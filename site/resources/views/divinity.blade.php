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
                <div class="row">
                    <div id="blessing-tab" class="col-lg-3 col-md-3 col-sm-4 col-4 generique-tab" onclick="switchTab('blessing')">
                        @lang('divinity.blessing')
                    </div>
                    <div id="disaster-tab" class="col-lg-3 col-md-3 col-sm-4 col-4 generique-tab" onclick="switchTab('disaster')">
                        @lang('divinity.disaster')
                    </div>
                </div>
                <div id="blessing-panel" class="row" style="margin-top: 30px;display:none">
                    <p>blessing</p>
                </div>
                <div id="disaster-panel" class="row" style="margin-top: 30px;display:none">
                @if ($disaster_cool_down != null)
                    <div>
                        <span>@lang('divinity.next_disaster') : </span><span id="disaster_count" duration="{{ $disaster_cool_down }}"></span>
                    </div>
                @else
                    @foreach ($allowed_disaster as $disaster)
                        <div class="divinity-block">
                            <div class="divinity-name">{{ $disaster["name"] }}</div> 
                            <img class="divinity" style="width:250px;height: 250px;" src="{{ $disaster['illustration'] }}">
                            <div id="disaster{{ $disaster['id'] }}" name="{{ $disaster['name'] }}" @if ($disaster['faith_cost'] > $faith) class="divinity-button-impossible" @else class="divinity-button" onclick="launch_disaster('{{ $disaster['id'] }}')"@endif>                                
                                <span>@lang('common.trigger') <i class="fas fa-praying-hands"></i></span>
                                <div id="disaster_cost{{ $disaster['id'] }}" class="disaster-res-needed">
                                    <ul>
                                        <li>@lang('divinity.faith') : {{ $disaster['faith_cost'] }} <i @if ($disaster['faith_cost'] > $faith) class="fas fa-times icon" @else class="fas fa-check icon" @endif></i></li>
                                        <li>@lang('common.cool_down') : {{ $disaster['cool_down'] }} <i class="fas fa-clock"></i></li>
                                    </ul>
                                </div>                        
                            </div>
                        </div>
                    @endforeach
                @endif
                </div>
                <hr class="signin-footer">
                <div style="text-align: left">
                    <span><i class="fas fa-praying-hands"></i> @lang('divinity.available_faith') : </span><span>{{ $faith }}</span>
                </div>
                <input id="_token" name="_token" type="hidden" value="{{csrf_token()}}">
                <div id="fat" style="display: none" divinity_active_tab="{{ $divinity_active_tab }}" ></div>
            </div>
        </div>
        <script src="/js/divinity.js"></script>
    </body>
</html>