<h2>Scouting record of {$memberdetails.firstName} {$memberdetails.lastName}</h2>
<div align="center">
                        <ul id="navlist">
                                <!-- CSS Tabs -->
<li><a id="trooptab" {if $action=="view_advancements" || $action==""}class="current"{/if} href="{$pagename}&amp;id={$id}&amp;action=view_advancements&amp;scheme={$schemeNumber}" >View {$scoutlang.advancement_badges}</a></li>
{if $editallowed}<li><a id="websitetab" {if $action=="edit_advancements"}class="current"{/if} href="{$pagename}&amp;id={$id}&amp;action=edit_advancements">Edit {$scoutlang.advancement_badges}</a></li>{/if}
<li><a id="registrationtab"{if $action=="view_badges"}class="current"{/if} href="{$pagename}&amp;id={$id}&amp;action=view_badges&amp;scheme={$schemeNumber}" >{$scoutlang.badges}</a></li>
<li><a id="usertab" href="admin.php?page={$mainpage}" >Back to {$scoutlang.members}</a></li>
</ul>
                </div>
{if $action=="view_advancements" || $action==""}
<div align="center">
<div align="right">{$scoutlang.award_scheme}: 
<script type="text/javascript">
{literal}
function changeScheme()
{
    var index = document.getElementById('schemes').selectedIndex;
    var id = document.getElementById('schemes').options[index].value;
    {/literal}
    window.location = "admin.php?page=troop&subpage=records&id={$memberdetails.id}&scheme="+ id +"&action=view_advancements";
    {literal}
}
{/literal}
</script>
<select id="schemes" class="inputbox" onchange="changeScheme()" style="width:300px;">
{section name=num loop=$numschemes}
    <option value="{$schemes[num].id}" {if $schemeNumber == $schemes[num].id}selected="selected"{/if}>{$schemes[num].name}{if $memberdetails.awardScheme == $schemes[num].id} (Active {$scoutlang.award_scheme}){/if}</option>
{/section}
</select>
</div>
{if $numadva > 0}
<div style="border:1px solid #000;text-align:center;width:29%;float:left;">
{section name=num loop=$numadva}
<a href="#{$advan[num].advancement|replace:' ':''}">{$advan[num].advancement}</a><br />
{/section}
</div>
<table width="70%" border="0" cellspacing="1" cellpadding="4" align="center" class="table">
    {section name=num loop=$numadva}
	  {if $advan[num].numitems > 0}
      <tr><th colspan="2" class="bighead"><a name="{$advan[num].advancement|replace:' ':''}"></a>{$advan[num].advancement}</th></tr>
	  <tr>
        <th class="smallhead"></th>
		<th width="83%" scope="col" class="smallhead">Requirement</th>
	  </tr>
	  {section name=numreq loop=$advan[num].numitems}
      {assign var="id" value=$advan[num].items[numreq].ID}
		  <tr valign="top" align="center">
            {if $scoutRecord.requirement.$id == 1}<td class="complete">Completed</td>{else}<td class="notcomplete">Not Complete</td>{/if}
			<td class="text"><span style="float:left" class="hintanchor" title="{$advan[num].items[numreq].item} :: {if $advan[num].items[numreq].description}{$advan[num].items[numreq].description}{else}No description{/if}"><img src="{$tempdir}admin/images/information.png" alt="[i]"/></span><strong>{$advan[num].items[numreq].item}</strong>{if $scoutRecord.comment.$id != ""}<br />
            <span class="comments">{$scoutRecord.comment.$id}</span>{/if}</td>
		  </tr>
	  {/section}
      {/if}
	 {/section}
</table>
{else}
<div align="center">No {$scoutlang.advancement_badges}</div>
{/if}
<br style="clear:both" />
{elseif $action=="edit_advancements" && $editallowed}
  <script type="text/javascript">
   {literal}
   function checkAll(id)
   {
    switch (id)
    {
       {/literal}{section name=advancement loop=$numadva} 
        case {$advan[advancement].ID}:
            itemList = [{section name=requirement loop=$advan[advancement].numitems}'{$advan[advancement].items[requirement].ID}'{if $smarty.section.requirement.iteration < $advan[advancement].numitems},{/if}{/section}];
            number = {$advan[advancement].numitems};
            break;
        {/section}{literal}
    }
    for (i=0;i<number;i++)
    {
        document.getElementById('requirement' + itemList[i]).checked = document.getElementById('all'+id).checked;
    }
   }
   {/literal}
  </script>
{if $numadva > 0}
<div align="center"><div style="border:1px solid #000;text-align:center;width:29%;float:left;">
{section name=num loop=$numadva}
<a href="#{$advan[num].advancement|replace:' ':''}">{$advan[num].advancement}</a><br />
{/section}
</div>
	<form method="post" name="form1" action="{$editFormAction}" id="form1">
