{literal}
  <script type="text/javascript">
<!--
function confirmDelete(articleId) {
if (confirm("This will delete this category. Continue?"))
document.location = "admin.php?page=menus&action=delcat&id=" + articleId;
}
function confirmDelete2(articleId, ids) {
if (confirm("This will delete this item (And any child items). Continue?"))
document.location = "admin.php?page=menus&action=delitem&rid=" + articleId + "&id=" + ids;
}

function show(id)
{
    document.getElementById(id).style.display = "";
}

function hide(id)
{
    document.getElementById(id).style.display = "none";
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

function showitems(id)
{
	var item = null;
    var item2 = null;
    var writediv = null;

    item = document.getElementById('sub' + id);
    item2 = document.getElementById('subhide' + id);

    if (item.style.display == "none")
    {
        item.style.display = "block";
        item2.style.display = "none";
        document.getElementById('close'+id).style.display = "block";
        document.getElementById('open'+id).style.display = "none";
    }
    else
    {
        item.style.display = "none";
        item2.style.display = "block";
        document.getElementById('close'+id).style.display = "none";
        document.getElementById('open'+id).style.display = "block";
    }

}
//-->
  </script>
  {/literal}
  <h2>Menu Manager</h2>
{if $action == "view" || $action == ""}
<div align="center"><div style="width:100%">
<div id="navcontainer" align="center">
    <ul class="mootabs_title">
        <li title="left">Left Menu</li>
        <li title="right">Right Menu</li>
        <li title="top">Top Menu</li>
    </ul>

<div id="left" class="mootabs_panel">
<div class="toplinks">{if $addallowed}<a href="{$pagename}&amp;action=newcat&amp;side=left" title="Add menu"><img src="{$tempdir}admin/images/add.png" alt="Add menu" border="0" /></a>
{/if}{if $editallowed}<a href="{$pagename}&amp;action=fixcat" title="Fix Positions"><img src="{$tempdir}admin/images/fix.png" alt="Fix Positions" border="0" /></a>
{/if}</div>
<table width="98%" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-15" id="sortTable">
<thead>
  <tr>
    <th width="10%" scope="col" class="smallhead"></th>
    <th width="5%" class="smallhead">Publish</th>
    <th scope="col" class="smallhead">Name</th>
    <th width="5%" scope="col" class="smallhead">Items</th>
    <th width="10%" scope="col" class="smallhead">Position</th>
  </tr>
  </thead>
  <tbody>
  {section name=cats loop=$numleft}
  <tr class="text">
    <td class="text"><div align="center"><a href="{$pagename}&amp;action=catview&amp;id={$left[cats].id}" title="Open {$left[cats].name}"><img src="{$tempdir}admin/images/mod.gif" border="0" alt="Open {$left[cats].name}" /></a>&nbsp;&nbsp;{if $editallowed}<a href="admin.php?page=menus&amp;action=editcat&amp;id={$left[cats].id}" title="Edit {$left[cats].name}"><img src="{$tempdir}admin/images/edit.gif" border="0" alt="Edit {$left[cats].name}" /></a>{else}<img src="{$tempdir}admin/images/edit_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;&nbsp;{if $deleteallowed}<a href="javascript:confirmDelete({$left[cats].id})" title="Delete {$left[cats].name}"><img src="{$tempdir}admin/images/delete.gif" border="0" alt="Delete {$left[cats].name}" /></a>{else}<img src="{$tempdir}admin/images/delete_grey.gif" border="0" alt="Deleting Disabled" title="Deleting Disabled" />{/if}</div></td>
    <td class="text"><div align="center">{if $publishallowed}{if $left[cats].published == 0}<a href="admin.php?page=menus&amp;action=publish&amp;id={$left[cats].id}&amp;activetab=left"><img src="{$tempdir}admin/images/publish.png" border="0" alt="Publish {$left[cats].name}" title="Publish {$left[cats].name}" /></a>{else}<a href="admin.php?page=menus&amp;action=unpublish&amp;id={$left[cats].id}&amp;activetab=left"><img src="{$tempdir}admin/images/unpublish.png" border="0" alt="Unpublish {$left[cats].name}" title="Unpublish {$left[cats].name}" /></a>{/if}{else}{if $left[cats].published == 0}<img src="{$tempdir}admin/images/publish_grey.gif" border="0" alt="Not allowed to publish" title="Not allowed to publish" />{else}<img src="{$tempdir}admin/images/unpublish_grey.gif" border="0" alt="Not allowed to unpublish" title="Not allowed to unpublish" />{/if}{/if}</div></td>
    <td class="text">{$left[cats].name}</td>
    <td class="text">{$left[cats].numitems}</td>
    <td class="text"><div align="center">{if $smarty.section.cats.iteration != 1}{if $editallowed}<a href="{$pagename}&amp;action=moveup&amp;id={$left[cats].id}"><img src="{$tempdir}admin/images/up.gif" border="0" alt="Up" title="Move Up" /></a>{else}<img src="{$tempdir}admin/images/up_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}{/if}{if $smarty.section.cats.iteration != $numleft}{if $editallowed}<a href="{$pagename}&amp;action=movedown&amp;id={$left[cats].id}"><img src="{$tempdir}admin/images/down.gif" border="0" alt="Up" title="Move Down" /></a>{else}<img src="{$tempdir}admin/images/down_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}{/if}</div></td>
  </tr>
  {sectionelse}
  <tr>
  <td class="text" colspan="5">
   No menus on this side
  </td>
  </tr>
  {/section}
  </tbody>
  </table>
