module.exports.launch_battle = function (id)
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
            host            :       "localhost",
            user            :       db_user,
            password        :       db_password,
            database        :       "epicbattlecorp"
    });
    mysqlClient.query(`SELECT * FROM traveling_units WHERE id = ${id}`, function (err, ret){
        if (err)
        {
            console.log(err)
            mysqlClient.end();
            return -1;
        }
        else
        {
            //console.log("debut");
            let city_id = ret[0]['city_id'];
            let units = ret[0]['units'];
            let split = (ret[0]['ending_point']).split("/");
            let x_target = split[0];
            let y_target = split[1];
            mysqlClient.query(`SELECT * FROM cities WHERE x_pos = ${x_target} AND y_pos = ${y_target}`, function (err, ret){
                if (err)
                {
                    mysqlClient.end();
                    console.log(err);
                    return -1;
                }
                if (ret == null || ret.length == 0)
                {
                    // target is not a city
                    let p0 = no_battle(ret[0]);
                    Promise.all([p0])
                    .then(() => {
                        console.log('Battle canceled');
                    })
                    .catch((err) => {
                        console.log(err);
                    })
                    .finally(() =>{
                        mysqlClient.end();
                        return 0;
                    });
                }
                let target_city = ret[0];
                let p0 = get_A_units(city_id, units);
                let p1 = get_D_units(target_city['id']);
                let p2 = get_D_builds(target_city['id']);
                Promise.all([p0, p1, p2])
                .then((result) => {
                    //console.log("then");
                    console.log("Attaquant : ")
                    console.log(result[0]);
                    console.log("Defenseurs : ")
                    console.log(result[1]);
                    console.log("Batiments : ")
                    console.log(result[2]);
                    let mod_battle = require('./module_battle');
                    let result_battle = mod_battle.start_battle(result[2], result[1], result[0]);
                    console.log(result_battle);
                    let winner = result_battle["winner"];
                    let p10 = update_build(result_battle["D_defenses"], target_city);
                    let p11 = update_Dunit(result_battle["D_troopers"], target_city, winner);
                    let p12 = update_Aunit(result_battle["A_troopers"], winner, result[0], target_city);
                    Promise.all([p10, p11, p12])
                    .then(() =>{
                        console.log('Fin de la bataille');
                    })
                    .catch((err) =>{
                        console.log(err);
                    })
                    .finally(() =>{
                        mysqlClient.end();
                        return 0;
                    })
                })
                .catch((err) =>{
                    console.log(err);
                    mysqlClient.end();
                    return -1;
                })
            });
        }
    });

    function no_battle(data)
    {
        return new Promise((reslve, reject) => {
            let title = "Assault annulé";
            let text = `Il n'y avait pas de ville à attaquer en ${data['ending_point']}. Nos troupes sont sur le chemin du retour.`;
            mysqlClient.query(`INSERT INTO messages (sender, target, target_city, title, content, sending_date) VALUES ('notification', ${data['owner']}, ${data['city_id']}, '${title}', '${text}', ${Date.now()})`, function (err, ret){
                if (err)
                    reject(err);
                else
                {
                    mysqlClient.query(`DELETE FROM traveling_units WHERE id = ${id}`, function (err, ret){
                        if (err)
                            reject(err);

                        else
                        {
                            mysqlClient.query(`INSERT INTO traveling_units (city_id, owner, starting_point, ending_point, units, traveling_duration, finishing_date, mission) 
                            VALUES (${data['city_id']}, ${data['owner']}, '${data['ending_point']}', '${data['starting_point']}', '${data['units']}', ${data['traveling_duration']}, ${data['finishing_date'] + data['traveling_duration']}), 6`, function (err, ret){
                                if (err)
                                    reject(err);
                                else
                                    resolve();
                            });
                        }
                    });
                }
            });
        });
    }

    function update_Dunit(unit_obj, target_city, winner)
    {
        // la key c'est l'id et la val c'est la quantity
        return new Promise((resolve, reject) => {
            let remaining_units = remove_deaths(unit_obj);
            mysqlClient.query("SELECT id, name FROM units", function (err, ret){
                let request = "UPDATE cities_units SET"
                let flag = 0;
                for (var key in ret)
                {
                    if (remaining_units.hasOwnProperty(ret[key]['id']))
                    {
                        if (flag == 0)
                        {
                            request += ` ${ret[key]['name']} = ${remaining_units[ret[key['id']]]}`;
                            flag = 1;
                        }
                        else
                            request += `, ${ret[key]['name']} = ${remaining_units[ret[key['id']]]}`;
                    }
                }
                request += ` WHERE city_id = ${target_city['id']}`;
                mysqlClient.query(request, function (err, ret){
                    if (err)
                        reject(err);
                    else
                    {
                        let title = "Assault énemie - ";
                        let text = `Une armée étrangère à attaquer notre cité ${target_city['name']} ! `;
                        if (winner == "A")
                        {
                            text += "Malheureusement nos troupes n'ont pas réussis à repousser les assaillants. Toutes les unitées présentes dans la ville ont été tués. L'énemie à également piller des ressources de la ville et fait des prisonniers !";
                            title += "Victoire"
                        }
                        else
                        {
                            text += "Fort heureusement nos troupes ont réussis à vaincre tout les assaillants !"
                            title += "Défaite"
                        }
                        mysqlClient.query(`INSERT INTO messages (sender, target, target_city, title, content, sending_date) VALUES ('notification', ${target_city['owner']}, ${target_city['id']}, '${title}', '${text}', ${Date.now()})`, function (err, ret){
                            if (err)
                                reject(err);
                            else
                                resolve();
                        });
                    }
                })
            });
        });
    }

    function update_build(build_obj, target_city)
    {
        return new Promise((resolve, reject) => {
            mysqlClient.query("SELECT defenses.life, defenses.building_id, army_buildings.name FROM defenses INNER JOIN army_buildings ON defenses.building_id = army_buildings.id", function (err, ret){
                if (err)
                    reject(err);
                else
                {
                    build_stats = {};
                    for (var key in ret)
                        build_stats[ret[key]['name'].replace(/\s/gi, "_")] = {"life":parseInt(ret[key]['life']), "id":parseInt(ret[key]['building_id'])};
                    let request_update = "UPDATE cities_buildings SET";
                    let request_delete = `DELETE FROM waiting_buildings WHERE city_id = ${target_city['id']} AND type = army_buildings AND (`;
                    let flag_u = 0;
                    let flag_d = 0;
                    for (var key in build_obj)
                    {
                        let new_lvl = Math.trunc(build_obj[key]['life'] / build_stats[key]['life']);
                        if (flag_u == 0)
                        {
                            request_update += ` ${key} = ${new_lvl}`;
                            flag_u = 1;
                        }
                        else
                            request_update += `, ${key} = ${new_lvl}`;
                        if (flag_d == 0)
                        {
                            request_delete += `building_id = ${build_stats[key]['id']}`;
                            flag_d = 1;
                        }
                        else
                            request_delete += ` OR building_id = ${build_stats[key]['id']}`;
                    }
                    request_update += ` WHERE city_id = ${target_city['id']}`;
                    request_delete += ")";
                    mysqlClient.query(request_delete, function (err, ret){
                        if (err)
                            reject(err);
                        else
                        {
                            mysqlClient.query(request_update, function (err, ret){
                                if (err)
                                    reject(err);
                                else
                                    resolve();
                            });
                        }
                    });
                }
            });
        });
    }

    function update_Aunit(unit_obj, winner, data, target_city)
    {
        return new Promise((resolve, reject) => {
            if (winner == "A")
            {
                var p0 = rob_res(unit_obj, target_city);
                var p1 = calc_traveling_duration(unit_obj, data, x_pos, y_pos);
                var p2 = remove_deaths(unit_obj);
                Promise.all([p0, p1, p2])
                .then((result) => {
                    let robed_res = JSON.stringify(result[0]).replace(/[{}"]/gi, '').replace(/,/gi,";");
                    let traveling_duration = result[1];
                    let finishing_date = Date.now() + traveling_duration;
                    let units = JSON.stringify(result[2]).replace(/[{}"]/gi, '').replace(/,/gi,";");
                    mysqlClient.query(`DELETE FROM traveling_units WHERE id = ${id}`, function (err, ret){
                        if (err)
                            reject(err)
                        else
                        {
                            mysqlClient.query(`INSERT INTO traveling_units (city_id, owner, starting_point, ending_point, units, res_taken, traveling_duration, finishing_date, mission) VALUES (${data['city_id']}, ${data['owner']}, '${data['ending_point']}', '${data['starting_point']}', '${units}', '${robed_res}', ${traveling_duration}, ${finishing_date}, 6)`, function (err, ret){
                                if (err)
                                    reject(err);
                                else
                                {
                                    robed_res = result[0];
                                    let title = "Succes de l'assault";
                                    let text = `Notre attaque contre la ville ${target_city['name']} en ${x_pos}/${y_pos} est une réussite ! `;
                                    if (robed_res['food'] == 0 && robed_res['wood'] == 0 && robed_res['rock'] == 0 && robed_res['steel'] == 0 && robed_res['gold'] == 0)
                                        text += "Nos troupes n'ont pas pillé de ressources car les réserves de la villes étaient vides";
                                    else
                                    {
                                        text += "Nos troupes ont pillé :";
                                        for (var key in robed_res)
                                        {
                                            if (key == "captives" || robed_res[key] == 0)
                                                continue;
                                            text += ` ${key} x${robed_res[key]}`;
                                        }
                                    }
                                    text += `. Nos troupes ont fait ${robed_res['captives']} prisonniers. Il nous reste :`;
                                    for (var key_2 in unit_obj)
                                    {
                                        if (unit_obj[key_2][quantity] == 0)
                                            continue;
                                        else
                                            text += ` ${key_2} x${unit_obj[key_2]['quantity']}`;
                                    }
                                    text += ".Les troupes sont sur le chemin du retour.";
                                    mysqlClient.query(`INSERT INTO messages (sender, target, target_city, title, content, sending_date VALUES ('notification', ${data['owner']}, ${data['city_id']}, '${title}', '${text}', ${data['finishing_date']}))`, function (err, ret){
                                        if (err)
                                            reject(err);
                                        else
                                        {
                                            mysqlClient.query(`DELETE FROM traveling_units WHERE id = ${id}`, function (err, ret){
                                                if (err)
                                                    reject(err)
                                                else
                                                    resolve();
                                            });
                                        }
                                    });
                                }
                            });
                        }
                    });
                })
                .catch((err) => {
                    reject(err);
                })
            }
            else
            {
                let title = "Echec de l'assault";
                let text = `Notre attaque contre la ville ${target_city['name']} en ${x_pos}/${y_pos} s'est soldée par un echec. Toutes nos troupes on été vaincus.`;
                mysqlClient.query(`INSERT INTO messages (sender, target, target_city, title, content, sending_date VALUES ('notification', ${data['owner']}, ${data['city_id']}, '${title}', '${text}', ${data['finishing_date']}))`, function (err, ret){
                    if (err)
                        reject(err);
                    else
                    {
                        mysqlClient.query(`DELETE FROM traveling_units WHERE id = ${id}`, function (err, ret){
                            if (err)
                                reject(err)
                            else
                                resolve();
                        });
                    }
                });
            }
        });
    }

    function remove_deaths(unit_obj)
    {
        return new Promise((resolve, reject) => {
            let remaining_units = {};
            for (var key in unit_obj)
            {
                if (unit_obj[key]['quantity'] == 0)
                    continue ;
                else
                    remaining_units[unit_obj[key]['id']] = unit_obj[key]['quantity'];
            }
            resolve(remaining_units);
        });
    }

    function calc_traveling_duration(unit_obj, data, x, y)
    {
        return new Promise((resolve, reject) => {
            let min_speed = -1;
            for (var key in unit_obj)
            {
                if (unit_obj[key]['quantity'] > 0 && unit_obj[key]['speed'] > min_speed)
                    min_speed = unit_obj[key][speed];
            }
            if (min_speed < 0)
                reject("Error : negative speed");
            else
            {
                min_speed = 3600 / min_speed;
                let starting_coord = data['starting_point'].split("/");
                let x_target = starting_coord[0];
                let y_target = starting_coord[1];
                let traveling_duration = (Math.abs(x - x_target) + Math.abs(y - y_target) * min_speed);
                resolve(traveling_duration);
            }
        });
    }

    function rob_res(unit_obj, data)
    {
        return new Promise((resolve, reject) => {
            mysqlClient.query('SELECT name, storage FROM units', function (err, ret){
                if (err)
                    reject(err);
                else
                {
                    let storage = 0;
                    let robed_res = {"food":0, "wood":0, "rock":0, "steel":0, "gold":0, "captives":0};
                    for (var key in ret)
                    {
                        let unit_ref = ret[key]['name'];
                        if (unit_obj.hasOwnProperty(unit_ref) && unit_obj[unit_ref]['quantity'] > 0)
                            storage += (unit_obj[unit_ref]['quantity'] * ret[key]['storage']);
                    }
                    let part = Math.trunc(storage / 6);
                    for (var res in robed_res)
                    {
                        if (res != "captives")
                        {
                            if (data[res] < part)
                            {
                                robed_res[res] = data[res];
                                robed_res["captives"] += (part - data[res]);
                            }
                            else
                                robed_res[res] = part;
                        }   
                        else
                            robed_res["captives"] += part;
                    }
                    mysqlClient.query(`UPDATE cities SET food = cities.food - ${robed_res['food']},  wood = cities.wood - ${robed_res['wood']}, rock = cities.rock - ${robed_res['rock']}, steel = cities.steel - ${robed_res['steel']}, gold = cities.gold - ${robed_res['gold']} WHERE id = ${data['id']}`, function (err, ret){
                        if (err)
                            reject(err);
                        else
                            resolve(robed_res);
                    })
                }
            });
        });
    }

    function get_D_builds(target_city_id)
    {
        return new Promise((resolve, reject) => {
            mysqlClient.query("SELECT defenses.life, defenses.type, defenses.dmg_type, defenses.dmg, army_buildings.name FROM defenses, army_buildings WHERE defenses.building_id = army_buildings.id", function (err, ret){
                if (err)
                    reject(err);
                else
                {
                    var build_stats = {};
                    for (var key in ret)
                        build_stats[ret[key]['name'].replace(/\s/gi, "_")] = {"life":parseInt(ret[key]['life']), "type":ret[key]['type'], "dmg_type":ret[key]['dmg_type'], "dmg":ret[key]["dmg"]};
                    mysqlClient.query(`SELECT * FROM cities_buildings WHERE city_id = ${target_city_id}`, function (err, ret){
                        if (err)
                            reject(err);
                        else
                        {
                            let builds = ret[0];
                            var build_obj = {};
                            for (var key in builds)
                            {
                                if (builds[key] <= 0 || !build_stats.hasOwnProperty(key))
                                    continue ;
                                else
                                    build_obj[key] = {"lvl":parseInt(builds[key]), "life":parseInt(build_stats[key]['life']) * parseInt(builds[key]), "type":build_stats[key]["type"], "dmg_type":build_stats[key]["dmg_type"], "dmg":parseInt(build_stats[key]['dmg'])};
                            }
                            //console.log(build_obj);
                            resolve(build_obj);
                        }
                    });
                }
            });
        });
    }

    function get_D_units(target_city_id)
    {
        return new Promise((resolve, reject) => {
            mysqlClient.query(`SELECT * FROM cities_units WHERE city_id = ${target_city_id}`, function (err, ret){
                if (err)
                    reject(err);
                var city_units = {};
                var unit_obj = {};
                for (var key in ret[0])
                {
                    if (key == "id" || key == "city_id" || key == "owner" || ret[0][key] == 0)
                        continue ;
                    else
                        city_units[key] = ret[0][key];
                }
                mysqlClient.query("SELECT * FROM units", function (err, ret){
                    if (err)
                        reject(err);
                    for (var key_2 in ret)
                    {
                        if (city_units.hasOwnProperty(ret[key_2]['name']))
                        {
                            let life = ret[key_2]['life'];
                            let dmg_type = ret[key_2]['dmg_type'];
                            let dmg = ret[key_2]['power'];
                            let mv = ret[key_2]['moving_type'];
                            let unit_ref = ret[key_2]['name'];
                            let id = ret[key_2]['id'];
                            let speed = ret[key_2]['speed'];
                            unit_obj[unit_ref] = {"id":id, "quantity":parseInt(city_units[ret[key_2]['name']]), "life":parseInt(life), "dmg_type":dmg_type, "dmg":parseInt(dmg), "mv":mv, "speed":parseInt(speed)};
                        }
                    }
                    //console.log(unit_obj);
                    var tab_p = [];
                    for (var key in unit_obj)
                        tab_p.push(serach_unit_boost(key, unit_obj, target_city_id));
                    Promise.all(tab_p)
                    .then(() => 
                    {
                        /*console.log("Def army : ");
                        console.log(unit_obj);*/
                        resolve(unit_obj);
                    })
                    .catch((err) => 
                    {
                        reject(err);
                    });
                });
            });
        });
    }

    function get_A_units(city_id, units)
    {
        return new Promise((resolve, reject) => {
            mysqlClient.query("SELECT * FROM units", function (err, ret){
                if (err)
                    reject(err);
                var unit_obj = {};
                units = units.split(";");
                for (var key in units)
                {
                    let split = units[key].split(":");
                    //split[0] -> unit_id
                    //split[1] -> quantity
                    let life = ret[split[0] - 1]['life'];
                    let dmg_type = ret[split[0] - 1]['dmg_type'];
                    let dmg = ret[split[0] - 1]['power'];
                    let mv = ret[split[0] - 1]['moving_type'];
                    let unit_ref = ret[split[0] - 1]['name'];
                    let speed = ret[split[0] - 1]['speed'];
                    unit_obj[unit_ref] = {"id":split[0], "quantity":parseInt(split[1]), "life":parseInt(life), "dmg_type":dmg_type, "dmg":parseInt(dmg), "mv":mv, "speed":parseInt(speed)};
                }
                //console.log ("avant boost");
                //console.log(unit_obj);
                var tab_p = [];
                for (var key in unit_obj)
                    tab_p.push(serach_unit_boost(key, unit_obj, city_id));
                Promise.all(tab_p)
                .then(() => 
                {
                    /*console.log("Attack army : ");
                    console.log(unit_obj);*/
                    resolve(unit_obj);
                })
                .catch((err) => 
                {
                    reject(err);
                });
            }); 
        });
    }

    function serach_unit_boost(key, obj, city_id)
    {
        //console.log("search_unit_boost");
        return new Promise ((resolve, reject) => {
            let unit = obj[key];
            mysqlClient.query(`SELECT item_needed FROM units WHERE id = ${unit['id']}`, function (err, ret){
                if (err)
                    reject(err);
                else
                {
                    //console.log("req1 passed");
                    let items = ret[0]['item_needed'];
                    if (items == "NONE")
                        resolve(); // pas d'item = pas de boost
                    else if (items.indexOf(";") < 0)
                    {
                        // un seul item
          //              console.log("1 item");
                        let pp0 = apply_boost(obj, key, city_id, items);
                        Promise.all([pp0])
                        .then(() =>{
                            resolve();
                  //          console.log(obj[key]);
                        })
                        .catch((err) => {
                    //        console.log(err);
                            reject();
                        });
                    }
                    else
                    {
            //            console.log("x items");
                        // plusieurs items
                        let tab_pp0 = [];
                        let split = items.split(";");
                        for (var item in split)
                            tab_pp0.push(apply_boost(obj, key, city_id, split[item]));
                        Promise.all(tab_pp0)
                        .then(() =>{
              //              console.log(obj[key]);
                            resolve();
                        })
                        .catch((err) => {
                //            console.log(err);
                            reject(err);
                        });
                    }
                }
            });
        });
    }

    function apply_boost(obj, key, city_id, item)
    {
        return new Promise((resolve, reject) => {
            //console.log(`SELECT techs.name, techs.boost FROM techs INNER JOIN forge ON techs.id = forge.tech_required WHERE forge.id = ${item}`);
            mysqlClient.query(`SELECT techs.name, techs.boost FROM techs INNER JOIN forge ON techs.id = forge.tech_required WHERE forge.id = ${item}`, function (err, ret){
                if (err)
                    reject(err);
                else
                {
                    var tech_name = ret[0]['name'];
                    var boost = ret[0]['boost'];
                    if (tech_name.indexOf(" ") >= 0)
                        tech_name = tech_name.replace(/\s/gi, "_");
              //      console.log(`SELECT ${tech_name} FROM cities_techs WHERE city_id = ${city_id}`);
                    mysqlClient.query(`SELECT ${tech_name} FROM cities_techs WHERE city_id = ${city_id}`, function (err, ret){
                        if (err)
                            reject(err);
                        else
                        {
                            if (boost != "life" && boost != "power")
                                resolve();
                            else
                            {
                                let tech_lvl = ret[0][tech_name];                                            
                                if (tech_lvl == 0)
                                    resolve();
                                else
                                {
                                    if (boost == "power")
                                        obj[key].dmg = calc_new_value(obj[key].dmg, tech_lvl);
                                    else if (boost == "life")
                                        obj[key].life = calc_new_value(obj[key].life, tech_lvl);
                                    resolve();
                                }
                            }
                        }
                    });
                }
            });
        });
    }

    function calc_new_value(init_val, lvl)
    {
        boosted = init_val;
        boost = init_val * 10 / 100;
        for (let i = 0; i < lvl; i++)
        {
            boosted += boost;
            boost = boosted * 10 / 100;
        }
        return (Math.trunc(boosted));
    }
}