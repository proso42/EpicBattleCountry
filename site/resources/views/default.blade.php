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
                    <div  class="col-lg-12" title="Food : {{ $food }}">
                        <img src="images/food.png">
                        <span id="food" style="margin-left: 5px;margin-top: 7px;margin-right: 10px; @if ($food == $max_food) color:maroon @elseif ($food >= ($max_food / 10 * 9)) color:darkorange @endif">
                            {{ $food }}
                        </span>
                        <span id="compact_food" style="margin-left: 5px;margin-top: 7px;margin-right: 10px;@if ($food == $max_food) color:maroon @elseif ($food >= ($max_food / 10 * 9)) color:darkorange @endif; display: none">
                            {{ $compact_food }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2 col-2">
                <div class="row">
                    <div  class="col-lg-12" title="Wood : {{ $wood }}">
                        <img src="images/wood.png">
                        <span style="margin-left: 5px;margin-top: 7px;margin-right: 10px;@if ($wood == $max_wood) color:maroon @elseif ($wood >= ($max_wood / 10 * 9)) color:darkorange @endif">
                            {{ $wood }}
                        </span>
                        <span id="compact_wood" style="margin-left: 5px;margin-top: 7px;margin-right: 10px;@if ($wood == $max_wood) color:maroon @elseif ($wood >= ($max_wood / 10 * 9)) color:darkorange @endif; display: none">
                            {{ $compact_wood }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2 col-2">
                <div class="row">
                    <div  class="col-lg-12" title="Rock : {{ $rock }}">
                        <img src="images/rock.png">
                        <span style="margin-left: 5px;margin-top: 7px;margin-right: 10px;@if ($rock == $max_rock) color:maroon @elseif ($rock >= ($max_rock / 10 * 9)) color:darkorange @endif">
                            {{ $rock }}
                        </span>
                        <span id="compact_rock" style="margin-left: 5px;margin-top: 7px;margin-right: 10px;@if ($rock == $max_rock) color:maroon @elseif ($rock >= ($max_rock / 10 * 9)) color:darkorange @endif; display: none">
                            {{ $compact_rock }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2 col-2">
                <div class="row">
                    <div  class="col-lg-12" title="Steel : {{ $steel }}">
                        <img src="images/steel.png">
                        <span style="margin-left: 5px;margin-top: 7px;margin-right: 10px;@if ($steel == $max_steel) color:maroon @elseif ($steel >= ($max_steel / 10 * 9)) color:darkorange @endif">
                            {{ $steel }}
                        </span>
                        <span id="compact_steel" style="margin-left: 5px;margin-top: 7px;margin-right: 10px;@if ($steel == $max_steel) color:maroon @elseif ($steel >= ($max_steel / 10 * 9)) color:darkorange @endif; display: none">
                            {{ $compact_steel }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2 col-2">
                <div class="row">
                    <div  class="col-lg-12" title="Gold : {{ $gold }}">
                        <img src="images/gold.png">
                        <span style="margin-left: 5px;margin-top: 7px;margin-right: 10px;@if ($gold == $max_gold) color:maroon @elseif ($gold >= ($max_gold / 10 * 9)) color:darkorange @endif">
                            {{ $gold }}
                        </span>
                        <span id="compact_gold" style="margin-left: 5px;margin-top: 7px;margin-right: 10px;@if ($gold == $max_gold) color:maroon @elseif ($gold >= ($max_gold / 10 * 9)) color:darkorange @endif; display: none">
                            {{ $compact_gold }}
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
        <div class="row menu-left">
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
        <div class="row menu-left">
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
            <div class="col-lg-3 col-md-3 col-sm-3 col-3">@lang('default.messages')</div>
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
            <div onclick="document.location.href='/menu_god'" class="row menu-left last-case">
                <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-fingerprint icon"></i></div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-3">@lang('default.admin')</div>
            </div>
        @endif
        <div onclick="document.location.href='/logout'" class="row menu-left last-case">
            <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-sign-out-alt icon"></i></div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-3">@lang('default.logout')</div>
        </div>
    </div>