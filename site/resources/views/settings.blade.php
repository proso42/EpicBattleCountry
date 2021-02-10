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
        @include('default')
            <div class="col-lg-9 col-md-8 center-win">
                <h2>@lang('settings.profile')</h2>
                <div id="err_login" class="signup-err-password" style="display: none">
                    <p>@lang('error.login_already_taken')</p>
                </div>
                <div id="empty_login" class="signup-err-password" style="display: none">
                    <p>@lang('error.thx_fill_login')</p>
                </div>
                <div id="err_email" class="signup-err-password" style="display: none">
                    <p>@lang('error.email_already_taken')</p>
                </div>
                <div id="empty_email" class="signup-err-password" style="display: none">
                    <p>@lang('error.thx_fill_email')</p>
                </div>
                <div id="empty_mdp" class="signup-err-password" style="display: none">
                    <p>@lang('error.thx_fill_passwd')</p>
                </div>
                <div id="err_same_mdp" class="signup-err-password" style="display: none">
                    <p>@lang('error.new_passwd_not_be_same_current')</p>
                </div>
                <div id="login_updated" class="settings-update-success" style="display: none">
                    <p>@lang('settings.login_updated') </p>
                </div>
                <div id="email_updated" class="settings-update-success" style="display: none">
                    <p>@lang('settings.confirm_email_send')</p>
                </div>
                <div>
                    <!-- User Settings for Large Screen -->
                    <div class="row large-screen" style="align-items: baseline;">
                        <span class="col-lg-4 col-md-5" id="current-login-lg" name="current_login" title="{{ $complete_login }}" >@lang('common.login') : {{ $user_login }}</span>
                        <br class="md-br"/>
                        <input id="new-login-lg" name="new_login" class="settings-input col-lg-3 col-md-3" placeholder="@lang('settings.new_login')" type="text" pattern="[a-zA-Z]{3,20}">
                        <input id="_token-lg" name="_token" type="hidden" value="{{ $csrf_token_login }}">
                        <img id="spin-login-lg" class="settings-spin" style="display: none" src="images/loader.gif">
                        <input onclick="reset_login('lg')" id="change-login-button-lg" class="settings-button col-lg-3 col-md-3" type="button" value="@lang('common.update')">
                    </div>
                    <div class="row large-screen" style="align-items: baseline;line-height: 31px;">
                        <span class="col-lg-4 col-md-5" id="current_email-lg" name="current_email" title="{{ $complete_email }}">@lang('common.email') : {{ $user_email }}</span>
                        <input id="new-email-lg" name="new_email" class="settings-input col-lg-3 col-md-3" placeholder="@lang('settings.new_email')" type="text">
                        <input id="_token2-lg" name="_token2" type="hidden" value="{{ $csrf_token_email }}">
                        <img id="spin-email-lg" class="settings-spin" style="display: none" src="images/loader.gif">
                        <input onclick="reset_email('lg')" id="change-email-button-lg" class="settings-button col-lg-3 col-md-3" type="button" value="@lang('common.update')">
                    </div>
                    <div class="row large-screen" style="align-items: baseline;line-height: 31px;">
                        <span class="col-lg-4 col-md-5" id="current_mdp-lg" name="current_mdp">@lang('login.passwd') : xxxxxxxxxxxxx</span>
                        <input id="new-mdp-lg" name="new_mdp" class="settings-input col-lg-3 col-md-3" placeholder="@lang('settings.new_passwd')" type="password">
                        <input id="_token3-lg" name="_token3" type="hidden" value="{{ $csrf_token_password }}">
                        <img id="spin-mdp-lg" class="settings-spin" style="display: none" src="images/loader.gif">
                        <input onclick="reset_mdp('lg')" id="change-mdp-button-lg" class="settings-button col-lg-3 col-md-3" type="button" value="@lang('settings.update_passwd')">
                    </div>
                    <!-- User Settings for Medium Screen -->
                    <div class="medium-screen" style="align-items: baseline;">
                        <span id="current-login-md" name="current_login" id="current-login-md" title="{{ $complete_login }}">@lang('common.login') : {{ $user_login }}</span>
                        <br class="md-br"/>
                        <input id="new-login-md" name="new_login" class="settings-input" placeholder="@lang('settings.new_login')" type="text" pattern="[a-zA-Z]{3,20}">
                        <input id="_token-md" name="_token" type="hidden" value="{{ $csrf_token_login }}">
                        <img id="spin-login-md" class="settings-spin" style="display: none" src="images/loader.gif">
                        <input onclick="reset_login('md')" id="change-login-button-md" class="settings-button" type="button" value="@lang('common.update')">
                    </div>
                    <div class="medium-screen" style="align-items: baseline;line-height: 31px;">
                        <span id="current-email-md" name="current_email" title="{{ $complete_email }}">@lang('common.email') : {{ $user_email }}</span>
                        <br class="md-br"/>
                        <input id="new-email-md" name="new_email" class="settings-input" placeholder="@lang('settings.new_email')" type="text">
                        <input id="_token2-md" name="_token2" type="hidden" value="{{ $csrf_token_email }}">
                        <img id="spin-email-md" class="settings-spin" style="display: none" src="images/loader.gif">
                        <input onclick="reset_email('md')" id="change-email-button-md" class="settings-button" type="button" value="@lang('common.update')">
                    </div>
                    <div class="medium-screen" style="align-items: baseline;line-height: 31px;">
                        <span id="current_mdp-md" name="current_mdp">@lang('login.passwd') : xxxxxxxxxxxxx</span>
                        <input id="new-mdp-md" name="new_mdp" class="settings-input" placeholder="@lang('settings.new_passwd')" type="password">
                        <input id="_token3-md" name="_token3" type="hidden" value="{{ $csrf_token_password }}">
                        <img id="spin-mdp-md" class="settings-spin" style="display: none" src="images/loader.gif">
                        <input onclick="reset_mdp('md')" id="change-mdp-button-md" class="settings-button" type="button" value="@lang('settings.update_passwd')">
                    </div>
                    <!-- User Settings for Small Screen -->
                    <div class="small-screen" style="align-items: baseline;">
                        <span id="current-login-sm" name="current_login" title="{{ $complete_login }}">@lang('common.login') : {{ $user_login }}</span>
                        <br/>
                        <input id="new-login-sm" name="new_login" class="settings-input" placeholder="@lang('settings.new_login')" type="text" pattern="[a-zA-Z]{3,20}">
                        <br/>
                        <input id="_token-sm" name="_token" type="hidden" value="{{ $csrf_token_login }}">
                        <img id="spin-login-sm" class="settings-spin" style="display: none" src="images/loader.gif">
                        <input onclick="reset_login('sm')" id="change-login-button-sm" class="settings-button" type="button" value="@lang('common.update')">
                    </div>
                    <div class="small-screen" style="align-items: baseline;line-height: 31px;">
                        <span id="current-email-sm" name="current_email" title="{{ $complete_email }}">@lang('common.email') : {{ $user_email }}</span>
                        <br/>
                        <input id="new-email-sm" name="new_email" class="settings-input" placeholder="@lang('settings.new_email')" type="text">
                        <br/>
                        <input id="_token2-sm" name="_token2" type="hidden" value="{{ $csrf_token_email }}">
                        <img id="spin-email-sm" class="settings-spin" style="display: none" src="images/loader.gif">
                        <input onclick="reset_email('sm')" id="change-email-button-sm" class="settings-button" type="button" value="@lang('common.update')">
                    </div>
                    <div class="small-screen" style="align-items: baseline;line-height: 31px;">
                        <span id="current_mdp-sm" name="current_mdp">@lang('login.passwd') : xxxxxxxxxxxxx</span>
                        <input id="new-mdp-sm" name="new_mdp" class="settings-input" placeholder="@lang('settings.new_passwd')" type="password">
                        <input id="_token3-sm" name="_token3" type="hidden" value="{{ $csrf_token_password }}">
                        <img id="spin-mdp-sm" class="settings-spin" style="display: none" src="images/loader.gif">
                        <input onclick="reset_mdp('sm')" id="change-mdp-button-sm" class="settings-button" type="button" value="@lang('settings.update_passwd')">
                    </div>
                    <hr class="signin-footer"/>
                    <div>
                        <p>Premium : {{ $is_premium }}</p>
                        <p>@lang('common.race') : {{ $user_race }}</p>
                        <p>
                            @lang('settings.lang') : @if ($user_lang == 'fr') <img class="settings-country-flag" src="images/flag_fr.png"> @else <img class="settings-country-flag" src="images/flag_en.png"> @endif <i class="fas fa-sync-alt" style="cursor: pointer;margin-left: 20px" onclick="window.location.href='/lang/{{ $alt_lang }}'"></i>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function reset_login(id)
            {
                console.log('1');
                var new_login = document.getElementById('new-login-' + id).value;
                console.log('2');
                var csrf_token_login = document.getElementById('_token-' + id).value;
                console.log('3');
                if (new_login === '')
                {
                    console.log('4');
                    document.getElementById('empty_login').style.display = '';
                    setTimeout(() =>{
                        document.getElementById("empty_login").style.display = 'none';
                    }, 5000);
                    return false;
                }
                console.log('5');
                var btn = document.getElementById('change-login-button-' + id);
                console.log('6');
                var spin = document.getElementById('spin-login-' + id);
                console.log('7');
                spin.style.display = '';
                console.log('8');
                btn.disabled = true;
                console.log('9');
                btn.style.display = 'none';
                console.log('10');
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'http://www.epicbattlecorp.fr/reset_login');
                xhr.onreadystatechange =  function(){
                    console.log('onreadystatechange')
                    if (xhr.readyState === 4 && xhr.status === 200)
                    {
                        btn.disabled = false;
                        btn.style.display = '';
                        spin.style.display = 'none';
                        if (xhr.responseText == 1)
                        {
                            document.getElementById('err_login').style.display = '';
                            setTimeout(() =>{
                                document.getElementById("err_login").style.display = 'none';
                            }, 5000);
                            return false;
                        }
                        else
                        {
                            document.getElementById('current-login-' + id).textContent = 'Pseudo : ' + new_login;
                            document.getElementById('new-login-' + id).value = '';
                            document.getElementById('login_updated').style.display = '';
                            setTimeout(() =>{
                                document.getElementById("login_updated").style.display = 'none';
                            }, 5000);
                            return true;
                        }
                    }
                }
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.send('new_login=' + new_login + '&_token=' + csrf_token_login);
            }
            function reset_email(id2)
            {
                var new_email = document.getElementById('new-email-' + id2).value;
                var csrf_token_email = document.getElementById('_token2-' + id2).value;
                if (new_email === '')
                {
                    document.getElementById('empty_email').style.display = '';
                    setTimeout(() =>{
                        document.getElementById("empty_email").style.display = 'none';
                    }, 5000);
                    return false;
                }
                var btn = document.getElementById('change-email-button-' + id2);
                var spin = document.getElementById('spin-email-' + id2);
                spin.style.display = '';
                btn.disabled = true;
                btn.style.display = 'none';
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'http://www.epicbattlecorp.fr/send_email_reset_email');
                xhr.onreadystatechange =  function(){
                    console.log('onreadystatechange')
                    if (xhr.readyState === 4 && xhr.status === 200)
                    {
                        btn.disabled = false;
                        btn.style.display = '';
                        spin.style.display = 'none';
                        if (xhr.responseText == 1)
                        {
                            document.getElementById('err_email').style.display = '';
                            setTimeout(() =>{
                                document.getElementById("err_email").style.display = 'none';
                            }, 5000);
                            return false;
                        }
                        else
                        {
                            document.getElementById('email_updated').style.display = '';
                            setTimeout(() =>{
                                document.getElementById("email_updated").style.display = 'none';
                            }, 5000);
                            return true;
                        }
                    }
                }
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.send('new_email=' + new_email + '&_token=' + csrf_token_email);
            }
            function reset_mdp(id3)
            {
                var new_password = document.getElementById('new-mdp-' + id3).value;
                var csrf_token_password = document.getElementById('_token3-' + id3).value;
                if (new_password === '')
                {
                    document.getElementById('empty_mdp').style.display = '';
                    setTimeout(() =>{
                        document.getElementById("empty_mdp").style.display = 'none';
                    }, 5000);
                    return false;
                }
                var btn = document.getElementById('change-mdp-button-' + id3);
                var spin = document.getElementById('spin-mdp-' + id3);
                spin.style.display = '';
                btn.disabled = true;
                btn.style.display = 'none';
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'http://www.epicbattlecorp.fr/check_new_password');
                xhr.onreadystatechange =  function()
                {
                    console.log('onreadystatechange')
                    if (xhr.readyState === 4 && xhr.status === 200)
                    {
                        btn.disabled = false;
                        btn.style.display = '';
                        spin.style.display = 'none';
                        if (xhr.responseText == 1)
                        {
                            document.getElementById('err_same_mdp').style.display = '';
                            setTimeout(() =>{
                                document.getElementById("err_same_mdp").style.display = 'none';
                            }, 5000);
                            return false;
                        }
                        else
                        {
                            document.location.href= '/set_new_password?_token=' + document.getElementById('_token3-' + id3).value;;
                        }
                    }
                }
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.send('new_password=' + new_password + '&_token=' + csrf_token_password);
            }
        </script>
    </body>
</html>