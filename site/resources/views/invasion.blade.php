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
            <div class="offset-lg-0 offset-md-2 offset-sm-0 offset-0 col-lg-9 col-md-7 col-sm-11 center-win" style="margin-top: 50px; padding: 0;">
                <div id="error_no_unit_selected" class="forge-input-error" style="display: none;">
                    <p>@lang('error.no_unit_selected')</p>
                </div>
                <div id="units_move_success" class="explo-input-success" style="display: none;">
                    <p>@lang('invasion.units_move_success')</p>
                </div>
                <div id="attack_launch" class="explo-input-success" style="display: none;">
                    <p>@lang('invasion.attack_launch')</p>
                </div>
                <div id="error_city_and_dest" class="explo-input-error" style="display: none;">
                    <p>@lang('error.city_and_dest')</p>
                </div>
                <div id="error_cannot_attack_allied" class="explo-input-error" style="display: none;">
                    <p>@lang('error.cannot_attack_allied')</p>
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
                <div id="action_choice" class="row no-gutters">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-6" onmouseover="show_img('left')" onmouseout="hide_img('left')" onclick="step2bis()">
                        <div id="left_overlay" class="invasion-left-overlay"></div>
                        <img class="invasion-left-image" src="images/invasion_battle.jpg">
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-6" onmouseover="show_img('right')" onmouseout="hide_img('right')" onclick="step2()">
                        <div id="right_overlay" class="invasion-right-overlay"></div>
                        <img class="invasion-right-image" src="images/invasion_moving.jpg">
                    </div>
                </div>
                <div id="list_unit" style="display: none; text-align: center;margin-top: 25px;">
                        @if ($info_unit == null)
                            <h2>@lang('invasion.no_unit')</h2>
                            <input onclick="back_step1()" id="cancel_button_1" type="button" class="home-button-cancel" value="@lang('common.return')">
                        @else
                            <h2>@lang('invasion.select_units')</h2>
                            @foreach ($info_unit as $unit)
                                <div id="unit_{{ $unit['ref'] }}" class="row invasion-unit-line" unit_ref="{{ $unit['ref'] }}" storage="{{ $unit['storage'] }}" unit_name="{{ $unit['name'] }}">
                                    <span class="col-lg-5 col-md-5 col-sm-5 col-5" style="text-align: left">{{ $unit['name'] }}</span>
                                    <span onclick="manual('{{ $unit['ref'] }}', '{{ $unit['name'] }}', '{{ $unit['quantity'] }}')" id="{{ $unit['ref'] }}_selected" class="col-lg-4 col-md-4 col-sm-4 col-4" style="cursor: pointer"> 0/{{ $unit['quantity'] }}</span>
                                    <span class="col-lg-1 col-md-1 col-sm-1 col-1"><i ondblclick="add_max('{{ $unit['ref'] }}', '{{ $unit['quantity'] }}')" onmousedown="add_unit('{{ $unit['ref'] }}', '{{ $unit['quantity'] }}', 0)" class="fas fa-plus invasion-plus"></i></span>
                                    <span class="col-lg-1 col-md-1 col-sm-1 col-1"><i ondblclick="remove_all('{{ $unit['ref'] }}', '{{ $unit['quantity'] }}')" onmousedown="remove_unit('{{ $unit['ref'] }}', '{{ $unit['quantity'] }}'), 0" class="fas fa-minus invasion-minus"></i></span>
                                </div>
                            @endforeach
                            <input id="button_step3" type="button" class="home-button" value="@lang('common.confirm')">
                            <input onclick="back_step1()" id="cancel_button_1" type="button" class="home-button-cancel" value="@lang('common.return')">
                        @endif
                </div>
                <div id="block_dest" style="display: none; text-align:center; margin-top: 25px;">
                    <h2>@lang('exploration.dest')</h2>
                    <hr class="signin-footer">
                    @if ($attackable_cities != null)
                        <h4>@lang('invasion.attackable_cities')</h3>
                        @foreach ($attackable_cities as $target)
                            <div onclick="select_target_city('{{ $target->name }}')" id="id_target_city_{{ $target->name }}" class="row invasion-city-line" style="cursor: pointer;text-align: center;">
                                <span style="text-align: left" class="offset-lg-4 offset-md-4 offset-sm-4 offset-4 col-lg-8 col-md-8 col-sm-8 col-8">
                                    {{ $target->name }} ({{ $target->x_pos }}/{{ $target->y_pos }})
                                    <i id="target_city_{{ $target->name }}" class="fas fa-check icon-color-green" style="display: none"></i>
                                </span>
                            </div>
                        @endforeach
                    @endif
                    <hr class="signin-footer">
                    <h4>@lang('invasion.manual_coord')</h3>
                    <div id="inputs_dest" class="explo-dest" style="margin-top: 0px">
                        <input id="dest_x" type="text" class="explo-input" placeholder="X">
                        <input id="dest_y" type="text" class="explo-input" placeholder="Y">
                    </div>
                    <input onclick="step_confirm_dest()" id="button_confirm_dest" type="button" class="home-button" value="@lang('common.confirm')">
                    <input onclick="back_step2bis()" id="cancel_button_2bis" type="button" class="home-button-cancel" value="@lang('common.return')">
                </div>
                <div id="list_res_item" style="display: none; text-align: center;margin-top: 25px;">
                    <h2>@lang('invasion.res_to_move')</h2>
                    <div id="fret_div" class="row invasion-unit-line">
                        <span class="col-lg-5 col-md-5 col-sm-5 col-5" style="text-align: left">@lang('invasion.freight')</span>
                        <span class="col-lg-4 col-md-4 col-sm-4 col-4" id="fret"></span>
                        <span class="col-lg-2 col-md-2 col-sm-2 col-2" style="margin-left: 5px"><i onclick="reset_fret()" class="fas fa-undo-alt" style="cursor: pointer"></i></span>
                    </div>
                    @foreach ($res as $re => $val)
                        <div id="res_{{ $re }}" res_name="@lang('common.' . $re)" quantity="{{ $val }}" class="row invasion-unit-line">
                            <span class="col-lg-5 col-md-5 col-sm-5 col-5" style="text-align: left">@lang('common.' . $re)</span>
                            <span onclick="manual_res('{{ $re }}', '{{ trans('common.' . $re) }}', '{{ $val }}')" id="res_{{ $re }}_selected" class="col-lg-4 col-md-4 col-sm-4 col-4" style="cursor: pointer"> 0/{{ $val }}</span>
                            <span class="col-lg-1 col-md-1 col-sm-1 col-1"><i ondblclick="add_max_res('{{ $re }}', '{{ $val }}')" onmousedown="add_res('{{ $re }}', '{{ $val }}', 0)" class="fas fa-plus invasion-plus"></i></span>
                            <span class="col-lg-1 col-md-1 col-sm-1 col-1"><i ondblclick="remove_all_res('{{ $re }}', '{{ $val }}')" onmousedown="remove_res('{{ $re }}', '{{ $val }}'), 0" class="fas fa-minus invasion-minus"></i></span>
                        </div>
                    @endforeach
                    @if ($info_item != null)
                        @foreach ($info_item as $item)
                            <div id="res_{{ $item['ref'] }}" res_name="{{ $item['name'] }}" quantity="{{ $item['quantity'] }}" class="row invasion-unit-line">
                                <span class="col-lg-5 col-md-5 col-sm-5 col-5" style="text-align: left">{{ $item['name'] }}</span>
                                <span onclick="manual_res('{{ $item['ref'] }}', '{{ $item['name'] }}', '{{ $item['quantity'] }}')" id="res_{{ $item['ref'] }}_selected" class="col-lg-4 col-md-4 col-sm-4 col-4" style="cursor: pointer"> 0/{{ $item['quantity'] }}</span>
                                <span class="col-lg-1 col-md-1 col-sm-1 col-1"><i ondblclick="add_max_res('{{ $item['ref'] }}', '{{ $item['quantity'] }}')" onmousedown="add_res('{{ $item['ref'] }}', '{{ $item['quantity'] }}', 0)" class="fas fa-plus invasion-plus"></i></span>
                                <span class="col-lg-1 col-md-1 col-sm-1 col-1"><i ondblclick="remove_all_res('{{ $item['ref'] }}', '{{ $item['quantity'] }}')" onmousedown="remove_res('{{ $item['ref'] }}', '{{ $item['quantity'] }}'), 0" class="fas fa-minus invasion-minus"></i></span>
                            </div>
                        @endforeach
                    @endif
                    <input onclick="step3()" id="button_step3" type="button" class="home-button" value="@lang('common.confirm')">
                    <input onclick="back_step2()" id="cancel_button_1" type="button" class="home-button-cancel" value="@lang('common.return')">
                </div>
                <div id="list_city" style="display: none">
                    @if ($user_cities == null)
                        <h2>@lang('invasion.no_other_city')</h2>
                        <input onclick="back_step_res()" id="cancel_button_2" type="button" class="home-button-cancel" value="@lang('common.return')">
                    @else
                        <h2>@lang('invasion.select_city')</h2>
                        @foreach ($user_cities as $city)
                            <div id="id_{{ $city->name }}" onclick="select_city('{{ $city->name }}')" class="row invasion-city-line" style="text-align: center; cursor: pointer">
                                <span style="text-align:left" class="offset-lg-4 offset-md-4 offset-sm-4 offset-4 col-lg-6 col-md-6 col-sm-6 col-6">{{ $city->name }} <i id="city_{{ $city->name }}" class="fas fa-check icon-color-green" style="display: none"></i></span>
                            </div>
                        @endforeach
                        <input onclick="step4()" id="button_step4" type="button" class="home-button" value="@lang('common.confirm')">
                        <input onclick="back_step_res()" id="cancel_button_2" type="button" class="home-button-cancel" value="@lang('common.return')">
                    @endif
                </div>
                <div id="confirm_move_unit" style="display: none">
                    <h2>@lang('invasion.confirm_move_unit')</h2>
                    <div id="move_unit_duration" class="invasion-unit-line">
                        <span id="travel_duration"></span>
                    </div>
                    <div id="div_target_city" class="invasion-unit-line">
                        <span>@lang('invasion.target_city')</span>
                        <span id="target_city_name"></span>
                    </div>
                    <input onclick="step5()" id="button_step5" type="button" class="home-button" value="@lang('common.confirm')">
                    <input onclick="back_step3()" id="cancel_button_3" type="button" class="home-button-cancel" value="@lang('common.return')">
                    <img id="spin" class="explo-spin" style="display: none" src="images/loader.gif">
                </div>
                <div id="confirm_attack" style="display: none">
                    <h2>@lang('invasion.confirm_attack')</h2>
                    <div id="div_attack_target" class="invasion-unit-line">
                        <div id="warning" style="color: crimson;display: none">
                            <i class="fas fa-exclamation-triangle icon-color-orange" style="margin-right: 15px"></i>
                            <span class="explo-warning-text">@lang('exploration.unknow_target') : <span>
                            <span id="warning_coord"></span>
                            <i class="fas fa-exclamation-triangle icon-color-orange" style="margin-left: 15px"></i>
                        </div>
                        <div id="info_target">
                            <span>@lang('common.target') : </span>
                            <span id="attack_target"></span>
                        </div>
                    </div>
                    <div id="attack_duration" class="invasion-unit-line">
                        <span id="attack_travel_duration"></span>
                    </div>
                    <input onclick="step_attack()" id="button_step_attack" type="button" class="home-button" value="@lang('common.confirm')">
                    <input onclick="back_step_dest()" id="cancel_button_attack" type="button" class="home-button-cancel" value="@lang('common.return')">
                    <img id="spin_attack" class="explo-spin" style="display: none" src="images/loader.gif">
                </div>
            </div>
            <input id="_token" name="_token" type="hidden" value="{{csrf_token()}}">
        </div>
        <script src="/js/invasion.js"></script>
    </body>
</html>