<script type="text/javascript">
{include file="../scripts/validator.tpl"}
</script>
<form name="form1" method="post" action="" onsubmit="return checkForm([['troopname','text',true,0,0,'', 'Website Name'],['siteaddress','text',true,0,0,'', 'Site Address'],['sitemail','email',true,0,0,'', 'Website Email'],['numpm','number',true,0,0,'', 'Number of Private Messages'],['uploadlimit','number',true,0,0,'', 'Upload Limit'],['avyy','number',true,0,0,'', 'Avatar Y size'],['avyx','number',true,0,0,'', 'Avatar X size'],['sigsize','number',true,0,0,'', 'Signature Size'],['photox','number',true,0,0,'', 'Maximum Photo X Size'],['photoy','number',true,0,0,'', 'Maximum Photo Y Size'],['numsidebox','number',true,0,0,'', 'Items on Sideboxes'],['numpage','number',true,0,0,'', 'Number of items per page'],['privacy','text',true,0,0,'', 'Privacy Statement']]);">
<h2>Website Configuration</h2>
<div align="center"><div style="width:100%;">
<div class="fieldError" id="anyerror"></div>
<div id="navcontainer" align="center">
<ul class="mootabs_title">
    <li title="website">Website</li>
    <li title="email">Email</li>
    <li title="registration">Registration</li>
    <li title="profile">Profile</li>
    <li title="layout">Layout</li>
    <li title="confirm">Confirmation</li>
</ul>

