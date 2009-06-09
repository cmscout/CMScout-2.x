{literal}
<script type="text/javascript">
<!--
function confirmDelete(articleId) {
if (confirm("This will delete the category. Continue?"))
document.location = "admin.php?page=downloads&action=delete&id=" + articleId;
}

function confirmPublish(articleId, al)
{
    if (confirm("This will publish the download. Continue?")){/literal}
    document.location = "{$pagename}&action=publish&did=" + articleId + "&id=" + al;{literal}
}

function confirmunPublish(articleId, al)
{
    if (confirm("This will unpublish the download. Continue?")){/literal}
    document.location = "{$pagename}&action=unpublish&did=" + articleId + "&id=" + al;{literal}
}
//-->
</script>
{/literal}
<h2>Download Manager</h2>
{if $action != 'view' && $action != "adddown" && $action != "editdown" && $action != "edit" && $action != "add"}
{if $addallowed}<div class="toplinks"><a href="{$pagename}&amp;action=add" title="Add Category"><img src="{$tempdir}admin/images/add.png" alt="Add Category" border="0" /></a>
</div>{/if}
{if $num_cats > 0}
<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="table sortable-onload-2 rowstyle-alt paginate-15" id="sortTable">
<thead>
	  <tr>
		<th width="10%" class="smallhead"></th>
		<th class="smallhead">Category</th>
	  </tr> 
        </thead>
  <tbody>
	 {section name=catloop loop=$num_cats}
		 <tr class="text">
			<td class="text"><div align="center"><a href="{$pagename}&amp;action=view&amp;id={$cats[catloop].id}"><img src="{$tempdir}admin/images/mod.gif" border="0" alt="View {$cats[catloop].name} items" title="View {$cats[catloop].name} items" /></a>&nbsp;&nbsp;{if $editallowed}<a href="{$pagename}&amp;action=edit&amp;id={$cats[catloop].id}"><img src="{$tempdir}admin/images/edit.gif" border="0" alt="Edit {$cats[catloop].name}" title="Edit {$cats[catloop].name}" /></a>{else}<img src="{$tempdir}admin/images/edit_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;&nbsp;{if $deleteallowed}<a href="javascript:confirmDelete({$cats[catloop].id})"><img src="{$tempdir}admin/images/delete.gif" border="0" alt="Delete {$cats[catloop].name}" title="Delete {$cats[catloop].name}" /></a>{else}<img src="{$tempdir}admin/images/delete_grey.gif" border="0" alt="Deleting Disabled" title="Deleting Disabled" />{/if}</div></td>
			<td class="text">{$cats[catloop].name}</td>
	  </tr>
	  {/section}
      </tbody>
	</table>
    {else}
<div align="center">No download categories</div>
{/if}
  {elseif $action == "view"}
  <div class="toplinks">{if $addallowed}<a href="{$pagename}&amp;action=adddown&amp;id={$catinfo.id}" title="Add Download"><img src="{$tempdir}admin/images/add.png" alt="Add Download" border="0" />{/if}<a href="{$pagename}" title="Back"><img src="{$tempdir}admin/images/back.png" alt="Back" border="0" /></a>
</div>
  {if $numdown > 0}
