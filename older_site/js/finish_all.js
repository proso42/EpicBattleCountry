
var fs = require('fs');
var mysql = require('mysql');
let env_file = fs.readFileSync("../src/site/.env", 'utf8').split('\n');
var db_user = '';
var db_password = '';
var print = require("./module_color");
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

let unflag_buildings_p = unflag_buildings();
let unflag_items_p = unflag_items();
let unflag_techs_p = unflag_techs();
let unflag_units_p = unflag_units();
let unflag_travels_p = unflag_travels();
let unflag_magic_cool_down_p = unflag_magic_cool_down();
let unflag_mercenaries_cool_down_p = unflag_mercenaries_cool_down();
Promise.all([unflag_buildings_p, unflag_items_p, unflag_techs_p, unflag_units_p, unflag_travels_p, unflag_magic_cool_down_p, unflag_mercenaries_cool_down_p])
	.then(() => {
		setInterval(catch_unflag_action, 1000);
	})
	.catch((err) => {
		console.log(`unflag error : ${err}`);
		process.exit();
	});

function catch_unflag_action()
{
	mysqlClient.query("SELECT id, finishing_date FROM waiting_buildings WHERE flag = 0;", function (err, ret)
	{
		if (err)
			return (err);
		else if (ret.length > 0)
		{
			console.log("\x1b[1m\x1b[32mnew building entry !\x1b[0m");
			for (let i = 0; i < ret.length; i++)
			{
				mysqlClient.query(`UPDATE waiting_buildings SET flag = 1 WHERE id = ${ret[i]['id']};`, function(err, ret){
					if (err)
					{
						console.log(`waiting_buildings error : flag to 1 : ${err}`);
						return ;
					}
					else
						return ;
				});
				setTimeout(() => 
				{
					let mod_build = require('./module_buildings.js');
					mod_build.start(ret[i]['id']);
				}, (ret[i]['finishing_date'] - (Math.round(Date.now() / 1000))) * 1000);
			}
		}
		else
		{
			return ;
		}
	});
	mysqlClient.query("SELECT id, finishing_date FROM waiting_techs WHERE flag = 0;", function (err, ret)
	{
		if (err)
			return (err);
		else if (ret.length > 0)
		{
			console.log("\x1b[1m\x1b[36mnew tech entry !\x1b[0m");
			for (let i = 0; i < ret.length; i++)
			{
				mysqlClient.query(`UPDATE waiting_techs SET flag = 1 WHERE id = ${ret[i]['id']};`, function(err, ret){
					if (err)
					{
						console.log(`waiting_techs error : flag to 1 : ${err}`);
						return ;
					}
					else
						return ;
				});
				setTimeout(() => 
				{
					let mod_tech = require('./module_techs.js');
					mod_tech.start(ret[i]['id']);
				}, (ret[i]['finishing_date'] - (Math.round(Date.now() / 1000))) * 1000);
			}
		}
		else
		{
			return ;
		}
	});
	mysqlClient.query("SELECT id, finishing_date, quantity FROM waiting_items WHERE flag = 0;", function (err, ret)
	{
		if (err)
			return (err);
		else if (ret.length > 0)
		{
			console.log("\x1b[1m\x1b[35mnew forge entry !\x1b[0m");
			for (let i = 0; i < ret.length; i++)
			{
				mysqlClient.query(`UPDATE waiting_items SET flag = 1 WHERE id = ${ret[i]['id']};`, function(err, ret){
					if (err)
					{
						console.log(`waiting_items error : flag to 1 : ${err}`);
						return ;
					}
					else
						return ;
				});
				setTimeout(() => 
				{
					let mod_build = require('./module_items.js');
					mod_build.start(ret[i]['id'], ret[i]['quantity']);
				}, (ret[i]['finishing_date'] - (Math.round(Date.now() / 1000))) * 1000);
			}
		}
		else
		{
			return ;
		}
	});
	mysqlClient.query("SELECT id, finishing_date, quantity FROM waiting_units WHERE flag = 0;", function (err, ret)
	{
		if (err)
			return (err);
		else if (ret.length > 0)
		{
			console.log("\x1b[1m\x1b[31mnew unit entry !\x1b[0m");
			for (let i = 0; i < ret.length; i++)
			{
				mysqlClient.query(`UPDATE waiting_units SET flag = 1 WHERE id = ${ret[i]['id']};`, function(err, ret){
					if (err)
					{
						console.log(`waiting_units error : flag to 1 : ${err}`);
						return ;
					}
					else
						return ;
				});
				setTimeout(() => 
				{
					let mod_build = require('./module_units.js');
					mod_build.start(ret[i]['id'], ret[i]['quantity']);
				}, (ret[i]['finishing_date'] - (Math.round(Date.now() / 1000))) * 1000);
			}
		}
		else
		{
			return ;
		}
	});
	mysqlClient.query("SELECT id, finishing_date FROM traveling_units WHERE flag = 0;", function (err, ret)
	{
		if (err)
			return (err);
		else if (ret.length > 0)
		{
			console.log("\x1b[1m\x1b[30mnew exploration entry !\x1b[0m");
			for (let i = 0; i < ret.length; i++)
			{
				mysqlClient.query(`UPDATE traveling_units SET flag = 1 WHERE id = ${ret[i]['id']};`, function(err, ret){
					if (err)
					{
						console.log(`traveling_units error : flag to 1 : ${err}`);
						return ;
					}
					else
						return ;
				});
				setTimeout(() => 
				{
					let mod_exploration = require('./module_expedition.js');
					mod_exploration.start(ret[i]['id']);
				}, (ret[i]['finishing_date'] - (Math.round(Date.now() / 1000))) * 1000);
			}
		}
		else
		{
			return ;
		}
	});
	mysqlClient.query("SELECT id, finishing_date FROM magic_cool_down WHERE flag = 0;", function (err, ret)
	{
		if (err)
			return (err);
		else if (ret.length > 0)
		{
			print.color("new magic cool down entry !", "J");
			for (let i = 0; i < ret.length; i++)
			{
				let entry_id = ret[i]["id"];
				mysqlClient.query(`UPDATE magic_cool_down SET flag = 1 WHERE id = ${ret[i]['id']};`, function (err, ret)
				{
					if (err)
						return (err);
					else
						return ;
				});
				setTimeout(() =>
				{
					mysqlClient.query(`DELETE FROM magic_cool_down WHERE id = ${entry_id};`, function (err, ret)
					{
						if (err)
							return (err);
						else
							console.log(`Magic cool down (${entry_id}) deleted !`);
					}, (ret[i]['finishing_date'] - (Math.round(Date.now() / 1000))) * 1000), entry_id;
				});
			}
		}
		else
			return ;
	});
	mysqlClient.query("SELECT id, finishing_date FROM mercenaries_cool_down WHERE flag = 0;", function (err, ret)
	{
		if (err)
			return (err);
		else if (ret.length > 0)
		{
			print.color("new mercenaries cool down entry !", "M");
			for (let i = 0; i < ret.length; i++)
			{
				let entry_id = ret[i]["id"];
				console.log((ret[i]['finishing_date'] - (Math.round(Date.now() / 1000))) * 1000);
				mysqlClient.query(`UPDATE mercenaries_cool_down SET flag = 1 WHERE id = ${ret[i]['id']};`, function (err, ret)
				{
					if (err)
						return (err);
					else
						return ;
				});
				setTimeout(() =>
				{
					mysqlClient.query(`DELETE FROM mercenaries_cool_down WHERE id = ${entry_id};`, function (err, ret)
					{
						if (err)
							return (err);
						else
							console.log(`Mercenaries cool down (${entry_id}) deleted !`);
					}, (ret[i]['finishing_date'] - (Math.round(Date.now() / 1000))) * 1000), entry_id;
				});
			}
		}
		else
			return ;
	});
}

