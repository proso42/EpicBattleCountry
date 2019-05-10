setTimeout(() =>{
    let body_height = document.body.scrollHeight + 20;
    let win_height = window.innerHeight;
    if (body_height > win_height)
        document.getElementById("overlay").style.height = body_height + "px";
    else
        document.getElementById("overlay").style.height = win_height + "px";
}, 1000);

function move_map(x_offset, y_offset)
{
    window.location.href = '/map?x_offset=' + x_offset + '&y_offset=' + y_offset;
}

function display_cell_info(id)
{
    let cell = document.getElementById(id);
    let icon = document.getElementById(id + "_icon").className + " large-icon";
    document.getElementById("cell_info_icon").className = icon;
    let x_pos = cell.getAttribute("x_pos");
    let y_pos = cell.getAttribute("y_pos");
    document.getElementById("cell_coord").textContent = x_pos + "/" + y_pos;
    let format_type = cell.getAttribute("format_type");
    let type = cell.getAttribute("type");
    document.getElementById("cell_type").textContent = type;
    if (format_type == "city" || format_type == "capital")
    {
        let name = cell.getAttribute("name");
        let diplomatie = cell.getAttribute("diplomatie");
        let race = cell.getAttribute("owner_race");
        document.getElementById("cell_diplomatie").textContent = diplomatie;
        document.getElementById("city_name").textContent = name;
        document.getElementById("cell_owner_race").textContent = race;
        document.getElementById("cell_diplomatie").style.display = "";
        document.getElementById("city_name").style.display = "";
        document.getElementById("cell_owner_race").style.display = "";
    }
    else
    {
        document.getElementById("city_name").style.display = "none";
        document.getElementById("cell_owner_race").style.display = "none";
        document.getElementById("cell_diplomatie").style.display = "none";
    }
    document.getElementById("overlay").style.display = "";
    document.getElementById("cell_info").style.display = "";
}

function hide_cell_info()
{
    document.getElementById("overlay").style.display = "none";
    document.getElementById("cell_info").style.display = "none";
}