<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="table sortable-onload-3 rowstyle-alt paginate-15" id="sortTable">
<thead>
	  <tr> 
	  <th colspan="4" class="bighead">Downloads for {$catinfo.name}</th>
	</tr>
	  <tr>
		<th width="10%" class="smallhead"></th>
        <th width="5%" class="smallhead">Publish</th>
		<th width="26%" class="smallhead sortable">Name</th>
		<th class="smallhead sortable">Descripton</th>
	  </tr> 
      </thead>
      <tbody>
	 {section name=downloadloop loop=$numdown}
		 <tr class="text">
			<td class="text"><div align="center"><a href="{$pagename}&amp;action=down&amp;did={$downloads[downloadloop].id}"><img src="{$tempdir}admin/images/download.gif" border="0" alt="Download {$downloads[downloadloop].name}" title="Download {$downloads[downloadloop].name}" /></a>&nbsp;&nbsp;{if $editallowed}<a href="{$pagename}&amp;action=editdown&amp;id={$catinfo.id}&amp;did={$downloads[downloadloop].id}"><img src="{$tempdir}admin/images/edit.gif" border="0" alt="Edit {$downloads[downloadloop].name}" title="Edit {$downloads[downloadloop].name}" /></a>{else}<img src="{$tempdir}admin/images/edit_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;&nbsp;{if $deleteallowed}<a href="admin.php?page=downloads&amp;action=deletedown&amp;did={$downloads[downloadloop].id}&amp;id={$catinfo.id}"><img src="{$tempdir}admin/images/delete.gif" border="0" alt="Delete {$downloads[downloadloop].name}" title="Delete {$downloads[downloadloop].name}" /></a>{else}<img src="{$tempdir}admin/images/delete_grey.gif" border="0" alt="Deleting Disabled" title="Deleting Disabled" />{/if}</div></td>
              <td class="text"><div align="center">{if $publishallowed}{if $downloads[downloadloop].allowed == 0}<a href="javascript:confirmPublish({$downloads[downloadloop].id},{$catinfo.id})"><img src="{$tempdir}admin/images/publish.png" border="0" alt="Publish {$downloads[downloadloop].name}" title="Publish {$downloads[downloadloop].name}" /></a>{else}<a href="javascript:confirmunPublish({$downloads[downloadloop].id},{$catinfo.id})"><img src="{$tempdir}admin/images/unpublish.png" border="0" alt="Unpublish {$downloads[downloadloop].name}" title="Unpublish {$downloads[downloadloop].name}" /></a>{/if}{else}{if $downloads[downloadloop].allowed == 0}<img src="{$tempdir}admin/images/publish_grey.gif" border="0" alt="Not allowed to publish" title="Not allowed to publish" />{else}<img src="{$tempdir}admin/images/unpublish_grey.gif" border="0" alt="Not allowed to unpublish" title="Not allowed to unpublis" />{/if}{/if}</div></td>
			<td class="text">{$downloads[downloadloop].name}</td>
			<td class="text">{$downloads[downloadloop].descs}</td>
	  </tr>
	  {/section}
      </tbody>
	</table>
    {else}
<div align="center">No downloads in {$catinfo.name}</div>
{/if}
  {elseif $action=="adddown" || $action=="editdown"}
  <script type="text/javascript">
{include file="../scripts/validator.tpl"}
</script>
  <div align="center">
  <form action="{$editFormAction}" method="post" enctype="multipart/form-data" name="form1" onsubmit="return checkForm([['name','text',true,0,0,''],['desc','text',true,0,0,'']]);">
  <fieldset class="formlist">
  <legend>{if $action=="adddown"}Add{else}Edit{/if} Download</legend>
  <div class="field">
    <label for="name" class="label">Name<span class="hintanchor" title="Enter the name of this download."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
      <div class="inputboxwrapper"><input name="name" type="text" id="name" size="60" maxlength="50" value="{$download.name}" class="inputbox" onblur="checkElement('name', 'text', true, 0, 0, '');" /><br /><span class="fieldError" id="nameError">Required</span></div><br />
      
      <label for="desc" class="label">Description<span class="hintanchor" title="A short description of this download"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
      <div class="inputboxwrapper"><textarea name="desc" rows="10" id="desc" class="inputbox" onblur="checkElement('desc', 'text', true, 0, 0, '');">{$download.descs}</textarea><br /><span class="fieldError" id="descError">Required</span></div><br />
      
    {if $numalbum > 0}
    {literal}
