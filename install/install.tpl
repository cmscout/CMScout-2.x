<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>..:.:.:: CMScout {$version} Installer :: Step {$stage+1} ::.:.:..</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="install.css" rel="stylesheet" type="text/css" />
</head>
<body>
{if $stage == 0}
{literal}
<script type="text/javascript">
<!--
function show_hide(id)
{
	var item = null;
    hideall();
	if (document.getElementById)
	{
		item = document.getElementById(id);
	}
	else if (document.all)
	{
		item = document.all[id];
	}
	else if (document.layers)
	{
		item = document.layers[id];
	}

	if (item && item.style)
	{
		if (item.style.display == "none")
		{
			item.style.display = "";
		}
	}
	else if (item)
	{
		item.visibility = "show";
	}
}

function hideall()
{
  	var item = null;
    var i = 0;
    
    var id = ['welcome', 'database', 'admin', 'config', 'license', 'install', 'chmoding'];
    for(i=0;i<9;i++)
    {
        if (document.getElementById)
        {
            item = document.getElementById(id[i]);
        }
        else if (document.all)
        {
            item = document.all[id[i]];
        }
        else if (document.layers)
        {
            item = document.layers[id[i]];
        }
        
        if (item && item.style)
        {
            item.style.display = "none";
        } 
    }
}
-->
</script>
{/literal}
<script type="text/javascript">
{include file="../scripts/validator.tpl"}
</script>
{/if}
<form id="form1" method="post" action="install.php">
<center style="margin:20px;">
<div class="outside-box" style="height:135px;"><span style="text-align:left;"><img align="left" src="logo.gif" alt="CMScout" /></span>
        <div style="text-align:right;">

        </div>
	</div>
	<div class="outside-box" style="border-top: none;">
        ..:.:.:: CMScout {$version} Installer ::.:.:..
	</div>
	<div class="outside-box" style="border-top: none;">
{if $stage == 0}
		<table cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td valign="top">
                <div style="padding-bottom: 3px;">
             <div class="nav-title">Menu ::.:.:..</div>
                    <a href="javascript:show_hide('chmoding')" class="nav-link">Requirements</a>
                    <a href="javascript:show_hide('database')" class="nav-link">Database</a>
                    <a href="javascript:show_hide('admin')" class="nav-link">Administrator</a>
                    <a href="javascript:show_hide('config')" class="nav-link">Website Configuration</a>
                    <a href="javascript:show_hide('license')" class="nav-link">License Agreement</a>
                    <a href="javascript:show_hide('install')" class="nav-link">Install CMScout</a>
              </div>
  				</td>
				<td valign="top" width="100%" style="padding-left: 3px; padding-right: 3px;">
					<!-- NEWS-ITEM -->
                       <div style="padding-bottom: 3px;">
						<div class="inside-box" align="left">
                           <div>Step 1 :: Configuration ::.::.:..</div>
						</div>
                        <div class="inside-box" style="border-top: none;" align="left">
                            <div style="color:#ff0000">{$errors}</div>
                            <div id="chmoding">
			       <h1>Clean install of CMScout {$version}</h1>
                               <h1 align="left">Requirements</h1>
                                <p align="left">You must be running at least PHP 4.1.0 with support for MySQL. If no support modules are shown as available you should contact your hosting provider or review the relevant PHP installation documentation for advice. If "safe mode" is displayed below your PHP installation is running in that mode. This will impose limitations on remote administration and similar features. CMScout also requires the GD image extension library to allow the uploading of photos and other image functions.</p>
                                <table cellspacing="1" cellpadding="4" border="0" width="100%">
                                <tr align="left">
                                    <td width="20%">&bull;&nbsp;PHP >= 4.1.0: </td><td>{if $php == true}<span style="color:green">Yes{if $safemode == true}<span style="color:green">, Safe Mode</span>{/if}</span>{else}<span style="color:red">No</span>{/if} ({$php_version})</td>
                                </tr>
                                <tr align="left">
                                    <td>&bull;&nbsp;MySQL</td><td>{if $mysql3 == true}<span style="color:green">Available</span>{else}<span style="color:red">Not Available</span>{/if}</td>
                                </tr>
                                <tr align="left">
                                    <td>&bull;&nbsp;GD </td><td>{if $gd == true}<span style="color:green">Available</span>{else}<span style="color:red">Not Available</span>{/if}</td>
                                </tr>
                                </table>
                                <h1 align="left">File and Folder Permissions</h1>
                                <h2 align="left">Required</h2>
                                <p align="left">In order to function correctly CMScout needs to be able to access or write to certain files or directories. If you see "Not Found" you need to create the relevant file or directory. If you see "Unwriteable" you need to change the permissions on the file or directory to allow CMScout to write to it.</p>
                                <table cellspacing="1" cellpadding="4" border="0" width="100%">
                                <tr align="left">
                                    <td width="20%">&bull;&nbsp;photos/</td><td align="left">{if $exists.photos == true}<span style="color:green">Found</span>{else}<span style="color:red">Not Found</span>{/if}, {if $write.photos == true}<span style="color:green">Writable</span>{else}<span style="color:red">Unwritable</span>{/if}</td>
                                </tr>
                                <tr align="left">
                                    <td width="20%">&bull;&nbsp;photos/thumbnails</td><td align="left">{if $exists.photothumbs == true}<span style="color:green">Found</span>{else}<span style="color:red">Not Found</span>{/if}, {if $write.photothumbs == true}<span style="color:green">Writable</span>{else}<span style="color:red">Unwritable</span>{/if}</td>
                                </tr>
                                <tr align="left">
                                    <td>&bull;&nbsp;downloads/</td><td>{if $exists.downloads == true}<span style="color:green">Found</span>{else}<span style="color:red">Not Found</span>{/if}, {if $write.downloads == true}<span style="color:green">Writable</span>{else}<span style="color:red">Unwritable</span>{/if}</td>
                                </tr>
                                <tr align="left">
                                    <td>&bull;&nbsp;cache/</td><td>{if $exists.cache == true}<span style="color:green">Found</span>{else}<span style="color:red">Not Found</span>{/if}, {if $write.cache == true}<span style="color:green">Writable</span>{else}<span style="color:red">Unwritable</span>{/if}</td>
                                </tr>
                                <tr align="left">
                                    <td>&bull;&nbsp;templates_c/</td><td>{if $exists.templates_c == true}<span style="color:green">Found</span>{else}<span style="color:red">Not Found</span>{/if}, {if $write.templates_c == true}<span style="color:green">Writable</span>{else}<span style="color:red">Unwritable</span>{/if}</td>
                                </tr>
                                <tr align="left">
                                    <td>&bull;&nbsp;avatars/</td><td>{if $exists.avatars == true}<span style="color:green">Found</span>{else}<span style="color:red">Not Found</span>{/if}, {if $write.avatars == true}<span style="color:green">Writable</span>{else}<span style="color:red">Unwritable</span>{/if}</td>
                                </tr>
                                <tr align="left">
                                    <td>&bull;&nbsp;images/</td><td>{if $exists.images == true}<span style="color:green">Found</span>{else}<span style="color:red">Not Found</span>{/if}, {if $write.images == true}<span style="color:green">Writable</span>{else}<span style="color:red">Unwritable</span>{/if}</td>
                                </tr>
                                <tr align="left">
                                    <td>&bull;&nbsp;tiny_mce/plugins/ibrowser/scripts/phpThumb/cache/</td><td>{if $exists.tiny_mce == true}<span style="color:green">Found</span>{else}<span style="color:red">Not Found</span>{/if}, {if $write.tiny_mce == true}<span style="color:green">Writable</span>{else}<span style="color:red">Unwritable</span>{/if}</td>
                                </tr>
                                </table>
                                <h2 align="left">Optional</h2>
                                <p align="left">These files, directories or permissions are optional. The installation routines will attempt to use various techniques to complete if they do not exist or cannot be written to. However, the presence of these files, directories or permissions will speed installation.</p>
                                <table cellspacing="1" cellpadding="4" border="0" width="100%">
                                <tr align="left">
                                    <td width="20%">&bull;&nbsp;config.php</td><td>{if $exists.config == true}<span style="color:green">Found</span>{else}<span style="color:red">Not Found</span>{/if}, {if $write.config == true}<span style="color:green">Writable</span>{else}<span style="color:red">Unwriteable</span>{/if}</td>
                                </tr>
                                <tr align="left">
                                    <td width="20%">&bull;&nbsp;logfile.txt</td><td>{if $exists.logfile == true}<span style="color:green">Found</span>{else}<span style="color:red">Not Found</span>{/if}, {if $write.logfile == true}<span style="color:green">Writable</span>{else}<span style="color:red">Unwriteable</span>{/if}</td>
                                </tr>
                                </table>
                                {if $filesok == false}<h1 style="color:red">TESTS FAILED</h1>{/if}
                                <div align="center"><input type="button" value="Next >" onclick="show_hide('database')" class="button" /></div>
                            </div>
                            <div id="database" style="display: none;">
                                <fieldset class="formlist">
                                <legend>Database Configuration</legend>
                                Please fill in the required details. Make sure that the database has already been created and that the database user has permision to add tables to the database.<br /><br />
                                <div class="field">

                                <div class="fieldItem"><label for="dbhostname" class="label">Database server hostname<span class="hintanchor" title="Required :: Name of the mysql server that CMScout must communicate with (Normally localhost)."><img src="help.png" alt="[?]"/></span></label>
                                <div class="inputboxwrapper"><input type="text" id="dbhostname" name="dbhostname" size="30" value="{if $database.hostname}{$database.hostname}{else}localhost{/if}" class="inputbox" onblur="checkElement('dbhostname', 'text', true, 0, 0, '');"/><br /><span class="error" id="dbhostnameError">Required: Please ensure that the hostname is entered correctly.</span></div></div><br />

                                <div class="fieldItem"><label for="dbport" class="label">Database server port<span class="hintanchor" title="Required :: Port that CMScout must communicate to MySQL through. The default is 3306."><img src="help.png" alt="[?]"/></span></label>
                                <div class="inputboxwrapper"><input type="text" id="dbport" name="dbport" size="30" value="{if $database.port}{$database.port}{else}3306{/if}" class="inputbox" onblur="checkElement('dbport', 'number', true, 0, 0, '');"/><br /><span class="error" id="dbportError">Required: May only be numbers.</span></div></div><br />

                                <div class="fieldItem"><label for="databasename" class="label">Database name<span class="hintanchor" title="Required :: Name of the database that CMScout must store all the tables used for data."><img src="help.png" alt="[?]"/></span></label>
                                <div class="inputboxwrapper"><input type="text" id="databasename" name="databasename" size="30" value="{$database.name}" class="inputbox" onblur="checkElement('databasename', 'text', true, 0, 0, '');"/><br /><span class="error" id="databasenameError">Required: Please enter the database name.</span></div></div><br />

                                <div class="fieldItem"><label for="databaseusername" class="label">Database username<span class="hintanchor" title="Required :: Username that has permisions to access the database and to create tables. If your not sure how to set this up, please contact your hosting provider."><img src="help.png" alt="[?]"/></span></label>
                                <div class="inputboxwrapper"><input type="text" id="databaseusername" name="databaseusername" size="30" value="{$database.username}" class="inputbox" onblur="checkElement('databaseusername', 'text', true, 0, 0, '');"/><br /><span class="error" id="databaseusernameError">Required: Please enter the database username.</span></div></div><br />

                                <div class="fieldItem"><label for="databasepassword" class="label">Database password<span class="hintanchor" title="Required :: Password for the database user. CMScout requires the database user to have a password set."><img src="help.png" alt="[?]"/></span></label>
                                <div class="inputboxwrapper"><input type="password" id="databasepassword" name="databasepassword" size="30" value="{$database.password}" class="inputbox" onblur="checkElement('databasepassword', 'text', true, 0, 0, '');"/><br /><span class="error" id="databasepasswordError">Required: Please enter the password for the database user.</span></div></div><br />

                                <div class="fieldItem"><label for="dbprefix" class="label">Table Prefix<span class="hintanchor" title="Required :: Prefix that will be placed in front of all CMScout tables."><img src="help.png" alt="[?]"/></span></label>
                                <div class="inputboxwrapper"><input type="text" id="dbprefix" name="dbprefix" size="30" {if $database.prefix == ""}value="cms_"{else}value="{$database.prefix}"{/if} class="inputbox" onblur="checkElement('dbprefix', 'custom', true, 0, 0, /^[a-zA-Z0-9_]*$/);" /><br /><span class="error" id="dbprefixError">Required: Please enter a short table prefix. It may only contain alphanumeric characters and the underscore.</span></div></div><br />
                                </div>
                                </fieldset>
                                <div align="center"><input type="button" value="< Previous" onclick="show_hide('chmoding')" class="button" />&nbsp;
                                <input type="button" value="Next >" onclick="show_hide('admin')" class="button" /></div>
                            </div>
                            <div id="admin" style="display: none;">
