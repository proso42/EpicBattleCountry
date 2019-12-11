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
var request_get_all_cities = "SELECT cities.id, cities.tavern_slot1, cities.tavern_slot2, cities.tavern_slot3, cities.tavern_slot1_qt, cities.tavern_slot2_qt, cities.tavern_slot3_qt FROM cities INNER JOIN cities_buildings ON cities.id = cities_buildings.city_id WHERE cities_buildings.Taverne > 0";
mysqlClient.query(request_get_all_cities, function (err, results) {
    if (err)
        return (err);
    else
    {
        mysqlClient.query("SELECT COUNT(id) AS 'max' FROM mercenaries", function (err, ret) {
            let tab_p = [];
            let max = ret[0]['max'];
            for (let i = 0; i < results.length; i++)
                tab_p.push(change_mercenraies(results[i], max));
            Promise.all(tab_p)
            .then(() => {
                console.log('All availables mercenaries changed !');
                return 0;
            })
            .catch((err) => {
                console.log(err);
                return 1;
            });
        });
    }
});

function change_mercenraies(city, max)
{
    return new Promise((resolve, reject) => {
        if (city['tavern_slot1'] == -1)
        {
            console.log(`For city ${city['id']} nothing ...`)
            resolve();
        }
        else
        {
            let new_mercenary_1 = Math.floor(Math.random() * Math.floor(max)) + 1;
            let new_quantity_1 = Math.trunc((getRandomInt(5000)+500)/100)*100;
            if (city['tavern_slot2'] == -1)
            {
                mysqlClient.query(`UPDATE cities WHERE id = ${city['id']} SET tavern_slot1 = ${new_mercenary_1}, tavern_slot1_qt = ${new_quantity_1}`, function (err, ret){
                    console.log(`For city ${city['id']} tavern_slot1 UPDATED !`);
                    resolve();
                });
            }
            else
            {
                let new_mercenary_2 = Math.floor(Math.random() * Math.floor(max)) + 1;
                let new_quantity_2 = Math.trunc((getRandomInt(5000)+500)/100)*100;
                if (city['tavern_slot3'] == -1)
                {
                    mysqlClient.query(`UPDATE cities WHERE id = ${city['id']} SET tavern_slot1 = ${new_mercenary_1}, tavern_slot2 = ${new_mercenary_2}, tavern_slot1_qt = ${new_quantity_1}, tavern_slot2_qt = ${new_quantity_2}`, function (err, ret){
                        console.log(`For city ${city['id']} tavern_slot1 and tavern_slot2 UPDATED !`);
                        resolve();
                    });
                }
                else
                {
                    let new_mercenary_3 = Math.floor(Math.random() * Math.floor(max)) + 1;
                    let new_quantity_3 = Math.trunc((getRandomInt(5000)+500)/100)*100;
                    mysqlClient.query(`UPDATE cities WHERE id = ${city['id']} SET tavern_slot1 = ${new_mercenary_1}, tavern_slot2 = ${new_mercenary_2}, tavern_slot3 = ${new_mercenary_3},tavern_slot1_qt = ${new_quantity_1}, tavern_slot2_qt = ${new_quantity_2}, tavern_slot3_qt = ${new_quantity_3},`, function (err, ret){
                        console.log(`For city ${city['id']} all slots UPDATED !`);
                        resolve();
                    });
                }
            }
        }
    });
}

function getRandomInt(max) {
    return Math.floor(Math.random() * Math.floor(max));
  }