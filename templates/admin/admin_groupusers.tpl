{literal}
<script type="text/javascript">
<!--
function confirmDelete(articleId) {{/literal}
if (confirm("This will remove {$userinfo.uname} from the group Continue?"))
document.location = "admin.php?page=group&subpage=groupusers&action=delete&gid={$groupinfo.id}&uid=" + articleId;{literal}
}
//-->
</script>
{/literal}
<h2>{$groupinfo.teamname} Users</h2>
<table width="100%" border="0" cellspacing="1" cellpadding="4" align="center" class="table">
<tr> 
   <th width="5%" class="smallhead"></th>
  <th colspan="2" class="smallhead">User Name</th>
</tr>
{section name=groups loop=$numgroupusers}
<tr>
<td class="text" style="text-align:center;">{if $editallowed_page}<a href="javascript:confirmDelete('{$groupusers[groups].id}')" title="Remove {$groupusers[groups].uname} from {$groupinfo.teamname}"><img src="{$tempdir}admin/images/delete.gif"  border="0" alt="Remove {$groupusers[groups].uname} from {$groupinfo.teamname}" /></a>{else}<img src="{$tempdir}admin/images/delete_grey.gif" border="0" alt="Deleting Disabled" title="Deleting Disabled" />{/if}</td>
<td colspan="2" class="text" {if $groupusers[groups].type == 1}style="background-color:#cdd7ff;"{elseif $groupusers[groups].type == 2}style="background-color:#eeffff;"{/if}><a href="admin.php?page=users&subpage=users_view&id={$groupusers[groups].id}">{$groupusers[groups].uname}</a></td>
</tr>
{/section}
<tr>
<th class="smallhead" colspan="3">Colour Key</td>
</tr>
<tr style="padding:0px;margin:0px;">
<td colspan="3" style="padding:0px;margin:0px;height:2em">
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0" style="padding:0px;margin:0px;" align="center" class="table">
<tr>
<td class="text" width="33%" style="text-align:center;font-weight:bold;vertical-align:middle;">Normal User</td>
<td class="text" width="33%" style="background-color:#cdd7ff;text-align:center;font-weight:bold;vertical-align:middle;">Assistant Group Leader</td>
<td class="text" width="33%" style="background-color:#eeffff;text-align:center;font-weight:bold;vertical-align:middle;">Group Leader</td>
</tr>
</table>
</td>
</tr>
{if $editallowed_page}
<tr>
<th class="smallhead" colspan="3">Add User to Group</td>
<tr>
<td class="text" colspan="3">
<form method="post" action="{$pagename}&amp;subpage=groupusers&amp;action=add&amp;gid={$groupinfo.id}">
<div class="field">
<label for="uid" class="label">User</label>
<div class="inputboxwrapper"><select name="uid" id="uid" class="inputbox">
<option value="0">Select user</option>
{section name=users loop=$numusers}
<option value="{$users[users].id}">{$users[users].uname}</option>
{/section}
</select></div><br />
<label for="utype" class="label">Type</label>
<div class="inputboxwrapper">
<select name="utype" id="utype" class="inputbox">
<option value="0">Normal User</option>
<option value="1">Assistant Group Leader</option>
<option value="2">Group Leader</option>
</select></div><br />
</div>
<div class="submitWrapper">
<input type="submit" class="button" value="Add" name="action" id="action" />&nbsp;<input type="button" class="button" value="Back" onclick="window.location='admin.php?page=group'" /></div>
</form></td>
</tr>
{else}
<tr>
<td colspan="3" class="text"><input type="button" class="button" value="Back" onclick="window.location='admin.php?page=group'" /></td>
</tr>
{/if}
</table>
