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
        <div id="overlay" class="home-overlay" style="display: none">
        </div>
        <div id="confirm_give_up_win" class="quest-give-up-win" style="display: none">
            
            <h3 id="confirm_give_up_title">@lang('quests.confirm_give_up_title')</h3>
            <span id="confirm_give_up_text">@lang('quests.confirm_give_up_text')</span>
            <input onclick="confirm_give_up()" type="button" class="quest-button" style="width: 100px" value="@lang('common.confirm')">
            <input onclick="cancel_give_up()" type="button" class="quest-button-cancel" style="width: 100px" value="@lang('common.cancel')">
        </div>
            @include('default')
            <div class="offset-lg-0 offset-md-2 offset-sm-1 offset-1 col-lg-9 col-md-7 col-sm-10 col-10 center-win" style="margin-top: 50px; padding-right: 10px;">
                <div id="give_up_success" class="quest-input-success" style="display: none;">
                    <p>@lang('quests.give_up_success')</p>
                </div>
                <div id="quest_error" class="quest-input-success" style="display: none;">
                    <p>@lang('error.an_error_occured')</p>
                </div>
                <div id="quest_win" class="quest-win" style="display: none">
                    <h3 id="quest_win_title"></h3>
                    <img id="quest_img" class="quest-img" style="width:250px;height: 250px;" src="">
                    <div>
                        <input id="quest_choice_1" onclick="" type="button" class="quest-button-choice col-lg-2 col-md-2 col-sm-2 col-2" value="">
                        <input id="quest_choice_2" onclick="" type="button" class="quest-button-choice col-lg-2 col-md-2 col-sm-2 col-2" value="">
                        <input id="quest_choice_return" onclick="" type="button" class="quest-button-cancel col-lg-2 col-md-2 col-sm-2 col-2" value="@lang('common.return')">
                        <p id="quest_texte"></p>
                    </div>
                </div>
                @if ($quests === null)
                    <h3>@lang('quests.no_quest')</h3>
                @else
                    <h1 id="quest_title" style="margin-top: 25px">@lang('common.quests')</h1>
                    <div id="quests_list">
                        @foreach ($quests as $quest)
                            <div id="quest_id_{{ $quest->id }}" class="row" style="align-items: baseline;line-height: 31px;">
                                <div class="quest-logo offset-lg-2 offset-md-2 offset-sm-2 offset-2 col-lg-1 col-md-1 col-sm-1 col-1" style="text-align:center">
                                    <i class="fas fa-dungeon"></i>
                                </div>
                                <div class="quest-type col-lg-3 col-md-3 col-sm-3 col-3">
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
                                                @lang('common.difficulty') : @if($quest->difficulty == 1) 
                                                    <i class="fas fa-skull icon-color-black"></i> 
                                                @elseif($quest->difficulty == 2) 
                                                    <i class="fas fa-skull icon-color-black"></i><i class="fas fa-skull icon-color-black"></i>
                                                @else
                                                    <i class="fas fa-skull icon-color-black"></i><i class="fas fa-skull icon-color-black"></i><i class="fas fa-skull icon-color-black"></i>
                                                @endif
                                            </li>
                                            <li>
                                                @lang('quests.life_remaining') : @for ($i = 0; $i < 3; $i++)
                                                    @if ($quest->life <= $i)
                                                        <i class="fas fa-heart-broken icon-color-l-gray"></i>
                                                    @else
                                                        <i class="fas fa-heart icon-color-red"></i>
                                                    @endif
                                                @endfor
                                            </li>

                                        </ul>
                                    </div>
                                </div>
                                <input onclick="resume_quest({{ $quest->id }})" type="button" class="quest-button col-lg-2 col-md-2 col-sm-2 col-2" value="@if($quest->user_position == -1) @lang('common.start') @else @lang('common.resume') @endif">
                                <input onclick="give_up({{ $quest->id }})" type="button" class="quest-button-cancel col-lg-2 col-md-2 col-sm-2 col-2" value="@lang('common.give_up')">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        <input id="_token" name="_token" type="hidden" value="{{csrf_token()}}">
        <script src="/js/quests.js"></script>
    </body>
</html>