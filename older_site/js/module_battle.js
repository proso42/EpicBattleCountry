/*var A_troopers = {};
var D_troopers = {};
var D_defenses = {};

D_defenses['basic_wall'] = {"lvl":15, "life":75000, "type":"block", "dmg_type":"NONE", "dmg":0};
D_defenses['spike_trap'] = {"lvl":20, "life":20, "type":"trap", "dmg_type":"CaC", "dmg":1000};
A_troopers['guerrier'] = {"quantity":1000, "life":110, "dmg_type":"CaC", "dmg":25, "mv":"ground"};
A_troopers['chevalier'] = {"quantity":200, "life":300, "dmg_type":"CaC", "dmg":70, "mv":"ground"};
D_troopers['archer_nain'] = {"quantity":1475, "life":50, "dmg_type":"Dist", "dmg":20, "mv":"ground"};*/
module.exports.start_battle = function(D_defenses, D_troopers, A_troopers)
{
	var tour = 1 ;
	var print = require('./module_color');
	let winner = "";
	while (1)
	{
		/*print.color(D_defenses);
		print.color(D_troopers);
		print.color(A_troopers);*/
		if (defense_status(D_defenses) == "ok")
		{
			// defense step
			print.color("Tour " + tour, "B");
			print.color("Defense Step ", "B");
			A_dmg = get_Atrooper_dmg();
			D_dmg = get_Dtrooper_dmg("ok");
			Def_dmg = get_defenses_dmg();
			spread_dmg(A_dmg, D_defenses);
			spread_dmg(Def_dmg, A_troopers);
			spread_dmg(D_dmg, A_troopers);
		}
		else
		{
			// battle step
			print.color("Tour " + tour, "B");
			print.color("Battle Step ", "B");
			A_dmg = get_Atrooper_dmg();
			D_dmg = get_Dtrooper_dmg("ko");
			spread_dmg(A_dmg, D_troopers);
			spread_dmg(D_dmg, A_troopers);
		}
		if (check("A") == "ko")
		{
			print.color("Tout les attaquants sont morts ! Les défenseurs gagnent !", "V");
			winner = "D";
			break;
		}
		else if (check("D") == "ko")
		{
			print.color("Tout les défenseurs sont morts ! Les attaquants gagnent !", "R");
			winner = "A";
			break ;
		}
		tour++;
	}
	print.color('fin', "C");
	let final_obj = {"D_defenses":D_defenses, "D_troopers":D_troopers, "A_troopers":A_troopers, "winner":winner};
	return (final_obj);
	/*print.color(D_defenses);
	print.color(D_troopers);
	print.color(A_troopers);*/

	function check(side)
	{
		if (side == "A")
		{
			for (var key in A_troopers)
			{
				if (A_troopers[key].quantity > 0 && A_troopers[key].dmg_type != "NONE")
					return ("ok");
			}
			return ("ko");
		}
		else
		{
			for (var key in D_troopers)
			{
				if (D_troopers[key].quantity > 0 && D_troopers[key].dmg_type != "NONE")
					return ("ok");
			}
			return defense_status();
		}
	}

	function spread_dmg(dmg_object, target)
	{
		//print.color(dmg_object);
		if (target == D_defenses)
		{
			let nb_defenses = 0;
			for (var key in D_defenses)
			{
				if (D_defenses[key].life == 0)
					continue;
				else
					nb_defenses++;
			}
			dmg_object["CaC"] /= nb_defenses;
			dmg_object["Dist"] /= nb_defenses;
			dmg_object["Magic"] /= nb_defenses;
			dmg_object["Siege"] /= nb_defenses;
		//	print.color("Apres division : ");
		//	print.color(dmg_object);
			for (var key in D_defenses)
			{
				if (D_defenses[key].life == 0)
					continue;
				let dmg = 0;
				let def_type = D_defenses[key].type;
				if (def_type == "block")
					dmg = Math.round((dmg_object["CaC"]) + (dmg_object["Dist"] / 3) + (dmg_object["Magic"] / 2) + (dmg_object["Siege"] * 3));
				else
					dmg = Math.round((dmg_object["CaC"]) + (dmg_object["Dist"] / 4) + (dmg_object["Magic"] / 3) + (dmg_object["Siege"] * 2));
				D_defenses[key].life -= dmg;
				print.color(`[Defenses]${key} subit ${dmg} dommages`, "V");
				if (D_defenses[key].life <= 0)
				{
					D_defenses[key].life = 0;
					print.color(`[Defenses]${key} est detruit`, "V");
				}
			}
		}
		else
		{
		//	print.color(dmg_object);
			let nb_troopers = 0;
			for (var key in target)
			{
				if (target[key].quantity == 0 || target[key].dmg_type == "NONE")
					continue;
				else
					nb_troopers++;
			}
			dmg_object["CaC"] /= nb_troopers;
			dmg_object["Dist"] /= nb_troopers;
			dmg_object["Magic"] /= nb_troopers;
			dmg_object["Siege"] /= nb_troopers;
		//	print.color("apres divide");
		//	print.color(dmg_object);
			for (var key in target)
			{
				if (target[key].quantity == 0 || target[key].dmg_type == "NONE")
					continue ;
				let dmg = 0;
				let lost = 0;
				let trooper_type = target[key].mv;
				if (trooper_type == "ground")
				{
		//			print.color("ground");
					dmg = Math.round((dmg_object["CaC"]) + (dmg_object["Dist"]) + (dmg_object["Magic"] * 3) + (dmg_object["Siege"] / 3));
				}
				else if (trooper_type == "naval")
				{
		//			print.color("naval");
					dmg = Math.round((dmg_object["CaC"] / 5) + (dmg_object["Dist"] / 3) + (dmg_object["Magic"] / 2) + (dmg_object["Siege"]));
				}
				else
				{
		//			print.color("sky");
					dmg = Math.round((dmg_object["CaC"] * 0) + (dmg_object["Dist"] * 2) + (dmg_object["Magic"]) + (dmg_object["Siege"] * 0));
				}
				lost = Math.round(dmg / target[key].life);
				// affichage
				if (target == D_troopers)
				{
					print.color(`[Defenseur]${key} subit ${dmg} dommages`, "V");
					if (lost > target[key].quantity)
						print.color(`[Defenseur]${key} sont tous morts`, "V");
					else
						print.color(`[Defenseur]${key} ont des pertes : ${lost}`, "V");
				}
				else
				{
					print.color(`[Attaquant]${key} subit ${dmg} dommages`, "R");
					if (lost > target[key].quantity)
						print.color(`[Attaquant]${key} sont tous morts`, "R");
					else
						print.color(`[Attaquant]${key} ont des pertes : ${lost}`, "R");
				}
				// fin affichage
				(lost > target[key].quantity) ? target[key].quantity = 0 : target[key].quantity = target[key].quantity - lost;
			}
		}
	}

	function get_defenses_dmg()
	{
		let dmg_object = {"CaC":0, "Dist":0, "Magic":0, "Siege":0};
		for (var key in D_defenses)
		{
			let type = D_defenses[key].dmg_type;
			if (type == "NONE" || D_defenses[key].life == 0)
				continue;
			else
				dmg_object[type] += D_defenses[key].dmg * D_defenses[key].lvl;
		}
		return dmg_object;
	}

	function get_Atrooper_dmg()
	{
		let dmg_object = {"CaC":0, "Dist":0, "Magic":0, "Siege":0};
		for (var key in A_troopers)
		{
			let type = A_troopers[key].dmg_type;
			if (type == "NONE" || A_troopers[key].quantity == 0)
				continue;
			else
				dmg_object[type] += A_troopers[key].dmg * A_troopers[key].quantity;
		}
		return dmg_object;
	}

	function get_Dtrooper_dmg(def_status)
	{
		let dmg_object = {"CaC":0, "Dist":0, "Magic":0, "Siege":0};
		for (var key in D_troopers)
		{
			let type = D_troopers[key].dmg_type;
			if (type == "NONE" || type == "CaC" && def_status == "ok" || D_troopers[key].quantity == 0)
				continue;
			else
				dmg_object[type] += D_troopers[key].dmg * D_troopers[key].quantity;
		}
		return dmg_object;
	}

	function defense_status()
	{
		for (var key in D_defenses)
		{
			if (D_defenses[key].life > 0 && D_defenses[key].type == "block")
				return ("ok");
		}
		return ("ko");	
	}
}

