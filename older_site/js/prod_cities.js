
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


var minutes = new Date().getMinutes();
main();

function main()
{
		var tab_promises = [];
		var request1 = `SELECT * FROM cities`;
		mysqlClient.query(request1, function (error, results)
		{
			if (error)
			{
				console.log('select error');
				process.exit();
			}
			else
			{
				console.log('select cities OK')
				for (let i = 0; i < results.length; i++)
				{
					/*var race_request = `SELECT race FROM users WHERE id = ${results[i]['owner']}`;
					mysqlClient.query(race_request, function (error, ret)
					{
						if (error)
						{
							console.log('select race error');
						}
						console.log('select race OK');*/
						tab_promises.push(inc_prod(results[i]));
					//});
				}
				console.log('async abuse');
				Promise.all(tab_promises).then(()=>{console.log('fin success');mysqlClient.end();process.exit()}).catch(()=>{console.log('fin error');mysqlClient.end();process.exit()});
			}
			
		});
}
function inc_prod(e)
{
	return new Promise((resolve, reject) =>{
		let race_request = `SELECT race FROM users WHERE id = ${e['owner']}`;
		mysqlClient.query(race_request, function (error, ret)
		{
			if (error)
				reject(error)
			console.log('select race OK');
			let race = ret[0]['race'];
			let id = e['id'];
			let new_food = e['food_prod'] + e['food'];
			if (new_food > e['max_food'])
				new_food = e['max_food'];
			let new_wood = e['wood_prod'] + e['wood'];
			if (new_wood > e['max_wood'])
				new_wood = e['max_wood'];
			let new_rock = e['rock_prod'] + e['rock'];
			if (new_rock > e['max_rock'])
				new_rock = e['max_rock'];
			let new_steel = e['steel_prod'] + e['steel'];
			if (new_steel > e['max_steel'])
				new_steel = e['max_steel'];
			let new_gold = e['gold_prod'] + e['gold'];
			if (new_gold > e['max_gold'])
				new_gold = e['max_gold'];
			if (race == 1)
				var mount_type = 'Cheval';
			else if (race == 2)
				var mount_type = 'Likorne';
			else if (race == 3)
				var mount_type = 'Bouc_de_guerre';
			else if (race == 4)
				var mount_type = 'Loup';
			console.log(`before test : minutes [${minutes}]`);
			if (minutes == 0)
			{
				console.log('test minute');
				var new_mount = e['mount_prod'] + e[mount_type];
				if (e['mount_prod'] + e['Cheval'] + e['Likorne'] + e['Bouc_de_guerre'] + e['Loup'] > e['max_mount'])
					new_mount = e[mount_type] + (e['max_mount'] - (e['Cheval'] + e['Likorne'] + e['Boc_de_guerre'] + e['Loup']));
				var new_faith = e['faith_prod'] + e['faith'];
				if (new_faith > e['max_faith'])
					new_faith = e['max_faith'];
			}
			else
			{				
				var new_mount = e[mount_type];
				var new_faith = e['faith'];
			}
			let request2 = `UPDATE cities SET food = ${new_food}, wood = ${new_wood}, rock = ${new_rock}, steel = ${new_steel}, gold = ${new_gold}, ${mount_type} = ${new_mount} , faith = ${new_faith} WHERE id = ${id}`;
			console.log(request2)
			mysqlClient.query(request2, function (error, results)
			{
				if (error)
				{
					console.log('update error : ' + error);
					reject();
				}
				else
				{
					console.log('update ok');
					resolve();
				}
			});
		});
	});
}
