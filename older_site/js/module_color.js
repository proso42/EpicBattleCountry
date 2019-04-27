module.exports.color = function (text, color)
{
	var bold = "\x1b[1m"
	if (color == "noir" || color == "black" || color == "N")
		color = "\x1b[30m";
	else if (color == "rouge" || color == "red" || color == "R")
		color = "\x1b[31m";
	else if (color == "vert" || color == "green" || color == "V")
		color = "\x1b[32m";
	else if (color == "jaune" || color == "yellow" || color == "J")
		color = "\x1b[33m";
	else if (color == "bleu" || color == "blue" || color == "B")
		color = "\x1b[34m";
	else if (color == "magenta" || color == "purpule" || color == "M")
		color = "\x1b[35m";
	else if (color == "cyan" || color == "C")
		color = "\x1b[36m";
	else if (color == "blanc" || color == "white" || color == "W")
		color = "\x1b[37m";
	else
	{
		console.log(text);
		return ;
	}
	console.log(bold + color + text + "\x1b[0m");
	return ;
}
