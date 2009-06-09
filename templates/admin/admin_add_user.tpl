<h2>Add User</h2>

<form name="form1" method="post" onsubmit="return checkForm([['usernames','text',true,0,0,''],['passwords','text',true,6,0,''],['email','email',true,0,0,''],['firstname','text',true,0,0,''],['lastname','text',true,0,0,'']{if $numfields > 0}{section name=fields loop=$numfields}{if $fields[fields].required && ($fields[fields].type==1 || $fields[fields].type==2)},['{$fields[fields].name}', 'text', true, 0, 0, '']{elseif $fields[fields].type==6},['{$fields[fields].name}', 'date', {if $fields[fields].required}true{else}false{/if}, 0, 0, '']{/if}{/section}{/if}]);">
<div align="center">
<div class="formlist">
<div class="field">
<fieldset>
<legend>Website Access</legend>
<label for="usernames" class="label">Username<span class="hintanchor"title="Required :: Username for the user"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><input name="usernames" id="usernames" type="text" size="40" value="{$post.usernames}" class="inputbox" onblur="checkElement('usernames', 'text', true, 0, 0, '');" /><br /><span class="fieldError" id="usernamesError">Required</span></div><br />
    
 <label for="passwords" class="label">Password<span class="hintanchor"title="Password for the user, must be longer then 6 characters"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><input name="passwords" id="passwords" type="text" size="40" class="inputbox" value="{$post.passwords}" onblur="checkElement('passwords', 'text', true, 6, 0, '');" /><br /><span class="fieldError" id="passwordsError">Required: Must be longer then 6 characters</span></div><br />
    
<label for="status" class="label">Status<span class="hintanchor"title="If a user's status is set to inactive the user will not be able to login."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><select name="status" id="status" class="inputbox">
<option value="1" {if $post.status == 1}selected{/if}>Active</option>
<option value="0" {if $post.status == 0}selected{/if}>Inactive</option>
<option value="-1" {if $post.status == -1}selected{/if}>Block</option>
</select></div><br />
    
<label for="zone" class="label">Timezone<span class="hintanchor"title="The users timezone."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><select name="zone" id="zone" class="inputbox">
{section name=zones loop=$numzones}
    <option value="{$zone[zones].id}" {if ($post.zone == $zone[zones].id)}selected="selected"{/if}>{$zone[zones].offset} Hours</option>
{/section}
</select></div><br />

<label for="email" class="label">Email Address<span class="hintanchor"title="Required"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><input name="email" id="email" type="text" size="60" value="{$post.email}" class="inputbox" onblur="checkElement('email', 'email', true, 0, 0, '');" /><br /><span class="fieldError" id="emailError">Required:Must be a valid email address</span></div><br />
</fieldset>

<fieldset>
<legend>Personal Information</legend>
<label for="firstname" class="label">First Name<span class="hintanchor"title="Required"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><input name="firstname" id="firstname" type="text" size="40" value="{$post.firstname}" onblur="checkElement('firstname', 'text', true, 0, 0, '');" class="inputbox" /><br /><span class="fieldError" id="firstnameError">Required</span></div><br />
    
<label for="lastname" class="label">Last Name<span class="hintanchor"title="Required."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><input name="lastname" id="lastname" type="text" size="40" value="{$post.lastname}" onblur="checkElement('lastname', 'text', true, 0, 0, '');" class="inputbox" /><br /><span class="fieldError" id="lastnameError">Required</span></div><br />
</fieldset>

<fieldset>
<legend>Member</legend>
<span class="label">Make Member<span class="hintanchor"title="Required"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></span>
<div class="inputboxwrapper"><input name="member" id="member:yes" type="radio" value="1" /><label for="member:yes">Yes</label>&nbsp;<input name="member" id="member:no" type="radio" value="0" checked="checked" /><label for="member:no">No</label></div><br />
    
<label for="type" class="label">Type of member<span class="hintanchor" title="Required"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><select id="type" name="type" class="inputbox" />
<option value="0" selected="selected">Member</option>
<option value="1">Father</option>
<option value="2">Mother</option>
<option value="3">Legal Guardian</option>
</select></div><br />

