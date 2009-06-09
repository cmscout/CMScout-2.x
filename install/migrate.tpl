<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>..:.:.:: CMScout {$version} Migrator :: Step {$stage+1} ::.:.:..</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="install.css" rel="stylesheet" type="text/css" />

<script type="text/JavaScript" src="../includes/functions.js"></script>
{section name=scripts loop=$scriptIncludeNum}
<script src="../{$scriptInclude[scripts]}" type="text/javascript"></script>
{/section}

<script type="text/javascript">
{$tinyMCEGzip}
</script>

<script type="text/javascript">
{$tinyMCE}
</script>

{section name=css loop=$cssIncludeNum}
<link rel="stylesheet" href="{$cssInclude[css]}" type="text/css" media="screen" />
{/section}
{literal}
<script type="text/javascript">
function initilize() 
{
    {/literal}{eval var=$onDomReady}{literal}
}
window.onDomReady(initilize); 
</script>
{/literal}
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
    
    var id = ['database', 'admin', 'config', 'license', 'install', 'chmoding'];
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
<form id="form1" method="post" action="migrate.php">
<center style="margin:20px;">
<div class="outside-box" style="height:135px;"><span style="text-align:left;"><img align="left" src="logo.gif" alt="CMScout" /></span>
        <div style="text-align:right;">

        </div>
	</div>
	<div class="outside-box" style="border-top: none;">
        ..:.:.:: CMScout {$version} Migrator ::.:.:..
	</div>
	<div class="outside-box" style="border-top: none;">
{if $stage == 0}
				{if $configstage == 0}
		<table cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td valign="top">
                <div style="padding-bottom: 3px;">
             <div class="nav-title">Menu ::.:.:..</div>
                    <a href="javascript:show_hide('chmoding')" class="nav-link">Requirements</a>
                    <a href="javascript:show_hide('database')" class="nav-link">Database</a>
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
			       <h1>Migrating CMScout 1.x to CMScout {$version}</h1>
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
                                Please enter the details for your existing CMScout 1.x database.<br /><br />
                                <div class="field">
                                <div class="fieldItem"><label for="dbhostname" class="label">Database server hostname<span class="hintanchor" title="Required :: Name of the mysql server that CMScout must communicate with (Normally localhost)."><img src="help.png" alt="[?]"/></span></label>
                                <div class="inputboxwrapper"><input type="text" id="dbhostname" name="dbhostname" size="30" value="{if $database.hostname}{$database.hostname}{else}localhost{/if}" class="inputbox" onblur="checkElement('dbhostname', 'text', true, 0, 0, '');"/><br /><span class="error" id="dbhostnameError">Required: Please ensure that the hostname is entered correctly.</span></div></div><br />

                                <div class="fieldItem"><label for="dbport" class="label">Database server port<span class="hintanchor" title="Required :: Port that CMScout must communicate to MySQL through. The default is 3306."><img src="help.png" alt="[?]"/></span></label>
                                <div class="inputboxwrapper"><input type="text" id="dbport" name="dbport" size="30" value="{if $database.port}{$database.port}{else}3306{/if}" class="inputbox" onblur="checkElement('dbport', 'number', true, 0, 0, '');"/><br /><span class="error" id="dbportError">Required: May only be numbers.</span></div></div><br />

                                <div class="fieldItem"><label for="databasename" class="label">Database name<span class="hintanchor" title="Required :: Name of the database that CMScout will convert into 2.00 format."><img src="help.png" alt="[?]"/></span></label>
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
                                <input type="submit" value="Next >" class="button" /></div>
                            </div>
			                            </div>
                    </div>
				</td>
			</tr>
		</table>
			    {elseif $configstage == 1}
			    		<table cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td valign="top">
                <div style="padding-bottom: 3px;">
             <div class="nav-title">Menu ::.:.:..</div>
                    <a href="javascript:show_hide('database')" class="nav-link">Version Check</a>
                    <a href="javascript:show_hide('config')" class="nav-link">Configuration</a>
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
				<div id="database">
					<p>CMScout has detected the site "{$oldname}" which is running CMScout v{$oldversion}. Continuing with this migration script will migrate that installation to CMScout {$version}. This is a irreversable proccess, so it is advisable to have an up to date backup of your existing CMScout database before continuing.</p>
					<p>Due to changes between CMScout 1.x and 2.x certain parts of your website will need to be recreated or modified before you can use your website again. These parts include:</p>
					<ul>
						<li>Forum Access Rights</li>
						<li>Authorizations</li>
						<li>RSS Feeds</li>
						<li>Sub Site and Group Site menu items may need rechecking</li>
						<li>Frontpage items</li>
						<li>Menu items will need to be published</li>
					</ul>
