var units_send = [];
var click = false;
onmousedown = function(){
    //console.log("enfoncé");
    click = true;
};

onmouseup = function(){
    //console.log("relaché");
    click = false;
};

function hide_img(side)
{
    if (side == "left")
        document.getElementById("left_overlay").style.display = "";
    else if (side == "right")
        document.getElementById("right_overlay").style.display = "";
    else
        return ;
}

function show_img(side)
{
    if (side == "left")
        document.getElementById("left_overlay").style.display = "none";
    else if (side == "right")
        document.getElementById("right_overlay").style.display = "none";
    else
        return ;
}

function step2()
{
    document.getElementById("action_choice").style.display = "none";
    document.getElementById("list_unit").style.display = "";
}

function back_step1()
{
    document.getElementById("action_choice").style.display = "";
    document.getElementById("list_unit").style.display = "none";
}

function add_unit(unit_ref, max)
{
    if (units_send.hasOwnProperty(unit_ref) && units_send[unit_ref] < max)
    {
        units_send[unit_ref]++;
        document.getElementById(unit_ref + "_selected").textContent = units_send[unit_ref] + "/" + max;
    }
    else
    {
        units_send[unit_ref] = 1;
        document.getElementById(unit_ref + "_selected").textContent = "1/" + max;
    }
    if (click == true)
        add_unit(unit_ref, max);
}

function remove_unit(unit_ref, max)
{
    if (units_send.hasOwnProperty(unit_ref) && units_send[unit_ref] > 0)
    {
        units_send[unit_ref]--;
        document.getElementById(unit_ref + "_selected").textContent = units_send[unit_ref] + "/" + max;
    }
}