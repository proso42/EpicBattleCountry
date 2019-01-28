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
        <div class="center-rect">
            <h2>Inscription</h2>
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-2 col-2"></div>
                <div id="err_password" class="col-lg-6 col-md-6 col-sm-8 col-8 signup-err-password" style="display: none">
                    <p>Les deux mots de passe ne sont pas identiques !</p>
                </div>
                <div id="err_login" class="col-lg-6 col-md-6 col-sm-8 col-8 signup-err-password" style="display: none">
                    <p>Ce login est déjà utilisé !</p>
                </div>
                <div id="err_city" class="col-lg-6 col-md-6 col-sm-8 col-8 signup-err-password" style="display: none">
                    <p>Ce nom de cité est déjà utilisé</p>
                </div>
                <div id="err_email" class="col-lg-6 col-md-6 col-sm-8 col-8 signup-err-password" style="display: none">
                    <p>Cet email est déjà utilisé</p>
                </div>
                <div id="err_sponsor" class="col-lg-6 col-md-6 col-sm-8 col-8 signup-err-password" style="display: none">
                    <p>Ce parrain n'existe pas !</p>
                </div>
            </div>
            <form method="POST" id="signup_form" action="/register" onSubmit="return false">
                <input id="login" name="login" class="signup-input" placeholder="Login *" type="text" pattern="[a-zA-Z]{3,20}" required>
                </br>
                <input id="city" name="city" class="signup-input" placeholder="Cité de départ *" type="text" pattern="^(?=.*[a-zA-Z]{3})[-a-zA-Z ]{3,20}$" required>
                </br>
                <select id="race" name="race" class="signup-select-race" required>
                    <optgroup style="background-color: white" label="Races disponibles">
                            <option selected class="signup-option-race">Humain</option>
                            <option class="signup-option-race">Elfe</option>
                            <option class="signup-option-race">Orc</option>
                            <option class="signup-option-race">Nain</option>
                    </optgroup>
                </select>
                </br>
                <input id="email" name="email" class="signup-input" placeholder="Email *" type="email" required>
                </br>
                <input id="password" name="password" class="signup-input" placeholder="Mot de passe *" type="password" pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[\da-zA-Z!-/:-@[-`{-~]{12,20}$" required>
                </br>
                <input id="password2" name="password2" class="signup-input" placeholder="Confirmer mot de passe *" type="password" pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[\da-zA-Z!-/:-@[-`{-~]{12,20}$" required>
                </br>
                <input id="sponsor" name="sponsor" class="signup-input" placeholder="Parrain" type="text">
                </br>
                {{csrf_field()}}
                <div id="spin" style="display: none;">
                    <img class="signin-spin" src="images/loader.gif">
                </div>
                <input id="submit_btn" class="signup-button" type="submit" value="S'inscrire">      
            </form>
            <hr class="signup-footer"/>
            <div class="signup-conditions">
                <p style="font-size: 12px;">
                    Votre mot de passe doit être composé de 12 à 20 caractères non accentué, comprenant au moins une minuscule, une majuscule et un chiffre.
                    </br>
                    Votre login doit être composé de 3 à 20 lettres, minuscule et/ou majuscule.
                    </br>
                    Le nom de votre cité de départ doit être composé de 3 à 20 lettres, minuscule et/ou majuscule. Vous pouvez aussi utiliser des espaces et des tirets.
                </p>
            </div>
        </div>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <script>
            let ua = navigator.userAgent;
            if (ua.indexOf("Chrome") >= 0)
                document.getElementById("race").style.textAlignLast = "center";
            else if (ua.indexOf("Firefox") >= 0)
                document.getElementById("race").style.textAlign = "center";

            function check_form()
            {
                var btn = document.getElementById('submit_btn');
                btn.disabled = true;
                btn.style.display = 'none';
                var spin = document.getElementById('spin');
                spin.style.display = '';
                var password = document.getElementById("password").value
                var password2 = document.getElementById("password2").value
                var login = document.getElementById("login").value
                var city = document.getElementById("city").value
                var email = document.getElementById("email").value
                var sponsor = document.getElementById("sponsor").value
                var xhr = new XMLHttpRequest()
                xhr.open('GET', 'http://www.epicbattlecorp.fr/check_infos?login=' + login + '&city=' + city + '&email=' + email + '&sponsor=' + sponsor, true);
                xhr.onreadystatechange =  function(){
                    if (xhr.readyState === 4 && xhr.status === 200)
                    {
                        spin.style.display = 'none'
                        btn.style.display = ''
                        btn.disabled = false
                        console.log('Receive datas')
                        console.log('Value : ' + xhr.responseText)
                        if (xhr.responseText == 1)
                        {
                            console.log('Login')
                            document.getElementById('err_login').style.display = ''
                            setTimeout(() =>{
                                document.getElementById("err_login").style.display = 'none';
                                }, 5000);
                            return false;
                        }
                        else if (xhr.responseText == 2)
                        {
                            console.log('City')
                            document.getElementById('err_city').style.display = ''
                            setTimeout(() =>{
                                document.getElementById("err_city").style.display = 'none';
                                }, 5000);
                            return false;
                        }
                        else if (xhr.responseText == 3)
                        {
                            console.log('Email')
                            document.getElementById('err_email').style.display = ''
                            setTimeout(() =>{
                                document.getElementById("err_email").style.display = 'none';
                                }, 5000);
                            return false;
                        }
                        else if (xhr.responseText == 4)
                        {
                            console.log('Sponsor')
                            document.getElementById('err_sponsor').style.display = ''
                            setTimeout(() =>{
                                document.getElementById("err_sponsor").style.display = 'none';
                                }, 5000);
                            return false;
                        }
                        else if (xhr.responseText == 0 && password != password2)
                        {
                            console.log('Passwords')
                            document.getElementById("err_password").style.display = 'block';
                            setTimeout(() =>{
                                document.getElementById("err_password").style.display = 'none';
                            }, 5000);
                            document.getElementById('submit_btn').disabled = false;
                            return false;
                        }
                        else if (xhr.responseText == 404)
                        {
                            console.log('404 => Missing variable');
                            return false;
                        }
                        else
                        {
                            console.log('Good')
                            return true;
                        }
                    }
                };
                xhr.send();
                //return false;
            }

            function change_color()
            {
                document.getElementById("race").style.color = "black";
            }
        </script>
    </body>
</html>