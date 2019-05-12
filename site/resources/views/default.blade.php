<div class="menu-top">
    <div class="row">
        <div class="col-lg-1 col-md-1 col-sm-1 col-0"></div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-3">
            <img style="margin-top: 15px;" src="images/swords.png">
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-6">
            <h1 style="margin-top: 25px;">EpicBattle</h1>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-3">
            <img style="margin-top: 15px;" src="images/swords.png">
        </div>
    </div>
    <div style="margin-top: 25px;">
        <div class="row">
            <div class="offset-lg-1 offset-md-1 offset-sm-1 offset-1 col-lg-2 col-md-2 col-sm-2 col-2">
                <div class="row">
                    <div  class="col-lg-12" title="Food : {{ $util->food }}">
                        <img src="images/food.png">
                        <span id="food" style="margin-left: 5px;margin-top: 7px;margin-right: 10px; @if ($util->food == $util->max_food) color:maroon @elseif ($util->food >= ($util->max_food / 10 * 9)) color:darkorange @endif">
                            {{ $util->food }}
                        </span>
                        <span id="compact_food" style="margin-left: 5px;margin-top: 7px;margin-right: 10px;@if ($util->food == $util->max_food) color:maroon @elseif ($util->food >= ($util->max_food / 10 * 9)) color:darkorange @endif; display: none">
                            {{ $util->compact_food }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2 col-2">
                <div class="row">
                    <div  class="col-lg-12" title="Wood : {{ $util->wood }}">
                        <img src="images/wood.png">
                        <span id="wood" style="margin-left: 5px;margin-top: 7px;margin-right: 10px;@if ($util->wood == $util->max_wood) color:maroon @elseif ($util->wood >= ($util->max_wood / 10 * 9)) color:darkorange @endif">
                            {{ $util->wood }}
                        </span>
                        <span id="compact_wood" style="margin-left: 5px;margin-top: 7px;margin-right: 10px;@if ($util->wood == $util->max_wood) color:maroon @elseif ($util->wood >= ($util->max_wood / 10 * 9)) color:darkorange @endif; display: none">
                            {{ $util->compact_wood }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2 col-2">
                <div class="row">
                    <div  class="col-lg-12" title="Rock : {{ $util->rock }}">
                        <img src="images/rock.png">
                        <span id="rock" style="margin-left: 5px;margin-top: 7px;margin-right: 10px;@if ($util->rock == $util->max_rock) color:maroon @elseif ($util->rock >= ($util->max_rock / 10 * 9)) color:darkorange @endif">
                            {{ $util->rock }}
                        </span>
                        <span id="compact_rock" style="margin-left: 5px;margin-top: 7px;margin-right: 10px;@if ($util->rock == $util->max_rock) color:maroon @elseif ($util->rock >= ($util->max_rock / 10 * 9)) color:darkorange @endif; display: none">
                            {{ $util->compact_rock }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2 col-2">
                <div class="row">
                    <div  class="col-lg-12" title="Steel : {{ $util->steel }}">
                        <img src="images/steel.png">
                        <span id="steel" style="margin-left: 5px;margin-top: 7px;margin-right: 10px;@if ($util->steel == $util->max_steel) color:maroon @elseif ($util->steel >= ($util->max_steel / 10 * 9)) color:darkorange @endif">
                            {{ $util->steel }}
                        </span>
                        <span id="compact_steel" style="margin-left: 5px;margin-top: 7px;margin-right: 10px;@if ($util->steel == $util->max_steel) color:maroon @elseif ($util->steel >= ($util->max_steel / 10 * 9)) color:darkorange @endif; display: none">
                            {{ $util->compact_steel }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2 col-2">
                <div class="row">
                    <div  class="col-lg-12" title="Gold : {{ $util->gold }}">
                        <img src="images/gold.png">
                        <span id="gold" style="margin-left: 5px;margin-top: 7px;margin-right: 10px;@if ($util->gold == $util->max_gold) color:maroon @elseif ($util->gold >= ($util->max_gold / 10 * 9)) color:darkorange @endif">
                            {{ $util->gold }}
                        </span>
                        <span id="compact_gold" style="margin-left: 5px;margin-top: 7px;margin-right: 10px;@if ($util->gold == $util->max_gold) color:maroon @elseif ($util->gold >= ($util->max_gold / 10 * 9)) color:darkorange @endif; display: none">
                            {{ $util->compact_gold }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row" style="margin-left:0; margin-right: 0;">
    <div class="col-lg-2 col-md-2" style="margin-top: 50px;">
        <div onclick="document.location.href='/home'" class="row menu-left">
            <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-home icon"></i></div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-3">@lang('default.home')</div>
        </div>
        <div onclick="document.location.href='/divinity'" class="row menu-left">
            <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-praying-hands"></i></div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-3">@lang('default.god')</div>
        </div>
        <div onclick="document.location.href='/buildings'" class="row menu-left">
            <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-hammer icon"></i></div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-3">@lang('default.build')</div>
        </div>
        <div onclick="document.location.href='/techs'" class="row menu-left">
            <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-flask icon"></i></div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-3">@lang('default.tech')</div>
        </div>
        <div onclick="document.location.href='/forge'" class="row menu-left">
            <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-cog icon"></i></div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-3">@lang('default.forge')</div>
        </div>
        <div onclick="document.location.href='/exploration'" class="row menu-left">
            <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-map-marked-alt icon"></i></div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-3">@lang('default.scout')</div>
        </div>
        <div onclick="document.location.href='/army'" class="row menu-left">
            <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-chess-rook icon"></i></div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-3">@lang('default.army')</div>
        </div>
        <div onclick="document.location.href='/invasion'" class="row menu-left">
            <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-fist-raised icon"></i></div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-3">@lang('default.battle')</div>
        </div>
        <div class="row menu-left">
            <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-flag icon"></i></div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-3">@lang('default.diplomacy')</div>
        </div>
        <div class="row menu-left">
            <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-balance-scale icon"></i></div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-3">@lang('default.trade')</div>
        </div>
        <div class="row menu-left">
            <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-shield-alt icon"></i></div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-3">@lang('default.pacte')</div>
        </div>
        <div onclick="document.location.href='/map'" class="row menu-left">
            <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-globe-americas icon"></i></div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-3">@lang('default.map')</div>
        </div>
        <div onclick="document.location.href='/messages'" class="row menu-left">
            <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-comment icon"></i></div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-3"><span>@lang('default.messages') @if($util->msg_not_seen) <i class="fas fa-exclamation icon-color-red"></i>@endif</span></div>
        </div>
        <div class="row menu-left">
            <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-chart-line icon"></i></div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-3">@lang('default.score')</div>
        </div>
        <div onclick="document.location.href='/settings'" class="row menu-left">
            <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-user-circle icon"></i></div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-3">@lang('default.profile')</div>
        </div>
        <div class="row menu-left">
            <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-store-alt icon"></i></div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-3">@lang('default.shop')</div>
        </div>
        @if ($is_admin == 1)
            <div onclick="document.location.href='/menu_god'" class="row menu-left">
                <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-fingerprint icon"></i></div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-3">@lang('default.admin')</div>
            </div>
        @endif
        <div onclick="document.location.href='/logout'" class="row menu-left last-case">
            <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-sign-out-alt icon"></i></div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-3">@lang('default.logout')</div>
        </div>
    </div>