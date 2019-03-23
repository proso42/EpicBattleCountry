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
                @if($allowed == 0)
                    <p>Vous devez construire uen Forge avant de pouvoir l'utiliser !</p>
                @else
                    @foreach ($allowed_items as $item)
                        <div id="id_{{ $item_name }}">
                            <span>{{ $item['name'] }}</span><input type="text" class="signup-input">
                            <table>
                                <tr>
                                    @if ($item['food_required'] > 0)
                                        <td>
                                            <img src="images/food.png"> x{{ $item['food_required'] }}
                                        </td>
                                    @endif
                                    @if ($item['wood_required'] > 0)
                                        <td>
                                            <img src="images/wood.png"> x{{ $item['wood_required'] }}
                                        </td>
                                    @endif
                                    @if ($item['rock_required'] > 0)
                                        <td>
                                            <img src="images/rock.png"> x{{ $item['rock_required'] }}
                                        </td>
                                    @endif
                                    @if ($item['steel_required'] > 0)
                                        <td>
                                            <img src="images/steel.png"> x{{ $item['steel_required'] }}
                                        </td>
                                    @endif
                                    @if ($item['gold_required'] > 0)
                                        <td>
                                            <img src="images/gold.png"> x{{ $item['gold_required'] }}
                                        </td>
                                    @endif
                                    <td>
                                        <i class="fas fa-clock icon"></i>{{ $item['duration'] }}
                                    </td>
                                </tr>
                            </table>
                            <div class="signup-button">Produires</div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </body>
</html>