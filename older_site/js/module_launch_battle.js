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
            let p0 = get_A_units(ret[0]['city_id'], ret[0]['units']);
            let p1 = get_D_units(ret[0]['ending_point']);
            Promise.all([p0, p1])
            .then((ret) => {
                console.log("then");
                console.log(ret[1]);
                return 0;
            })
            .catch((err) =>{
                console.log("error");
                console.log(err);
                return -1;
            })
        }
    });

    function get_D_units(coord)
    {
        return new Promise((resolve, reject) => {
            coord = coord.split("/");
            let x_pos = coord[0];
            let y_pos = coord[1];
            mysqlClient.query(`SELECT id FROM cities WHERE x_pos = ${x_pos} AND y_pos = ${y_pos}`, function (err, ret){
                if (err)
                    reject(err);
                else if (ret == null || ret.length == 0)
                    resolve("no target");
                else
                {
                    let city_id = ret[0]['id'];
                    mysqlClient.query(`SELECT * FROM cities_units WHERE city_id = ${city_id}`, function (err, ret){
                        if (err)
                            reject(err);
                        var city_units = {};
                        var unit_obj = {};
                        for (var key in ret[0])
                        {
                            if (key == "id" || key == "city_id" || key == "owner" || ret[0][key] == 0)
                                continue ;
                            else
                                city_units[key.replace(/_/gi, "\s")] = ret[0][key];
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
                                    let mv = ret[key_2]['mv'];
                                    let unit_ref = ret[key_2]['name'];
                                    let id = ret[key_2]['id'];
                                    unit_obj[unit_ref] = {"id":id, "quantity":parseInt(city_units[key_2]), "life":parseInt(life), "dmg_type":dmg_type, "dmg":parseInt(dmg), "mv":mv};
                                }
                            }
                            console.log(unit_obj);
                            resolve();
                        });
                    });
                }
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
                    unit_obj[unit_ref] = {"id":split[0], "quantity":parseInt(split[1]), "life":parseInt(life), "dmg_type":dmg_type, "dmg":parseInt(dmg), "mv":mv};
                }
                //console.log ("avant boost");
                //console.log(unit_obj);
                var tab_p = [];
                for (var key in unit_obj)
                    tab_p.push(serach_unit_boost(key, unit_obj, city_id));
                Promise.all(tab_p)
                .then(() => 
                {
                  //  console.log(unit_obj);
                    resolve();
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
                            mysqlClient.end();
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
                            mysqlClient.end();
                            reject();
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