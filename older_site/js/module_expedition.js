module.exports.start = function (id)
{
	var fs = require('fs');
	var mysql = require('mysql');
	let env_file = fs.readFileSync("../src/site/.env", 'utf8').split('\n');
	var db_user = '';
	var db_password = '';
	env_file.forEach(function (e){
		let e_split = e.split('=');
		if (e_split[0] == 'DB_USERNAME')
			db_user = e_split[1];
		else if (e_split[0] == 'DB_PASSWORD')
			db_password = e_split[1];
	});
	var mysqlClient = mysql.createConnection({
		host		:	"localhost",
		user		:	db_user,
		password	:	db_password,
		database	:	"epicbattlecorp"
	});
	console.log('Starting');
	var request1 = `SELECT * FROM traveling_units WHERE id = ${id}`;
	console.log("request1 : " + request1);
	mysqlClient.query(request1, function (error, results)
		{
			if (error)
			{
				console.log(error);
				mysqlClient.end();
				return -1;
			}
			if (results == null || results.length == 0)
			{
				console.log(`id : ${id} not found inside database. Exit normaly.`);
				mysqlClient.end();
				return 0;
			}
			var city_id = results[0]['city_id'];
			var owner = results[0]['owner'];
			var ending_point = (results[0]['ending_point']).split('/');
			var starting_point = (results[0]['starting_point']).split('/');
			var finishing_date = results[0]['finishing_date'];
			var traveling_duration = results[0]['traveling_duration'];
			var dest_x = ending_point[0];
			var dest_y = ending_point[1];
			var ori_x = starting_point[0];
			var ori_y = starting_point[1];
			var mission = results[0]['mission'];
			var units = results[0]['units'];
			var request2 = `SELECT type FROM map WHERE x_pos = ${dest_x} AND y_pos = ${dest_y}`;
			console.log("request 2 : " + request2);
			mysqlClient.query(request2, function (error, results2){
				var type = "";
				if (results2 == null || results2.length == 0)
				{
					type = "empty";
				}
				else
					type = results2[0]['type'];
				if (mission == 1)
				{
					// Scouting
					if (type == "empty")
						var text_notif = "Votre exploration en " + dest_x + "/" + dest_y + " n'a rien donnee";
					else
						var text_notif = "Le lieu suivant a ete decouvert en " + dest_x + "/" + dest_y + " : " + type;
					let title = "Rapport d'exploration";
					let p0 = send_notif(text_notif, title, owner, city_id, finishing_date);
					Promise.all([p0]).then(() => {
						mysqlClient.query(`DELETE FROM traveling_units WHERE id = ${id}`, function (err, ret){
							if (err)
							{
								console.log(err);
								mysqlClient.end();
								return -1;
							}
							else
							{
								finishing_date += traveling_duration;
								let req = `INSERT INTO traveling_units (city_id, owner, starting_point, ending_point, units, traveling_duration, finishing_date, mission, flag) VALUES (${city_id}, ${owner}, '${dest_x + "/" + dest_y}', '${ori_x + "/" + ori_y}', '${units}', ${traveling_duration}, ${finishing_date}, 6, 0)`;
								mysqlClient.query(req, function (err, ret){
									if (err)
									{
										console.log(err);
										mysqlClient.end();
										return -1;
									}
									else
									{										
										mysqlClient.end();
										return 0;
									}
								});
							}
						});
					}).catch((err) => {
						console.log(err);
						mysqlClient.end();
						return -1;
					});
				}
				else if (mission == 2)
				{
					// Raid Dungeon

					// On vérifie qu'il y a toujours un donjon à cet emplacement
					let check_dungeon = `SELECT id FROM map WHERE x_pos = ${dest_x} AND y_pos = ${dest_y} AND type = 'city'`;
					console.log(check_dungeon);
					mysqlClient.query(check_dungeon, function(error, result){
						if (error)
						{
							console.log(error)
							mysqlClient.end();
							return -1;
						}
						else if (result == null)
						{
							// rien à cet endroit. On envoie un message comme quoi le raid à échouer et on renvoie le scout chez lui
							console.log("Pas de donjon ici");
							let title = "Echec du raid";
							let text_notif = `Aucun donjon n'a été trouvé en ${dest_x}/${dest_y}.`;
							let p0 = send_notif(text_notif, title, owner, city_id, finishing_date);
							Promise.all([p0]).then(() => {
								let delete_request = `DELETE FROM traveling_units WHERE id =  ${id}`;
								mysqlClient.query(delete_request, function(error, result){
									if (error)
									{
										console.log(error)
										mysqlClient.end();
										return -1;
									}
									else
									{
										finishing_date += traveling_duration;
										let return_travel = `INSERT INTO traveling_units (city_id, owner, starting_point, ending_point, units, traveling_duration, finishing_date, mission, flag) VALUES (${city_id}, ${owner}, ${ending_point}, ${starting_point}, ${units}, ${traveling_duration}, ${finishing_date}, 6, 0)`;
										mysqlClient.query(return_travel, function(error, result){
											if (error)
											{
												console.log(error);
												mysqlClient.end();
												return -1;
											}
											else
											{
												console.log(result);
												mysqlClient.end();
												return 0;
											}
										});
									}
								});
							}).catch((err) => {
								console.log(err);
								mysqlClient.end();
								return -1;
							});
						}
						else
						{
							// il y a bien un donjon à cet endroit. On envoie un message et on commence la quete
							console.log('Donjon atteint !');
							let title = "Donjon atteint";
							let text_notif = `Une unitée attend vos ordres à l'entrée du donjon [${ending_point}].`;
							let p0 = send_notif(text_notif, title, owner, city_id, finishing_date);
							Promise.all([p0]).then(() => {
								let delete_request = `DELETE FROM traveling_units WHERE id =  ${id}`;
								mysqlClient.query(delete_request, function(error, result){
									if (error)
									{
										console.log(error)
										mysqlClient.end();
										return -1;
									}
									else
									{
										let create_new_dungeon_quest = `INSERT INTO city_quests (city_id, type, scenario, coord, life) VALUES (${city_id}, 1, 1, "${ending_point}", 3)`;
										mysqlClient.query(create_new_dungeon_quest, function (error, result){
											if (error)
											{
												console.log(error)
												mysqlClient.end();
												return -1;
											}
											else
											{
												console.log(result)
												mysqlClient.end();
												return 0;
											}
										});
									}
								});
							}).catch((err) => {
								console.log(err);
								mysqlClient.end();
								return -1;
							});
						}
					});
				}
				else if (mission == 4)
				{
					// Colonize
					if (type == "empty")
					{
						var check_range_request = `SELECT x_pos, y_pos FROM map WHERE x_pos >= ${dest_x} - 5 AND x_pos <= ${dest_x} + 5 AND y_pos >= ${dest_y} - 5 AND y_pos <= ${dest_y} + 5 AND (x_pos != ${ori_x} OR y_pos != ${ori_y}) AND type = "city" LIMIT 1`;
						console.log(check_range_request);
						mysqlClient.query(check_range_request, function (error, results_range)
							{
								if (error)
								{
									console.log(error)
									mysqlClient.end();
									return -1;
								}
								if (results_range == null || results_range.length == 0)
								{
									console.log("aucune ville dans la range");
									let p0 = add_new_city(dest_x, dest_y, owner);
									Promise.all([p0]).then(() => {
										let text_notif = "Votre équipe a construit une nouvelle ville en " + dest_x + "/" + dest_y + " ! Choisissez un nom pour cette ville.";
										let title = "Succès de colonisation";
										let p1 = send_notif(text_notif, title, owner, city_id, finishing_date);
										Promise.all([p1]).then(() => {
											let delete_request = `DELETE FROM traveling_units WHERE id = ${id}`;
											console.log("delete request : " + delete_request);
											mysqlClient.query(delete_request, function (error, ret)
												{
													if (error)
													{
														console.log(error);
														mysqlClient.end();
														return -1;;
													}
													else
													{
														console.log("process ok");
														mysqlClient.end();
														return 0;
													}
												});
										}).catch((err) => {
											console.log(err);
											mysqlClient.end();
											return -1;
										});
									}).catch((err) => {
										console.log(err);
										mysqlClient.end();
										return -1;
									});
								}
								else
								{
									let text_notif = "Votre equipe n'a pas pu construire de ville en " + dest_x + "/" + dest_y + " car une autre ville est trop proche de cet endroit ! Les ressources ont ete perdues. L'equipe est sur le chemin du retour.";
									let title = "Echec de colonisation";
									let p0 = send_notif(text_notif, title, owner, city_id, finishing_date);
									Promise.all([p0]).then(() => {
										mysqlClient.query(`DELETE FROM traveling_units WHERE id = ${id}`, function (err, ret){
											if (err)
											{
												console.log(err);
												mysqlClient.end();
												return -1;
											}
											else
											{
												finishing_date += traveling_duration;
												let req = `INSERT INTO traveling_units (city_id, owner, starting_point, ending_point, units, traveling_duration, finishing_date, mission, flag) VALUES (${city_id}, ${owner}, '${dest_x + "/" + dest_y}', '${ori_x + "/" + ori_y}', '${units}', ${traveling_duration}, ${finishing_date}, 6, 0)`;
												mysqlClient.query(req, function (err, ret){
													if (err)
													{
														console.log(err);
														mysqlClient.end();
														return -1;
													}
													else
													{
														mysqlClient.end();
														return 0;
													}
												});
											}
										});
									}).catch((err) => {
										console.log(err);
										mysqlClient.end();
										return -1;
									});
								}

							});
					}
					else
					{
						console.log("location not empty");
						let text_notif = "Votre equipe n'a pas pu construire de ville en " + dest_x + "/" + dest_y + " car l'endroit n'est pas vide ! Les ressources ont ete perdues. L'equipe est sur le chemin du retour.";
						let title = "Echec de colonisation";
						let p0 = send_notif(text_notif, title, owner, city_id, finishing_date);
						Promise.all([p0]).then(() => {
							mysqlClient.query(`DELETE FROM traveling_units WHERE id = ${id}`, function (err, ret){
								if (err)
								{
									console.log(err);
									mysqlClient.end();
									return -1;
								}
								else
								{
									finishing_date += traveling_duration;
									let req = `INSERT INTO traveling_units (city_id, owner, starting_point, ending_point, units, traveling_duration, finishing_date, mission, flag) VALUES (${city_id}, ${owner}, '${dest_x + "/" + dest_y}', '${ori_x + "/" + ori_y}', '${units}', ${traveling_duration}, ${finishing_date}, 6, 0)`;
									mysqlClient.query(req, function (err, ret){
										if (err)
										{
											console.log(err);
											mysqlClient.end();
											return -1;
										}
										else
										{
											mysqlClient.end();
											return 0;
										}
									});
								}
							});
						}).catch((err) => {
							console.log(err);
							mysqlClient.end();
							return -1;
						});
					}
				}
				else if (mission == 5)
				{
					// Attack
					let mod_battle = require("./module_launch_battle");
					let p_attack = mod_battle.launch_battle(id);
					Promise.all([p_attack])
					.then(() => {
						mysqlClient.end();
						return 0;
					})
					.catch(() => {
						mysqlClient.end();
						return -1;
					});
				}
				else if (mission == 6)
				{
					// Go Home
					var request_go_home = `SELECT city_id, units, res_taken FROM traveling_units WHERE id = ${id}`;
					mysqlClient.query(request_go_home, function (error, results_go_home)
						{
							if (error)
							{
								console.log(error);
								mysqlClient.end();
								return -1;
							}
							else
							{
								let p1 = refound_units(results_go_home[0]['units'].split(';'), city_id);
								let p2;
								if (results_go_home[0]['res_taken'] == null)
									p2 = Promise.resolve();
								else
									p2 = refound_res(results_go_home[0]['res_taken'].split(';'), city_id);
								Promise.all([p1, p2]).then(() => {console.log('fin 6');mysqlClient.end();return 0;}).catch((err) =>{console.log(err);mysqlClient.end();return -1;});
							}
						});
				}
				else if (mission == 7)
				{
					// Move Units
					var request_move_units = `SELECT ending_point, units, res_taken FROM traveling_units WHERE id = ${id}`;
					mysqlClient.query(request_move_units, function (err, result_move_units)
					{
						if (err)
						{
							console.log(err);
							mysqlClient.end();
							return -1;
						}
						else
						{
							var units = result_move_units[0]['units'].split(';');
							var res_taken = result_move_units[0]['res_taken'];
							if (res_taken != null)
								res_taken = res_taken.split(';');
							let split = result_move_units[0]['ending_point'].split('/');
							let x_target = split[0];
							let y_target = split[1];
							let request_target_city = `SELECT id FROM cities WHERE x_pos = ${x_target} AND y_pos = ${y_target}`;
							mysqlClient.query(request_target_city, function (err, ret)
							{
								if (err)
								{
									console.log(err);
									mysqlClient.end();
									return -1;
								}
								else
								{
									var p = refound_units(units, ret[0]['id']);
									var p2 = refound_res(res_taken, ret[0]['id']);
									Promise.all([p, p2]).then(() => {console.log('fin 7');mysqlClient.end();return 0;}).catch((err) => {console.log(err);mysqlClient.end();return -1;});
								}
							});
						}
					});
				}
			});

			function add_new_city(x_pos, y_pos, owner)
			{
				console.log("Make new city");
				return new Promise((resolve, reject) =>
					{
						let race_request = `SELECT race FROM users WHERE id = ${owner}`;
						let p0 = get_rdm_name();
						Promise.all([p0])
						.then((result) => {
							let new_rdm_name = result[0];
							console.log("race request : " + race_request);
							mysqlClient.query(race_request, function (error, ret)
							{
								if (error)
									reject(error)
								var user_race = ret[0]['race'];
								let city_request = `INSERT INTO cities (name, owner, x_pos, y_pos, is_capital) VALUES ("${new_rdm_name}", ${owner}, ${x_pos}, ${y_pos}, 0)`;
								console.log("city request : " + city_request);
								mysqlClient.query(city_request, function (error, ret)
									{
										if (error)
											reject(error);
										let id_request = `SELECT id FROM cities WHERE owner = ${owner} AND x_pos = ${x_pos} AND y_pos = ${y_pos}`;
										mysqlClient.query(id_request, function (error, ret)
											{
												if (error)
													reject(error)
												var new_city_id = ret[0]['id'];
												let building_request = "";
												if (user_race == 1)
													building_request = `INSERT INTO cities_buildings (city_id, owner, Likornerie, Bergerie, Loufterie, Douves_de_lave, Fosse_cachee, Arbre_de_la_Vie, Temple_de_la_Vie, Statue_du_Dieu_Nain, Temple_de_la_Montagne, Statue_engloutie_de_la_Mort, Temple_de_la_Mort, Skull_wall) VALUES (${new_city_id}, ${owner}, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1)`;
												else if (user_race == 2)
													building_request = `INSERT INTO cities_buildings (city_id, owner, Ecurie, Bergerie, Loufterie, Douves_de_lave, Statue_de_la_Guerre, Temple_de_la_Guerre, Statue_du_Dieu_Nain, Temple_de_la_Montagne, Statue_engloutie_de_la_Mort, Temple_de_la_Mort, Sanctuaire, Skull_wall) VALUES (${new_city_id}, ${owner}, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1)`;
												else if (user_race == 3)
													building_request = `INSERT INTO cities_buildings (city_id, owner, Champs, Ecurie, Likornerie, Loufterie, Douves_de_lave, Fosse_cachee, Statue_de_la_Guerre, Temple_de_la_Guerre, Arbre_de_la_Vie, Temple_de_la_Vie, Statue_engloutie_de_la_Mort, Temple_de_la_Mort, Sanctuaire, Skull_wall) VALUES (${new_city_id}, ${owner}, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1)`;
												else
													building_request = `INSERT INTO cities_buildings (city_id, owner, Champs, Ecurie, Likornerie, Bergerie, Mur_basique, Fosse_cachee, Statue_de_la_Guerre, Temple_de_la_Guerre, Arbre_de_la_Vie, Temple_de_la_Vie, Statue_du_Dieu_Nain, Temple_de_la_Montagne, Sanctuaire) VALUES (${new_city_id}, ${owner}, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1)`;
												console.log("building request : " + building_request);
												mysqlClient.query(building_request, function (error, ret)
													{
														if (error)
															reject(error);
														let tech_request = "";
														if (user_race == 1)
															tech_request = `INSERT INTO cities_techs (city_id, owner, Esclavage) VALUES (${new_city_id}, ${owner}, -1)`
														else if (user_race == 2)
															tech_request = `INSERT INTO cities_techs (city_id, owner, Esclavage) VALUES (${new_city_id}, ${owner}, -1)`
														else if (user_race == 3)
															tech_request = `INSERT INTO cities_techs (city_id, owner, Esclavage) VALUES (${new_city_id}, ${owner}, -1)`
														else
															tech_request = `INSERT INTO cities_techs (city_id, owner) VALUES (${new_city_id}, ${owner})`
														console.log("tech request : " + tech_request)
														mysqlClient.query(tech_request, function (error, ret)
															{
																if (error)
																	reject(error);
																let unit_request = `INSERT INTO cities_units (city_id, owner) VALUES (${new_city_id}, ${owner})`;
																mysqlClient.query(unit_request, function (error, ret)
																	{
																		if (error)
																			reject(error);
																		let map_request = `INSERT INTO map (x_pos, y_pos, type, icon) VALUES (${x_pos}, ${y_pos}, "city", "fa-city")`;
																		console.log("map_request : " + map_request);
																		mysqlClient.query(map_request, function (error, ret)
																			{
																				if (error)
																					reject(error);
																				console.log("End of Promise Hell :)");
																				resolve();
																			});
																	});
															});
													});
											});
									});
							});
						});
					});
			}

			function send_notif(text, title, owner, city_id, finishing_date)
			{
				console.log("Send Notification");
				return new Promise((resolve, reject) => 
					{
						var notif_request = `INSERT INTO messages (sender, target, target_city, title, content, sending_date) VALUES ('notification', ${owner}, ${city_id}, "${title}", "${text}", ${finishing_date})`;
						console.log(notif_request);
						mysqlClient.query(notif_request, function (error, ret)
							{
								if (error)
									reject(error)
								else
									resolve();
							});
					});
			}

			function refound_units(units, city_id)
			{
				console.log("Refound");
				return new Promise((resolve, reject) => {
					console.log("Promise unit");
					let unit_request = "SELECT name FROM units";
					console.log(unit_request);
					mysqlClient.query(unit_request, function (error, ret){
						if (error)
						{
							console.log(`Ici : ${error}`);
							process.exit();
						}
						console.log("unit request OK");
						var refound_request = "UPDATE cities_units SET";
						for (let i = 0; i < units.length ;i++)
						{
							console.log(`Boucle : ${i}`);
							let unit_info = units[i].split(':');
							let unit_id = unit_info[0];
							let unit_quantity = unit_info[1];
							let unit_name = ret[unit_id - 1]['name'].replace('/\s/', "_");
							if (i > 0)
								refound_request += ` , ${unit_name} = cities_units.${unit_name} + ${unit_quantity}`;
							else
								refound_request += ` ${unit_name} = cities_units.${unit_name} + ${unit_quantity}`;
						}
						refound_request += ` WHERE city_id = ${city_id}`;
						console.log(`refound_request : ${refound_request}`);
						mysqlClient.query(refound_request, function (error2, ret){
							if (error2)
							{
								console.log(error2);
								reject(error2);
							}
							else
							{
								console.log("OK");
								let delete_request = `DELETE FROM traveling_units WHERE id = ${id}`;
								console.log(`delete_request : ${delete_request}`);
								mysqlClient.query(delete_request, function (error3, ret) {
									if (error3)
										reject(error3)
									else
										resolve();
								});
							}
						});
					});
				});
			}

			function refound_res(res, city_id)
			{
				return new Promise((resolve, reject) => {
					if (res == null)
						resolve();
					console.log('Promise res');
					mysqlClient.query(`SELECT food, wood, rock, steel, gold, max_food, max_wood, max_rock, max_steel, max_gold FROM cities WHERE id = ${city_id}`, function (err, ret){
						if (err)
							reject(err);
						else
						{
							let refound_request_res = "UPDATE cities SET ";
							for (let i = 0; i < res.length ;i++)
							{
								console.log(`Boucle res : ${i}`);
								let split = res[i].split(':');
								let key = split[0];
								let val = split[1];
								if (val + ret[0][key] > ret[0]["max_" + key])
									val = ret[0]["max_" + key];
								if (i == 0)
									refound_request_res += `${key} = cities.${key} + ${val}`;
								else
									refound_request_res += ` , ${key} = cities.${key} + ${val}`;
							}
							refound_request_res += ` WHERE id = ${city_id}`;
							console.log(`refound_request_res : ${refound_request_res}`);
							mysqlClient.query(refound_request_res, function (err, ret){
								if (err)
									reject(err);
								else
									resolve();
							});
						}
					});
				});
			}

			function get_rdm_name()
			{
				return new Promise((resolve, reject) => {
					let rdm_name = "";
					let con = ['b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'm', 'n', 'p', 'q', 'r', 's', 't', 'v', 'w', 'x', 'z'];
					let voy = ['a', 'e', 'i', 'o', 'u', 'y'];
					let max = Math.floor(Math.random() * Math.floor(12));
					while (max < 3)
						max = Math.floor(Math.random() * Math.floor(12));
					while (max > 0)
					{
						let rdm = Math.floor(Math.random() * Math.floor(3));
						if (rdm == 2)
							rdm_name = rdm_name.concat(voy[Math.floor(Math.random() * Math.floor(6))]);
						else
							rdm_name = rdm_name.concat(con[Math.floor(Math.random() * Math.floor(20))]);
						max--;
					}
					resolve(rdm_name);
				});
			}

		});
};
