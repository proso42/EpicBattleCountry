var activeTab = document.getElementById("fat").getAttribute("first_active_tab") + "-tab";
var activeMsg = document.getElementById("fat").getAttribute("first_active_tab");
document.getElementById(activeTab).className = "col-lg-3 col-md-3 col-sm-3 col-3 generique-tab-active";
document.getElementById(activeMsg).style.display = '';

function switchTab(activeId)
{
    if (activeId + "-tab" === activeTab)
    {
        console.log('inactif');
        return ;
    }
    else
        window.location.href= "http://www.epicbattlecorp.fr/messages?activeTab=" + activeId;
}

function unlock_user(login, id)
{
    var _token = document.getElementById("_token").value;
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'http://www.epicbattlecorp.fr/unlock_user');
    xhr.onreadystatechange =  function()
    {
        if (xhr.readyState === 4 && xhr.status === 200)
        {
            console.log('ok !');
            document.getElementById(id).remove();
        }
        else
            console.log('error unlocking user');
    }
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send('_token=' + _token + '&login=' + login);
}

function hide_show_msg(id, type)
{
    let eye = document.getElementById('eye_' + id);
    if (eye.className == "offset-lg-2 offset-md-2 offset-sm-2 offset-2 col-lg-1 col-md-1 col-sm-1 col-1 fas fa-eye")
    {
        //showing part
        document.getElementById('content_' + id).style.display = "";
        eye.className = "offset-lg-2 offset-md-2 offset-sm-2 offset-2 col-lg-1 col-md-1 col-sm-1 col-1 fas fa-eye-slash";
        icon_envelope = document.getElementById('seen_' + id);
        if (icon_envelope.className == "col-lg-1 col-md-1 col-sm-1 col-1 fas fa-envelope icon-color-red")
        {
            icon_envelope.className = "col-lg-1 col-md-1 col-sm-1 col-1 fas fa-envelope-open-text icon-color-yellow";
            var _token = document.getElementById("_token").value;
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'http://www.epicbattlecorp.fr/seen_msg');
            xhr.onreadystatechange =  function()
            {
                if (xhr.readyState === 4 && xhr.status === 200)
                {
                    console.log("msg_id : " + xhr.responseText);
                    decrease(type);
                }
            }
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send('_token=' + _token + '&msg_id=' + id);
        }
    }
    else
    {
        //hiding part
        document.getElementById('content_' + id).style.display = "none";
        eye.className = "offset-lg-2 offset-md-2 offset-sm-2 offset-2 col-lg-1 col-md-1 col-sm-1 col-1 fas fa-eye";
    }
}

function remove_msg(id, type)
{
    var _token = document.getElementById("_token").value;
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'http://www.epicbattlecorp.fr/delete_msg');
    xhr.onreadystatechange =  function()
    {
        if (xhr.readyState === 4 && xhr.status === 200)
        {
            if (xhr.responseText == 0)
            {
                console.log('msg removed !');
                let seen = document.getElementById("seen_" + id).className;
                if (seen.indexOf("icon-color-red") === -1)
                    document.getElementById(id).remove();
                else
                {
                    document.getElementById(id).remove();
                    decrease(type);
                }
            }
            else
                console.log("error");
            
        }
    }
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send('_token=' + _token + '&msg_id=' + id);
}

function decrease(type)
{
    if (type == "msg_sended")
        return ;
    nbSpan = document.getElementById('nb_' + type);
    nb = nbSpan.textContent;
    nbSpan.textContent = nb - 1;
    if (nb < 0)
        nb = 0;
    if (nb == 1)
    {
        nbSpan.style.color = "lightyellow";
        document.getElementById(type + '_alert').remove();
    }
}