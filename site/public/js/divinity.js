launch_all_timers();
var activeTab = document.getElementById("fat").getAttribute("divinity_active_tab") + "-tab";
var activePanel = document.getElementById("fat").getAttribute("divinity_active_tab") + "-panel";
document.getElementById(activeTab).className = "col-lg-3 col-md-3 col-sm-4 col-4 generique-tab-active";
document.getElementById(activePanel).style.display = '';

var disaster_id = -1;
var target_city = "";

setTimeout(() =>{
    let body_height = document.body.scrollHeight + 20;
    let win_height = window.innerHeight;
    if (body_height > win_height)
        document.getElementById("overlay").style.height = body_height + "px";
    else
        document.getElementById("overlay").style.height = win_height + "px";
}, 1000);

function switchTab(activeId)
{
    if (activeId + "-tab" === activeTab)
    {
        console.log('inactif');
        return ;
    }
    else
    {
        document.getElementById(activeTab).className = "col-lg-3 col-md-3 col-sm-4 col-4 generique-tab";
        document.getElementById(activeId + "-tab").className = "col-lg-3 col-md-3 col-sm-4 col-4 generique-tab-active";
        document.getElementById(activePanel).style.display = "none";
        document.getElementById(activeId + "-panel").style.display = "";
        activeTab = activeId + "-tab";
        activePanel = activeId + "-panel";
        var _token = document.getElementById('_token').value;
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'http://www.epicbattlecorp.fr/set_active_divinity');
        xhr.onreadystatechange =  function()
        {
            if (xhr.readyState === 4 && xhr.status === 200)
            {
                console.log(xhr.responseText);
                return ;
            }
        }
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send('_token=' + _token + '&active_tab=' + activeId);
    }
}

function show_desc(id)
{
    document.getElementById("overlay").style.display = "";
    document.getElementById("block_desc").style.display = "";
    let elem = document.getElementById(id);
    document.getElementById("block_desc_p").textContent = elem.getAttribute("desc");
    document.getElementById("block_desc_title").textContent = elem.getAttribute("name");
}

function ok()
{
    document.getElementById("overlay").style.display = "none";
    document.getElementById("block_desc").style.display = "none";
}

function choice_disaster_target(id)
{
    disaster_id = id;
    document.getElementById("main_panel").style.display = "none";
    document.getElementById("block_disaster_target").style.display = "";
}

function back_to_main()
{
    disaster_id = -1;
    document.getElementById("main_panel").style.display = "";
    document.getElementById("block_disaster_target").style.display = "none";
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
        console.log("Target City : " + target_city);
        return ;
    }
    target_city = name;
    document.getElementById("id_target_city_" + target_city).style.border = "1px solid lightgreen";
    document.getElementById("target_city_" + target_city).style.display = "";
    console.log("Target City2 : " + target_city);
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

function confirm_target()
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
    var _token = document.getElementById('_token').value;
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'http://www.epicbattlecorp.fr/check_disaster_target');
    xhr.onreadystatechange =  function()
    {
        //console.log(xhr.responseText);
        if (xhr.readyState === 4 && xhr.status === 200)
        {
            if (xhr.responseText.indexOf("error") >= 0)
            {
                console.log(xhr.responseText);
                if (xhr.responseText == "divinity error : cannot target allied")
                {
                    document.getElementById("error_cannot_target_allied").style.display = "";
                    setTimeout(function(){
                        document.getElementById("error_cannot_target_allied").style.display = "none";
                    }, 2500);
                }
                return ;
            }
            else
            {
                let rep = JSON.parse(xhr.responseText)
                console.log(rep);
                document.getElementById("confirm_disaster").style.display = "";
                document.getElementById("block_disaster_target").style.display = "none";
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
                        document.getElementById("disaster_target").textContent = rep.cell + " - " + rep.name + " (" + rep.x + "/" + rep.y + ")";
                    else
                        document.getElementById("disaster_target").textContent = rep.cell + " (" + rep.x + "/" + rep.y + ")";
                }
                document.getElementById("disaster_name").textContent = rep.name;
                return ;
            }
        }
    }
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    if (target_city == "")
    {
        x = parseInt(x);
        y = parseInt(y);
        xhr.send('_token=' + _token + '&disaster_id=' + disaster_id + "&x_pos=" + x + "&y_pos=" + y);
    }
    else
        xhr.send('_token=' + _token + '&disaster_id=' + disaster_id + "&target_city=" + target_city);
}

function back_choice_target()
{
    document.getElementById("confirm_disaster").style.display = "none";
    document.getElementById("block_disaster_target").style.display = "";
}

function trigger_disaster()
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
    document.getElementById("spin_disaster").style.display = "";
    var btn_tri_dis = document.getElementById("button_trigger_disaster");
    btn_tri_dis.style.display = "none";
    btn_tri_dis.disabled = true;
    var can_tri_dis = document.getElementById("cancel_trigger_disaster");
    can_tri_dis.style.display = "none";
    can_tri_dis.disabled = true;
    var _token = document.getElementById('_token').value;
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'http://www.epicbattlecorp.fr/trigger_disaster');
    xhr.onreadystatechange =  function()
    {
        console.log(xhr.responseText);
        if (xhr.readyState === 4 && xhr.status === 200)
        {
            if (xhr.responseText.indexOf("error") >= 0)
            {
                console.log(xhr.responseText);
                document.getElementById("spin_disaster").style.display = "none";
                btn_tri_dis.style.display = "";
                btn_tri_dis.disable = "";
                can_tri_dis.style.display = "";
                can_tri_dis.disabled = "";
                if (xhr.responseText == "divinity error : cannot target allied")
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
                document.getElementById("disaster_success").style.display = "";
                setTimeout(function(){
                    window.location.reload();
                }, 2500);
            }
        }
    }
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    if (target_city == "")
    {
        x = parseInt(x);
        y = parseInt(y);
        xhr.send('_token=' + _token + '&disaster_id=' + disaster_id + "&x_pos=" + x + "&y_pos=" + y);
    }
    else
        xhr.send('_token=' + _token + '&disaster_id=' + disaster_id + "&target_city=" + target_city);
}

function launch_all_timers()
{
    var timers = Array.prototype.slice.call(document.getElementsByClassName('cool-down'));
    if (timers.length == 0)
        return ;
    timers.forEach(function(e){
        timer(e.id, e.getAttribute("duration"));
    });
}

function timer(id, duration)
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
        compteur.textContent = " Terminé";
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
            compteur.textContent = compteur.getAttribute('quantity') + " " + j + " " + h + " " + m + " " + s;
        else
            compteur.textContent= j + " " + h + " " + m + " " + s;
        setTimeout(function(same_id=id, new_duration=duration-1){
            timer(same_id, new_duration);
        },1000);
    }
}