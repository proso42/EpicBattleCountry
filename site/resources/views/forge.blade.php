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
                        <div id="id_{{ $item['name'] }}" class="row">
                            <span class="col-lg-2 col-md-2 col-sm-2 col-2">{{ $item['name'] }}</span><input type="text" placeholder="quantité" class="col-lg-2 col-md-2 col-sm-2 col-2 forge-input">
                            <table class="col-lg-6 col-md-6 col-sm-6 col-6 forge-table-res">
                                <tr>
                                    @if ($item['food_required'] > 0)
                                        <td>
                                            <img class="forge-image" src="images/food.png"> x{{ $item['food_required'] }}
                                        </td>
                                    @endif
                                    @if ($item['wood_required'] > 0)
                                        <td>
                                            <img class="forge-image" src="images/wood.png"> x{{ $item['wood_required'] }}
                                        </td>
                                    @endif
                                    @if ($item['rock_required'] > 0)
                                        <td>
                                            <img class="forge-image" src="images/rock.png"> x{{ $item['rock_required'] }}
                                        </td>
                                    @endif
                                    @if ($item['steel_required'] > 0)
                                        <td>
                                            <img class="forge-image"src="images/steel.png"> x{{ $item['steel_required'] }}
                                        </td>
                                    @endif
                                    @if ($item['gold_required'] > 0)
                                        <td>
                                            <img class="forge-image" src="images/gold.png"> x{{ $item['gold_required'] }}
                                        </td>
                                    @endif
                                    <td>
                                        <i class="fas fa-clock icon"></i>{{ $item['duration'] }}
                                    </td>
                                </tr>
                            </table>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-2 forge-button">Produire</div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </body>
</html>