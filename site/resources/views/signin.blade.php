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
        <div id="main" class="signin-rect">
            <h2>@lang('signin.connection')</h2>
            <div id="connection_failed" class="signin-err-connexion" style="display: none;">
                <p>@lang('signin.err_connection')</p>
            </div>
            <div id="email_not_validated" class="signin-err-connexion" style="display: none;">
                <p>@lang('signin.email_not_validated')</p>
            </div>
            <form method="POST" action="/login" id="signin_form">
                <input id="account" type="text" class="signin-input" placeholder="@lang('signin.login_or_email')" required>
                <br/>
                <input id="password" type="password" class="signin-input" placeholder="@lang('login.passwd')" required>
                <br/>
                <div id="connexion">
                    <input type="submit" class="signin-button" value="@lang('signin.log_in')">
                </div>
                <div id="spin" style="display: none;">
                    <img class="signin-spin" src="images/loader.gif">
                </div>
                <hr class="signin-footer"/>
                <div id="forgot" class="signin-forgot">
                    <a href="/forgot_password" class="signin-forgot-link">@lang('signin.forgot_passwd')</a>
                </div>
                <div id="link_no_account" class="signin-forgot">
                    <a href="/signup" class="signin-forgot-link">@lang('signin.no_account')</a>
                </div>
                {{csrf_field()}}
            </form>
        </div>
        <div id="second" class="signin-rect-lg">
            <p style="margin-top: 10px; margin-left: 5px;margin-right: 5px;">@lang('signin.link_reset_email')</p>
            <button class="signin-button" style="margin-top: 10px;margin-bottom: 10px;" onclick="hide_second()">@lang('common.ok')</button>
        </div>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <script>
            let ua = navigator.userAgent;
            if (ua.indexOf("Firefox") >= 0)
            {
                document.getElementById("account").style.marginLeft = "auto";
                document.getElementById("account").style.marginRight = "auto";
                document.getElementById("password").style.marginLeft = "auto";
                document.getElementById("password").style.marginRight = "auto";
                document.getElementById("connexion").style.marginLeft = "auto";
                document.getElementById("connexion").style.marginRight = "auto";
            }
            var f = document.getElementById('signin_form');
            f.addEventListener('submit', function(e){
                e.preventDefault();
                var btn = document.getElementById('connexion');
                btn.disabled = true;
                btn.style.display = 'none';
                var spin = document.getElementById('spin');
                spin.style.display = '';
                var login = document.getElementById('account').value;
                var passwd = document.getElementById('password').value;
                var xhr = new XMLHttpRequest()
                xhr.open('GET', 'http://www.epicbattlecorp.fr/try_to_login?account=' + login + '&password=' + passwd)
                xhr.onreadystatechange =  function(){
                    if (xhr.readyState === 4 && xhr.status === 200)
                    {
                        if (xhr.responseText > 0)
                        {
                            btn.disabled = false;
                            btn.style.display = '';
                            spin.style.display = 'none';
                        }
                        if (xhr.responseText == 1)
                        {
                            console.log('erreur 1');
                            document.getElementById('connection_failed').style.display = '';
                            setTimeout(() =>{
                                document.getElementById("connection_failed").style.display = 'none';
                            }, 5000);
                            return false;
                        }
                        else if (xhr.responseText == 2)
                        {
                            console.log('erreur 2');
                            document.getElementById('email_not_validated').style.display = '';
                            setTimeout(() =>{
                                document.getElementById("email_not_validated").style.display = 'none';
                            }, 5000);
                            return false;
                        }
                        else
                        {
                            console.log('Good');
                            document.getElementById('signin_form').submit();
                        }
                    }
                }
                xhr.send()
            });
            function hide_main()
            {
                document.getElementById("main").style.display = "none";
                document.getElementById("second").style.display = "block";
            }
            function hide_second()
            {
                document.getElementById("second").style.display = "none";
                document.getElementById("main").style.display = "block";
            }
        </script>
    </body>
</html>