<div id="website" class="mootabs_panel">
<div class="field">
    <div class="fieldItem"><label for="troopname" class="label">Website Name<span class="hintanchor" title="The name of your website."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper">{if $editallowed}<input type="text" name="troopname" id="troopname" value="{$configs.troopname}" size="40" class="inputbox" onblur="checkElement('troopname', 'text', true, 0, 0, '');" /><br /><span class="fieldError" id="troopnameError">Required</span>{else}{$configs.troopname}{/if}</div></div><br />

    <div class="fieldItem"><label for="troop_description" class="label">Website Description<span class="hintanchor" title="A short description of the website. Where it is used depends on the template."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper">{if $editallowed}<input type="text" name="troop_description" id="troop_description" value="{$configs.troop_description}" size="40" class="inputbox"  />{else}{$configs.troop_description}{/if}</div></div><br />

    <div class="fieldItem"><label for="copyright" class="label">Copyright Message<span class="hintanchor" title="This is a short copyright message that is shown at the bottom of every page."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper">{if $editallowed}<input type="text" name="copyright" id="copyright" value="{$configs.copyright}" size="40" class="inputbox" />{else}{$configs.copyright}{/if}</div></div><br />

    <div class="fieldItem"><label for="disclaimer" class="label">Disclaimer<span class="hintanchor" title="This is a short disclaimer message that is shown at the bottom of every page. You may leave it blank, but most Scout Associations require scouting websites to display a disclaimer message."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper">{if $editallowed}<input type="text" name="disclaimer" id="disclaimer" value="{$configs.disclaimer}" size="40" class="inputbox" />{else}{$configs.disclaimer}{/if}</div></div><br />

    <div class="fieldItem"><label for="siteaddress" class="label">Site Address<span class="hintanchor" title="Advanced Users Only :: The address of your website, make sure that this points to the correct address."><img src="{$tempdir}admin/images/exclamation.png" alt="[!]"/></span></label>
    <div class="inputboxwrapper">{if $editallowed}<input type="text" name="siteaddress" id="siteaddress" value="{$configs.siteaddress}" size="40" class="inputbox" onblur="checkElement('siteaddress', 'text', true, 0, 0, '');" /><br /><span class="fieldError" id="siteaddressError">Required</span>{else}{$configs.siteaddress}{/if}</div></div><br />

    <div class="fieldItem"><span class="label">Disable Site<span class="hintanchor" title="Disable the website"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></span>
    <div class="inputboxwrapper">{if $editallowed}<input type="radio" name="disablesite" id="disablesite:yes" value="1" {if $configs.disablesite == 1}checked="checked"{/if} /><label for="disablesite:yes">Yes</label>
    <input name="disablesite" id="disablesite:no" type="radio" value="0" {if $configs.disablesite == 0}checked="checked"{/if} /><label for="disablesite:no">No</label>{else}{if $configs.disablesite}Yes{else}No{/if}{/if}</div></div><br />

    <div class="fieldItem"><label for="disablereason" class="label">Reason<span class="hintanchor" title="The reason for the website being disabled. You may use HTML here."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper">{if $editallowed}<textarea name="disablereason" style="width:100%" rows="30" id="disablereason" class="inputbox">{$configs.disablereason}</textarea>{else}{$configs.disablereason}{/if}</div></div><br />

    <div class="fieldItem"><span class="label">Debug Mode<span class="hintanchor" title="Advanced Users Only :: Displays number of database queries, and how long it took to render a page."><img src="{$tempdir}admin/images/exclamation.png" alt="[!]"/></span></span>
    <div class="inputboxwrapper">{if $editallowed}<input type="radio" name="softdebug" id="softdebug:yes" value="1" {if $configs.softdebug == 1}checked="checked"{/if} /><label for="softdebug:yes">Yes</label>
    <input name="softdebug" id="softdebug:no" type="radio" value="0" {if $configs.softdebug == 0}checked="checked"{/if} /><label for="softdebug:no">No</label>{else}{if $configs.softdebug}Yes{else}No{/if}{/if}</div></div><br />

    <div class="fieldItem"><span class="label">GZip Output<span class="hintanchor" title="Advanced Users Only :: Compresses the output before sending it to the user. Will only work if your server and the users browser supports GZip (Most modern ones do)"><img src="{$tempdir}admin/images/exclamation.png" alt="[!]"/></span></span>
    <div class="inputboxwrapper">{if $editallowed}<input type="radio" name="gzip" id="gzip:yes" value="1" {if $configs.gzip == 1}checked="checked"{/if} /><label for="gzip:yes">Yes</label>
    <input name="gzip" id="gzip:no" type="radio" value="0" {if $configs.gzip == 0}checked="checked"{/if} /><label for="gzip:no">No</label>{else}{if $configs.gzip}Yes{else}No{/if}{/if}</div></div><br />

    <div class="fieldItem"><label for="zone" class="label">Server Timezone<span class="hintanchor" title="The timezone that the server is set to."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper">{if $editallowed}<select name="zone" id="zone" class="inputbox">
    {section name=zones loop=$numzones}
    <option value="{$zone[zones].id}" {if ($configs.zone == $zone[zones].id)}selected="selected" {assign var="offset" value=$zone[zones].offset}{/if}>{$zone[zones].offset} Hours</option>
    {/section}
    </select>{else}
    {section name=zones loop=$numzones}
    {if ($configs.zone == $zone[zones].id)}{$zone[zones].offset} Hours{/if}
    {/section}{/if}</div></div><br />

    <div class="fieldItem"><label for="numpm" class="label">Number of PMs<span class="hintanchor" title="How many personal messages can a user have in each box? Default 10"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper">{if $editallowed}<input type="text" name="numpm" id="numpm" value="{$configs.numpm}" class="inputbox"  onblur="checkElement('numpm', 'number', true, 0, 0, '');"/><br /><span class="fieldError" id="numpmError">Required: Must be a number.</span>{else}{$configs.numpm}{/if}</div></div><br />

    <div class="fieldItem"><span class="label">Default Authorisation<span class="hintanchor" title="If a user does not have a authorisation setting associated with them, how must CMScout handle their permisions."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></span>
    <div class="inputboxwrapper">{if $editallowed}<input type="radio" name="defaultaccess" id="defaultaccess:yes" value="1" {if $configs.defaultaccess == 1}checked="checked"{/if} /><label for="defaultaccess:yes">Allow</label>
    <input name="defaultaccess" id="defaultaccess:no" type="radio" value="0" {if $configs.defaultaccess == 0}checked="checked"{/if} /><label for="defaultaccess:no">Deny</label>{else}{if $configs.defaultaccess}Allow{else}Deny{/if}{/if}</div></div><br />

    <div class="fieldItem"><label for="uploadlimit" class="label">Upload Limit<span class="hintanchor" title="Maximum file size a user can upload to the download section, this does not apply for users with administrator or Scouter access level"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper">{if $editallowed}<input type="text" name="uploadlimit" id="uploadlimit" value="{$configs.uploadlimit}" size="20" class="inputbox" onblur="checkElement('uploadlimit', 'number', true, 0, 0, '');" />Kb<br /><span class="fieldError" id="uploadlimitError">Required: Must be a number.</span>{else}{$configs.uploadlimit}Kb{/if}</div></div><br />

    <div class="fieldItem"><label for="cookiename" class="label">Name of cookie<span class="hintanchor" title="Advanced Users Only :: Name of the CMScout cookie."><img src="{$tempdir}admin/images/exclamation.png" alt="[!]"/></span></label>
    <div class="inputboxwrapper">{if $editallowed}<input type="text" name="cookiename" id="cookiename" value="{$configs.cookiename}" class="inputbox" />{else}{$configs.cookiename}{/if}</div></div><br />

    <div class="fieldItem"><label for="session_length" class="label">Logon Time<span class="hintanchor" title="Advanced Users Only :: Length of time a inactive user will remain logged on (In seconds). Default value is 3600."><img src="{$tempdir}admin/images/exclamation.png" alt="[!]"/></span></label>
    <div class="inputboxwrapper">{if $editallowed}<input type="text" name="session_length" id="session_length" value="{$configs.session_length}" class="inputbox"  onblur="checkElement('session_length', 'number', true, 0, 0, '');"/><br /><span class="fieldError" id="session_lengthError">Required: Must be a number.</span>{else}{$configs.session_length}{/if}</div></div><br />

    <div class="fieldItem"><label for="activetime" class="label">Active Length<span class="hintanchor" title="Advanced Users Only :: Length of time a logged on user will remain in the active list (In seconds). Default value is 300."><img src="{$tempdir}admin/images/exclamation.png" alt="[!]"/></span></label>
    <div class="inputboxwrapper">{if $editallowed}<input type="text" name="activetime" id="activetime" value="{$configs.activetime}" class="inputbox" onblur="checkElement('activetime', 'number', true, 0, 0, '');" /><br /><span class="fieldError" id="activetimeError">Required: Must be a number.</span>{else}{$configs.activetime}{/if}</div></div><br />
    </div>
    {if $editallowed}<div class="submitWrapper">
        <input type="submit" name="Submit" value="Update Config"  class="button" />&nbsp;
        <input type="reset" name="Submit2" value="Reset" class="button" />
    </div>{/if}
