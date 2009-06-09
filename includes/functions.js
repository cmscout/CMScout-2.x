function popuphelp(what)
{
	var url = "index.php?page=help&ex=nomenu&helppage=" + what;
	window.open(url, 'Help', 'HEIGHT=480,resizable=yes,WIDTH=400,scrollbars=yes');
}

function fileBrowser (field_name, url, type, win) {

    //alert("Field_Name: " + field_name + "\nURL: " + url + "\nType: " + type + "\nWin: " + win); // debug/testing

    // newer writing style of the TinyMCE developers for tinyMCE.openWindow
    if (type == 'image')
    {
        winWidth = 600;
        winHeight = 500;
    }
    else
    {
        winWidth = 300;
        winHeight = 115;
    }

    tinyMCE.openWindow({
        file : "../../../browser.php?" + "type=" + type,
        title : "Browser",
        width : winWidth,  // Your dimensions may differ - toy around with them!
        height : winHeight,
        close_previous : "no"
    }, {
        window : win,
        input : field_name,
        resizable : "yes",
        inline : "yes",  // This parameter only has an effect if you use the inlinepopups plugin!
        editor_id : tinyMCE.getWindowArg("editor_id")
    });
    return false;
  }