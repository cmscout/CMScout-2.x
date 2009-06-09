{literal}
<script type="text/javascript">
<!--
function confirmDelete(articleId) {
if (confirm("This will delete the category. Continue?"))
document.location = "admin.php?page=links&action=delete&id=" + articleId;
}

function deletedown(photoid, albumid) {
if (confirm("This will remove the link from the category. Continue?"))
document.location = "admin.php?page=links&action=deletedown&did=" + photoid + "&id=" + albumid;
}

function confirmPublish(articleId, al)
{
    if (confirm("This will publish the link. Continue?")){/literal}
    document.location = "{$pagename}&action=publish&did=" + articleId + "&id=" + al;{literal}
}

function confirmunPublish(articleId, al)
{
    if (confirm("This will unpublish the link. Continue?")){/literal}
    document.location = "{$pagename}&action=unpublish&did=" + articleId + "&id=" + al;{literal}
}


//-->
</script>
{/literal}
<h2>Link Database</h2>
{if $action != 'view' && $action != "addlink" && $action != "editlink" && $action != "edit" && $action != "add"}
<div class="toplinks">{if $addallowed}<a href="{$pagename}&amp;action=add" title="Add Category"><img src="{$tempdir}admin/images/add.png" alt="Add Category" border="0" /></a>
{/if}{if $editallowed}<a href="{$pagename}&amp;action=fixcat" title="Fix Positions"><img src="{$tempdir}admin/images/fix.png" alt="Fix Positions" border="0" /></a>
{/if}</div>
{if $num_cats > 0}
<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-15" id="sortTable">
<thead>
	  <tr>
		<th width="10%" class="smallhead"></th>
		<th class="smallhead">Category</th>
        <th width="10%" scope="col" class="smallhead">Position</th>
	  </tr> 
      </thead>
      <tbody>
	 {section name=catloop loop=$num_cats}
		 <tr class="text">
			<td class="text" style="text-align:center;"><a href="{$pagename}&amp;action=view&amp;id={$cats[catloop].id}"><img src="{$tempdir}admin/images/mod.gif" border="0" alt="View {$cats[catloop].name} items" title="View {$cats[catloop].name} items" /></a>&nbsp;&nbsp;{if $editallowed}<a href="{$pagename}&amp;action=edit&amp;id={$cats[catloop].id}"><img src="{$tempdir}admin/images/edit.gif" border="0" alt="Edit {$cats[catloop].name}" title="Edit {$cats[catloop].name}" /></a>{else}<img src="{$tempdir}admin/images/edit_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;&nbsp;{if $deleteallowed}<a href="javascript:confirmDelete({$cats[catloop].id})"><img src="{$tempdir}admin/images/delete.gif" border="0" alt="Delete {$cats[catloop].name}" title="Delete {$cats[catloop].name}" /></a>{else}<img src="{$tempdir}admin/images/delete_grey.gif" border="0" alt="Deleting Disabled" title="Deleting Disabled" />{/if}</td>
			<td class="text">{$cats[catloop].name}</td>
            <td class="text" style="text-align:center;">{if $smarty.section.catloop.iteration != 1}{if $editallowed}<a href="{$pagename}&amp;action=moveup&amp;id={$cats[catloop].id}"><img src="{$tempdir}admin/images/up.gif" border="0" alt="Up" title="Move Up" /></a>{else}<img src="{$tempdir}admin/images/up_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}{/if}{if $smarty.section.catloop.iteration != $num_cats}{if $editallowed}<a href="{$pagename}&amp;action=movedown&amp;id={$cats[catloop].id}"><img src="{$tempdir}admin/images/down.gif" border="0" alt="Up" title="Move Down" /></a>{else}<img src="{$tempdir}admin/images/down_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}{/if}</td>
	  </tr>
	  {/section}
      </tbody>
	</table>
    {else}
<div align="center">No Link categories</div>
{/if}
  {elseif $action == "view"}
  <div class="toplinks">{if $addallowed}<a href="{$pagename}&amp;action=addlink&amp;id={$catinfo.id}" title="Add link"><img src="{$tempdir}admin/images/add.png" alt="Add menu" border="0" /></a>
{/if}<a href="{$pagename}" title="Back"><img src="{$tempdir}admin/images/back.png" alt="Back" border="0" /></a></div>
  {if $numlinks > 0}
