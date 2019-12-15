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
                        <div id="notif-tab" class="col-lg-3 col-md-3 col-sm-3 col-3 generique-tab" onclick="switchTab('notif')">
                            @if ($notif_alert > 0)<i id="notif_alert" class="fas fa-exclamation-circle icon-color-red"></i> @endif @lang('msg.notif') (<span id="nb_notif" @if($notif_alert == 0) style="color: lightyellow" @else style="color: crimson" @endif>{{ $notif_alert }}</span>)
                        </div>
                        <div id="sended-tab" class="col-lg-3 col-md-3 col-sm-3 col-3 generique-tab" onclick="switchTab('sended')">
                            @lang('msg.msg_send')                        
                        </div>
                        <div id="received-tab" class="col-lg-3 col-md-3 col-sm-3 col-3 generique-tab" onclick="switchTab('received')">
                            @if ($msg_received_alert > 0)<i id="msg_received_alert" class="fas fa-exclamation-circle icon-color-red"></i> @endif @lang('msg.msg_received') (<span id="nb_msg_received" @if($msg_received_alert == 0) style="color: lightyellow" @else style="color: crimson" @endif>{{ $msg_received_alert }}</span>)                     
                        </div>
                        <div id="blocked-tab" class="col-lg-3 col-md-3 col-sm-3 col-3 generique-tab" onclick="switchTab('blocked')">
                            @lang('msg.players_blocked')       
                        </div>
                </div>
                <div id="notif" style="margin-top: 30px;display: none">
                    @if (count($notifications) > 0)
                        @foreach ($notifications as $notif)
                            <div id="{{ $notif['id'] }}" class="msg-line row">
                                <i id="seen_{{ $notif['id'] }}" @if ($notif['seen'] == 0) class="col-lg-1 col-md-1 col-sm-1 col-1 fas fa-envelope icon-color-red" @else class="col-lg-1 col-md-1 col-sm-1 col-1 fas fa-envelope-open-text icon-color-yellow" @endif></i>
                                <span class="col-lg-1 col-md-1 col-sm-1 col-1">{{ $notif['date'] }}</span>
                                <span class="offset-lg-3 offset-md-3 offset-sm-3 offset-3 col-lg-3 col-md-3 col-sm-3 col-3">{{ $notif['title'] }}</span>
                                <i onclick="hide_show_msg('{{ $notif['id'] }}', 'notif')" id="eye_{{ $notif['id'] }}" class="offset-lg-1 offset-md-1 offset-sm-1 offset-1 col-lg-1 col-md-1 col-sm-1 col-1 fas fa-eye" style="cursor: pointer; margin-right: 10px"></i>
                                <i onclick="remove_msg('{{ $notif['id'] }}', 'notif')" class="offset-lg-1 offset-md-1 offset-sm-1 offset-1 col-lg-1 col-md-1 col-sm-1 col-1 fas fa-times icon-color-red" style="cursor: pointer"></i>
                                <p id="content_{{ $notif['id'] }}" class="offset-lg-3 offset-md-3 offset-sm-3 offset-3 col-lg-6 col-md-6 col-sm-6 col-6" style="display:none">{{ $notif['content'] }}</p>
                            </div>
                            <br/>
                        @endforeach
                    @else
                        <p>@lang('msg.no_notif')</p>
                    @endif
                </div>
                <div id="sended" style="margin-top: 30px;display: none">
                    @if (count($msg_sended) > 0)
                        @foreach ($msg_sended as $msg)
                            <div id="{{ $msg['id'] }}" class="msg-line row">
                                <i class="col-lg-1 col-md-1 col-sm-1 col-1 fas fa-paper-plane"></i>
                                <span class="col-lg-1 col-md-1 col-sm-1 col-1">{{ $notif['date'] }}</span>
                                <span class="offset-lg-3 offset-md-3 offset-sm-3 offset-3 col-lg-3 col-md-3 col-sm-3 col-3">{{ $msg['title'] }}</span>
                                <i onclick="hide_show_msg('{{ $msg['id'] }}', 'msg_sended')" id="eye_{{ $msg['id'] }}" class="offset-lg-2 offset-md-2 offset-sm-2 offset-2 col-lg-1 col-md-1 col-sm-1 col-1 fas fa-eye" style="cursor: pointer"></i>
                                <i onclick="remove_msg('{{ $msg['id'] }}', 'msg_sended')" class="col-lg-1 col-md-1 col-sm-1 col-1 fas fa-times icon-color-red" style="cursor: pointer"></i>
                                <p id="content_{{ $msg['id'] }}" class="offset-lg-3 offset-md-3 offset-sm-3 offset-3 col-lg-6 col-md-6 col-sm-6 col-6" style="display:none">{{ $msg['content'] }}</p>
                            </div>
                        @endforeach
                    @else
                        <p>@lang('msg.no_msg_sended')</p>
                    @endif

                </div>
                <div id="received" style="margin-top: 30px;display: none">
                    @if (count($msg_received) > 0)
                        @foreach ($msg_received as $msg)
                            <div id="{{ $msg['id'] }}" class="msg-line row">
                                <i class="col-lg-1 col-md-1 col-sm-1 col-1 fas fa-paper-plane"></i>
                                <span class="col-lg-1 col-md-1 col-sm-1 col-1">{{ $notif['date'] }}</span>
                                <span class="offset-lg-3 offset-md-3 offset-sm-3 offset-3 col-lg-3 col-md-3 col-sm-3 col-3">{{ $msg['title'] }}</span>
                                <i onclick="hide_show_msg('{{ $msg['id'] }}', 'msg_received')" id="eye_{{ $msg['id'] }}" class="offset-lg-2 offset-md-2 offset-sm-2 offset-2 col-lg-1 col-md-1 col-sm-1 col-1 fas fa-eye" style="cursor: pointer"></i>
                                <i onclick="remove_msg('{{ $msg['id'] }}', 'msg_received')" class="col-lg-1 col-md-1 col-sm-1 col-1 fas fa-times icon-color-red" style="cursor: pointer"></i>
                                <p id="content_{{ $msg['id'] }}" class="offset-lg-3 offset-md-3 offset-sm-3 offset-3 col-lg-6 col-md-6 col-sm-6 col-6" style="display:none">{{ $msg['content'] }}</p>
                            </div>
                        @endforeach
                    @else
                        <p>@lang('msg.no_msg_received')</p>
                    @endif
                </div>
                <div id="blocked" style="margin-top: 30px;display: none">
                    @if (count($users_blocked) > 0)
                        <?php $i = 0;?>
                        @foreach ($users_blocked as $user)
                            <div id="user_locked_{{ $i}}" class="msg-line row">
                                <i class="col-lg-1 col-md-1 col-sm-1 col-1 fas fa-user"></i>
                                <span class="offset-lg-3 offset-md-3 offset-sm-3 offset-3 col-lg-3 col-md-3 col-sm-3 col-3">{{ $user['login'] }}</span>
                                <i onclick="unlock_user('{{ $user['login'] }}', 'user_locked_{{ $i}}')" class="offset-lg-1 offset-md-1 offset-sm-1 offset-1 col-lg-1 col-md-1 col-sm-1 col-1 fas fa-times icon-color-red" style="cursor: pointer"></i>
                            </div>
                            <?php $i++;?>
                        @endforeach
                    @else
                        <p>@lang('msg.no_player_blocked')</p>
                    @endif
                </div>
                
            </div>
        </div>
        <input id="_token" name="_token" type="hidden" value="{{csrf_token()}}">
        <div id="fat" style="display: none" first_active_tab="{{ $first_active_tab }}" ></div>
        <script src="/js/messages.js"></script>
    </body>
</html>