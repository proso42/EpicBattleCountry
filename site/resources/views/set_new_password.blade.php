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
        <div class="center-rect" style="margin-top: 270px">
            <h2>@lang('login.passwd_update')</h2>
            <div id="password_updated" class="settings-update-success" style="display: none">
                <p>@lang('login.passwd_changed')</p>
                <button class="return-button" onclick="window.location.href='/settings'">@lang('common.return')</button>
            </div>
            <form method="POST" id="new_password_form">
                <div id="err_password" class="col-lg-6 offset-lg-3 col-md-6 offset-md-3 col-sm-8 offset-sm-2 col-8 offset-2 signup-err-password" style="display: none">
                    <p>@lang('error.error_not_same_passwd')</p>
                </div>
                <div id="err_current_password" class="col-lg-6 offset-lg-3 col-md-6 offset-md-3 col-sm-8 offset-sm-2 col-8 offset-2 signup-err-password" style="display: none">
                    <p>@lang('error.error_bad_current_passwd')</p>
                </div>
                <div id="err_same_password" class="col-lg-6 offset-lg-3 col-md-6 offset-md-3 col-sm-8 offset-sm-2 col-8 offset-2 signup-err-password" style="display: none">
                    <p>@lang('error.error_same_current_and_new_passwd')</p>
                </div>
                <!--<input id="password" name="password" class="signup-input" placeholder="Mot de passe *" type="password" pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[\da-zA-Z!-/:-@[-`{-~]{12,20}$" required>
                <br/>
                <input id="password2" name="password2" class="signup-input" placeholder="Confirmer mot de passe *" type="password" pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[\da-zA-Z!-/:-@[-`{-~]{12,20}$" required> -->
                <input id="current_password" name="current_password" class="set-new-password-input" placeholder="@lang('login.current_passwd') *" type="password" required>
                <br/>
                <input id="confirm_new_password" name="confirm_new_password" class="set-new-password-input" placeholder="@lang('login.confirm_new_passwd') *" type="password" required>
                <input id="_token" name="_token" type="hidden" value="{{ $csrf_token_password }}">
                <br/>
                <div id="spin" style="display: none;">
                    <img class="signin-spin" src="images/loader.gif">
                </div>
                <input id="update-button" class="set-new-password-button" style="margin-bottom: 15px;" type="submit" value="@lang('common.update')">
            </form>
        </div>
        <script>
            var f = document.getElementById('new_password_form');
            f.addEventListener('submit', function(e)
            {
                e.preventDefault();
                var btn = document.getElementById('update-button');
                var _token = document.getElementById('_token').value;
                btn.disabled = true;
                btn.style.display = 'none';
                var spin = document.getElementById('spin');
                spin.style.display = '';
                var user_current_password = document.getElementById("current_password").value
                var confirm_new_password = document.getElementById("confirm_new_password").value
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'http://www.epicbattlecorp.fr/update_password');
                xhr.onreadystatechange =  function(){
                    console.log('onreadystatechange')
                    if (xhr.readyState === 4 && xhr.status === 200)
                    {
                        btn.disabled = false;
                        btn.style.display = '';
                        spin.style.display = 'none';
                        if (xhr.responseText == 1)
                        {
                            document.getElementById('err_password').style.display = '';
                            setTimeout(() =>{
                                document.getElementById("err_password").style.display = 'none';
                            }, 5000);
                            return false;
                        }
                        else if (xhr.responseText == 2)
                        {
                            document.getElementById('err_current_password').style.display = '';
                            setTimeout(() =>{
                                document.getElementById("err_current_password").style.display = 'none';
                            }, 5000);
                            return false;
                        }
                        else if (xhr.responseText == 3)
                        {
                            document.getElementById('err_same_password').style.display = '';
                            setTimeout(() =>{
                                document.getElementById("err_same_password").style.display = 'none';
                            }, 5000);
                            return false;
                        }
                        else
                        {
                            document.getElementById('password_updated').style.display = '';
                            document.getElementById('new_password_form').remove();
                            return true;
                        }
                    }
                }
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.send('user_current_password=' + user_current_password + '&confirm_new_password=' + confirm_new_password + '&_token=' + _token);
            });
        </script>
    </body>
</html>