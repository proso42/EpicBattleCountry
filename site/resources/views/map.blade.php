<!DOCTYPE html>
<html>
<head>
        <meta charset="utf8">
        <title>EpicBattleCorp</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <script src="https://kit.fontawesome.com/345fcffdfe.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" type="text/css" href="css/style.css"/>
    </head>
    <body>
        <div id="overlay" class="map-overlay" style="display: none">
        </div>
        <div id="cell_info" class="map-cell-info" style="display: none">
            <i id="cell_info_icon" class=""></i>
            <br/>
            <h3 id="city_name"></h3>
            <ul style="text-align: left">
                <li class="map-cell-info-line" id="cell_coord"></li>
                <li class="map-cell-info-line" id="cell_type"></li>
                <li class="map-cell-info-line" id="cell_owner_race"></li>
                <li class="map-cell-info-line" id="cell_diplomatie"></li>
            </ul>
            <input onclick="hide_cell_info()" id="return_button" type="button" class="return-button" value="@lang('common.return')">            
        </div>
        @include('default')
            <div class="offset-lg-0 offset-md-2 offset-sm-1 offset-1 col-lg-9 col-md-7 col-sm-10 col-10 center-win" style="margin-top: 50px; padding-right: 10px;">
                @if ($cartographer == 0)
                    <p>@lang('map.need_cartographer')</p>
                @else
                    @if ($move_map == 1)
                        <i class="fas fa-arrow-circle-left" style="cursor: pointer" onclick="move_map({{ $x_pos - $util->x_pos + 1}}, {{ $y_pos - $util->y_pos}})"></i>
                        <i class="fas fa-arrow-circle-up" style="cursor: pointer" onclick="move_map({{ $x_pos - $util->x_pos}}, {{ $y_pos - $util->y_pos + 1 }})"></i>
                        <i class="fas fa-arrow-circle-down" style="cursor: pointer" onclick="move_map({{ $x_pos - $util->x_pos}}, {{ $y_pos - $util->y_pos - 1 }})"></i>
                        <i class="fas fa-arrow-circle-right" style="cursor: pointer" onclick="move_map({{ $x_pos - $util->x_pos - 1}}, {{ $y_pos - $util->y_pos}})"></i>
                    @endif
                    <br/>
                    <div style="display: inline-block">
                        <table class="map-table">
                            <?php $i=0; ?>
                            @for ($y = $y_pos + $cartographer; $y >= $y_pos - $cartographer; $y--)
                                <tr>
                                    @for ($x = $x_pos - $cartographer; $x <= $x_pos + $cartographer; $x++)
                                        @if ($i < count($visible_cells) && $x == $visible_cells[$i]['x_pos'] && $y == $visible_cells[$i]['y_pos'])
                                            <td id="cell_{{ $i }}" onclick="display_cell_info('cell_{{ $i }}')" x_pos="{{ $visible_cells[$i]['x_pos'] }}" y_pos="{{ $visible_cells[$i]['y_pos'] }}" type="{{ $visible_cells[$i]['type'] }}" 
                                                @if ($visible_cells[$i]['format_type'] == 'capital' || $visible_cells[$i]['format_type'] == 'city') format_type="{{ $visible_cells[$i]['format_type'] }}" name="{{ $visible_cells[$i]['name'] }}" diplomatie="{{ $visible_cells[$i]['diplomatie'] }}" owner_race="{{ $visible_cells[$i]['race'] }}" @endif
                                                title="{{ $visible_cells[$i]['x_pos'] }}/{{ $visible_cells[$i]['y_pos'] }}" class="map-cell" style="cursor: help;background-color: {{ $visible_cells[$i]['background-color'] }};color: {{ $visible_cells[$i]['color'] }}">
                                                <i id="cell_{{ $i  }}_icon" class="fas {{ $visible_cells[$i]['class'] }} "></i>
                                            </td>
                                            <?php $i++; ?>
                                        @else
                                            <td title="{{ $x }}/{{ $y }}" class="map-empty-cell"><i class="fas fa-road"></i></td>
                                        @endif
                                    @endfor
                                </tr>
                            @endfor
                        </table>
                    </div>
                    <br/>
                    
                    <hr class="signin-footer">
                    <ul style="text-align: left">
                        <li><i class="fas fa-star" style="color: green"></i> @lang('map.capital')</li>
                        <li><i class="fas fa-city" style="color:green"></i> @lang('map.owned_city')</li>
                        <li><i class="fas fa-city" style="color: darkred"></i> @lang('map.bad_city')</li>
                        <li><i class="fas fa-city" style="color: lightskyblue"></i> @lang('map.good_city')</li>
                        <li><i class="fas fa-city"></i> @lang('map.other_city')</li>
                        <li><i class="fas fa-dungeon"></i> @lang('map.ruins')</li>
                        <li><i class="fas fa-spider"></i> @lang('map.spider')</li>
                        <li><i class="fas fa-dragon"></i> @lang('map.dragon')</li>
                        <li><i class="fas fa-water"></i> @lang('map.water')</li>
                        <li><i class="fas fa-mountain"></i> @lang('map.mountain')</li>
                        <li><i class="fas fa-tree"></i> @lang('map.forest')</li>
                    </ul>
                @endif
            </div>
        </div>
        <script src="/js/map.js"></script>
    </body>
</html>