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
                <div id="sending_failed" class="explo-input-error" @if ($sending_expedition_failed == 0) style="display: none;" @endif>
                    <p>@lang('error.an_error_occured')</p>
                </div>
                <div id="sending_success" class="explo-input-success" @if ($sending_expedition_success == 0) style="display: none;" @endif>
                    <p>@lang('exploration.exp_send')</p>
                </div>
                <div id="error_empty_input" class="explo-input-error" style="display: none;">
                    <p>@lang('error.thx_fill_fields')</p>
                </div>
                <div id="error_bad_input" class="explo-input-error" style="display: none;">
                    <p>@lang('error.thx_correclty_fill_fields')</p>
                </div>
                <div id="error_limit_value" class="explo-input-error" style="display: none;">
                    <p>@lang('error.limit_coord')</p>
                </div>
                <div id="error_no_move" class="explo-input-error" style="display: none;">
                    <p>@lang('error.exp_not_moving')</p>
                </div>
                <div id="explo_choice" class="row" style="margin-top: 30px">
                    <div class="explo-block">
                        <div class="explo-name">@lang('exploration.scout')</div>
                        <img class="explo" style="width:250px;height: 250px;" src="{{ $explo[0]['illustration'] }}">
                        <div @if ($explo[0]['unit_required'] > $unit_avaible || $explo[0]['food_required'] > $food || $explo[0]['wood_required'] > $wood || $explo[0]['rock_required'] > $rock || $explo[0]['steel_required'] > $steel || $explo[0]['gold_required'] > $gold) class="explo-button-impossible" @else class="explo-button" onclick="choice(1)"@endif>                                
                            @lang('common.choice') <i class="fas fa-map-marked-alt"></i>
                            <div class="explo-needed">
                                <ul>
                                    <li>{{ $explo_unit_name }} : {{ $explo[0]['unit_required'] }} @if ($explo[0]['unit_required'] > $unit_avaible) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                    @if ($explo[0]['food_required'] > 0)
                                        <li>@lang('common.food') : {{ $explo[0]['food_required'] }} @if ($explo[0]['food_required'] > $food) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                    @endif
                                    @if ($explo[0]['wood_required'] > 0)
                                        <li>@lang('common.wood') : {{ $explo[0]['wood_required'] }} @if ($explo[0]['wood_required'] > $wood) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                    @endif
                                    @if ($explo[0]['rock_required'] > 0)
                                        <li>@lang('common.rock') : {{ $explo[0]['rock_required'] }} @if ($explo[0]['rock_required'] > $rock) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                    @endif
                                    @if ($explo[0]['steel_required'] > 0)
                                        <li>@lang('common.steel') : {{ $explo[0]['steel_required'] }} @if ($explo[0]['steel_required'] > $steel) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                    @endif
                                    @if ($explo[0]['gold_required'] > 0)
                                        <li>@lang('common.gold') : {{ $explo[0]['gold_required'] }} @if ($explo[0]['gold_required'] > $gold) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                    @endif
                                </ul>
                            </div>                        
                        </div>
                    </div>
                    <div class="explo-block">
                        <div class="explo-name">@lang('exploration.raid_dungeon')</div>
                        <img class="explo" style="width:250px;height: 250px;" src="{{ $explo[1]['illustration'] }}">
                        <div @if ($explo[1]['unit_required'] > $unit_avaible || $explo[1]['food_required'] > $food || $explo[1]['wood_required'] > $wood || $explo[1]['rock_required'] > $rock || $explo[1]['steel_required'] > $steel || $explo[1]['gold_required'] > $gold) class="explo-button-impossible" @else class="explo-button" onclick="choice(2)"@endif>                                
                            @lang('common.choice') <i class="fas fa-map-marked-alt"></i>
                            <div class="explo-needed">
                                <ul>
                                    <li>{{ $explo_unit_name }} : {{ $explo[1]['unit_required'] }} @if ($explo[1]['unit_required'] > $unit_avaible) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                    @if ($explo[1]['food_required'] > 0)
                                        <li>@lang('common.food') : {{ $explo[1]['food_required'] }} @if ($explo[1]['food_required'] > $food) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                    @endif
                                    @if ($explo[1]['wood_required'] > 0)
                                        <li>@lang('common.wood') : {{ $explo[1]['wood_required'] }} @if ($explo[1]['wood_required'] > $wood) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                    @endif
                                    @if ($explo[1]['rock_required'] > 0)
                                        <li>@lang('common.rock') : {{ $explo[1]['rock_required'] }} @if ($explo[1]['rock_required'] > $rock) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                    @endif
                                    @if ($explo[1]['steel_required'] > 0)
                                        <li>@lang('common.steel') : {{ $explo[1]['steel_required'] }} @if ($explo[1]['steel_required'] > $steel) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                    @endif
                                    @if ($explo[1]['gold_required'] > 0)
                                        <li>@lang('common.gold') : {{ $explo[1]['gold_required'] }} @if ($explo[1]['gold_required'] > $gold) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                    @endif
                                </ul>
                            </div>                        
                        </div>
                    </div>
                    <div class="explo-block">
                        <div class="explo-name">@lang('exploration.raid_battlefield')</div>
                        <img class="explo" style="width:250px;height: 250px;" src="{{ $explo[2]['illustration'] }}">
                        <div @if ($explo[2]['unit_required'] > $unit_avaible || $explo[2]['food_required'] > $food || $explo[2]['wood_required'] > $wood || $explo[2]['rock_required'] > $rock || $explo[2]['steel_required'] > $steel || $explo[2]['gold_required'] > $gold) class="explo-button-impossible" @else class="explo-button" onclick="choice(3)"@endif>                                
                            @lang('common.choice') <i class="fas fa-map-marked-alt"></i>
                            <div class="explo-needed">
                                <ul>
                                    <li>{{ $explo_unit_name }} : {{ $explo[2]['unit_required'] }} @if ($explo[2]['unit_required'] > $unit_avaible) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                    @if ($explo[2]['food_required'] > 0)
                                        <li>@lang('common.food') : {{ $explo[2]['food_required'] }} @if ($explo[2]['food_required'] > $food) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                    @endif
                                    @if ($explo[2]['wood_required'] > 0)
                                        <li>@lang('common.wood') : {{ $explo[2]['wood_required'] }} @if ($explo[2]['wood_required'] > $wood) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                    @endif
                                    @if ($explo[2]['rock_required'] > 0)
                                        <li>@lang('common.rock') : {{ $explo[2]['rock_required'] }} @if ($explo[2]['rock_required'] > $rock) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                    @endif
                                    @if ($explo[2]['steel_required'] > 0)
                                        <li>@lang('common.steel') : {{ $explo[2]['steel_required'] }} @if ($explo[2]['steel_required'] > $steel) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                    @endif
                                    @if ($explo[2]['gold_required'] > 0)
                                        <li>@lang('common.gold') : {{ $explo[2]['gold_required'] }} @if ($explo[2]['gold_required'] > $gold) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                    @endif
                                </ul>
                            </div>                        
                        </div>
                    </div>
                    <div class="explo-block">
                        <div class="explo-name">@lang('exploration.colonize')</div>
                        <img class="explo" style="width:250px;height: 250px;" src="{{ $explo[3]['illustration'] }}">
                        <div @if ($explo[3]['unit_required'] > $unit_avaible || $explo[3]['food_required'] > $food || $explo[3]['wood_required'] > $wood || $explo[3]['rock_required'] > $rock || $explo[3]['steel_required'] > $steel || $explo[3]['gold_required'] > $gold) class="explo-button-impossible" @else class="explo-button" onclick="choice(4)"@endif>                                
                            @lang('common.choice') <i class="fas fa-map-marked-alt"></i>
                            <div class="explo-needed">
                                <ul>
                                    <li>{{ $explo_unit_name }} : {{ $explo[3]['unit_required'] }} @if ($explo[3]['unit_required'] > $unit_avaible) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                    @if ($explo[3]['food_required'] > 0)
                                        <li>@lang('common.food') : {{ $explo[3]['food_required'] }} @if ($explo[3]['food_required'] > $food) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                    @endif
                                    @if ($explo[3]['wood_required'] > 0)
                                        <li>@lang('common.wood') : {{ $explo[3]['wood_required'] }} @if ($explo[3]['wood_required'] > $wood) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                    @endif
                                    @if ($explo[3]['rock_required'] > 0)
                                        <li>@lang('common.rock') : {{ $explo[3]['rock_required'] }} @if ($explo[3]['rock_required'] > $rock) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                    @endif
                                    @if ($explo[3]['steel_required'] > 0)
                                        <li>@lang('common.steel') : {{ $explo[3]['steel_required'] }} @if ($explo[3]['steel_required'] > $steel) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                    @endif
                                    @if ($explo[3]['gold_required'] > 0)
                                        <li>@lang('common.gold') : {{ $explo[3]['gold_required'] }} @if ($explo[3]['gold_required'] > $gold) <i class="fas fa-times icon"></i> @else <i class="fas fa-check icon"></i> @endif</li>
                                    @endif
                                </ul>
                            </div>                        
                        </div>
                    </div>
                </div>
                <div id="explo_dest" class="explo-dest">
                    <h2>@lang('exploration.dest')</h2>
                    <input id="dest_x" type="text" class="explo-input" placeholder="X">
                    <input id="dest_y" type="text" class="explo-input" placeholder="Y">
                </div>
                <div id="explo_confirm" class="confirm-win" style="display:none">
                    <h3 id="title_choice_1" style="display:none">@lang('exploration.go_scouting')</h3>
                    <h3 id="title_choice_2" style="display:none">@lang('exploration.raid_dungeon')</h3>
                    <h3 id="title_choice_3" style="display:none">@lang('exploration.raid_battlefield')</h3>
                    <h3 id="title_choice_4" style="display:none">@lang('exploration.colonize_new_place')</h3>
                    <div id="warning" style="color: crimson;display: none"><i class="fas fa-exclamation-triangle icon-color-orange" style="margin-right: 15px"></i><span class="explo-warning-text">@lang('exploration.unknow_target')<span><i class="fas fa-exclamation-triangle icon-color-orange" style="margin-left: 15px"></i></div>
                    <p>@lang('exploration.traveling_duration') : <span id="finishing_time"></span><i class="fas fa-clock"></i></p>
                    <input onclick="confirm()" id="confirm-button" type="button" class="explo-button-confirm" value="@lang('common.confirm')">
                    <input onclick="cancel()" id="cancel-button" type="button" class="explo-button-cancel" value="@lang('common.cancel')">
                    <img id="spin" class="explo-spin" style="display: none" src="images/loader.gif">
                </div>
            </div>
        </div>
        <input id="_token" name="_token" type="hidden" value="{{csrf_token()}}">
        <script>

            var g_choice = -1;
            setTimeout(() =>{
                        document.getElementById("sending_failed").style.display = "none";
                        document.getElementById("sending_success").style.display = "none";
            }, 5000);


            function cancel()
            {
                document.getElementById("warning").style.display = "none";
                document.getElementById("finishing_time").textContent = "";
                document.getElementById("explo_confirm").style.display = "none";
                for (let i = 1; i < 5; i++)
                    document.getElementById("title_choice_" + i).style.display = "none";
                document.getElementById("dest_x").value = "";
                document.getElementById("dest_y").value = "";
                document.getElementById("explo_choice").style.display = "";
                document.getElementById("explo_dest").style.display = "";
            }

            function confirm()
            {
                var _token = document.getElementById("_token").value;
                var dest_x = document.getElementById("dest_x").value;
                var dest_y = document.getElementById("dest_y").value;
                document.getElementById("confirm-button").style.display = "none";
                document.getElementById("confirm-button").disabled = "true";
                document.getElementById("cancel-button").style.display = "none";
                document.getElementById("confirm-button").disabled = "true";
                document.getElementById("spin").style.display = "";
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'http://www.epicbattlecorp.fr/send_expedition');
                xhr.onreadystatechange =  function()
                {
                    if (xhr.readyState === 4 && xhr.status === 200)
                    {
                        console.log(xhr.responseText)
                        if (xhr.responseText == 1)
                            window.location.reload();
                    }
                }
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.send('_token=' + _token + '&choice=' + g_choice + "&dest_x=" + dest_x + "&dest_y=" + dest_y);
                setTimeout(() =>{
                    window.location.reload();
                }, 2000);
            }

            function check_coord(n)
            {
                if (n == "")
                {
                    document.getElementById("error_empty_input").style.display = "";
                    setTimeout(() =>{
                        document.getElementById("error_empty_input").style.display = 'none';
                    }, 5000);
                    return 0;
                }
                else if (!(!isNaN(parseFloat(n)) && isFinite(n)))
                {
                    document.getElementById("error_bad_input").style.display = "";
                    setTimeout(() =>{
                        document.getElementById("error_bad_input").style.display = 'none';
                    }, 5000);
                    return 0;
                }
                else if (parseInt(n) < -2000 || parseInt(n) > 2000)
                {
                    document.getElementById("error_limit_value").style.display = "";
                    setTimeout(() =>{
                        document.getElementById("error_limit_value").style.display = 'none';
                    }, 5000);
                    return 0;
                }
                return 1;
            }

            function choice(type)
            {
                var dest_x = document.getElementById("dest_x").value;
                var dest_y = document.getElementById("dest_y").value;
                if (check_coord(dest_x) == 0 || check_coord(dest_y) == 0 || type < 1 || type > 4)
                    return ;
                g_choice = type;
                dest_x = parseInt(dest_x);
                dest_y = parseInt(dest_y);
                var _token = document.getElementById("_token").value;
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'http://www.epicbattlecorp.fr/time_explo');
                xhr.onreadystatechange =  function()
                {
                    if (xhr.readyState === 4 && xhr.status === 200)
                    {
                        console.log(xhr.responseText)
                        if (xhr.responseText == 1)
                        {
                            document.getElementById("ajax_error").style.display = "";
                            setTimeout(() =>{
                                document.getElementById("ajax_error").style.display = 'none';
                            }, 5000);
                            return 0;
                        }
                        else if (xhr.responseText == "no_move")
                        {
                            document.getElementById("error_no_move").style.display = "";
                            setTimeout(() =>{
                                document.getElementById("error_no_move").style.display = 'none';
                            }, 5000);
                            return 0;
                        }
                        else
                        {
                            let response = xhr.responseText.split(";");
                            document.getElementById("explo_choice").style.display = "none";
                            document.getElementById("explo_dest").style.display = "none";
                            document.getElementById("explo_confirm").style.display = "";
                            document.getElementById("title_choice_" + type).style.display = "";
                            document.getElementById("finishing_time").textContent = response[0] + " ";
                            if (response[1] == "warning")
                                document.getElementById("warning").style.display = "";
                        }
                    }
                }
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.send('_token=' + _token + '&dest_x=' + dest_x + '&dest_y=' + dest_y + "&choice=" + type);
            }
        </script>
    </body>
</html>