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
        <div id="tech_desc_win" class="tech-desc" style="display: none">
            <h3 id="tech_desc_name"></h3>
            <p id="tech_desc_text"></p>
            <input onclick="close_help()" id="ok_button" type="button" class="return-button" value="@lang('common.ok')">
        </div>
            @include('default')
            <div class="offset-lg-0 offset-md-2 offset-sm-1 offset-1 col-lg-9 col-md-7 col-sm-10 col-10 center-win" style="margin-top: 50px; padding-right: 10px;">
                @if ($allowed == 1)
                    <div id="techs" class="row" style="margin-top: 30px">
                        @foreach ($allowed_techs as $tech)
                            <div class="tech-block">
                                <div class="tech-name" onclick="open_help({{ $tech["tech_id"] }}, '{{ $tech["name"] }}')"><a href="#top">{{ $tech["name"] }} @if ($tech["niv"] > 0) {{$tech["niv"]}} @endif</a></div>
                                <img class="tech" style="width:250px;height: 250px;" src="{{ $tech['illustration'] }}">
                                @if ($tech['status'] == "OK" && $waiting_tech == 0)
                                    <div id="tech_{{ $tech['tech_id'] }}" @if ($tech['food_required'] > $util->food || $tech['wood_required'] > $util->wood || $tech['rock_required'] > $util->rock || $tech['steel_required'] > $util->steel || $tech['gold_required'] > $util->gold) class="tech-button-impossible" @else class="tech-button"
                                    onclick="update_tech('{{ $tech['tech_id'] }}')"@endif>
                                        @lang('tech.search') <i class="fas fa-flask icon"></i>
                                        <div id="res_tech_{{ $tech['tech_id'] }}" class="tech-res-needed">
                                            <ul>
                                            @if ($tech['food_required'] > 0)
                                                    <li>@lang('common.food') : {{ $tech['food_required'] }} @if ($tech['food_required'] > $util->food) <i id="icon_food_tech_{{ $tech['tech_id'] }}" class="fas fa-times icon"></i> @else <i id="icon_food_tech_{{ $tech['tech_id'] }}" class="fas fa-check icon"></i> @endif</li>
                                                @endif
                                                @if ($tech['wood_required'] > 0)
                                                    <li>@lang('common.wood') : {{ $tech['wood_required'] }} @if ($tech['wood_required'] > $util->wood) <i id="icon_wood_tech_{{ $tech['tech_id'] }}" class="fas fa-times icon"></i> @else <i id="icon_wood_tech_{{ $tech['tech_id'] }}" class="fas fa-check icon"></i> @endif</li>
                                                @endif
                                                @if ($tech['rock_required'] > 0)
                                                    <li>@lang('common.rock') : {{ $tech['rock_required'] }} @if ($tech['rock_required'] > $util->rock) <i id="icon_rock_tech_{{ $tech['tech_id'] }}" class="fas fa-times icon"></i> @else <i id="icon_rock_tech_{{ $tech['tech_id'] }}" class="fas fa-check icon"></i> @endif</li>
                                                @endif
                                                @if ($tech['steel_required'] > 0)
                                                    <li>@lang('common.steel') : {{ $tech['steel_required'] }} @if ($tech['steel_required'] > $util->steel) <i id="icon_steel_tech_{{ $tech['tech_id'] }}" class="fas fa-times icon"></i> @else <i id="icon_steel_tech_{{ $tech['tech_id'] }}" class="fas fa-check icon"></i> @endif</li>
                                                @endif
                                                @if ($tech['gold_required'] > 0)
                                                    <li>@lang('common.gold') : {{ $tech['gold_required'] }} @if ($tech['gold_required'] > $util->gold) <i id="icon_gold_tech_{{ $tech['tech_id'] }}" class="fas fa-times icon"></i> @else <i id="icon_gold_tech_{{ $tech['tech_id'] }}" class="fas fa-check icon"></i> @endif</li>
                                                @endif
                                                <li>@lang('common.time') : {{ $tech['duration'] }} <i class="fas fa-clock icon"></i></li>
                                            </ul>
                                        </div>                        
                                    </div>
                                @elseif ($tech['status'] == "OK" && $waiting_tech == 1)
                                    <div id="unavailable_{{ $tech['name'] }}" class="unavailable">@lang('common.unavailable') <i class="fas fa-hourglass-half"></i></div>
                                @else
                                    <div id="compteur_{{ $tech['name'] }}" duration="{{ $tech['duration'] }}" class="tech-wip"></div>
                                @endif
                            </div>
                        @endforeach
                        <input id="_token" name="_token" type="hidden" value="{{csrf_token()}}">
                    </div>
                @else
                    <div id="need_lab" style="margin-top: 30px">
                        <h2>@lang('tech.need_laboratory')</h2>
                    <div>
                @endif
            </div>
        </div>
        <script>
            launch_all_timers();
            setTimeout(() =>{
                let body_height = document.body.scrollHeight + 20;
                let win_height = window.innerHeight;
                if (body_height > win_height)
                    document.getElementById("overlay").style.height = body_height + "px";
                else
                    document.getElementById("overlay").style.height = win_height + "px";
            }, 1000);
            function launch_all_timers()
            {
                var timers = Array.prototype.slice.call(document.getElementsByClassName('tech-wip'));
                if (timers.length == 0)
                    return ;
                timers.forEach(function(e){
                    timer(e.id, e.getAttribute("duration"));
                });
            }

            function timer(id, duration)
            {
                var compteur=document.getElementById(id);
                var s=duration;
                var m=0;
                var h=0;
                var j = 0;
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
                        j = ""
                    }
                    else
                    {
                        j += " j "
                    }
                    compteur.textContent= "@lang('common.in_progress') : " + j + " " + h+" "+m+" "+s;
                    setTimeout(function(same_id=id, new_duration=duration-1){
                        timer(same_id, new_duration);
                    },1000);
                }
            }

            function open_help(id, name)
            {
                var _token = document.getElementById('_token').value;
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'http://www.epicbattlecorp.fr/techs_description');
                xhr.onreadystatechange =  function()
                {
                    if (xhr.readyState === 4 && xhr.status === 200)
                    {
                        var infos = JSON.parse(xhr.responseText);
                        if (infos.Result == "Error")
                            console.log(infos.Reason);
                        else
                        {
                            document.getElementById('overlay').style.display = "";
                            document.getElementById('tech_desc_win').style.display = "";
                            document.getElementById('tech_desc_name').textContent = name;
                            document.getElementById('tech_desc_text').textContent = infos.description;
                        }
                    }
                }
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.send('_token=' + _token + '&id=' + id);
            }

            function close_help()
            {
                document.getElementById('overlay').style.display = "none";
                document.getElementById('tech_desc_win').style.display = "none";
                document.getElementById('tech_desc_name').textContent = "";
                document.getElementById('tech_desc_text').textContent = "";
            }

            function update_tech(id)
            {
                var _token = document.getElementById('_token').value;
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'http://www.epicbattlecorp.fr/update_tech');
                xhr.onreadystatechange =  function()
                {
                    if (xhr.readyState === 4 && xhr.status === 200)
                    {
                        if (xhr.responseText.indexOf("error") < 0)
                        {
                            var infos = JSON.parse(xhr.responseText);
                            console.log(infos);                          
                            let duration = infos.time_remaining;
                            document.getElementById("food").textContent = infos.food;
                            document.getElementById("wood").textContent = infos.wood;
                            document.getElementById("rock").textContent = infos.rock;
                            document.getElementById("steel").textContent = infos.steel;
                            document.getElementById("gold").textContent = infos.gold;
                            let elem = document.getElementById("tech_" + id);
                            elem.className = "tech-wip";
                            elem.onclick = function (){};
                            document.getElementById("res_tech_" + id).remove();
                            let techs_buttons = document.getElementsByClassName("tech-button");
                            let stop = techs_buttons.length;
                            for(i = 0; i < stop; i++)
                            {
                                techs_buttons[0].onclick = function(){};
                                techs_buttons[0].firstElementChild.textContent = infos.trad;
                                techs_buttons[0].children[1].className = "fas fa-hourglass-half";
                                techs_buttons[0].className = "unavailable";
                            }
                            techs_buttons = document.getElementsByClassName("tech-button-impossible");
                            stop = techs_buttons.length;
                            for(i = 0; i < stop; i++)
                            {
                                techs_buttons[0].onclick = function(){};
                                techs_buttons[0].firstElementChild.textContent = infos.trad;
                                techs_buttons[0].children[1].className = "fas fa-hourglass-half";
                                techs_buttons[0].className = "unavailable";
                            }
                            /*infos.forbidden_techs.forEach(function(e){
                                let forbidden = document.getElementById(e.tech_id);
                                if (forbidden.className == "tech-button-impossible")
                                    return ;
                                else
                                {
                                    forbidden.className = "tech-button-impossible";
                                    forbidden.onclick = function (){};
                                    if (e.food_required > 0 && e.food_required > infos.food)
                                        document.getElementById("icon_food_" + e.tech_id).className = "fas fa-times icon";
                                    if (e.wood_required > 0 && e.wood_required > infos.wood)
                                        document.getElementById("icon_wood_" + e.tech_id).className = "fas fa-times icon";
                                    if (e.rock_required > 0 && e.rock_required > infos.rock)
                                        document.getElementById("icon_rock_" + e.tech_id).className = "fas fa-times icon";
                                    if (e.steel_required > 0 && e.steel_required > infos.steel)
                                        document.getElementById("icon_steel_" + e.tech_id).className = "fas fa-times icon";
                                    if (e.gold_required > 0 && e.gold_required > infos.gold)
                                        document.getElementById("icon_gold_" + e.tech_id).className = "fas fa-times icon";
                                }
                            });*/
                            timer("tech_" + id, duration);
                        }
                        else
                        {
                            console.log(xhr.responseText);
                            return ;
                        }
                    }
                }
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.send('_token=' + _token + '&tech_id=' + id);
            }
        </script>
    </body>
</html>