</div>

<div id="email" class="mootabs_panel">
<div class="field">
    <div class="fieldItem"><label for="sitemail" class="label">Website Email<span class="hintanchor" title="The webmaster's email address. All emails sent by CMScout will be from this address. The address needs to exist since CMScout will send emails to this address as well."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper">{if $editallowed}<input type="text" name="sitemail" id="sitemail" value="{$configs.sitemail}" size="40" class="inputbox" onblur="checkElement('sitemail', 'email', true, 0, 0, '');" /><br /><span class="fieldError" id="sitemailError">Required: Must be a valid email address.</span>{else}{$configs.sitemail}{/if}</div></div><br />

    <div class="fieldItem"><label for="emailPrefix" class="label">Email Prefix<span class="hintanchor" title="This will be added to the front of the subject of any emails sent by CMScout."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper">{if $editallowed}<input type="text" name="emailPrefix" id="emailPrefix" value="{$configs.emailPrefix}" size="40" class="inputbox" onblur="checkElement('emailPrefix', 'text', true, 0, 0, '');" /><br /><span class="fieldError" id="emailPrefixError">Required</span>{else}{$configs.emailPrefix}{/if}</div></div><br />
    
    <div class="fieldItem"><span class="label">Enable Emails<span class="hintanchor" title="Advanced Users Only :: Use this to disable CMScout from sending any emails."><img src="{$tempdir}admin/images/exclamation.png" alt="[!]"/></span></span>
    <div class="inputboxwrapper">{if $editallowed}<input type="radio" name="allowemails" id="allowemails:yes" value="1" {if $configs.allowemails == 1}checked="checked"{/if} /><label for="allowemails:yes">Yes</label>
    <input name="allowemails" id="allowemails:no" type="radio" value="0" {if $configs.allowemails == 0}checked="checked"{/if} /><label for="allowemails:no">No</label>{else}{if $configs.allowemails}Yes{else}No{/if}{/if}</div></div><br />

    <div class="fieldItem"><span class="label">Use SMTP<span class="hintanchor" title="Advanced Users Only :: Select this if you need to use a SMTP email connection to send emails"><img src="{$tempdir}admin/images/exclamation.png" alt="[!]"/></span></span>
    <div class="inputboxwrapper">{if $editallowed}<input type="radio" name="smtp" id="smtp:yes" value="1" {if $configs.smtp == 1}checked="checked"{/if} /><label for="smtp:yes">Yes</label>
    <input name="smtp" id="smtp:no" type="radio" value="0" {if $configs.smtp == 0}checked="checked"{/if} /><label for="smtp:no">No</label>{else}{if $configs.smtp}Yes{else}No{/if}{/if}</div></div><br />
    
    <div class="fieldItem"><label for="smtp_host" class="label">SMTP Host<span class="hintanchor" title="Advanced Users Only :: Hostname for the SMTP server."><img src="{$tempdir}admin/images/exclamation.png" alt="[!]"/></span></label>
    <div class="inputboxwrapper">{if $editallowed}<input type="text" name="smtp_host" id="smtp_host" value="{$configs.smtp_host}" class="inputbox" />{else}{$configs.smtp_host}{/if}</div></div><br />
    
    <div class="fieldItem"><label for="smtp_port" class="label">SMTP Port<span class="hintanchor" title="Advanced Users Only :: Port number for the SMTP server. Default is 25."><img src="{$tempdir}admin/images/exclamation.png" alt="[!]"/></span></label>
    <div class="inputboxwrapper">{if $editallowed}<input type="text" name="smtp_port" id="smtp_port" value="{$configs.smtp_port}" class="inputbox" />{else}{$configs.smtp_port}{/if}</div></div><br />
    
    <div class="fieldItem"><label for="smtp_host" class="label">SMTP Username<span class="hintanchor" title="Advanced Users Only :: Username to access the SMTP server."><img src="{$tempdir}admin/images/exclamation.png" alt="[!]"/></span></label>
    <div class="inputboxwrapper">{if $editallowed}<input type="text" name="smtp_username" id="smtp_username" value="{$configs.smtp_username}" class="inputbox" />{else}{$configs.smtp_username}{/if}</div></div><br />
    
    <div class="fieldItem"><label for="smtp_password" class="label">SMTP Password<span class="hintanchor" title="Advanced Users Only :: Password for the SMTP server."><img src="{$tempdir}admin/images/exclamation.png" alt="[!]"/></span></label>
    <div class="inputboxwrapper">{if $editallowed}<input type="text" name="smtp_password" id="smtp_password" value="{$configs.smtp_password}" class="inputbox" />{else}{$configs.smtp_password}{/if}</div></div><br />