</div>

<div id="right" class="mootabs_panel">
<div class="toplinks">{if $addallowed}<a href="{$pagename}&amp;action=newcat&amp;side=right" title="Add menu"><img src="{$tempdir}admin/images/add.png" alt="Add menu" border="0" /></a>
{/if}{if $editallowed}<a href="{$pagename}&amp;action=fixcat" title="Fix Positions"><img src="{$tempdir}admin/images/fix.png" alt="Fix Positions" border="0" /></a>{/if}</div>
<table width="98%" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-15" id="sortTable1">
<thead>
  <tr>
    <th width="10%" scope="col" class="smallhead"></th>
    <th width="5%" class="smallhead">Publish</th>
    <th scope="col" class="smallhead">Name</th>
    <th width="5%" scope="col" class="smallhead">Items</th>
    <th width="10%" scope="col" class="smallhead">Position</th>
  </tr>
  </thead>
  <tbody>
  {section name=cats loop=$numright}
  <tr class="text">
    <td class="text"><div align="center"><a href="{$pagename}&amp;action=catview&amp;id={$right[cats].id}" title="Open {$right[cats].name}"><img src="{$tempdir}admin/images/mod.gif" border="0" alt="Open {$right[cats].name}" /></a>&nbsp;&nbsp;{if $editallowed}<a href="admin.php?page=menus&amp;action=editcat&amp;id={$right[cats].id}" title="Edit {$right[cats].name}"><img src="{$tempdir}admin/images/edit.gif" border="0" alt="Edit {$right[cats].name}" /></a>{else}<img src="{$tempdir}admin/images/edit_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;&nbsp;{if $deleteallowed}<a href="javascript:confirmDelete({$right[cats].id})" title="Delete {$right[cats].name}"><img src="{$tempdir}admin/images/delete.gif" border="0" alt="Delete {$right[cats].name}" /></a>{else}<img src="{$tempdir}admin/images/delete_grey.gif" border="0" alt="Deleting Disabled" title="Deleting Disabled" />{/if}</div></td>
    <td class="text"><div align="center">{if $publishallowed}{if $right[cats].published == 0}<a href="admin.php?page=menus&amp;action=publish&amp;id={$right[cats].id}&amp;activetab=right"><img src="{$tempdir}admin/images/publish.png" border="0" alt="Publish {$right[cats].name}" title="Publish {$right[cats].name}" /></a>{else}<a href="admin.php?page=menus&amp;action=unpublish&amp;id={$right[cats].id}&amp;activetab=right"><img src="{$tempdir}admin/images/unpublish.png" border="0" alt="Unpublish {$right[cats].name}" title="Unpublish {$right[cats].name}" /></a>{/if}{else}{if $right[cats].published == 0}<img src="{$tempdir}admin/images/publish_grey.gif" border="0" alt="Not allowed to publish" title="Not allowed to publish" />{else}<img src="{$tempdir}admin/images/unpublish_grey.gif" border="0" alt="Not allowed to unpublish" title="Not allowed to unpublish" />{/if}{/if}</div></td>
    <td class="text">{$right[cats].name}</td>
    <td class="text">{$right[cats].numitems}</td>
    <td class="text"><div align="center">{if $smarty.section.cats.iteration != 1}{if $editallowed}<a href="{$pagename}&amp;action=moveup&amp;id={$right[cats].id}"><img src="{$tempdir}admin/images/up.gif" border="0" alt="Up" title="Move Up" /></a>{else}<img src="{$tempdir}admin/images/up_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}{/if}{if $smarty.section.cats.iteration != $numright}{if $editallowed}<a href="{$pagename}&amp;action=movedown&amp;id={$right[cats].id}"><img src="{$tempdir}admin/images/down.gif" border="0" alt="Up" title="Move Down" /></a>{else}<img src="{$tempdir}admin/images/down_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}{/if}</div></td>
  </tr> 
  {sectionelse}
  <tr>
  <td class="text" colspan="5">
   No menus on this side
  </td>
  </tr>
  {/section}
    </tbody>
  </table>
