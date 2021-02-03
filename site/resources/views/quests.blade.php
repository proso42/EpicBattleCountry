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
                @if ($quests === null)
                    <h3>@lang('quests.no_quest')</h3>
                @else
                    <h1 style="margin-top: 25px">@lang('common.quests')</h1>
                    @foreach ($quests as $quest)
                        <div class="quest-slot">
                            <div style="margin-bottom: 25px"><i class="fas fa-dungeon"></i>
                            <span>Quete de donjon</span>
                            <input  type="button" class="quest-button" value="Reprendre"></div>
                        </div>
                    @endforeach
                @endif
            </div>
        <script>
        </script>
    </body>
</html>