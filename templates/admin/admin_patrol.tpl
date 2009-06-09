<h2>Group Site Manager</h2>
{if $numpatrol > 0}
<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-15" id="sortTable">
<thead>
<tr>
    <th width="8%"class="smallhead"></th>
    <th class="smallhead">Group</th>
  </tr>
  </thead><tbody>
  {section name=patrolloop loop=$numpatrol}
  <tr class="text">
	<td class="text" style="text-align:center;">
    <a href="admin.php?page=patrol&amp;subpage=patrolcontent&amp;pid={$patrol[patrolloop].id}"><img src="{$tempdir}admin/images/page.png" border="0" alt="Content Manager" title="Content Manager" /></a>&nbsp;&nbsp;<a href="admin.php?page=patrol&amp;subpage=patrolmenus&amp;pid={$patrol[patrolloop].id}"><img src="{$tempdir}admin/images/menu.png" border="0" alt="Menu Manager" title="Menu Manager" /></a></td>
	<td class="text"><div align="left">{$patrol[patrolloop].teamname}</div></td>
  </tr>
  {/section} </tbody>
</table>
{else}
<div align="center">There are no groups currently activated for a group site</div>
{/if}