<fieldset class="formlist">
<legend>Administrator Configuration</legend>
Please fill in the required details.<br /><br />
<div class="field">
<div class="fieldItem"><label for="adminusername" class="label">Administrator Username<span class="hintanchor" title="Required :: This will be the default administrator. This will initially be the only user which will be able to access the administration panel."><img src="help.png" alt="[?]"/></span></label> 
<div class="inputboxwrapper"><input type="text" id="adminusername" name="adminusername" size="30" value="{if $admin.name}{$admin.name}{else}Admin{/if}" class="inputbox" onblur="checkElement('adminusername', 'text', true, 0, 0, '');" /><br /><span class="error" id="adminusernameError">Required: Please enter a username for the default administrator.</span></div></div><br />

<div class="fieldItem"><label for="adminpassword" class="label">Administrator Password<span class="hintanchor" title="Required :: Password for the administrator user"><img src="help.png" alt="[?]"/></span></label> 
<div class="inputboxwrapper"><input type="password" id="adminpassword" name="adminpassword" size="30" value="{$admin.password}" class="inputbox" onblur="checkElement('adminpassword', 'text', true, 6, 0, '');" /><br /><span class="error" id="adminpasswordError">Required: Please enter a password that is longer then 6 characters.</span></div></div><br />

<div class="fieldItem"><label for="adminrepass" class="label">Retype the password<span class="hintanchor" title="Required :: Retype the above password."><img src="help.png" alt="[?]"/></span></label>
 <div class="inputboxwrapper"><input type="password" id="adminrepass" name="adminrepass" size="30" value="{$admin.repass}" class="inputbox" onblur="checkElement('adminrepass', 'duplicate', true, 0, 0, 'adminpassword');" /><br /><span class="error" id="adminrepassError">Required: Does not match above password.</span></div></div><br />

