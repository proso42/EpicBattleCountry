console.log("oui");

function hid_img(side)
{
    console.log("hide_img");
    if (side == "left")
        document.getElementsByClassName("invasion-left-overlay").style.display = "";
    else if (side == "right")
        document.getElementsByClassName("invasion-right-overlay").style.display = "";
    else
        return ;
}