</div>

<div id="top" class="mootabs_panel">
<div class="toplinks">{if $addallowed}<a href="{$pagename}&amp;action=newcat&amp;side=top" title="Add menu"><img src="{$tempdir}admin/images/add.png" alt="Add menu" border="0" /></a>
{/if}{if $editallowed}<a href="{$pagename}&amp;action=fixcat" title="Fix Positions"><img src="{$tempdir}admin/images/fix.png" alt="Fix Positions" border="0" /></a>
{/if}</div>
<table width="98%" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-15" id="sortTable2">
<thead>
  <tr>
    <th width="10%" class="smallhead"></th>
    <th width="5%" class="smallhead">Publish</th>
    <th scope="col" class="smallhead">Name</th>
    <th width="5%" scope="col" class="smallhead">Items</th>
    <th width="10%" scope="col" class="smallhead">Position</th>
  </tr>
  </thead>
  <tbody>
  {section name=cats loop=$numtop}
  <tr class="text">
    <td class="text"><div align="center"><a href="{$pagename}&amp;action=catview&amp;id={$top[cats].id}" title="Open {$top[cats].name}"><img src="{$tempdir}admin/images/mod.gif" border="0" alt="Open {$top[cats].name}" /></a>&nbsp;&nbsp;{if $editallowed}<a href="admin.php?page=menus&amp;action=editcat&amp;id={$top[cats].id}" title="Edit {$top[cats].name}"><img src="{$tempdir}admin/images/edit.gif" border="0" alt="Edit {$top[cats].name}" /></a>{else}<img src="{$tempdir}admin/images/edit_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;&nbsp;{if $deleteallowed}<a href="javascript:confirmDelete({$top[cats].id})" title="Delete {$top[cats].name}"><img src="{$tempdir}admin/images/delete.gif" border="0" alt="Delete {$top[cats].name}" /></a>{else}<img src="{$tempdir}admin/images/delete_grey.gif" border="0" alt="Deleting Disabled" title="Deleting Disabled" />{/if}</div></td>
    <td class="text"><div align="center">{if $publishallowed}{if $top[cats].published == 0}<a href="admin.php?page=menus&amp;action=publish&amp;id={$top[cats].id}&amp;activetab=top"><img src="{$tempdir}admin/images/publish.png" border="0" alt="Publish {$top[cats].name}" title="Publish {$top[cats].name}" /></a>{else}<a href="admin.php?page=menus&amp;action=unpublish&amp;id={$top[cats].id}&amp;activetab=top"><img src="{$tempdir}admin/images/unpublish.png" border="0" alt="Unpublish {$top[cats].name}" title="Unpublish {$top[cats].name}" /></a>{/if}{else}{if $top[cats].published == 0}<img src="{$tempdir}admin/images/publish_grey.gif" border="0" alt="Not allowed to publish" title="Not allowed to publish" />{else}<img src="{$tempdir}admin/images/unpublish_grey.gif" border="0" alt="Not allowed to unpublish" title="Not allowed to unpublish" />{/if}{/if}</div></td>
    <td class="text">{$top[cats].name}</td>
    <td class="text">{$top[cats].numitems}</td>
    <td class="text"><div align="center">{if $smarty.section.cats.iteration != 1}{if $editallowed}<a href="{$pagename}&amp;action=moveup&amp;id={$top[cats].id}"><img src="{$tempdir}admin/images/up.gif" border="0" alt="Up" title="Move Up" /></a>{else}<img src="{$tempdir}admin/images/up_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}{/if}{if $smarty.section.cats.iteration != $numtop}{if $editallowed}<a href="{$pagename}&amp;action=movedown&amp;id={$top[cats].id}"><img src="{$tempdir}admin/images/down.gif" border="0" alt="Up" title="Move Down" /></a>{else}<img src="{$tempdir}admin/images/down_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}{/if}</div></td>
  </tr>
      {sectionelse}
  <tr>
  <td class="text" colspan="5">
   No menus on this side
  </td>
  </tr>
  {/section}
  </tbody>
