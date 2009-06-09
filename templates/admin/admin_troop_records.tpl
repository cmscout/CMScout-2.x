<h2>Advancement Records</h2>
<div align="center">
                        <ul id="navlist">
                                <!-- CSS Tabs -->
<li><a id="trooptab" {if $action==""}class="current"{/if} href="{$pagename}&amp;scheme={$schemeNumber}" >View {$scoutlang.advancement_badges}</a></li>
{if $editallowed}<li><a id="websitetab" {if $action=="edit_advancements"}class="current"{/if} href="{$pagename}&amp;action=edit_advancements&amp;scheme={$schemeNumber}">Edit {$scoutlang.advancement_badges}</a></li>{/if}
<li><a id="registrationtab"{if $action=="view_badges"}class="current"{/if} href="{$pagename}&amp;action=view_badges&amp;scheme={$schemeNumber}" >{$scoutlang.badges}</a></li>
</ul>
                </div>
{if $action==""}
<div align="center">
<div align="left">{$scoutlang.award_scheme}: 
<script type="text/javascript">
<!--
{literal}
function changeScheme()
{
    var index = document.getElementById('schemes').selectedIndex;
    var id = document.getElementById('schemes').options[index].value;
    {/literal}
    window.location = "admin.php?page=troop_records&scheme="+ id;
    {literal}
}
{/literal}
//-->
</script>
<select id="schemes" class="inputbox" onchange="changeScheme()" style="width:300px;">
{section name=num loop=$numschemes}
    <option value="{$schemes[num].id}" {if $schemeNumber == $schemes[num].id}selected="selected"{/if}>{$schemes[num].name}{if $memberdetails.awardScheme == $schemes[num].id} (Active {$scoutlang.award_scheme}){/if}</option>
{/section}
</select>
</div>
{if $numadva > 0}
<table style="width:100%;" cellpadding="0" cellspacing="0" border="0" align="center" class="table sortable-onload-1 rowstyle-alt paginate-15" id="sortTable">
<thead>
<tr>
    <th colspan="2" class="bighead"></th>
    {section name=num loop=$numadva}
	  {if $advan[num].numitems > 0}
      <th colspan="{$advan[num].numitems}" class="bighead" style="border-right:5px solid #335368;"><a name="{$advan[num].advancement|replace:' ':''}"></a>{$advan[num].advancement}</th>
     {/if}
     {/section}
     </tr>
     <tr>
     <th class="smallhead sortable" style="width:13em;padding:5px;">Last</th>
     <th class="smallhead sortable" style="width:13em;padding:5px;">First</th>
{section name=num loop=$numadva}
	  {section name=numreq loop=$advan[num].numitems}
			<th class="smallhead" style="vertical-align:bottom;width:35px;height:35px;{if $smarty.section.numreq.iteration == $advan[num].numitems}border-right:5px solid #335368;{/if}"><span class="hintanchor" title="{$advan[num].items[numreq].item} :: {if $advan[num].items[numreq].description}{$advan[num].items[numreq].description}{else}No description{/if}"><img src="{$tempdir}admin/images/information.png" alt="[i]"/></span></th>
	  {/section}
	 {/section}    
     </tr>
     </thead>
     <tbody>
     {section name=members loop=$nummembers}
     <tr class="text">
     <td class="text">{$member[members].lastName}</td>
     <td class="text">{$member[members].firstName}</td>
    {section name=num loop=$numadva}
	  {section name=numreq loop=$advan[num].numitems}
            {assign var="id" value=$advan[num].items[numreq].ID}
			<td {if $member[members].require.$id}class="complete"{else}class="notcomplete"{/if} style="width:35px;height:35px;{if $smarty.section.numreq.iteration == $advan[num].numitems}border-right:5px solid #335368;{/if}">{if $member[members].comment.$id}<span class="hintanchor" title="{$advan[num].items[numreq].item} :: {$member[members].comment.$id}"><img src="{$tempdir}admin/images/information.png" alt="[i]"/></span>{else}&nbsp;{/if}</td>
	  {/section}
	 {/section}    
     </tr>
     {/section}
     </tbody>
