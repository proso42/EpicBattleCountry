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
                <div onclick="document.location.href='/home'" class="row menu-left">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-home icon"></i></div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-3">Acceuil</div>
                </div>
                <div onclick="document.location.href='/buildings'" class="row menu-left">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-hammer icon"></i></div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-3">Construction</div>
                </div>
                <div class="row menu-left">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-flask icon"></i></div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-3">Technologie</div>
                </div>
                <div class="row menu-left">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-map-marked-alt icon"></i></div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-3">Exploration</div>
                </div>
                <div class="row menu-left">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-fist-raised icon"></i></div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-3">Invasion</div>
                </div>
                <div class="row menu-left">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-flag icon"></i></div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-3">Diplomatie</div>
                </div>
                <div class="row menu-left">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-balance-scale icon"></i></div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-3">Commerce</div>
                </div>
                <div class="row menu-left">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-shield-alt icon"></i></div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-3">Alliance</div>
                </div>
                <div class="row menu-left">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-globe-americas icon"></i></div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-3">Carte</div>
                </div>
                <div class="row menu-left">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-comment icon"></i></div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-3">Messages</div>
                </div>
                <div class="row menu-left">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-chart-line icon"></i></div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-3">Statistiques</div>
                </div>
                <div onclick="document.location.href='/settings'" class="row menu-left">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-user-circle icon"></i></div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-3">Profile</div>
                </div>
                <div class="row menu-left">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-store-alt icon"></i></div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-3">Boutique</div>
                </div>
                <div onclick="document.location.href='/logout'" class="row menu-left last-case">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-sign-out-alt icon"></i></div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-3">Déconnexion</div>
                </div>
            </div>
            <div class="offset-lg-0 offset-md-2 offset-sm-1 offset-1 col-lg-9 col-md-7 col-sm-10 col-10 center-win" style="margin-top: 50px; padding-right: 10px;">
                <div class="row">
                    <div id="eco-tab" class="col-lg-3 col-md-3 col-sm-3 col-3 generique-tab-active" onclick="switchTab('eco')">
                        Economie
                    </div>
                    <div id="army-tab" class="col-lg-3 col-md-3 col-sm-3 col-3 generique-tab" onclick="switchTab('army')">
                        Militaire
                    </div>
                    <div id="def-tab" class="col-lg-3 col-md-3 col-sm-3 col-3 generique-tab" onclick="switchTab('def')">
                        Défensif                    
                    </div>
                    <div id="tech-tab" class="col-lg-3 col-md-3 col-sm-3 col-3 generique-tab" onclick="switchTab('tech')">
                        Technique                    
                    </div>
                </div>
                <div id="eco-buildings" class="row" style="margin-top: 30px;">
                    @foreach ($allowed_eco_buildings as $build)
                        <div class="building-block">
                            <div class="building-name">{{ $build["name"] }} @if ($build["niv"] > 0) {{$build["niv"]}} @endif</div>
                            <img class="building" style="width:250px;height: 250px;" src="{{ $build['illustration'] }}">
                            @if ($build['status'] == "OK")
                                <div class="@if ($build['food_required'] > $food || $build['wood_required'] > $wood || $build['rock_required'] > $rock || $build['steel_required'] > $steel || $build['gold_required'] > $gold) building-button-impossible @else building-button @endif"
                                onclick="update_building('{{ $build['name'] }}', '{{ $build['food_required'] }}', '{{ $build['wood_required'] }}', '{{ $build['rock_required'] }}', '{{ $build['steel_required'] }}', '{{ $build['gold_required'] }}', '{{ $build['duration'] }}')">                                
                                    @if ($build["niv"] == 0)
                                        Construire <i class="fas fa-hammer icon"></i>
                                    @else
                                        Améliorer <i class="fas fa-angle-double-up icon"></i>
                                    @endif
                                    <div class="building-res-needed">
                                        <ul>
                                        @if ($build['food_required'] > 0)
                                                <li>Food : {{ $build['food_required'] }} @if ($build['food_required'] > $food) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                            @endif
                                            @if ($build['wood_required'] > 0)
                                                <li>Wood : {{ $build['wood_required'] }} @if ($build['wood_required'] > $wood) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                            @endif
                                            @if ($build['rock_required'] > 0)
                                                <li>Rock : {{ $build['rock_required'] }} @if ($build['rock_required'] > $rock) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                            @endif
                                            @if ($build['steel_required'] > 0)
                                                <li>Steel : {{ $build['steel_required'] }} @if ($build['steel_required'] > $steel) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                            @endif
                                            @if ($build['gold_required'] > 0)
                                                <li>Gold : {{ $build['gold_required'] }} @if ($build['gold_required'] > $gold) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                            @endif
                                            <li>Time : {{ $build['duration'] }} <i class="fas fa-clock icon"></i></li>
                                        </ul>
                                    </div>                        
                                </div>
                            @else
                                <div id="compteur_{{ $build['name'] }}" duration="{{ $build['duration'] }}" class="building-wip"></div>
                            @endif
                        </div>
                    @endforeach
                </div>
                <div id="army-buildings" class="row" style="margin-top: 30px;display:none">
                @foreach ($allowed_army_buildings as $build)
                    <div class="building-block">
                        <div class="building-name">{{ $build["name"] }} @if ($build["niv"] > 0) {{$build["niv"]}} @endif</div>
                        <img class="building" style="width:250px;height: 250px;" src="{{ $build['illustration'] }}">
                        <div class="@if ($build['food_required'] > $food || $build['wood_required'] > $wood || $build['rock_required'] > $rock || $build['steel_required'] > $steel || $build['gold_required'] > $gold) building-button-impossible @else building-button @endif" 
                        onclick="update_building('{{ $build['name'] }}', '{{ $build['food_required'] }}', '{{ $build['wood_required'] }}', '{{ $build['rock_required'] }}', '{{ $build['steel_required'] }}', '{{ $build['gold_required'] }}', '{{ $build['duration'] }}')">                        
                            @if ($build["niv"] == 0)
                                Construire <i class="fas fa-hammer icon"></i>
                            @else
                                Améliorer <i class="fas fa-angle-double-up icon"></i>
                            @endif
                            <div class="building-res-needed">
                                <ul>
                                @if ($build['food_required'] > 0)
                                        <li>Food : {{ $build['food_required'] }} @if ($build['food_required'] > $food) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                    @endif
                                    @if ($build['wood_required'] > 0)
                                        <li>Wood : {{ $build['wood_required'] }} @if ($build['wood_required'] > $wood) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                    @endif
                                    @if ($build['rock_required'] > 0)
                                        <li>Rock : {{ $build['rock_required'] }} @if ($build['rock_required'] > $rock) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                    @endif
                                    @if ($build['steel_required'] > 0)
                                        <li>Steel : {{ $build['steel_required'] }} @if ($build['steel_required'] > $steel) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                    @endif
                                    @if ($build['gold_required'] > 0)
                                        <li>Gold : {{ $build['gold_required'] }} @if ($build['gold_required'] > $gold) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                    @endif
                                    <li>Time : {{ $build['duration'] }} <i class="fas fa-clock icon"></i></li>
                                </ul>
                            </div>                         
                        </div>
                    </div>
                @endforeach
                </div>
                <div id="def-buildings" class="row" style="margin-top: 30px;display:none">
                @foreach ($allowed_defensive_buildings as $build)
                    <div class="building-block">
                        <div class="building-name">{{ $build["name"] }} @if ($build["niv"] > 0) {{$build["niv"]}} @endif</div>
                        <img class="building" style="width:250px;height: 250px;" src="{{ $build['illustration'] }}">
                        <div class="@if ($build['food_required'] > $food || $build['wood_required'] > $wood || $build['rock_required'] > $rock || $build['steel_required'] > $steel || $build['gold_required'] > $gold) building-button-impossible @else building-button @endif"
                        onclick="update_building('{{ $build['name'] }}', '{{ $build['food_required'] }}', '{{ $build['wood_required'] }}', '{{ $build['rock_required'] }}', '{{ $build['steel_required'] }}', '{{ $build['gold_required'] }}', '{{ $build['duration'] }}')">                        
                            @if ($build["niv"] == 0)
                                Construire <i class="fas fa-hammer icon"></i>
                            @else
                                Améliorer <i class="fas fa-angle-double-up icon"></i>
                            @endif
                            <div class="building-res-needed">
                                <ul>
                                @if ($build['food_required'] > 0)
                                        <li>Food : {{ $build['food_required'] }} @if ($build['food_required'] > $food) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                    @endif
                                    @if ($build['wood_required'] > 0)
                                        <li>Wood : {{ $build['wood_required'] }} @if ($build['wood_required'] > $wood) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                    @endif
                                    @if ($build['rock_required'] > 0)
                                        <li>Rock : {{ $build['rock_required'] }} @if ($build['rock_required'] > $rock) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                    @endif
                                    @if ($build['steel_required'] > 0)
                                        <li>Steel : {{ $build['steel_required'] }} @if ($build['steel_required'] > $steel) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                    @endif
                                    @if ($build['gold_required'] > 0)
                                        <li>Gold : {{ $build['gold_required'] }} @if ($build['gold_required'] > $gold) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                    @endif
                                    <li>Time : {{ $build['duration'] }} <i class="fas fa-clock icon"></i></li>
                                </ul>
                            </div>                          
                        </div>
                    </div>
                @endforeach
                </div>
                <div id="tech-buildings" class="row" style="margin-top: 30px;display:none">
                @foreach ($allowed_tech_buildings as $build)
                    <div class="building-block">
                        <div class="building-name">{{ $build["name"] }} @if ($build["niv"] > 0) {{$build["niv"]}} @endif</div>
                        <img class="building" style="width:250px;height: 250px;" src="{{ $build['illustration'] }}">
                        <div class="@if ($build['food_required'] > $food || $build['wood_required'] > $wood || $build['rock_required'] > $rock || $build['steel_required'] > $steel || $build['gold_required'] > $gold) building-button-impossible @else building-button @endif"
                        onclick="update_building('{{ $build['name'] }}', '{{ $build['food_required'] }}', '{{ $build['wood_required'] }}', '{{ $build['rock_required'] }}', '{{ $build['steel_required'] }}', '{{ $build['gold_required'] }}', '{{ $build['duration'] }}')">
                            @if ($build["niv"] == 0)
                                Construire <i class="fas fa-hammer icon"></i>
                            @else
                                Améliorer <i class="fas fa-angle-double-up icon"></i>
                            @endif
                            <div class="building-res-needed">
                                <ul>
                                    @if ($build['food_required'] > 0)
                                        <li>Food : {{ $build['food_required'] }} @if ($build['food_required'] > $food) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                    @endif
                                    @if ($build['wood_required'] > 0)
                                        <li>Wood : {{ $build['wood_required'] }} @if ($build['wood_required'] > $wood) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                    @endif
                                    @if ($build['rock_required'] > 0)
                                        <li>Rock : {{ $build['rock_required'] }} @if ($build['rock_required'] > $rock) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                    @endif
                                    @if ($build['steel_required'] > 0)
                                        <li>Steel : {{ $build['steel_required'] }} @if ($build['steel_required'] > $steel) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                    @endif
                                    @if ($build['gold_required'] > 0)
                                        <li>Gold : {{ $build['gold_required'] }} @if ($build['gold_required'] > $gold) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                    @endif
                                    <li>Time : {{ $build['duration'] }} <i class="fas fa-clock icon"></i></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
                <input id="_token" name="_token" type="hidden" value="{{csrf_token()}}">
                </div>
            </div>
        </div>
        <script>
            launch_all_timers();
            var activeTab = "eco-tab";
            var activeBuildings = "eco-buildings";
            function switchTab(activeId)
            {
                if (activeId + "-tab" === activeTab)
                {
                    console.log('inactif');
                    return ;
                }
                else
                {
                    document.getElementById(activeTab).className = "col-lg-3 col-md-3 col-sm-3 col-3 generique-tab";
                    document.getElementById(activeId + "-tab").className = "col-lg-3 col-md-3 col-sm-3 col-3 generique-tab-active";
                    document.getElementById(activeBuildings).style.display = 'none';
                    document.getElementById(activeId + "-buildings").style.display = '';
                    activeTab = activeId + "-tab";
                    activeBuildings = activeId + "-buildings";
                }
            }

            function launch_all_timers()
            {
                var timers = Array.prototype.slice.call(document.getElementsByClassName('building-wip'));
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

            function update_building(name, food, wood, rock, steel, gold, duration)
            {
                var _token = document.getElementById('_token').value;
                var building_type = activeBuildings.replace(/-/gi, '_');
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'http://www.epicbattlecorp.fr/update_building');
                xhr.onreadystatechange =  function()
                {
                    if (xhr.readyState === 4 && xhr.status === 200)
                    {
                        window.location.reload();
                    }
                }
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.send('_token=' + _token + '&building_name=' + name + '&building_type=' + building_type + '&food_required=' + food + '&wood_required=' + wood + '&rock_required=' + rock + '&steel_required=' + steel + '&gold_required=' + gold + '&duration=' + duration);
            }
        </script>
    </body>
</html>