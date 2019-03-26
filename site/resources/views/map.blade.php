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
                <i class="fas fa-arrow-circle-up" style="cursor: pointer" onclick="window.location.href='htpp://www.epicbatllecorp.fr/map?x_offset=0&y_offset=1'"></i>
                <br/>
                <div style="display: inline-block">
                    <i class="fas fa-arrow-circle-left" style="cursor: pointer" onclick="window.location.href='htpp://www.epicbatllecorp.fr/map?x_offset=-1&y_offset=0'"></i>
                    <table class="map-table">
                        <?php $i=0; ?>
                        @for ($y = $y_pos + $cartographer; $y >= $y_pos - $cartographer; $y--)
                            <tr>
                                @for ($x = $x_pos - $cartographer; $x <= $x_pos + $cartographer; $x++)
                                    @if ($i < count($visible_cells) && $x == $visible_cells[$i]['x_pos'] && $y == $visible_cells[$i]['y_pos'])
                                        <td title="{{ $visible_cells[$i]['x_pos'] }}/{{ $visible_cells[$i]['y_pos'] }} {{ $visible_cells[$i]['title'] }}" class="map-cell" style="background-color: {{ $visible_cells[$i]['background-color'] }}"><i class="fas {{ $visible_cells[$i]['class'] }}"></i></td>
                                        <?php $i++; ?>
                                    @else
                                        <td title="{{ $x }}/{{ $y }}" class="map-empty-cell"><i class="fas fa-road"></i></td>
                                    @endif
                                @endfor
                            </tr>
                        @endfor
                    </table>
                    <i class="fas fa-arrow-circle-right" style="cursor: pointer" onclick="window.location.href='htpp://www.epicbatllecorp.fr/map?x_offset=1&y_offset=0'"></i>
                </div>
                <br/>
                <i class="fas fa-arrow-circle-down" style="cursor: pointer" onclick="window.location.href='htpp://www.epicbatllecorp.fr/map?x_offset=0&y_offset=-1'"></i>
                <hr class="signin-footer">
                <ul style="text-align: left">
                    <li><i class="fas fa-star"></i> Votre ville</li>
                    <li><i class="fas fa-city"></i> Autre ville</li>
                    <li><i class="fas fa-dungeon"></i> Ruine</li>
                    <li><i class="fas fa-dragon"></i> Tannière d'un dragon</li>
                    <li><i class="fas fa-water"></i> Fleuve / Lac</li>
                </ul>
            </div>
        </div>
        <input id="_token" name="_token" type="hidden" value="{{csrf_token()}}">
    </body>
</html>