<div class="fieldItem"><label for="adminfirstname" class="label">Administrator First Name<span class="hintanchor" title="Required :: The administrators first name."><img src="help.png" alt="[?]"/></span></label> 
<div class="inputboxwrapper"><input type="text" id="adminfirstname" name="adminfirstname" size="30" value="{if $admin.first}{$admin.first}{else}Admin{/if}" class="inputbox" onblur="checkElement('adminfirstname', 'text', true, 0, 0, '');" /><br /><span class="error" id="adminfirstnameError">Required: Please enter the first name of the default administrator.</span></div></div><br />

<div class="fieldItem"><label for="adminlastname" class="label">Administrator Lastname<span class="hintanchor" title="Required :: The administrators last name."><img src="help.png" alt="[?]"/></span></label> 
<div class="inputboxwrapper"><input type="text" id="adminlastname" name="adminlastname" size="30" value="{if $admin.last}{$admin.last}{else}Admin{/if}" class="inputbox" onblur="checkElement('adminlastname', 'text', true, 0, 0, '');" /><br /><span class="error" id="adminlastnameError">Required: Please enter the last name of the default administrator.</span></div></div><br />

<div class="fieldItem"><label for="adminemail" class="label">Administrator email address<span class="hintanchor" title="Required :: Administrator user email address."><img src="help.png" alt="[?]"/></span></label>
 <div class="inputboxwrapper"><input type="text" id="adminemail" name="adminemail" size="30" value="{$admin.email}" class="inputbox" onblur="checkElement('adminemail', 'email', true, 0, 0, '');" /><br /><span class="error" id="adminemailError">Required: Please enter a valid email address.</span></div></div><br />

