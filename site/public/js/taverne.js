var g_slot = 0;
timer("main_timing", document.getElementById("main_timing").getAttribute("duration"));
let timing_slot1 = document.getElementById("timing_slot1");
let timing_slot2 = document.getElementById("timing_slot2");
let timing_slot3 = document.getElementById("timing_slot3");
if (timing_slot1 != null)
    timer("timing_slot1", timing_slot1.getAttribute("duration"));
if (timing_slot2 != null)
    timer("timing_slot2", timing_slot2.getAttribute("duration"));
if (timing_slot3 != null)
    timer("timing_slot3", timing_slot3.getAttribute("duration"));

function get_body_height()
{
    let body_height = document.body.scrollHeight + 20;
    let win_height = window.innerHeight;
    if (body_height > win_height)
        return (body_height + "px");
    else
        return (win_height + "px");
}

function upgrade(slot)
{
    g_slot = slot;
    document.getElementById('overlay').style.height = get_body_height();
    document.getElementById('overlay').style.display = "";
    document.getElementById('block_upgrade').style.display = "";
}

function cancel_upgrade()
{
    g_slot = 0;
    document.getElementById('overlay').style.display = "none";
    document.getElementById('block_upgrade').style.display = "none";
}

function confirm_upgrade()
{
    var _token = document.getElementById("_token").value;
    document.getElementById("spin_upgrade").style.display = "";
    document.getElementById("confirm_upgrade_button").style.display = "none";
    document.getElementById("cancel_upgrade_button").style.display = "none";
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'http://www.epicbattlecorp.fr/upgrade_mercenary');
    xhr.onreadystatechange =  function()
    {
        if (xhr.readyState === 4 && xhr.status === 200)
        {
            if (xhr.responseText == "Success")
            {
                document.getElementById("slot" + g_slot + "_qt").textContent = 5000;
                document.getElementById("success_mercenary_upgraded").style.display = "";
                g_slot = 0;
                setTimeout(() =>{
                    document.getElementById("success_mercenary_upgraded").style.display = 'none';
                    document.getElementById("spin_upgrade").style.display = "none";
                    document.getElementById("block_upgrade").style.display = "none";
                    document.getElementById("overlay").style.display = "none";
                    document.getElementById("confirm_upgrade_button").style.display = "";
                    document.getElementById("cancel_upgrade_button").style.display = "";
                }, 2000);
            }
            else
            {
                var type_error = "";
                g_slot = 0;
                if (xhr.responseText == "Error upgrade : bad slot" || xhr.responseText == "Error upgrade : slot locked" || xhr.responseText == "Error upgrade : slot unavailable")
                    type_error = "error_hacker";
                else if (xhr.responseText == "Error upgrade : maximum quantity reach")
                    type_error = "error_max";
                else
                    type_error = "error_diamond";
                document.getElementById(type_error).style.display = "";
                setTimeout(() =>{
                    document.getElementById("spin_upgrade").style.display = "none";
                    document.getElementById("block_upgrade").style.display = "none";
                    document.getElementById("overlay").style.display = "none";
                    document.getElementById(type_error).style.display = "none";
                    document.getElementById("confirm_upgrade_button").style.display = "";
                    document.getElementById("cancel_upgrade_button").style.display = "";
                }, 5000);
            }
        }
    }
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send('_token=' + _token + '&slot=' + g_slot);
}

function randomize(slot)
{
    g_slot = slot;
    document.getElementById('overlay').style.height = get_body_height();
    document.getElementById('overlay').style.display = "";
    document.getElementById('block_randomize').style.display = "";
}

function cancel_randomize()
{
    g_slot = 0;
    document.getElementById('overlay').style.display = "none";
    document.getElementById('block_randomize').style.display = "none";
}

function confirm_randomize()
{
    var _token = document.getElementById("_token").value;
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'http://www.epicbattlecorp.fr/randomize_mercenary');
    xhr.onreadystatechange =  function()
    {
        if (xhr.readyState === 4 && xhr.status === 200)
        {
            if (xhr.responseText == "Succes")
            {
                window.location.reload();
            }
            else
            {
                console.log(xhr.responseText);
                return ;
            }
        }
    }
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send('_token=' + _token + '&slot=' + slot);
}


function timer(id, duration)
            {
                var compteur=document.getElementById(id);
                var s=duration;
                var m=0;
                var h=0;
                var j = 0;
                if(s<=0)
                {
                    compteur.textContent = "Terminé";
                    window.location.reload();
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
                        j = Math.floor(h/24);
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
                        j += " j ";
                    }
                    compteur.textContent = j + " " + h+" "+m+" "+s;
                    setTimeout(function(same_id=id, new_duration=duration-1){
                        timer(same_id, new_duration);
                    },1000);
                }
            }