</div>
    
    {if $editallowed}<div class="submitWrapper">
        <input type="submit" name="Submit" value="Update Config"  class="button" />&nbsp;
        <input type="reset" name="Submit2" value="Reset" class="button" />
    </div>{/if}
</div>

<div id="registration" class="mootabs_panel">
<div class="field">
    <div class="fieldItem"><span class="label">Allow registration<span class="hintanchor" title="Are users allowed to register to join the site?"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></span>
    <div class="inputboxwrapper">{if $editallowed}<input type="radio" name="register" id="register:yes" value="1" {if $configs.register == 1}checked="checked"{/if} /><label for="register:yes">Yes</label>
    <input name="register" id="register:no" type="radio" value="0" {if $configs.register == 0}checked="checked"{/if} /><label for="register:no">No</label>{else}{if $configs.register}Yes{else}No{/if}{/if}</div></div><br />

    <div class="fieldItem"><span class="label">CAPTCHA image<span class="hintanchor" title="Displays a CAPTCHA image when a user registers or when a guest posts on the forums. This verifies that the user is a human and prevents spam-bots from registering or posting"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></span>
    <div class="inputboxwrapper">{if $editallowed}<input type="radio" name="registerimage" id="registerimage:yes" value="1" {if $configs.registerimage == 1}checked="checked"{/if} /><label for="registerimage:yes">Yes</label>
    <input name="registerimage" id="registerimage:no" type="radio" value="0" {if $configs.registerimage == 0}checked="checked"{/if} /><label for="registerimage:no">No</label>{else}{if $configs.registerimage}Yes{else}No{/if}{/if}</div></div><br />

    <div class="fieldItem"><span class="label">Duplicate email<span class="hintanchor" title="Allow more than one user to use an email address."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></span>
    <div class="inputboxwrapper">{if $editallowed}<input type="radio" name="dubemail" id="dubemail:yes" value="1" {if $configs.dubemail == 1}checked="checked"{/if} /><label for="dubemail:yes">Yes</label>
    <input name="dubemail" id="dubemail:no" type="radio" value="0" {if $configs.dubemail == 0}checked="checked"{/if} /><label for="dubemail:no">No</label>{else}{if $configs.dubemail}Yes{else}No{/if}{/if}</div></div><br />

    <div class="fieldItem"><label for="accountactivation" class="label">Account Activation<span class="hintanchor" title="User activation will send the user an email with a activation link that he/she must click to activate their account. Admin activation requires the administrator to activate the account from the user control panel."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper">{if $editallowed}<select name="accountactivation" id="accountactivation" class="inputbox">
    <option value="0" {if ($configs.accountactivation == 0)}selected="selected"{/if}>No activation</option>
    <option value="1" {if ($configs.accountactivation == 1)}selected="selected"{/if}>User Activation</option>
    <option value="2" {if ($configs.accountactivation == 2)}selected="selected"{/if}>Admin Activation</option>
    </select>{else}{if ($configs.accountactivation == 0)}No activiation{elseif ($configs.accountactivation == 1)}User Activation{elseif ($configs.accountactivation == 2)}Admin Activation{/if}{/if}</div></div><br />

    <div class="fieldItem"><label for="defaultgroup" class="label">Default Group<span class="hintanchor" title="The group that new users will be added to automatically."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper">{if $editallowed}<select name="defaultgroup" id="defaultgroup" class="inputbox">
    {section name=groups loop=$numgroups}
    <option value="{$group[groups].id}" {if ($configs.defaultgroup == $group[groups].id)}selected="selected"{/if}>{$group[groups].teamname}</option>
    {/section}
    </select>{else}
    {section name=groups loop=$numgroups}
    {if ($configs.defaultgroup == $group[groups].id)}{$group[groups].teamname}{/if}
    {/section}{/if}</div></div><br />

    <div class="fieldItem"><label for="welcomemessage" class="label">Welcome Message<span class="hintanchor" title="A short welcome message to show to all new users when the register. You may use HTML here."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper">{if $editallowed}<textarea name="welcomemessage" style="width:100%" rows="30" id="welcomemessage"class="inputbox" onblur="checkElement('welcomemessage', 'text', true, 0, 0, '');">{$configs.welcomemessage}</textarea><br /><span class="fieldError" id="welcomemessageError">Required</span>{else}{$configs.welcomemessage}{/if}</div></div><br />

    <div class="fieldItem"><label for="privacy" class="label">Privacy Statement<span class="hintanchor" title="A short privacy statement to show to all new users when the register. This is a legal requirement in some countries. You may use HTML here."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper">{if $editallowed}<textarea name="privacy" style="width:100%" rows="30" id="privacy"class="inputbox" onblur="checkElement('privacy', 'text', true, 0, 0, '');">{$configs.privacy}</textarea><br /><span class="fieldError" id="privacyError">Required</span>{else}{$configs.privacy}{/if}</div></div><br />

    </div>
    
    {if $editallowed}<div class="submitWrapper">
        <input type="submit" name="Submit" value="Update Config"  class="button" />&nbsp;
        <input type="reset" name="Submit2" value="Reset" class="button" />
    </div>{/if}
