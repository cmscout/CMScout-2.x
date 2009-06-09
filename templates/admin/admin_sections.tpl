{literal}
  <script type="text/javascript">
<!--
function confirmDelete(id) {
if (confirm("This will delete this section. Continue?"))
document.location = "admin.php?page=sections&action=delete&id=" + id;
}
//-->
  </script>
  {/literal}
<h2>Award Scheme Manager</h2>
{if ($action == "")}
{if $addallowed}<div class="toplinks"><a href="{$pagename}&amp;action=new" title="New Section"><img src="{$tempdir}admin/images/add.png" alt="New Section" border="0" /></a>
</div>{/if}
{if $numsections > 0}
<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-15" id="sortTable1">
<thead>
  <tr valign="top"> 
    <th width="10%" class="smallhead"</th>
    <th class="smallhead">Section</th>
  </tr>
  </thead>
  <tbody>
 {section name=sectionloop loop=$numsections}
	  <tr class="text" valign="middle"> 
		<td class="text" style="text-align:center;">{if $editallowed == 1}<a href="{$pagename}&amp;id={$sections[sectionloop].id}&amp;action=edit"><img src="{$tempdir}admin/images/edit.gif" border="0" alt="Edit {$sections[sectionloop].name}" title="Edit {$sections[sectionloop].name}" /></a>{else}<img src="{$tempdir}admin/images/edit_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;&nbsp;{if $deleteallowed == 1}<a href="javascript:confirmDelete({$sections[sectionloop].id})"><img src="{$tempdir}admin/images/delete.gif" border="0" alt="Delete {$sections[sectionloop].name}" title="Delete {$sections[sectionloop].name}" /></a>{else}<img src="{$tempdir}admin/images/delete_grey.gif" border="0" alt="Deleting Disabled" title="Deleting Disabled" />{/if}</td>
		<td class="text">{$sections[sectionloop].name}</td>
	  </tr>  
	{/section}
    </tbody>
</table>
{else}
<div align="center">No sections</div>
{/if}
{elseif $action == "new" || $action == "edit"}
<div align="center">
<form name="News" method="post" onsubmit="return checkForm([['name','text',true,0,0,'']]);">
<fieldset class="formlist">
<legend>{if $action == "new"}New{else}Edit{/if} Section</legend>
<div class="field">    
<label for="adv" class="label">Name<span class="hintanchor" title="Required :: Enter the name of the section."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><input name="name" type="text" id="name" size="40" maxlength="30" value="{$section.name}" class="inputbox" onblur="checkElement('name', 'text', true, 0, 0, '');" /><br /><span class="fieldError" id="nameError">Required</span></div><br />
</div>
<div class="submitWrapper">
<input type="submit" name="Submit" value="Submit" class="button" />
<input name="Cancel" type="button" id="Cancel" value="Cancel" onclick="history.go(-1);" class="button" />
</div>
</fieldset>
</form></div>
{/if}
