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
        <div id="overlay" class="mercenary-overlay" style="display: none">
        </div>
        <div id="block_upgrade" class="mercenary-hidden-block" style="display: none">
            <div id="success_mercenary_upgraded" class="mercenary-success" style="display: none;">
                <p>@lang('mercenaries.mercenary_upgraded')</p>
            </div>
            <div id="error_hacker" class="mercenary-error" style="display: none;">
                <p>@lang('error.an_error_occured')</p>
            </div>
            <div id="error_max" class="mercenary-error" style="display: none;">
                <p>@lang('error.mercenaries_upgrade_max')</p>
            </div>
            <div id="error_diamond" class="mercenary-error" style="display: none;">
                <p>@lang('error.missing_diamond')</p>
            </div>
            <h3>@lang('mercenaries.up')</h3>
            <p>@lang('mercenaries.explain_up')</p>
            <button onclick="confirm_upgrade()" id="confirm_upgrade_button" class="mercenary-button">@lang('common.pay') 1 <i class="fas fa-gem"></i></button>
            <button onclick="cancel_upgrade()" id="cancel_upgrade_button" class="mercenary-button-cancel">@lang('common.cancel')</button>
            </br>
            <img id="spin_upgrade" class="explo-spin" style="display: none" src="images/loader.gif">
        </div>
        <div id="block_randomize" class="mercenary-hidden-block" style="display: none">
            <h3>@lang('mercenaries.randomize')</h3>
            <div id="success_mercenary_randomized" class="mercenary-success" style="display: none;">
                <p>@lang('mercenaries.mercenary_randomized')</p>
            </div>
            <p>@lang('mercenaries.explain_randomize')</p>
            <button onclick="confirm_randomize()" id="confirm_randomize_button" class="mercenary-button">@lang('common.pay') 1 <i class="fas fa-gem"></i></button>
            <button onclick="cancel_randomize()" id="cancel_randomize_button" class="mercenary-button-cancel">@lang('common.cancel')</button>
            </br>
            <img id="spin_randomize" class="explo-spin" style="display: none" src="images/loader.gif">
        </div>
        @include('default')
            <div class="offset-lg-0 offset-md-2 offset-sm-1 offset-1 col-lg-9 col-md-7 col-sm-10 col-10 center-win" style="margin-top: 50px; padding-right: 10px;">
                @if ($allowed === 0)
                    <p>@lang('mercenaries.missing_tavern')</p>
                @else
                    <!-- Listes des slots -->
                    <h1 style="margin-top: 25px">@lang('building.Taverne')</h1>
                    <div style="text-align: left">
                        <span>@lang('mercenaries.next_switch')</span>
                        <span id="main_timing" duration="{{ $next_switch }}"></span>
                    </div>
                    <div class="mercenary-slot">
                        <!-- SLOT 1 ACTIF -->
                        @if ($slots['slot1']['available'] == 1)
                            <div id="slot1" class="row" style="align-items: baseline;line-height: 31px;">
                                <div class="mercenary-unit offset-lg-1 offset-md-1 offset-sm-1 offset-1 col-lg-3 col-md-3 col-sm-3 col-3" style="text-align:center">
                                    <span>{{ $slots['slot1']['name'] }}</span>
                                    <div class="mercenary-info-unit">
                                        <ul>
                                            <li>@lang('army.life') : {{ $slots['slot1']['life'] }} <i class="fas fa-heartbeat"></i></li>
                                            <li>@lang('army.speed') : {{ $slots['slot1']['speed'] }} <i class="fas fa-tachometer-alt"></i></li>
                                            <li>@lang('army.power') : {{ $slots['slot1']['power'] }} <i class="fas fa-fist-raised"></i></li>
                                            <li>@lang('army.storage') : {{ $slots['slot1']['storage'] }} <i class="fas fa-box"></i></li>
                                            <li>@lang('common.cool_down') : {{ $slots['slot1']['cool_down'] }} <i class="fas fa-clock"></i></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="mercenary-quantity col-lg-2 col-md-2 col-sm-2 col-2">
                                    <span>@lang('common.quantity') : </span><span id="slot1_qt">{{ $slots['slot1']['quantity'] }}</span>
                                </div>
                                <div class="mercenary-ressources col-lg-2 col-md-2 col-sm-2 col-2">
                                    <span>@lang('common.price')</span>
                                    <div class="mercenary-ressources-details">
                                        <ul>
                                            <li>@lang('common.gold') : {{ $slots['slot1']['gold'] }}</li>
                                            <span>@lang('common.or')</span>
                                            <li>@lang('common.diamond') : {{ $slots['slot1']['diamond'] }}</li>
                                        </ul>
                                    </div>
                                </div>
                                <input onclick="recruit()" type="button" class="mercenary-button col-lg-2 col-md-2 col-sm-2 col-2" value="@lang('common.recruit')">
                            </div>
                            <div id="slot1_option" class="row" style="align-items: baseline;line-height: 31px;">
                                <div class="offset-lg-5 offset-md-5 offset-sm-5 offset-5 col-lg-1 col-md-1 col-sm-1 col-1">
                                    <button onclick="randomize(1)" class="mercenary-button-rdm"><i class="fas fa-random"></i></button>
                                </div>
                                <div class="col-lg-1 col-md-1 col-sm-1 col-1">
                                    <button onclick="upgrade(1)" class="mercenary-button-up"><i class="fas fa-sort-amount-up"></i></button>
                                </div>    
                            </div>
                        @else
                            <!-- SLOT 1 INACTIF -->
                            <div style="text-align: center;color: white;font-weight: bold">
                                <span>@lang('mercenaries.unavailable_slot')</span>
                                <span id="timing_slot1" duration="{{ $slots['slot1']['cool_down'] }}"></span>
                            </div>
                        @endif
                    </div>
                    @if($allowed >= 10)
                        <div class="mercenary-slot">
                        <!-- SLOT 2 ACTIF -->
                            @if ($slots['slot2']['available'] == 1)
                                <div id="slot2" class="row" style="align-items: baseline;line-height: 31px;">
                                    <div class="mercenary-unit offset-lg-1 offset-md-1 offset-sm-1 offset-1 col-lg-3 col-md-3 col-sm-3 col-3" style="text-align:center">
                                        <span>{{ $slots['slot2']['name'] }}</span>
                                        <div class="mercenary-info-unit">
                                            <ul>
                                                <li>@lang('army.life') : {{ $slots['slot2']['life'] }} <i class="fas fa-heartbeat"></i></li>
                                                <li>@lang('army.speed') : {{ $slots['slot2']['speed'] }} <i class="fas fa-tachometer-alt"></i></li>
                                                <li>@lang('army.power') : {{ $slots['slot2']['power'] }} <i class="fas fa-fist-raised"></i></li>
                                                <li>@lang('army.storage') : {{ $slots['slot2']['storage'] }} <i class="fas fa-box"></i></li>
                                                <li>@lang('common.cool_down') : {{ $slots['slot2']['cool_down'] }} <i class="fas fa-clock"></i></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="mercenary-quantity col-lg-2 col-md-2 col-sm-2 col-2">
                                        <span>@lang('common.quantity') : </span><span id="slot2_qt">{{ $slots['slot2']['quantity'] }}</span>
                                    </div>
                                    <div class="mercenary-ressources col-lg-2 col-md-2 col-sm-2 col-2">
                                        <span>@lang('common.price')</span>
                                        <div class="mercenary-ressources-details">
                                            <ul>
                                                <li>@lang('common.gold') : {{ $slots['slot2']['gold'] }}</li>
                                                <span>@lang('common.or')</span>
                                                <li>@lang('common.diamond') : {{ $slots['slot2']['diamond'] }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <input onclick="recruit()" type="button" class="mercenary-button col-lg-2 col-md-2 col-sm-2 col-2" value="@lang('common.recruit')">
                                </div>
                                <div id="slot2_option" class="row" style="align-items: baseline;line-height: 31px;">
                                    <div class="offset-lg-5 offset-md-5 offset-sm-5 offset-5 col-lg-1 col-md-1 col-sm-1 col-1">
                                        <button onclick="randomize(2)" class="mercenary-button-rdm"><i class="fas fa-random"></i></button>
                                    </div>
                                    <div class="col-lg-1 col-md-1 col-sm-1 col-1">
                                        <button onclick="upgrade(2)" class="mercenary-button-up"><i class="fas fa-sort-amount-up"></i></button>
                                    </div>    
                                </div>
                            @else
                                <!-- SLOT 2 INACTIF -->
                                <div style="text-align: center;color: white;font-weight: bold">
                                    <span>@lang('mercenaries.unavailable_slot')</span>
                                    <span id="timing_slot2" duration="{{ $slots['slot2']['cool_down'] }}"></span>
                                </div>
                            @endif
                        </div>
                    @endif
                    @if($allowed >= 25)
                        <div class="mercenary-slot">
                        <!-- SLOT 3 ACTIF -->
                            @if ($slots['slot3']['available'] == 1)
                                <div id="slot3" class="row" style="align-items: baseline;line-height: 31px;">
                                    <div class="mercenary-unit offset-lg-1 offset-md-1 offset-sm-1 offset-1 col-lg-3 col-md-3 col-sm-3 col-3" style="text-align:center">
                                        <span>{{ $slots['slot3']['name'] }}</span>
                                        <div class="mercenary-info-unit">
                                            <ul>
                                                <li>@lang('army.life') : {{ $slots['slot3']['life'] }} <i class="fas fa-heartbeat"></i></li>
                                                <li>@lang('army.speed') : {{ $slots['slot3']['speed'] }} <i class="fas fa-tachometer-alt"></i></li>
                                                <li>@lang('army.power') : {{ $slots['slot3']['power'] }} <i class="fas fa-fist-raised"></i></li>
                                                <li>@lang('army.storage') : {{ $slots['slot3']['storage'] }} <i class="fas fa-box"></i></li>
                                                <li>@lang('common.cool_down') : {{ $slots['slot3']['cool_down'] }} <i class="fas fa-clock"></i></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="mercenary-quantity col-lg-2 col-md-2 col-sm-2 col-2">
                                        <span>@lang('common.quantity') : </span><span id="slot3_qt">{{ $slots['slot3']['quantity'] }}</span>
                                    </div>
                                    <div class="mercenary-ressources col-lg-2 col-md-2 col-sm-2 col-2">
                                        <span>@lang('common.price')</span>
                                        <div class="mercenary-ressources-details">
                                            <ul>
                                                <li>@lang('common.gold') : {{ $slots['slot3']['gold'] }}</li>
                                                <span>@lang('common.or')</span>
                                                <li>@lang('common.diamond') : {{ $slots['slot3']['diamond'] }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <input onclick="recruit()" type="button" class="mercenary-button col-lg-2 col-md-2 col-sm-2 col-2" value="@lang('common.recruit')">
                                </div>
                                <div id="slot3_option" class="row" style="align-items: baseline;line-height: 31px;">
                                    <div class="offset-lg-5 offset-md-5 offset-sm-5 offset-5 col-lg-1 col-md-1 col-sm-1 col-1">
                                        <button onclick="randomize(3)" class="mercenary-button-rdm"><i class="fas fa-random"></i></button>
                                    </div>
                                    <div class="col-lg-1 col-md-1 col-sm-1 col-1">
                                        <button onclick="upgrade(3)" class="mercenary-button-up"><i class="fas fa-sort-amount-up"></i></button>
                                    </div>    
                                </div>
                            @else
                                <!-- SLOT 3 INACTIF -->
                                <div style="text-align: center;color: white;font-weight: bold">
                                    <span>@lang('mercenaries.unavailable_slot')</span>
                                    <span id="timing_slot3" duration="{{ $slots['slot3']['cool_down'] }}"></span>
                                </div>
                            @endif
                        </div>
                    @endif
                @endif
            </div>
        </div>
        <input id="_token" name="_token" type="hidden" value="{{csrf_token()}}">
        <script src="/js/taverne.js"></script>
    </body>
</html>