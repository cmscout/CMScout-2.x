<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>{$browserTitle} browser</title>
<link href="tiny_mce/themes/advanced/css/editor_popup.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="tiny_mce/tiny_mce_popup.js"></script>
{literal}
<style type="text/css">
.image
{
	font-size:1.5em;
	text-decoration:none;
  	color:#000000;
  	background-color:#feffff;
	padding:3px;
	margin:5px;
}
.image:hover
{
	text-decoration:none;
	border:2px outset #999;
	margin:3px;
}
  </style>
<script type="text/javascript">
var win = tinyMCEPopup.getWindowArg("window");
var input = tinyMCEPopup.getWindowArg("input");
var res = tinyMCEPopup.getWindowArg("resizable");
var inline = tinyMCEPopup.getWindowArg("inline");

{/literal}
{if $browserTitle == "Image"}
{literal}
var FileBrowserDialogue = {
    init : function () {
        // ensure window title in inlinepopups
        var obj; 
        var inlinepopups = false; 
        for (obj in tinyMCE.selectedInstance.plugins)
            if (tinyMCE.selectedInstance.plugins[obj] == "inlinepopups")
                inlinepopups = true;

        if (inlinepopups)
            tinyMCE.setWindowTitle(window, document.getElementsByTagName("title")[0].innerHTML);
    },
    insertImage : function (id) {
          {/literal}var URL = "getphoto.php?pic="+id;{literal}

        // insert information now
        win.document.getElementById(tinyMCEPopup.getWindowArg("input")).value = URL;

        // for image browsers: update image dimensions
        if (win.ImageDialog.getImageData) win.ImageDialog.getImageData();
        if (win.ImageDialog.showPreviewImage) win.ImageDialog.showPreviewImage(URL);

        // close popup window
        tinyMCEPopup.close();
    }
}

tinyMCEPopup.onInit.add(FileBrowserDialogue.init, FileBrowserDialogue);

/*function insertImage(id) 
{
  //call this function only after page has loaded
  //otherwise tinyMCEPopup.close will close the
  //"Insert/Edit Image" or "Insert/Edit Link" window instead

  {/literal}var URL = "getphoto.php?pic="+id;{literal}
  var win = tinyMCE.getWindowArg("window");

  // insert information now
  win.document.getElementById(tinyMCE.getWindowArg("input")).value = URL;

  // for image browsers: update image dimensions
  if (win.getImageData) win.getImageData();

  // close popup window
  tinyMCEPopup.close();
}*/
{/literal}
{else}
{literal}

var FileBrowserDialogue = {
    init : function () {
        // ensure window title in inlinepopups
        var obj; 
        var inlinepopups = false; 
        for (obj in tinyMCE.selectedInstance.plugins)
            if (tinyMCE.selectedInstance.plugins[obj] == "inlinepopups")
                inlinepopups = true;

        if (inlinepopups)
            tinyMCE.setWindowTitle(window, document.getElementsByTagName("title")[0].innerHTML);
    },
    insertItem : function () {
          var URL = document.theform.pickItem.value;

        // insert information now
        win.document.getElementById(tinyMCEPopup.getWindowArg("input")).value = URL;

        // close popup window
        tinyMCEPopup.close();
    }
}

tinyMCEPopup.onInit.add(FileBrowserDialogue.init, FileBrowserDialogue);

/*
function insertItem() {
  //call this function only after page has loaded
  //otherwise tinyMCEPopup.close will close the
  //"Insert/Edit Image" or "Insert/Edit Link" window instead

  var URL = document.theform.pickItem.value;
  var win = tinyMCE.getWindowArg("window");

  // insert information now
  win.document.getElementById(tinyMCE.getWindowArg("input")).value = URL;

  // close popup window
  tinyMCEPopup.close();
  }*/
{/literal}
{/if}
{literal}
/*myInitFunction = function () {
    // ensure window title in inlinepopups
    var obj; 
    var inlinepopups = false; 
    for (obj in tinyMCE.selectedInstance.plugins)
        if (tinyMCE.selectedInstance.plugins[obj] == "inlinepopups")
            inlinepopups = true;

    if (inlinepopups)
        tinyMCE.setWindowTitle(window, document.getElementsByTagName("title")[0].innerHTML);
}*/

{/literal}
{if $browserTitle == "Image"}
{literal}
function getAlbumData()
{
    var index = document.getElementById('pickAlbum').selectedIndex;
    var id = document.getElementById('pickAlbum').options[index].value;
    var albums = new Array();
    {/literal}
    {section name=album loop=$numalbum}
       {if $albums[album].numphotos}albums[{$albums[album].ID}] = "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"2\" align=\"center\"><tr style=\"height:136px\">{section name=photos loop=$albums[album].numphotos}<td width=\"136px\" style=\"vertical-align:middle;\"><div align=\"center\"><a href=\"#\" onclick=\"FileBrowserDialogue.insertImage('{$albums[album].photos[photos].ID}');\" title=\"Insert Photo\"><img class=\"image\" border=\"0\" src=\"thumbnail.php?pic={$albums[album].photos[photos].ID}\" alt=\"Insert Photo\" /></a></div></td>{if ($smarty.section.photos.iteration % 3 == 0)}</tr><tr style=\"height:136px\">{/if}{/section}</tr></table>";{/if}
    {/section}
    {literal}
    description_div = document.getElementById('showAlbum');
    description_div.innerHTML = '';
    description_div.innerHTML = albums[id];
}
{/literal}
{/if}
{literal}
</script>
{/literal}
</head>
<body>

