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
                @if ($allowed == 1)
                    <div id="techs" class="row" style="margin-top: 30px">
                        @foreach ($allowed_techs as $tech)
                            <div class="tech-block">
                                <div class="tech-name">{{ $tech["name"] }} @if ($tech["niv"] > 0) {{$tech["niv"]}} @endif</div>
                                <img class="tech" style="width:250px;height: 250px;" src="{{ $tech['illustration'] }}">
                                @if ($tech['status'] == "OK")
                                    <div @if ($tech['food_required'] > $food || $tech['wood_required'] > $wood || $tech['rock_required'] > $rock || $tech['steel_required'] > $steel || $tech['gold_required'] > $gold) class="tech-button-impossible" @else class="tech-button"
                                    onclick="update_tech('{{ $tech['name'] }}', '{{ $tech['food_required'] }}', '{{ $tech['wood_required'] }}', '{{ $tech['rock_required'] }}', '{{ $tech['steel_required'] }}', '{{ $tech['gold_required'] }}', '{{ $tech['duration'] }}', '{{ $tech['niv'] }}')"@endif>
                                        Rechercher <i class="fas fa-flask icon"></i>
                                        <div class="tech-res-needed">
                                            <ul>
                                            @if ($tech['food_required'] > 0)
                                                    <li>Food : {{ $tech['food_required'] }} @if ($tech['food_required'] > $food) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                                @endif
                                                @if ($tech['wood_required'] > 0)
                                                    <li>Wood : {{ $tech['wood_required'] }} @if ($tech['wood_required'] > $wood) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                                @endif
                                                @if ($tech['rock_required'] > 0)
                                                    <li>Rock : {{ $tech['rock_required'] }} @if ($tech['rock_required'] > $rock) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                                @endif
                                                @if ($tech['steel_required'] > 0)
                                                    <li>Steel : {{ $tech['steel_required'] }} @if ($tech['steel_required'] > $steel) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                                @endif
                                                @if ($tech['gold_required'] > 0)
                                                    <li>Gold : {{ $tech['gold_required'] }} @if ($tech['gold_required'] > $gold) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                                @endif
                                                <li>Time : {{ $tech['duration'] }} <i class="fas fa-clock icon"></i></li>
                                            </ul>
                                        </div>                        
                                    </div>
                                @else
                                    <div id="compteur_{{ $tech['name'] }}" duration="{{ $tech['duration'] }}" class="tech-wip"></div>
                                @endif
                            </div>
                        @endforeach
                        <input id="_token" name="_token" type="hidden" value="{{csrf_token()}}">
                    </div>
                @else
                    <div id="need_lab" style="margin-top: 30px">
                        <h2>Vous devez construire un laboratoire avant de pouvoir faire de la recherche.</h2>
                    <div>
                @endif
            </div>
        </div>
        <script>
            launch_all_timers();

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

            function update_tech(name, food, wood, rock, steel, gold, duration, niv)
            {
                var _token = document.getElementById('_token').value;
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'http://www.epicbattlecorp.fr/update_tech');
                xhr.onreadystatechange =  function()
                {
                    if (xhr.readyState === 4 && xhr.status === 200)
                    {
                        window.location.href="http://www.epicbattlecorp.fr/tech" + (activeTab.split('-'))[0];
                    }
                }
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.send('_token=' + _token + '&tech_name=' + name + '&food_required=' + food + '&wood_required=' + wood + '&rock_required=' + rock + '&steel_required=' + steel + '&gold_required=' + gold + '&duration=' + duration + '&niv=' + niv);
                window.location.href="http://www.epicbattlecorp.fr/tech" + (activeTab.split('-'))[0];
            }
        </script>
    </body>
</html>