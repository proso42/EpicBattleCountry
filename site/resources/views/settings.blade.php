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
        <div class="menu-top">
            <div class="row">
                <div class="col-lg-4 col-md-2 col-sm-2"></div>
                <div class="col-lg-1 col-md-2 col-sm-2 col-3">
                    <img style="margin-top: 15px;" src="images/swords.png">
                </div>
                <div class="col-lg-2 col-md-4 col-sm-4 col-6">
                    <h1 style="margin-top: 25px;">EpicBattle</h1>
                </div>
                <div class="col-lg-1 col-md-1 col-sm-1 col-1">
                    <img style="margin-top: 15px;" src="images/swords.png">
                </div>
            </div>
            <!--<div>
                    <div  class="res">
                        <img src="images/food.png">
                        <p style="margin-left: 5px;margin-top: 5px;margin-right: 10px;">Vivres : 0 / 100</p>
                    </div>
                    <div class="res">
                        <img src="images/wood.png">
                        <p style="margin-left: 5px;margin-top: 5px;margin-right: 10px;">Bois : 0 / 100</p>
                    </div>
                    <div class="res">
                        <img src="images/rock.png">
                        <p style="margin-left: 5px;margin-top: 5px;margin-right: 10px;">Pierre : 0 / 100</p>
                    </div>                        
                    <div class="res">
                        <img src="images/steel.png">
                        <p style="margin-left: 5px;margin-top: 5px;margin-right: 10px;">Métal : 0 / 100</p>
                    </div>
                    <div class="res">
                        <img src="images/gold.png">
                        <p style="margin-left: 5px;margin-top: 5px;margin-right: 10px;">Or : 0 / 100</p>
                    </div>
            </div>-->
        </div>
        <div class="row" style="margin-left:0; margin-right: 0;">
            <div class="col-lg-2 col-md-2" style="margin-top: 50px;">
                <div class="row menu-left">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-home icon"></i></div>
                    <div onclick="document.location.href='/home'" class="col-lg-3 col-md-3 col-sm-1 col-3">Acceuil</div>
                </div>
                <div class="row menu-left">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-hammer icon"></i></div>
                    <div class="col-lg-3 col-md-3 col-sm-10 col-10">Construction</div>
                </div>
                <div class="row menu-left">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fab fa-whmcs icon"></i></div>
                    <div class="col-lg-3 col-md-3 col-sm-10 col-10">Production</div>
                </div>
                <div class="row menu-left">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-flask icon"></i></div>
                    <div class="col-lg-3 col-md-3 col-sm-10 col-10">Technologie</div>
                </div>
                <div class="row menu-left">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-map-marked-alt icon"></i></div>
                    <div class="col-lg-3 col-md-3 col-sm-10 col-10">Exploration</div>
                </div>
                <div class="row menu-left">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-fist-raised icon"></i></div>
                    <div class="col-lg-3 col-md-3 col-sm-10 col-10">Invasion</div>
                </div>
                <div class="row menu-left">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-flag icon"></i></div>
                    <div class="col-lg-3 col-md-3 col-sm-10 col-10">Diplomatie</div>
                </div>
                <div class="row menu-left">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-balance-scale icon"></i></div>
                    <div class="col-lg-3 col-md-3 col-sm-10 col-10">Commerce</div>
                </div>
                <div class="row menu-left">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-shield-alt icon"></i></div>
                    <div class="col-lg-3 col-md-3 col-sm-10 col-10">Alliance</div>
                </div>
                <div class="row menu-left">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-globe-americas icon"></i></div>
                    <div class="col-lg-3 col-md-3 col-sm-10 col-10">Carte</div>
                </div>
                <div class="row menu-left">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-comment icon"></i></div>
                    <div class="col-lg-3 col-md-3 col-sm-10 col-10">Messages</div>
                </div>
                <div class="row menu-left">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-chart-line icon"></i></div>
                    <div class="col-lg-3 col-md-3 col-sm-10 col-10">Statistiques</div>
                </div>
                <div onclick="document.location.href='/settings'" class="row menu-left">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-user-circle icon"></i></div>
                    <div class="col-lg-3 col-md-3 col-sm-10 col-10">Profile</div>
                </div>
                <div class="row menu-left">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-store-alt icon"></i></div>
                    <div class="col-lg-3 col-md-3 col-sm-10 col-10">Boutique</div>
                </div>
                <div onclick="document.location.href='/logout'" class="row menu-left last-case">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-1"><i class="fas fa-sign-out-alt icon"></i></div>
                    <div class="col-lg-3 col-md-3 col-sm-10 col-10">Déconnexion</div>
                </div>
            </div>
            <div class="col-lg-9 col-md-4 center-win">
                <h2>Profile</h2>
                <div id="err_login" class="signup-err-password" style="display: none">
                    <p>Ce login est déjà utilisé !</p>
                </div><div id="err_email" class="signup-err-password" style="display: none">
                    <p>Cet email est déjà utilisé !</p>
                </div>
                <div id="login_updated" class="settings-update-success" style="display: none">
                    <p>Votre login a été modifié avec succès ! </p>
                </div>
                <div id="email_updated" class="settings-update-success" style="display: none">
                    <p>Un email de confirmation a été envoyé à votre nouvelle adresse email !</p>
                </div>
                <div>
                    <span id="current_login" name="current_login" style="margin-right: 20px;">Psuedo : {{ $user_login }}</span><input id="new_login" name="new_login" class="settings-input" placeholder="Nouveau pseudo" type="text" pattern="[a-zA-Z]{3,20}"><input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}"> <img id="spin_login" class="settings-spin" style="display: none" src="images/loader.gif"><input onclick="reset_login()" id="change_login_button" class="settings-button" type="button" value="Modifier">
                </div>
                <div>
                    <span id="current_email" name="current_email" style="margin-right: 20px;">Email : {{ $user_email }}</span><input id="new_email" name="new_email" class="settings-input" placeholder="Nouvel email" type="email"><input id="_token2" name="_token2" type="hidden" value="{{ csrf_token() }}"> <img id="spin_email" class="settings-spin" style="display: none" src="images/loader.gif"><input onclick="reset_email()" id="change_email_button" class="settings-button" type="button" value="Modifier">
                </div>
            </div>
        </div>
        <script>
            function reset_login()
            {
                var btn = document.getElementById('change_login_button');
                btn.disabled = true;
                btn.style.display = 'none';
                var spin = document.getElementById('spin_login');
                spin.style.display = '';
                var new_login = document.getElementById('new_login').value;
                var _token = document.getElementById('_token').value;
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
                            document.getElementById('current_login').textContent = 'Pseudo : ' + new_login;
                            document.getElementById('login_updated').style.display = '';
                            setTimeout(() =>{
                                document.getElementById("login_updated").style.display = 'none';
                            }, 5000);
                            return true;
                        }
                    }
                }
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.send('new_login=' + new_login + '&_token=' + _token);
            }
            function reset_email()
            {
                var btn = document.getElementById('change_email_button');
                btn.disabled = true;
                btn.style.display = 'none';
                var spin = document.getElementById('spin_email');
                spin.style.display = '';
                var new_login = document.getElementById('new_email').value;
                var _token = document.getElementById('_token2').value;
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
                            document.getElementById('current_email').textContent = 'Pseudo : ' + new_login;
                            document.getElementById('email_updated').style.display = '';
                            setTimeout(() =>{
                                document.getElementById("email_updated").style.display = 'none';
                            }, 5000);
                            return true;
                        }
                    }
                }
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.send('new_email=' + new_login + '&_token=' + _token);
            }
        </script>
    </body>
</html>