{literal}
<script type="text/javascript">
<!--
function confirmDelete(articleId) {
if (confirm("This will delete this user. Continue?"))
document.location = "admin.php?page=users&action=delete&id=" + articleId;
}
//-->
</script>
{/literal}
<h2>User Manager</h2>
{if ($action == "done") || ($action=='')}
{if $addallowed}<div class="toplinks"><a href="admin.php?page=users&amp;subpage=add_user" title="Add User"><img src="{$tempdir}admin/images/add.png" alt="Add User" border="0" /></a>
</div>{/if}
{if $numusers > 0}
<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="table sortable-onload-2 rowstyle-alt paginate-15" id="sortTable">
<thead>
<tr> 
  <th width="120px" class="smallhead"></th>
  <th class="smallhead sortable">Username</th>
  <th width="200px" class="smallhead sortable">Groups</th>
  <th width="100px" class="smallhead sortable-date">Last Login</th>
  <th width="90px" class="smallhead sortable-numeric">Login Count</th>
</tr>
  </thead>
  <tbody>
{section name=users loop=$numusers}
<tr class="text" style="vertical-align:middle">
  <td class="text"><div align="center">{if $editallowed}<a href="admin.php?page=users&amp;subpage=user_edit&amp;action=Edit&amp;id={$row[users].id}"><img src="{$tempdir}admin/images/edit.gif" alt="Edit {$row[users].uname}'s uinfo" title="Edit {$row[users].uname}'s uinfo" border="0" /></a>{else}<img src="{$tempdir}admin/images/edit_grey.gif" alt="Edit disabled" title="Edit disabled" border="0" />{/if}&nbsp;&nbsp;<a href="admin.php?page=users&amp;subpage=users_view&amp;id={$row[users].id}" ><img src="{$tempdir}admin/images/page.gif" alt="View {$row[users].uname}'s uinfo" title="View {$row[users].uname}'s uinfo" border="0"/></a>&nbsp;&nbsp;<a href="admin.php?page=users&amp;subpage=usergroups&amp;uid={$row[users].id}" ><img src="{$tempdir}admin/images/group.png" alt="{$row[users].uname} Groups" title="{$row[users].uname} Groups" border="0"/></a>&nbsp;&nbsp;{if $ownerallowed}<a href="admin.php?page=owners&amp;uid={$row[users].id}"><img src="{$tempdir}admin/images/group.gif" alt="{$row[users].uname} Items" title="{$row[users].uname} Items" border="0"/></a>{else}<img src="{$tempdir}admin/images/group_grey.gif" border="0" alt="Not allowed to change owners" title="Not allowed to change owners" />{/if}&nbsp;&nbsp;{if $deleteallowed && ($row[users].uname != $uname) && !$limitgroup}<a href="javascript:confirmDelete('{$row[users].id}')"><img src="{$tempdir}admin/images/delete.gif"  border="0" alt="Delete {$row[users].uname}" title="Delete {$row[users].uname}" /></a>{else}<img src="{$tempdir}admin/images/delete_grey.gif"  border="0" alt="Delete disabled" title="Delete disabled" />{/if}
</div></td>
  <td class="text" {if $row[users].uname == $uname}style="border:2px solid #0F0;"{elseif $row[users].status == 0}style="border:2px solid #00f;"{elseif $row[users].status == -1}style="border:2px solid #f00;"{/if}><span class="hintanchor"title="Information for {$row[users].uname} :: {$row[users].detail}"><img src="{$tempdir}admin/images/information.png" alt="[i]"/></span>{$row[users].uname}</td>
  <td class="text">{$row[users].team}</td>
  <td class="text">{if $row[users].lastlogin > 0}{$row[users].lastlogin+$timeoffset|date_format:"%Y-%m-%d"}{else}Never logged in{/if}</td>
  <td style="text-align:center" class="text">{$row[users].logincount}</td>
</tr>
{/section}
</tbody>
</table>
<div>
<strong>Key: </strong>
<span style="border:2px solid #0F0;padding:1px;">Yourself</span>&nbsp;<span style="border:2px solid #00F;padding:1px;">Inactive user</span>&nbsp;<span style="border:2px solid #F00;padding:1px;">Blocked user</span>&nbsp;
</div>
{else}
<div align="center">No Users</div>
{/if}
{elseif $action=="Edit"}
<form name="form1" method="post" onsubmit="return checkForm([['usernames','text',true,0,0,''],['email','email',true,0,0,''],['firstname','text',true,0,0,''],['lastname','text',true,0,0,'']{if $numfields > 0}{section name=fields loop=$numfields}{if $fields[fields].required && ($fields[fields].type==1 || $fields[fields].type==2)},['{$fields[fields].name}', 'text', true, 0, 0, '']{elseif $fields[fields].type==6},['{$fields[fields].name}', 'date', {if $fields[fields].required}true{else}false{/if}, 0, 0, '']{/if}{/section}{/if}]);">
<div align="center">
<div class="formlist">
<div class="field">
<fieldset>
<legend>Website Access</legend>
<div class="fieldItem"><label for="name" class="label">Username<span class="hintanchor"title="Please don't forget to inform the user of any username changes"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper">{if !$limitgroup}<input name="usernames" id="usernames" type="text" size="40" value="{$uinfo.uname}" class="inputbox" onblur="checkElement('usernames', 'text', true, 0, 0, '');" /><br /><span class="fieldError" id="usernamesError">Required</span>{else}{$uinfo.uname}{/if}</div></div><br />
    