</table>
</div>
{else}
<div align="center">No {$scoutlang.advancement_badges}</div>
{/if}
{elseif $action=="edit_advancements" && $editallowed}
<div align="center">
<div align="left">{$scoutlang.award_scheme}: 
<script type="text/javascript">
{literal}
function changeScheme()
{
    var index = document.getElementById('schemes').selectedIndex;
    var id = document.getElementById('schemes').options[index].value;
    {/literal}
    window.location = "admin.php?page=troop_records&action=edit_advancements&scheme="+ id;
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
  <script type="text/javascript">
   {literal}
   function all(thingy)
   {
      if (document.getElementById("selectall[" + thingy + "]").checked == true)
      {
       {/literal} 
     {section name=members loop=$nummembers}
                document.getElementById('requirement[{$member[members].id}][' + thingy+ "]").checked=true;
	  {/section} 
        {literal}
      }
      else if (document.getElementById("selectall[" + thingy + "]").checked == false)
      {
       {/literal} 
     {section name=members loop=$nummembers}
                document.getElementById('requirement[{$member[members].id}][' + thingy+ "]").checked=false;
	  {/section} 
        {literal}
      }
   }
   {/literal}
   </script>
<form method="post" name="form1" action="{$editFormAction}" id="form1">
<table style="width:100%;" cellpadding="0" cellspacing="0" border="0" align="center" class="table sortable-onload-1 rowstyle-alt paginate-15" id="sortTable">
<thead>
<tr>
    <th colspan="2" class="bighead">&nbsp;</th>
    {section name=num loop=$numadva}
	  {if $advan[num].numitems > 0}
      <th colspan="{$advan[num].numitems}" class="bighead" style="border-right:5px solid #335368;"><a name="{$advan[num].advancement|replace:' ':''}"></a>{$advan[num].advancement}</th>
     {/if}
     {/section}
     </tr>
     <tr>
     <th class="smallhead sortable" style="width:13em;padding:5px;">Last</th>
     <th class="smallhead sortable" style="width:13em;padding:5px;">First</th>
{section name=num loop=$numadva}
	  {section name=numreq loop=$advan[num].numitems}
			<th class="smallhead" style="width:35px;height:35px;{if $smarty.section.numreq.iteration == $advan[num].numitems}border-right:5px solid #335368;{/if}"><span class="hintanchor" title="{$advan[num].items[numreq].item} :: {if $advan[num].items[numreq].description}{$advan[num].items[numreq].description}{else}No description{/if}"><img src="{$tempdir}admin/images/information.png" alt="[i]"/></span></th>
	  {/section}
	 {/section}    
     </tr>
          <tr>
     <th class="smallhead sortable" style="width:13em;padding:5px;"></th>
     <th class="smallhead sortable" style="width:13em;padding:5px;"></th>
{section name=num loop=$numadva}
	  {section name=numreq loop=$advan[num].numitems}
	  {assign var="id" value=$advan[num].items[numreq].ID}
			<th class="smallhead" style="width:35px;height:35px;{if $smarty.section.numreq.iteration == $advan[num].numitems}border-right:5px solid #335368;{/if}">
			<input value="1" class="inputbox" type="checkbox" name="selectall[{$id}]" id="selectall[{$id}]" onclick="all({$id});" />
			</th>
	  {/section}
	 {/section}    
     </tr>
     </thead><tbody>
     {section name=members loop=$nummembers}
     <tr class="text">
     <td class="text" valign="middle">{$member[members].lastName}</td>
     <td class="text" valign="middle">{$member[members].firstName}</td>
    {section name=num loop=$numadva}
	  {section name=numreq loop=$advan[num].numitems}
            {assign var="id" value=$advan[num].items[numreq].ID}
			<td class="text" style="width:35px;height:35px;{if $smarty.section.numreq.iteration == $advan[num].numitems}border-right:5px solid #335368;{/if}" valign="middle"><input value="1" class="inputbox" type="checkbox" name="requirement[{$member[members].id}][{$id}]" id="requirement[{$member[members].id}][{$id}]" {if $member[members].require.$id}checked="checked"{/if} /></td>
	  {/section}
	 {/section}    
     </tr>
     {/section}</tbody>
</table>
<div align="left"><input type="submit" name="Submit" value="Submit" class="button" /></div>
</form>
{else}
<div align="center">No {$scoutlang.advancement_badges}</div>
{/if}
</div>
{elseif $action=="view_badges"}
<div align="left">{$scoutlang.award_scheme}: 
<script type="text/javascript">
{literal}
function changeScheme()
{
    var index = document.getElementById('schemes').selectedIndex;
    var id = document.getElementById('schemes').options[index].value;
    {/literal}
    window.location = "admin.php?page=troop_records&scheme="+ id +"&action=view_badges";
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
{if $nummembers > 0}
{section name=member loop=$nummembers}
<h3>{$memberBadges[member].lastName}, {$memberBadges[member].firstName}</h3>
<ul>
{section name=badges loop=$memberBadges[member].numbadge}
    <li><span class="comments"><b>Date Added: </b>{$memberBadges[member].badge[badges].date+$timeoffset|date_format:"%B %e, %Y %H:%M"}</span><br />
    <b>{$scoutlang.badges}: </b><span class="hintanchor" title="{$memberBadges[member].badge[badges].name} :: {if $memberBadges[member].badge[badges].description}{$memberBadges[member].badge[badges].description}{else}No description{/if}"><img src="{$tempdir}admin/images/information.png" alt="[i]"/></span>{$memberBadges[member].badge[badges].name}
    {if $memberBadges[member].badge[badges].comment != ""}<br /><span class="comments"><b>Comment: </b>{$memberBadges[member].badge[badges].comment}</span>{/if}<br />&nbsp;</li>
    {/section}
    </ul>
 {/section}
 {else}
User has no badges
{/if}
{if $editallowed}
<table width="70%" border="0" cellspacing="1" cellpadding="4" align="center" class="table">
<tr>
<th class="smallhead">Add {$scoutlang.badges}</th>
</tr>
<tr>
<td class="text">
<form method="post" action="{$pagename}&amp;action=addbadge&scheme={$schemeNumber}">
<div class="field">

<label class="label">Users</label>
<div class="inputboxwrapper"><ul class="checklist" style="height:10em;">
            {section name=member loop=$nummembers}
               <li><label for="user.{$memberBadges[member].id}"><input type="checkbox" value="{$memberBadges[member].id}" name="user[]" id="user.{$memberBadges[member].id}" />{$memberBadges[member].lastName}, {$memberBadges[member].firstName}</label></li>
            {/section} 
    </ul></div>
<br />
<label class="label">{$scoutlang.badges}</label>
<div class="inputboxwrapper"><ul class="checklist" style="height:10em;">
            {section name=badges loop=$numavailable}
               <li><label for="badge.{$available[badges].id}"><input type="checkbox" value="{$available[badges].id}" name="badge[]" id="badge.{$available[badges].id}" /><span class="hintanchor" title="{$available[badges].name} :: {if $available[badges].description}{$available[badges].description}{else}No description{/if}"><img src="{$tempdir}admin/images/information.png" alt="[i]"/></span>{$available[badges].name}</label></li>
            {/section} 
    </ul></div>
<br />
<label for="comment" class="label">Comment</label><div class="inputboxwrapper"><input type="text" class="inputbox" size="30" name="comment" id="comment"/></div><br />
</div>
<div class="submitWrapper">
<input type="submit" class="button" value="Submit" name="Submit" id="Submit" /></div></form></td>
</tr>
</table>
{/if}

{/if}