var g_choice = "";
setTimeout(() =>{
    let body_height = document.body.scrollHeight + 20;
    let win_height = window.innerHeight;
    if (body_height > win_height)
        document.getElementById("overlay").style.height = body_height + "px";
    else
        document.getElementById("overlay").style.height = win_height + "px";
}, 1000);
launch_all_timers();

function show_switch_block()
{
    document.getElementById("overlay").style.display = "";
    document.getElementById("block_change_city").style.display = "";
}

function choice_city(id)
{
    if (g_choice == "")
    {
        console.log("1");
        document.getElementById("city_" + id).className = "city-li-selected";
        g_choice = id;
    }
    else if (g_choice !== id)
    {   
        console.log("2");
        document.getElementById("city_" + g_choice).className = "city-li";
        g_choice = id;
        document.getElementById("city_" + id).className = "city-li-selected";
    }
    else
    {
        console.log("3");
        document.getElementById("city_" + g_choice).className = "city-li";
        g_choice = "";
    }
}

function switch_city()
{
    if (g_choice == "")
    {
        document.getElementById("error_empty_input2").style.display = "";
        setTimeout(() =>{
            document.getElementById("error_empty_input2").style.display = 'none';
        }, 5000);
        return ;
    }
    var _token = document.getElementById("_token").value;
    var xhr_switch = new XMLHttpRequest();
    xhr_switch.open('POST', 'http://www.epicbattlecorp.fr/switch_city');
    xhr_switch.onreadystatechange =  function()
    {
        if (xhr_switch.readyState === 4 && xhr_switch.status === 200)
        {
            let ret = xhr_switch.responseText;
            console.log(ret)
            if (ret == 0)
                window.location.reload();
            else
                console.log("error");
        }
    }
    xhr_switch.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr_switch.send('_token=' + _token + '&new_city_id=' + g_choice);
}

function cancel_switch()
{
    if (g_choice != "")
        document.getElementById("city_" + g_choice).className = "city-li";
    document.getElementById("overlay").style.display = "none";
    document.getElementById("block_change_city").style.display = "none";
    g_choice = "";
}

function show_edit_block()
{
    document.getElementById("overlay").style.display = "";
    document.getElementById("block_edit").style.display = "";
}

function cancel_rename()
{
    document.getElementById("new_name").value = "";
    document.getElementById("overlay").style.display = "none";
    document.getElementById("block_edit").style.display = "none";
}

function rename()
{
    var _token = document.getElementById("_token").value;
    var new_name = document.getElementById("new_name").value;
    if (new_name.length == "")
    {
        document.getElementById("error_empty_input").style.display = "";
        setTimeout(() =>{
            document.getElementById("error_empty_input").style.display = 'none';
        }, 5000);
        return ;
    }
    document.getElementById("rename_button").style.display = "none";
    document.getElementById("cancel_button").style.display = "none";
    document.getElementById("spin").style.display = "";
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'http://www.epicbattlecorp.fr/rename_city');
    xhr.onreadystatechange =  function()
    {
        if (xhr.readyState === 4 && xhr.status === 200)
        {
            ret = xhr.responseText;
            console.log(ret);
            if (ret == 0)
            {
                document.getElementById("name_changed").style.display = "";
                setTimeout(() =>{
                    document.getElementById("name_changed").style.display = 'none';
                    document.getElementById("city_name").textContent = document.getElementById("new_name").value;
                    document.getElementById("overlay").style.display = "none";
                    document.getElementById("block_edit").style.display = "none";
                    document.getElementById("rename_button").style.display = "";
                    document.getElementById("cancel_button").style.display = "";
                    document.getElementById("spin").style.display = "none";
                    document.getElementById("new_name").value = "";
                }, 3000);
            }
            else if (ret == 1)
            {
                document.getElementById("rename_button").style.display = "";
                document.getElementById("cancel_button").style.display = "";
                document.getElementById("spin").style.display = "none";
                document.getElementById("erro_invalid_input").style.display = "";
                setTimeout(() =>{
                    document.getElementById("erro_invalid_input").style.display = 'none';
                }, 5000);
            }
            else if (ret == 2)
            {
                document.getElementById("rename_button").style.display = "";
                document.getElementById("cancel_button").style.display = "";
                document.getElementById("spin").style.display = "none";
                document.getElementById("error_already_taken").style.display = "";
                setTimeout(() =>{
                    document.getElementById("error_already_taken").style.display = 'none';
                }, 5000);
            }
        }
    }
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send('_token=' + _token + '&new_name=' + new_name);
}

