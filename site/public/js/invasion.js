function hide_img(side)
{
    if (side == "left")
        document.getElementsByClassName("invasion-left-overlay").style.display = "";
    else if (side == "right")
        document.getElementsByClassName("invasion-right-overlay").style.display = "";
    else
        return ;
}