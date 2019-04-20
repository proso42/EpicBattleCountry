setTimeout(() =>{
    let body_height = document.body.scrollHeight + 20;
    let win_height = window.innerHeight;
    if (body_height > win_height)
        document.getElementById("overlay").style.height = body_height + "px";
    else
        document.getElementById("overlay").style.height = win_height + "px";
}, 1000);


var units_send = [];
var city = "";
var speed = 100;
var click = false;
onmousedown = function(){
    click = true;
};

onmouseup = function(){
    click = false;
    speed = 100;
};

function manual(unit_ref, unit_name, max)
{
    document.getElementById("quantity_title").textContent = unit_name;
    document.getElementById("overlay").style.display = "";
    document.getElementById("block_edit").style.display = "";
    document.getElementById("edit_button").onclick = function (){
        let quantity = document.getElementById("new_quantity").value;
        if (quantity == "")
            quantity = 0;
        else if (!(!isNaN(parseFloat(quantity)) && isFinite(quantity)))
            quantity = 0;
        else if (parseInt(quantity) <= 0)
            quantity = 0;
        quantity = parseInt(quantity);
        if (quantity > max)
            quantity = max;
        units_send[unit_ref] = quantity;
        document.getElementById(unit_ref + "_selected").textContent = units_send[unit_ref] + "/" + max;
        document.getElementById("overlay").style.display = "none";
        document.getElementById("block_edit").style.display = "none";
        document.getElementById("new_quantity").value = "";
    };
    document.getElementById("cancel_button").onclick = function (){
        document.getElementById("overlay").style.display = "none";
        document.getElementById("block_edit").style.display = "none";
        document.getElementById("new_quantity").value = "";
    };
}

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

function step3()
{
    document.getElementById("list_unit").style.display = "none";
    document.getElementById("list_city").style.display = "";
}

function back_step2()
{
    document.getElementById("list_unit").style.display = "";
    document.getElementById("list_city").style.display = "none";
}

function back_step1()
{
    document.getElementById("action_choice").style.display = "";
    document.getElementById("list_unit").style.display = "none";
}

function add_unit(unit_ref, max, nb)
{
    if (nb > 5)
        speed = 75;
    else if (nb > 10)
        speed = 50;
    else if (nb > 20)
        speed = 10;
    setTimeout(function(){
        if (units_send.hasOwnProperty(unit_ref))
        {
            if (units_send[unit_ref] < max)
            {
                units_send[unit_ref]++;
                document.getElementById(unit_ref + "_selected").textContent = units_send[unit_ref] + "/" + max;
            }
        }
        else
        {
            units_send[unit_ref] = 1;
            document.getElementById(unit_ref + "_selected").textContent = "1/" + max;
        }
        if (click == true)
            add_unit(unit_ref, max, nb + 1);
    }, speed);
}

function add_max(unit_ref, max)
{
    units_send[unit_ref] = max;
    document.getElementById(unit_ref + "_selected").textContent = max + "/" + max;
}

function remove_unit(unit_ref, max, nb)
{
    if (nb > 5)
        speed = 75;
    else if (nb > 10)
        speed = 50;
    else if (nb > 20)
        speed = 10;
    setTimeout(function(){
        if (units_send.hasOwnProperty(unit_ref) && units_send[unit_ref] > 0)
        {
            units_send[unit_ref]--;
            document.getElementById(unit_ref + "_selected").textContent = units_send[unit_ref] + "/" + max;
        }
        if (click == true)
            remove_unit(unit_ref, max, nb + 1);
    }, speed);
}

function remove_all(unit_ref, max)
{
    units_send[unit_ref] = 0;
    document.getElementById(unit_ref + "_selected").textContent = "0/" + max;
}

function select_city(name)
{
    if (city != "")
    {
        document.getElementById("id_" + city).style.border = "1px solid lightblue";
        document.getElementById("city_" + city).style.display = "none";
    }
    if (city == name)
    {
        document.getElementById("id_" + city).style.border = "1px solid lightblue";
        document.getElementById("city_" + city).style.display = "none";
        city = "";
        return ;
    }
    city = name;
    document.getElementById("id_" + city).style.border = "1px solid lightgreen";
    document.getElementById("city_" + city).style.display = "";
}