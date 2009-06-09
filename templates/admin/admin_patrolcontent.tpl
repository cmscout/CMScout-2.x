{literal}
  <script type="text/javascript">
<!--
function confirmDelete(articleId) {
if (confirm("This will delete this page. Continue?"))
  {/literal}
document.location = "admin.php?page=patrol&subpage=patrolcontent&action=delete&pid={$patrolid}&id=" + articleId;
{literal}
}
//-->
</script>
  {/literal}
  <h2>{$patrolname} Content Manager</h2>
{if $action!="edit" && $action!="new" && $action != "moveitem"}
<div class="toplinks">{if $addallowed}<a href="{$pagename}&amp;action=new&amp;pid={$patrolid}" title="Add Page"><img src="{$tempdir}admin/images/add.png" alt="Add Page" border="0" /></a>&nbsp;{/if}<a href="admin.php?page=patrol&amp;subpage=patrolmenus&amp;pid={$patrolid}" title="Menu Manager"><img src="{$tempdir}admin/images/menu.png" alt="Menu Manager" border="0" /></a>&nbsp;<a href="admin.php?page=patrol" title="Back"><img src="{$tempdir}admin/images/back.png" alt="Back" border="0" /></a></div>
{if $numcontent > 0}
<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="table sortable-onload-2 rowstyle-alt paginate-15" id="sortTable">
<thead>
  <tr valign="top"> 
    <th width="13%" class="smallhead"></th>
    <th width="15%" class="smallhead sortable">Name</th>
    <th width="75%" class="smallhead sortable">Summary</th>
  </tr></thead><tbody>
 {section name=contentloop loop=$numcontent}
	  <tr valign="middle" class="text"> 
		<td class="text" style="text-align:center;">{if $editallowed}<a href="{$pagename}&amp;id={$content[contentloop].id}&amp;action=edit&amp;pid={$patrolid}" title="Edit {$content[contentloop].name}"><img src="{$tempdir}admin/images/edit.gif" border="0" alt="Edit {$content[contentloop].friendly}" /></a>{else}<img src="{$tempdir}admin/images/edit_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;&nbsp;{if $editallowed && !$limit}<a href="{$pagename}&amp;action=moveitem&amp;id={$content[contentloop].id}&amp;pid={$patrolid}" title="Move {$content[contentloop].name}"><img src="{$tempdir}admin/images/move.gif" border="0" alt="Move {$content[contentloop].friendly}" /></a>{else}<img src="{$tempdir}admin/images/move_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;&nbsp;{if $editallowed}{if $content[contentloop].frontpage == 0}<a href="{$pagename}&amp;action=putfront&amp;pid={$patrolid}&amp;id={$content[contentloop].id}" title="Place {$content[contentloop].friendly} on frontpage"><img src="{$tempdir}admin/images/offfrontpage.png" border="0" alt="Place {$content[contentloop].friendly} on frontpage"  /></a>{else}<img src="{$tempdir}admin/images/onfrontpage.png" border="0" alt="{$content[contentloop].friendly} is current frontpage" title="{$content[contentloop].friendly} is current frontpage"  />{/if}{else}<img src="{$tempdir}admin/images/frontpage_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}
        {if $deleteallowed}<a href="javascript:confirmDelete({$content[contentloop].id})"  title="Delete {$content[contentloop].friendly}">
        <img src="{$tempdir}admin/images/delete.gif" border="0" alt="Delete {$content[contentloop].friendly}"/></a>{else}<img src="{$tempdir}admin/images/delete_grey.gif" border="0" alt="Deleting Disabled" title="Deleting Disabled" />{/if}</td>
		<td class="text"><span class="hintanchor" title="Internal Name :: {$content[contentloop].name}"><img src="{$tempdir}admin/images/information.png" alt="[i]"/></span>{if $content[contentloop].friendly}{$content[contentloop].friendly}{else}Please set a friendly name for this page{/if}</td>
        <td class="text">{$content[contentloop].content|strip_tags|truncate:150}</td>
	  </tr>  
	{/section}</tbody>
