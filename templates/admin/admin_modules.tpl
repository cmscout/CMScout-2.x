{literal}
<script type="text/javascript">
<!--
function confirmPublish(articleId)
{
    if (confirm("This will activate the module. Continue?")){/literal}
    document.location = "{$pagename}&action=activate&id=" + articleId;{literal}
}

function confirmunPublish(articleId)
{
    if (confirm("This will deactivate the module. Continue?")){/literal}
    document.location = "{$pagename}&action=deactivate&id=" + articleId;{literal}
}
//-->
</script>
{/literal}
<h2>Module Manager</h2>
<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="table sortable-onload-2 rowstyle-alt paginate-15" id="sortTable">
<thead>
<tr> 
  <th width="2%" class="smallhead"></th>
  <th class="smallhead sortable">Name</th>
  <th width="30%" class="smallhead sortable">Type</th>
  </tr>
</thead>
<tbody>
{section name=module loop=$nummodule}
<tr class="text">
  <td class="text"><div align="center">{if $publishallowed}{if $modules[module].active == 0}<a href="javascript:confirmPublish({$modules[module].id})"><img src="{$tempdir}admin/images/publish.png" border="0" alt="Activate {$modules[module].name}" title="Activate {$modules[module].name}" /></a>{else}<a href="javascript:confirmunPublish({$modules[module].id})"><img src="{$tempdir}admin/images/unpublish.png" border="0" alt="Deactivate {$modules[module].name}" title="Deactivate {$modules[module].name}" /></a>{/if}{else}{if $modules[module].active == 0}<img src="{$tempdir}admin/images/publish_grey.gif" border="0" alt="Not allowed to publish" title="Not allowed to publish" />{else}<img src="{$tempdir}admin/images/unpublish_grey.gif" border="0" alt="Not allowed to unpublish" title="Not allowed to unpublis" />{/if}{/if}</div></td>
  <td class="text">{$modules[module].name}</td>
  <td class="text">{if $modules[module].type == 1}Sidebox{elseif $modules[module].type == 2}Dynamic Page{elseif $modules[module].type == 4 || $modules[module].type == 5}Group site dynamic page{elseif $modules[module].type == 6}Sub site dynamic page{/if}</td>
</tr>
{/section}
</tbody>
</table>