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
                <form id="change_login_form" method="POST" action="/reset_login">
                        <span style="margin-right: 20px;">Psuedo : {{ $user_login }}</span><input id="new_login" name="new_login" class="settings-input" placeholder="Nouveau pseudo" type="text" pattern="[a-zA-Z]{3,20}" required>{{csrf_field()}}<div id="spin_login" style="display: none;"><img class="signin-spin" src="images/loader.gif"></div><input id="change_login_button" class="settings-button" type="submit" value="Modifier">
                </form>
            </div>
        </div>
        <script>
            var f = document.getElementById('change_login_form');
            f.addEventListener('submit', function(e){
                e.preventDefault();
                var btn = document.getElementById('change_login_button');
                btn.disabled = true;
                btn.style.display = 'none';
                var spin = document.getElementById('spin_login');
                spin.style.display = '';
                var ret_form = document.getElementById('change_login_form').submit();
                console.log(ret_form);
            });
        </script>
    </body>
</html>