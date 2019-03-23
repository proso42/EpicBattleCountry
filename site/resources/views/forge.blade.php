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
                    <div id="items_list">
                    @foreach ($allowed_items as $item)
                        <div id="id_{{ $item['name'] }}" class="row" style="align-items: baseline;line-height: 31px;">
                            <span class="forge-item offset-lg-2 offset-md-2 offset-sm-2 offset-2 col-lg-2 col-md-2 col-sm-2 col-2" style="text-align:center">{{ $item['name'] }}</span>
                            <input id="input_{{ $item['name'] }}" type="text" placeholder="Quantité" class="forge-input col-lg-2 col-md-2 col-sm-2 col-2">
                            <div class="forge-ressources col-lg-2 col-md-2 col-sm-2 col-2">
                                @if ($item['food_required'] > 0)
                                    <img class="forge-image" src="images/food.png"> x{{ $item['food_required'] }}
                                @endif
                                @if ($item['wood_required'] > 0)
                                    <img class="forge-image" src="images/wood.png"> x{{ $item['wood_required'] }}
                                @endif
                                @if ($item['rock_required'] > 0)
                                    <img class="forge-image" src="images/rock.png"> x{{ $item['rock_required'] }}
                                @endif
                                @if ($item['steel_required'] > 0)
                                    <img class="forge-image" src="images/steel.png"> x{{ $item['steel_required'] }}
                                @endif
                                @if ($item['gold_required'] > 0)
                                    <img class="forge-image" src="images/gold.png"> x{{ $item['gold_required'] }}
                                @endif
                                <i class="fas fa-clock"></i> {{ $item['duration'] }}
                            </div>
                            <input onclick="craft('{{ $item['name'] }}')" type="button" class="forge-button col-lg-2 col-md-2 col-sm-2 col-2" value="Produire">
                        </div>
                    @endforeach
                    </div>
                    <div id="confirm_win">
                    </div>
                @endif
            </div>
        </div>
        <input id="_token" name="_token" type="hidden" value="{{csrf_token()}}">
        <script>
            function craft(name)
            {
                /*document.getElementById('items_list').style.display = "none";
                document.getElementById('confirm_win').style.display = "";*/
                var _token = document.getElementById("_token").value;
                var quantity = document.getElementById("input_" + name).value;
                var name_format = name.replace(/\s/gi, "_");
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'http://www.epicbattlecorp.fr/calculate_price');
                xhr.onreadystatechange =  function()
                {
                    if (xhr.readyState === 4 && xhr.status === 200)
                    {
                        console.log(xhr.responseText);
                    }
                }
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.send('_token=' + _token + '&name=' + name_format + "&quantity=" + quantity);
            }
        </script>
    </body>
</html>