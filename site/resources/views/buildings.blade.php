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
                <div class="row">
                    <div id="eco-tab" class="col-lg-3 col-md-3 col-sm-3 col-3 generique-tab" onclick="switchTab('eco')">
                        @lang('building.eco')
                    </div>
                    <div id="army-tab" class="col-lg-3 col-md-3 col-sm-3 col-3 generique-tab" onclick="switchTab('army')">
                        @lang('building.military')
                    </div>
                    <div id="religious-tab" class="col-lg-3 col-md-3 col-sm-3 col-3 generique-tab" onclick="switchTab('religious')">
                        @lang('building.god')                    
                    </div>
                    <div id="tech-tab" class="col-lg-3 col-md-3 col-sm-3 col-3 generique-tab" onclick="switchTab('tech')">
                        @lang('building.tech')                    
                    </div>
                </div>
                <div id="eco-buildings" class="row" style="margin-top: 30px;display:none">
                    @foreach ($allowed_eco_buildings as $build)
                        <div class="building-block">
                            <div class="building-name">{{ $build["name"] }} @if ($build["niv"] > 0) {{$build["niv"]}} @endif</div> <!-- NEED TRAD -->
                            <img class="building" style="width:250px;height: 250px;" src="{{ $build['illustration'] }}">
                            @if ($build['status'] == "OK")
                                <div id="eco_{{ $build['id'] }}" name="{{ $build['name'] }}" @if ($build['food_required'] > $food || $build['wood_required'] > $wood || $build['rock_required'] > $rock || $build['steel_required'] > $steel || $build['gold_required'] > $gold) class="building-button-impossible" @else class="building-button"
                                onclick="update_building('{{ $build['id'] }}', 'eco')"@endif>                                
                                    @if ($build["niv"] == 0)
                                        @lang('building.build') <i class="fas fa-hammer icon"></i>
                                    @else
                                        @lang('building.upgrade') <i class="fas fa-angle-double-up icon"></i>
                                    @endif
                                    <div id="res_eco_{{ $build['id'] }}" class="building-res-needed">
                                        <ul>
                                        @if ($build['food_required'] > 0)
                                                <li>@lang('common.food') : {{ $build['food_required'] }} @if ($build['food_required'] > $food) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                            @endif
                                            @if ($build['wood_required'] > 0)
                                                <li>@lang('common.wood') : {{ $build['wood_required'] }} @if ($build['wood_required'] > $wood) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                            @endif
                                            @if ($build['rock_required'] > 0)
                                                <li>@lang('common.rock') : {{ $build['rock_required'] }} @if ($build['rock_required'] > $rock) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                            @endif
                                            @if ($build['steel_required'] > 0)
                                                <li>@lang('common.steel') : {{ $build['steel_required'] }} @if ($build['steel_required'] > $steel) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                            @endif
                                            @if ($build['gold_required'] > 0)
                                                <li>@lang('common.gold') : {{ $build['gold_required'] }} @if ($build['gold_required'] > $gold) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                            @endif
                                            <li>@lang('common.time') : {{ $build['duration'] }} <i class="fas fa-clock icon"></i></li>
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
                        <div class="building-name">{{ $build["name"] }} @if ($build["niv"] > 0) {{$build["niv"]}} @endif</div> <!-- NEED TRAD -->
                        <img class="building" style="width:250px;height: 250px;" src="{{ $build['illustration'] }}">
                        @if ($build['status'] == "OK")
                            <div id="army_{{ $build['id'] }}" name="{{ $build['name'] }}" @if ($build['food_required'] > $food || $build['wood_required'] > $wood || $build['rock_required'] > $rock || $build['steel_required'] > $steel || $build['gold_required'] > $gold) class="building-button-impossible" @else class="building-button"
                            onclick="update_building('{{ $build['id'] }}', 'army')"@endif>                                
                                @if ($build["niv"] == 0)
                                    @lang('building.build') <i class="fas fa-hammer icon"></i>
                                @else
                                    @lang('building.upgrade') <i class="fas fa-angle-double-up icon"></i>
                                @endif
                                <div id="res_army_{{ $build['id'] }}" class="building-res-needed">
                                    <ul>
                                    @if ($build['food_required'] > 0)
                                            <li>@lang('common.food') : {{ $build['food_required'] }} @if ($build['food_required'] > $food) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                        @endif
                                        @if ($build['wood_required'] > 0)
                                            <li>@lang('common.wood') : {{ $build['wood_required'] }} @if ($build['wood_required'] > $wood) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                        @endif
                                        @if ($build['rock_required'] > 0)
                                            <li>@lang('common.rock') : {{ $build['rock_required'] }} @if ($build['rock_required'] > $rock) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                        @endif
                                        @if ($build['steel_required'] > 0)
                                            <li>@lang('common.steel') : {{ $build['steel_required'] }} @if ($build['steel_required'] > $steel) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                        @endif
                                        @if ($build['gold_required'] > 0)
                                            <li>@lang('common.gold') : {{ $build['gold_required'] }} @if ($build['gold_required'] > $gold) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                        @endif
                                        <li>@lang('common.time') : {{ $build['duration'] }} <i class="fas fa-clock icon"></i></li>
                                    </ul>
                                </div>                        
                            </div>
                        @else
                            <div id="compteur_{{ $build['name'] }}" duration="{{ $build['duration'] }}" class="building-wip"></div>
                        @endif
                    </div>
                @endforeach
                </div>
                <div id="religious-buildings" class="row" style="margin-top: 30px;display:none">
                @foreach ($allowed_religious_buildings as $build)
                    <div class="building-block">
                        <div class="building-name">{{ $build["name"] }} @if ($build["niv"] > 0) {{$build["niv"]}} @endif</div> <!-- NEED TRAD -->
                        <img class="building" style="width:250px;height: 250px;" src="{{ $build['illustration'] }}">
                        @if ($build['status'] == "OK")
                            <div id="religious_{{ $build['id'] }}" name="{{ $build['name'] }}" @if ($build['food_required'] > $food || $build['wood_required'] > $wood || $build['rock_required'] > $rock || $build['steel_required'] > $steel || $build['gold_required'] > $gold) class="building-button-impossible" @else class="building-button"
                            onclick="update_building('{{ $build['id'] }}', 'religious')"@endif>                                
                                @if ($build["niv"] == 0)
                                    @lang('building.build') <i class="fas fa-hammer icon"></i>
                                @else
                                    @lang('building.upgrade') <i class="fas fa-angle-double-up icon"></i>
                                @endif
                                <div id="res_religious_{{ $build['id'] }}" class="building-res-needed">
                                    <ul>
                                    @if ($build['food_required'] > 0)
                                            <li>@lang('common.food') : {{ $build['food_required'] }} @if ($build['food_required'] > $food) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                        @endif
                                        @if ($build['wood_required'] > 0)
                                            <li>@lang('common.wood') : {{ $build['wood_required'] }} @if ($build['wood_required'] > $wood) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                        @endif
                                        @if ($build['rock_required'] > 0)
                                            <li>@lang('common.rock') : {{ $build['rock_required'] }} @if ($build['rock_required'] > $rock) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                        @endif
                                        @if ($build['steel_required'] > 0)
                                            <li>@lang('common.steel') : {{ $build['steel_required'] }} @if ($build['steel_required'] > $steel) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                        @endif
                                        @if ($build['gold_required'] > 0)
                                            <li>@lang('common.gold') : {{ $build['gold_required'] }} @if ($build['gold_required'] > $gold) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                        @endif
                                        <li>@lang('common.time') : {{ $build['duration'] }} <i class="fas fa-clock icon"></i></li>
                                    </ul>
                                </div>                        
                            </div>
                        @else
                            <div id="compteur_{{ $build['name'] }}" duration="{{ $build['duration'] }}" class="building-wip"></div>
                        @endif
                    </div>
                @endforeach
                </div>
                <div id="tech-buildings" class="row" style="margin-top: 30px;display:none">
                @foreach ($allowed_tech_buildings as $build)
                    <div class="building-block">
                        <div class="building-name">{{ $build["name"] }} @if ($build["niv"] > 0) {{$build["niv"]}} @endif</div> <!-- NEED TRAD -->
                        <img class="building" style="width:250px;height: 250px;" src="{{ $build['illustration'] }}">
                        @if ($build['status'] == "OK")
                            <div id="tech_{{ $build['id'] }}" name="{{ $build['name'] }}" @if ($build['food_required'] > $food || $build['wood_required'] > $wood || $build['rock_required'] > $rock || $build['steel_required'] > $steel || $build['gold_required'] > $gold) class="building-button-impossible" @else class="building-button"
                            onclick="update_building('{{ $build['id'] }}', 'tech')"@endif>                                
                                @if ($build["niv"] == 0)
                                    @lang('building.build') <i class="fas fa-hammer icon"></i>
                                @else
                                    @lang('building.upgrade') <i class="fas fa-angle-double-up icon"></i>
                                @endif
                                <div id="res_tech_{{ $build['id'] }}" class="building-res-needed">
                                    <ul>
                                    @if ($build['food_required'] > 0)
                                            <li>@lang('common.food') : {{ $build['food_required'] }} @if ($build['food_required'] > $food) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                        @endif
                                        @if ($build['wood_required'] > 0)
                                            <li>@lang('common.wood') : {{ $build['wood_required'] }} @if ($build['wood_required'] > $wood) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                        @endif
                                        @if ($build['rock_required'] > 0)
                                            <li>@lang('common.rock') : {{ $build['rock_required'] }} @if ($build['rock_required'] > $rock) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                        @endif
                                        @if ($build['steel_required'] > 0)
                                            <li>@lang('common.steel') : {{ $build['steel_required'] }} @if ($build['steel_required'] > $steel) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                        @endif
                                        @if ($build['gold_required'] > 0)
                                            <li>@lang('common.gold') : {{ $build['gold_required'] }} @if ($build['gold_required'] > $gold) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                        @endif
                                        <li>@lang('common.time') : {{ $build['duration'] }} <i class="fas fa-clock icon"></i></li>
                                    </ul>
                                </div>                        
                            </div>
                        @else
                            <div id="compteur_{{ $build['name'] }}" duration="{{ $build['duration'] }}" class="building-wip"></div>
                        @endif
                    </div>
                @endforeach
                <input id="_token" name="_token" type="hidden" value="{{csrf_token()}}">
                <div id="fat" style="display: none" first_active_tab="{{ $first_active_tab }}" ></div>
                </div>
            </div>
        </div>
        <script>
            launch_all_timers();
            var activeTab = document.getElementById("fat").getAttribute("first_active_tab") + "-tab";
            var activeBuildings = document.getElementById("fat").getAttribute("first_active_tab") + "-buildings";
            document.getElementById(activeTab).className = "col-lg-3 col-md-3 col-sm-3 col-3 generique-tab-active";
            document.getElementById(activeBuildings).style.display = '';

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
                    document.getElementById(activeBuildings).style.display = "none";
                    document.getElementById(activeId + "-buildings").style.display = "";
                    activeTab = activeId + "-tab";
                    activeBuildings = activeId + "-buildings";
                    var _token = document.getElementById('_token').value;
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'http://www.epicbattlecorp.fr/set_active_tab');
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
                        j = "";
                    }
                    else
                    {
                        j += " j ";
                    }
                    compteur.textContent= "@lang('common.in_progress') : " + j + " " + h+" "+m+" "+s;
                    setTimeout(function(same_id=id, new_duration=duration-1){
                        timer(same_id, new_duration);
                    },1000);
                }
            }

            function update_building(id, type)
            {
                var _token = document.getElementById('_token').value;
                var building_type = activeBuildings.replace(/-/gi, '_');
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'http://www.epicbattlecorp.fr/update_building');
                xhr.onreadystatechange =  function()
                {
                    if (xhr.readyState === 4 && xhr.status === 200)
                    {
                        //console.log(xhr.responseText);
                        if (xhr.responseText.indexOf("error") < 0)
                        {
                            let infos = JSON.parse(xhr.responseText);
                            console.log(infos);                          
                            div_id = type + "_" + id; 
                            let duration = infos.time_remaining;
                            document.getElementById("food").textContent = infos.food;
                            document.getElementById("wood").textContent = infos.wood;
                            document.getElementById("rock").textContent = infos.rock;
                            document.getElementById("steel").textContent = infos.steel;
                            document.getElementById("gold").textContent = infos.gold;
                            let elem = document.getElementById(div_id);
                            elem.className = "building-wip";
                            elem.onclick = function (){};
                            document.getElementById("res_" + type + "_" + id).remove();
                            timer(div_id, duration);
                        }
                        else
                            return ;
                    }
                }
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.send('_token=' + _token + '&id=' + id + '&type=' + building_type);
            }
        </script>
    </body>
</html>