import { get } from 'lang.js';
var g_unit_id = 0;
var unit_timing = document.getElementById('unit_timer');
if (unit_timing !== null)
    timer('unit_timer', unit_timing.getAttribute("duration"));

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
        $cancel_button = document.getElementById("interrupt_unit_button");
        $cancel_button.className = "army-button";
        $cancel_button.value = "Ok";
        $cancel_button.onclick = function(){window.location.reload();};
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
        if (j >=24)
        {
            j = Math.floor(h/24);
            h = h - j *24;
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
            j = "0" + j + " j "
        }
        else if (j == 0)
        {
            j = "";
        }
        else
        {
            j += " j"
        }
        compteur.textContent= get('common.in_progress') + " : " + j + " " + h+" "+m+" "+s;
        setTimeout(function(same_id=id, new_duration=duration-1){
            timer(same_id, new_duration);
        },1000);
    }
}

function interrupt_unit()
{
    var _token = document.getElementById("_token").value;
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'http://www.epicbattlecorp.fr/interrupt');
    xhr.onreadystatechange =  function()
    {
        if (xhr.readyState === 4 && xhr.status === 200)
        {
            window.location.reload();
        }
    }
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send('_token=' + _token + '&type=unit');
    setTimeout(() => {
        window.location.reload();
    }, 300);
}

function confirm()
{
    var _token = document.getElementById("_token").value;
    var unit_id = g_unit_id;
    var quantity = document.getElementById("input_" + unit_id).value;
    var xhr2 = new XMLHttpRequest();
    xhr2.open('POST', 'http://www.epicbattlecorp.fr/train_unit');
    xhr2.onreadystatechange =  function()
    {
        if (xhr2.readyState === 4 && xhr2.status === 200)
        {
            if (xhr2.responseText == "Good")
                window.location.reload();
            else
            {
                console.log(xhr2.responseText);
                return ;
            }
        }
    }
    xhr2.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr2.send('_token=' + _token + '&unit_id=' + unit_id + "&quantity=" + quantity);
}

function cancel()
{
    g_unit_id = 0;
    document.getElementById("confirm_win").style.display = "none";
    document.getElementById("unit_list").style.display = "";
}

function train(id)
{
    g_unit_id = id;
    var _token = document.getElementById("_token").value;
    var quantity = document.getElementById("input_" + id).value;
    if (quantity == "")
    {
        document.getElementById("error_empty_input").style.display = "";
        setTimeout(() =>{
            document.getElementById("error_empty_input").style.display = 'none';
        }, 5000);
        return ;
    }
    else if (!(!isNaN(parseFloat(quantity)) && isFinite(quantity)))
    {
        document.getElementById("error_bad_input").style.display = "";
        setTimeout(() =>{
            document.getElementById("error_bad_input").style.display = 'none';
        }, 5000);
        return ;
    }
    else if (parseInt(quantity) <= 0)
    {
        document.getElementById("error_negative_value").style.display = "";
        setTimeout(() =>{
            document.getElementById("error_negative_value").style.display = 'none';
        }, 5000);
        return ;
    }
    quantity = parseInt(quantity);
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'http://www.epicbattlecorp.fr/calculate_training_price');
    xhr.onreadystatechange =  function()
    {
        if (xhr.readyState === 4 && xhr.status === 200)
        {
            if (xhr.responseText.indexOf("error") >= 0)
            {
                console.log(xhr.responseText);
                return ;
            }
            //console.log(xhr.responseText);
            Response.Charset="iso-8859-1";
            //console.log("Après : " + xhr.responseText);
            for (let i = 6; i < 16; i++)
                document.getElementById("list" + i).style.display = "none";
            var ressources_need = xhr.responseText.split("[");
            var basic = ressources_need[1].replace(/"/gi, "").split(",");
            //console.log(basic);
            var items = ressources_need[2].replace(/[{}"\]]/gm, "").split(",");
            //console.log(items);
            if (basic[0] == "KO")
            {
                document.getElementById("confirm-button").style.display = "none";
                document.getElementById("confirm-button").disabled = "true";
            }
            else
            {
                document.getElementById("confirm-button").style.display = "";
                document.getElementById("confirm-button").disabled = "";  
            }
            document.getElementById("confirm-title").textContent = get('army.train') + " " + quantity + " " + name + " ?";
            if (basic[1] > 0)
            {
                document.getElementById("food_list").textContent = get('common.food') + " : " +  basic[1];
                document.getElementById("food_icon").className = basic[2];
                document.getElementById("list1").style.display = "";
            }
            else
                document.getElementById("list1").style.display = "none";
            if (basic[3] > 0)
            {
                document.getElementById("wood_list").textContent = get('common.wood') + " : " +  basic[3];
                document.getElementById("wood_icon").className = basic[4];
                document.getElementById("list2").style.display = "";
            }
            else
                document.getElementById("list2").style.display = "none";
            if (basic[5] > 0)
            {
                document.getElementById("rock_list").textContent = get('common.rock') + " : " +  basic[5];
                document.getElementById("rock_icon").className = basic[6];
                document.getElementById("list3").style.display = "";
            }
            else
                document.getElementById("list3").style.display = "none";
            if (basic[7] > 0)
            {
                document.getElementById("steel_list").textContent = get('common.steel') + " : " +  basic[7];
                document.getElementById("steel_icon").className = basic[8];
                document.getElementById("list4").style.display = "";
            }
            else
                document.getElementById("list4").style.display = "none";
            if (basic[9] > 0)
            {
                document.getElementById("gold_list").textContent = get('common.gold') + " : " +  basic[9];
                document.getElementById("gold_icon").className = basic[10];
                document.getElementById("list5").style.display = "";
            }
            else
                document.getElementById("list5").style.display = "none";
            if (basic[11] == 0)
                document.getElementById("list_last").style.display = "none";
            else
            {
                document.getElementById("mount_list").textContent = get('army.mount') + " : " +  basic[11] + " x" + quantity;
                document.getElementById("mount_icon").className = basic[12];
                document.getElementById("list_last").style.display = "";
            }
            document.getElementById("time_list").textContent = get('common.time') + " : " +  basic[13] + " ";
            var i = 0;
            items.forEach(function(e){
                let e_split = e.split(":");
                let type = e_split[0];
                let value = unescape(e_split[1]);
                //console.log("Type : " + type);
                //console.log("Value : " + value);
                if (type == "item")
                {
                    document.getElementById("list" + (i + 6)).style.display = "";
                    document.getElementById("item_list" + i).textContent = value + " x" + quantity;
                }
                else
                {
                    document.getElementById("item_" + i + "_icon").className = value;
                    i++;
                }
            });
            document.getElementById("unit_list").style.display = "none";
            document.getElementById("confirm_win").style.display = "";
        }
    }
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send('_token=' + _token + '&unit_id=' + id + "&quantity=" + quantity);
}