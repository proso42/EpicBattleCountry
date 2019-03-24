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
                @if($allowed == 0)
                    <p>Vous devez construire uen Forge avant de pouvoir l'utiliser !</p>
                @elseif ($allowed == -1)
                    <div class="confirm-win">
                        <h3>Production en cours</h3>
                        <p>{{ $waiting_item['name'] }} x{{ $waiting_item['quantity'] }}</p>
                        <p id="item_timer" duration="{{ $waiting_item['finishing_date']}} "></p>
                        <input type="button" class="forge-button-cancel" value="Annuler">
                    </div>
                @else
                    <div id="items_list">
                    @foreach ($allowed_items as $item)
                        <div id="id_{{ $item['name'] }}" class="row" style="align-items: baseline;line-height: 31px;">
                            <span class="forge-item offset-lg-2 offset-md-2 offset-sm-2 offset-2 col-lg-2 col-md-2 col-sm-2 col-2" style="text-align:center">{{ $item['name'] }}</span>
                            <input id="input_{{ $item['name'] }}" type="text" placeholder="Quantité" class="forge-input col-lg-2 col-md-2 col-sm-2 col-2">
                            <div class="forge-ressources col-lg-2 col-md-2 col-sm-2 col-2">
                                @if ($item['food_required'] > 0)
                                    <img class="forge-image" src="images/food.png"> x{{ $item['food_required'] }}
                                @endif
                                @if ($item['wood_required'] > 0)
                                    <img class="forge-image" src="images/wood.png"> x{{ $item['wood_required'] }}
                                @endif
                                @if ($item['rock_required'] > 0)
                                    <img class="forge-image" src="images/rock.png"> x{{ $item['rock_required'] }}
                                @endif
                                @if ($item['steel_required'] > 0)
                                    <img class="forge-image" src="images/steel.png"> x{{ $item['steel_required'] }}
                                @endif
                                @if ($item['gold_required'] > 0)
                                    <img class="forge-image" src="images/gold.png"> x{{ $item['gold_required'] }}
                                @endif
                                <i class="fas fa-clock"></i> {{ $item['duration'] }}
                            </div>
                            <input onclick="craft('{{ $item['name'] }}')" type="button" class="forge-button col-lg-2 col-md-2 col-sm-2 col-2" value="Produire">
                        </div>
                    @endforeach
                    </div>
                    <div id="confirm_win" class="confirm-win" style="display: none">
                        <h3 id="confirm-title"style="margin-top: 25px"></h3>
                        <ul style="text-align:left;margin-top: 25px;">
                            <li id="list1"><span style="margin-right:5px" id="food_list"></span><i id="food_icon" class=""></i></li>
                            <li id="list2"><span style="margin-right:5px" id="wood_list"></span><i id="wood_icon" class=""></i></li>
                            <li id="list3"><span style="margin-right:5px" id="rock_list"></span><i id="rock_icon" class=""></i></li>
                            <li id="list4"><span style="margin-right:5px" id="steel_list"></span><i id="steel_icon" class=""></i></li>
                            <li id="list5"><span style="margin-right:5px" id="gold_list"></span><i id="gold_icon" class=""></i></li>
                            <li><span style="margin-right:5px" id="time_list"></span><i class="fas fa-clock"></i></li>
                        </ul>
                        <input onclick="confirm()" id="confirm-button" type="button" class="forge-button" value="Confirmer">
                        <input onclick="cancel()" type="button" class="forge-button-cancel" value="Annuler">
                    </div>
                @endif
            </div>
        </div>
        <input id="_token" name="_token" type="hidden" value="{{csrf_token()}}">
        <script>

            var g_name = "";
            var item_timing = document.getElementById('item_timer');
            if (item_timing !== null)
                timer('item_timer', item_timing.getAttribute("duration"));

            function timer(id, duration)
            {
                var compteur=document.getElementById(id);
                var s=duration;
                var m=0;
                var h=0;
                if(s<=0)
                    compteur.textContent = "Terminé";
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

            function cancel()
            {
                g_name = "";
                document.getElementById("confirm_win").style.display = "none";
                document.getElementById("items_list").style.display = "";
            }

            function confirm()
            {
                var _token = document.getElementById("_token").value;
                var name = g_name;
                var name_format = name.replace(/\s/gi, "_");
                var quantity = document.getElementById("input_" + name).value;
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'http://www.epicbattlecorp.fr/craft_item');
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

            function craft(name)
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
                var name_format = name.replace(/\s/gi, "_");
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'http://www.epicbattlecorp.fr/calculate_price');
                xhr.onreadystatechange =  function()
                {
                    if (xhr.readyState === 4 && xhr.status === 200)
                    {
                        if (xhr.responseText === "item_error")
                            return ;
                        var need_tab = xhr.responseText.replace(/]/gi, "").replace(/\[/gi, "").replace(/"/gi, "").split(",");
                        var new_duration = need_tab[11];
                        if (need_tab[0] == "KO")
                        {
                            document.getElementById("confirm-button").style.display = "none";
                            document.getElementById("confirm-button").disabled = "true";   
                        }
                        else
                        {
                            document.getElementById("confirm-button").style.display = "";
                            document.getElementById("confirm-button").disabled = "";
                        }
                        document.getElementById("confirm-title").textContent = "Fabriquer " + quantity + " " + name + " ?";
                        if (need_tab[1] > 0)
                        {
                            document.getElementById("food_list").textContent = "Food : " +  need_tab[1];
                            document.getElementById("food_icon").className = need_tab[2];
                            document.getElementById("list1").style.display = "";
                        }
                        else
                            document.getElementById("list1").style.display = "none";
                        if (need_tab[3] > 0)
                        {
                            document.getElementById("wood_list").textContent = "Wood : " +  need_tab[3];
                            document.getElementById("wood_icon").className = need_tab[4];
                            document.getElementById("list2").style.display = "";
                        }
                        else
                            document.getElementById("list2").style.display = "none";
                        if (need_tab[5] > 0)
                        {
                            document.getElementById("rock_list").textContent = "Rock : " +  need_tab[5];
                            document.getElementById("rock_icon").className = need_tab[6];
                            document.getElementById("list3").style.display = "";
                        }
                        else
                            document.getElementById("list3").style.display = "none";
                        if (need_tab[7] > 0)
                        {
                            document.getElementById("steel_list").textContent = "Steel : " +  need_tab[7];
                            document.getElementById("steel_icon").className = need_tab[8];
                            document.getElementById("list4").style.display = "";
                        }
                        else
                            document.getElementById("list4").style.display = "none";
                        if (need_tab[9] > 0)
                        {
                            document.getElementById("gold_list").textContent = "Gold : " +  need_tab[9];
                            document.getElementById("gold_icon").className = need_tab[10];
                            document.getElementById("list5").style.display = "";
                        }
                        else
                            document.getElementById("list5").style.display = "none";
                        document.getElementById("time_list").textContent = "Time : " +  new_duration + " ";
                        document.getElementById('items_list').style.display = "none";
                        document.getElementById('confirm_win').style.display = "";
                    }
                }
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.send('_token=' + _token + '&name=' + name_format + "&quantity=" + quantity);
            }
        </script>
    </body>
</html>