<table width="70%" border="0" cellspacing="1" cellpadding="4" align="center" class="table">
    {section name=num loop=$numadva}
	  {if $advan[num].numitems > 0}
      <tr><th colspan="2" class="bighead"><a name="{$advan[num].advancement|replace:' ':''}"></a>{$advan[num].advancement}</th></tr>
	  <tr>
        <th width="10%" class="smallhead"><input type="checkbox" value="1" id="all{$advan[num].ID}" onclick="checkAll({$advan[num].ID});" /></th>
		<th scope="col" class="smallhead">Requirement</th>
	  </tr>
	  {section name=numreq loop=$advan[num].numitems}
      {assign var="id" value=$advan[num].items[numreq].ID}
		  <tr valign="top" align="center">
            <td class="text" style="text-align:center;"><input type="checkbox" name="requirement[{$advan[num].items[numreq].ID}]" id="requirement{$advan[num].items[numreq].ID}" value="1" {if $scoutRecord.requirement.$id == 1}checked="checked"{/if} /></td>
			<td class="text"><div align="left"><span style="float:left;" class="hintanchor" title="{$advan[num].items[numreq].item} :: {if $advan[num].items[numreq].description}{$advan[num].items[numreq].description}{else}No description{/if}"><img src="{$tempdir}admin/images/information.png" alt="[i]"/></span><strong>{$advan[num].items[numreq].item}</strong><br /><strong>Comment:</strong><input type="text" class="inputbox" style="width:300px;" name="comment[{$advan[num].items[numreq].ID}]" id="comment{$advan[num].items[numreq].ID}" value="{$scoutRecord.comment.$id}"/></div></td>
		  </tr>
	  {/section}
      <tr>
      <td colspan="2" class="text"  style="text-align:center;"><input type="submit" name="Submit" value="Submit" class="button" />&nbsp;<input type="reset" name="reset" value="Reset" class="button" /></td>
      </tr>
      {/if}
	 {/section}
</table>
</form>
{else}
<div align="center">No {$scoutlang.advancement_badges}</div>
{/if}
{elseif $action=="view_badges"}
{literal}
<script type="text/javascript">
<!-- 
function description()
{
    var descriptions = new Array();
    descriptions[0] = "";
    {/literal}
    {section name=badges loop=$numavailable}
       descriptions[{$available[badges].id}] = "{$available[badges].description}";
    {/section}
    {literal}
    id = document.getElementById('bid').value;
    description_div = document.getElementById('description');
    description_div.innerHTML = '';
    description_div.innerHTML = descriptions[id];
}

function confirmDelete(articleId) {
if (confirm("This will remove this badge. Continue?"))
{/literal}
document.location = "admin.php?page=troop&subpage=records&action=delete_badge&id={$id}&bid=" + articleId;
{literal}
}

//-->
</script>
{/literal}
<div align="right">{$scoutlang.award_scheme}: 
<script type="text/javascript">
{literal}
function changeScheme()
{
    var index = document.getElementById('schemes').selectedIndex;
    var id = document.getElementById('schemes').options[index].value;
    {/literal}
    window.location = "admin.php?page=troop&subpage=records&id={$memberdetails.id}&scheme="+ id +"&action=view_badges";
    {literal}
}
{/literal}
</script>
<select id="schemes" class="inputbox" onchange="changeScheme()" style="width:300px;">
{section name=num loop=$numschemes}
    <option value="{$schemes[num].id}" {if $schemeNumber == $schemes[num].id}selected="selected"{/if}>{$schemes[num].name}{if $memberdetails.awardScheme == $schemes[num].id} (Active {$scoutlang.award_scheme}){/if}</option>
{/section}
</select>
</div>
<table width="100%" border="0" cellspacing="1" cellpadding="4" align="center" class="table">
{if $numbadge > 0}
<tr>
    <th scope="col" colspan="3" class="bighead"><a name="badges"></a>Other Badges Earned</th>
  </tr>
  <tr>
    <th width="3%" class="smallhead"></th>
    <th width="21%" class="smallhead">Date</th>
    <th width="79%" class="smallhead">Badge</th>
  </tr>
{section name=badgenum loop=$numbadge}
  <tr>
    <td class="text" style="text-align:center;">{if $editallowed}<a href="javascript:confirmDelete('{$badges[badgenum].id}')"><img src="{$tempdir}admin/images/delete.gif"  border="0" alt="Remove {$badges[badgenum].name}" title="Remove {$badges[badgenum].name}" /></a>{else}<img src="{$tempdir}admin/images/delete_grey.gif"  border="0" alt="Removing disabled" title="Removing disabled" />{/if}</td>
    <td class="text">{$badges[badgenum].date+$timeoffset|date_format:"%B %e, %Y %H:%M"}</td>
    <td class="text"><span style="float:left;" class="hintanchor" title="{$badges[badgenum].name} :: {if $badges[badgenum].description}{$badges[badgenum].description}{else}No description{/if}"><img src="{$tempdir}admin/images/information.png" alt="[i]"/></span>{$badges[badgenum].name}
    {if $badges[badgenum].comment != ""}<br /><span class="comments">{$badges[badgenum].comment}</span>{/if}</td>
  </tr>
 {/section}
 {else}
<tr><td colspan="2" class="text">{$scoutlang.member} has no {$scoutlang.badges} yet</td></tr>
{/if}
{if $editallowed && $memberdetails.awardScheme == $schemeNumber}
<tr>
<th class="smallhead" colspan="3">Add{$scoutlang.badges}</th>
</tr>
<tr>
<td class="text" colspan="3">
<form method="post" action="{$pagename}&amp;action=addbadge&amp;id={$id}">
<div class="field">
<label for="bid" class="label">{$scoutlang.badges}</label>
<div class="inputboxwrapper"><select name="bid" id="bid" class="inputbox" onChange="description();">
<option value="0">Select {$scoutlang.badges} to add</option>
{section name=badges loop=$numavailable}
<option value="{$available[badges].id}">{$available[badges].name}</option>
{/section}
</select></div><br />

<span class="label">Description</span>
<div class="inputboxwrapper" id="description" align="left">No {$scoutlang.badges} selected</div><br />

<label for="bid" class="label">Comment</label>
<div class="inputboxwrapper"><input type="text" class="inputbox" size="30" name="comment" id="comment"/></div><br />
</div>
<div class="submitWrapper"><input type="submit" class="button" value="Submit" name="Submit" id="Submit" /></div></form></td>
</tr>
{/if}
</table>
{/if}
