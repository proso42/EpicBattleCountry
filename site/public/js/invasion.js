setTimeout(() =>{
    let body_height = document.body.scrollHeight + 20;
    let win_height = window.innerHeight;
    if (body_height > win_height)
        document.getElementById("overlay").style.height = body_height + "px";
    else
        document.getElementById("overlay").style.height = win_height + "px";
}, 1000);


var units_send = {};
var res_taken = {};
var city = "";
var target_city = "";
var speed = 100;
var total_storage = 0;
var total_taken = 0;
var click = false;
onmousedown = function(){
    click = true;
};

onmouseup = function(){
    click = false;
    speed = 100;
};

function limit_attack_reach()
{
    document.getElementById("error_limit_attack_reach").style.display = "";
    setTimeout(() =>{
        document.getElementById("error_limit_attack_reach").style.display = "none";
    }, 3000);
}

function limit_move_reach()
{
    document.getElementById("error_limit_move_reach").style.display = "";
    setTimeout(() =>{
        document.getElementById("error_limit_move_reach").style.display = "none";
    }, 3000);
}

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
        total_storage = 0;
        for (var key in units_send)
            total_storage += parseInt((units_send[key] * document.getElementById("unit_" + key).getAttribute("storage")));
    };
    document.getElementById("cancel_button").onclick = function (){
        document.getElementById("overlay").style.display = "none";
        document.getElementById("block_edit").style.display = "none";
        document.getElementById("new_quantity").value = "";
    };
}

function manual_res(res_ref, res_name, max)
{
    document.getElementById("quantity_title").textContent = res_name;
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
        if (res_taken.hasOwnProperty(res_ref))
            total_taken -= res_taken[res_ref];
        if (quantity > max)
            quantity = max;
        if (total_taken + quantity > total_storage)
            quantity = total_storage - total_taken;
        total_taken += quantity;
        document.getElementById("fret").textContent = total_taken + "/" + total_storage;    
        res_taken[res_ref] = quantity;
        document.getElementById("res_" + res_ref + "_selected").textContent = res_taken[res_ref] + "/" + max;
        document.getElementById("overlay").style.display = "none";
        document.getElementById("block_edit").style.display = "none";
        document.getElementById("new_quantity").value = "";
        console.log("total_taken : " + total_taken);
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
    document.getElementById("button_step3").onclick = function(){step_res()};
}

function step2bis()
{
    document.getElementById("action_choice").style.display = "none";
    document.getElementById("list_unit").style.display = "";
    document.getElementById("button_step3").onclick = function(){step_dest()};
}

function step_dest()
{
    document.getElementById("list_unit").style.display = "none";
    document.getElementById("block_dest").style.display = "";
}

function step_confirm_dest()
{
    let x = document.getElementById('dest_x').value;
    let y = document.getElementById('dest_y').value;
    if ((target_city != "" && (x != "" || y != "")) || target_city == "" && x == "" && y == "")
    {
        document.getElementById("error_city_and_dest").style.display = "";
        setTimeout(function(){
            document.getElementById("error_city_and_dest").style.display = "none";
        }, 2500);
        return ;
    }
    else if (target_city == "" && check_coord(x) == 0 || target_city == "" && check_coord(y) == 0)
        return ;
    console.log(units_send);
    var _token = document.getElementById("_token").value;
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'http://www.epicbattlecorp.fr/calculate_attack');
    xhr.onreadystatechange =  function()
    {
        if (xhr.readyState === 4 && xhr.status === 200)
        {
            if (xhr.responseText.indexOf("error") >= 0)
            {
                console.log(xhr.responseText);
                if (xhr.responseText == "Invasion error : cannot attack allied")
                {
                    document.getElementById("error_cannot_attack_allied").style.display = "";
                    setTimeout(function(){
                        document.getElementById("error_cannot_attack_allied").style.display = "none";
                    }, 2500);
                }
                return ;
            }
            else
            {
                let rep = JSON.parse(xhr.responseText)
                console.log(rep);
                document.getElementById("confirm_attack").style.display = "";
                document.getElementById("block_dest").style.display = "none";
                if (rep.cell == "unknow")
                {
                    document.getElementById("warning").style.display = "";
                    document.getElementById("warning_coord").textContent += "(" + rep.x + "/" + rep.y + ")";
                    document.getElementById("info_target").style.display = "none";
                }
                else
                {
                    document.getElementById("warning").style.display = "none";
                    document.getElementById("info_target").style.display = "";
                    if (rep.cell == "City" || rep.cell == "Ville")
                        document.getElementById("attack_target").textContent = rep.cell + " - " + rep.name + " (" + rep.x + "/" + rep.y + ")";
                    else
                        document.getElementById("attack_target").textContent = rep.cell + " (" + rep.x + "/" + rep.y + ")";
                }
                let travel_duration = document.getElementById("attack_travel_duration");
                if (travel_duration.childElementCount == 1)
                    travel_duration.lastChild.remove();
                travel_duration.textContent = rep.travel_duration;
                let clock_icon = document.createElement("i");
                clock_icon.className = "fas fa-clock";
                travel_duration.insertBefore(clock_icon, travel_duration.lastChild.nextSibling);
                var parent = document.getElementById("confirm_attack");
                var duration_div = document.getElementById("attack_duration");
                for (var key in units_send)
                {
                    if (units_send[key] <= 0)
                        continue;
                    let new_div = document.createElement("div");
                    new_div.className = "invasion-unit-line";
                    new_div.id = "confirm_attack_unit" + key;
                    parent.insertBefore(new_div, duration_div);
                    let new_span = document.createElement("span");
                    let unit_name = document.getElementById("unit_" + key).getAttribute("unit_name");
                    let textNode = document.createTextNode(unit_name + " x" + units_send[key]);
                    new_span.appendChild(textNode);
                    new_div.insertBefore(new_span, new_div.firstChild);
                }
                return ;
            }
        }
    }
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    if (target_city == "")
    {
        x = parseInt(x);
        y = parseInt(y);
        xhr.send('_token=' + _token + '&units=' + JSON.stringify(units_send) + "&x_pos=" + x + "&y_pos=" + y);
    }
    else
        xhr.send('_token=' + _token + '&units=' + JSON.stringify(units_send) + "&target_city=" + target_city);
}

