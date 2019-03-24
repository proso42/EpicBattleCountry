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
                <div style="text-align:center">
                    <h2>{{ $city_name }}</h2>
                </div>
                <hr class="signin-footer">
                <div class="prod-div">
                    <table class="prod-table">
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
                @if (count($waiting_list) > 0)
                    <hr class="signin-footer">
                    <div class="waiting-list">
                        @foreach ($waiting_list as $elem)
                            <div id="id_{{$elem['name']}}" class="row">
                                <div class="offset-lg-1 offset-md-1 offset-sm-1 offset-1 col-lg-1 col-md-1 col-sm-1 col-1">
                                    @if ($elem['type'] == "building") 
                                        <i class="fas fa-hammer icon"></i>
                                    @elseif ($elem['type'] == "tech")
                                        <i class="fas fa-flask icon"></i>
                                    @else
                                        <i class="fas fa-cog icon"></i>
                                    @endif
                                </div>
                                <span style="color: gold">@if($elem['type'] == 'item') {{ $elem['name'] }} x{{ $elem['quantity'] }} @else {{ $elem['name'] }} @endif}</span>
                                <span id="compteur_{{ $elem['name'] }}" duration="{{ $elem['duration'] }}" name="{{ $elem['name'] }}" @if($elem['type'] == 'item') quantity="x{{ $elem['quantity'] }}" @endif class="col-lg-8 col-md-8 col-sm-8 col-8 infos-building-wip"></span>
                                <div id="interrupt_{{ $elem['name'] }}" class="col-lg-2 col-md-2 col-sm-2 col-2">
                                    <i title="Interrompre" onclick="interrupt('{{ $elem['wait_id'] }}', '{{ $elem['type'] }}', 'id_{{ $elem['name'] }}')" class="fas fa-times icon-red"></i>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            <input id="_token" name="_token" type="hidden" value="{{csrf_token()}}">
        </div>
        <script>
            launch_all_timers();
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
                {
                    let id_to_hide = id.replace(/compteur/gi, "interrupt");
                    document.getElementById(id_to_hide).remove();
                    compteur.textContent = " Terminé";
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
                    compteur.textContent= name + " " + h+" "+m+" "+s;
                    setTimeout(function(same_id=id, new_duration=duration-1){
                        timer(same_id, new_duration);
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