</table>
{else}
<div align="center">No content for {$patrolname}</div>
{/if}
{elseif $action=="new"}
<script type="text/javascript">
{include file="../scripts/validator.tpl"}

{literal}
function insertHTML(html) {
    tinyMCE.execInstanceCommand("editor","mceInsertContent",false,html);
}
{/literal}
</script>
<div align="center">
<form name="Content" method="post" action="{$editFormAction}" onsubmit="return checkForm([['name','custom',true,0,0,/^[a-zA-Z0-9_]*$/],['fname','text',true,0,0,''],['editor','text',0,0,'']]);"> 
<fieldset class="formlist">
<legend>New Page</legend>
<div class="field">
      <label for="name" class="label">Internal Name<span class="hintanchor" title="This is the name that CMScout uses internally to refer to the page. It can only contain alphanumeric characters and the underscore, and can not contain any spaces. It can not be changed once it has been set."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
      <div class="inputboxwrapper"><input name="name" type="text" id="name" class="inputbox" onblur="checkElement('name', 'custom', true, 0, 0, /^[a-zA-Z0-9_]*$/);" /><br /><span class="fieldError" id="nameError">Required: May only contain alphanumeric characters and the underscore.</span></div><br />
    
      <label for="fname" class="label">Friendly Name<span class="hintanchor" title="General name for the page."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
      <div class="inputboxwrapper"><input name="fname" type="text" id="fname" class="inputbox" onblur="checkElement('fname', 'text', true, 0, 0, '');" /><br /><span class="fieldError" id="fnameError">Required</span></div><br />

    <span class="label">Public Page<span class="hintanchor" title="Can the general public see this page, or is it limited to members of the group."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></span>
    <div class="inputboxwrapper"><input name="public" type="radio" id="public" id="puplic:yes" value="1" checked="checked" /><label for="public:yes">Yes</label>&nbsp;<input name="public" type="radio" id="public" value="0"  id="puplic:no"/><label for="public:no">No</label></div><br />
    
    </div>
      <textarea id="editor" name="editor" style="width:100%;height:50em" class="inputbox"></textarea><br />
      <div style="width:100%"><div style="font-weight:bold;text-align:center;font-size:big;">CMScout Tags</div>
<div class="field" style="border:1px dashed #000">
<div style="font-weight:bold;text-align:left;font-size:big;">Number of:</div>
<span class="label">Articles</span><div class="inputboxwrapper"><a href="javascript:insertHTML('!#articles#!');">!#articles#!</a></div><br />
<span class="label">Albums</span><div class="inputboxwrapper"><a href="javascript:insertHTML('!#albums#!');">!#albums#!</a></div><br />
<span class="label">Photos</span><div class="inputboxwrapper"><a href="javascript:insertHTML('!#photos#!');">!#photos#!</a></div><br />
<span class="label">Users</span><div class="inputboxwrapper"><a href="javascript:insertHTML('!#users#!');">!#users#!</a></div><br />
<span class="label">Log entries</span><div class="inputboxwrapper"><a href="javascript:insertHTML('!#log#!');">!#log#!</a></div><br />
</div>
</div>
<br style="clear:both;" />
	  
    <div class="submitWrapper"> 
        <input type="submit" name="Submit" value="Submit" class="button" />
        <input name="Cancel" type="button" id="Cancel" value="Cancel" onclick="window.location = '{$pagename}&amp;pid={$patrolid}'" class="button" />
      </div>
      </fieldset>
</form>
</div>
{elseif $action=="edit"}
<script type="text/javascript">
{include file="../scripts/validator.tpl"}

