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
        <div class="menu-top">
            <div class="row">
                <div class="col-lg-1 col-md-1 col-sm-1 col-0"></div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-3">
                    <img style="margin-top: 15px;" src="images/swords.png">
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-6">
                    <h1 style="margin-top: 25px;">EpicBattle</h1>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-3">
                    <img style="margin-top: 15px;" src="images/swords.png">
                </div>
            </div>
            <div style="margin-top: 25px;">
                <div class="row">
                    <div class="offset-lg-1 offset-md-1 offset-sm-1 offset-1 col-lg-2 col-md-2 col-sm-2 col-2">
                        <div class="row">
                            <div  class="col-lg-12" title="Food : {{ $food }}">
                                <img src="images/food.png">
                                <span id="food" style="margin-left: 5px;margin-top: 7px;margin-right: 10px; @if ($food == $max_food) color:maroon @elseif ($food >= ($max_food / 10 * 9)) color:darkorange @endif">
                                    {{ $food }}
                                </span>
                                <span id="compact_food" style="margin-left: 5px;margin-top: 7px;margin-right: 10px;@if ($food == $max_food) color:maroon @elseif ($food >= ($max_food / 10 * 9)) color:darkorange @endif; display: none">
                                    {{ $compact_food }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-2">
                        <div class="row">
                            <div  class="col-lg-12" title="Wood : {{ $wood }}">
                                <img src="images/wood.png">
                                <span style="margin-left: 5px;margin-top: 7px;margin-right: 10px;@if ($wood == $max_wood) color:maroon @elseif ($wood >= ($max_wood / 10 * 9)) color:darkorange @endif">
                                    {{ $wood }}
                                </span>
                                <span id="compact_wood" style="margin-left: 5px;margin-top: 7px;margin-right: 10px;@if ($wood == $max_wood) color:maroon @elseif ($wood >= ($max_wood / 10 * 9)) color:darkorange @endif; display: none">
                                    {{ $compact_wood }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-2">
                        <div class="row">
                            <div  class="col-lg-12" title="Rock : {{ $rock }}">
                                <img src="images/rock.png">
                                <span style="margin-left: 5px;margin-top: 7px;margin-right: 10px;@if ($rock == $max_rock) color:maroon @elseif ($rock >= ($max_rock / 10 * 9)) color:darkorange @endif">
                                    {{ $rock }}
                                </span>
                                <span id="compact_rock" style="margin-left: 5px;margin-top: 7px;margin-right: 10px;@if ($rock == $max_rock) color:maroon @elseif ($rock >= ($max_rock / 10 * 9)) color:darkorange @endif; display: none">
                                    {{ $compact_rock }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-2">
                        <div class="row">
                            <div  class="col-lg-12" title="Steel : {{ $steel }}">
                                <img src="images/steel.png">
                                <span style="margin-left: 5px;margin-top: 7px;margin-right: 10px;@if ($steel == $max_steel) color:maroon @elseif ($steel >= ($max_steel / 10 * 9)) color:darkorange @endif">
                                    {{ $steel }}
                                </span>
                                <span id="compact_steel" style="margin-left: 5px;margin-top: 7px;margin-right: 10px;@if ($steel == $max_steel) color:maroon @elseif ($steel >= ($max_steel / 10 * 9)) color:darkorange @endif; display: none">
                                    {{ $compact_steel }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-2">
                        <div class="row">
                            <div  class="col-lg-12" title="Gold : {{ $gold }}">
                                <img src="images/gold.png">
                                <span style="margin-left: 5px;margin-top: 7px;margin-right: 10px;@if ($gold == $max_gold) color:maroon @elseif ($gold >= ($max_gold / 10 * 9)) color:darkorange @endif">
                                    {{ $gold }}
                                </span>
                                <span id="compact_gold" style="margin-left: 5px;margin-top: 7px;margin-right: 10px;@if ($gold == $max_gold) color:maroon @elseif ($gold >= ($max_gold / 10 * 9)) color:darkorange @endif; display: none">
                                    {{ $compact_gold }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="margin-left:0; margin-right: 0;">
            <div class="col-lg-2 col-md-2" style="margin-top: 50px;">
                <div class="row menu-left">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-home icon"></i></div>
                    <div onclick="document.location.href='/home'" class="col-lg-3 col-md-3 col-sm-1 col-3">Acceuil</div>
                </div>
                <div onclick="document.location.href='/buildings'" class="row menu-left">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-hammer icon"></i></div>
                    <div class="col-lg-3 col-md-3 col-sm-10 col-10">Construction</div>
                </div>
                <div class="row menu-left">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fab fa-whmcs icon"></i></div>
                    <div class="col-lg-3 col-md-3 col-sm-10 col-10">Production</div>
                </div>
                <div class="row menu-left">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-flask icon"></i></div>
                    <div class="col-lg-3 col-md-3 col-sm-10 col-10">Technologie</div>
                </div>
                <div class="row menu-left">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-map-marked-alt icon"></i></div>
                    <div class="col-lg-3 col-md-3 col-sm-10 col-10">Exploration</div>
                </div>
                <div class="row menu-left">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-fist-raised icon"></i></div>
                    <div class="col-lg-3 col-md-3 col-sm-10 col-10">Invasion</div>
                </div>
                <div class="row menu-left">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-flag icon"></i></div>
                    <div class="col-lg-3 col-md-3 col-sm-10 col-10">Diplomatie</div>
                </div>
                <div class="row menu-left">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-balance-scale icon"></i></div>
                    <div class="col-lg-3 col-md-3 col-sm-10 col-10">Commerce</div>
                </div>
                <div class="row menu-left">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-shield-alt icon"></i></div>
                    <div class="col-lg-3 col-md-3 col-sm-10 col-10">Alliance</div>
                </div>
                <div class="row menu-left">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-globe-americas icon"></i></div>
                    <div class="col-lg-3 col-md-3 col-sm-10 col-10">Carte</div>
                </div>
                <div class="row menu-left">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-comment icon"></i></div>
                    <div class="col-lg-3 col-md-3 col-sm-10 col-10">Messages</div>
                </div>
                <div class="row menu-left">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-chart-line icon"></i></div>
                    <div class="col-lg-3 col-md-3 col-sm-10 col-10">Statistiques</div>
                </div>
                <div onclick="document.location.href='/settings'" class="row menu-left">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-user-circle icon"></i></div>
                    <div class="col-lg-3 col-md-3 col-sm-10 col-10">Profile</div>
                </div>
                <div class="row menu-left">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-store-alt icon"></i></div>
                    <div class="col-lg-3 col-md-3 col-sm-10 col-10">Boutique</div>
                </div>
                <div onclick="document.location.href='/logout'" class="row menu-left last-case">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-sign-out-alt icon"></i></div>
                    <div class="col-lg-3 col-md-3 col-sm-10 col-10">Déconnexion</div>
                </div>
            </div>
            <div class="offset-lg-0 offset-md-2 offset-sm-1 offset-1 col-lg-9 col-md-7 col-sm-10 col-10 center-win" style="margin-top: 50px; padding-right: 10px;">
                <div style="text-align:center">
                    <h2>{{ $city_name }}</h2>
                </div>
                <hr class="signin-footer">
                <div class="production-tab">
                    <table style="margin-left: auto; margin-right: auto">
                        <tr>
                            <td>Ressources</td><td>Prod par heure</td><td> </td><td>Stockage Max</td>
                        </tr>
                        <tr>
                            <td>Nourriture</td><td>{{ $food_prod * 60 }}</td><td> </td><td>{{ $max_food }}</td>
                        </tr>
                        <tr>
                            <td>Bois</td><td>{{ $wood_prod * 60 }}</td><td></td> <td>{{ $max_wood }}</td>
                        </tr>
                        <tr>
                            <td>Pierre</td><td>{{ $rock_prod * 60 }}</td><td></td> <td>{{ $max_rock }}</td>
                        </tr>
                        <tr>
                            <td>Fer</td><td>{{ $steel_prod * 60 }}</td><td></td> <td>{{ $max_steel }}</td>
                        </tr>
                        <tr>
                            <td>Or</td><td>{{ $gold_prod * 60 }}</td><td></td> <td>{{ $max_gold }}</td>
                        </tr>
                    </table>
                </div>
                <hr class="signin-footer">
                <div>
                    <ul>
                    @foreach ($waiting_list as $build)
                        <li id="compteur_{{ $build['name'] }}" duration="{{ $build['duration'] }}" name="{{ $build['name'] }}" class="infos-building-wip"></li>
                    @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <script>
            launch_all_timers();
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
                if(s<=0)
                    compteur.textContent = name + " Terminé";
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
                    compteur.textContent= name + " " + h+" "+m+" "+s;
                    setTimeout(function(same_id=id, new_duration=duration-1, same_name=name){
                        timer(same_id, new_duration,same_name);
                    },1000);
                }
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