<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-15" id="sortTable">
<thead>
	  <tr> 
	  <th colspan="5" class="bighead">Links for {$catinfo.name}</th>
	</tr>
    
	  <tr>
		<th width="5%" class="smallhead"></th>
		<th width="25%" class="smallhead">Name</th>
		<th class="smallhead">Descripton</th>
        <th width="30%" class="smallhead">Link</th>
        <th width="12%" scope="col" class="smallhead">Position</th>
	  </tr> </thead><tbody>
	 {section name=links loop=$numlinks}
		 <tr class="text">
			<td class="text" style="text-align:center;">{if $editallowed}<a href="{$pagename}&amp;action=editlink&amp;id={$catinfo.id}&amp;did={$links[links].id}"><img src="{$tempdir}admin/images/edit.gif" border="0" alt="Edit {$links[links].name}" title="Edit {$links[links].name}" /></a>{else}<img src="{$tempdir}admin/images/edit_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;&nbsp;{if $deleteallowed}<a href="javascript:deletedown({$links[links].id},{$catinfo.id})"><img src="{$tempdir}admin/images/delete.gif" border="0" alt="Delete {$links[links].name}" title="Delete {$links[links].name}" /></a>{else}<img src="{$tempdir}admin/images/delete_grey.gif" border="0" alt="Deleting Disabled" title="Deleting Disabled" />{/if}</td>
			<td class="text">{$links[links].name}</td>
			<td class="text">{$links[links].desc}</td>
            <td class="text"><a href="http://{$links[links].url}">{$links[links].url}</a></td>
            <td class="text" style="text-align:center;">{if $smarty.section.links.iteration != 1}{if $editallowed}<a href="{$pagename}&amp;action=moveitemup&amp;did={$links[links].id}&amp;id={$catinfo.id}"><img src="{$tempdir}admin/images/up.gif" border="0" alt="Up" title="Move Up" /></a>{else}<img src="{$tempdir}admin/images/up_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;{/if}{if $smarty.section.links.iteration != $numlinks}{if $editallowed}<a href="{$pagename}&amp;action=moveitemdown&amp;did={$links[links].id}&amp;id={$catinfo.id}"><img src="{$tempdir}admin/images/down.gif" border="0" alt="Up" title="Move Down" /></a>{else}<img src="{$tempdir}admin/images/down_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}{/if}</td>
	  </tr>
	  {/section}</tbody>
	</table>
    {else}
<div align="center">No Links in {$catinfo.name}</div>
{/if}
  {elseif $action=="addlink" || $action=="editlink"}
  <div align="center">
  <form action="{$editFormAction}" method="post"  name="form1" onsubmit="return checkForm([['name','text',true,0,0,''],['url','text',true,0,0,'']]);">
  <fieldset class="formlist">
  <legend>{if $action=="addlink"}Add{else}Edit{/if} Link</legend>
  <div class="field">
    <label for="name" class="label">Name of link<span class="hintanchor" title="Name of the link"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper"><input name="name" type="text" id="name" size="60" maxlength="50" value="{$links.name}" class="inputbox" onblur="checkElement('name', 'text', true, 0, 0, '');" /><br /><span class="fieldError" id="nameError">Required</span></div><br />
      
      <label for="desc" class="label">Description<span class="hintanchor" title="A short description of the linked site"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span> </label>     
      <div class="inputboxwrapper"><textarea name="desc" cols="50" rows="5" id="desc" class="inputbox">{$links.desc}</textarea> </div><br />    
      
    <label for="url" class="label">URL<span class="hintanchor" title="The address of the site"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper">http://<input name="url" id="url" type="text" size="50" class="inputbox" value="{$links.url}" onblur="checkElement('url', 'text', true, 0, 0, '');"/><br /><span class="fieldError" id="urlError">Required</span></div><br />
    
    {if $action=="editlink"}
      <label for="cat" class="label">Category<span class="hintanchor" title="Change the category that this link is in"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
      <div class="inputboxwrapper"><select name="cat" id="cat" class="inputbox">
        {section name=cats loop=$numcats}
            <option value="{$cat[cats].id}"{if $cat[cats].id == $links.cat} selected{/if}>{$cat[cats].name}</option>
        {/section}
    </select></div><br />
      {/if}
    </div>
    <div class="submitWrapper">
    <input type="submit" name="Submit" value="Submit" class="button" />
		<input name="Cancel" type="button" id="Cancel" value="Cancel" onclick="history.go(-1);" class="button" />
    </div>
</form>
</div></div>
{elseif $action=="add" || $action == "edit"}
<div align="center">
<form name="form2" method="post" action="" onsubmit="return checkForm([['catname','text',true,0,0,'']]);">
<fieldset class="formlist">
<legend>{if $action=="add"}Add{else}Edit{/if} Category</legend>
<div class="field">
    <label for="catname" class="label">Name<span class="hintanchor" title="Name of the category"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper"><input name="catname" type="text" id="catname" size="60" maxlength="50" value="{$cat.name}" class="inputbox" onblur="checkElement('catname', 'text', true, 0, 0, '');" /><br /><span class="fieldError" id="catnameError">Required</span></div><br />
</div>
  <div class="submitWrapper">
    <input type="submit" name="Submit" value="Submit" class="button" />
        <input name="Cancel" type="button" id="Cancel" value="Cancel" onclick="history.go(-1);" class="button" />
   </div>
   </fieldset>
</form></div>
{/if}
