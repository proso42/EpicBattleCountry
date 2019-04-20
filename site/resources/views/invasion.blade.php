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
        <div id="block_edit" class="edit-unit-quantity-block" style="display: none">
            <h3 id="quantity_title"></h3>
            <input id="new_quantity" type="text" class="edit-unit-quantity-input" placeholder="@lang('common.quantity')">
            <br/>
            <input id="edit_button" type="button" class="home-button" value="@lang('common.confirm')">
            <input id="cancel_button" type="button" class="home-button-cancel" value="@lang('common.cancel')">
        </div>
        @include('default')
            <div class="offset-lg-0 offset-md-2 offset-sm-1 offset-1 col-lg-9 col-md-7 col-sm-10 col-10 center-win" style="margin-top: 50px; padding: 0;">
                <div id="action_choice" class="row no-gutters">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-6" onmouseover="show_img('left')" onmouseout="hide_img('left')">
                        <div id="left_overlay" class="invasion-left-overlay"></div>
                        <img class="invasion-left-image" src="images/invasion_battle.jpg">
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-6" onmouseover="show_img('right')" onmouseout="hide_img('right')" onclick="step2()">
                        <div id="right_overlay" class="invasion-right-overlay"></div>
                        <img class="invasion-right-image" src="images/invasion_moving.jpg">
                    </div>
                </div>
                <div id="list_unit" style="display: none; text-align: center;margin-top: 25px;">
                        <h2>@lang('invasion.unit_to_move')</h2>
                        @foreach ($info_unit as $unit)
                            <div id="unit_{{ $unit['ref'] }}" class="row invasion-unit-line" unit_ref="{{ $unit['ref'] }}">
                                <span class="col-lg-5 col-md-5 col-sm-5 col-5" style="text-align: left">{{ $unit['name'] }}</span>
                                <span onclick="manual('{{ $unit['ref'] }}', '{{ $unit['name'] }}', '{{ $unit['quantity'] }}')" id="{{ $unit['ref'] }}_selected" class="col-lg-4 col-md-4 col-sm-4 col-4" style="cursor: pointer"> 0/{{ $unit['quantity'] }}</span>
                                <span class="col-lg-1 col-md-1 col-sm-1 col-1"><i ondblclick="add_max('{{ $unit['ref'] }}', '{{ $unit['quantity'] }}')" onmousedown="add_unit('{{ $unit['ref'] }}', '{{ $unit['quantity'] }}', 0)" class="fas fa-plus invasion-plus"></i></span>
                                <span class="col-lg-1 col-md-1 col-sm-1 col-1"><i ondblclick="remove_all('{{ $unit['ref'] }}', '{{ $unit['quantity'] }}')" onmousedown="remove_unit('{{ $unit['ref'] }}', '{{ $unit['quantity'] }}'), 0" class="fas fa-minus invasion-minus"></i></span>
                            </div>
                        @endforeach
                        <input onclick="step3()" id="button_step3" type="button" class="home-button" value="@lang('common.confirm')">
                        <input onclick="back_step1()" id="cancel_button_1" type="button" class="home-button-cancel" value="@lang('common.return')">
                </div>
                <div id="list_city" style="display: none">
                    @if ($user_cities == null)
                        <h2>@lang('invasion.no_other_city')</h2>
                    @else
                        <h2>@lang('invasion.select_city')</h2>
                        @foreach ($user_cities as $city)
                            <div id="id_{{ $city->name }}" onclick="select_city('{{ $city->name }}')" class="row invasion-city-line" style="text-align: center; cursor: pointer">
                                <span class="col-lg-12 col-md-12 col-sm-12 col-12">{{ $city->name }} <i id="city_{{ $city->name }}" class="fas fa-check icon-color-green" style="display: none"></i></span>
                            </div>
                        @endforeach
                        <input onclick="step4()" id="button_step4" type="button" class="home-button" value="@lang('common.confirm')">
                        <input onclick="back_step2()" id="cancel_button_2" type="button" class="home-button-cancel" value="@lang('common.return')">
                    @endif
                </div>
                <div id="confirm_move_unit" style="display: none">
                    <h2>@lang('invasion.confirm_move_unit')</h2>
                    <p class="invasion-unit-line" id="move_unit_duration"></p>
                    <input onclick="step5()" id="button_step5" type="button" class="home-button" value="@lang('common.confirm')">
                    <input onclick="back_step3()" id="cancel_button_3" type="button" class="home-button-cancel" value="@lang('common.return')">
                </div>
            </div>
            <input id="_token" name="_token" type="hidden" value="{{csrf_token()}}">
        </div>
        <script src="/js/invasion.js"></script>
    </body>
</html>