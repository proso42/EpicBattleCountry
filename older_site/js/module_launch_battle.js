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
        }
    });

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
                console.log ("avant boost");
                console.log(unit_obj);
                var tab_p = [];
                for (var key in unit_obj)
                    tab_p.push(apply_unit_boost(key, unit_obj, city_id));
                Promise.all(tab_p)
                .then(() => 
                {
                    console.log(unit_obj);
                    resolve();
                })
                .catch((err) => 
                {
                    reject(err);
                });
            }); 
        });
    }

    function apply_unit_boost(key, obj, city_id)
    {
        console.log("apply_unit_boost");
        return new Promise ((resolve, reject) => {
            let unit = obj[key];
            mysqlClient.query(`SELECT item_needed FROM units WHERE id = ${unit['id']}`, function (err, ret){
                if (err)
                    reject(err);
                else
                {
                    console.log("req1 passed");
                    let items = ret[0]['item_needed'];
                    if (items == "NONE")
                        resolve(); // pas d'item = pas de boost
                    else if (items.indexOf(";") < 0)
                    {
                        // un seul item
                        console.log("1 item");
                        mysqlClient.query(`SELECT techs.name, techs.boost FROM techs INNER JOIN forge ON techs.id = forge.tech_required WHERE forge.id = ${items}`, function (err, ret){
                            if (err)
                                reject(err);
                            else
                            {
                                var tech_name = ret[0]['name'];
                                var boost = ret[0]['boost'];
                                if (tech_name.indexOf(" ") >= 0)
                                    tech_name = tech_name.replace(/\s/gi, "_");
                                console.log(`SELECT ${tech_name} FROM cities_techs WHERE city_id = ${city_id}`);
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
                                                    obj[key].dmg = calc_boost(obj[key].dmg, tech_lvl);
                                                else if (boost == "life")
                                                    obj[key].life = calc_boost(obj[key].dmg, tech_lvl);
                                                resolve();
                                            }
                                        }
                                    }
                                });
                            }
                        });
                    }
                    else
                    {
                        // plusieurs items
                        console.log("x items");
                        resolve();
                    }
                }
            });
        });
    }

    function calc_boost(init_val, lvl)
    {
        boosted = init_val;
        boost = init_val * 30 / 100;
        for (let i = 0; i < lvl; i++)
        {
            boosted += boost;
            boost = boosted * 30 / 100;
            console.log(boosted);
        }
        return (Math.trunc(boosted));
    }
}