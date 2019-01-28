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
            <h2>Connexion</h2>
            <div id="err_password" class="signin-err-connexion">
                <p>Mot de passe et/ou login/email incorrect</p>
            </div>
            <form id="signin_form">
                <input id="account" type="text" class="signin-input" placeholder="Login ou email" required>
                </br>
                <input id="password" type="password" class="signin-input" placeholder="Mot de passe" required>
                </br>
                <div id="connexion">
                    <input type="submit" class="signin-button" value="Se connecter">
                </div>
                <div id="spin" style="display: none;">
                    <img class="signin-spin" src="images/loader.gif">
                </div>
                <hr class="signin-footer"/>
                <div id="forgot" class="signin-forgot" onclick="hide_main()">
                    <p class="signin-forgot-link" style="width:132px;">Mot de passe oublié ?</p>
                </div>
                <div id="link_no_account" class="signin-forgot">
                    <a href="signup.html" class="signin-forgot-link">Pas encore de compte ?</a>
                </div>
                <div id="text_no_account" class="signin-forgot" style="display: none; width: 144px;">
                    <p class="signin-forgot-link">Pas encore de compte ?</p>
                </div>
            </form>
        </div>
        <div id="second" class="signin-rect-lg">
            <p style="margin-top: 10px; margin-left: 5px;margin-right: 5px;">Nous avons envoyé un lien de réinitialisation de mot de passe à ton adresse mail.</p>
            <button class="signin-button" style="margin-top: 10px;margin-bottom: 10px;" onclick="hide_second()">Ok</button>
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
            $('#signin_form').submit(function(e) {
                e.preventDefault();
                console.log("On intercepte");
                document.getElementById("forgot").onclick = ''
                document.getElementById("link_no_account").style.display = "none"
                document.getElementById("text_no_account").style.display = ""
                document.getElementById("connexion").style.display = "none"
                document.getElementById("spin").style.display = ""
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