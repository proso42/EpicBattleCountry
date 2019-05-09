var activeTab = document.getElementById("fat").getAttribute("divinity_active_tab") + "-tab";
var activePanel = document.getElementById("fat").getAttribute("divinity_active_tab") + "-panel";
document.getElementById(activeTab).className = "col-lg-3 col-md-3 col-sm-4 col-4 generique-tab-active";
document.getElementById(activePanel).style.display = '';

setTimeout(() =>{
    let body_height = document.body.scrollHeight + 20;
    let win_height = window.innerHeight;
    if (body_height > win_height)
        document.getElementById("overlay").style.height = body_height + "px";
    else
        document.getElementById("overlay").style.height = win_height + "px";
}, 1000);

function switchTab(activeId)
{
    if (activeId + "-tab" === activeTab)
    {
        console.log('inactif');
        return ;
    }
    else
    {
        document.getElementById(activeTab).className = "col-lg-3 col-md-3 col-sm-4 col-4 generique-tab";
        document.getElementById(activeId + "-tab").className = "col-lg-3 col-md-3 col-sm-4 col-4 generique-tab-active";
        document.getElementById(activePanel).style.display = "none";
        document.getElementById(activeId + "-panel").style.display = "";
        activeTab = activeId + "-tab";
        activePanel = activeId + "-panel";
        var _token = document.getElementById('_token').value;
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'http://www.epicbattlecorp.fr/set_active_divinity');
        xhr.onreadystatechange =  function()
        {
            if (xhr.readyState === 4 && xhr.status === 200)
            {
                console.log(xhr.responseText);
                return ;
            }
        }
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send('_token=' + _token + '&active_tab=' + activeId);
    }
}

function show_desc(id)
{
    document.getElementById("overlay").style.display = "";
    document.getElementById("block_desc").style.display = "";
    document.getElementById("block_desc_p").textContent = document.getElementById("block_desc").getAttribute("desc");
    document.getElementById("block_desc_title").textContent = document.getElementById("block_desc").getAttribute("name");
}

function ok()
{
    document.getElementById("overlay").style.display = "none";
    document.getElementById("block_desc").style.display = "none";
}