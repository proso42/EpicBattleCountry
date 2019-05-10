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
        <div class="center-rect" style="margin-top: 270px">
            <h2>@lang('signin.forgot_passwd')</h2>
            <form method="POST" action="/send_reset_password_email" id="send_reset_password_email_form">
                <input id="email" name="email" type="text" class="signin-input" placeholder="@lang('common.email')" required>
                <div id="spin" style="display: none;">
                    <img class="signin-spin" src="images/loader.gif">
                </div>
                <div>
                    <input id="reset-button" type="submit" class="signin-button" value="@lang('common.confirm')">
                </div>
                <br/>
                {{csrf_field()}}            
            </form>
        </div>
        <script src="/js/forgot_password.js"></script>
    </body>
</html>