{literal}
<script type="text/javascript">
<!--
function confirmPublishart(articleId)
{
    if (confirm("This will publish the album. Continue?"))
    {
       if (confirm("Do you wish to publish all photos under the album as well?"))
       {
        {/literal}
        document.location = "{$pagename}&action=publishart&photo=yes&id=" + articleId;{literal}
       }
       else
       {
        {/literal}
        document.location = "{$pagename}&action=publishart&photo=no&id=" + articleId;{literal}
       }
    }
}

function confirmunPublishart(articleId)
{
    if (confirm("This will unpublish the album. Continue?")){/literal}
    document.location = "{$pagename}&action=unpublishart&id=" + articleId;{literal}
}


//-->
</script>
{/literal}
<h2>Photo Album Manager</h2>
{if $action == ''}
{if $addallowed}<div class="toplinks"><a href="{$pagename}&amp;action=new" title="Add Album"><img src="{$tempdir}admin/images/add.png" alt="Add Album" border="0" /></a>
</div>{/if}
{if $numalbums > 0}
<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-15" id="sortTable">
<thead>
	  <tr>
		<th width="5%" class="smallhead"></th>
        <th width="5%" class="smallhead">Publish</th>
		<th class="smallhead">Name of Album</th>
	  </tr> 
      </thead><tbody>
	 {section name=albumloop loop=$numalbums}
		 <tr class="text">
			<td class="text"><div align="center"><a href="{$pagename}&amp;action=view&amp;id={$albums[albumloop].ID}"><img src="{$tempdir}admin/images/mod.gif" border="0" alt="View Photos for {$albums[albumloop].album_name}" title="View Photos for {$albums[albumloop].album_name}" /></a>&nbsp;&nbsp;{if $deleteallowed}<a href="admin.php?page=photo&amp;action=delete&amp;id={$albums[albumloop].ID}"><img src="{$tempdir}admin/images/delete.gif" border="0" alt="Delete {$albums[albumloop].album_name}" title="Delete {$albums[albumloop].album_name}" /></a>{else}<img src="{$tempdir}admin/images/delete_grey.gif" border="0" alt="Deleting Disabled" title="Deleting Disabled" />{/if}</div></td>
            <td class="text"><div align="center">{if $publishallowed}{if $albums[albumloop].allowed == 0}<a href="javascript:confirmPublishart({$albums[albumloop].ID})"><img src="{$tempdir}admin/images/publish.png" border="0" alt="Publish" title="Publish" /></a>{else}<a href="javascript:confirmunPublishart({$albums[albumloop].ID})"><img src="{$tempdir}admin/images/unpublish.png" border="0" alt="Unpublish" title="Unpublish" /></a>{/if}{else}{if $albums[albumloop].allowed == 0}<img src="{$tempdir}admin/images/publish_grey.gif" border="0" alt="Not allowed to publish" title="Not allowed to publish" />{else}<img src="{$tempdir}admin/images/unpublish_grey.gif" border="0" alt="Not allowed to unpublish" title="Not allowed to unpublis" />{/if}{/if}</div></td>
			<td class="text">{$albums[albumloop].album_name}</td>
	  </tr>
	  {/section}
      </tbody>
	</table>
    {else}
<div align="center">No photo albums</div>
{/if}
{elseif $action=="view"}
{literal}
<script language="javascript" type="text/javascript"> 
<!--
function deletephoto(photoid, albumid) {
if (confirm("This will remove the photo from the album. Continue?"))
document.location = "admin.php?page=photo&action=deletephoto&pid=" + photoid + "&id=" + albumid;
}

function viewphoto(what)
{
{/literal}
	var url = "{$photopath}" + what;
	window.open(url, 'ViewPhoto', 'HEIGHT=400,resizable=yes,WIDTH=400');
{literal}
}

function confirmPublishphoto(articleId, al)
{
    if (confirm("This will publish the photo. Continue?")){/literal}
    document.location = "{$pagename}&action=publishphoto&pid=" + articleId + "&id=" + al;{literal}
}

function confirmunPublishphoto(articleId, al)
{
    if (confirm("This will unpublish the photo. Continue?")){/literal}
    document.location = "{$pagename}&action=unpublishphoto&pid=" + articleId + "&id=" + al;{literal}
}

function showedit(photoid, caption)
{
    document.getElementById('editphoto').style.display='block';
    document.getElementById('albumedit').style.display = 'none';
    document.getElementById('photoupload').style.display = 'none';
    
    document.getElementById('editcaption').value = caption;
    document.getElementById('photoid').value = photoid;
    document.getElementById('photopreview').innerHTML = '';
    document.getElementById('photopreview').innerHTML = '<img src="thumbnail.php?pic=' + photoid + '" alt="' + caption + '" />';
}

function update()
{
    document.getElementById('albumedit').style.display = 'block';
    document.getElementById('photoupload').style.display = 'none';
    document.getElementById('editphoto').style.display='none';
}