</div>

<div id="profile" class="mootabs_panel">
<div class="field">
    <div class="fieldItem"><label for="avyx" class="label">Avatar Size<span class="hintanchor" title="Width x Height. Maximum size of a users avatar in pixels. Enter zero for either value to disable avatars. Default value is 100x100."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper">{if $editallowed}<input type="text" style="width:60px" id="avyx" name="avyx" value="{$configs.avyx}" class="inputbox" onblur="checkElement('avyx', 'number', true, 0, 0, '');" /> x <input type="text" name="avyy" id="avyy" style="width:60px" value="{$configs.avyy}" class="inputbox" onblur="checkElement('avyy', 'number', true, 0, 0, '');" /><br /><span class="fieldError" id="avyxError">Required: Width must be a number.<br /></span><span class="fieldError" id="avyyError">Required: Height must be a number.</span>{else}{$configs.avyx}x{$configs.avyy}{/if}</div></div><br />

    <div class="fieldItem"><label for="sigsize" class="label">Signature length<span class="hintanchor" title="Maximum number of characters that a users signature can be. Enter zero to disable signatures. Default value is 255."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper">{if $editallowed}<input type="text" name="sigsize" id="sigsize" value="{$configs.sigsize}" class="inputbox" onblur="checkElement('sigsize', 'number', true, 0, 0, '');" /><br /><span class="fieldError" id="sigsizeError">Required: Must be a number.</span>{else}{$configs.sigsize}{/if}</div></div><br />
</div>

    {if $editallowed}<div class="submitWrapper">
        <input type="submit" name="Submit" value="Update Config"  class="button" />&nbsp;
        <input type="reset" name="Submit2" value="Reset" class="button" />
    </div>{/if}
</div>