<label for="sex" class="label">Sex<span class="hintanchor" title="Required if user is a member."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><select id="sex" name="sex" class="inputbox" />
<option value="0" selected="selected">Male</option>
<option value="1">Female</option>
</select></div><br />
</fieldset>

{if $numfields > 0}
<fieldset>
<legend>Additional Information</legend>
    {section name=fields loop=$numfields}
        <label for="{$fields[fields].name}" class="label">{$fields[fields].query}<span class="hintanchor" title="{if $fields[fields].required}Required{else}Optional{/if}::{$fields[fields].hint}"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
        {assign var="name" value=$fields[fields].name}
        <div class="inputboxwrapper">
        {if $fields[fields].type == 1}
            <input name="{$fields[fields].name}" id="{$fields[fields].name}" type="text" size="{math equation="x + y" x=$fields[fields].options y=5}" maxlength="{$fields[fields].options}" value="{$post.custom.$name}" class="inputbox" {if $fields[fields].required}onblur="checkElement('{$fields[fields].name}', 'text', true, 0, 0, '');"{/if} />{if $fields[fields].required}<br /><span class="fieldError" id="{$fields[fields].name}Error">Required</span>{/if}
        {elseif $fields[fields].type == 2}
            <textarea name="{$fields[fields].name}" id="{$fields[fields].name}" rows="5"  class="inputbox" {if $fields[fields].required}onblur="checkElement('{$fields[fields].name}', 'text', true, 0, 0, '');"{/if}>{$details.custom.$name}</textarea>{if $fields[fields].required}<br /><span class="fieldError" id="{$fields[fields].name}Error">Required</span>{/if}
        {elseif $fields[fields].type == 3}
            {section name=options loop=$fields[fields].options[0]+1 start=1}
            <input type="radio" name="{$fields[fields].name}" id="{$fields[fields].name}:{$smarty.section.options.iteration}" value="{$smarty.section.options.iteration}" {if $post.custom.$name == $smarty.section.options.iteration}checked="checked"{/if} /><label for="{$fields[fields].name}:{$smarty.section.options.iteration}">{$fields[fields].options[options]}</label>
            {/section}
        {elseif $fields[fields].type == 4}
            {assign var="temp" value=$post.custom.$name}
            {section name=options loop=$fields[fields].options[0]+1 start=1}
            <input type="checkbox" name="{$fields[fields].name}{$smarty.section.options.iteration}" id="{$fields[fields].name}:{$smarty.section.options.iteration}" value="1" {if $temp[options] == 1}checked="checked"{/if} /><label for="{$fields[fields].name}:{$smarty.section.options.iteration}">{$fields[fields].options[options]}</label>&nbsp;
            {/section}
        {elseif $fields[fields].type == 5}
            <select name="{$fields[fields].name}" class="inputbox">
            {section name=options loop=$fields[fields].options[0]+1 start=1}
            <option value="{$smarty.section.options.iteration}" {if $post.custom.$name == $smarty.section.options.iteration}selected="selected"{/if}>{$fields[fields].options[options]}</option>
            {/section}
            </select>  
        {elseif $fields[fields].type == 6}
            <input name="{$fields[fields].name}" id="{$fields[fields].name}" type="text" value="{$post.custom.$name}" class="inputbox format-y-m-d highlight-days-67" onblur="checkElement('{$fields[fields].name}', 'date', {if $fields[fields].required}true{else}false{/if}, 0, 0, '');"/><br /><span class="fieldError" id="{$fields[fields].name}Error">{if $fields[fields].required}Required: {/if}Must be a valid date in the format YYYY-MM-DD</span>         
        {/if}   
        </div><br />
    {/section}          
</fieldset>
{/if}
    </div>
<div class="submitWrapper"><input type="submit" name="Submit" value="Submit" class="button" />
<input name="Cancel" type="button" id="Cancel" value="Cancel" onclick="window.location='admin.php?page=users';" class="button" /></div>
</div></div>
</form>