function unflag_buildings()
{
	return new Promise ((resolve, reject) => {
		mysqlClient.query("UPDATE waiting_buildings SET flag = 0", function (err, ret){
			if (err)
				reject(err);
			else
				resolve();
		});
	});
}

function unflag_items()
{
	return new Promise ((resolve, reject) => {
		mysqlClient.query("UPDATE waiting_items SET flag = 0", function (err, ret){
			if (err)
				reject(err);
			else
				resolve();
		});
	});
}

function unflag_techs()
{
	return new Promise ((resolve, reject) => {
		mysqlClient.query("UPDATE waiting_techs SET flag = 0", function (err, ret){
			if (err)
				reject(err);
			else
				resolve();
		});
	});
}

function unflag_units()
{
	return new Promise ((resolve, reject) => {
		mysqlClient.query("UPDATE waiting_units SET flag = 0", function (err, ret){
			if (err)
				reject(err);
			else
				resolve();
		});
	});
}

function unflag_travels()
{
	return new Promise ((resolve, reject) => {
		mysqlClient.query("UPDATE traveling_units SET flag = 0", function (err, ret){
			if (err)
				reject(err);
			else
				resolve();
		});
	});
}

function unflag_magic_cool_down()
{
	return new Promise ((resolve, reject) => {
		mysqlClient.query("UPDATE magic_cool_down SET flag = 0", function (err, ret){
			if (err)
				reject(err);
			else
				resolve();
		});
	});
}

function unflag_mercenaries_cool_down()
{
	return new Promise ((resolve, reject) => {
		mysqlClient.query("UPDATE mercenaries_cool_down SET flag = 0", function (err, ret){
			if (err)
				reject(err);
			else
				resolve();
		});
	});
}


