{literal}
  <script type="text/javascript">
<!--
function confirmschemeDelete(articleId) {
if (confirm("This will delete this award scheme. Continue?"))
document.location = "admin.php?page=advancements&action=delsch&id=" + articleId;
}
function confirmDelete(articleId, sid) {
if (confirm("This will delete this award badge. Continue?"))
document.location = "admin.php?page=advancements&action=deladd&id=" + articleId + "&sid=" + sid;
}
function confirmDelete2(articleId, ids, sid) {
if (confirm("This will delete this requirement. Continue?"))
document.location = "admin.php?page=advancements&action=delreq&rid=" + articleId + "&id=" + ids + "&sid=" + sid;
}
//-->
  </script>
  {/literal}
<h2>{$scoutlang.award_scheme} Manager</h2>
{if ($action == "")}
{if $addallowed}<div class="toplinks"><a href="{$pagename}&amp;action=newsch" title="New {$scoutlang.award_scheme}"><img src="{$tempdir}admin/images/add.png" alt="New {$scoutlang.award_scheme}" border="0" /></a>
</div>{/if}
{if $numschemes > 0}
<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-15" id="sortTable1">
<thead>
  <tr valign="top"> 
    <th width="10%" class="smallhead">&nbsp;</th>
    <th class="smallhead">{$scoutlang.award_scheme}</th>
  </tr>
  </thead>
  <tbody>
 {section name=schemeloop loop=$numschemes}
	  <tr class="text" valign="middle"> 
		<td class="text" style="text-align:center;"><a href="{$pagename}&amp;action=viewsch&amp;id={$schemes[schemeloop].id}"><img src="{$tempdir}admin/images/mod.gif" border="0" alt="View {$schemes[schemeloop].name} items" title="View {$schemes[schemeloop].name} items" /></a>&nbsp;&nbsp;{if $editallowed == 1}<a href="{$pagename}&amp;id={$schemes[schemeloop].id}&amp;action=editsch"><img src="{$tempdir}admin/images/edit.gif" border="0" alt="Edit {$schemes[schemeloop].name}" title="Edit {$schemes[schemeloop].name}" /></a>{else}<img src="{$tempdir}admin/images/edit_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;&nbsp;{if $deleteallowed == 1}<a href="javascript:confirmschemeDelete({$schemes[schemeloop].id})"><img src="{$tempdir}admin/images/delete.gif" border="0" alt="Delete {$schemes[schemeloop].name}" title="Delete {$schemes[schemeloop].name}" /></a>{else}<img src="{$tempdir}admin/images/delete_grey.gif" border="0" alt="Deleting Disabled" title="Deleting Disabled" />{/if}</td>
		<td class="text">{$schemes[schemeloop].name}</td>
	  </tr>  
	{/section}
    </tbody>
</table>
{else}
<div align="center">No Award Schemes</div>
{/if}
{elseif $action == "newsch" || $action == "editsch"}
<div align="center">
<form name="News" method="post" onsubmit="return checkForm([['adv','text',true,0,0,'']]);">
<fieldset class="formlist">
<legend>{if $action == "newsch"}New{else}Edit{/if} {$scoutlang.award_scheme}</legend>
<div class="field">    
<label for="adv" class="label">Name<span class="hintanchor" title="Enter the name of the {$scoutlang.award_scheme}."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><input name="adv" type="text" id="adv" size="40" maxlength="30" value="{$advan}" class="inputbox" onblur="checkElement('adv', 'text', true, 0, 0, '');" /><br /><span class="fieldError" id="advError">Required</span></div><br />
</div>
<div class="submitWrapper">
<input type="submit" name="Submit" value="Submit" class="button" />
<input name="Cancel" type="button" id="Cancel" value="Cancel" onclick="history.go(-1);" class="button" />
</div>
</fieldset>
</form></div>
{elseif $action == "viewsch"}
<h3>{$scheme} {$scoutlang.award_scheme}</h3>
<div id="navcontainer" align="center">

