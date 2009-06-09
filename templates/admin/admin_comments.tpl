{literal}
<script type="text/javascript">
<!--
function confirmDelete(articleId) {
if (confirm("This will delete the comment. Continue?"))
document.location = "admin.php?page=comments&action=delete&id=" + articleId;
}

function confirmPublish(articleId)
{
    if (confirm("This will publish the comment. Continue?")){/literal}
    document.location = "{$pagename}&action=publish&id=" + articleId;{literal}
}

function confirmunPublish(articleId)
{
    if (confirm("This will unpublish the comment. Continue?")){/literal}
    document.location = "{$pagename}&action=unpublish&id=" + articleId;{literal}
}

//-->
</script>
{/literal}
<h2>Comment Manager</h2>
{if $number > 0}
<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="table sortable-onload-4 rowstyle-alt paginate-15" id="sortTable">
<thead>
  <tr>
    <th width="5%" class="smallhead"></th>
    <th width="5%" class="smallhead">Publish</th>
    <th class="smallhead sortable">User</th>
    <th width="10%" class="smallhead sortable-date">Date</th>
    <th width="40%" class="smallhead sortable">Comment Information</th>
  </tr>
  </thead>
  <tbody>
{section  name=noteloop loop=$number}
  <tr class="text">
    <td class="text"><div align="center">{if $deleteallowed}<a href="javascript:confirmDelete({$comments[noteloop].id})"><img src="{$tempdir}admin/images/delete.gif" border="0" alt="Delete Comment" title="Delete Comment" /></a>{else}<img src="{$tempdir}admin/images/delete_grey.gif" border="0" alt="Deleting Disabled" title="Deleting Disabled" />{/if}</div></td>
    <td class="text"><div align="center">{if $publishallowed}{if $comments[noteloop].allowed == 0}<a href="javascript:confirmPublish({$comments[noteloop].id})"><img src="{$tempdir}admin/images/publish.gif" border="0" alt="Publish Comment" title="Publish Comment" /></a>{else}<a href="javascript:confirmunPublish({$comments[noteloop].id})"><img src="{$tempdir}admin/images/unpublish.gif" border="0" alt="Unpublish Comment" title="Unpublish Comment" /></a>{/if}
    {else}{if $comments[noteloop].allowed == 0}<img src="{$tempdir}admin/images/publish_grey.gif" border="0" alt="Not allowed to publish" title="Not allowed to publish" />{else}<img src="{$tempdir}admin/images/unpublish_grey.gif" border="0" alt="Not allowed to unpublish" title="Not allowed to unpublish" />{/if}{/if}</div></td>
    <td class="text">{$comments[noteloop].uname}</td>
    <td class="text">{$comments[noteloop].date+$timeoffset|date_format:"%Y-%m-%d"}</td>
    <td class="text"><span class="hintanchor" title="Comment :: {$comments[noteloop].comment}"><img src="{$tempdir}admin/images/information.png" alt="[i]"/></span>{if $comments[noteloop].type == 0}<b>Article:</b>{elseif $comments[noteloop].type == 1}<b>Photo Album:</b>{/if} {$comments[noteloop].title}</td>
  </tr>
{/section}
</tbody>
</table>
{else}
<div align="center">No Comments</div>
{/if}