<div id="layout" class="mootabs_panel">
<div class="field">
    <div class="fieldItem"><label for="photox" class="label">Maximum size of photos<span class="hintanchor" title="Maximum size for a photo. CMScout makes sure that scaled down photos keep their aspect ratio. Default value is 800x600. To disable resizing set either value to zero."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper">{if $editallowed}<input type="text" style="width:60px" name="photox" id="photox" value="{$configs.photox}" class="inputbox" onblur="checkElement('photox', 'number', true, 0, 0, '');" /> x <input type="text" name="photoy" id="photoy" style="width:60px" value="{$configs.photoy}" class="inputbox" onblur="checkElement('photoys', 'number', true, 0, 0, '');" /><br /><span class="fieldError" id="photoxError">Required: Width must be a number.<br /></span><span class="fieldError" id="photoyError">Required: Height must be a number.</span>{else}{$configs.photox}x{$configs.photoy}{/if}</div></div><br />
    
    <div class="fieldItem"><label for="theme" class="label">Default Template<span class="hintanchor" title="The default template that CMScout uses."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper">{if $editallowed}<select name="defaulttheme" id="theme" class="inputbox">
    {section name=themes loop=$numthemes}
    <option value="{$theme[themes].id}" {if ($configs.defaulttheme == $theme[themes].id)}selected="selected"{/if}>{$theme[themes].name}</option>
    {/section}
    </select>{else}{section name=themes loop=$numthemes}{if ($configs.defaulttheme == $theme[themes].id)}{$theme[themes].name}{/if}{/section}{/if}</div></div><br />

    <div class="fieldItem"><span class="label">User custom templates<span class="hintanchor" title="Allow users to choose between installed templates (If you only have one template then users will not be given the option to change their template)"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></span>
    <div class="inputboxwrapper">{if $editallowed}<input type="radio" name="allowtemplate" id="allowtemplate:yes" value="1" {if $configs.allowtemplate == 1}checked="checked"{/if} /><label for="allowtemplate:yes">Yes</label>
    <input name="allowtemplate" id="allowtemplate:no" type="radio" value="0" {if $configs.allowtemplate == 0}checked="checked"{/if} /><label for="allowtemplate:no">No</label>{else}{if $configs.allowtemplate}Yes{else}No{/if}{/if}</div></div><br />

    <div class="fieldItem"><span class="label">Group sites enabled<span class="hintanchor" title="Allows you to deactivate all group sites."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></span>
    <div class="inputboxwrapper">{if $editallowed}<input type="radio" name="patrolpage" id="patrolpage:yes" value="1" {if $configs.patrolpage == 1}checked="checked"{/if} /><label for="patrolpage:yes">Yes</label>
    <input name="patrolpage" id="patrolpage:no" type="radio" value="0" {if $configs.patrolpage == 0}checked="checked"{/if} /><label for="patrolpage:no">No</label>{else}{if $configs.patrolpage}Yes{else}No{/if}{/if}</div></div><br />

    <div class="fieldItem"><span class="label">News page sorting<span class="hintanchor" title="How should news items on the news archive page be sorted"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></span>
    <div class="inputboxwrapper">{if $editallowed}<input type="radio" name="newssort" id="newssort:yes" value="1" {if $configs.newssort == 1}checked="checked"{/if} /><label for="newssort:yes">Oldest First</label>
    <input name="newssort" id="newssort:no" type="radio" value="0" {if $configs.newssort == 0}checked="checked"{/if} /><label for="newssort:no">Newest First</label>{else}{if $configs.newssort}Oldest First{else}Newest First{/if}{/if}</div></div><br />

    <div class="fieldItem"><span class="label">Display all articles<span class="hintanchor" title="Display all articles on the general article page, or only display articles not attached to a group."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></span>
    <div class="inputboxwrapper">{if $editallowed}<input type="radio" name="articledisplay" id="articledisplay:yes" value="1" {if $configs.articledisplay == 1}checked="checked"{/if} /><label for="articledisplay:yes">Yes</label>
    <input name="articledisplay" id="articledisplay:no" type="radio" value="0" {if $configs.articledisplay == 0}checked="checked"{/if} /><label for="articledisplay:no">No</label>{else}{if $configs.articledisplay}Yes{else}No{/if}{/if}</div></div><br />

    <div class="fieldItem"><span class="label">Display all photo albums<span class="hintanchor" title="Display all photo albums on the general photo album page, or only display photo albums not attached to a group."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></span>
    <div class="inputboxwrapper">{if $editallowed}<input type="radio" name="albumdisplay" id="albumdisplay:yes" value="1" {if $configs.albumdisplay == 1}checked="checked"{/if} /><label for="albumdisplay:yes">Yes</label>
    <input name="albumdisplay" id="albumdisplay:no" type="radio" value="0" {if $configs.albumdisplay == 0}checked="checked"{/if} /><label for="albumdisplay:no">No</label>{else}{if $configs.albumdisplay}Yes{else}No{/if}{/if}</div></div><br />

    <div class="fieldItem"><label for="numsidebox" class="label">Items on sideboxes<span class="hintanchor" title="Number of items to show in sideboxes. Default value is 5"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper">{if $editallowed}<input type="text" name="numsidebox" id="numsidebox" value="{$configs.numsidebox}" class="inputbox" onblur="checkElement('numsidebox', 'number', true, 0, 0, '');" /><br /><span class="fieldError" id="numsideboxError">Required: Must be a number.</span>{else}{$configs.numsidebox}{/if}</div></div><br />
    
    <div class="fieldItem"><label for="numpage" class="label">Items per page<span class="hintanchor" title="Number of itmes to show on a single page in the forum and photo album. Default value is 10."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper">{if $editallowed}<input type="text" name="numpage" id="numpage" value="{$configs.numpage}" class="inputbox" onblur="checkElement('numpage', 'number', true, 0, 0, '');" /><br /><span class="fieldError" id="numpageError">Required: Must be a number.</span>{else}{$configs.numpage}{/if}</div></div><br />

    <div class="fieldItem"><label for="defaultview" class="label">Default Calendar View<span class="hintanchor" title="The default view for your calendar."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper">{if $editallowed}<select name="defaultview" id="defaultview" class="inputbox">
    <option value="Year" {if ($configs.defaultview == "Year")}selected="selected"{/if}>Year</option>
    <option value="Month" {if ($configs.defaultview == "Month")}selected="selected"{/if}>Month</option>
    <option value="List" {if ($configs.defaultview == "List")}selected="selected"{/if}>List</option>
    </select>{else}{$configs.defaultview}{/if}</div></div><br />
    </div>
    {if $editallowed}<div class="submitWrapper">
        <input type="submit" name="Submit" value="Update Config"  class="button" />&nbsp;
        <input type="reset" name="Submit2" value="Reset" class="button" />
    </div>{/if}
</div>