<script type="text/javascript">
function selectImage(id)
{
   oldid = document.getElementById('downloadphoto').value;
   document.getElementById('downloadphoto').value = id;
   
    if (document.getElementById(oldid))
    {    
        document.getElementById(oldid).style.borderWidth = "0px";
    }
    document.getElementById(id).style.borderWidth = "5px";
}

function getAlbumData()
{
    var index = document.getElementById('albumSelect').selectedIndex;
    var id = document.getElementById('albumSelect').options[index].value;
    var albums = new Array();
    var photoId = new Array();
    {/literal}
    {section name=album loop=$numalbum}
       {if $albums[album].numphotos}
       albums[{$albums[album].ID}] = "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"2\" align=\"center\"><tr style=\"height:135px\">{section name=photos loop=$albums[album].numphotos}<td width=\"135px\" style=\"vertical-align:middle;\"><div align=\"center\" ><a href=\"javascript:selectImage('{$albums[album].photos[photos].ID}');\" title=\"Insert Photo\"><img border=\"0\" src=\"thumbnail.php?pic={$albums[album].photos[photos].ID}\" alt=\"Insert Photo\" class=\"selectImage\" id=\"{$albums[album].photos[photos].ID}\" /></a></div></td>{if ($smarty.section.photos.iteration % 3 == 0)}</tr><tr style=\"height:135px\">{/if}{/section}</tr></table>";{/if}
    {/section}
    {literal}
    if (id != 0)
    {
        description_div = document.getElementById('showAlbum');
        description_div.innerHTML = '';
        description_div.innerHTML = albums[id];
        description_div.style.height = "280px";
        selectedID = document.getElementById('downloadphoto').value; 
        if (document.getElementById(selectedID))
        {
            document.getElementById(selectedID).style.borderWidth = "5px";
        }
    }
    else
    {
        document.getElementById('downloadphoto').value = 0;
        description_div = document.getElementById('showAlbum');        
        description_div.innerHTML = '';
        description_div.innerHTML = 'No album selected';
        description_div.style.height = "";
    }
}
</script>
{/literal}
     <label for="albumSelect" class="label">Thumbnail Photo<span class="hintanchor"title="Optional :: Select an image to use as a thumbnail image for this download."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
        <div class="inputboxwrapper"><select id="albumSelect" class="inputbox" style="width:100%" onchange="getAlbumData();">
            <option value="0" selected>None</option>
          {section name=albums loop=$numalbum}
            {if $albums[albums].numphotos > 0}
            <option value="{$albums[albums].ID}" {if $selectedAlbum == $albums[albums].ID}selected="selected"{/if} title="{$albums[albums].album_name}">{$albums[albums].album_name|truncate:30} (Contains {$albums[albums].numphotos} photos)</option>
            {/if}
          {/section}
        </select>
        <br /><div id="showAlbum" style="overflow:auto;width:480px;{if $selectedAlbum}height:280px{/if}">{if !$selectedAlbum}No album selected{else}<table width="100%" cellspacing="0" cellpadding="2" align="center"><tr style="height:135px">{section name=photos loop=$selectedAlbumInfo.numphotos}<td width="135px" style="vertical-align:middle;"><div align="center" ><a href="javascript:selectImage('{$selectedAlbumInfo.photos[photos].ID}');" title="Insert Photo"><img border="0" src="thumbnail.php?pic={$selectedAlbumInfo.photos[photos].ID}" alt="Insert Photo" class="selectImage" id="{$selectedAlbumInfo.photos[photos].ID}" {if $download.thumbnail == $selectedAlbumInfo.photos[photos].ID}style="border-width:5px;"{/if}/></a></div></td>{if ($smarty.section.photos.iteration % 3 == 0)}</tr><tr style="height:135px">{/if}{/section}</tr></table>{/if}</div><input type="hidden" name="downloadphoto" id="downloadphoto" value="{$download.thumbnail}" /></div><br />
   {/if}      
      
     <label for="file" class="label">File<span class="hintanchor" title="Select a file on your computer to upload to the server. {if $action=="editdown"}Leave blank if you don't want to change the file.{/if}"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper"><input name="file" id="file" type="file" size="50" class="inputbox" />{if $action=="editdown"}<br />Current Filename: {$download.file}{/if}</div><br />
    
    {if $action=="editdown"}
      <label for="cat" class="label">Category<span class="hintanchor" title="Change the category that this download is in"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
      <div class="inputboxwrapper"><select name="cat" id="cat" class="inputbox">
        {section name=cats loop=$numcats}
            <option value="{$cat[cats].id}"{if $cat[cats].id == $download.cat} selected{/if}>{$cat[cats].name}</option>
        {/section}
    </select></div><br />
      {/if}
      </div>
      <div class="submitWrapper">
    <input type="submit" name="Submit" value="Submit" class="button" />
		<input name="Cancel" type="button" id="Cancel" value="Cancel" onclick="history.go(-1);" class="button" />
   </div>
    </fieldset>
