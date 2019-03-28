<!DOCTYPE html>
<html>
<head>
        <meta charset="utf8">
        <title>EpicBattleCorp</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/style.css"/>
    </head>
    <body>
        @include('default')
            <div class="offset-lg-0 offset-md-2 offset-sm-1 offset-1 col-lg-9 col-md-7 col-sm-10 col-10 center-win" style="margin-top: 50px; padding-right: 10px;">
                <div id="error_empty_input" class="forge-input-error" style="display: none;">
                    <p>Merci de remplir le champs !</p>
                </div>
                <div id="error_bad_input" class="forge-input-error" style="display: none;">
                    <p>Merci de remplir correctement le champs !</p>
                </div>
                <div id="error_negative_value" class="forge-input-error" style="display: none;">
                    <p>Merci de renseigner une valeur supérieur à zéro !</p>
                </div>
                @if ($allowed == 0)
                    <p>Vous devez construire une caserne avant de pouvoir former des unitées !</p>
                @elseif ($allowed == -1)
                    <div class="confirm-win">
                        <h3>Entraimenemt en cours</h3>
                        <p>{{ $waiting_unit['name'] }} x{{ $waiting_unit['quantity'] }}</p>
                        <p id="unit_timer" duration="{{ $waiting_unit['finishing_date']}} "></p>
                        <input id="interrupt_unit_button" onclick="interrupt_unit()" type="button" class="army-button-cancel" value="Annuler">
                    </div>
                @else
                    <div id="unit_list">
                        @foreach ($allowed_units as $unit)
                            <div id="id_{{ $unit['name'] }}" class="row" style="align-items: baseline;line-height: 31px;">
                                <div class="army-unit offset-lg-2 offset-md-2 offset-sm-2 offset-2 col-lg-2 col-md-2 col-sm-2 col-2" style="text-align:center">
                                    <span>{{ $unit['name'] }}</span>
                                    <div class="army-info-unit">
                                        <ul>
                                            <li>Life : {{ $unit['life'] }} <i class="fas fa-heartbeat"></i></li>
                                            <li>Speed : {{ $unit['speed'] }} <i class="fas fa-tachometer-alt"></i></li>
                                            <li>Power : {{ $unit['power'] }} <i class="fas fa-fist-raised"></i></li>
                                        </ul>
                                    </div>
                                </div>
                                <input id="input_{{ $unit['name'] }}" type="text" placeholder="Quantité" class="army-input col-lg-2 col-md-2 col-sm-2 col-2">
                                <div class="army-ressources col-lg-2 col-md-2 col-sm-2 col-2">
                                    <span>Ressources Items</span>
                                    <div class="army-ressources-details">
                                        <ul>
                                            @if ($unit['food'] > 0)
                                                <li>Food : {{ $unit['food'] }}</li>
                                            @endif
                                            @if ($unit['wood'] > 0)
                                                <li>Wood : {{ $unit['wood'] }}</li>
                                            @endif
                                            @if ($unit['rock'] > 0)
                                                <li>Rock : {{ $unit['rock'] }}</li>
                                            @endif
                                            @if ($unit['steel'] > 0)
                                                <li>Steel : {{ $unit['steel'] }}</li>
                                            @endif
                                            @if ($unit['gold'] > 0)
                                                <li>Gold : {{ $unit['gold'] }}</li>
                                            @endif
                                            @if ($unit['items'] !== null)
                                                @foreach ($unit['items'] as $item)
                                                    <li>{{ $item['name'] }} <i class="fas fa-cog icon"></i></li>
                                                @endforeach
                                            @endif
                                            @if ($unit['mount'] !== null)
                                                <li>Monture : {{ $unit['mount'] }} <i class="fas fa-paw"></i></li>
                                            @endif
                                            <li>Time : {{ $unit['duration'] }} <i class="fas fa-clock"></i></li>
                                        </ul>
                                    </div>
                                </div>
                                <input onclick="train('{{ $unit['name'] }}')" type="button" class="army-button col-lg-2 col-md-2 col-sm-2 col-2" value="Entrainer">
                            </div>
                        @endforeach
                    </div>
                    <div id="confirm_win" class="confirm-win" style="display: none">
                        <h3 id="confirm-title" style="margin-top: 25px"></h3>
                        <ul style="text-align:left;margin-top: 25px;">
                            <li id="list1"><span style="margin-right:5px" id="food_list"></span><i id="food_icon" class=""></i></li>
                            <li id="list2"><span style="margin-right:5px" id="wood_list"></span><i id="wood_icon" class=""></i></li>
                            <li id="list3"><span style="margin-right:5px" id="rock_list"></span><i id="rock_icon" class=""></i></li>
                            <li id="list4"><span style="margin-right:5px" id="steel_list"></span><i id="steel_icon" class=""></i></li>
                            <li id="list5"><span style="margin-right:5px" id="gold_list"></span><i id="gold_icon" class=""></i></li>
                            @for ($i = 0; $i < 10; $i++)
                                <li id="list{{ $i + 6}}"><span style="margin-right: 5px;" id="item_list{{ $i }}"></span><i id="item_{{ $i }}_icon" class=""></i></li>
                            @endfor
                            <li id="list_last"><span style="margin-right:5px" id="mount_list"></span><i id="mount_icon" class=""></i></li>
                            <li><span style="margin-right:5px" id="time_list"></span><i class="fas fa-clock"></i></li>
                        </ul>
                        <input onclick="confirm()" id="confirm-button" type="button" class="army-button" value="Confirmer">
                        <input onclick="cancel()" type="button" class="army-button-cancel" value="Annuler">
                    </div>
                @endif
            </div>
        </div>
        <input id="_token" name="_token" type="hidden" value="{{csrf_token()}}">
        <script>

            var g_name = "";
            var unit_timing = document.getElementById('unit_timer');
            if (unit_timing !== null)
                timer('unit_timer', unit_timing.getAttribute("duration"));

            function timer(id, duration)
            {
                var compteur=document.getElementById(id);
                var s=duration;
                var m=0;
                var h=0;
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
                    compteur.textContent= "In Progress : " + h+" "+m+" "+s;
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
            }

            function confirm()
            {
                var _token = document.getElementById("_token").value;
                var name = g_name;
                var name_format = name.replace(/\s/gi, "_");
                var quantity = document.getElementById("input_" + name).value;
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'http://www.epicbattlecorp.fr/train_unit');
                xhr.onreadystatechange =  function()
                {
                    if (xhr.readyState === 4 && xhr.status === 200)
                    {
                        window.location.reload();
                    }
                }
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.send('_token=' + _token + '&name=' + name_format + "&quantity=" + quantity);
                setTimeout(() => {
                    window.location.reload();
                }, 300);
            }

            function cancel()
            {
                g_name = "";
                document.getElementById("confirm_win").style.display = "none";
                document.getElementById("unit_list").style.display = "";
            }

            function train(name)
            {
                g_name = name;
                var _token = document.getElementById("_token").value;
                var quantity = document.getElementById("input_" + name).value;
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
                var name_format = name.replace(/\s/gi, "_");
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'http://www.epicbattlecorp.fr/calculate_training_price');
                xhr.onreadystatechange =  function()
                {
                    if (xhr.readyState === 4 && xhr.status === 200)
                    {
                        if (xhr.responseText === "unit_error")
                        {
                            console.log('unit_error');
                            return ;
                        }
                        console.log(xhr.responseText);
                        Response.Charset="iso-8859-1";
                        console.log("Après : " + xhr.responseText);
                        for (let i = 6; i < 16; i++)
                            document.getElementById("list" + i).style.display = "none";
                        var ressources_need = xhr.responseText.split("[");
                        var basic = ressources_need[1].replace(/"/gi, "").split(",");
                        console.log(basic);
                        var items = ressources_need[2].replace(/[{}"\]]/gm, "").split(",");
                        console.log(items);
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
                        document.getElementById("confirm-title").textContent = "Entrainer " + quantity + " " + name + " ?";
                        if (basic[1] > 0)
                        {
                            document.getElementById("food_list").textContent = "Food : " +  basic[1];
                            document.getElementById("food_icon").className = basic[2];
                            document.getElementById("list1").style.display = "";
                        }
                        else
                            document.getElementById("list1").style.display = "none";
                        if (basic[3] > 0)
                        {
                            document.getElementById("wood_list").textContent = "Wood : " +  basic[3];
                            document.getElementById("wood_icon").className = basic[4];
                            document.getElementById("list2").style.display = "";
                        }
                        else
                            document.getElementById("list2").style.display = "none";
                        if (basic[5] > 0)
                        {
                            document.getElementById("rock_list").textContent = "Rock : " +  basic[5];
                            document.getElementById("rock_icon").className = basic[6];
                            document.getElementById("list3").style.display = "";
                        }
                        else
                            document.getElementById("list3").style.display = "none";
                        if (basic[7] > 0)
                        {
                            document.getElementById("steel_list").textContent = "Steel : " +  basic[7];
                            document.getElementById("steel_icon").className = basic[8];
                            document.getElementById("list4").style.display = "";
                        }
                        else
                            document.getElementById("list4").style.display = "none";
                        if (basic[9] > 0)
                        {
                            document.getElementById("gold_list").textContent = "Gold : " +  basic[9];
                            document.getElementById("gold_icon").className = basic[10];
                            document.getElementById("list5").style.display = "";
                        }
                        else
                            document.getElementById("list5").style.display = "none";
                        if (basic[11] == 0)
                            document.getElementById("list_last").style.display = "none";
                        else
                        {
                            document.getElementById("mount_list").textContent = "Mount : " +  basic[11] + " x" + quantity;
                            document.getElementById("mount_icon").className = basic[12];
                            document.getElementById("list_last").style.display = "";
                        }
                        document.getElementById("time_list").textContent = "Time : " +  basic[13] + " ";
                        var i = 0;
                        items.forEach(function(e){
                            let e_split = e.split(":");
                            let type = e_split[0];
                            let value = unescape(e_split[1]);
                            console.log("Type : " + type);
                            console.log("Value : " + value);
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
                xhr.send('_token=' + _token + '&name=' + name_format + "&quantity=" + quantity);
            }
        </script>
    </body>
</html>