function step_res()
{
    let total_unit = 0;
    for (var key in units_send)
        total_unit += units_send[key];
    if (total_unit == 0)
    {
        document.getElementById("error_no_unit_selected").style.display = "";
        setTimeout(function(){
            document.getElementById("error_no_unit_selected").style.display = "none";
        }, 5000)
    }
    else
    {
        document.getElementById("list_unit").style.display = "none";
        document.getElementById("list_res_item").style.display = "";
        document.getElementById("fret").textContent = "0/" + total_storage;
    }
    console.log("total_storage : " + total_storage);
}

function step3()
{
    document.getElementById("list_res_item").style.display = "none";
    document.getElementById("list_city").style.display = "";
}

function step4()
{
    console.log(units_send);
    var _token = document.getElementById("_token").value;
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'http://www.epicbattlecorp.fr/calculate_move_units');
    xhr.onreadystatechange =  function()
    {
        if (xhr.readyState === 4 && xhr.status === 200)
        {
            if (xhr.responseText.indexOf("error") >= 0)
            {
                console.log(xhr.responseText);
                return ;
            }
            else
            {
                console.log("duration : " + xhr.responseText);
                document.getElementById("confirm_move_unit").style.display = "";
                document.getElementById("list_city").style.display = "none";
                document.getElementById("target_city_name").textContent = city;
                let travel_duration = document.getElementById("travel_duration");
                if (travel_duration.childElementCount == 1)
                    travel_duration.lastChild.remove();
                travel_duration.textContent = xhr.responseText;
                let clock_icon = document.createElement("i");
                clock_icon.className = "fas fa-clock";
                travel_duration.insertBefore(clock_icon, travel_duration.lastChild.nextSibling);
                var parent = document.getElementById("confirm_move_unit");
                var duration_div = document.getElementById("move_unit_duration");
                for (var key in units_send)
                {
                    if (units_send[key] <= 0)
                        continue;
                    let new_div = document.createElement("div");
                    new_div.className = "invasion-unit-line";
                    new_div.id = "confirm_unit_" + key;
                    parent.insertBefore(new_div, duration_div);
                    let new_span = document.createElement("span");
                    let unit_name = document.getElementById("unit_" + key).getAttribute("unit_name");
                    let textNode = document.createTextNode(unit_name + " x" + units_send[key]);
                    new_span.appendChild(textNode);
                    new_div.insertBefore(new_span, new_div.firstChild);
                }
                for (var key2 in res_taken)
                {
                    if (res_taken[key2] <= 0)
                        continue;
                    let new_div = document.createElement("div");
                    new_div.className = "invasion-unit-line";
                    new_div.id = "confirm_res_" + key2;
                    parent.insertBefore(new_div, duration_div);
                    let new_span = document.createElement("span");
                    let res_name = document.getElementById("res_" + key2).getAttribute("res_name");
                    let textNode = document.createTextNode(res_name + " x" + res_taken[key2]);
                    new_span.appendChild(textNode);
                    new_div.insertBefore(new_span, new_div.firstChild);
                }
            }
        }
    }
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send('_token=' + _token + '&units=' + JSON.stringify(units_send) + '&res=' + JSON.stringify(res_taken) + "&city_target=" + city);
}