<h4 title="advance">{$scoutlang.advancement_badges}</h4>
<div id="advance">
<div class="toplinks">{if $addallowed}<a href="{$pagename}&amp;action=newadd&amp;sid={$sid}" title="Add {$scoutlang.advancement_badges}"><img src="{$tempdir}admin/images/add.png" alt="Add {$scoutlang.advancement_badges}" border="0" /></a>
{/if}<a href="{$pagename}" title="Back"><img src="{$tempdir}admin/images/back.png" alt="Back" border="0" /></a></div>
{if $numads > 0}
<table width="98%" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-15" id="sortTable2">
<thead>
  <tr valign="top"> 
    <th width="10%" class="smallhead"></th>
    <th class="smallhead">{$scoutlang.advancement_badges}</th>
    <th width="10%" class="smallhead">Position</th>
  </tr>
  </thead>
  <tbody>
 {section name=adloop loop=$numads}
	  <tr valign="middle" class="text"> 
		<td class="text" style="text-align:center;"><a href="{$pagename}&amp;action=viewadd&amp;id={$adv[adloop].ID}&amp;sid={$sid}"><img src="{$tempdir}admin/images/mod.gif" border="0" alt="View {$adv[adloop].advancement} items" title="View {$adv[adloop].advancement} items" /></a>&nbsp;&nbsp;{if $editallowed == 1}<a href="{$pagename}&amp;id={$adv[adloop].ID}&amp;action=editadd&amp;sid={$sid}"><img src="{$tempdir}admin/images/edit.gif" border="0" alt="Edit {$adv[adloop].advancement}" title="Edit {$adv[adloop].advancement}" /></a>{else}<img src="{$tempdir}admin/images/edit_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;&nbsp;{if $deleteallowed == 1}<a href="javascript:confirmDelete({$adv[adloop].ID},{$sid})"><img src="{$tempdir}admin/images/delete.gif" border="0" alt="Delete {$adv[adloop].advancement}" title="Delete {$adv[adloop].advancement}" /></a>{else}<img src="{$tempdir}admin/images/delete_grey.gif" border="0" alt="Deleting Disabled" title="Deleting Disabled" />{/if}</td>
		<td class="text">{$adv[adloop].advancement}</td>
    <td class="text" style="text-align:center;">{if $smarty.section.adloop.iteration != 1}{if $editallowed == 1}<a href="{$pagename}&amp;action=moveup&amp;id={$adv[adloop].ID}"><img src="{$tempdir}admin/images/up.gif" border="0" alt="Up" title="Move Up" /></a>{else}<img src="{$tempdir}admin/images/up_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;{/if}{if $smarty.section.adloop.iteration != $numads}{if $editallowed == 1}<a href="{$pagename}&amp;action=movedown&amp;id={$adv[adloop].ID}"><img src="{$tempdir}admin/images/down.gif" border="0" alt="Up" title="Move Down" /></a>{else}<img src="{$tempdir}admin/images/down_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}{/if}</td>
	  </tr>  
	{/section}
    </tbody>
</table>
{else}
<div align="center">No {$scoutlang.advancement_badges} in {$scheme} {$scoutlang.award_scheme}</div>
{/if}
</div>

