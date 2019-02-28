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
            <h2>Modification de mot de passe</h2>
            <div id="password_updated" class="settings-update-success" style="display: none">
                <p>Votre mot de passe a été modifié avec succès !</p>
                <button class="return-button" onclick="window.location.href='/home'">Retour</button>
            </div>
            <form method="POST" id="new_password_form">
                <div id="err_password" class="col-lg-6 col-md-6 col-sm-8 col-8 signup-err-password" style="display: none">
                    <p>Les deux mots de passe ne sont pas identiques !</p>
                </div>
                <div id="err_current_password" class="col-lg-6 col-md-6 col-sm-8 col-8 signup-err-password" style="display: none">
                    <p>Le mot de passe actuel n'est pas bon !</p>
                </div>
                <div id="err_same_password" class="col-lg-6 col-md-6 col-sm-8 col-8 signup-err-password" style="display: none">
                    <p>Le nouveau mot de passe ne peut pas être le mot de passe actuel !</p>
                </div>
                <div id="err_7" class="col-lg-6 col-md-6 col-sm-8 col-8 signup-err-password" style="display: none">
                    <p>NULL</p>
                </div>
                <!--<input id="password" name="password" class="signup-input" placeholder="Mot de passe *" type="password" pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[\da-zA-Z!-/:-@[-`{-~]{12,20}$" required>
                <br/>
                <input id="password2" name="password2" class="signup-input" placeholder="Confirmer mot de passe *" type="password" pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[\da-zA-Z!-/:-@[-`{-~]{12,20}$" required> -->
                <input id="current_password" name="current_password" class="signup-input" placeholder="Mot de passe actuel *" type="password" required>
                <br/>
                <input id="confirm_new_password" name="confirm_new_password" class="signup-input" placeholder="Confirmer nouveau mot de passe *" type="password" required>
                <input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">
                <br/>
                <div id="spin" style="display: none;">
                    <img class="signin-spin" src="images/loader.gif">
                </div>
                <input id="update-button" class="signup-button" style="margin-bottom: 15px;" type="submit" value="Modifier">
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
                        else if (xhr.responseText == 7)
                        {
                            document.getElementById('err_7').style.display = '';
                            setTimeout(() =>{
                                document.getElementById("err_7").style.display = 'none';
                            }, 5000);
                            return false;
                        }
                        else if (xhr.responseText == 0)
                        {
                            document.getElementById('password_updated').style.display = '';
                            docuemt.getElementById('new_password_form').remove();
                            return true;
                        }
                        else
                        {
                            console.log('new_password en session => ' + xhr.responseText)
                        }
                    }
                }
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.send('current_password=' + user_current_password + 'confirm_new_password=' + confirm_new_password + '&_token=' + _token);
            });
        </script>
    </body>
</html>