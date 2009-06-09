<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>..:.:.:: CMScout {$version} Installer ::.:.:..</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="install.css" rel="stylesheet" type="text/css" />

</head>
<body>

<center style="margin:20px;">
<div class="outside-box" style="height:135px;"><span style="text-align:left;"><img align="left" src="logo.gif" alt="CMScout" /></span></div>
	<div class="outside-box" style="border-top: none;">
        ..:.:.:: CMScout {$version} Installer ::.:.:..
	</div>
	<div class="outside-box" style="border-top: none;">
		<div style="padding-bottom: 3px;">
			<div class="inside-box" align="left">
				Welcome ::.::.:..
			</div>

			<div class="inside-box" style="border-top: none;" align="left">
				<h1>CMScout {$version} Installer</h1>
				<p>Thank you for choosing to install CMScout {$version}. We hope that it serves your needs and that you find it easy and intuitive to use.</p>
				<p>CMScout {$version} offers you two ways to install it. A clean install which is the way to go if this is the first time you are installing CMScout, and a migration install which is the way to go if you already have a CMScout 1.x based installation and you wish to upgrade to CMScout {$version}.</p>
				<p>If you are migrating from CMScout 1.x, please ensure that you have a version of CMScout 1.x newer then version 1.23.</p>
				<p align="center">{if $clean}<input type="button" value="Clean Installation" onclick="window.location='install.php';" class="button" />&nbsp;&nbsp;{/if}{if $migrate}<input type="button" value="Migrate from CMScout 1.x" onclick="window.location='migrate.php';"  class="button" />&nbsp;&nbsp;{/if}{if $upgrade}<input type="button" value="Upgrade existing CMScout 2.x installation to {$version}" onclick="window.location='upgrade.php';"  class="button" />&nbsp;&nbsp;{/if}</p>
			</div>
		</div>
	</div>
	<div class="outside-box" style="border-top: none;">
		 {eval var=$copyright}
	</div>
</center>
    </body>
    {if $repost}
<script type="text/javascript">
{section name=posts loop=$repost}
    document.getElementById('{$repost[posts].id}').value = '{$repost[posts].value}';
{/section}
</script>
{/if}
</html>