<h4 title="other">{$scoutlang.badges}</h4>
<div id="other">
<div class="toplinks">{if $addallowed}<a href="{$pagename}&amp;action=newbadge&amp;sid={$sid}" title="Add {$scoutlang.badges}"><img src="{$tempdir}admin/images/add.png" alt="Add {$scoutlang.badges}" border="0" /></a>
{/if}<a href="{$pagename}" title="Back"><img src="{$tempdir}admin/images/back.png" alt="Back" border="0" /></a></div>
{if $numbadges > 0}
<table width="98%" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-15" id="sortTable3">
<thead>
  <tr valign="top"> 
    <th width="10%" class="smallhead"></th>
    <th class="smallhead">{$scoutlang.badges}</th>
  </tr>
  </thead>
  <tbody>
 {section name=badge loop=$numbadges}
	  <tr valign="middle" class="text"> 
		<td class="text" style="text-align:center;">{if $editallowed == 1}<a href="{$pagename}&amp;action=editbadge&amp;id={$badge[badge].id}&amp;sid={$sid}"><img src="{$tempdir}admin/images/edit.gif" border="0" alt="Edit {$badge[badge].name}" title="Edit {$badge[badge].name}" /></a>{else}<img src="{$tempdir}admin/images/edit_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;&nbsp;{if $deleteallowed == 1}<a href="javascript:confirmDeleteBadge({$badge[badge].id},{$sid})"><img src="{$tempdir}admin/images/delete.gif" border="0" alt="Delete {$badge[badge].name}" title="Delete {$badge[badge].name}" /></a>{else}<img src="{$tempdir}admin/images/delete_grey.gif" border="0" alt="Deleting Disabled" title="Deleting Disabled" />{/if}</td>
		<td class="text"><span class="hintanchor" title="Description :: {if $badge[badge].description}{$badge[badge].description}{else}No description{/if}"><img src="{$tempdir}admin/images/information.png" alt="[i]"/></span>{$badge[badge].name}</td>
	  </tr>  
	{/section}
    </tbody>
</table>
{else}
<div align="center">No {$scoutlang.badges} in {$scheme} {$scoutlang.award_scheme}</div>
{/if}
</div>
</div>
{elseif $action == "editbadge" || $action=="newbadge"}
<div align="center">
<form name="News" method="post" onsubmit="return checkForm([['name','text',true,0,0,'']]);">
<fieldset class="formlist">
<legend>{if $action == "editbadge"}Edit{else}New{/if} {$scoutlang.badges}</legend>
<div class="field">
<label for="name" class="label">Name<span class="hintanchor" title="Enter the name of this {$scoutlang.badges}."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><input name="name" id="name" type="text" {if $badge.name != ""}value="{$badge.name}"{/if} size="50" class="inputbox" onblur="checkElement('name', 'text', true, 0, 0, '');" /><br /><span class="fieldError" id="nameError">Required</span></div><br />

<label for="name" class="label">Description<span class="hintanchor" title="A short description of this {$scoutlang.badges}"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
 <div class="inputboxwrapper"><textarea name="desc" rows="10" class="inputbox">{$badge.description}</textarea></div><br />
</div>
  <div class="submitWrapper">
    <input type="submit" name="Submit" value="Submit" class="button" />
        <input name="Cancel" type="button" id="Cancel" value="Cancel" onclick="history.go(-1);" class="button"/>
   </div>