</table>
</div>
</div></div></div>
  {elseif $action == "catview"}<div class="toplinks">{if $addallowed}<a href="{$pagename}&amp;action=newitem&amp;id={$menu.id}" title="Add item"><img src="{$tempdir}admin/images/add.png" alt="Add item" border="0" /></a>
{/if}<a href="{$pagename}&amp;activetab={$menu.side}" title="Back"><img src="{$tempdir}admin/images/back.png" alt="Back" border="0" /></a></div>
  {if $numitems > 0}
<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-15" id="sortTable3">
<thead>
  <tr>
    <th width="10%" scope="col" class="smallhead"></th>
    <th scope="col" class="smallhead">Name</th>
    <th width="41%" scope="col" class="smallhead">Action of item</th>
    <th width="10%" scope="col" class="smallhead">Position</th>
  </tr>
  </thead>
  <tbody>
{section name=items loop=$numitems}
  <tr class="text">
    <td class="text"><div align="center">{if $editallowed}<a href="{$pagename}&amp;action=edititem&amp;cid={$menu.id}&amp;id={$item[items].id}" title="Edit {$item[items].name}"><img src="{$tempdir}admin/images/edit.gif" border="0" alt="Edit {$item[items].name}" /></a>{else}<img src="{$tempdir}admin/images/edit_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;&nbsp;{if $editallowed}<a href="{$pagename}&amp;action=moveitem&amp;cid={$menu.id}&amp;id={$item[items].id}" title="Move {$item[items].name} to a different menu"><img src="{$tempdir}admin/images/move.gif" border="0" alt="Move {$item[items].name} to a different menu" /></a>{else}<img src="{$tempdir}admin/images/move_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;&nbsp;{if $deleteallowed}<a href="javascript:confirmDelete2({$item[items].id},{$menu.id})" title="Delete {$item[items].name}"><img src="{$tempdir}admin/images/delete.gif" border="0" alt="Delete {$item[items].name}"  /></a>{else}<img src="{$tempdir}admin/images/delete_grey.gif" border="0" alt="Deleting Disabled" title="Deleting Disabled" />{/if}</div></td>
    <td class="text"><div align="left">{$item[items].name}</div></td>
    <td class="text">{$item[items].action}</td>
    <td class="text"><div align="center">{if $smarty.section.items.iteration != 1}{if $editallowed}<a href="{$pagename}&amp;action=moveitemup&amp;id={$item[items].id}&amp;cid={$menu.id}"><img src="{$tempdir}admin/images/up.gif" border="0" alt="Up" title="Move Up" /></a>{else}<img src="{$tempdir}admin/images/up_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;{/if}{if $smarty.section.items.iteration != $numitems}{if $editallowed}<a href="{$pagename}&amp;action=moveitemdown&amp;id={$item[items].id}&amp;cid={$menu.id}"><img src="{$tempdir}admin/images/down.gif" border="0" alt="Up" title="Move Down" /></a>{else}<img src="{$tempdir}admin/images/down_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}{/if}</div></td>
  </tr>
  {if $item[items].subnumber > 0}
  <tr>
   <td class="text" valign="top"><div align="center"><div id="open{$item[items].id}"><a href="javascript:showitems('{$item[items].id}');" class="hintanchor2"><img src="{$tempdir}admin/images/close.png" title="[+]" border="0"/></a></div><div id="close{$item[items].id}" style="display:none;"><a href="javascript:showitems('{$item[items].id}');" class="hintanchor2"><img src="{$tempdir}admin/images/open.png" title="[-]" border="0"/></a></div></div></td>
   <td colspan="3" class="text">
   <div id="subhide{$item[items].id}" style="display:'';">
    <div align="left">&nbsp;Item has <b>{$item[items].subnumber}</b> sub menu item(s).</div>
   </div>
   <div id="sub{$item[items].id}" style="display:none;">
    <strong><div align="left">Sub menu item(s)</div></strong>
    <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt" id="sortTable3">
