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

    tinyMCE.activeEditor.windowManager.open({
        file : "browser.php?" + "type=" + type,
        title : "Browser",
        width : winWidth,  // Your dimensions may differ - toy around with them!
        height : winHeight,
        resizable : "yes",
        inline : "yes",
        close_previous : "no"
    }, {
        window : win,
        input : field_name
    });
    return false;
  }