{if !$limitgroup}
 <div class="fieldItem"><label for="name" class="label">Password<span class="hintanchor"title="Please don't forget to inform the user of any password changes"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><input name="passwords" type="text" size="40" class="inputbox" /></div></div><br />
{/if}
    
<div class="fieldItem"><label for="name" class="label">Status<span class="hintanchor"title="If a user's status is set to inactive the user will not be able to login."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper">{if !$limitgroup}<select name="status" class="inputbox">
<option value="1" {if $uinfo.status == 1}selected{/if}>Active</option>
<option value="0" {if $uinfo.status == 0}selected{/if}>Inactive</option>
<option value="-1" {if $uinfo.status == -1}selected{/if}>Block</option>
</select>{else}{if $uinfo.status == 1}Active{elseif $uinfo.status == 0}Inactive{else}Blocked{/if}{/if}</div></div><br />
    
<div class="fieldItem"><label for="name" class="label">Timezone<span class="hintanchor"title="The users timezone."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><select name="zone" id="zone" class="inputbox">
{section name=zones loop=$numzones}
    <option value="{$zone[zones].id}" {if ($uinfo.timezone == $zone[zones].id)}selected{/if}>{$zone[zones].offset} Hours</option>
{/section}
</select></div></div><br />

<div class="fieldItem"><label for="name" class="label">Email Address<span class="hintanchor"title="Required"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><input name="email" id="email" type="text" size="60" value="{$uinfo.email}" class="inputbox" onblur="checkElement('email', 'email', true, 0, 0, '');" /><br /><span class="fieldError" id="emailError">Required:Must be a valid email address</span></div></div><br />
</fieldset>

<fieldset>
<legend>Personal Information</legend>
<div class="fieldItem"><label for="name" class="label">First Name<span class="hintanchor"title="Required"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><input name="firstname" id="firstname" type="text" size="40" value="{$uinfo.firstname}" onblur="checkElement('firstname', 'text', true, 0, 0, '');" class="inputbox" /><br /><span class="fieldError" id="firstnameError">Required</span></div></div><br />
    
<div class="fieldItem"><label for="name" class="label">Last Name<span class="hintanchor"title="Required."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><input name="lastname" id="lastname" type="text" size="40" value="{$uinfo.lastname}" onblur="checkElement('lastname', 'text', true, 0, 0, '');" class="inputbox" /><br /><span class="fieldError" id="lastnameError">Required</span></div></div><br />
</fieldset>
    
{if $numfields > 0}
<fieldset>
<legend>Additional Information</legend>
    {section name=fields loop=$numfields}
        <div class="fieldItem"><label for="{$fields[fields].name}" class="label">{$fields[fields].query}<span class="hintanchor" title="{if $fields[fields].required}Required{else}Optional{/if}::{$fields[fields].hint}"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
        {assign var="name" value=$fields[fields].name}
        <div class="inputboxwrapper">
        {if $fields[fields].type == 1}
            <input name="{$fields[fields].name}" id="{$fields[fields].name}" type="text" size="{math equation="x + y" x=$fields[fields].options y=5}" maxlength="{$fields[fields].options}" value="{$uinfo.custom.$name}" class="inputbox" {if $fields[fields].required}onblur="checkElement('{$fields[fields].name}', 'text', true, 0, 0, '');"{/if} />{if $fields[fields].required}<br /><span class="fieldError" id="{$fields[fields].name}Error">Required</span>{/if}
        {elseif $fields[fields].type == 2}
            <textarea name="{$fields[fields].name}" id="{$fields[fields].name}" rows="5"  class="inputbox" {if $fields[fields].required}onblur="checkElement('{$fields[fields].name}', 'text', true, 0, 0, '');"{/if}>{$details.custom.$name}</textarea>{if $fields[fields].required}<br /><span class="fieldError" id="{$fields[fields].name}Error">Required</span>{/if}
        {elseif $fields[fields].type == 3}
            {section name=options loop=$fields[fields].options[0]+1 start=1}
            <input type="radio" name="{$fields[fields].name}" id="{$fields[fields].name}:{$smarty.section.options.iteration}" value="{$smarty.section.options.iteration}" {if $uinfo.custom.$name == $smarty.section.options.iteration}checked="checked"{/if} /><div class="fieldItem"><label for="{$fields[fields].name}:{$smarty.section.options.iteration}">{$fields[fields].options[options]}</label>
            {/section}
        {elseif $fields[fields].type == 4}
            {assign var="temp" value=$uinfo.custom.$name}
            {section name=options loop=$fields[fields].options[0]+1 start=1}
            <input type="checkbox" name="{$fields[fields].name}{$smarty.section.options.iteration}" id="{$fields[fields].name}:{$smarty.section.options.iteration}" value="1" {if $temp[options] == 1}checked="checked"{/if} /><div class="fieldItem"><label for="{$fields[fields].name}:{$smarty.section.options.iteration}">{$fields[fields].options[options]}</label>&nbsp;
            {/section}
        {elseif $fields[fields].type == 5}
            <select name="{$fields[fields].name}" class="inputbox">
            {section name=options loop=$fields[fields].options[0]+1 start=1}
            <option value="{$smarty.section.options.iteration}" {if $uinfo.custom.$name == $smarty.section.options.iteration}selected="selected"{/if}>{$fields[fields].options[options]}</option>
            {/section}
            </select>  
        {elseif $fields[fields].type == 6}
            <input name="{$fields[fields].name}" id="{$fields[fields].name}" type="text" value="{$uinfo.custom.$name}" class="inputbox format-y-m-d highlight-days-67" onblur="checkElement('{$fields[fields].name}', 'date', {if $fields[fields].required}true{else}false{/if}, 0, 0, '');"/><br /><span class="fieldError" id="{$fields[fields].name}Error">{if $fields[fields].required}Required: {/if}Must be a valid date in the format YYYY-MM-DD</span>         
        {/if}   
        </div></div><br />
    {/section}          
