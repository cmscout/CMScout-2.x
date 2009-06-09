{literal}
  <script type="text/javascript">
<!--
function confirmDelete(articleId) {
if (confirm("This will delete this sub site. Continue?"))
 {/literal}
document.location = "{$pagename}&action=delete&id=" + articleId;
{literal}
}
//-->
</script>
 {/literal}
<h2>Sub Site Manager</h2>
{if $action != "Add" && $action != "edit"}
{if $addallowed}<div class="toplinks"><a href="{$pagename}&amp;action=Add" title="Add Site"><img src="{$tempdir}admin/images/add.png" alt="Add Site" border="0" /></a>
</div>{/if}
{if $numsites > 0}
	<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-15" id="sortTable">
<thead>
  <tr>
    <th class="smallhead" width="12%"></th>
    <th class="smallhead">Site Name</th>
  </tr></thead><tbody>
  {section name=siteloop loop=$numsites}
  <tr class="text">
    <td class="text" style="text-align:center">{if $editallowed}<a href="{$pagename}&amp;action=edit&amp;id={$sites[siteloop].id}" title="Edit {$sites[siteloop].name} Subsite"><img src="{$tempdir}admin/images/edit.gif" border="0" alt="Edit {$sites[siteloop].name} Subsite" /></a>{else}<img src="{$tempdir}admin/images/edit_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;&nbsp;<a href="admin.php?page=subsite&amp;subpage=subcontent&amp;sid={$sites[siteloop].id}" title="Content Manager"><img src="{$tempdir}admin/images/page.png" border="0" alt="Content Manager"  /></a>&nbsp;&nbsp;<a href="admin.php?page=subsite&amp;subpage=submenu&amp;sid={$sites[siteloop].id}" title="Menu Manager"><img src="{$tempdir}admin/images/menu.png" border="0" alt="Menu Manager" /></a>&nbsp;&nbsp;{if $deleteallowed}<a href="javascript:confirmDelete({$sites[siteloop].id})" title="Delete {$sites[siteloop].name} Subsite"><img src="{$tempdir}admin/images/delete.gif" border="0" alt="Delete {$sites[siteloop].name} Subsite" /></a>{else}<img src="{$tempdir}admin/images/delete_grey.gif" border="0" alt="Deleting Disabled" title="Deleting Disabled" />{/if}</td>
	<td class="text"><div align="left">{$sites[siteloop].name}</div></td>
  </tr>
  {/section}  </tbody>
</table>
{else}
<div align="center">There are no sub sites</div>
{/if}
{else}
<div align="center">
<form name="form1" method="post"  onsubmit="return checkForm([['name','text',true,0,0,'']]);">
<fieldset class="formlist">
<legend>{if $action == "Add"}New{else}Edit{/if} Sub Site</legend>
<div class="field">
  <label for="name" class="label">Site Name</label>
<div class="inputboxwrapper"><input name="name" type="text" id="name" size="60" maxlength="50" value="{$site.name}" class="inputbox"  onblur="checkElement('name', 'text', true, 0, 0, '');"/><br /><span class="fieldError" id="nameError">Required</span></div><br />
</div>
    <div class="submitWrapper">
    <input type="submit" name="Submit" value="Submit" class="button">
    <input name="Cancel" type="button" id="Cancel" value="Cancel" onclick="history.go(-1);" class="button" /></div>
</form></div></div>
{/if}