<div class="fieldItem"><label for="webemail" class="label">Website email address<span class="hintanchor" title="Required :: Webmaster email address. CMScout will send administrative emails to this address, and will sned emails to users from this address."><img src="help.png" alt="[?]"/></span></label>
 <div class="inputboxwrapper"><input type="text" id="webemail" name="webemail" size="30" value="{$config.webemail}" class="inputbox" onblur="checkElement('webemail', 'email', true, 0, 0, '');" /><br /><span class="error" id="webemailError">Required: Please enter a valid email address.</span></div></div><br />
</div>
</fieldset>
<div align="center"><input type="button" value="< Previous" onclick="show_hide('database')" class="button" />&nbsp;
<input type="button" value="Next >" onclick="show_hide('config')" class="button" /></div>
                            </div>
                            <div id="config" style="display: none;">
<fieldset class="formlist">
<legend>Website Configuration</legend>
Please fill in the required details.<br /><br />
<div class="field"> 
<div class="fieldItem"><label for="cmscoutaddress" class="label">Website Address<span class="hintanchor" title="Required :: The URL that users will use to access your website."><img src="help.png" alt="[?]"/></span></label>
 <div class="inputboxwrapper"><input type="text" id="cmscoutaddress" name="cmscoutaddress" size="30" {if $config.address == ""}value="http://{$cmscoutaddress}"{else}value="{$config.address}"{/if} class="inputbox" onblur="checkElement('cmscoutaddress', 'text', true, 0, 0, '');" /><br /><span class="error" id="cmscoutaddressError">Required: Please enter a valid URL.</span></div></div><br />

<div class="fieldItem"><label for="zone" class="label">Server Timezone<span class="hintanchor" title="Required :: The timezone that the server is in."><img src="help.png" alt="[?]"/></span></label>
 <div class="inputboxwrapper"><select name="zone" id="zone" class="inputbox">
<option value="14" {if $config.timezone == 14}selected{/if}>GMT/UTC-11.0</option>
<option value="15" {if $config.timezone == 15}selected{/if}>GMT/UTC-10.0</option>

<option value="16" {if $config.timezone == 16}selected{/if}>GMT/UTC-9.0</option>
<option value="17" {if $config.timezone == 17}selected{/if}>GMT/UTC-8.0</option>
<option value="18" {if $config.timezone == 18}selected{/if}>GMT/UTC-7.0</option>
<option value="19" {if $config.timezone == 19}selected{/if}>GMT/UTC-6.0</option>
<option value="20" {if $config.timezone == 20}selected{/if}>GMT/UTC-5.0</option>
<option value="21" {if $config.timezone == 21}selected{/if}>GMT/UTC-4.0</option>

<option value="22" {if $config.timezone == 22}selected{/if}>GMT/UTC-3.0</option>
<option value="23" {if $config.timezone == 23}selected{/if}>GMT/UTC-2.0</option>
<option value="24" {if $config.timezone == 24}selected{/if}>GMT/UTC-1.0</option>
<option value="1" {if $config.timezone == 1 || !$config.timezone}selected{/if}>GMT/UTC</option>
<option value="2" {if $config.timezone == 2}selected{/if}>GMT/UTC+1.0</option>
<option value="3" {if $config.timezone == 3}selected{/if}>GMT/UTC+2.0</option>