function interrupt(wait_id, type, div_id)
{
    var _token = document.getElementById("_token").value;
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'http://www.epicbattlecorp.fr/interrupt');
    xhr.onreadystatechange =  function()
    {
        if (xhr.readyState === 4 && xhr.status === 200)
        {
            if (xhr.responseText.indexOf("error") < 0)
            {
                let infos = JSON.parse(xhr.responseText);
                console.log(infos);
                if (infos.type != "explo")
                {
                    document.getElementById(div_id).remove();
                    document.getElementById("food").textContent = infos.food;
                    document.getElementById("wood").textContent = infos.wood;
                    document.getElementById("rock").textContent = infos.rock;
                    document.getElementById("steel").textContent = infos.steel;
                    document.getElementById("gold").textContent = infos.gold;
                }
                else
                {
                    let new_id = "id_" + infos.mission_name + div_id;
                    document.getElementById(div_id).id = new_id;
                    let compteur = document.getElementById(new_id).children[1];
                    compteur.textContent = "";
                    compteur.id = "compteur_" + infos.mission_name + div_id;
                    let red_cross_id = div_id.replace(/id/gi, "interrupt");
                    document.getElementById(red_cross_id).remove();
                    timer("compteur_" + infos.mission_name + div_id, infos.duration, infos.mission_name);
                    document.getElementById(div_id.replace(/id/gi, "icon")).className = "fas fa-map-marked-alt";
                }
                if (infos.type == "mounted_unit" || infos.type == "unit")
                {
                    if (infos.type == "mounted_unit")
                    {
                        let div_mount = document.getElementById("id_" + infos.mount.mount_id);
                        if (div_mount != null)
                            div_mount.textContent = infos.mount.quantity;
                        else
                        {
                            let parent = document.getElementById("unit_table").firstElementChild;
                            let new_tr = document.createElement("tr");
                            parent.insertBefore(new_tr, parent.lastElementChild.nextSibling);
                            let td_name = document.createElement("td");
                            let textNode = document.createTextNode(infos.mount.mount_id);
                            td_name.appendChild(textNode);
                            new_tr.insertBefore(td_name, new_tr.childNodes[0]);
                            let td_quantity = document.createElement("td");
                            td_quantity.id = "id_" + infos.mount.mount_id;
                            let textNode2 = document.createTextNode(infos.mount.quantity);
                            td_quantity.appendChild(textNode2);
                            new_tr.insertBefore(td_quantity, new_tr.lastElementChild.nextSibling);
                        }
                    }
                    if (infos.item.length > 0)
                    {
                        infos.item.forEach(function (e){
                            let div_quantity = document.getElementById("item_id_" + e.item_name);
                            if (div_quantity != null)
                                div_quantity.textContent = e.quantity;
                            else
                            {
                                let parent = document.getElementById("item_table").firstElementChild;
                                let new_tr = document.createElement("tr");
                                parent.insertBefore(new_tr, parent.lastElementChild.nextSibling);
                                let td_name = document.createElement("td");
                                let textNode = document.createTextNode(e.item_name);
                                td_name.appendChild(textNode);
                                new_tr.insertBefore(td_name, new_tr.childNodes[0]);
                                let td_quantity = document.createElement("td");
                                td_quantity.id = "item_id_" + e.item_name;
                                let textNode2 = document.createTextNode(e.quantity);
                                td_quantity.appendChild(textNode2);
                                new_tr.insertBefore(td_quantity, new_tr.lastElementChild.nextSibling);
                            }
                        });
                    }
                }
            }
            else
            {
                console.log(xhr.responseText);
                setTimeout(() =>{
                    window.location.reload();
                }, 3000);                     
            }
            
        }
    }
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send('_token=' + _token + '&wait_id=' + wait_id + "&type=" + type);
}
function launch_all_timers()
{
    var timers = Array.prototype.slice.call(document.getElementsByClassName('infos-building-wip'));
    if (timers.length == 0)
        return ;
    timers.forEach(function(e){
        timer(e.id, e.getAttribute("duration"), e.getAttribute("name"));
    });
}
function timer(id, duration, name)
{
    var compteur=document.getElementById(id);
    if (compteur == null)
        return ;
    var s=duration;
    var m=0;
    var h=0;
    var j = 0;
    if(s<=0)
    {
        let id_to_hide = id.replace(/compteur/gi, "interrupt");
        document.getElementById(id_to_hide).remove();
        compteur.textContent = name + " Terminé";
    }
    else
    {
        let new_time = "";
        if(s>59)
        {
            m=Math.floor(s/60);
            s=s - m * 60;
        }
        if(m>59)
        {
            h=Math.floor(m/60);
            m= m - h * 60;
        }
        if (h >= 24)
        {
            j=Math.floor(h/24);
            h = h - j * 24;
        }
        if(s<10 && s > 0)
        {
            s= "0" + s + " s";
        }
        else if (s == 0)
        {
            s = "";
        }
        else
        {
            s += " s";
        }
        if(m<10 && m > 0)
        {
            m= "0" + m + " m ";
        }
        else if (m == 0)
        {
            m = "";
        }
        else
        {
            m += " m ";
        }
        if (h < 10 && h > 0)
        {
            h= "0" + h + " h ";
        }
        else if (h == 0)
        {
            h = "";
        }
        else
        {
            h += " h ";
        }
        if (j < 10 && j > 0)
        {
            j = "0" + j + " j ";
        }
        else if (j == 0)
        {
            j = "";
        }
        else
        {
            j += " j " 
        }
        if (compteur.hasAttribute('quantity'))
            compteur.textContent = name + " " + compteur.getAttribute('quantity') + " " + j + " " + h + " " + m + " " + s;
        else
            compteur.textContent= name + " " + j + " " + h + " " + m + " " + s;
        setTimeout(function(same_id=id, new_duration=duration-1, same_name=name){
            timer(same_id, new_duration,same_name);
        },1000);
    }
}