function step5()
{
    document.getElementById("button_step5").style.display = "none";
    document.getElementById("cancel_button_3").style.display = "none";
    document.getElementById("spin").style.display = "";
    var _token = document.getElementById("_token").value;
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'http://www.epicbattlecorp.fr/move_units');
    xhr.onreadystatechange =  function()
    {
        if (xhr.readyState === 4 && xhr.status === 200)
        {
            if (xhr.responseText.indexOf("error") >= 0)
                console.log(xhr.responseText);
            else
                document.getElementById("units_move_success").style.display = "";
            setTimeout(function(){
                window.location.reload();
            }, 3000);
        }
    }
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send('_token=' + _token + '&units=' + JSON.stringify(units_send) + '&res=' + JSON.stringify(res_taken) + "&city_target=" + city);
}

function step_attack()
{
    document.getElementById("button_step_attack").style.display = "none";
    document.getElementById("cancel_button_attack").style.display = "none";
    document.getElementById("spin_attack").style.display = "";
    var _token = document.getElementById("_token").value;
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'http://www.epicbattlecorp.fr/attack');
    xhr.onreadystatechange =  function()
    {
        if (xhr.readyState === 4 && xhr.status === 200)
        {
            if (xhr.responseText.indexOf("error") >= 0)
                console.log(xhr.responseText);
            else
                document.getElementById("attack_launch").style.display = "";
            setTimeout(function(){
                window.location.reload();
            }, 2500);
        }
    }
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    if (target_city == "")
    {
        x = document.getElementById("dest_x").value;
        y = document.getElementById("dest_y").value;
        xhr.send('_token=' + _token + '&units=' + JSON.stringify(units_send) + "&x_pos=" + x + "&y_pos=" + y);
    }
    else
        xhr.send('_token=' + _token + '&units=' + JSON.stringify(units_send) + '&res=' + JSON.stringify(res_taken) + "&target_city=" + target_city);
}

function back_step1()
{
    document.getElementById("action_choice").style.display = "";
    document.getElementById("list_unit").style.display = "none";
}

function back_step2()
{
    document.getElementById("list_unit").style.display = "";
    document.getElementById("list_res_item").style.display = "none";
}

function back_step2bis()
{
    document.getElementById("block_dest").style.display = "none";
    document.getElementById("list_unit").style.display = "";
}

function back_step_res()
{
    document.getElementById("list_res_item").style.display = "";
    document.getElementById("list_city").style.display = "none";
}

function back_step3()
{
    document.getElementById("list_city").style.display = "";
    document.getElementById("confirm_move_unit").style.display = "none";
    for (var key in units_send)
    {
        if (units_send[key] <= 0)
            continue ;
        document.getElementById("confirm_unit_" + key).remove();
    }
    for (var key2 in res_taken)
    {
        if (res_taken[key2] <= 0)
            continue ;
        document.getElementById("confirm_res_" + key2).remove();
    }
}

function back_step_dest()
{
    document.getElementById("block_dest").style.display = "";
    document.getElementById("confirm_attack").style.display = "none";
    for (var key in units_send)
    {
        if (units_send[key] <= 0)
            continue ;
        document.getElementById("confirm_attack_unit" + key).remove();
    }
}

function check_coord(n)
{
    if (n == "")
    {
        document.getElementById("error_empty_input").style.display = "";
        setTimeout(() =>{
            document.getElementById("error_empty_input").style.display = 'none';
        }, 5000);
        return 0;
    }
    else if (!(!isNaN(parseFloat(n)) && isFinite(n)))
    {
        document.getElementById("error_bad_input").style.display = "";
        setTimeout(() =>{
            document.getElementById("error_bad_input").style.display = 'none';
        }, 5000);
        return 0;
    }
    else if (parseInt(n) < -2000 || parseInt(n) > 2000)
    {
        document.getElementById("error_limit_value").style.display = "";
        setTimeout(() =>{
            document.getElementById("error_limit_value").style.display = 'none';
        }, 5000);
        return 0;
    }
    return 1;
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
                total_storage += parseInt(document.getElementById("unit_" + unit_ref).getAttribute("storage"));
            }
        }
        else
        {
            units_send[unit_ref] = 1;
            document.getElementById(unit_ref + "_selected").textContent = "1/" + max;
            total_storage += parseInt(document.getElementById("unit_" + unit_ref).getAttribute("storage"));
        }
        if (click == true)
            add_unit(unit_ref, max, nb + 1);
    }, speed);
}

