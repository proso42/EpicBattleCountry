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
                        <!-- SLOT 1 -->
                        <div id="slot1" class="row" style="align-items: baseline;line-height: 31px;">
                            <div class="mercenary-unit offset-lg-2 offset-md-2 offset-sm-2 offset-2 col-lg-2 col-md-2 col-sm-2 col-2" style="text-align:center">
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
                                <span>@lang('common.quantity') : {{ $slots['slot1']['quantity'] }}</span>
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
                                <button class="mercenary-button-rdm"><i class="fas fa-random"></i></button>
                            </div>
                            <div class="col-lg-1 col-md-1 col-sm-1 col-1">
                                <button class="mercenary-button-up"><i class="fas fa-sort-amount-up"></i></button>
                            </div>    
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <input id="_token" name="_token" type="hidden" value="{{csrf_token()}}">
        <script src="/js/taverne.js"></script>
    </body>
</html>