<option value="4" {if $config.timezone == 4}selected{/if}>GMT/UTC+3.0</option>
<option value="26" {if $config.timezone == 26}selected{/if}>GMT/UTC+3.5</option>
<option value="5" {if $config.timezone == 5}selected{/if}>GMT/UTC+4.0</option>
<option value="6" {if $config.timezone == 6}selected{/if}>GMT/UTC+5.0</option>
<option value="27" {if $config.timezone == 27}selected{/if}>GMT/UTC+5.5</option>
<option value="7" {if $config.timezone == 7}selected{/if}>GMT/UTC+6.0</option>

<option value="8" {if $config.timezone == 8}selected{/if}>GMT/UTC+7.0</option>
<option value="9" {if $config.timezone == 9}selected{/if}>GMT/UTC+8.0</option>
<option value="10" {if $config.timezone == 10}selected{/if}>GMT/UTC+9.0</option>
<option value="28" {if $config.timezone == 11}selected{/if}>GMT/UTC+9.5</option>
<option value="11" {if $config.timezone == 28}selected{/if}>GMT/UTC+10.0</option>
<option value="12" {if $config.timezone == 12}selected{/if}>GMT/UTC+11.0</option>

<option value="13" {if $config.timezone == 13}selected{/if}>GMT/UTC+12</option>
</select></div></div><br />

<div class="fieldItem"><label for="troopname" class="label">Website Title<span class="hintanchor" title="Required ::The title or name of your website."><img src="help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><input type="text" id="troopname" name="troopname" size="30" value="{if $config.troopname}{$config.troopname}{else}CMScout {$version}{/if}" class="inputbox" onblur="checkElement('troopname', 'text', true, 0, 0, '');" /><br /><span class="error" id="troopnameError">Required: Please enter a name for your website.</span></div></div><br />

<div class="fieldItem"><label for="troopslogon" class="label">Website Description<span class="hintanchor" title="Optional :: A short description of the website."><img src="help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><input type="text" id="troopslogon" name="troopslogon" size="30" value="{$config.troopdesc}" class="inputbox" /></div></div><br />

<div class="fieldItem"><span class="label">Sample content<span class="hintanchor" title="Optional :: Should CMScout install sample content?"><img src="help.png" alt="[?]"/></span></span>
 <div class="inputboxwrapper"><label for="sample:yes"><input type="radio" id="sample:yes" name="sample" value="2" {if $config.sample == "" || $config.sample == 2}checked="checked"{/if} />Yes</label>&nbsp;
<label for="sample:no"><input type="radio" id="sample:no" name="sample" value="1" {if $config.sample == 1}checked="checked"{/if} />No</label></div></div><br />
</div>
</fieldset>
<div align="center"><input type="button" value="< Previous" onclick="show_hide('admin')" class="button" />&nbsp;
<input type="button" value="Next >" onclick="show_hide('license')" class="button" /></div>
                            </div>
                            <div id="license" style="display: none;">
<fieldset class="formlist">
<legend>License Agreement</legend>
Please read the license agreement carefully before accepting it.<br /><br />
<div class="field"><div style="width:100%;height:500px;overflow:auto;">
<pre>GNU GENERAL PUBLIC LICENSE
Version 2, June 1991

Copyright (C) 1989, 1991 Free Software Foundation, Inc.
51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
Everyone is permitted to copy and distribute verbatim copies
of this license document, but changing it is not allowed.

Preamble

The licenses for most software are designed to take away your
freedom to share and change it.  By contrast, the GNU General Public
License is intended to guarantee your freedom to share and change free
software--to make sure the software is free for all its users.  This
General Public License applies to most of the Free Software
Foundation's software and to any other program whose authors commit to
using it.  (Some other Free Software Foundation software is covered by
the GNU Library General Public License instead.)  You can apply it to
your programs, too.

When we speak of free software, we are referring to freedom, not
price.  Our General Public Licenses are designed to make sure that you
have the freedom to distribute copies of free software (and charge for
this service if you wish), that you receive source code or can get it
if you want it, that you can change the software or use pieces of it
in new free programs; and that you know you can do these things.

To protect your rights, we need to make restrictions that forbid
anyone to deny you these rights or to ask you to surrender the rights.
These restrictions translate to certain responsibilities for you if you
distribute copies of the software, or if you modify it.

For example, if you distribute copies of such a program, whether
gratis or for a fee, you must give the recipients all the rights that
you have.  You must make sure that they, too, receive or can get the
source code.  And you must show them these terms so they know their
rights.

We protect your rights with two steps: (1) copyright the software, and
(2) offer you this license which gives you legal permission to copy,
distribute and/or modify the software.

Also, for each author's protection and ours, we want to make certain
that everyone understands that there is no warranty for this free
software.  If the software is modified by someone else and passed on, we
want its recipients to know that what they have is not the original, so
that any problems introduced by others will not reflect on the original
authors' reputations.

