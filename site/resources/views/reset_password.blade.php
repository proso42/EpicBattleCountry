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
            <h2>@lang('login.reseting_passwd')</h2>
            <form method="POST" id="new_password_form" action="/set_password">
                <div id="err_password" class="col-lg-6 col-md-6 col-sm-8 col-8 signup-err-password" style="display: none">
                    <p>@lang('error.error_not_same_passwd')</p>
                </div>
                <input id="user_id" name="user_id" type="hidden" value="{{ $user_id }}"> 
                <!--<input id="password" name="password" class="signup-input" placeholder="Mot de passe *" type="password" pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[\da-zA-Z!-/:-@[-`{-~]{12,20}$" required>
                <br/>
                <input id="password2" name="password2" class="signup-input" placeholder="Confirmer mot de passe *" type="password" pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[\da-zA-Z!-/:-@[-`{-~]{12,20}$" required> -->
                <input id="password" name="password" class="signup-input" placeholder="@lang('login.passwd') *" type="password" required>
                <br/>
                <input id="password2" name="password2" class="signup-input" placeholder="@lang('login.confirm_passwd') *" type="password" required>
                {{csrf_field()}}
                <br/>
                <div id="spin" style="display: none;">
                    <img class="signin-spin" src="images/loader.gif">
                </div>
                <input id="reset-button" class="signup-button" style="margin-bottom: 15px;" type="submit" value="@lang('common.reset')">
            </form>
        </div>
        <script>
            var f = document.getElementById('new_password_form');
            f.addEventListener('submit', function(e)
            {
                e.preventDefault();
                var btn = document.getElementById('reset-button');
                btn.disabled = true;
                btn.style.display = 'none';
                var spin = document.getElementById('spin');
                spin.style.display = '';
                var password = document.getElementById("password").value
                var password2 = document.getElementById("password2").value
                if (password != password2)
                {
                    document.getElementById("err_password").style.display = 'block';
                    setTimeout(() =>{
                        document.getElementById("err_password").style.display = 'none';
                    }, 5000);
                    document.getElementById('reset-button').disabled = false;
                    return false;
                }
                else
                    document.getElementById('new_password_form').submit();
            });
        </script>
    </body>
</html>