</form></div></div>
{elseif $action == "newadd" || $action=="editadd"}
<div align="center">
<form name="News" method="post" onsubmit="return checkForm([['adv','text',true,0,0,'']]);">
<fieldset class="formlist">
<legend>{if $action == "newadd"}New{else}Edit{/if} {$scoutlang.advancement_badges}</legend>
<div class="field">    
<label for="adv" class="label">Name<span class="hintanchor" title="Enter the name of the {$scoutlang.advancement_badges}."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><input name="adv" type="text" id="adv" size="40" maxlength="30" value="{$advan}" class="inputbox" onblur="checkElement('adv', 'text', true, 0, 0, '');" /><br /><span class="fieldError" id="advError">Required</span></div><br />
</div>
<div class="submitWrapper">
<input type="submit" name="Submit" value="Submit" class="button" />
<input name="Cancel" type="button" id="Cancel" value="Cancel" onclick="history.go(-1);" class="button" />
</div>
</fieldset>
</form></div>
{elseif $action=="viewadd"}
<h3>{$advan}</h3>
<div class="toplinks">{if $addallowed}<a href="{$pagename}&amp;action=newreq&amp;id={$id}&amp;sid={$sid}" title="Add Requirement"><img src="{$tempdir}admin/images/add.png" alt="Add Requirement" border="0" /></a>
{/if}<a href="{$pagename}&amp;action=viewsch&amp;id={$sid}" title="Back"><img src="{$tempdir}admin/images/back.png" alt="Back" border="0" /></a></div>
{if $numreqs > 0}
<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-15" id="sortTable4">
<thead>
 <tr valign="top">
    <th width="8%" class="smallhead"></th>
    <th class="smallhead">Requirement</th>
    <th width="10%" class="smallhead">Position</th>
  </tr>
  </thead>
  <tbody>
  {section name=adloop loop=$numreqs}
  <tr valign="middle" class="text">
    <td class="text"><div align="center" >{if $editallowed == 1}<a href="{$pagename}&amp;rid={$req[adloop].ID}&amp;action=editreq&amp;id={$id}&amp;sid={$sid}"><img src="{$tempdir}admin/images/edit.gif" border="0" alt="Edit {$req[adloop].item}" title="Edit {$req[adloop].item}" /></a>{else}<img src="{$tempdir}admin/images/edit_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;&nbsp;{if $deleteallowed == 1}<a href="javascript:confirmDelete2({$req[adloop].ID},{$id},{$sid})"><img src="{$tempdir}admin/images/delete.gif" border="0" alt="Delete {$req[adloop].item}" title="Delete {$req[adloop].item}" /></a>{else}<img src="{$tempdir}admin/images/delete_grey.gif" border="0" alt="Deleting Disabled" title="Deleting Disabled" />{/if}</div></td>
    <td class="text"><span class="hintanchor" title="Description :: {if $req[adloop].description}{$req[adloop].description}{else}No description{/if}"><img src="{$tempdir}admin/images/information.png" alt="[i]"/></span>{$req[adloop].item}</td>
    <td class="text" style="text-align:center">{if $smarty.section.adloop.iteration != 1}{if $editallowed == 1}<a href="{$pagename}&amp;action=moveitemup&amp;id={$id}&amp;rid={$req[adloop].ID}&amp;sid={$sid}"><img src="{$tempdir}admin/images/up.gif" border="0" alt="Up" title="Move Up" /></a>{else}<img src="{$tempdir}admin/images/up_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;{/if}{if $smarty.section.adloop.iteration != $numreqs}{if $editallowed == 1}<a href="{$pagename}&amp;action=moveitemdown&amp;id={$id}&amp;rid={$req[adloop].ID}&amp;sid={$sid}"><img src="{$tempdir}admin/images/down.gif" border="0" alt="Up" title="Move Down" /></a>{else}<img src="{$tempdir}admin/images/down_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}{/if}</td>
  </tr>
  {/section}
  </tbody>
</table>
{else}
<div align="center">No requirements for {$advan}</div>
{/if}
{elseif $action == "newreq" || $action=="editreq"}
<div align="center">
<form name="News" method="post" onsubmit="return checkForm([['req','text',true,0,0,'']]);">
<fieldset class="formlist">
<div class="field">
<label for="req" class="label">Name<span class="hintanchor" title="Enter the name of this requirement."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><input name="req" id="req" type="text" {if $requirement.item != ""}value="{$requirement.item}"{/if} size="50" class="inputbox"  onblur="checkElement('req', 'text', true, 0, 0, '');"/><br /><span class="fieldError" id="reqError">Required</span></div><br />

<label for="desc" class="label">Description<span class="hintanchor" title="A short description of this requirement"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><textarea name="desc" id="desc" class="inputbox" rows="10">{$requirement.description}</textarea></div><br />
</div>
  <div class="submitWrapper">
    <input type="submit" name="Submit" value="Submit" class="button" />
        <input name="Cancel" type="button" id="Cancel" value="Cancel" onclick="history.go(-1);" class="button"/>
   </div>
   </fieldset>
</form></div>
{/if}
