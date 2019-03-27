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
                @if ($allowed == 0)
                    <p>Vous devez construire une caserne avant de pouvoir former des unitées !</p>
                @else
                    <div id="unit_list">
                        @foreach ($allowed_units as $unit)
                            <div id="id_{{ $unit['name'] }}" class="row" style="align-items: baseline;line-height: 31px;">
                                <div class="army-unit offset-lg-2 offset-md-2 offset-sm-2 offset-2 col-lg-2 col-md-2 col-sm-2 col-2" style="text-align:center">
                                    <span>{{ $unit['name'] }}</span>
                                    <div class="army-info-unit">
                                        <ul>
                                            <li>Life : {{ $unit['life'] }} <i class="fas fa-heartbeat"></i></li>
                                            <li>Speed : {{ $unit['speed'] }} <i class="fas fa-tachometer-alt"></i></li>
                                            <li>Power : {{ $unit['power'] }} <i class="fas fa-fist-raised"></i></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="army-ressources col-lg-2 col-md-2 col-sm-2 col-2">
                                Ressources Items
                                <div class="army-ressources-details">
                                    <ul>
                                        @if ($unit['food'] > 0)
                                            <li>Food : {{ $unit['food'] }}</li>
                                        @endif
                                        @if ($unit['wood'] > 0)
                                            <li>Wood : {{ $unit['wood'] }}</li>
                                        @endif
                                        @if ($unit['rock'] > 0)
                                            <li>Rock : {{ $unit['rock'] }}</li>
                                        @endif
                                        @if ($unit['steel'] > 0)
                                            <li>Steel : {{ $unit['steel'] }}</li>
                                        @endif
                                        @if ($unit['gold'] > 0)
                                            <li>Gold : {{ $unit['gold'] }}</li>
                                        @endif
                                        @if ($unit['items'] !== null)
                                            @foreach ($unit['items'] as $item)
                                                <li>{{ $item['name'] }}</li>
                                            @endforeach
                                        @endif
                                        @if ($unit['mount'] !== null)
                                            <li>{{ $unit['mount'] }}</li>
                                        @endif
                                        <li>Time : {{ $unit['duration'] }} <i class="fas fa-clock"></i></li>
                                    </ul>
                                </div>
                            </div>
                            <input type="button" class="army-button col-lg-2 col-md-2 col-sm-2 col-2" value="Entrainer">
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        <input id="_token" name="_token" type="hidden" value="{{csrf_token()}}">
    </body>
</html>