function add_max(unit_ref, max)
{
    units_send[unit_ref] = max;
    document.getElementById(unit_ref + "_selected").textContent = max + "/" + max;
    total_storage = 0;
    for (var key in units_send)
        total_storage += parseInt((units_send[key] * document.getElementById("unit_" + key).getAttribute("storage")));
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
            total_storage -= parseInt(document.getElementById("unit_" + unit_ref).getAttribute("storage"));
        }
        if (click == true)
            remove_unit(unit_ref, max, nb + 1);
    }, speed);
}

function remove_all(unit_ref, max)
{
    if (units_send.hasOwnProperty(unit_ref))
        total_storage -= parseInt((units_send[unit_ref] * document.getElementById("unit_" + unit_ref).getAttribute("storage")));
    units_send[unit_ref] = 0;
    document.getElementById(unit_ref + "_selected").textContent = "0/" + max;
}

function add_res(res_ref, max, nb)
{
    if (nb > 5)
        speed = 75;
    else if (nb > 10)
        speed = 50;
    else if (nb > 20)
        speed = 10;
    setTimeout(function(){
        if (total_taken == total_storage)
        {
            console.log("total_taken : " + total_taken);
            return ;
        }
        else if (res_taken.hasOwnProperty(res_ref))
        {
            if (res_taken[res_ref] < max)
            {
                res_taken[res_ref]++;
                document.getElementById("res_" + res_ref + "_selected").textContent = res_taken[res_ref] + "/" + max;
                total_taken++;
                document.getElementById("fret").textContent = total_taken + "/" + total_storage;
            }
        }
        else
        {
            res_taken[res_ref] = 1;
            document.getElementById("res_" + res_ref + "_selected").textContent = "1/" + max;
            total_taken++;
            document.getElementById("fret").textContent = total_taken + "/" + total_storage;
        }
        console.log("total_taken : " + total_taken);
        if (click == true)
            add_res(res_ref, max, nb + 1);
    }, speed);
}

function add_max_res(res_ref, max)
{
    let res_max = max;
    if (total_taken + max > total_storage)
        max = (total_storage - total_taken);
    if (res_taken.hasOwnProperty(res_ref))
        res_taken[res_ref] += max;
    else
        res_taken[res_ref] = max;
    total_taken += max;
    document.getElementById("res_" + res_ref + "_selected").textContent = res_taken[res_ref] + "/" + res_max;
    document.getElementById("fret").textContent = total_taken + "/" + total_storage;
    console.log("total_taken : " + total_taken);
}

function remove_res(res_ref, max, nb)
{
    if (nb > 5)
        speed = 75;
    else if (nb > 10)
        speed = 50;
    else if (nb > 20)
        speed = 10;
    setTimeout(function(){
        if (res_taken.hasOwnProperty(res_ref) && res_taken[res_ref] > 0)
        {
            res_taken[res_ref]--;
            document.getElementById("res_" + res_ref + "_selected").textContent = res_taken[res_ref] + "/" + max;
            total_taken--;
            document.getElementById("fret").textContent = total_taken + "/" + total_storage;
        }
        console.log("total_taken : " + total_taken);
        if (click == true)
            remove_res(res_ref, max, nb + 1);
    }, speed);
}

function remove_all_res(res_ref, max)
{
    if (res_taken.hasOwnProperty(res_ref))
        total_taken -= res_taken[res_ref];
    res_taken[res_ref] = 0;
    document.getElementById("res_" + res_ref + "_selected").textContent = "0/" + max;
    document.getElementById("fret").textContent = total_taken + "/" + total_storage;
    console.log("total_taken : " + total_taken);
}

function reset_fret()
{
    total_taken = 0;
    document.getElementById("fret").textContent = "0/" + total_storage;
    for (var key in res_taken)
    {
        if (res_taken[key] == 0)
            continue ;
        else
        {
            let quantity = document.getElementById("res_" + key).getAttribute("quantity");
            document.getElementById("res_" + key + "_selected").textContent = "0/" + quantity;
            res_taken[key] = 0;
        }
    }
    console.log("total_taken : " + total_taken);
    console.log(res_taken);

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

function select_target_city(name)
{
    if (target_city != "")
    {
        document.getElementById("id_target_city_" + target_city).style.border = "1px solid lightblue";
        document.getElementById("target_city_" + target_city).style.display = "none";
    }
    if (target_city == name)
    {
        document.getElementById("id_target_city_" + target_city).style.border = "1px solid lightblue";
        document.getElementById("target_city_" + target_city).style.display = "none";
        target_city = "";
        return ;
    }
    target_city = name;
    document.getElementById("id_target_city_" + target_city).style.border = "1px solid lightgreen";
    document.getElementById("target_city_" + target_city).style.display = "";
}