function add()
{
    document.getElementById('photoupload').style.display = 'block';
    document.getElementById('albumedit').style.display = 'none';
    document.getElementById('editphoto').style.display='none';
}

 function changeoptions(type)
 {
    var numoptions = document.getElementById('numoptions').value;
    var optiondiv = document.getElementById('photouploads');
    var temp = '';
    var html = '';
    for(var i=1;i<=numoptions;i++)
    {
        temp = ''; 
        if (document.getElementById('filename[' + i + ']')) 
        {
            temp = document.getElementById('filename[' + i + ']').value; 
            temp2 = document.getElementById('caption[' + i + ']').value; 
        }
        {/literal}
        html = html + '<h3>Photo ' + i + '</h3><div class="fieldItem"><label for="filename" class="label">Photograph<span class="hintanchor" title="Select the photo file."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label><div class="inputboxwrapper"><input id="filename[' + i + ']" name="filename[' + i + ']" type="file" size="50" maxlength="255" class="inputbox" /></div></div><br /><div class="fieldItem"><label for="caption" class="label">Caption<span class="hintanchor" title="Caption for the photo."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label><div class="inputboxwrapper"><input id="caption[' + i + ']" name="caption[' + i + ']" type="text" size="50" maxlength="255" class="inputbox" /></div></div><br />';
        {literal}
    }
    optiondiv.innerHTML = '';
    optiondiv.innerHTML = html;
 }
 
 function addone(type)
 {
    document.getElementById('numoptions').value++;
    if (document.getElementById('numoptions').value > 10)
    {
        document.getElementById('numoptions').value = 10;
    }
    changeoptions(type);
 }
 
 function takeone(type)
 {
    var value = document.getElementById('numoptions').value;
    
    if (--value == 0)
    {
        document.getElementById('numoptions').value = 1;
    }
    else
    {
        document.getElementById('numoptions').value--;
    }
    changeoptions(type);
 }
//-->
</script>
{/literal}
<script type="text/javascript">
{include file="../scripts/validator.tpl"}
</script>
	<h3>{$albuminfo.album_name}</h3>
<div align="center"><a href="#" onclick="update();">Edit Album</a>&nbsp;|&nbsp;<a href="#" onclick="add();">Add Photos</a></div>
<div id="albumedit" class="field" style="display:none;">
<form action="{$editFormAction}" method="post" name="form2" onsubmit="return checkForm([['name','text',true,0,0,'']]);">
<fieldset>
<legend>Edit Album</legend>
<label for="name" class="label">Name<span class="hintanchor" title="Name of the name."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><input id="name" name="name" type="text" size="50" maxlength="255" class="inputbox" value="{$albuminfo.album_name}" onblur="checkElement('name', 'text', true, 0, 0, '');" /><br /><span class="fieldError" id="nameError">Required</span></div><br />

<label for="group" class="label">Group<span class="hintanchor" title="Which group site should this album be shown?"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><select name="group" id="group" class="inputbox">
               <option value="0"  {if 0 == $albuminfo.patrol}selected="selected"{/if}>None</option>
                {section name=team loop=$numteams}
                    <option value="{$teams[team].id}" {if $teams[team].id == $albuminfo.patrol}selected="selected"{/if}>{$teams[team].teamname}</option>
                {/section}
               <option value="-1" {if -1 == $albuminfo.patrol}selected="selected"{/if}>Hidden</option>
     </select></div><br />
<div class="submitWrapper">
 <input type="submit" name="Submit" value="Update" class="button" />
 <input type="reset" name="Submit2" value="Cancel" onclick="document.getElementById('albumedit').style.display='none';" class="button" />
</div>
</fieldset>
</form>
</div>
<div id="photoupload" class="field" style="display:none;">
<form action="{$editFormAction}" method="post" enctype="multipart/form-data" name="form3">
<fieldset>
<legend>Upload Photos</legend>
<div class="fieldItem"><label for="numoptions" class="label">Number of photos to upload</label><div class="inputboxwrapper"><input type="text" size="10" class="inputbox" name="numoptions" id="numoptions"  onchange="changeoptions({$item.type})" value="1" style="width:70%"/><a href="#" onclick="takeone({$item.type});"><img src="{$tempdir}admin/images/small_arrow_delete.png" title="[-]" border="0"/></a><a href="#" onclick="addone({$item.type});"><img src="{$tempdir}admin/images/small_arrow_add.png" title="[+]" border="0"/></a></div></div><br />
<div id="photouploads">
<h3>Photo 1</h3>
<div class="fieldItem"><label for="filename" class="label">Photograph<span class="hintanchor" title="Select the photo file."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><input id="filename[1]" name="filename[1]" type="file" size="50" maxlength="255" class="inputbox" /></div></div><br />

<div class="fieldItem"><label for="caption" class="label">Caption<span class="hintanchor" title="Caption for the photo."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><input id="caption[1]" name="caption[1]" type="text" size="50" maxlength="255" class="inputbox" /></div></div><br />
</div>

<div class="submitWrapper">
 <input type="submit" name="Submit" value="Upload Photos" class="button" />
 <input type="reset" name="Submit2" value="Cancel" onclick="document.getElementById('photoupload').style.display='none';" class="button" />