function switch_part(id)
{
    let eye = document.getElementById(id + "_eye")
    let val = 1;
    let section = id.replace(/_table/, "")
    if (eye.className == "fas fa-eye-slash icon-eye")
    {
        document.getElementById(id).style.display = "none"
        eye.className = "fas fa-eye icon-eye"
        val = 0
    }
    else
    {
        document.getElementById(id).style.display = ""
        eye.className = "fas fa-eye-slash icon-eye"
    }
    console.log('section : ' + section)
    console.log('val : ' + val)
    var _token = document.getElementById("_token").value;
    var xhr_switch = new XMLHttpRequest();
    xhr_switch.open('POST', 'http://www.epicbattlecorp.fr/save_choice');
    xhr_switch.onreadystatechange =  function()
    {
        if (xhr_switch.readyState === 4 && xhr_switch.status === 200)
        {
            console.log(xhr_switch.responseText)
            console.log('choice saved !');
        }
    }
    xhr_switch.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr_switch.send('_token=' + _token + '&section=' + section + "&val=" + val);
}

var screen_width = window.innerWidth;
if (screen_width <= 563)
{
    document.getElementById('food').style.display = 'none';
    document.getElementById('compact_food').style.display = '';
    document.getElementById('wood').style.display = 'none';
    document.getElementById('compact_wood').style.display = '';
    document.getElementById('rock').style.display = 'none';
    document.getElementById('compact_rock').style.display = '';
    document.getElementById('steel').style.display = 'none';
    document.getElementById('compact_steel').style.display = '';
    document.getElementById('gold').style.display = 'none';
    document.getElementById('compact_gold').style.display = '';
}
window.onresize = function(){
    let new_screen_width = window.innerWidth;
    if (new_screen_width <= 563)
    {
        document.getElementById('food').style.display = 'none';
        document.getElementById('compact_food').style.display = '';
        document.getElementById('wood').style.display = 'none';
        document.getElementById('compact_wood').style.display = '';
        document.getElementById('rock').style.display = 'none';
        document.getElementById('compact_rock').style.display = '';
        document.getElementById('steel').style.display = 'none';
        document.getElementById('compact_steel').style.display = '';
        document.getElementById('gold').style.display = 'none';
        document.getElementById('compact_gold').style.display = '';
    }
    else
    {
        document.getElementById('food').style.display = '';
        document.getElementById('compact_food').style.display = 'none';
        document.getElementById('wood').style.display = '';
        document.getElementById('compact_wood').style.display = 'none';
        document.getElementById('rock').style.display = '';
        document.getElementById('compact_rock').style.display = 'none';
        document.getElementById('steel').style.display = '';
        document.getElementById('compact_steel').style.display = 'none';
        document.getElementById('gold').style.display = '';
        document.getElementById('compact_gold').style.display = 'none';
    }
}