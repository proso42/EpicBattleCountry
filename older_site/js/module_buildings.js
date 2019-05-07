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
	var city_id;
	var next_level;
	var type;
	var building_id;
	var building_name;
	console.log('Starting');
	var request1 = `SELECT * FROM waiting_buildings WHERE id = ${id}`;
	mysqlClient.query(request1, function (error, results){
		if (error)
		{
			console.log(error);
			mysqlClient.end();
			return -1;
		}
		if (results == null || results.length == 0)
		{
			mysqlClient.end();
			return 0;
		}
		city_id = results[0]['city_id'];
		next_level = results[0]['next_level'];
		type = results[0]['type'];
		building_id = results[0]['building_id'];
		var request2 = `SELECT name FROM ${type} WHERE id = ${building_id}`;
		console.log(`city_id : ${city_id} | next_level = ${next_level} | type : ${type} | building_id : ${building_id}`)
		mysqlClient.query(request2, function (error, results2){
			building_name = results2[0]['name'].replace(/\s/gi, "_");
			var request3 = `UPDATE cities_buildings SET ${building_name} = ${next_level} WHERE city_id = ${city_id}`;
			console.log(`building_name : ${building_name}`);
			mysqlClient.query(request3, function (error, results3){
				if (error)
				{					
					console.log('error UPDATE');
					mysqlClient.end();
					return -1;
				}
				else
					console.log('success UPDATE');
				var request4 = `DELETE FROM waiting_buildings WHERE id = ${id}`;
				mysqlClient.query(request4, function (error, results4){
					if (error)
					{
						console.log('error DELETE');
						mysqlClient.end();
						return -1;
					}
					else
						console.log('success DELETE');
					if (type == "eco_buildings" || type == "religious_buildings")
					{
						console.log('eco_buildings')
						let request5 = `SELECT prod_type, basic_prod, raised_prod FROM ${type} WHERE id = ${building_id}`;
						console.log(`r5 : ${request5}`);
						mysqlClient.query(request5, function (error, results5){
							if (error)
							{
								console.log('error r5 : ' + error);
								mysqlClient.end();
								return -1;
							}
							else
							{
								console.log('select ok');

								console.log('prod_type : ' + results5[0]['prod_type']);
								if (results5[0]['prod_type'] > 0)
								{
									console.log('prod > 0');
									var prod_type = results5[0]['prod_type'];
									var basic_prod = results5[0]['basic_prod'];
									var raised_prod = results5[0]['raised_prod'];
									if (prod_type == 1)
										var p1 = update_food_prod(next_level, basic_prod, raised_prod, city_id);
									else if (prod_type == 2)
										var p1 = update_wood_prod(next_level, basic_prod, raised_prod, city_id);
									else if (prod_type == 3)
										var p1 = update_rock_prod(next_level, basic_prod, raised_prod, city_id);
									else if (prod_type == 4)
										var p1 = update_steel_prod(next_level, basic_prod, raised_prod, city_id);
									else if (prod_type == 5)
										var p1 = update_gold_prod(next_level, basic_prod, raised_prod, city_id);
									else if (prod_type == 6) 
										var p1 = update_food_stock(next_level, basic_prod, raised_prod, city_id);
									else if (prod_type == 7) 
										var p1 = update_wood_stock(next_level, basic_prod, raised_prod, city_id);
									else if (prod_type == 8) 
										var p1 = update_rock_stock(next_level, basic_prod, raised_prod, city_id);
									else if (prod_type == 9) 
										var p1 = update_steel_stock(next_level, basic_prod, raised_prod, city_id);
									else if (prod_type == 10) 
										var p1 = update_gold_stock(next_level, basic_prod, raised_prod, city_id);
									else if (prod_type == 11) 
										var p1 = update_mount_prod(next_level, basic_prod, raised_prod, city_id);
									else if (prod_type == 12)
										var p1 = update_mount_stock(next_level, basic_prod, raised_prod, city_id);
									else if (prod_type == 13)
										var p1 = update_faith_prod(next_level, basic_prod, raised_prod, city_id);
									else if (prod_type == 14)
										var p1 = update_faith_stock(next_level, basic_prod, raised_prod, city_id);
									Promise.all([p1]).then(() => {console.log('fin ok');mysqlClient.end();return (0)}).catch((err) => {console.log(`fin error : ${err}`);mysqlClient.end();return -1});
								}
							}

						});
					}
				});
			});
		});
	});

	function update_faith_prod(next_level, basic_prod, raised_prod, city_id)
	{
		console.log('faith');
		return new Promise((resolve, reject) => {
			let request = 'UPDATE cities SET faith_prod = cities.faith_prod + ';
			if (next_level == 1)
				request += `${basic_prod}`;
			else
				request += `${raised_prod}`;
			request += ` WHERE id = ${city_id}`;
			mysqlClient.query(request, function (err, ret){
				if (err)
					reject(err);
				else
					resolve();
			});
		});
	}

	function update_food_prod(next_level, basic_prod, raised_prod, city_id)
	{
		console.log('food');
		return new Promise((resolve, reject) => {
			let prod_request = `SELECT food_prod FROM cities WHERE id = ${city_id}`;
			console.log(prod_request);
			mysqlClient.query(prod_request, function (error, ret1){
				console.log('laaaaaaaaaaaaaaaaaaaaaaaa');
				if (error)
				{
					console.log(error);
					reject(error);
				}
				else
				{
					console.log(`food_prod : ${ret1[0]['food_prod']}`);
					if (next_level > 1)
						var food_prod = ret1[0]['food_prod'] - Math.trunc(basic_prod * Math.pow(raised_prod, next_level - 2));
					else
						var food_prod = ret1[0]['food_prod'];
					console.log(`food_prod after : ${food_prod}`);
					var new_food_prod = Math.trunc(basic_prod * Math.pow(raised_prod, next_level - 1)) + food_prod;
					let food_request = `UPDATE cities SET food_prod = ${new_food_prod} WHERE id = ${city_id}`;
					console.log(food_request);
					mysqlClient.query(food_request, function (error2, ret){
						if (error2)
						{
							console.log(`error2 : ${error2}`)
							reject(error2);
						}
						else
							resolve();
					});
				}
			});
		});
	}

	function update_wood_prod(next_level, basic_prod, raised_prod, city_id)
	{
		console.log('wood');
		return new Promise((resolve, reject) => {
			let new_wood_prod = Math.trunc(basic_prod * Math.pow(raised_prod, next_level - 1)) + 8;
			let wood_request = `UPDATE cities SET wood_prod = ${new_wood_prod} WHERE id = ${city_id}`;
			mysqlClient.query(wood_request, function (error, ret){
				if (error)
					reject();
				else
					resolve();
			});
		});
	}

	function update_rock_prod(next_level, basic_prod, raised_prod, city_id)
	{
		console.log('rock');
		return new Promise((resolve, reject) => {
			let new_rock_prod = Math.trunc(basic_prod * Math.pow(raised_prod, next_level - 1)) + 5;
			let rock_request = `UPDATE cities SET rock_prod = ${new_rock_prod} WHERE id = ${city_id}`;
			mysqlClient.query(rock_request, function (error, ret){
				if (error)
					reject();
				else
					resolve();
			});
		});
	}

	function update_steel_prod(next_level, basic_prod, raised_prod, city_id)
	{
		console.log('steel');
		return new Promise((resolve, reject) => {
			let new_steel_prod = Math.trunc(basic_prod * Math.pow(raised_prod, next_level - 1)) + 1;
			let steel_request = `UPDATE cities SET steel_prod = ${new_steel_prod} WHERE id = ${city_id}`;
			mysqlClient.query(steel_request, function (error, ret){
				if (error)
					reject();
				else
					resolve();
			});
		});
	}

	function update_gold_prod(next_level, basic_prod, raised_prod, city_id)
	{
		console.log('gold');
		return new Promise((resolve, reject) => {
			let new_gold_prod = Math.trunc(basic_prod * Math.pow(raised_prod, next_level - 1));
			let gold_request = `UPDATE cities SET gold_prod = ${new_gold_prod} WHERE id = ${city_id}`;
			mysqlClient.query(gold_request, function (error, ret){
				if (error)
					reject();
				else
					resolve();
			});
		});
	}

	function update_food_stock(next_level, basic_prod, raised_prod, city_id)
	{
		console.log('food stock');
		return new Promise((resolve, reject) => {
			let new_max_food = Math.trunc(basic_prod * Math.pow(raised_prod, next_level - 1));
			let food_request = `UPDATE cities SET max_food = ${new_max_food} WHERE id = ${city_id}`;
			mysqlClient.query(food_request, function (error, ret){
				if (error)
					reject();
				else
					resolve();
			});
		});
	}

	function update_wood_stock(next_level, basic_prod, raised_prod, city_id)
	{
		console.log('wood stock');
		return new Promise((resolve, reject) => {
			let new_max_wood = Math.trunc(basic_prod * Math.pow(raised_prod, next_level - 1));
			let wood_request = `UPDATE cities SET max_wood = ${new_max_wood} WHERE id = ${city_id}`;
			mysqlClient.query(wood_request, function (error, ret){
				if (error)
					reject();
				else
					resolve();
			});
		});
	}

	function update_rock_stock(next_level, basic_prod, raised_prod, city_id)
	{
		console.log('rock stock');
		return new Promise((resolve, reject) => {
			let new_max_rock = Math.trunc(basic_prod * Math.pow(raised_prod, next_level - 1));
			let rock_request = `UPDATE cities SET max_rock = ${new_max_rock} WHERE id = ${city_id}`;
			mysqlClient.query(rock_request, function (error, ret){
				if (error)
					reject();
				else
					resolve();
			});
		});
	}

	function update_steel_stock(next_level, basic_prod, raised_prod, city_id)
	{
		console.log('steel stock');
		return new Promise((resolve, reject) => {
			let new_max_steel = Math.trunc(basic_prod * Math.pow(raised_prod, next_level - 1));
			let steel_request = `UPDATE cities SET max_steel = ${new_max_steel} WHERE id = ${city_id}`;
			mysqlClient.query(steel_request, function (error, ret){
				if (error)
					reject();
				else
					resolve();
			});
		});
	}

	function update_gold_stock(next_level, basic_prod, raised_prod, city_id)
	{
		console.log('gold stock');
		return new Promise((resolve, reject) => {
			let new_max_gold = Math.trunc(basic_prod * Math.pow(raised_prod, next_level - 1));
			let gold_request = `UPDATE cities SET max_gold = ${new_max_gold} WHERE id = ${city_id}`;
			mysqlClient.query(gold_request, function (error, ret){
				if (error)
					reject();
				else
					resolve();
			});
		});
	}


	function update_mount_prod(next_level, basic_prod, raised_prod, city_id)
	{
		console.log('mount');
		return new Promise((resolve, reject) => {
			let new_mount_prod = Math.trunc(basic_prod * Math.pow(raised_prod, next_level - 1));
			let mount_request = `UPDATE cities SET mount_prod = ${new_mount_prod} WHERE id = ${city_id}`;
			mysqlClient.query(mount_request, function (error, ret){
				if (error)
					reject();
				else
					resolve();
			});
		});
	}


	function update_mount_stock(next_level, basic_prod, raised_prod, city_id)
	{
		console.log('mount stock');
		return new Promise((resolve, reject) => {
			let new_max_mount = Math.trunc(basic_prod * Math.pow(raised_prod, next_level - 1));
			let mount_request = `UPDATE cities SET max_mount = ${new_max_mount} WHERE id = ${city_id}`;
			mysqlClient.query(mount_request, function (error, ret){
				if (error)
					reject();
				else
					resolve();
			});
		});
	}

	function update_faith_stock(next_level, basic_prod, raised_prod, city_id)
	{
		console.log('faith stock');
		return new Promise((resolve, reject) => {
			let faith_request = 'UPDATE cities SET max_faith = cities.max_faith + ';
			if (next_level == 1)
				faith_request += basic_prod;
			else
				faith_request += raised_prod;
			faith_request += ` WHERE id = ${city_id}`;
			mysqlClient.query(faith_request, function (error, ret){
				if (error)
					reject();
				else
					resolve();
			});
		});
	}

};