</fieldset>
{/if}
    </div>
<div class="submitWrapper"><input type="submit" name="Submit" value="{$action}" class="button" />
<input name="Cancel" type="button" id="Cancel" value="Cancel" onclick="window.location='admin.php?page=users';" class="button" /></div>
</div></div>
</form>
{elseif $action == "view"}
<div align="center">
<div class="formlist">
<div class="field">
<fieldset>
<legend>Website Access</legend>
    <div class="fieldItem"><span class="label">Username</span>
    <div class="inputboxwrapper">{$uinfo.uname}</div></div><br />
  
    <div class="fieldItem"><span class="label">Groups</span>
    <div class="inputboxwrapper">{$uinfo.team}</div></div><br />
  
    <div class="fieldItem"><span class="label">Status</span>
    <div class="inputboxwrapper">{if $uinfo.status == 1}Active{elseif $uinfo.status == 0}Inactive{else}Blocked{/if}</div></div><br />
  
    <div class="fieldItem"><span class="label">Email Address</span>
    <div class="inputboxwrapper">{$uinfo.email}</div></div><br />
 </fieldset> 
 
<fieldset>
<legend>Personal Information</legend> 
    <div class="fieldItem"><span class="label">First Name</span>
    <div class="inputboxwrapper">{$uinfo.firstname}</div></div><br />
  
  
    <div class="fieldItem"><span class="label">Last Name</span>
    <div class="inputboxwrapper">{$uinfo.lastname}</div></div><br />
</fieldset>

{if $numfields > 0}
<fieldset>
<legend>Additional Information</legend>
    {section name=fields loop=$numfields}
        <div class="fieldItem"><span class="label">{$fields[fields].query}</span>
        {assign var="name" value=$fields[fields].name}
        <div class="inputboxwrapper">
        {if $fields[fields].type == 1}
            {$uinfo.custom.$name}
        {elseif $fields[fields].type == 2}
            {$uinfo.custom.$name}
        {elseif $fields[fields].type == 3}
            {section name=options loop=$fields[fields].options[0]+1 start=1}
                {if $uinfo.custom.$name == $smarty.section.options.iteration}{$fields[fields].options[options]}{/if}
            {/section}
        {elseif $fields[fields].type == 4}
            {assign var="temp" value=$uinfo.custom.$name}
            {if $temp}
            {section name=options loop=$fields[fields].options[0]+1 start=1}
                {if $temp[options] == 1}{$fields[fields].options[options]}{/if}{if $smarty.section.options.iteration < ($fields[fields].options[0]+1)}, {/if}
            {/section}
            {/if}
        {elseif $fields[fields].type == 5}
            {section name=options loop=$fields[fields].options[0]+1 start=1}
                {if $uinfo.custom.$name == $smarty.section.options.iteration}{$fields[fields].options[options]}{/if}
            {/section}
        {elseif $fields[fields].type == 6}
            {$uinfo.custom.$name}           
        {/if}   
        </div></div><br />
    {/section}          
</fieldset>
{/if}
</div> 
<div class="submitWrapper"><input type="button" class="button" value="Back" onclick="history.go(-1);" /></div>
</div></div>
{/if}