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
                        <div class="row" style="align-items: baseline;line-height: 31px;">
                            <div class="quest-logo offset-lg-3 offset-md-3 offset-sm-3 offset-3 col-lg-1 col-md-1 col-sm-1 col-1" style="text-align:center">
                                <i class="fas fa-dungeon"></i>
                            </div>
                            <div class="quest-type col-lg-2 col-md-2 col-sm-2 col-2">
                                <span>
                                    @if ($quest->type == 1) 
                                        @lang('quests.dunegon_quest') 
                                    @elseif ($quest->type == 2) 
                                        @lang('quests.spider_quest') 
                                    @elseif ($quest->type == 3)
                                        @lang('quests.dragon_quest')
                                    @else
                                        @lang('quests.heroic_quest')
                                    @endif
                                </span>
                                <div class="quest-details">
                                    <ul>
                                        <li>@lang('common.quest') #{{ $quest->id }}</li>
                                        <li>@lang('common.location') : {{ $quest->coord }}</li>
                                        <li>
                                            @lang('common.difficulty') : <i class="fas fa-skull"></i> @if($quest->difficulty >= 2) 
                                                <i class="fas fa-skull"></i> 
                                            @endif
                                            @if($quest->difficulty == 3) 
                                                <i class="fas fa-skull"></i> 
                                            @endif
                                        </li>
                                        <li>
                                            @lang('quests.life_remaining') : @if ($quest->life == 1) <i class="fas fa-heart icon-color-red"></i> 
                                            @elseif ($quest->life == 2)
                                                <i class="fas fa-heart icon-color-red"></i><i class="fas fa-heart icon-color-red"></i>
                                            @else
                                                <i class="fas fa-heart icon-color-red"></i><i class="fas fa-heart icon-color-red"></i><i class="fas fa-heart icon-color-red"></i>
                                            @endif
                                        </li>

                                    </ul>
                                </div>
                            </div>
                            <input type="button" class="quest-button col-lg-2 col-md-2 col-sm-2 col-2" value="@if($quest->user_position == -1) @lang('common.start') @else @lang('common.resume') @endif">
                        </div>
                    @endforeach
                @endif
            </div>
        <script>
        </script>
    </body>
</html>