{literal}
function insertHTML(html) {
    tinyMCE.execInstanceCommand("editor","mceInsertContent",false,html);
}
{/literal}
</script>
<div align="center">
{if $editallowed}<form name="Content" method="post" action="{$editFormAction}" onsubmit="return checkForm([['fname','text',true,0,0,''],['editor','text',0,0,'']]);">
<fieldset class="formlist">
<legend>Edit Page</legend>{else}<div class="formlist">{/if}
<div class="field">
      <label for="name" class="label">Internal Name<span class="hintanchor" title="This is the name that CMScout uses internally to refer to the page. It can not be changed"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
      <div class="inputboxwrapper">{$item.name}</div><br />
    
      <label for="fname" class="label">Friendly Name<span class="hintanchor" title="General name for the page."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
      <div class="inputboxwrapper">{if $editallowed}<input name="fname" type="text" id="fname" class="inputbox" onblur="checkElement('fname', 'text', true, 0, 0, '');" value="{$item.friendly}" /><br /><span class="fieldError" id="fnameError">Required</span>{else}{$item.friendly}{/if}</div><br />
      
    <span class="label">Public Page<span class="hintanchor" title="Can the general public see this page, or is it limited to members of the group."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></span>
    <div class="inputboxwrapper"><input name="public" type="radio" id="public" id="puplic:yes" value="1" {if $item.special == 1}checked="checked"{/if} /><label for="public:yes">Yes</label>&nbsp;<input name="public" type="radio" id="public" value="0"  id="puplic:no" {if $item.special == 0}checked="checked"{/if}/><label for="public:no">No</label></div><br />
    </div>
      {if $editallowed}
      <textarea id="editor" name="editor" style="width:100%;height:50em" class="inputbox">{$item.content}</textarea><br />
      <div style="width:100%"><div style="font-weight:bold;text-align:center;font-size:big;">CMScout Tags</div>
<div class="field" style="border:1px dashed #000">
<div style="font-weight:bold;text-align:center;font-size:big;">Number of:</div>
<span class="label">Articles</span><div class="inputboxwrapper"><a href="javascript:insertHTML('!#articles#!');">!#articles#!</a></div><br />
<span class="label">Albums</span><div class="inputboxwrapper"><a href="javascript:insertHTML('!#albums#!');">!#albums#!</a></div><br />
<span class="label">Photos</span><div class="inputboxwrapper"><a href="javascript:insertHTML('!#photos#!');">!#photos#!</a></div><br />
<span class="label">Users</span><div class="inputboxwrapper"><a href="javascript:insertHTML('!#users#!');">!#users#!</a></div><br />
<span class="label">Members</span><div class="inputboxwrapper"><a href="javascript:insertHTML('!#members#!');">!#members#!</a></div><br />
<span class="label">Log entries</span><div class="inputboxwrapper"><a href="javascript:insertHTML('!#log#!');">!#log#!</a></div><br />
</div>
</div>
<br style="clear:both;" />{else}{$item.content}{/if}
	      
    {if $editallowed}<div class="submitWrapper"> 
        <input type="submit" name="Submit" value="Submit" class="button" />
        <input name="Cancel" type="button" id="Cancel" value="Cancel" onclick="window.location = '{$pagename}&amp;pid={$patrolid}'" class="button" />
      </div>
      </fieldset>
</form>{else}</div>{/if}
</div>
{elseif $action=="moveitem"}
<div align="center">
 <form method="post" action="{$editFormAction}" name="form">
 <fieldset class="formlist">
 <legend>Move Page</legend>
 <div class="field">
 <label for="place" class="label">Location<span class="hintanchor" title="Location to move this page too."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
 <div class="inputboxwrapper">
 <select name="place" class="inputbox">
 <option value="0" selected="selected">Main content</option>
 <optgroup label="Group Site">
  {section name=loop loop=$numpatrols}
   <option value="group_{$patrols[loop].id}">{$patrols[loop].teamname}</option>
  {/section}
  </optgroup>
  <optgroup label="Sub Site">
  {section name=loop loop=$numsubsites}
   <option value="site_{$subsites[loop].id}">{$subsites[loop].name}</option>
  {/section}
  </div>
 </select></div><br />
 </div>
 <div class="submitWrapper">
 <input type="Submit" name="Submit" id="Submit" value="Move" class="button" />&nbsp;<input type="button" value="Cancel" id="cancel" name="cancel" onclick="window.location = '{$pagename}&amp;pid={$patrolid}'" class="button" /></div>
 </fieldset>
 </form>
 </div>
{/if}
