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
            <div class="offset-lg-0 offset-md-2 offset-sm-1 offset-1 col-lg-9 col-md-7 col-sm-10 col-10 center-win" style="margin-top: 50px; padding: 0;">
                <div id="action_choice" class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-6" onmouseover="show_img('left')" onmouseout="hide_img('left')">
                        <div id="left_overlay" class="invasion-left-overlay"></div>
                        <img class="invasion-left-image" src="images/invasion_battle.jpg">
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-6" onmouseover="show_img('right')" onmouseout="hide_img('right')" onclick="step2()">
                        <div id="right_overlay" class="invasion-right-overlay"></div>
                        <img class="invasion-right-image" src="images/invasion_moving.jpg">
                    </div>
                </div>
                <div id="list_unit" style="display: none; text-align: center;">
                    <ul style="text-align: left">
                        @foreach ($info_unit as $unit)
                            <li unit_ref="{{ $unit['ref'] }}">{{ $unit['name'] }} x{{ $unit['quantity'] }}</li>
                        @endforeach
                        <input onclick="back_step1()" id="cancel_button_1" type="button" class="home-button-cancel" value="@lang('common.return')">
                    </ul>
                </div>
            </div>
            <input id="_token" name="_token" type="hidden" value="{{csrf_token()}}">
        </div>
        <script src="/js/invasion.js"></script>
    </body>
</html>