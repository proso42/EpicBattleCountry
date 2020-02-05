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
        <div id="block_edit" class="edit-city-name-block" style="display: none">
            <div id="error_empty_input" class="home-input-error" style="display: none;">
                <p>@lang('error.thx_fill_field')</p>
            </div>
            <div id="error_invalid_input" class="home-input-error" style="display: none;">
                <p>@lang('error.invalid_name')</p>
            </div>
            <div id="error_already_taken" class="home-input-error" style="display: none;">
                <p>@lang('error.already_taken')</p>
            </div>
            <div id="name_changed" class="home-input-success" style="display: none;">
                <p>@lang('home.name_changed')</p>
            </div>
            <h3>@lang('home.change_city_name')</h3>
            <input id="new_name" type="text" class="edit-city-name-input" placeholder="@lang('home.new_name')">
            <br/>
            <input onclick="rename()" id="rename_button" type="button" class="home-button" value="@lang('common.rename')">
            <input onclick="cancel_rename()" id="cancel_button" type="button" class="home-button-cancel" value="@lang('common.cancel')">
            <img id="spin" class="explo-spin" style="display: none" src="images/loader.gif">
        </div>
        @if (count($user_cities) > 0)
            <div id="error_empty_input2" class="home-input-error" style="display: none;">
                <p>@lang('error.thx_fill_field')</p>
            </div>
            <div id="block_change_city" class="edit-city-name-block" style="display: none">
                <div id="city_list">
                    <ul>
                        @foreach ($user_cities as $city)
                            <li onclick="choice_city('{{ $city->id }}')" id="city_{{ $city->id }}" class="city-li">{{ $city->name }}</li>
                        @endforeach
                        <input onclick="switch_city()" id="switch_button" type="button" class="home-button" value="@lang('common.change')">
                        <input onclick="cancel_switch()" id="cancel_button_switch" type="button" class="home-button-cancel" value="@lang('common.cancel')">
                    </ul>
                </div>
            </div>
        @endif
        @include('default')
            <div class="offset-lg-0 offset-md-2 offset-sm-1 offset-1 col-lg-9 col-md-7 col-sm-10 col-10 center-win" style="margin-top: 50px; padding-right: 10px;">
                <div style="text-align:center;display: inline-block">
                    <h2 id="city_name">{{ $util->name }} <i onclick="show_edit_block()" class="fas fa-edit" style="cursor:pointer;margin-left: 25px;font-size: 15px"></i> @if (count($user_cities) > 0) <i onclick="show_switch_block()" class="fas fa-sync-alt" style="cursor:pointer;margin-left: 25px;font-size: 15px"></i>@endif</h2> 
                </div>
                <hr class="signin-footer">
                @if (count($enemy_on_the_way) > 0)
                    <div class="waiting-list" style="margin-bottom: 20px;border-color: crimson">
                        @foreach ($enemy_on_the_way as $enemy)
                            <div class="row">
                                <div class="offset-lg-1 offset-md-1 offset-sm-1 offset-1 col-lg-1 col-md-1 col-sm-1 col-1">
                                    <i class="fas fa-fist-raised icon-color-red"></i>
                                </div>
                                <div id="compteur_{{ $enemy['wait_id'] }}_attack" name="@lang('common.enemy_attack')" duration="{{ $enemy['duration'] }}" class="col-lg-8 col-md-8 col-sm-8 col-8 infos-building-wip"></div>
                            </div>
                        @endforeach
                    </div>
                    <hr class="signin-footer">
                @endif
                <div class="prod-div">
                    <h3 class="home-title-table">@lang('home.prod') <i id="prod_table_eye" onclick="switch_part('prod_table')" @if($tables_class['prod'] == 0) class="fas fa-eye icon-eye" @else class="fas fa-eye-slash icon-eye" @endif></i></h3>
                    <table id="prod_table" class="prod-table" @if($tables_class['prod'] == 0) style="display: none" @endif>
                        <tr>
                            <td>@lang('home.res')</td><td>@lang('home.prod_per_h')</td><td>@lang('home.max_storage')</td>
                        </tr>
                        <tr>
                            <td>@lang('common.food')</td><td>{{ $util->food_prod * 60 }}</td><td>{{ $util->max_food }}</td>
                        </tr>
                        <tr>
                            <td>@lang('common.wood')</td><td>{{ $util->wood_prod * 60 }}</td><td>{{ $util->max_wood }}</td>
                        </tr>
                        <tr>
                            <td>@lang('common.rock')</td><td>{{ $util->rock_prod * 60 }}</td><td>{{ $util->max_rock }}</td>
                        </tr>
                        <tr>
                            <td>@lang('common.steel')</td><td>{{ $util->steel_prod * 60 }}</td><td>{{ $util->max_steel }}</td>
                        </tr>
                        <tr>
                            <td>@lang('common.gold')</td><td>{{ $util->gold_prod * 60 }}</td><td>{{ $util->max_gold }}</td>
                        </tr>
                        <tr>
                            <td>@lang('common.faith')</td><td>{{ $util->faith_prod }}</td><td>{{ $util->max_faith }}</td>
                        </tr>
                        @if ($util->mount_prod > 0)
                            <tr>
                                <td>@lang('army.mount')</td><td>{{ $util->mount_prod }}</td><td>{{ $util->max_mount }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
                @if (count($items_owned) > 0)
                <hr class="signin-footer">
                <div class="prod-div">
                    <h3 class="home-title-table">@lang('home.available_items') <i id="item_table_eye" onclick="switch_part('item_table')" @if($tables_class['item'] == 0) class="fas fa-eye icon-eye" @else class="fas fa-eye-slash icon-eye" @endif></i></h3>
                    <table id="item_table" class="prod-table" @if($tables_class['item'] == 0) style="display: none" @endif>
                        <tr>
                            <td>@lang('home.item')</td><td>@lang('common.quantity')</td>
                        </tr>
                        @foreach ($items_owned as $item)
                            <tr>
                                <td>{{ $item['name'] }}</td><td id="item_id_{{ $item['name'] }}">{{ $item['quantity'] }}</td>
                            </tr>
                        @endforeach
                        @if ($util->diamond > 0)
                            <tr>
                                <td>@lang('common.diamond')</td><td id="item_diamond }}">{{ $util->diamond }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
                @endif
                @if (count($units_owned) > 0)
                <hr class="signin-footer">
                <div class="prod-div">
                    <h3 class="home-title-table">@lang('home.available_units') <i id="unit_table_eye" onclick="switch_part('unit_table')" @if($tables_class['unit'] == 0) class="fas fa-eye icon-eye" @else class="fas fa-eye-slash icon-eye" @endif></i></h3>
                    <table id="unit_table" class="prod-table" @if($tables_class['unit'] == 0) style="display: none" @endif>
                        <tr>
                            <td>@lang('common.unit')</td><td>@lang('home.workforce')</td>
                        </tr>
                        @foreach ($units_owned as $unit)
                            <tr>
                                <td>@if ($unit['is_mercenary'] == 1)  <i class="fas fa-coins"> </i> @endif{{ $unit['name'] }}</td><td id="id_{{ $unit['name'] }}">{{ $unit['quantity'] }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                @endif
                @if (count($waiting_list) > 0)
                    <hr class="signin-footer">
                    <div class="waiting-list" style="margin-bottom: 20px;">
                        @foreach ($waiting_list as $elem)
                            <div id="id_{{$elem['name']}}_{{ $elem['wait_id'] }}" class="row">
                                <div class="offset-lg-1 offset-md-1 offset-sm-1 offset-1 col-lg-1 col-md-1 col-sm-1 col-1">
                                    @if ($elem['type'] == "building") 
                                        <i id="icon_{{$elem['name']}}_{{ $elem['wait_id'] }}" class="fas fa-hammer icon"></i>
                                    @elseif ($elem['type'] == "tech")
                                        <i id="icon_{{$elem['name']}}_{{ $elem['wait_id'] }}" class="fas fa-flask icon"></i>
                                    @elseif ($elem['type'] == "item")
                                        <i id="icon_{{$elem['name']}}_{{ $elem['wait_id'] }}" class="fas fa-cog icon"></i>
                                    @elseif ($elem['type'] == "unit")
                                        <i id="icon_{{$elem['name']}}_{{ $elem['wait_id'] }}" class="fas fa-chess-rook icon"></i>
                                    @elseif ($elem['type'] == "explo")
                                        <i id="icon_{{$elem['name']}}_{{ $elem['wait_id'] }}" class="fas fa-map-marked-alt"></i>
                                    @elseif ($elem['type'] == "battle")
                                        <i id="icon_{{$elem['name']}}_{{ $elem['wait_id'] }}" class="fas fa-fist-raised"></i>
                                    @else
                                        <i id="icon_{{$elem['name']}}_{{ $elem['wait_id'] }}" class="fas fa-shoe-prints"></i>
                                    @endif
                                </div>
                                <div id="compteur_{{ $elem['name'] }}_{{ $elem['wait_id'] }}" duration="{{ $elem['duration'] }}" name="{{ $elem['name'] }}" @if($elem['type'] == 'item' || $elem['type'] == 'unit') quantity="x{{ $elem['quantity'] }}" @endif class="col-lg-8 col-md-8 col-sm-8 col-8 infos-building-wip"></div>
                                <div id="interrupt_{{ $elem['name'] }}_{{ $elem['wait_id'] }}" class="col-lg-2 col-md-2 col-sm-2 col-2">
                                    @if($elem['type'] != "explo" || $elem['allow_interrupt'] == 1) <i title="Interrompre" onclick="interrupt('{{ $elem['wait_id'] }}', '{{ $elem['type'] }}', 'id_{{ $elem['name'] }}_{{ $elem['wait_id'] }}')" class="fas fa-times icon-red"></i> @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            <input id="_token" name="_token" type="hidden" value="{{csrf_token()}}">
        </div>
        <script src="/js/home.js"></script>
    </body>
</html>