<div align="center"><input type="button" value="< Previous" onclick="window.location='migrate.php';" class="button" />&nbsp;
<input type="button" value="Next >" onclick="show_hide('config')" class="button" /></div>
                            </div>
                            <div id="config"  style="display: none;">
<fieldset class="formlist">
<legend>Configuration</legend>
Please fill in the required details.<br /><br />
<div class="field">
<div class="fieldItem"><label for="adminuser" class="label">Administrator<span class="hintanchor" title="Required :: Please select which user is your existing main administrator. This user will be added to the Administrators group automatically."><img src="help.png" alt="[?]"/></span></label> 
<div class="inputboxwrapper">
<select name="adminuser" id="adminuser" class="inputbox">
{section name=users loop=$numusers}
<option value="{$userlist[users].id}">{$userlist[users].uname}</option>
{/section}
</select></div></div><br />
<div class="fieldItem"><label for="admingroup" class="label">Administration Group<span class="hintanchor" title="Required :: Please select which group is your existing main administration group."><img src="help.png" alt="[?]"/></span></label> 
<div class="inputboxwrapper">
<select name="admingroup" id="admingroup" class="inputbox">
{section name=users loop=$numgroups}
<option value="{$grouplist[users].id}">{$grouplist[users].teamname}</option>
{/section}
</select></div></div><br />
<div class="fieldItem"><label for="defaultgroup" class="label">Default Group<span class="hintanchor" title="Required :: Please select which group all users should automatically be added too."><img src="help.png" alt="[?]"/></span></label> 
<div class="inputboxwrapper">
<select name="defaultgroup" id="defaultgroup" class="inputbox">
{section name=users loop=$numgroups}
<option value="{$grouplist[users].id}">{$grouplist[users].teamname}</option>
{/section}
</select></div></div><br />

</div>
</fieldset>
<div align="center"><input type="button" value="< Previous" onclick="show_hide('database');" class="button" />&nbsp;
<input type="button" value="Next >" onclick="show_hide('install')" class="button" /></div>
                            </div>
                          
                            <div id="install" style="display: none;">
            
            <p>
		CMScout is now ready to install your website. Please make sure that all the required information has been filled in and is correct before you click on the install button below.
            </p>
		<p>
			If you have any queries about CMScout, or if you encounter any serious problems with installating please contact us on our forum at <a href="http://www.cmscout.za.net">www.cmscout.za.net</a>
		</p>
            <div align="center"><input type="button" value="< Previous" onclick="show_hide('config')" class="button" />&nbsp;<input type="submit" id="submit" name="submit" value="Install" class="button" /></div>
                            </div>
                        </div>
                    </div>
				</td>
			</tr>
		</table>
		<input type="hidden" name="database" value="{$database}" id="database" />
		{/if}
		<input type="hidden" name="configstage" value="{$configstage+1}" id="configstage" />
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
		<input name="adminuser" type="hidden" value="{$adminuser}" />
		<input name="admingroup" type="hidden" value="{$admingroup}" />
		<input name="defaultgroup" type="hidden" value="{$defaultgroup}" />
	</div>
</div>{elseif $stage == 2}<div style="padding-bottom: 3px;">
	<div class="inside-box" align="left">
		<div style="color:#ff0000">Error ::.::.:..</div>
	</div>

	<div class="inside-box" style="border-top: none;" align="left">
 <h2>CMScout {$version} could not install</h2>
	    <p>CMScout {$version} could not install as there were 1 or more errors while configuring the database. Please restore your database using the backup you made before you started installing and try again. If the problem persists please contact us on the CMScout forums at <a href="www.cmscout.za.net">www.cmscout.za.net</a> and include the errors shown below</p>

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
        <p>Your CMScout installation has been successfully converted to CMScout {$version}. Please ensure that the config.php file exists and has the correct database information stored in it.<br />
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