Finally, any free program is threatened constantly by software
patents.  We wish to avoid the danger that redistributors of a free
program will individually obtain patent licenses, in effect making the
program proprietary.  To prevent this, we have made it clear that any
patent must be licensed for everyone's free use or not licensed at all.

The precise terms and conditions for copying, distribution and
modification follow.

GNU GENERAL PUBLIC LICENSE
TERMS AND CONDITIONS FOR COPYING, DISTRIBUTION AND MODIFICATION

0. This License applies to any program or other work which contains
a notice placed by the copyright holder saying it may be distributed
under the terms of this General Public License.  The "Program", below,
refers to any such program or work, and a "work based on the Program"
means either the Program or any derivative work under copyright law:
that is to say, a work containing the Program or a portion of it,
either verbatim or with modifications and/or translated into another
language.  (Hereinafter, translation is included without limitation in
the term "modification".)  Each licensee is addressed as "you".

Activities other than copying, distribution and modification are not
covered by this License; they are outside its scope.  The act of
running the Program is not restricted, and the output from the Program
is covered only if its contents constitute a work based on the
Program (independent of having been made by running the Program).
Whether that is true depends on what the Program does.

1. You may copy and distribute verbatim copies of the Program's
source code as you receive it, in any medium, provided that you
conspicuously and appropriately publish on each copy an appropriate
copyright notice and disclaimer of warranty; keep intact all the
notices that refer to this License and to the absence of any warranty;
and give any other recipients of the Program a copy of this License
along with the Program.

You may charge a fee for the physical act of transferring a copy, and
you may at your option offer warranty protection in exchange for a fee.

2. You may modify your copy or copies of the Program or any portion
of it, thus forming a work based on the Program, and copy and
distribute such modifications or work under the terms of Section 1
above, provided that you also meet all of these conditions:

a) You must cause the modified files to carry prominent notices
stating that you changed the files and the date of any change.

b) You must cause any work that you distribute or publish, that in
whole or in part contains or is derived from the Program or any
part thereof, to be licensed as a whole at no charge to all third
parties under the terms of this License.

c) If the modified program normally reads commands interactively
when run, you must cause it, when started running for such
interactive use in the most ordinary way, to print or display an
announcement including an appropriate copyright notice and a
notice that there is no warranty (or else, saying that you provide
a warranty) and that users may redistribute the program under
these conditions, and telling the user how to view a copy of this
License.  (Exception: if the Program itself is interactive but
does not normally print such an announcement, your work based on
the Program is not required to print an announcement.)

These requirements apply to the modified work as a whole.  If
identifiable sections of that work are not derived from the Program,
and can be reasonably considered independent and separate works in
themselves, then this License, and its terms, do not apply to those
sections when you distribute them as separate works.  But when you
distribute the same sections as part of a whole which is a work based
on the Program, the distribution of the whole must be on the terms of
this License, whose permissions for other licensees extend to the
entire whole, and thus to each and every part regardless of who wrote it.

Thus, it is not the intent of this section to claim rights or contest
your rights to work written entirely by you; rather, the intent is to
exercise the right to control the distribution of derivative or
collective works based on the Program.

In addition, mere aggregation of another work not based on the Program
with the Program (or with a work based on the Program) on a volume of
a storage or distribution medium does not bring the other work under
the scope of this License.

3. You may copy and distribute the Program (or a work based on it,
under Section 2) in object code or executable form under the terms of
Sections 1 and 2 above provided that you also do one of the following:

a) Accompany it with the complete corresponding machine-readable
source code, which must be distributed under the terms of Sections
1 and 2 above on a medium customarily used for software interchange; or,

b) Accompany it with a written offer, valid for at least three
years, to give any third party, for a charge no more than your
cost of physically performing source distribution, a complete
machine-readable copy of the corresponding source code, to be
distributed under the terms of Sections 1 and 2 above on a medium
customarily used for software interchange; or,

c) Accompany it with the information you received as to the offer
to distribute corresponding source code.  (This alternative is
allowed only for noncommercial distribution and only if you
received the program in object code or executable form with such
an offer, in accord with Subsection b above.)

The source code for a work means the preferred form of the work for
making modifications to it.  For an executable work, complete source
code means all the source code for all modules it contains, plus any
associated interface definition files, plus the scripts used to
control compilation and installation of the executable.  However, as a
special exception, the source code distributed need not include
anything that is normally distributed (in either source or binary
form) with the major components (compiler, kernel, and so on) of the
operating system on which the executable runs, unless that component
itself accompanies the executable.

If distribution of executable or object code is made by offering
access to copy from a designated place, then offering equivalent
access to copy the source code from the same place counts as
distribution of the source code, even though third parties are not
compelled to copy the source along with the object code.