</div>
</fieldset>
</form>
</div>
<div class="field" id="editphoto"  style="display:none;">
<form action="{$editFormAction}" method="post" enctype="multipart/form-data" name="form1">
<fieldset>
<legend>Edit Photo</legend>
<span class="label">Existing Photo<span class="hintanchor" title="Preview of the current photo"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></span><div id="photopreview" class="inputboxwrapper"></div><br />
<label for="editfilename" class="label">New File<span class="hintanchor" title="Select a new photo here. Leave blank if you do not wish to change the photo."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><input id="editfilename" name="editfilename" type="file" size="50" maxlength="255" class="inputbox" /></div><br />

<label for="editcaption" class="label">Caption<span class="hintanchor" title="Caption for the photo."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><input id="editcaption" name="editcaption" type="text" size="50" maxlength="255" class="inputbox" /></div><br />
<input name="photoid" id="photoid" type="hidden" />
<div class="submitWrapper">
 <input type="submit" name="Submit" value="Update Photo" class="button" />
 <input type="reset" name="Submit2" value="Cancel" onclick="document.getElementById('editphoto{$photos[photo].ID}').style.display='none';" class="button" />
</div>
</fieldset>
</form>
</div>
{if $numphotos > 0}
	<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-15" id="sortTable">
<thead>
	  <tr>
		<th width="8%" class="smallhead"></th>
        <th width="10%" class="smallhead">Publish</th>
		<th class="smallhead" width="140">Photo</th>
		<th width="76%" class="smallhead">Caption</th>
	  </tr> 
      </thead><tbody>
    {section name=photo loop=$numphotos}
    <tr valign="top" class="text">
        <td style="text-align:center;" class="text"><div align="center">{if $editallowed}<a href="javascript:showedit({$photos[photo].ID},'{$photos[photo].caption}');" style="text-align:center"><img border="0" src="{$tempdir}admin/images/edit.gif" title="Edit" alt="Edit"/></a>{else}<img src="{$tempdir}admin/images/edit_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;&nbsp;{if $deleteallowed}<a href="javascript:deletephoto({$photos[photo].ID},{$albuminfo.ID})" style="text-align:center"><img border="0" src="{$tempdir}admin/images/delete.gif" title="Delete" alt="Delete"/></a>{else}<img src="{$tempdir}admin/images/delete_grey.gif" border="0" alt="Deleting Disabled" title="Deleting Disabled" />{/if}</div></td>
        <td  style="text-align:center;" class="text"><div align="center">{if $publishallowed}{if $photos[photo].allowed == 0}<a href="javascript:confirmPublishphoto({$photos[photo].ID},{$albuminfo.ID})"><img src="{$tempdir}admin/images/publish.png" border="0" alt="Publish" title="Publish" /></a>{else}<a href="javascript:confirmunPublishphoto({$photos[photo].ID},{$albuminfo.ID})"><img src="{$tempdir}admin/images/unpublish.png" border="0" alt="Unpublish" title="Unpublish" /></a>{/if}{else}{if $photos[photo].allowed == 0}<img src="{$tempdir}admin/images/publish_grey.gif" border="0" alt="Not allowed to publish" title="Not allowed to publish" />{else}<img src="{$tempdir}admin/images/unpublish_grey.gif" border="0" alt="Not allowed to unpublish" title="Not allowed to unpublis" />{/if}{/if}</div></td>
        <td style="text-align:center;" class="text"><a rel="lightbox" href="{$photopath}{$photos[photo].filename}" title="{$photos[photo].caption}"><img src="thumbnail.php?pic={$photos[photo].ID}" alt="{$photos[photo].caption}" border="0" /></a></td>
        <td  class="text">{$photos[photo].caption}</td>
    </tr>
    {/section}</tbody>
</table>
{else}
<div align="center">No photos in album</div>
{/if}
<br /><a href="admin.php?page=photo">Back</a>
{elseif $action == "new"}
<script type="text/javascript">
{include file="../scripts/validator.tpl"}
</script>
<div align="center">
<form action="{$editFormAction}" method="post" name="photos" onsubmit="return checkForm([['album_name','text',true,0,0,'']]);">
<fieldset class="formlist">
<legend>New photo album</legend>
<div class="field">
<label for="editcaption" class="label">Name<span class="hintanchor" title="Name of the album."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><input type="text" id="album_name" name="album_name" class="inputbox" onblur="checkElement('album_name', 'text', true, 0, 0, '');"/><br /><span class="fieldError" id="album_nameError">Required</span></div><br />

<label for="editcaption" class="label">Group<span class="hintanchor" title="Which group does this album belong to."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><select name="patrol" class="inputbox">
               <option value="0" selected >None</option>
                {section name=team loop=$numteams}
                    <option value="{$teams[team].id}">{$teams[team].teamname}</option>
                {/section}
               <option value="-1">Hidden</option>
     </select></div><br />
</div>
<div class="submitWrapper">
<input type="submit" name="submit" value="Add Album" class="button" />	
      <input type="button" name="Submit2" value="Cancel" onclick="window.location='admin.php?page=photo'" class="button" /></div>
</form>
</div>
{/if}
