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
            <input onclick="hide_cell_info()" id="return_button" type="button" class="return-button" value="retour">            
        </div>
        @include('default')
            <div class="offset-lg-0 offset-md-2 offset-sm-1 offset-1 col-lg-9 col-md-7 col-sm-10 col-10 center-win" style="margin-top: 50px; padding-right: 10px;">
                @if ($cartographer == 0)
                    <p>Vous devez construire nu Cartoraphe pour pouvoir utiliser la carte !</p>
                @else
                    @if ($move_map == 1)
                        <i class="fas fa-arrow-circle-left" style="cursor: pointer" onclick="move_map({{ $x_pos - $city_x + 1}}, {{ $y_pos - $city_y}})"></i>
                        <i class="fas fa-arrow-circle-up" style="cursor: pointer" onclick="move_map({{ $x_pos - $city_x}}, {{ $y_pos - $city_y + 1 }})"></i>
                        <i class="fas fa-arrow-circle-down" style="cursor: pointer" onclick="move_map({{ $x_pos - $city_x}}, {{ $y_pos - $city_y - 1 }})"></i>
                        <i class="fas fa-arrow-circle-right" style="cursor: pointer" onclick="move_map({{ $x_pos - $city_x - 1}}, {{ $y_pos - $city_y}})"></i>
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
                                                @if ($visible_cells[$i]['type'] == 'capital' || $visible_cells[$i]['type'] == 'city') name="{{ $visible_cells[$i]['name'] }}" diplomatie="{{ $visible_cells[$i]['diplomatie'] }}" owner_race="{{ $visible_cells[$i]['race'] }}" @endif
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
                        <li><i class="fas fa-star"></i> Votre ville</li>
                        <li><i class="fas fa-city"></i> Autre ville</li>
                        <li><i class="fas fa-dungeon"></i> Ruine</li>
                        <li><i class="fas fa-dragon"></i> Tannière d'un dragon</li>
                        <li><i class="fas fa-water"></i> Fleuve / Lac</li>
                        <li><i class="fas fa-mountain"></i> Montagne</li>
                        <li><i class="fas fa-tree"></i> Fôret</li>
                    </ul>
                @endif
            </div>
        </div>
        <script>

            setTimeout(() =>{
                let body_height = document.body.scrollHeight + 20;
                let win_height = window.innerHeight;
                if (body_height > win_height)
                    document.getElementById("overlay").style.height = body_height + "px";
                else
                    document.getElementById("overlay").style.height = win_height + "px";
            }, 1000);

            function move_map(x_offset, y_offset)
            {
                window.location.href = '/map?x_offset=' + x_offset + '&y_offset=' + y_offset;
            }

            function display_cell_info(id)
            {
                let cell = document.getElementById(id);
                let icon = document.getElementById(id + "_icon").className + " large-icon";
                document.getElementById("cell_info_icon").className = icon;
                let x_pos = cell.getAttribute("x_pos");
                let y_pos = cell.getAttribute("y_pos");
                document.getElementById("cell_coord").textContent = x_pos + "/" + y_pos;
                let type = cell.getAttribute("type");
                document.getElementById("cell_type").textContent = type;
                if (type == "city" || type == "capital")
                {
                    let name = cell.getAttribute("name");
                    let diplomatie = cell.getAttribute("diplomatie");
                    let race = cell.getAttribute("owner_race");
                    document.getElementById("cell_diplomatie").textContent = diplomatie;
                    document.getElementById("city_name").textContent = name;
                    document.getElementById("cell_owner_race").textContent = race;
                    document.getElementById("cell_diplomatie").style.display = "";
                    document.getElementById("city_name").style.display = "";
                    document.getElementById("cell_owner_race").style.display = "";
                }
                else
                {
                    document.getElementById("city_name").style.display = "none";
                    document.getElementById("cell_owner_race").style.display = "";
                    document.getElementById("cell_diplomatie").style.display = "none";
                }
                document.getElementById("overlay").style.display = "";
                document.getElementById("cell_info").style.display = "";
            }

            function hide_cell_info()
            {
                document.getElementById("overlay").style.display = "none";
                document.getElementById("cell_info").style.display = "none";
            }
        </script>
    </body>
</html>