{if $browserTitle == "Image"}
	<div class="tabs">
		<ul>
			<li id="general_tab" class="current"><span>Insert Photo</span></li>
		</ul>
	</div>
<div class="panel_wrapper">
    <div id="general_panel" class="panel current" style="height:400px">
    <table border="0" cellpadding="4" cellspacing="0">
          <tr>
            <td nowrap="nowrap" width="80px;"><label for="album">Select Album</label></td>
            <td>
    <select name="pickAlbum" id="pickAlbum" onchange="getAlbumData();">
        <option value="0" selected="selected">Select album</option>
        {section name=album loop=$numalbum}
        {if $albums[album].numphotos > 0}
        <option value="{$albums[album].ID}" title="{$albums[album].album_name}">{$albums[album].album_name|truncate:20} (Contains {$albums[album].numphotos} photos)</option>
        {/if}
        {/section}
    </select>
    </td>
      </tr>
      <tr>
        <td nowrap="nowrap" valign="top"><label for="photos">Choose a photo</label></td>
        <td>
        <script type="text/javascript">
        </script>
            <div id="showAlbum" style="overflow:auto;height:350px;width:450px;">No album selected</div>
        </td>
      </tr>
    </table>
</div>
</div>
	<div class="mceActionPanel">
		<div style="float: right">
			<input type="button" id="cancel" name="cancel" value="Cancel" onclick="tinyMCEPopup.close();" />
		</div>
</div>
{else}
<form name="theform" id="theform" onsubmit="FileBrowserDialogue.insertItem();return false;" action="#">
	<div class="tabs">
		<ul>
			<li id="general_tab" class="current"><span>Insert Link</span></li>
		</ul>
	</div>
<div class="panel_wrapper">
    <div id="general_panel" class="panel current" style="height:20px">
    <table border="0" cellpadding="4" cellspacing="0">
          <tr>
            <td nowrap="nowrap"><label for="album">Select Item</label></td>
            <td>

    <select name="pickItem" id="pickItem">
        <option value="0" selected="selected">Select item</option>
        <optgroup label="Articles">
        {section name=album loop=$numart}
        <option value="index.php?page=patrolarticle&id={$articles[album].ID}&action=view" title="{$articles[album].title}">{$articles[album].title|truncate:20}</option>
        {/section}
        </optgroup>
        <optgroup label="Content Items">
        {section name=album loop=$numcontent}
        <option value="index.php?page={$content[album].id}&type=static" title="{$content[album].friendly}">{$content[album].friendly|truncate:20}</option>
        {/section}
        </optgroup>
        <optgroup label="Downloads">
        {section name=album loop=$numdown}
        <option value="index.php?page=downloads&id={$downloads[album].id}&action=down&catid={$downloads[album].cat}" title="{$downloads[album].name}">{$downloads[album].name|truncate:20}</option>
        {/section}
        </optgroup>
        <optgroup label="Events">
        {section name=album loop=$numevents}
        <option value="index.php?page=calender&item={$events[album].id}" title="{$events[album].summary}">{$events[album].summary|truncate:20}</option>
        {/section}
        </optgroup>
        <optgroup label="News Items">
        {section name=album loop=$numnews}
        <option value="index.php?page=news&id={$newsitems[album].id}" title="{$newsitems[album].title}">{$newsitems[album].title|truncate:20}</option>
        {/section}
        </optgroup>
        <optgroup label="Photo Albums">
        {section name=album loop=$numalbum}
        <option value="index.php?page=photos&album={$albums[album].ID}" title="{$albums[album].album_name}">{$albums[album].album_name|truncate:20}</option>
        {/section}
        </optgroup>
        <optgroup label="Polls">
        {section name=album loop=$numpolls}
        <option value="index.php?page=polls&id={$pollitems[album].id}" title="{$pollitems[album].question}">{$pollitems[album].question|truncate:20}</option>
        {/section}
        </optgroup>
    </select>
    </td>
      </tr>
    </table>
    </div>
</div>
	<div class="mceActionPanel">
    		<div style="float: left">
			<input type="submit" id="insert" name="insert" value="Insert" />
		</div>
		<div style="float: right">
			<input type="button" id="cancel" name="cancel" value="Cancel" onclick="tinyMCEPopup.close();" />
		</div>
</div>
</form>
{/if}
</body>
</html>
