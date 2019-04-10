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
        <div id="overlay" class="home-overlay" style="display: none">
        </div>
        <div id="block_edit" class="edit-city-name-block" style="display: none">
            <div id="error_empty_input" class="home-input-error" style="display: none;">
                <p>@lang('home.thx_fill_field')</p>
            </div>
            <div id="error_invalid_input" class="home-input-error" style="display: none;">
                <p>Nom invalide !</p>
            </div>
            <div id="error_already_taken" class="home-input-error" style="display: none;">
                <p>Ce nom est déjà pris !</p>
            </div>
            <div id="name_changed" class="home-input-success" style="display: none;">
                <p>Le nom a été changé !</p>
            </div>
            <h3>Changer le nom de la ville</h3>
            <input id="new_name" type="text" class="edit-city-name-input" placeholder="Nouveau nom">
            <br/>
            <input onclick="rename()" id="rename_button" type="button" class="home-button" value="Renommer">
            <input onclick="cancel_rename()" id="cancel_button" type="button" class="home-button-cancel" value="Annuler">
            <img id="spin" class="explo-spin" style="display: none" src="images/loader.gif">
        </div>
        @if (count($user_cities) > 0)
            <div id="error_empty_input2" class="home-input-error" style="display: none;">
                <p>Merci de remplir le champs !</p>
            </div>
            <div id="block_change_city" class="edit-city-name-block" style="display: none">
                <div id="city_list">
                    <ul>
                        @foreach ($user_cities as $city)
                            <li onclick="choice_city('{{ $city->id }}')" id="city_{{ $city->id }}" class="city-li">{{ $city->name }}</li>
                        @endforeach
                        <input onclick="switch_city()" id="switch_button" type="button" class="home-button" value="Changer">
                    </ul>
                </div>
            </div>
        @endif
        @include('default')
            <div class="offset-lg-0 offset-md-2 offset-sm-1 offset-1 col-lg-9 col-md-7 col-sm-10 col-10 center-win" style="margin-top: 50px; padding-right: 10px;">
                <div style="text-align:center;display: inline-block">
                    <h2 id="city_name">{{ $city_name }} <i onclick="show_edit_block()" class="fas fa-edit" style="cursor:pointer;margin-left: 25px;font-size: 15px"></i> @if (count($user_cities) > 0) <i onclick="show_switch_block()" class="fas fa-sync-alt" style="cursor:pointer;margin-left: 25px;font-size: 15px"></i>@endif</h2> 
                </div>
                <hr class="signin-footer">
                <div class="prod-div">
                    <h3 class="home-title-table">Production <i id="prod_table_eye" onclick="switch_part('prod_table')" @if($tables_class['prod'] == 0) class="fas fa-eye icon-eye" @else class="fas fa-eye-slash icon-eye" @endif></i></h3>
                    <table id="prod_table" class="prod-table" @if($tables_class['prod'] == 0) style="display: none" @endif>
                        <tr>
                            <td>Ressources</td><td>Prod par heure</td><td>Stockage Max</td>
                        </tr>
                        <tr>
                            <td>Nourriture</td><td>{{ $food_prod * 60 }}</td><td>{{ $max_food }}</td>
                        </tr>
                        <tr>
                            <td>Bois</td><td>{{ $wood_prod * 60 }}</td><td>{{ $max_wood }}</td>
                        </tr>
                        <tr>
                            <td>Pierre</td><td>{{ $rock_prod * 60 }}</td><td>{{ $max_rock }}</td>
                        </tr>
                        <tr>
                            <td>Fer</td><td>{{ $steel_prod * 60 }}</td><td>{{ $max_steel }}</td>
                        </tr>
                        <tr>
                            <td>Or</td><td>{{ $gold_prod * 60 }}</td><td>{{ $max_gold }}</td>
                        </tr>
                    </table>
                </div>
                @if (count($items_owned) > 0)
                <hr class="signin-footer">
                <div class="prod-div">
                    <h3 class="home-title-table">Items disponibles <i id="item_table_eye" onclick="switch_part('item_table')" @if($tables_class['item'] == 0) class="fas fa-eye icon-eye" @else class="fas fa-eye-slash icon-eye" @endif></i></h3>
                    <table id="item_table" class="prod-table" @if($tables_class['item'] == 0) style="display: none" @endif>
                        <tr>
                            <td>Item</td><td>Stock</td>
                        </tr>
                        @foreach ($items_owned as $item)
                            <tr>
                                <td>{{ $item['name'] }}</td><td>{{ $item['quantity'] }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                @endif
                @if (count($units_owned) > 0)
                <hr class="signin-footer">
                <div class="prod-div">
                    <h3 class="home-title-table">Unitées disponibles <i id="unit_table_eye" onclick="switch_part('unit_table')" @if($tables_class['unit'] == 0) class="fas fa-eye icon-eye" @else class="fas fa-eye-slash icon-eye" @endif></i></h3>
                    <table id="unit_table" class="prod-table" @if($tables_class['unit'] == 0) style="display: none" @endif>
                        <tr>
                            <td>Unit</td><td>Stock</td>
                        </tr>
                        @foreach ($units_owned as $unit)
                            <tr>
                                <td>{{ $unit['name'] }}</td><td>{{ $unit['quantity'] }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                @endif
                @if (count($waiting_list) > 0)
                    <hr class="signin-footer">
                    <div class="waiting-list" style="margin-bottom: 20px;">
                        @foreach ($waiting_list as $elem)
                            <div id="id_{{$elem['name']}}" class="row">
                                <div class="offset-lg-1 offset-md-1 offset-sm-1 offset-1 col-lg-1 col-md-1 col-sm-1 col-1">
                                    @if ($elem['type'] == "building") 
                                        <i class="fas fa-hammer icon"></i>
                                    @elseif ($elem['type'] == "tech")
                                        <i class="fas fa-flask icon"></i>
                                    @elseif ($elem['type'] == "item")
                                        <i class="fas fa-cog icon"></i>
                                    @elseif ($elem['type'] == "unit")
                                        <i class="fas fa-chess-rook icon"></i>
                                    @else
                                        <i class="fas fa-map-marked-alt"></i>
                                    @endif
                                </div>
                                <div id="compteur_{{ $elem['name'] }}_{{ $elem['wait_id'] }}" duration="{{ $elem['duration'] }}" name="{{ $elem['name'] }}" @if($elem['type'] == 'item' || $elem['type'] == 'unit') quantity="x{{ $elem['quantity'] }}" @endif class="col-lg-8 col-md-8 col-sm-8 col-8 infos-building-wip"></div>
                                <div id="interrupt_{{ $elem['name'] }}_{{ $elem['wait_id'] }}" class="col-lg-2 col-md-2 col-sm-2 col-2">
                                    <i title="Interrompre" onclick="interrupt('{{ $elem['wait_id'] }}', '{{ $elem['type'] }}', 'id_{{ $elem['name'] }}_{{ $elem['wait_id'] }}')" class="fas fa-times icon-red"></i>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            <input id="_token" name="_token" type="hidden" value="{{csrf_token()}}">
        </div>
        <script>
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

            function interrupt(wait_id, type, id)
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
        </script>
    </body>
</html>