<div id="confirm" class="mootabs_panel">
<div class="field">
    <div class="fieldItem"><span class="label">Notify Webmaster<span class="hintanchor" title="Should CMScout send an email to the webmaster address if a item needs to be reviewed?"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></span>
    <div class="inputboxwrapper">{if $editallowed}<input type="radio" name="notify" id="notify:yes" value="1" {if $configs.notify == 1}checked="checked"{/if} /><label for="notify:yes">Yes</label>
    <input name="notify" id="notify:no" type="radio" value="0" {if $configs.notify == 0}checked="checked"{/if} /><label for="notify:no">No</label>{else}{if $configs.notify}Yes{else}No{/if}{/if}</div></div><br />
    
    <div class="fieldItem"><span class="label">Confirm Articles<span class="hintanchor" title="If set to yes, an administrator will be required to publish any article that is posted."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></span>
    <div class="inputboxwrapper">{if $editallowed}<input type="radio" name="confirmarticle" id="confirmarticle:yesall" value="2" {if $configs.confirmarticle == 2}checked="checked"{/if} /><label for="confirmarticle:yesall">Yes for all.</label><input type="radio" name="confirmarticle" id="confirmarticle:yes" value="1" {if $configs.confirmarticle == 1}checked="checked"{/if} /><label for="confirmarticle:yes">Yes, except groups in exclusion list</label>
    <input name="confirmarticle" id="confirmarticle:no" type="radio" value="0" {if $configs.confirmarticle == 0}checked="checked"{/if} /><label for="confirmarticle:no">No</label>{else}{if $configs.confirmarticle == 2}Yes for All{elseif $configs.confirmarticle == 1}Yes, except groups in exclusion list{else}No{/if}{/if}</div></div><br />

    <div class="fieldItem"><span class="label">Confirm Photo Albums<span class="hintanchor" title="If set to yes, an administrator will be required to publish any photo album that is posted."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></span>
    <div class="inputboxwrapper">{if $editallowed}<input type="radio" name="confirmalbum" id="confirmalbum:yesall" value="2" {if $configs.confirmalbum == 2}checked="checked"{/if} /><label for="confirmalbum:yesall">Yes for all.</label><input type="radio" name="confirmalbum" id="confirmalbum:yes" value="1" {if $configs.confirmalbum == 1}checked="checked"{/if} /><label for="confirmalbum:yes">Yes, except groups in exclusion list</label>
    <input name="confirmalbum" id="confirmalbum:no" type="radio" value="0" {if $configs.confirmalbum == 0}checked="checked"{/if} /><label for="confirmalbum:no">No</label>{else}{if $configs.confirmalbum == 2}Yes for All{elseif $configs.confirmalbum == 1}Yes, except groups in exclusion list{else}No{/if}{/if}</div></div><br />

    <div class="fieldItem"><span class="label">Confirm Photos<span class="hintanchor" title="If set to yes, an administrator will be required to publish any photo that is posted to a published album."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></span>
    <div class="inputboxwrapper">{if $editallowed}<input type="radio" name="confirmphoto" id="confirmphoto:yesall" value="2" {if $configs.confirmphoto == 2}checked="checked"{/if} /><label for="confirmphoto:yesall">Yes for all.</label><input type="radio" name="confirmphoto" id="confirmphoto:yes" value="1" {if $configs.confirmphoto == 1}checked="checked"{/if} /><label for="confirmphoto:yes">Yes, except groups in exclusion list</label>
    <input name="confirmphoto" id="confirmphoto:no" type="radio" value="0" {if $configs.confirmphoto == 0}checked="checked"{/if} /><label for="confirmphoto:no">No</label>{else}{if $configs.confirmphoto == 2}Yes for All{elseif $configs.confirmphoto == 1}Yes, except groups in exclusion list{else}No{/if}{/if}</div></div><br />

    <div class="fieldItem"><span class="label">Confirm Events<span class="hintanchor" title="If set to yes, an administrator will be required to publish any event that is posted."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></span>
    <div class="inputboxwrapper">{if $editallowed}<input type="radio" name="confirmevent" id="confirmevent:yesall" value="2" {if $configs.confirmevent == 2}checked="checked"{/if} /><label for="confirmevent:yesall">Yes for all.</label><input type="radio" name="confirmevent" id="confirmevent:yes" value="1" {if $configs.confirmevent == 1}checked="checked"{/if} /><label for="confirmevent:yes">Yes, except groups in exclusion list</label>
    <input name="confirmevent" id="confirmevent:no" type="radio" value="0" {if $configs.confirmevent == 0}checked="checked"{/if} /><label for="confirmevent:no">No</label>{else}{if $configs.confirmevent == 2}Yes for All{elseif $configs.confirmevent == 1}Yes, except groups in exclusion list{else}No{/if}{/if}</div></div><br />

    <div class="fieldItem"><span class="label">Confirm Downloads<span class="hintanchor" title="If set to yes, an administrator will be required to publish any download that is posted."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></span>
    <div class="inputboxwrapper">{if $editallowed}<input type="radio" name="confirmdownload" id="confirmdownload:yesall" value="2" {if $configs.confirmdownload == 2}checked="checked"{/if} /><label for="confirmdownload:yesall">Yes for all.</label><input type="radio" name="confirmdownload" id="confirmdownload:yes" value="1" {if $configs.confirmdownload == 1}checked="checked"{/if} /><label for="confirmdownload:yes">Yes, except groups in exclusion list</label>
    <input name="confirmdownload" id="confirmdownload:no" type="radio" value="0" {if $configs.confirmdownload == 0}checked="checked"{/if} /><label for="confirmdownload:no">No</label>{else}{if $configs.confirmdownload == 2}Yes for All{elseif $configs.confirmdownload == 1}Yes, except groups in exclusion list{else}No{/if}{/if}</div></div><br />

    <div class="fieldItem"><span class="label">Confirm News<span class="hintanchor" title="If set to yes, an administrator will be required to publish any news that is posted."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></span>
    <div class="inputboxwrapper">{if $editallowed}<input type="radio" name="confirmnews" id="confirmnews:yesall" value="2" {if $configs.confirmnews == 2}checked="checked"{/if} /><label for="confirmnews:yesall">Yes for all.</label><input type="radio" name="confirmnews" id="confirmnews:yes" value="1" {if $configs.confirmnews == 1}checked="checked"{/if} /><label for="confirmnews:yes">Yes, except groups in exclusion list</label>
    <input name="confirmnews" id="confirmnews:no" type="radio" value="0" {if $configs.confirmnews == 0}checked="checked"{/if} /><label for="confirmnews:no">No</label>{else}{if $configs.confirmnews == 2}Yes for All{elseif $configs.confirmnews == 1}Yes, except groups in exclusion list{else}No{/if}{/if}</div></div><br />

    <div class="fieldItem"><span class="label">Confirm Comments<span class="hintanchor" title="If set to yes, an administrator will be required to publish any comments that is posted."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></span>
    <div class="inputboxwrapper">{if $editallowed}<input type="radio" name="confirmcomment" id="confirmcomment:yesall" value="2" {if $configs.confirmcomment == 2}checked="checked"{/if} /><label for="confirmcomment:yesall">Yes for all.</label><input type="radio" name="confirmcomment" id="confirmcomment:yes" value="1" {if $configs.confirmcomment == 1}checked="checked"{/if} /><label for="confirmcomment:yes">Yes, except groups in exclusion list</label>
    <input name="confirmcomment" id="confirmcomment:no" type="radio" value="0" {if $configs.confirmcomment == 0}checked="checked"{/if} /><label for="confirmcomment:no">No</label>{else}{if $configs.confirmcomment == 2}Yes for All{elseif $configs.confirmcomment == 1}Yes, except groups in exclusion list{else}No{/if}{/if}</div></div><br />

    <div class="fieldItem"><span class="label">Confirm Polls<span class="hintanchor" title="If set to yes, an administrator will be required to publish any poll that is posted."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></span>
    <div class="inputboxwrapper">{if $editallowed}<input type="radio" name="confirmpoll" id="confirmpoll:yesall" value="2" {if $configs.confirmpoll == 2}checked="checked"{/if} /><label for="confirmpoll:yesall">Yes for all.</label><input type="radio" name="confirmpoll" id="confirmpoll:yes" value="1" {if $configs.confirmpoll == 1}checked="checked"{/if} /><label for="confirmpoll:yes">Yes, except groups in exclusion list</label>
    <input name="confirmpoll" id="confirmpoll:no" type="radio" value="0" {if $configs.confirmpoll == 0}checked="checked"{/if} /><label for="confirmpoll:no">No</label>{else}{if $configs.confirmpoll == 2}Yes for All{elseif $configs.confirmpoll == 1}Yes, except groups in exclusion list{else}No{/if}{/if}</div></div><br />
    
    <div class="fieldItem"><span class="label"><b>Exclusion List:</b><span class="hintanchor" title="Groups that are excluded from required administrator intervention for new items."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></span>
<div class="inputboxwrapper"><ul class="checklist" style="height:10em;">
    {section name=group loop=$numgroups}
        {assign var="id" value=$group[group].id}
        <li title="confirm"><label for="exclusion{$group[group].id}">{if $editallowed}<input type="checkbox" value="1" name="exclusion[{$group[group].id}]" id="exclusion{$group[group].id}" {if $config.exclusion.$id == 1}checked="checked"{/if} />{else}{if $config.exclusion.$id == 1}<img src="{$tempdir}admin/images/unpublish.png" border="0" alt="-" title="-" />{else}<img src="{$tempdir}admin/images/publish.png" border="0" alt="x" title="x" />{/if}&nbsp;{/if}{$group[group].teamname}</label></li>
    {sectionelse}
        <li title="confirm">No available groups</li>
    {/section} 
</ul></div></div><br />
</div>
    {if $editallowed}<div class="submitWrapper">
        <input type="submit" name="Submit" value="Update Config"  class="button" />&nbsp;
        <input type="reset" name="Submit2" value="Reset" class="button" />
    </div>{/if}
</div>

</div></div></div></form>
