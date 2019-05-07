var activeTab = document.getElementById("fat").getAttribute("divinity_active_tab") + "-tab";
var activePanel = document.getElementById("fat").getAttribute("divinity_active_tab") + "-panel";

function switchTab(activeId)
{
    if (activeId + "-tab" === activeTab)
    {
        console.log('inactif');
        return ;
    }
    else
    {
        document.getElementById(activeTab).className = "col-lg-3 col-md-3 col-sm-3 col-3 generique-tab";
        document.getElementById(activeId + "-tab").className = "col-lg-3 col-md-3 col-sm-3 col-3 generique-tab-active";
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