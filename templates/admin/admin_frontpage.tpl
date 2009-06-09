{literal}
  <script type="text/javascript">
<!--
function confirmDelete(articleId) {
if (confirm("This will remove this page from the frontpage. Continue?"))
  {/literal}
document.location = "{$pagename}&action=delete&id=" + articleId;
{literal}
}
//-->
</script>
  {/literal}
<h2>Frontpage Manager</h2>
{if $action!="edit" && $action!="new"}
{if $addallowed}<div class="toplinks"><a href="{$pagename}&amp;action=new" title="Add Item to Frontpage"><img src="{$tempdir}admin/images/add.png" alt="Add Item to Frontpage" border="0" /></a>
</div>{/if}
{if $numfront > 0}
<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-15" id="sortTable">
<thead>
  <tr valign="top"> 
    <th width="8%" class="smallhead"></th>
    <th  class="smallhead">Name</th>
    <th width="10%" class="smallhead">Position</th>
  </tr>
  </thead>
  <tbody>
 {section name=front loop=$numfront}
	  <tr valign="middle" class="text"> 
		<td class="text" style="text-align:center">{if $editallowed}<a href="{$pagename}&amp;id={$frontpages[front].id}&amp;action=edit"><img src="{$tempdir}admin/images/edit.gif" border="0" alt="Edit {if $frontpages[front].function != "none"}{$frontpages[front].function}{else}{$frontpages[front].page}{/if}" title="Edit {if $frontpages[front].function != "none"}{$frontpages[front].function}{else}{$frontpages[front].page}{/if}" /></a>{else}<img src="{$tempdir}admin/images/edit_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;&nbsp;{if $deleteallowed}<a href="javascript:confirmDelete({$frontpages[front].id})">
        <img src="{$tempdir}admin/images/delete.gif" border="0" alt="Remove {if $frontpages[front].function != "none"}{$frontpages[front].function}{else}{$frontpages[front].page}{/if} from frontpage" title="Remove {if $frontpages[front].function != "none"}{$frontpages[front].function}{else}{$frontpages[front].page}{/if} from frontpage" /></a>{else}<img src="{$tempdir}admin/images/delete_grey.gif" border="0" alt="Deleting Disabled" title="Deleting Disabled" />{/if}</td>
		<td class="text">{$frontpages[front].name}</td>
        <td class="text" style="text-align:center">{if $smarty.section.front.iteration != 1}{if $editallowed}<a href="{$pagename}&amp;action=moveup&amp;id={$frontpages[front].id}"><img src="{$tempdir}admin/images/up.gif" border="0" alt="Up" title="Move Up" /></a>{else}<img src="{$tempdir}admin/images/up_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}{/if}{if $smarty.section.front.iteration != $numfront}{if $editallowed}<a href="{$pagename}&amp;action=movedown&amp;id={$frontpages[front].id}"><img src="{$tempdir}admin/images/down.gif" border="0" alt="Up" title="Move Down" /></a>{else}<img src="{$tempdir}admin/images/down_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}{/if}</td>
      </tr>  
    {/section}</tbody>
</table>
{else}
<div align="center">No items on front page</div>
{/if}
{elseif $action=="new" || $action=="edit"}
<form name="form2" method="post" action="">
<div align="center">
<fieldset class="formlist">
<legend>{if $action=="new"}New{else}Edit{/if} Frontpage Item</legend>
<div class="field">
    
    <label class="label" for="itemid">Page<span class="hintanchor" title="Select the frontpage item."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper"><select name="itemid" id="itemid" class="inputbox">
      <option value="0" {if $item.item == 0}selected="selected"{/if}>Select a Page</option>
    <optgroup label="Dynamic">
      {section name=function loop=$numfunc}
	  	<option value="{$func[function].id}.dynamic" {if $item.item == $func[function].id}selected="selected"{/if}>{$func[function].name}</option>
	  {/section}
    </optgroup>
    <optgroup label="Static Content">
      {section name=pages loop=$numpages}
        <option value="{$page[pages].id}.static" {if $item.item == $page[pages].id}selected="selected"{/if}>{if $page[pages].friendly == ""}{$page[pages].name}{else}{$page[pages].friendly}{/if}</option> 
     {/section}	  
     </optgroup>
    </select></div><br />
    </div>
    
    <div class="submitWrapper">
    <input type="submit" name="Submit" value="Submit" class="button" />
     <input name="Cancel" type="button" id="Cancel" value="Cancel" onclick="history.go(-1);" class="button" /></div>
     
     </fieldset></div>
</form>
{/if}