</form></div>
{elseif $action=="add" || $action == "edit"}
<script type="text/javascript">
{include file="../scripts/validator.tpl"}
</script>
{literal}
  <script type="text/javascript">
<!--
function checkAll(type, start) 
{
    {/literal}itemList = [{section name=groups loop=$numgroups}'{$group[groups].id}'{if $smarty.section.groups.iteration <$numgroups},{/if}{/section}];
    number = {$numgroups};{literal}

    for (i=start;i<number;i++)
    {
        document.getElementById(type + itemList[i]).checked = document.getElementById('all'+type).checked;
    }
}
//-->
</script>
  {/literal}
<form name="form2" method="post" action="" onsubmit="return checkForm([['catname','text',true,0,0,'']]);">
<fieldset class="formlist">
<legend>{if $action=="add"}Add{else}Edit{/if} Category</legend>
<div class="field">
    <label for="catname" class="label">Name<span class="hintanchor" title="Name of this category"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper"><input name="catname" id="catname" size="50" value="{$cat.name}"  class="inputbox" onblur="checkElement('catname', 'text', true, 0, 0, '');"/><br /><span class="fieldError" id="catnameError">Required</span></div><br />

      <span class="label">Uploads<span class="hintanchor" title="Groups that are allowed to upload files. Due to security issues Guest users can never upload files."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></span>
       <div class="inputboxwrapper"><ul class="checklist">
      <li><label for="allupload"><input type="checkbox" value="1" id="allupload" onclick="checkAll('upload', 1);" />Select/Unselect All</label></li>
      {section name=groups loop=$numgroups start=1}
        {assign var="id" value=$group[groups].id}
        <li><label for="upload{$group[groups].id}"><input type="checkbox" value="1" name="upload[{$group[groups].id}]" id="upload{$group[groups].id}" {if $cat.upauth.$id == 1}checked="checked"{/if} />{$group[groups].teamname}</label></li>
      {/section}
      </ul></div><br />

      <span class="label">Downloads<span class="hintanchor" title="Groups that are allowed to download files."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></span>
       <div class="inputboxwrapper"><ul class="checklist">
      <li><label for="alldownload"><input type="checkbox" value="1" id="alldownload" onclick="checkAll('download', 0);" />Select/Unselect All</label></li>
      {section name=groups loop=$numgroups}
        {assign var="id" value=$group[groups].id}
        <li><label for="download{$group[groups].id}"><input type="checkbox" value="1" name="download[{$group[groups].id}]" id="download{$group[groups].id}" {if $cat.downauth.$id == 1}checked="checked"{/if}/>{$group[groups].teamname}</label></li>
      {/section}
      </ul></div><br />
    
    </div>
    <div class="submitWrapper">
    <input type="submit" name="Submit" value="Submit" class="button" />
    <input name="Cancel" type="button" id="Cancel" value="Cancel" onclick="history.go(-1);" class="button" />
    </div>
</fieldset>
</form>
{/if}
