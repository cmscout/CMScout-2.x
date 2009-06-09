{include file = "admin/admin_header.tpl"}
<h2>You do not have access to the administration panel</h2>
{if !$loggedin}
<div align="center">
<p>You are not currently logged in, this may be the reason why you can not access the administration panel. Use the form below to log in</p>
<form name="form1" method="post" action="logon.php?redirect=administration_panel">
<fieldset class="formlist">
<div class="field">
<label for="username" class="label">Username</label>
<div class="inputboxwrapper"><input type="text" name="username" id="username" size="20" maxlength="50" class="inputbox" /></div><br />
<label for="password" class="label">Password</label>
<div class="inputboxwrapper"><input type="password" name="password" id="password" size="20" maxlength="50" class="inputbox" /></div><br />
</div>
<div class="submitWrapper">
<input type="submit" name="Login" id="Login" value="Login" class="button" />
</div>
</fieldset>
</form>
</div>
{else}
<p>You do not have privilages to access the administration panel. Please return to the <a href="index.php">index page</a> of the website</p>
{/if}
{include file = "admin/admin_footer.tpl"}