<thead>
      <tr>
    <th width="8%" scope="col" class="smallhead"></th>
    <th scope="col" class="smallhead">Name</th>
    <th width="40%" scope="col" class="smallhead">Action of item</th>
    <th width="10%" scope="col" class="smallhead">Position</th>
  </tr>
  </thead>
  <tbody>
  {section name=subitem loop=$item[items].subnumber}
  <tr class="text">
    <td class="text"><div align="center">{if $editallowed}<a href="{$pagename}&amp;action=edititem&amp;cid={$menu.id}&amp;id={$item[items].subitems[subitem].id}"><img src="{$tempdir}admin/images/edit.gif" border="0" alt="Edit {$item[items].subitems[subitem].name}" title="Edit {$item[items].subitems[subitem].name}" /></a>{else}<img src="{$tempdir}admin/images/edit_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;&nbsp;{if $deleteallowed}<a href="javascript:confirmDelete2({$item[items].subitems[subitem].id},{$menu.id})"><img src="{$tempdir}admin/images/delete.gif" border="0" alt="Delete {$item[items].subitems[subitem].name}" title="Delete {$item[items].subitems[subitem].name}" /></a>{else}<img src="{$tempdir}admin/images/delete_grey.gif" border="0" alt="Deleting Disabled" title="Deleting Disabled" />{/if}</div></td>
    <td class="text">{$item[items].subitems[subitem].name}</td>
    <td class="text">{$item[items].subitems[subitem].action}</td>
    <td class="text"><div align="center">{if $smarty.section.subitem.iteration != 1}<a href="{$pagename}&amp;action=moveitemup&amp;id={$item[items].subitems[subitem].id}&amp;cid={$menu.id}"><img src="{$tempdir}admin/images/up.gif" border="0" alt="Up" title="Move Up" /></a>&nbsp;{/if}{if $smarty.section.subitem.iteration != $item[items].subnumber}<a href="{$pagename}&amp;action=moveitemdown&amp;id={$item[items].subitems[subitem].id}&amp;cid={$menu.id}"><img src="{$tempdir}admin/images/down.gif" border="0" alt="Up" title="Move Down" /></a>{/if}</div></td>
  </tr>
   {/section}
   </tbody>
   </table>
   </div>
   </td>
   </tr>
  {/if}
 {/section}
 </tbody>
</table>
{else}
<div align="center">No menu items in {$menu.name}</div>
{/if}
  {elseif $action == "newcat" || $action == "editcat"}
  <script type="text/javascript">
{include file="../scripts/validator.tpl"}
</script>
{literal}
  <script type="text/javascript">
<!--
function checkAll(type) 
{
    {/literal}itemList = [{section name=groups loop=$numgroups}'{$group[groups].id}'{if $smarty.section.groups.iteration <$numgroups},{/if}{/section}];
    number = {$numgroups};{literal}

    for (i=0;i<number;i++)
    {
        document.getElementById(type + itemList[i]).checked = document.getElementById('all'+type).checked;
    }
}
//-->
</script>
  {/literal}  
  <div align="center">
