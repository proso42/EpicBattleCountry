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
            console.log(ret[0]['max']);
            return 0;
            let tab_p = [];
            for (let i = 0; i < results.length; i++)
                tab_p.push(change_mercenraies(results[i]), max);
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
            resolve();
        else
        {
            resolve();
        }
    });
}