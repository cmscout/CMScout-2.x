{literal}
  <script type="text/javascript">
<!--
function confirmDelete(articleId) {
if (confirm("This will delete this item. Continue?"))
{/literal}
document.location = "admin.php?page=patrol&subpage=patrolmenus&action=delete&pid={$patrolid}&id=" + articleId;
{literal}
}

function show(id)
{
    document.getElementById(id).style.display = '';
    return;
}

function hide(id)
{
    document.getElementById(id).style.display = "none";
    return;
}

function itemss()
{
    var urlenabled = {/literal}{if $numpages > 0}true{else}false{/if}{literal};
    if (document.form2.items.selectedIndex==0 && urlenabled == true)
    {
        show('urldiv');
    }
    else
    {
       hide('urldiv');
    }
}
//-->
  </script>
  {/literal}
<h2>{$patrolname} Menu Manager</h2>
{if $action == "view" || $action == ""}

    <div class="toplinks">{if $addallowed}<a href="{$pagename}&amp;action=new&amp;pid={$patrolid}" title="Add Link"><img src="{$tempdir}admin/images/add.png" alt="Add Link" border="0" /></a>&nbsp;{/if}{if $editallowed}<a href="{$pagename}&amp;action=fixcat&amp;pid={$patrolid}" title="Fix Positions"><img src="{$tempdir}admin/images/fix.png" alt="Fix Positions" border="0" /></a>&nbsp;{/if}<a href="admin.php?page=patrol&amp;subpage=patrolcontent&amp;pid={$patrolid}" title="Content Manager"><img src="{$tempdir}admin/images/page.png" alt="Content Manager" border="0" /></a>&nbsp;<a href="admin.php?page=patrol" title="Back"><img src="{$tempdir}admin/images/back.png" alt="Back" border="0" /></a></div>
	<table width="98%" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-15" id="sortTable">
<thead>
  <tr>
    <th width="8%" scope="col" class="smallhead"></th>
    <th scope="col" class="smallhead">Name</th>
    <th width="41%" scope="col" class="smallhead">Action of item</th>
    <th width="10%" scope="col" class="smallhead">Position</th>
  </tr></thead><tbody>
  {section name=cats loop=$menuitems}
  <tr class="text">
    <td class="text" style="text-align:center">{if $editallowed}<a href="{$pagename}&amp;action=edit&amp;id={$menuitems[cats].id}&amp;pid={$patrolid}"><img src="{$tempdir}admin/images/edit.gif" border="0" alt="Edit {$menuitems[cats].name}" title="Edit {$menuitems[cats].name}" /></a>{else}<img src="{$tempdir}admin/images/edit_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;&nbsp;{if $deleteallowed}<a href="javascript:confirmDelete({$menuitems[cats].id})"><img src="{$tempdir}admin/images/delete.gif" border="0" alt="Delete {$menuitems.side[cats].name}" title="Delete {$menuitems.side[cats].name}" /></a>{else}<img src="{$tempdir}admin/images/delete_grey.gif" border="0" alt="Deleting Disabled" title="Deleting Disabled" />{/if}</td>
    <td class="text">{$menuitems[cats].name}</td>
    <td class="text">{$menuitems[cats].action}</td>
    <td class="text" style="text-align:center">{if $smarty.section.cats.iteration != 1}{if $editallowed}<a href="{$pagename}&amp;action=moveup&amp;id={$menuitems[cats].id}&amp;pid={$patrolid}"><img src="{$tempdir}admin/images/up.gif" border="0" alt="Move Up" title="Move Up" /></a>{else}<img src="{$tempdir}admin/images/up_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}{/if}{if $smarty.section.cats.iteration != $smarty.section.cats.loop}{if $editallowed}<a href="{$pagename}&amp;action=movedown&amp;id={$menuitems[cats].id}&amp;pid={$patrolid}"><img src="{$tempdir}admin/images/down.gif" border="0" alt="Move Down" title="Move Down" /></a>{else}<img src="{$tempdir}admin/images/down_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}{/if}</td>
  </tr>
  {sectionelse}
  <tr><td colspan="4" class="text">No menu items</td></tr>
  {/section}</tbody>
  </table>

{elseif $action == "new" || $action == "edit"}
<form name="form2" method="post" onsubmit="return checkForm([['name','text',true,0,0,'']]);">
  <div align="center">
  <fieldset class="formlist">
<legend>{if $action == "new"}New{else}Edit{/if} Link</legend>
 <div class="field">
  <div class="fieldItem"><label class="label" for="name">Name<span class="hintanchor" title="Name of menu item."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper"><input name="name" type="text" id="name" value="{$item.name}" class="inputbox" size="60" maxlength="50" onblur="checkElement('name', 'text', true, 0, 0, '');" /><br /><span class="fieldError" id="nameError">Required</span></div></div><br />

    <div class="fieldItem"><label class="label" for="items">Link to<span class="hintanchor" title="What does this menu item link to?"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper"><select name="items" id="items" class="inputbox" onchange="itemss();">
      <option value="url" {if $item.type == 5 || $item == ''}selected="selected"{/if}>External Link</option>
      {if $numpages}
     <optgroup label="Staic Pages">
      {section name=pages loop=$numpages}
        <option value="{$page[pages].id}.stat" {if $item.item == $page[pages].id && $item.type == 1}selected="selected"{/if}>{if $page[pages].friendly == ""}{$page[pages].name}{else}{$page[pages].friendly}{/if}</option> 
     {/section}	
      </optgroup>{/if}
      {if $numfunc}
      <optgroup label="Dynamic Pages">
      {section name=function loop=$numfunc}
	  	<option value="{$func[function].id}.dyn" {if $item.item == $func[function].id && $item.type == 2}selected="selected"{/if}>{$func[function].name}</option>
	  {/section}
      </optgroup>{/if}
      {if $numarticles}
      <optgroup label="Articles">
      {section name=function loop=$numarticles}
	  	<option value="{$articles[function].ID}.art" {if $item.item == $articles[function].ID && $item.type == 4}selected="selected"{/if}>{$articles[function].title}</option>
	  {/section}
      </optgroup>   {/if}
    </select></div></div><br />
    
<div id="urldiv" style="display: {if $item.type == 5 || $item == ''}'';{else}none;{/if}">
    <div class="fieldItem"><label class="label" for="url">External Address<span class="hintanchor" title="The address of the external website."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper">http://<input name="url" type="text" id="url" value="{$item.url}" class="inputbox" size="40" onblur="checkElement('url', 'text', true, 0, 0, '');" /><br /><span class="fieldError" id="urlError">Required</span>
    </div></div><br /></div>

</div>
<div class="submitWrapper">
    <input type="submit" name="Submit" value="Submit" class="button" />
    <input type="button" name="Cancel" value="Cancel" onclick="history.go(-1);" class="button" />
   </div>
   </fieldset>
   </div>
</form>
{/if}