<form name="form1" method="post" action="" onsubmit="return checkForm([['name','text',true,0,0,'']]);">
  <fieldset class="formlist">
  <legend>{if $action == "newcat"}Add{else}Edit{/if} Menu</legend>
  <div class="field">
  <label for="name" class="label">Name of Category<span class="hintanchor" title="Name of the menu."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper"><input name="name" type="text" id="name" value="{$menu.name}" class="inputbox" onblur="checkElement('name', 'text', true, 0, 0, '');" /><br /><span class="fieldError" id="nameError">Required</span></div><br />
    
    <label for="location" class="label">Location<span class="hintanchor" title="The location of this menu."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper">
    <select name="location" id="location" class="inputbox">
        <option value="left" {if $menu.side == 'left' ||  $menu.side == '' || $side=='left'}selected="selected"{/if}>Left Hand Menu</option>
        <option value="right" {if $menu.side == 'right' || $side=='right'}selected="selected"{/if}>Right Hand Menu</option>
        <option value="top" {if $menu.side == 'top' || $side=='top'}selected="selected"{/if}>Top Menu</option>
    </select></div><br />
    
    
    <label for="show" class="label">Heading<span class="hintanchor" title="Should the category name be shown as the heading."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
        <div class="inputboxwrapper">
    <select name="show" id="show" class="inputbox">
        <option value="1" {if $menu.showhead == 1 ||  $menu.showhead == ''}selected="selected"{/if}>Show Heading</option>
        <option value="0" {if $menu.showhead == 0}selected="selected"{/if}>Hide Heading</option>
    </select></div><br />    
    
    <label for="showperm" class="label">Show<span class="hintanchor" title="Under what circumstances should this menu be shown"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper">
    <select name="showperm" id="showperm" class="inputbox">
        <option value="0" {if $menu.showwhen == 0}selected="selected"{/if}>Always Show</option>
        <option value="1" {if $menu.showwhen == 1}selected="selected"{/if}>Show when logged in</option>
        <option value="2" {if $menu.showwhen == 2}selected="selected"{/if}>Show when logged out</option>
    </select></div><br />    
    
    <label for="expanded" class="label">Sub menu items<span class="hintanchor" title="Under what circumstances should submenu items be shown."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper">
    <select name="expanded" id="expanded" class="inputbox">
        <option value="1" {if $menu.expanded == 1}selected="selected"{/if}>Always show</option>
        <option value="0" {if $menu.expanded == 0}selected="selected"{/if}>Only show when parent item is active</option>
    </select></div><br />

      <span class="label">Groups<span class="hintanchor" title="Groups that can see this menu. Only applies if the menu is set to only show when logged in."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></span>
       <div class="inputboxwrapper"><ul class="checklist">
      <li><label for="allgroups"><input type="checkbox" value="1" id="allgroups" onclick="checkAll('groups');" />Select/Unselect All</label></li>
      {section name=groups loop=$numgroups}
        {assign var="id" value=$group[groups].id}
        <li><label for="groups{$group[groups].id}"><input type="checkbox" value="1" name="groups[{$group[groups].id}]" id="groups{$group[groups].id}" {if $menu.groups.$id == 1}checked="checked"{/if} />{$group[groups].teamname}</label></li>
      {/section}
      </ul></div><br />

    <div class="submitWrapper"><input type="submit" name="Submit" value="Submit" class="button" />
     <input name="Cancel" type="button" id="Cancel" value="Cancel" onclick="history.go(-1);" class="button" /></div>
</form>
</div>
{elseif $action == "newitem" || $action == "edititem"}
  <script type="text/javascript">
{include file="../scripts/validator.tpl"}
</script>
   <div align="center">
