function hide_img(side)
{
    if (side == "left")
        document.getElementById("left_overlay").style.display = "";
    else if (side == "right")
        document.getElementById("right_overlay").style.display = "";
    else
        return ;
}

function show_img(side)
{
    if (side == "left")
        document.getElementById("left_overlay").style.display = "none";
    else if (side == "right")
        document.getElementById("right_overlay").style.display = "none";
    else
        return ;
}