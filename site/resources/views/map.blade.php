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
                <table class="map-table">
                    <?php $i=0; ?>
                    @for ($y = $y_pos + $cartographer; $y >= $y_pos - $cartographer; $y--)
                        <tr>
                            @for ($x = $x_pos - $cartographer; $x <= $x_pos + $cartographer; $x++)
                                @if ($i < count($visible_cells) && $x == $visible_cells[$i]['x_pos'] && $y == $visible_cells[$i]['y_pos'])
                                    <td style="background-color: {{ $visible_cells[$i]['background-color'] }};width: 50px;height: 50px;"><i class="fas {{ $visible_cells[$i]['class'] }}"></i></td>
                                    <?php $i++; ?>
                                @else
                                    <td style="background-color: white"><i class="fas fa-road" style="color: white"></i></td>
                                @endif
                            @endfor
                        </tr>
                    @endfor
                </table>
            </div>
        </div>
        <input id="_token" name="_token" type="hidden" value="{{csrf_token()}}">
    </body>
</html>