<form name="form2" method="post" action="" onsubmit="return checkForm([['name','text',true,0,0,'']]);">
<fieldset class="formlist">
<legend>{if $action == "newitem"}Add{else}Edit{/if} Item</legend>
<div class="field">
<label for="name" class="label">Name<span class="hintanchor" title="Name of menu item."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><input name="name" type="text" id="name" value="{$item.name}" class="inputbox" size="50" onblur="checkElement('name', 'text', true, 0, 0, '');" /><br /><span class="fieldError" id="nameError">Required</span></div><br />
    
    {if $numparents > 0}
    <label for="parent" class="label">Parent<span class="hintanchor" title="Parent menu item, makes this item a sub menu item."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper"><select name="parent" id="parent" class="inputbox">
    <option value="0" {if $item.parent == '' || $item.parent == 'top'}selected="selected"{/if}>Top</option>
    {section name=parents loop=$numparents}
     <option value="{$parent[parents].id}" {if $item.parent == $parent[parents].id}selected="selected"{/if}>{$parent[parents].name}</option>
    {/section}
    </select></div><br />
    {/if}
     <label for="items" class="label">Item<span class="hintanchor" title="Location of link."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label> 
    <div class="inputboxwrapper"><select name="items" id="items" class="inputbox" onchange="itemss();">
      <option value="url" {if $item.type == 5 || $item == ''}selected="selected"{/if}>External Link</option>
      <optgroup label="Static Pages">
      {section name=pages loop=$numpages}
        <option value="{$page[pages].id}.stat" {if $item.item == $page[pages].id && $item.type == 1}selected="selected"{/if}>{if $page[pages].friendly == ""}{$page[pages].name}{else}{$page[pages].friendly}{/if}</option> 
     {/section}	  
     </optgroup>
    <optgroup label="Dynamic Pages">
      {section name=function loop=$numfunc}
      {if $func[function].type == 2}<option value="{$func[function].id}.dyn" {if $item.item == $func[function].id && $item.type == 2}selected="selected"{/if}>{$func[function].name}</option>{/if}
      {/section}
      </optgroup>
     <optgroup label="Articles">
      {section name=pages loop=$numarts}
      <option value="{$articles[pages].ID}.art" {if $item.item == $articles[pages].ID && $item.type == 6}selected="selected"{/if}>{$articles[pages].title}</option> 
     {/section}	  
     </optgroup>
     <optgroup label="Sub Sites">
      {section name=pages loop=$numsub}
      <option value="{$subsite[pages].id}.sub" {if $item.item == $subsite[pages].id && $item.type == 4}selected="selected"{/if}>{$subsite[pages].name}</option> 
     {/section}	  
     </optgroup>
     <optgroup label="Group Sites">
      {section name=pages loop=$numgroups}
      <option value="{$groups[pages].id}.group" {if $item.item == $groups[pages].id && $item.type == 7}selected="selected"{/if}>{$groups[pages].teamname}</option> 
     {/section}	  
     </optgroup>
    <optgroup label="Side Boxes">
      {section name=function loop=$numfunc}
      {if $func[function].type == 1}<option value="{$func[function].id}.box" {if $item.item == $func[function].id && $item.type == 3}selected="selected"{/if}>{$func[function].name}</option>{/if}
      {/section}
      </optgroup>
    </select></div><br />
    
     <div id="urldiv" style="display: {if $item.type != 5 && $action == 'edititem'}none;{else}'';{/if}">
     <label for="url" class="label">External Address<span class="hintanchor" title="The address of the external website."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label> 
     <div class="inputboxwrapper">http://<input name="url" type="text" id="url" value="{if $item.type==5}{$item.item}{/if}" class="inputbox" size="50" onblur="checkElement('url', 'text', true, 0, 0, '');" /><br /><span class="fieldError" id="urlError">Required</span></div><br />
     </div>
     
    <label for="target" class="label">Target<span class="hintanchor" title="How should the link be opened."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper"><select name="target" id="target" class="inputbox">
    <option value="" {if $item.target == ''}selected="selected"{/if}>Same Window</option>
    <option value="_blank" {if $item.target == '_blank'}selected="selected"{/if}>New Window</option>
    </select></div><br />
    </div>
    
    <div class="submitWrapper"><input type="submit" name="Submit" value="Submit" class="button" />
    <input name="Cancel" type="button" id="Cancel" value="Cancel" onclick="history.go(-1);" class="button" /></div>
  
   </fieldset>
</form>
 </div>
{elseif $action=="moveitem"}
<div align="center">
 <form method="post" action="{$editFormAction}" name="form">
 <fieldset class="formlist">
 <legend>Move item</legend>
 <div class="field">
 <label for="newcat" class="label">Location<span class="hintanchor" title="Where do you want to move the menu item too."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
 <div class="inputboxwrapper"><select name="newcat" class="inputbox">
  {section name=loop loop=$numcats}
   {if $cats[loop].id != $cat.id}<option value="{$cats[loop].id}">{$cats[loop].name}</option>{/if}
  {/section}
 </select></div><br />
 </div>
 <div class="submitWrapper">
 <input type="Submit" name="Submit" id="Submit" value="Move" class="button" />&nbsp;<input type="button" value="Cancel" id="cancel" name="cancel" onclick="window.location = '{$pagename}'" class="button" /></div>
 </fieldset>
 </form></div>
{/if}
