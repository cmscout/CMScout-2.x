                                     {literal}
<script type="text/javascript">
<!--
function confirmDelete(articleId) {
if (confirm("This will delete the word. Continue?"))
document.location = "admin.php?page=censor&action=delete&id=" + articleId;
}

function addWord()
{
    if (word = prompt("Enter in the new word. Wildcards (* and ?) are allowed:"))
    {
        {/literal}document.location = "{$pagename}&action=add&word=" + word;{literal}
    }
}
//-->
</script>
{/literal}
<h2>Word Censor Manager</h2>
{if $addallowed}<div class="toplinks"><a href="javascript:addWord()" title="Add Word"><img src="{$tempdir}admin/images/add.png" alt="Add Word" border="0" /></a>
</div>{/if}
{if $numwords > 0}
<table width="20%" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-15" id="sortTable">
<thead>
  <tr valign="top"> 
    <th width="5%" class="smallhead"></th>
    <th  class="smallhead">Word</th>
  </tr></thead><tbody>
 {section name=wordloop loop=$numwords}
	  <tr valign="middle" class="text"> 
		<td class="text" style="text-align:center">{if $deleteallowed}<a href="javascript:confirmDelete({$words[wordloop].id})"><img src="{$tempdir}admin/images/delete.gif" border="0" alt="Delete" title="Delete" /></a>{else}<img src="{$tempdir}admin/images/delete_grey.gif" border="0" alt="Deleting Disabled" title="Deleting Disabled" />{/if}</td>
		<td class="text">{$words[wordloop].word}</td>
	  </tr>  
	{/section}</tbody>
</table>
{else}
<div align="center">No censor words</div>
{/if}
