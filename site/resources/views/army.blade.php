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
                <div id="error_empty_input" class="forge-input-error" style="display: none;">
                    <p>@lang('error.thx_fill_field')</p>
                </div>
                <div id="error_bad_input" class="forge-input-error" style="display: none;">
                    <p>@lang('error.thx_corrctly_fill_field')</p>
                </div>
                <div id="error_negative_value" class="forge-input-error" style="display: none;">
                    <p>@lang('error.give_val_pos')</p>
                </div>
                @if ($allowed == 0)
                    <p>@lang('army.need_barrack')</p>
                @elseif ($allowed == -1)
                    <div class="confirm-win">
                        <h3>@lang('army.training')</h3>
                        <p>{{ $waiting_units['name'] }} x{{ $waiting_units['quantity'] }}</p>
                        <p id="unit_timer" duration="{{ $waiting_units['finishing_date']}} "></p>
                        <input id="interrupt_unit_button" onclick="interrupt_unit()" type="button" class="army-button-cancel" value="@lang('common.cancel')">
                    </div>
                @else
                    <div id="unit_list">
                        @foreach ($allowed_units as $unit)
                            <div id="id_{{ $unit['name'] }}" class="row" style="align-items: baseline;line-height: 31px;">
                                <div class="army-unit offset-lg-2 offset-md-2 offset-sm-2 offset-2 col-lg-2 col-md-2 col-sm-2 col-2" style="text-align:center">
                                    <span>{{ $unit['name'] }}</span>
                                    <div class="army-info-unit">
                                        <ul>
                                            <li>@lang('army.life') : {{ $unit['life'] }} <i class="fas fa-heartbeat"></i></li>
                                            <li>@lang('army.speed') : {{ $unit['speed'] }} <i class="fas fa-tachometer-alt"></i></li>
                                            <li>@lang('army.power') : {{ $unit['power'] }} <i class="fas fa-fist-raised"></i></li>
                                            <li>@lang('army.storage') : {{ $unit['storage'] }} <i class="fas fa-box"></i></li>
                                        </ul>
                                    </div>
                                </div>
                                <input id="input_{{ $unit['unit_id'] }}" type="text" placeholder="@lang('common.quantity')" class="army-input col-lg-2 col-md-2 col-sm-2 col-2">
                                <div class="army-ressources col-lg-2 col-md-2 col-sm-2 col-2">
                                    <span>@lang('common.price')</span>
                                    <div class="army-ressources-details">
                                        <ul>
                                            @if ($unit['food'] > 0)
                                                <li>@lang('common.food') : {{ $unit['food'] }}</li>
                                            @endif
                                            @if ($unit['wood'] > 0)
                                                <li>@lang('common.wood') : {{ $unit['wood'] }}</li>
                                            @endif
                                            @if ($unit['rock'] > 0)
                                                <li>@lang('common.rock') : {{ $unit['rock'] }}</li>
                                            @endif
                                            @if ($unit['steel'] > 0)
                                                <li>@lang('common.steel') : {{ $unit['steel'] }}</li>
                                            @endif
                                            @if ($unit['gold'] > 0)
                                                <li>@lang('common.gold') : {{ $unit['gold'] }}</li>
                                            @endif
                                            @if ($unit['items'] !== null)
                                                @foreach ($unit['items'] as $item)
                                                    <li>{{ $item['name'] }} <i class="fas fa-cog icon"></i></li>
                                                @endforeach
                                            @endif
                                            @if ($unit['mount'] !== null)
                                                <li>@lang('army.mount') : {{ $unit['mount'] }} <i class="fas fa-paw"></i></li>
                                            @endif
                                            <li>@lang('common.time') : {{ $unit['duration'] }} <i class="fas fa-clock"></i></li>
                                        </ul>
                                    </div>
                                </div>
                                <input onclick="train('{{ $unit['unit_id'] }}')" type="button" class="army-button col-lg-2 col-md-2 col-sm-2 col-2" value="@lang('army.train')">
                            </div>
                        @endforeach
                    </div>
                    <div id="confirm_win" class="confirm-win" style="display: none">
                        <h3 id="confirm-title" style="margin-top: 25px"></h3>
                        <ul style="text-align:left;margin-top: 25px;">
                            <li id="list1"><span style="margin-right:5px" id="food_list"></span><i id="food_icon" class=""></i></li>
                            <li id="list2"><span style="margin-right:5px" id="wood_list"></span><i id="wood_icon" class=""></i></li>
                            <li id="list3"><span style="margin-right:5px" id="rock_list"></span><i id="rock_icon" class=""></i></li>
                            <li id="list4"><span style="margin-right:5px" id="steel_list"></span><i id="steel_icon" class=""></i></li>
                            <li id="list5"><span style="margin-right:5px" id="gold_list"></span><i id="gold_icon" class=""></i></li>
                            @for ($i = 0; $i < 10; $i++)
                                <li id="list{{ $i + 6}}"><span style="margin-right: 5px;" id="item_list{{ $i }}"></span><i id="item_{{ $i }}_icon" class=""></i></li>
                            @endfor
                            <li id="list_last"><span style="margin-right:5px" id="mount_list"></span><i id="mount_icon" class=""></i></li>
                            <li><span style="margin-right:5px" id="time_list"></span><i class="fas fa-clock"></i></li>
                        </ul>
                        <input onclick="confirm()" id="confirm-button" type="button" class="army-button" value="@lang('common.confirm')">
                        <input onclick="cancel()" type="button" class="army-button-cancel" value="@lang('common.cancel')">
                    </div>
                @endif
            </div>
        </div>
        <input id="_token" name="_token" type="hidden" value="{{csrf_token()}}">
        <script>
            window.translations = {{ Cache::get('translations') }}
        </script>
        <script src="/js/army.js"></script>
    </body>
</html>