4. You may not copy, modify, sublicense, or distribute the Program
except as expressly provided under this License.  Any attempt
otherwise to copy, modify, sublicense or distribute the Program is
void, and will automatically terminate your rights under this License.
However, parties who have received copies, or rights, from you under
this License will not have their licenses terminated so long as such
parties remain in full compliance.

5. You are not required to accept this License, since you have not
signed it.  However, nothing else grants you permission to modify or
distribute the Program or its derivative works.  These actions are
prohibited by law if you do not accept this License.  Therefore, by
modifying or distributing the Program (or any work based on the
Program), you indicate your acceptance of this License to do so, and
all its terms and conditions for copying, distributing or modifying
the Program or works based on it.

6. Each time you redistribute the Program (or any work based on the
Program), the recipient automatically receives a license from the
original licensor to copy, distribute or modify the Program subject to
these terms and conditions.  You may not impose any further
restrictions on the recipients' exercise of the rights granted herein.
You are not responsible for enforcing compliance by third parties to
this License.

7. If, as a consequence of a court judgment or allegation of patent
infringement or for any other reason (not limited to patent issues),
conditions are imposed on you (whether by court order, agreement or
otherwise) that contradict the conditions of this License, they do not
excuse you from the conditions of this License.  If you cannot
distribute so as to satisfy simultaneously your obligations under this
License and any other pertinent obligations, then as a consequence you
may not distribute the Program at all.  For example, if a patent
license would not permit royalty-free redistribution of the Program by
all those who receive copies directly or indirectly through you, then
the only way you could satisfy both it and this License would be to
refrain entirely from distribution of the Program.

If any portion of this section is held invalid or unenforceable under
any particular circumstance, the balance of the section is intended to
apply and the section as a whole is intended to apply in other
circumstances.

It is not the purpose of this section to induce you to infringe any
patents or other property right claims or to contest validity of any
such claims; this section has the sole purpose of protecting the
integrity of the free software distribution system, which is
implemented by public license practices.  Many people have made
generous contributions to the wide range of software distributed
through that system in reliance on consistent application of that
system; it is up to the author/donor to decide if he or she is willing
to distribute software through any other system and a licensee cannot
impose that choice.

This section is intended to make thoroughly clear what is believed to
be a consequence of the rest of this License.

8. If the distribution and/or use of the Program is restricted in
certain countries either by patents or by copyrighted interfaces, the
original copyright holder who places the Program under this License
may add an explicit geographical distribution limitation excluding
those countries, so that distribution is permitted only in or among
countries not thus excluded.  In such case, this License incorporates
the limitation as if written in the body of this License.

9. The Free Software Foundation may publish revised and/or new versions
of the General Public License from time to time.  Such new versions will
be similar in spirit to the present version, but may differ in detail to
address new problems or concerns.

Each version is given a distinguishing version number.  If the Program
specifies a version number of this License which applies to it and "any
later version", you have the option of following the terms and conditions
either of that version or of any later version published by the Free
Software Foundation.  If the Program does not specify a version number of
this License, you may choose any version ever published by the Free Software
Foundation.

10. If you wish to incorporate parts of the Program into other free
programs whose distribution conditions are different, write to the author
to ask for permission.  For software which is copyrighted by the Free
Software Foundation, write to the Free Software Foundation; we sometimes
make exceptions for this.  Our decision will be guided by the two goals
of preserving the free status of all derivatives of our free software and
of promoting the sharing and reuse of software generally.

NO WARRANTY

11. BECAUSE THE PROGRAM IS LICENSED FREE OF CHARGE, THERE IS NO WARRANTY
FOR THE PROGRAM, TO THE EXTENT PERMITTED BY APPLICABLE LAW.  EXCEPT WHEN
OTHERWISE STATED IN WRITING THE COPYRIGHT HOLDERS AND/OR OTHER PARTIES
PROVIDE THE PROGRAM "AS IS" WITHOUT WARRANTY OF ANY KIND, EITHER EXPRESSED
OR IMPLIED, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE.  THE ENTIRE RISK AS
TO THE QUALITY AND PERFORMANCE OF THE PROGRAM IS WITH YOU.  SHOULD THE
PROGRAM PROVE DEFECTIVE, YOU ASSUME THE COST OF ALL NECESSARY SERVICING,
REPAIR OR CORRECTION.

12. IN NO EVENT UNLESS REQUIRED BY APPLICABLE LAW OR AGREED TO IN WRITING
WILL ANY COPYRIGHT HOLDER, OR ANY OTHER PARTY WHO MAY MODIFY AND/OR
REDISTRIBUTE THE PROGRAM AS PERMITTED ABOVE, BE LIABLE TO YOU FOR DAMAGES,
INCLUDING ANY GENERAL, SPECIAL, INCIDENTAL OR CONSEQUENTIAL DAMAGES ARISING
OUT OF THE USE OR INABILITY TO USE THE PROGRAM (INCLUDING BUT NOT LIMITED
TO LOSS OF DATA OR DATA BEING RENDERED INACCURATE OR LOSSES SUSTAINED BY
YOU OR THIRD PARTIES OR A FAILURE OF THE PROGRAM TO OPERATE WITH ANY OTHER
PROGRAMS), EVEN IF SUCH HOLDER OR OTHER PARTY HAS BEEN ADVISED OF THE
POSSIBILITY OF SUCH DAMAGES.</pre>
</div>

