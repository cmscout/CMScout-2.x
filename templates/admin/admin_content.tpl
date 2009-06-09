<h2>Content Manager</h2>
{if $action!="edit" && $action!="new" && $action != "moveitem"}
{if $addallowed}<div class="toplinks"><a href="{$pagename}&amp;action=new" title="Add Page"><img src="{$tempdir}admin/images/add.png" alt="Add Page" border="0" /></a>
</div>{/if}
<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="table sortable-onload-2 rowstyle-alt paginate-15" id="sortTable">
<thead>
{if $numcontent > 0}
  <tr valign="top"> 
    <th width="10%" class="smallhead"></th>
    <th width="30%" class="smallhead sortable">Name</th>
    <th class="smallhead sortable">Summary</th>
  </tr>
  </thead>
  <tbody>
 {section name=contentloop loop=$numcontent}
	  <tr valign="middle" class="text"> 
		<td class="text"><div align="center">{if $editallowed}<a href="{$pagename}&amp;id={$content[contentloop].id}&amp;action=edit"><img src="{$tempdir}admin/images/edit.gif" border="0" alt="Edit {$content[contentloop].friendly}" title="Edit {$content[contentloop].name}" /></a>{else}<a href="{$pagename}&amp;id={$content[contentloop].id}&amp;action=edit" title="View {$content[contentloop].name}"><img src="{$tempdir}admin/images/page.png" border="0" alt="View {$content[contentloop].name}" /></a>{/if}&nbsp;&nbsp;{if $editallowed && !$limit}<a href="{$pagename}&amp;action=moveitem&amp;id={$content[contentloop].id}" title="Move {$content[contentloop].friendly}"><img src="{$tempdir}admin/images/move.gif" border="0" alt="Move {$content[contentloop].friendly}" /></a>{else}<img src="{$tempdir}admin/images/move_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;&nbsp;{if $deleteallowed}<a href="{$pagename}&amp;action=delete&amp;id={$content[contentloop].id}"><img src="{$tempdir}admin/images/delete.gif" border="0" alt="Delete {$content[contentloop].friendly}" title="Delete {$content[contentloop].friendly}" /></a>{else}<img src="{$tempdir}admin/images/delete_grey.gif" border="0" alt="Deleting Disabled" title="Deleting Disabled" />{/if}</div></td>
		<td class="text"><span class="hintanchor" title="Internal Name :: {$content[contentloop].name}"><img src="{$tempdir}admin/images/information.png" alt="[i]"/></span>{if $content[contentloop].friendly}{$content[contentloop].friendly}{else}Please set a friendly name for this page{/if}</td>
        <td class="text">{$content[contentloop].content|strip_tags|truncate:150}</td>
	  </tr>  
	{/section}
    </tbody>
</table>
{else}
<div align="center">No Static Content</div>
{/if}
{elseif $action=="new"}
<script type="text/javascript">
{include file="../scripts/validator.tpl"}
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

      <span class="label">Default Group Access<span class="hintanchor" title="Give users in the default group access to this page."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></span>
      <div class="inputboxwrapper"><input type="radio" name="access" id="access:yes" value="1" checked="checked" /><label for="access:yes">Yes</label>
    <input name="access" id="access:no" type="radio" value="0"  /><label for="access:no">No</label></div><br />

      <span class="label">Guest User Access<span class="hintanchor" title="Give guests access to this page."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></span>
      <div class="inputboxwrapper"><input type="radio" name="gaccess" id="gaccess:yes" value="1" checked="checked" /><label for="gaccess:yes">Yes</label>
    <input name="gaccess" id="gaccess:no" type="radio" value="0"  /><label for="gaccess:no">No</label></div><br />

</div>
      <textarea id="editor" name="editor" style="width:100%; height:50em" class="inputbox"></textarea>
	  
    <div class="submitWrapper"> 
        <input type="submit" name="Submit" value="Submit" class="button" />
        <input name="Cancel" type="button" id="Cancel" value="Cancel" onclick="window.location = '{$pagename}'" class="button" />
      </div>
      </fieldset>
</form>
</div>
{elseif $action=="edit"}
<script type="text/javascript">
{include file="../scripts/validator.tpl"}
</script>
<div align="center">
{if $editallowed}<form name="Content" method="post" action="{$editFormAction}" onsubmit="return checkForm([['fname','text',true,0,0,''],['editor','text',0,0,'']]);">
<fieldset class="formlist">
<legend>Edit Page</legend>{else}<div class="formlist">{/if}
<div class="field">
      <label for="name" class="label">Internal Name<span class="hintanchor" title="This is the name that CMScout uses internally to refer to the page. It can not be changed"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
      <div class="inputboxwrapper">{$contents.name}</div><br />
    
      <label for="fname" class="label">Friendly Name<span class="hintanchor" title="General name for the page."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
      <div class="inputboxwrapper">{if $editallowed}<input name="fname" type="text" id="fname" class="inputbox" onblur="checkElement('fname', 'text', true, 0, 0, '');" value="{$contents.friendly}" /><br /><span class="fieldError" id="fnameError">Required</span>{else}{$contents.friendly}{/if}</div><br />
    </div>
      {if $editallowed}<textarea id="editor" name="editor" style="width:100%; height:50em" class="inputbox">{$Showcontent}</textarea>{else}{$Showcontent}{/if}
	  
    {if $editallowed}<div class="submitWrapper"> 
        <input type="submit" name="Submit" value="Submit" class="button" />
        <input name="Cancel" type="button" id="Cancel" value="Cancel" onclick="window.location = '{$pagename}'" class="button" />
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
 <input type="Submit" name="Submit" id="Submit" value="Move" class="button" />&nbsp;<input type="button" value="Cancel" id="cancel" name="cancel" onclick="window.location = '{$pagename}'" class="button" /></div>
 </fieldset>
 </form>
 </div>
{/if}
