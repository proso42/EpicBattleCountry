var confirm_give_up_state = 0;
var save_quest_id = 0;

setTimeout(() =>{
    let body_height = document.body.scrollHeight + 20;
    let win_height = window.innerHeight;
    if (body_height > win_height)
        document.getElementById("overlay").style.height = body_height + "px";
    else
        document.getElementById("overlay").style.height = win_height + "px";
}, 1000);

function confirm_give_up()
{
    document.getElementById('overlay').style.display = "none";
    document.getElementById('confirm_give_up_win').style.display = "none";
    confirm_give_up_state = 1;
    give_up(save_quest_id);
}

function cancel_give_up()
{
    document.getElementById('overlay').style.display = "none";
    document.getElementById('confirm_give_up_win').style.display = "none";
    save_quest_id = 0;
}

function give_up(quest_id)
{
    window.scrollBy(0, ((window.innerHeight)*(-1)) - (document.body.scrollHeight + 20));
    if (confirm_give_up_state)
    {
        var _token = document.getElementById('_token').value;
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'http://www.epicbattlecorp.fr/give_up_quest');
        xhr.onreadystatechange =  function()
        {
            if (xhr.readyState === 4 && xhr.status === 200)
            {
                var infos = JSON.parse(xhr.responseText);
                if (infos.Result == "Error")
                {
                    console.log(infos.Reason);
                    document.getElementById("quest_error").style.display = "";
                    setTimeout(() =>{
                        document.getElementById("quest_error").style.display = "none";
                    }, 3000);
                }
                else
                {
                    document.getElementById("quest_id_" + quest_id).remove();
                    document.getElementById("give_up_success").style.display = "";
                    setTimeout(() =>{
                        document.getElementById("give_up_success").style.display = "none";
                    }, 3000);
                }
                confirm_give_up_state = 0;
                save_quest_id = 0;
            }
        }
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send('_token=' + _token + '&quest_id=' + quest_id);
    }
    else
    {
        document.getElementById('overlay').style.display = "";
        document.getElementById('confirm_give_up_win').style.display = "";
        save_quest_id = quest_id;
    }
}

function resume_quest(quest_id)
{
    var _token = document.getElementById('_token').value;
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'http://www.epicbattlecorp.fr/resume_quest');
    xhr.onreadystatechange =  function()
    {
        if (xhr.readyState === 4 && xhr.status === 200)
        {
            var infos = JSON.parse(xhr.responseText);
            if (infos.Result == "Error")
            {
                console.log(infos.Reason);
                document.getElementById("quest_error").style.display = "";
                setTimeout(() =>{
                    document.getElementById("quest_error").style.display = "none";
                }, 3000);
            }
            else
            {
                console.log(infos);
            }
        }
    }
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send('_token=' + _token + '&quest_id=' + quest_id);
}