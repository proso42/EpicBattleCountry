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
        <div id="block_desc" class="divinity-block-desc" style="display: none">
            <h2 id="block_desc_title"></h2>
            <p id="block_desc_p"></p>
            <input onclick="ok()" id="ok_button" type="button" class="return-button" value="@lang('common.ok')">
        </div>
            @include('default')
            <div class="offset-lg-0 offset-md-2 offset-sm-1 offset-1 col-lg-9 col-md-7 col-sm-10 col-10 center-win" style="margin-top: 50px; padding-right: 10px;">
                <div id="error_city_and_dest" class="explo-input-error" style="display: none;">
                    <p>@lang('error.city_and_dest')</p>
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
                <div id="error_cannot_attack_allied" class="explo-input-error" style="display: none;">
                    <p>@lang('error.cannot_target_allied')</p>
                </div>
                <div id="disaster_success" class="explo-input-success" style="display: none;">
                    <p>@lang('divinity.disaster_success')</p>
                </div>
                <div id="main_panel">
                    <div class="row">
                        <div id="blessing-tab" class="col-lg-3 col-md-3 col-sm-4 col-4 generique-tab" onclick="switchTab('blessing')">
                            @lang('divinity.blessing')
                        </div>
                        <div id="disaster-tab" class="col-lg-3 col-md-3 col-sm-4 col-4 generique-tab" onclick="switchTab('disaster')">
                            @lang('divinity.disaster')
                        </div>
                    </div>
                    <div id="blessing-panel" class="row" style="margin-top: 30px;display:none">
                        <p>blessing</p>
                    </div>
                    <div id="disaster-panel" class="row" style="margin-top: 30px;display:none">
                        @if ($disaster_cool_down != null)
                            <div>
                                <span>@lang('divinity.next_disaster') : </span><span class="divinity-cool-down" id="disaster_count" duration="{{ $disaster_cool_down }}"></span>
                            </div>
                        @else
                            @foreach ($allowed_disaster as $disaster)
                                <div class="divinity-block" id="disaster_{{ $disaster['id'] }}" desc="{{ $disaster['desc'] }}" name="{{ $disaster['name'] }}">
                                    <div onclick="show_desc('disaster_{{ $disaster['id'] }}')" class="divinity-name">{{ $disaster["name"] }}</div> 
                                    <img class="divinity" style="width:250px;height: 250px;" src="{{ $disaster['illustration'] }}">
                                    <div id="disaster_id_{{ $disaster['id'] }}" @if ($disaster['faith_cost'] > $util->faith) class="divinity-button-impossible" @else class="divinity-button" onclick="choice_disaster_target('{{ $disaster['id'] }}')"@endif>                                
                                        <span>@lang('common.trigger') <i class="fas fa-praying-hands"></i></span>
                                        <div id="disaster_cost{{ $disaster['id'] }}" class="divinity-res-needed">
                                            <ul>
                                                <li>@lang('common.faith') : {{ $disaster['faith_cost'] }} <i @if ($disaster['faith_cost'] > $util->faith) class="fas fa-times icon" @else class="fas fa-check icon" @endif></i></li>
                                                <li>@lang('common.cool_down') : {{ $disaster['cool_down'] }} <i class="fas fa-clock"></i></li>
                                            </ul>
                                        </div>                        
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                <div id="block_disaster_target" style="display: none; text-align:center; margin-top: 25px;">
                    <h2>@lang('exploration.dest')</h2>
                    <hr class="signin-footer">
                    @if ($visible_cities != null)
                        <h4>@lang('common.target')</h3>
                        @foreach ($visible_cities as $target)
                            <div onclick="select_target_city('{{ $target->name }}')" id="id_target_city_{{ $target->name }}" class="row divinity-line" style="cursor: pointer;text-align: center;">
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
                    <input onclick="confirm_target()" id="button_confirm_dest" type="button" class="home-button" value="@lang('common.confirm')">
                    <input onclick="back_to_main()" id="cancel_button1" type="button" class="home-button-cancel" value="@lang('common.return')">
                </div>
                <div id="confirm_disaster" style="display: none">
                    <h2>@lang('divinity.confirm_disaster')</h2>
                    <div id="div_disaster_target" class="divinity-confirm-line">
                        <div id="warning" style="color: crimson;display: none">
                            <i class="fas fa-exclamation-triangle icon-color-orange" style="margin-right: 15px"></i>
                            <span class="explo-warning-text">@lang('exploration.unknow_target') : <span>
                            <span id="warning_coord"></span>
                            <i class="fas fa-exclamation-triangle icon-color-orange" style="margin-left: 15px"></i>
                        </div>
                        <div id="info_target">
                            <span>@lang('common.target') : </span>
                            <span id="disaster_target"></span>
                        </div>
                    </div>
                    <div class="divinity-confirm-line">
                        <span>@lang('divinity.disaster') : </span><span id="disaster_name"></span>
                    </div>
                    <input onclick="trigger_disaster()" id="button_trigger_disaster" type="button" class="home-button" value="@lang('common.confirm')">
                    <input onclick="back_choice_target()" id="cancel_trigger_disaster" type="button" class="home-button-cancel" value="@lang('common.return')">
                    <img id="spin_disaster" class="explo-spin" style="display: none" src="images/loader.gif">
                </div>
                <hr class="signin-footer">
                <div style="text-align: left">
                    <span><i class="fas fa-praying-hands"></i> @lang('divinity.available_faith') : </span><span>{{ $util->faith }}</span>
                </div>
                <input id="_token" name="_token" type="hidden" value="{{csrf_token()}}">
                <div id="fat" style="display: none" divinity_active_tab="{{ $divinity_active_tab }}" ></div>
            </div>
        </div>
        <script src="/js/divinity.js"></script>
    </body>
</html>