<div class="fieldItem"><span class="label">Accept the license agreement<span class="hintanchor" title="Required :: Do you accept the above user license agreedment?"><img src="help.png" alt="[?]"/></span></span>
 <div class="inputboxwrapper"><label for="licenseagreement:yes"><input type="radio" id="licenseagreement:yes" name="licenseagreement" value="1" {if $licenseagreement == 1}checked="checked"{/if} />Yes</label>&nbsp;
<label for="licenseagreement:no"><input type="radio" id="licenseagreement:no" name="licenseagreement" value="0" {if $licenseagreement == 0}checked="checked"{/if} />No</label></div></div><br />
</div>
</fieldset>
 <div align="center"><input type="button" value="< Previous" onclick="show_hide('config')" class="button" />&nbsp;
            <input type="button" value="Next >" onclick="show_hide('install')" class="button" /></div>
                            </div>
                            <div id="install" style="display: none;">
            
            <p>
		CMScout is now ready to install your website. Please make sure that all the required information has been filled in and is correct before you click on the install button below.
            </p>
		<p>
			If you have any queries about CMScout, or if you encounter any serious problems with installating please contact us on our forum at <a href="http://www.cmscout.za.net">www.cmscout.za.net</a>
		</p>
            <div align="center"><input type="button" value="< Previous" onclick="show_hide('license')" class="button" />&nbsp;<input type="submit" id="submit" name="submit" value="Install" class="button" /></div>
                            </div>
                        </div>
                    </div>
				</td>
			</tr>
		</table>
{elseif $stage == 1}
<div style="padding-bottom: 3px;">
	<div class="inside-box" align="left">
		Step 2 :: Upload config.php ::.::.:..
	</div>

	<div class="inside-box" style="border-top: none;" align="left">
		<p>Unfortunately CMScout could not write the configuration information directly to your config.php. This may be because the file does not exist or is not writeable.</p>

		<p>You may download the complete config.php to your own PC. You will then need to upload the file manually, replacing any existing config.php in your CMScout root directory. Please remember to upload the file in ASCII format (see your FTP application documentation if you are unsure how to achieve this). When you have uploaded the config.php please click "Done" to move to the next stage</p>
		<div align="center"><a href="config.php?data={$database}"><input name="down" type="button" value="Download config.php" class="button" /></a>&nbsp; <input name="dldone" type="submit" value="Done" class="button" /></div>
		<input name="database" type="hidden" value="{$database}" />
		<input name="admin" type="hidden" value="{$admin}" />
		<input name="config" type="hidden" value="{$config}" />
		<input name="sample" type="hidden" value="{$sample}" />
		<input name="licenseagreement" type="hidden" value="{$licenseagreement}" />
	</div>
</div>{elseif $stage == 2}<div style="padding-bottom: 3px;">
	<div class="inside-box" align="left">
		<div style="color:#ff0000">Error ::.::.:..</div>
	</div>

	<div class="inside-box" style="border-top: none;" align="left">
 <h2>CMScout {$version} could not install</h2>
	    <p>CMScout {$version} could not install as there were 1 or more errors while configuring the database. Please empty the database, and config.php and try to reinstall. If the problem persists please contact us on the CMScout forums at <a href="www.cmscout.za.net">www.cmscout.za.net</a> and include the errors shown below</p>

<p>The errors encountered by CMScout are listed below:</p>
	    <div style="color:#ff0000">{$errors}</div>
	</div>
</div>
{elseif $stage == 3}
<div style="padding-bottom: 3px;">
	<div class="inside-box" align="left">
		Step 3 :: Congratulations ::.::.:..
	</div>

	<div class="inside-box" style="border-top: none;" align="left">
        <h2>Congratulations</h2>
        <p>CMScout {$version} has successfully installed. Please ensure that the config.php file exists and has the correct database information stored in it.<br />
        Before you start using CMScout please delete the install directory. CMScout will refuse to start if the install directory has not been deleted.</p>
        <p>
        You can visit your site by going to <a href="{$cmscoutaddress}">{$cmscoutaddress}</a>
        </p>
	</div>
</div>
{/if}
	</div>
<input type="hidden" name="stage" value="{$stage}" id="stage" />
</center>
</form>
{if $gotoplace != ""}<script>show_hide('{$gotoplace}');</script>{/if}
	<div class="outside-box" style="border-top: none;">
		 {eval var=$copyright}
	</div>
    </body>
    {if $repost}
<script type="text/javascript">
{section name=posts loop=$repost}
    document.getElementById('{$repost[posts].id}').value = '{$repost[posts].value}';
{/section}
</script>
{/if}
</html>

