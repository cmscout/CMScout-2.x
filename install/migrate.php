<?php
/**************************************************************************
    FILENAME        :   install.php
    PURPOSE OF FILE :   Installs CMScout
    LAST UPDATED    :   20 November 2007
    COPYRIGHT       :   © 2005 CMScout Group
    WWW             :   www.cmscout.za.org
    LICENSE         :   GPL vs2.0
    
    

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
**************************************************************************/
?>
<?php
require_once("../includes/Smarty.class.php");
require_once("../includes/functions.php");

function check_dll($dll)
{
	$suffix = ((defined('PHP_OS')) && (preg_match('#win#i', PHP_OS))) ? 'dll' : 'so';
	return ((@ini_get('enable_dl') || strtolower(@ini_get('enable_dl')) == 'on') && (!@ini_get('safe_mode') || strtolower(@ini_get('safe_mode')) == 'off') && @dl($dll . ".$suffix")) ? true : false;
}

$cms_root = './../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
$tpl = new smarty();
$tpl->template_dir = "../install/";
$tpl->compile_dir = '../templates_c/';
$installed = 0;
$errors = "";
$gotoplace = "";
$stage = isset($_POST['stage']) ? $_POST['stage'] : 0;
$version = "2.07";
$upgradefrom = "1.23";

if($stage == '' || !isset($stage))
{
    $stage = 0;
}
elseif ($stage == 0)
{
    $stage = 1;
}

$configstage = isset($_POST['configstage']) ? $_POST['configstage'] : 0;

if($configstage == '' || !isset($configstage))
{
    $configstage = 0;
}
elseif ($configstage == 0)
{
    $configstage = 1;
}

if(isset($_POST['dldone']) && file_exists($cms_root . 'config.' . $phpEx) && filesize($cms_root . 'config.' . $phpEx) != 0)
{
    $stage = 2;
}
elseif(isset($_POST['dldone']) && (!file_exists($cms_root . 'config.' . $phpEx) || filesize($cms_root . 'config.' . $phpEx) == 0))
{
    $stage = 1;
    echo "<script>alert(\"You need to upload the config.php file to CMScouts directory before you can continue\");</script>";
}

//Check permissions and application versions
$available_dbms = array(
    'mysql'		=> 'mysql');

$php_version = phpversion();
$safemode = false;

if (version_compare($php_version, '4.1.0') < 0)
{
    $php = false;
}
else
{
    // We also give feedback on whether we're running in safe mode
    $php = true;
    if (@ini_get('safe_mode') || strtolower(@ini_get('safe_mode')) == 'on')
    {
        $safemode= true;
    }
}
$tpl->assign("php", $php);
$tpl->assign("safemode", $safemode);
$tpl->assign("php_version", $php_version);

foreach ($available_dbms as $dll)
{
    if (!extension_loaded($dll))
    {
        if (!check_dll($dll))
        {
            $db[$dll] = false;
            continue;
        }
    }
    $db[$dll] = true;
    $passed['db'] = true;
}

// Test for other modules
if (!extension_loaded('gd'))
{
    if (!check_dll('gd'))
    {
        $gd = false;
    }
}
else
{
    $gd = true;
}
$tpl->assign("gd", $gd);
$tpl->assign("mysql3", $db['mysql']);

$directories = array('cache/', 'photos/thumbnails/', 'photos/', 'downloads/', 'templates_c/', 'avatars/', 'images/', 'tiny_mce/plugins/ibrowser/scripts/phpThumb/cache/');
$names = array('cache', 'photothumbs', 'photos', 'downloads', 'templates_c', 'avatars', 'images', 'tiny_mce');

umask(0);

$passed['files'] = true;
foreach ($directories as $key=>$dir)
{
    $temp = explode('/', $dir);
    $itemname = $names[$key];
    $write[$itemname] = $exists[$itemname] = false;
    if (is_dir($cms_root . $dir))
    {
        $exists[$itemname] = true;
        if (is_writeable($cms_root . $dir))
        {
            $write[$itemname] = true;
        }
        else
        {
            $write[$itemname] = (@chmod($cms_root . $dir, 0777)) ? true : false;
        }
    }
    else
    {
        $write[$itemname] = $exists[$itemname] = (@mkdir($cms_root . $dir, 0777)) ? true : false;
    }
    $passed['files'] = ($exists[$itemname] && $write[$itemname] && $passed['files']) ? true : false;
}

// config.php ... let's just warn the user it's not writeable
$dir = 'config.'.$phpEx;
$write['config'] = $exists['config'] = true;


if (file_exists($cms_root . $dir))
{
    if (!is_writeable($cms_root . $dir))
    {
        $write['config'] = false;
    }
}
else
{
    $temp = fopen($cms_root . $dir,"w");
    fclose($temp);
    $write['config'] = (@chmod($cms_root . $dir, 0777)) ? true : false;
    $exists['config'] = file_exists($cms_root . $dir);
}

$dir = 'logfile.txt';
$write['logfile'] = $exists['logfile'] = true;
if (file_exists($cms_root . $dir))
{
    if (!is_writeable($cms_root . $dir))
    {
        $write['logfile'] = false;
    }
}
else
{
    $temp = fopen($cms_root . $dir,"w");
    fclose($temp);
    $write['logfile'] = (@chmod($cms_root . $dir, 0777)) ? true : false;
    $exists['logfile'] = file_exists($cms_root . $dir);
}

$tpl->assign("filesok", $passed['files']);
$tpl->assign("write", $write);
$tpl->assign("exists", $exists);


if (!empty($_SERVER['SERVER_NAME']) || !empty($_ENV['SERVER_NAME']))
{
    $server_name = (!empty($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : $_ENV['SERVER_NAME'];
}
else if (!empty($_SERVER['HTTP_HOST']) || !empty($_ENV['HTTP_HOST']))
{
    $server_name = (!empty($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : $_ENV['HTTP_HOST'];
}
else
{
    $server_name = '';
}

if (!empty($_SERVER['SERVER_PORT']) || !empty($_ENV['SERVER_PORT']))
{
    $server_port = (!empty($_SERVER['SERVER_PORT'])) ? $_SERVER['SERVER_PORT'] : $_ENV['SERVER_PORT'];
}
else
{
    $server_port = '80';
}

$script_path = preg_replace('#install\/install\.' . $phpEx . '#i', '', $_SERVER['PHP_SELF']);

$tpl->assign("cmscoutaddress", $server_name.$script_path);
 

if($stage == 1)
{
    $allok = true;
    
	if ($configstage == 1)
	{
		$database = isset($_POST['database']) ? $_POST['database'] : '';
		
		if(empty($database))
		{
			if ($passed['files'] == false)
			{
			    $errors .= "Not all files and directories are writable. Please check which ones are giving the problem and fix it.";
			    $allok = false; 
			    if ($gotoplace == "")
				$gotoplace = "chmoding";
			}
			if ($php == false)
			{
			    $errors .= "Your php version is incorrect. Please ask your service provider to upgrade your php version to at least 4.1.0";
			    $allok = false; 
			    if ($gotoplace == "")
				$gotoplace = "chmoding";
			}
			if ($db['mysql']==false && $db['mysqli'] == false)
			{
			    $errors .= "You do not seem to have MySQL installed, please ask your service provider to install it for you";
			    $allok = false; 
			    if ($gotoplace == "")
				$gotoplace = "chmoding";
			}

			$database['hostname'] = $_POST['dbhostname'];
			$database['name'] = $_POST['databasename'];
			$database['username']= $_POST['databaseusername'];
			$database['password'] = $_POST['databasepassword'];
			$database['port'] = $_POST['dbport'];
			$database['prefix'] = $_POST['dbprefix'];
			if ($database['hostname'] == "")
			{
			    $allok = false; 
			    $errors .= "You need to supply a database hostname<br />";
			    if ($gotoplace == "")
				$gotoplace = "database";
			}
			if ($database['name'] == "")
			{
			    $allok = false; 
			    $errors .= "You need to supply a database name<br />";
			    if ($gotoplace == "")
				$gotoplace = "database";
			}
			if ($database['username'] == "")
			{
			    $allok = false; 
			    $errors .= "You need to supply a database username<br />";
			    if ($gotoplace == "")
				$gotoplace = "database";
			}
			if ($database['prefix'] == "")
			{
			    $allok = false; 
			    $errors .= "You need to supply a table prefix<br />";
			    if ($gotoplace == "")
				$gotoplace = "database";
			}
			if ($database['port'] == "" || $database['port'] == 0)
			{
			    $dbport = 3306;
			} 

			if ($allok)
			{
				$dbconnection = @mysql_connect("{$database['hostname']}:{$database['port']}", $database['username'], $database['password']);
				if (mysql_error() || !isset($dbconnection) || empty($dbconnection))
				{
				    $errors .= "Something is incorrect with your database settings, please check them and make sure that they are correct<br />";
				    $allok = false; 
				    if ($gotoplace == "")
					$gotoplace = "database";
				}
				else
				{
				    $selectedb = @mysql_select_db($database['name']);
				    if (mysql_error() || !isset($selectedb) || empty($selectedb))
				    {
					$errors .= "The database that you specified does not exist. Please ensure that the database does exist.<br />";
					$allok = false; 
					if ($gotoplace == "")
					    $gotoplace = "database";
				    }
				    else
				    {
						$sql = mysql_query("select value from {$database['prefix']}config where name='version'");
						if ($sql)
						{
							$oldversion = mysql_fetch_array($sql);
							$oldversion = $oldversion['value'];
							if ($oldversion  < $upgradefrom)
							{
								$errors .= "The version of CMScout that you have installed is $oldversion. This migration script can only upgrade CMScout installations newer then $upgradefrom, please update your installation to $upgradefrom or newer, before migrating to $version.<br />";
								$allok = false; 
								if ($gotoplace == "")
								    $gotoplace = "database";
							}
							$oldname = mysql_fetch_array(mysql_query("select value from {$database['prefix']}config where name='troopname'"));
							$oldname = $oldname['value'];
							$usersql = mysql_query("select id, uname from {$database['prefix']}authuser order by uname asc");
							$userlist = array();
							$numusers = mysql_num_rows($usersql);
							while($userlist[] = mysql_fetch_array($usersql));
							$groupsql = mysql_query("select id, teamname from {$database['prefix']}authteam order by teamname asc");
							$grouplist = array();
							$numgroups = mysql_num_rows($groupsql);
							while($grouplist[] = mysql_fetch_array($groupsql));
							$tpl->assign('oldversion', $oldversion);
							$tpl->assign('oldname', $oldname);
							$tpl->assign('numusers', $numusers);
							$tpl->assign('userlist', $userlist);
							$tpl->assign('numgroups', $numgroups);
							$tpl->assign('grouplist', $grouplist);
						}
						else
						{
							$errors .= "No previous CMScout installation detected in database, please check that your database settings are correct.<br />";
							    $allok = false; 
							    if ($gotoplace == "")
								$gotoplace = "database";
						}
				    }
				}
				if ($allok)
				{
					$tpl->assign("database", htmlentities(serialize($database)));
					$stage = 0;
					$configstage = 1;
				}
				else
				{
					$tpl->assign("database", $database);
					$stage = 0;
					$configstage=0;
				}
			}
			else
			{
				$configstage = 0;
				$stage = 0;
				$tpl->assign("database", $database);
			}
		}
	}
	
	if ($configstage == 2)
	{
		$adminuser = isset($_POST['adminuser']) ? $_POST['adminuser'] : '';
		$admingroup = isset($_POST['admingroup']) ? $_POST['admingroup'] : '';
		$defaultgroup = isset($_POST['defaultgroup']) ? $_POST['defaultgroup'] : '';
		$database = isset($_POST['database']) ? $_POST['database'] : '';

		$database = unserialize(stripslashes(html_entity_decode($database)));        
    
		if($allok)
		{
			$direct = false;
			$config_data =
			"<?php
			/********************************************************************
			Auto Generated config file for CMScout
			DO NOT CHANGE THIS FILE!!
			*********************************************************************/
				\$dbhost = \"{$database['hostname']}\";
				\$dbusername = \"{$database['username']}\";
				\$dbpassword = \"{$database['password']}\";
				\$dbport = \"{$database['port']}\";
				\$dbname = \"{$database['name']}\";
				\$dbprefix = \"{$database['prefix']}\";
				\$phpex = \".$phpEx\";
			?>";
			    
			    // Attempt to write out the config directly ...
			    if (is_writeable($cms_root . 'config.' . $phpEx))
			    {
				// Lets jump to the DB setup stage ... if nothing goes wrong below
				$stage = 2;

				if (!($fp = @fopen($cms_root . 'config.'.$phpEx, 'w')))
				{
				    // Something went wrong ... so let's try another method
				    $stage = 1;
				}

				if (!(@fwrite($fp, $config_data)))
				{
				    // Something went wrong ... so let's try another method
				    $stage = 1;
				}
				else
				{
				    $direct = true;
				    $stage = 2;
				}
				@fclose($fp);
			    }

			if($stage == 1)
			{
			    $tpl->assign("database", htmlentities(serialize($database)));
			    $tpl->assign("adminuser", htmlentities($adminuser));
			    $tpl->assign("admingroup", htmlentities($admingroup));
			    $tpl->assign("defaultgroup", htmlentities($defaultgroup));
			}
		}
		else
		{
			$stage = 0;
			$configstage = 1;
			$tpl->assign("database", htmlentities(serialize($database)));
			$tpl->assign("adminuser", $adminuser);
			$tpl->assign("admingroup", $admingroup);
			$tpl->assign("defaultgroup", $defaultgroup);
		}
	}
}

if($stage == 2)
{
    if ($direct == false)
    {
        $database = $_POST['database'];
        $adminuser =  $_POST['adminuser'];
        $admingroup =  $_POST['admingroup'];
        $defaultgroup =  $_POST['defaultgroup'];
    
        if((isset($database) && $database != ""))
        {
            $database = unserialize(stripslashes(html_entity_decode($database)));
        }
    }
    
    $address = $config['address'];
    $name = $config['troopname'];
    $adminuser = safesql($adminuser, "int");    
    $admingroup = safesql($admingroup, "int");    
    
    $database['prefix'] = trim($database['prefix']);
    
    $dbconnection = mysql_connect("{$database['hostname']}:{$database['port']}", $database['username'], $database['password']);
    $selectedb = mysql_select_db($database['name']);

    $timestamp = time();

    $tablefile =  fopen("newtables.sql", "r");
    $tables = fread($tablefile, filesize("newtables.sql"));
    $tags = array("!#prefix#!");
    $replacements   = array($database['prefix']);
    $tables = str_replace($tags, $replacements, $tables);
    $tables = explode(";", $tables);

    foreach($tables as $query)
    {
    	$query = trim($query);
	if ($query != "")
	{
		$tablesql = mysql_query($query) or die("Error with SQL statement $query.<br />Error was: " . "Error at " . __LINE__ . ": " . mysql_error());
	}
    }
    
    //Migrate Group Information
    $groupsql = mysql_query("select * from {$database['prefix']}authteam") or die("Error at " . __LINE__ . ": " . mysql_error());
    while ($temp = mysql_fetch_array($groupsql))
    {
	if ($temp['id'] == $admingroup)
	{
		
		$normaladmin = $agladmin = $gladmin = 'a:7:{s:10:"adminpanel";i:1;s:6:"access";a:34:{s:9:"patrolart";i:1;s:10:"attendance";i:1;s:4:"auth";i:1;s:12:"advancements";i:1;s:8:"comments";i:1;s:7:"content";i:1;s:11:"customtroop";i:1;s:13:"customprofile";i:1;s:9:"downloads";i:1;s:6:"events";i:1;s:6:"forums";i:1;s:9:"frontpage";i:1;s:5:"group";i:1;s:6:"patrol";i:1;s:8:"language";i:1;s:7:"logfile";i:1;s:5:"troop";i:1;s:5:"menus";i:1;s:7:"modules";i:1;s:4:"news";i:1;s:6:"owners";i:1;s:5:"photo";i:1;s:12:"patrolpoints";i:1;s:4:"poll";i:1;s:13:"troop_records";i:1;s:8:"sections";i:1;s:7:"subsite";i:1;s:9:"templatem";i:1;s:5:"trash";i:1;s:9:"emailedit";i:1;s:5:"users";i:1;s:5:"links";i:1;s:6:"config";i:1;s:6:"censor";i:1;}s:3:"add";a:34:{s:9:"patrolart";i:1;s:10:"attendance";i:1;s:4:"auth";i:1;s:12:"advancements";i:1;s:8:"comments";i:0;s:7:"content";i:1;s:11:"customtroop";i:1;s:13:"customprofile";i:1;s:9:"downloads";i:1;s:6:"events";i:1;s:6:"forums";i:1;s:9:"frontpage";i:1;s:5:"group";i:1;s:6:"patrol";i:1;s:8:"language";i:0;s:7:"logfile";i:0;s:5:"troop";i:1;s:5:"menus";i:1;s:7:"modules";i:0;s:4:"news";i:1;s:6:"owners";i:0;s:5:"photo";i:1;s:12:"patrolpoints";i:0;s:4:"poll";i:1;s:13:"troop_records";i:0;s:8:"sections";i:1;s:7:"subsite";i:1;s:9:"templatem";i:1;s:5:"trash";i:0;s:9:"emailedit";i:0;s:5:"users";i:1;s:5:"links";i:1;s:6:"config";i:0;s:6:"censor";i:1;}s:4:"edit";a:34:{s:9:"patrolart";i:1;s:10:"attendance";i:1;s:4:"auth";i:1;s:12:"advancements";i:1;s:8:"comments";i:0;s:7:"content";i:1;s:11:"customtroop";i:1;s:13:"customprofile";i:1;s:9:"downloads";i:1;s:6:"events";i:1;s:6:"forums";i:1;s:9:"frontpage";i:1;s:5:"group";i:1;s:6:"patrol";i:1;s:8:"language";i:1;s:7:"logfile";i:1;s:5:"troop";i:1;s:5:"menus";i:1;s:7:"modules";i:0;s:4:"news";i:1;s:6:"owners";i:1;s:5:"photo";i:1;s:12:"patrolpoints";i:0;s:4:"poll";i:1;s:13:"troop_records";i:1;s:8:"sections";i:1;s:7:"subsite";i:1;s:9:"templatem";i:0;s:5:"trash";i:1;s:9:"emailedit";i:1;s:5:"users";i:1;s:5:"links";i:1;s:6:"config";i:1;s:6:"censor";i:0;}s:6:"delete";a:34:{s:9:"patrolart";i:1;s:10:"attendance";i:1;s:4:"auth";i:1;s:12:"advancements";i:1;s:8:"comments";i:1;s:7:"content";i:1;s:11:"customtroop";i:1;s:13:"customprofile";i:1;s:9:"downloads";i:1;s:6:"events";i:1;s:6:"forums";i:1;s:9:"frontpage";i:1;s:5:"group";i:1;s:6:"patrol";i:1;s:8:"language";i:0;s:7:"logfile";i:0;s:5:"troop";i:1;s:5:"menus";i:1;s:7:"modules";i:0;s:4:"news";i:1;s:6:"owners";i:0;s:5:"photo";i:1;s:12:"patrolpoints";i:0;s:4:"poll";i:1;s:13:"troop_records";i:0;s:8:"sections";i:1;s:7:"subsite";i:1;s:9:"templatem";i:1;s:5:"trash";i:1;s:9:"emailedit";i:0;s:5:"users";i:1;s:5:"links";i:1;s:6:"config";i:0;s:6:"censor";i:1;}s:7:"publish";a:34:{s:9:"patrolart";i:1;s:10:"attendance";i:0;s:4:"auth";i:0;s:12:"advancements";i:0;s:8:"comments";i:1;s:7:"content";i:0;s:11:"customtroop";i:0;s:13:"customprofile";i:0;s:9:"downloads";i:1;s:6:"events";i:1;s:6:"forums";i:0;s:9:"frontpage";i:0;s:5:"group";i:1;s:6:"patrol";i:0;s:8:"language";i:0;s:7:"logfile";i:0;s:5:"troop";i:0;s:5:"menus";i:1;s:7:"modules";i:1;s:4:"news";i:1;s:6:"owners";i:0;s:5:"photo";i:1;s:12:"patrolpoints";i:0;s:4:"poll";i:1;s:13:"troop_records";i:0;s:8:"sections";i:0;s:7:"subsite";i:0;s:9:"templatem";i:0;s:5:"trash";i:0;s:9:"emailedit";i:0;s:5:"users";i:0;s:5:"links";i:0;s:6:"config";i:0;s:6:"censor";i:0;}s:5:"limit";a:34:{s:9:"patrolart";i:0;s:10:"attendance";i:0;s:4:"auth";i:0;s:12:"advancements";i:0;s:8:"comments";i:0;s:7:"content";i:0;s:11:"customtroop";i:0;s:13:"customprofile";i:0;s:9:"downloads";i:0;s:6:"events";i:0;s:6:"forums";i:0;s:9:"frontpage";i:0;s:5:"group";i:0;s:6:"patrol";i:0;s:8:"language";i:0;s:7:"logfile";i:0;s:5:"troop";i:0;s:5:"menus";i:0;s:7:"modules";i:0;s:4:"news";i:0;s:6:"owners";i:0;s:5:"photo";i:0;s:12:"patrolpoints";i:0;s:4:"poll";i:0;s:13:"troop_records";i:0;s:8:"sections";i:0;s:7:"subsite";i:0;s:9:"templatem";i:0;s:5:"trash";i:0;s:9:"emailedit";i:0;s:5:"users";i:0;s:5:"links";i:0;s:6:"config";i:0;s:6:"censor";i:0;}}';
	}
	else
	{
		$normaladmin = $agladmin = $gladmin = '';
	}
	$insert = sprintf("%s, %s, %s, %s, %s, %s, %s, %s, %s",
				safesql($temp['id'], "text"), 
				safesql($temp['teamname'], "text"), 
				safesql($temp['ispatrol'], "text"), 
				safesql($temp['ispublic'], "text"), 
				safesql($temp['getpoints'], "text"), 
				safesql($temp['points'], "text"), 
				safesql($normaladmin, "text"), 
				safesql($agladmin, "text"), 
				safesql($gladmin, "text"));
	mysql_query("insert into {$database['prefix']}groups values ($insert)");
	//Migrate Group Ownership
		mysql_query("update {$database['prefix']}album_track set patrol = {$temp['id']} where patrol='{$temp['teamname']}'") or die("Error at " . __LINE__ . ": " . mysql_error());
		mysql_query("update {$database['prefix']}album_track set patrol = 0 where patrol='All'") or die("Error at " . __LINE__ . ": " . mysql_error());
		mysql_query("update {$database['prefix']}album_track set patrol = -1 where patrol='hidden'") or die("Error at " . __LINE__ . ": " . mysql_error());
		mysql_query("update {$database['prefix']}patrolmenu set patrol = {$temp['id']} where patrol='{$temp['teamname']}'") or die("Error at " . __LINE__ . ": " . mysql_error());
		mysql_query("update {$database['prefix']}patrol_articles set patrol = {$temp['id']} where patrol='{$temp['teamname']}'") or die("Error at " . __LINE__ . ": " . mysql_error());
		mysql_query("update {$database['prefix']}patrol_articles set patrol = 0 where patrol='general'") or die("Error at " . __LINE__ . ": " . mysql_error());
    }
    
    //Migrate User Information
    $usersql = mysql_query("select * from {$database['prefix']}authuser join {$database['prefix']}records using (uname)") or die("Error at " . __LINE__ . ": " . mysql_error());
    while ($temp = mysql_fetch_array($usersql))
    {
	$insert = sprintf("%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s",
				safesql($temp['id'], "text"), 
				safesql($temp['uid'], "text"), 
				safesql($temp['uname'], "text"), 
				safesql($temp['passwd'], "text"), 
				safesql($temp['status'], "text"), 
				safesql($temp['joined'], "text"), 
				safesql($temp['lastlogin'], "text"), 
				safesql($temp['prevlogin'], "text"), 
				safesql($temp['logincount'], "text"), 
				safesql($temp['theme_id'], "text"), 
				safesql($temp['timezone'], "text"), 
				safesql($temp['activationcode'], "text"), 
				safesql($temp['firstname'], "text") == NULL || safesql($temp['firstname'], "text") == "NULL" ? "'none'" : safesql($temp['firstname'], "text"), 
				safesql($temp['lastname'], "text") == NULL || safesql($temp['lastname'], "text") == "NULL" ? "'none'" : safesql($temp['lastname'], "text"), 
				safesql($temp['email'], "text"), 
				safesql($temp['avyfile'], "text"), 
				safesql($temp['sig'], "text"), 
				safesql($temp['newtopic'], "text"), 
				safesql($temp['allowemail'], "text"), 
				safesql($temp['newpm'], "text"), 
				safesql($temp['numposts'], "text"), 
				safesql($temp['publicprofile'], "text"), 
				safesql($temp['numtopics'], "text"), 
				safesql($temp['numalbums'], "text"), 
				safesql($temp['numphotos'], "text"), 
				safesql($temp['numarticles'], "text"), 
				safesql($temp['numnews'], "text"), 
				safesql($temp['numdown'], "text"), 
				safesql($temp['numevent'], "text"), 
				safesql($temp['showemail'], "text"), 
				safesql($temp['showname'], "text"), 
				safesql($temp['showrecord'], "text"), 
				'0', 
				'0', 
				'0', 
				'0', 
				'0', 
				'0', 
				'0', 
				'0');
	mysql_query("insert into {$database['prefix']}users values ($insert)") or die("Error at " . __LINE__ . ": " . mysql_error());
	    //Migrate Item Ownership
		//Photo Albums
		$sql = mysql_query("select ID from {$database['prefix']}album_track where owner='{$temp['uname']}'") or die("Error at " . __LINE__ . ": " . mysql_error());
		while ($item = mysql_fetch_array($sql))
		{
			mysql_query("insert into {$database['prefix']}owners values ('', {$item['ID']}, 'album', {$temp['id']}, 0, 0, 0)") or die("Error at " . __LINE__ . ": " . mysql_error());
		}
		//Articles
		$sql = mysql_query("select ID from {$database['prefix']}patrol_articles where owner='{$temp['uname']}'") or die("Error at " . __LINE__ . ": " . mysql_error());
		while ($item = mysql_fetch_array($sql))
		{
			mysql_query("insert into {$database['prefix']}owners values ('', {$item['ID']}, 'articles', {$temp['id']}, 0, 0, 0)") or die("Error at " . __LINE__ . ": " . mysql_error());
		}
		//Events
		$sql = mysql_query("select id from {$database['prefix']}calendar_items where owner='{$temp['uname']}'") or die("Error at " . __LINE__ . ": " . mysql_error());
		while ($item = mysql_fetch_array($sql))
		{
			mysql_query("insert into {$database['prefix']}owners values ('', {$item['id']}, 'events', {$temp['id']}, 0, 0, 0)") or die("Error at " . __LINE__ . ": " . mysql_error());
		}
		//Downloads
		$sql = mysql_query("select id from {$database['prefix']}downloads where owner='{$temp['uname']}'") or die("Error at " . __LINE__ . ": " . mysql_error());
		while ($item = mysql_fetch_array($sql))
		{
			mysql_query("insert into {$database['prefix']}owners values ('', {$item['id']}, 'downloads', {$temp['id']}, 0, 0, 0)") or die("Error at " . __LINE__ . ": " . mysql_error());
		}
		//News
		$sql = mysql_query("select id from {$database['prefix']}newscontent where owner='{$temp['uname']}'") or die("Error at " . __LINE__ . ": " . mysql_error());
		while ($item = mysql_fetch_array($sql))
		{
			mysql_query("insert into {$database['prefix']}owners values ('', {$item['id']}, 'newsitem', {$temp['id']}, 0, 0, 0)") or die("Error at " . __LINE__ . ": " . mysql_error());
		}
		//Polls
		$sql = mysql_query("select id from {$database['prefix']}polls where owner='{$temp['uname']}'") or die("Error at " . __LINE__ . ": " . mysql_error());
		while ($item = mysql_fetch_array($sql))
		{
			mysql_query("insert into {$database['prefix']}owners values ('', {$item['id']}, 'pollitems', {$temp['id']}, 0, 0, 0)") or die("Error at " . __LINE__ . ": " . mysql_error());
		}
		mysql_query("update {$database['prefix']}pms set fromuser = {$temp['id']} where fromuser='{$temp['uname']}'") or die("Error at " . __LINE__ . ": " . mysql_error());		
		mysql_query("update {$database['prefix']}pms set touser = {$temp['id']} where touser='{$temp['uname']}'") or die("Error at " . __LINE__ . ": " . mysql_error());		
		mysql_query("update {$database['prefix']}forumposts set userposted = {$temp['id']} where userposted='{$temp['uname']}'") or die("Error at " . __LINE__ . ": " . mysql_error());		
		mysql_query("update {$database['prefix']}forumposts set edituser = {$temp['id']} where edituser='{$temp['uname']}'") or die("Error at " . __LINE__ . ": " . mysql_error());		
		mysql_query("update {$database['prefix']}forumstopicwatch set username = {$temp['id']} where username='{$temp['uname']}'") or die("Error at " . __LINE__ . ": " . mysql_error());		
		mysql_query("update {$database['prefix']}forumtopics set userposted = {$temp['id']} where userposted='{$temp['uname']}'") or die("Error at " . __LINE__ . ": " . mysql_error());		
		mysql_query("update {$database['prefix']}forumtopics set lastpost = {$temp['id']} where lastpost='{$temp['uname']}'") or die("Error at " . __LINE__ . ": " . mysql_error());		
		mysql_query("update {$database['prefix']}forums set lastpost = {$temp['id']} where lastpost='{$temp['uname']}'") or die("Error at " . __LINE__ . ": " . mysql_error());		
		mysql_query("update {$database['prefix']}comments set uname = {$temp['id']} where uname='{$temp['uname']}'") or die("Error at " . __LINE__ . ": " . mysql_error());		
		mysql_query("update {$database['prefix']}forumnew set uname = {$temp['id']} where uname='{$temp['uname']}'") or die("Error at " . __LINE__ . ": " . mysql_error());		
		mysql_query("update {$database['prefix']}patrollog set uname = {$temp['id']} where uname='{$temp['uname']}'") or die("Error at " . __LINE__ . ": " . mysql_error());		
		
		$groupsql = mysql_query("select id from {$database['prefix']}groups where teamname = '{$temp['team']}'") or die("Error at " . __LINE__ . ": " . mysql_error());
		$grouptemp = mysql_fetch_array($groupsql);
		$type = $temp['level'] == 0 || $temp['level'] == 1 || $temp['level'] == 2 ? 2 : ($temp == 3 ? 1 : 0);
		mysql_query("INSERT INTO `{$database['prefix']}usergroups` (`groupid`, `userid`, `utype`) VALUES ({$grouptemp['id']}, {$temp['id']}, $type);") or die("Error at " . __LINE__ . ": " . mysql_error());
		$sql = mysql_query("select * from {$database['prefix']}usergroups where groupid = $defaultgroup and userid = {$temp['id']}") or die("Error at " . __LINE__ . ": " . mysql_error());
		if(!mysql_num_rows($sql))
		{
			mysql_query("INSERT INTO `{$database['prefix']}usergroups` (`groupid`, `userid`, `utype`) VALUES ({$defaultgroup}, {$temp['id']}, 0);") or die("Error at " . __LINE__ . ": " . mysql_error());
		}
    }
    $sql = mysql_query("select * from {$database['prefix']}usergroups where groupid = $admingroup and userid = $adminuser") or die("Error at " . __LINE__ . ": " . mysql_error());
    if (mysql_num_rows($sql))
    {
	$admin = mysql_fetch_array($sql);
	
	if ($admin['utype'] != 2)
	{
		mysql_query("update {$database['prefix']}usergroups set utype=2 where groupid = $admingroup and userid = $adminuser") or die("Error at " . __LINE__ . ": " . mysql_error());
	}
    }
    else
    {
		mysql_query("INSERT INTO `{$database['prefix']}usergroups` (`groupid`, `userid`, `utype`) VALUES ({$admingroup}, {$adminuser}, 2);") or die("Error at " . __LINE__ . ": " . mysql_error());
    }
    
    //Menus
    $sql = mysql_query("select * from {$database['prefix']}menu_items");
    while ($temp = mysql_fetch_array($sql))
    {
        switch($temp['type'])
        {
            case 1:
                $temp2 = mysql_fetch_array(mysql_query("select id from {$database['prefix']}static_content where name = '{$temp['item']}'"));
                mysql_query("update {$database['prefix']}menu_items set item={$temp2['id']} where id='{$temp['id']}'");
                break;
            case 2:
            case 3:
                $temp2 = mysql_fetch_array(mysql_query("select id from {$database['prefix']}functions where name = '{$temp['item']}'"));
                mysql_query("update {$database['prefix']}menu_items set item={$temp2['id']} where id='{$temp['id']}'");
                break;
            case 4:
                $temp2 = mysql_fetch_array(mysql_query("select id from {$database['prefix']}subsites where name = '{$temp['item']}'"));
                mysql_query("update {$database['prefix']}menu_items set item={$temp2['id']} where id='{$temp['id']}'");
                break;
            case 5:
                mysql_query("update {$database['prefix']}menu_items set item={$temp['url']} where id='{$temp['id']}'");
                break;
        }
    }
    
    //Apply table changes
	mysql_query("update {$database['prefix']}forumposts set userposted = -1 where userposted='guest'") or die("Error at " . __LINE__ . ": " . mysql_error());		
	mysql_query("update {$database['prefix']}forumposts set edituser = -1 where edituser='guest'") or die("Error at " . __LINE__ . ": " . mysql_error());		
	mysql_query("update {$database['prefix']}forumstopicwatch set username = -1 where username='guest'") or die("Error at " . __LINE__ . ": " . mysql_error());		
	mysql_query("update {$database['prefix']}forumtopics set userposted = -1 where userposted='guest'") or die("Error at " . __LINE__ . ": " . mysql_error());		
	mysql_query("update {$database['prefix']}forumtopics set lastpost = -1 where lastpost='guest'") or die("Error at " . __LINE__ . ": " . mysql_error());		
	mysql_query("update {$database['prefix']}forums set lastpost = -1 where lastpost='guest'") or die("Error at " . __LINE__ . ": " . mysql_error());		


	//Drop Owner fields
      mysql_query("alter table {$database['prefix']}album_track drop owner") or die("Error at " . __LINE__ . ": " . mysql_error());
      mysql_query("alter table {$database['prefix']}patrol_articles drop owner") or die("Error at " . __LINE__ . ": " . mysql_error());
      mysql_query("alter table {$database['prefix']}calendar_items drop owner") or die("Error at " . __LINE__ . ": " . mysql_error());
      mysql_query("alter table {$database['prefix']}downloads drop owner") or die("Error at " . __LINE__ . ": " . mysql_error());
      mysql_query("alter table {$database['prefix']}newscontent drop owner") or die("Error at " . __LINE__ . ": " . mysql_error());
      mysql_query("alter table {$database['prefix']}polls drop owner") or die("Error at " . __LINE__ . ": " . mysql_error());
      
      //album_track
      mysql_query("alter table {$database['prefix']}album_track drop numphotos") or die("Error at " . __LINE__ . ": " . mysql_error()); 
      mysql_query("alter table {$database['prefix']}album_track CHANGE `patrol` `patrol` INT( 11 ) default NULL ") or die("Error at " . __LINE__ . ": " . mysql_error());
      mysql_query("alter table {$database['prefix']}album_track ADD `trash` tinyint(4) NOT NULL AFTER `allowed`") or die("Error at " . __LINE__ . ": " . mysql_error()); 
      
      //auth
      mysql_query("alter table {$database['prefix']}auth drop level") or die("Error at " . __LINE__ . ": " . mysql_error()); 
      mysql_query("alter table {$database['prefix']}auth ADD dynamic longtext NOT NULL AFTER `authname`") or die("Error at " . __LINE__ . ": " . mysql_error()); 
      mysql_query("alter table {$database['prefix']}auth ADD permission longtext NOT NULL AFTER `dynamic`") or die("Error at " . __LINE__ . ": " . mysql_error()); 
      mysql_query("alter table {$database['prefix']}auth ADD `static` longtext NOT NULL AFTER `permission`") or die("Error at " . __LINE__ . ": " . mysql_error()); 
      mysql_query("alter table {$database['prefix']}auth ADD `subsites` longtext NOT NULL AFTER `static`") or die("Error at " . __LINE__ . ": " . mysql_error()); 
      
      //comments
      mysql_query("alter table {$database['prefix']}comments CHANGE `article_id` item_id int(11) NOT NULL default '0' ") or die("Error at " . __LINE__ . ": " . mysql_error());
      mysql_query("alter table {$database['prefix']}comments CHANGE `uname` uid int(11) NOT NULL") or die("Error at " . __LINE__ . ": " . mysql_error());
      mysql_query("alter table {$database['prefix']}comments CHANGE `comment` `comment` mediumtext NOT NULL ") or die("Error at " . __LINE__ . ": " . mysql_error());
      mysql_query("alter table {$database['prefix']}comments ADD type tinyint(4) NOT NULL default '0' AFTER `uid`") or die("Error at " . __LINE__ . ": " . mysql_error()); 
      
      //downloads
      mysql_query("alter table {$database['prefix']}downloads ADD saved_file varchar(32) NOT NULL AFTER `file`") or die("Error at " . __LINE__ . ": " . mysql_error()); 
      mysql_query("alter table {$database['prefix']}downloads ADD thumbnail int(11) AFTER `saved_file`") or die("Error at " . __LINE__ . ": " . mysql_error()); 
      mysql_query("alter table {$database['prefix']}downloads ADD trash tinyint(4) NOT NULL AFTER `allowed`") or die("Error at " . __LINE__ . ": " . mysql_error()); 
      
      //forumauths
      mysql_query("alter table {$database['prefix']}forumauths drop moderate") or die("Error at " . __LINE__ . ": " . mysql_error()); 
      
      //forumnew
      mysql_query("alter table {$database['prefix']}forumnew CHANGE `uname` `uid` INT( 11 ) default NULL ") or die("Error at " . __LINE__ . ": " . mysql_error());

      //forumposts
      mysql_query("alter table {$database['prefix']}forumposts CHANGE `userposted` userposted int(11) NOT NULL ") or die("Error at " . __LINE__ . ": " . mysql_error());
      mysql_query("alter table {$database['prefix']}forumposts CHANGE `edituser` edituser int(11) NOT NULL") or die("Error at " . __LINE__ . ": " . mysql_error());
      mysql_query("alter table {$database['prefix']}forumposts ADD attachment varchar(20) AFTER `edituser`") or die("Error at " . __LINE__ . ": " . mysql_error()); 
      
      //forums
      mysql_query("alter table {$database['prefix']}forums CHANGE `lastpost` lastpost int(11) NOT NULL ") or die("Error at " . __LINE__ . ": " . mysql_error());
      mysql_query("alter table {$database['prefix']}forums ADD parent int(11) NOT NULL default '0' AFTER `pos`") or die("Error at " . __LINE__ . ": " . mysql_error()); 
      mysql_query("alter table {$database['prefix']}forums ADD `limit` int(11) NOT NULL AFTER `parent`") or die("Error at " . __LINE__ . ": " . mysql_error()); 
      
      //forumstopicwatch
      mysql_query("alter table {$database['prefix']}forumstopicwatch CHANGE `username` `uid` INT( 11 ) NOT NULL") or die("Error at " . __LINE__ . ": " . mysql_error());
      
      //forumtopics
      mysql_query("alter table {$database['prefix']}forumtopics CHANGE `userposted` userposted int(11) NOT NULL") or die("Error at " . __LINE__ . ": " . mysql_error());
      mysql_query("alter table {$database['prefix']}forumtopics CHANGE `lastpost` lastpost int(11) NOT NULL") or die("Error at " . __LINE__ . ": " . mysql_error());
      mysql_query("alter table {$database['prefix']}forumtopics ADD locked tinyint(4) NOT NULL AFTER `forum`") or die("Error at " . __LINE__ . ": " . mysql_error()); 
      
      //functions
      mysql_query("alter table {$database['prefix']}functions ADD active tinyint(4) NOT NULL default '1' AFTER `filetouse`") or die("Error at " . __LINE__ . ": " . mysql_error()); 
      mysql_query("alter table {$database['prefix']}functions ADD `mainmodule` varchar(50) NOT NULL AFTER `active`") or die("Error at " . __LINE__ . ": " . mysql_error()); 
      mysql_query("alter table {$database['prefix']}functions ADD `options` longtext NOT NULL") or die("Error at " . __LINE__ . ": " . mysql_error()); 

	//links
      mysql_query("alter table {$database['prefix']}links ADD position tinyint(4) NOT NULL AFTER `cat`") or die("Error at " . __LINE__ . ": " . mysql_error()); 
	
	//links_cats
      mysql_query("alter table {$database['prefix']}links_cats ADD position tinyint(4) NOT NULL AFTER `name`") or die("Error at " . __LINE__ . ": " . mysql_error()); 

	//menu_cats
      mysql_query("alter table {$database['prefix']}menu_cats ADD published tinyint(4) NOT NULL default '0' AFTER `expanded`") or die("Error at " . __LINE__ . ": " . mysql_error()); 
      mysql_query("alter table {$database['prefix']}menu_cats ADD `groups` longtext NOT NULL AFTER `published`") or die("Error at " . __LINE__ . ": " . mysql_error()); 
      
      //menu_items
      mysql_query("alter table {$database['prefix']}menu_items drop url") or die("Error at " . __LINE__ . ": " . mysql_error()); 
      mysql_query("ALTER TABLE `{$database['prefix']}menu_items` CHANGE `item` `item` VARCHAR( 255 ) NULL DEFAULT NULL;") or die("Error at " . __LINE__ . ": " . mysql_error()); 
      mysql_query("ALTER TABLE `{$database['prefix']}menu_items` ADD `option` int(11);") or die("Error at " . __LINE__ . ": " . mysql_error()); 

        //newscontent
      mysql_query("alter table {$database['prefix']}newscontent ADD attachment varchar(20) NOT NULL AFTER `event`") or die("Error at " . __LINE__ . ": " . mysql_error()); 
      mysql_query("alter table {$database['prefix']}newscontent ADD trash tinyint(4) NOT NULL AFTER `allowed`") or die("Error at " . __LINE__ . ": " . mysql_error()); 
      
      //onlineusers
      mysql_query("alter table {$database['prefix']}onlineusers drop ip") or die("Error at " . __LINE__ . ": " . mysql_error()); 
      mysql_query("alter table {$database['prefix']}onlineusers ADD ip varchar(15) NOT NULL AFTER `locchange`") or die("Error at " . __LINE__ . ": " . mysql_error()); 
      
      //patrollog
      mysql_query("alter table {$database['prefix']}patrollog CHANGE `uname` `uid` INT( 11 ) NOT NULL ") or die("Error at " . __LINE__ . ": " . mysql_error());
      
	//patrolmenu
      mysql_query("alter table {$database['prefix']}patrolmenu drop `url`") or die("Error at " . __LINE__ . ": " . mysql_error()); 
      mysql_query("alter table {$database['prefix']}patrolmenu drop `side`") or die("Error at " . __LINE__ . ": " . mysql_error()); 
      mysql_query("alter table {$database['prefix']}patrolmenu CHANGE `item` item varchar(255) default NULL ") or die("Error at " . __LINE__ . ": " . mysql_error());
      mysql_query("alter table {$database['prefix']}patrolmenu CHANGE `patrol` patrol int(11) NOT NULL ") or die("Error at " . __LINE__ . ": " . mysql_error());
      mysql_query("alter table {$database['prefix']}patrolmenu ADD `type` tinyint(4) NOT NULL AFTER `item`") or die("Error at " . __LINE__ . ": " . mysql_error()); 
      
      //patrol_articles
      mysql_query("alter table {$database['prefix']}patrol_articles CHANGE `patrol` patrol int(11) default '0'") or die("Error at " . __LINE__ . ": " . mysql_error());
      mysql_query("alter table {$database['prefix']}patrol_articles CHANGE `pic` pic int(11) default NULL") or die("Error at " . __LINE__ . ": " . mysql_error());
      mysql_query("alter table {$database['prefix']}patrol_articles drop `date_happen`") or die("Error at " . __LINE__ . ": " . mysql_error()); 
      mysql_query("alter table {$database['prefix']}patrol_articles ADD topics longtext AFTER `allowed`") or die("Error at " . __LINE__ . ": " . mysql_error()); 
      mysql_query("alter table {$database['prefix']}patrol_articles ADD `order` tinyint(4) default NULL AFTER `topics`") or die("Error at " . __LINE__ . ": " . mysql_error()); 
      mysql_query("alter table {$database['prefix']}patrol_articles ADD summary mediumtext AFTER `order`") or die("Error at " . __LINE__ . ": " . mysql_error()); 
      mysql_query("alter table {$database['prefix']}patrol_articles ADD related longtext AFTER `summary`") or die("Error at " . __LINE__ . ": " . mysql_error()); 
      mysql_query("alter table {$database['prefix']}patrol_articles ADD trash tinyint(4) NOT NULL AFTER `related`") or die("Error at " . __LINE__ . ": " . mysql_error()); 
      mysql_query("alter table {$database['prefix']}patrol_articles ADD event_id int(11) AFTER `album_id`") or die("Error at " . __LINE__ . ": " . mysql_error()); 
      
      //polls
      mysql_query("alter table {$database['prefix']}polls ADD options longtext NOT NULL AFTER `date_stop`") or die("Error at " . __LINE__ . ": " . mysql_error()); 
      mysql_query("alter table {$database['prefix']}polls ADD results longtext NOT NULL AFTER `options`") or die("Error at " . __LINE__ . ": " . mysql_error()); 
      mysql_query("alter table {$database['prefix']}polls ADD trash tinyint(4) NOT NULL AFTER `allowed`") or die("Error at " . __LINE__ . ": " . mysql_error()); 
      mysql_query("ALTER TABLE `{$database['prefix']}polls` DROP `sidebox`;") or die("Error at " . __LINE__ . ": " . mysql_error()); 
      mysql_query("TRUNCATE TABLE `{$database['prefix']}polls`;") or die("Error at " . __LINE__ . ": " . mysql_error()); 
         
      //static_content
      mysql_query("alter table {$database['prefix']}static_content ADD type tinyint(4) NOT NULL AFTER `friendly`") or die("Error at " . __LINE__ . ": " . mysql_error()); 
      mysql_query("alter table {$database['prefix']}static_content ADD frontpage tinyint(4) NOT NULL AFTER `type`") or die("Error at " . __LINE__ . ": " . mysql_error()); 
      mysql_query("alter table {$database['prefix']}static_content ADD pid int(11) NOT NULL AFTER `frontpage`") or die("Error at " . __LINE__ . ": " . mysql_error()); 
      mysql_query("alter table {$database['prefix']}static_content ADD special tinyint(4) NOT NULL AFTER `pid`") or die("Error at " . __LINE__ . ": " . mysql_error()); 
      mysql_query("alter table {$database['prefix']}static_content ADD trash tinyint(4) NOT NULL AFTER `special`") or die("Error at " . __LINE__ . ": " . mysql_error()); 

	//submenu
      mysql_query("alter table {$database['prefix']}submenu drop `url`") or die("Error at " . __LINE__ . ": " . mysql_error()); 
      mysql_query("alter table {$database['prefix']}submenu drop `side`") or die("Error at " . __LINE__ . ": " . mysql_error()); 
      mysql_query("alter table {$database['prefix']}submenu CHANGE `item` item varchar(255) default NULL ") or die("Error at " . __LINE__ . ": " . mysql_error());
      mysql_query("alter table {$database['prefix']}submenu ADD `type` tinyint(4) NOT NULL AFTER `item`") or die("Error at " . __LINE__ . ": " . mysql_error()); 

      //calendar items
      $sql = mysql_query("select * from {$database['prefix']}calendar_items join {$database['prefix']}calendar_detail using (id)") or die("Error at " . __LINE__ . ": " . mysql_error());
      $calendar = array();
      while($temp = mysql_fetch_array($sql))
      {
		$temp['startdate'] = strtotime($temp['startdate']);
		$temp['enddate'] = strtotime($temp['enddate']);
		
		$calendar[] = $temp;
      }

      mysql_query("drop table {$database['prefix']}calendar_items") or die("Error at " . __LINE__ . ": " . mysql_error());
      mysql_query("CREATE TABLE {$database['prefix']}calendar_items (
  id int(11) NOT NULL auto_increment,
  summary varchar(50) NOT NULL default '',
  startdate int(11) NOT NULL default '0',
  enddate int(11) NOT NULL default '0',
  detail longtext,
  allowed tinyint(4) NOT NULL default '0',
  groups longtext NOT NULL,
  date_post int(11) NOT NULL,
  colour varchar(7) NOT NULL,
  signup tinyint(4) NOT NULL,
  signupusers tinyint(4) NOT NULL,
  patrols longtext NOT NULL,
  trash tinyint(4) NOT NULL,
  PRIMARY KEY  (id),
  FULLTEXT KEY detail (summary,detail)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;") or die("Error at " . __LINE__ . ": " . mysql_error());
	foreach($calendar as $temp)
	{
		$insert = sprintf("%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s",
					safesql($temp['id'], "text"), 
					"'".mysql_real_escape_string($temp['summary'])."'", 
					safesql($temp['startdate'], "text"), 
					safesql($temp['enddate'], "text"), 
					"'".mysql_real_escape_string($temp['detail'])."'", 
					safesql($temp['allowed'], "text"), 
					'\'\'', 
					"'$timestamp'", 
					'\'#dddddd\'',
					'0',
					'\'\'',
					'\'\'',
					'0');
		mysql_query("insert into {$database['prefix']}calendar_items values ($insert)") or die("Error at " . __LINE__ . ": " . mysql_error());
	}
      
      //content items
	$sql = mysql_query("select p.*, g.id as groupid from {$database['prefix']}patrolcontent p, {$database['prefix']}groups g where p.patrol = g.teamname") or die("Error at " . __LINE__ . ": " . mysql_error());
      while($temp = mysql_fetch_array($sql))
      {
		$front = $temp['name'] == "frontpage" ? 1 : 0;
		$insert = sprintf("%s, %s, %s, %s, %s, %s, %s, %s, %s",
					'\'\'', 
					"'".mysql_real_escape_string($temp['name'])."'", 
					"'".mysql_real_escape_string($temp['content'])."'", 
					safesql($temp['name'], "text"), 
					1, 
					$front,
					safesql($temp['groupid'], "text"), 
					safesql($temp['public'], "text"), 
					'0');
		mysql_query("insert into {$database['prefix']}static_content values ($insert)") or die("Error at " . __LINE__ . ": " . mysql_error());
      }
      
      $sql = mysql_query("select id, name from {$database['prefix']}subsites") or die("Error at " . __LINE__ . ": " . mysql_error());
      while($temp = mysql_fetch_array($sql))
      {
		mysql_query("update {$database['prefix']}submenu set `site`={$temp['id']} where `site` = '{$temp['name']}'") or die("Error at " . __LINE__ . ": " . mysql_error());
      }
      
      $sql = mysql_query("select p.*, g.id as groupid from {$database['prefix']}subcontent p, {$database['prefix']}subsites g where p.site = g.name") or die("Error at " . __LINE__ . ": " . mysql_error());
      while($temp = mysql_fetch_array($sql))
      {
		$front = $temp['name'] == "frontpage" ? 1 : 0;
		$insert = sprintf("%s, %s, %s, %s, %s, %s, %s, %s, %s",
					'\'\'', 
					"'".mysql_real_escape_string($temp['name'])."'", 
					"'".mysql_real_escape_string($temp['content'])."'", 
					safesql($temp['name'], "text"), 
					2, 
					$front,
					safesql($temp['groupid'], "text"), 
					0, 
					'0');
		mysql_query("insert into {$database['prefix']}static_content values ($insert)") or die("Error at " . __LINE__ . ": " . mysql_error());
      }
      
    $dropfile =  fopen("droptables.sql", "r");
    $drop = fread($dropfile, filesize("droptables.sql"));
    $tags = array("!#prefix#!");
    $replacements   = array($database['prefix']);
    $drop = str_replace($tags, $replacements, $drop);
    $drop = explode(";", $drop);

    foreach($drop as $query)
    {
    	$query = trim($query);
	if ($query != "")
	{
		$dropsql = mysql_query($query) or die("Error with SQL statement $query.<br />Error was: " . "Error at " . __LINE__ . ": " . mysql_error());
	}
    }
    
    
    $oldname = mysql_fetch_array(mysql_query("select value from {$database['prefix']}config where name='troopname'"));
    $oldname = safesql($oldname['value'], "text", true, false);
    $updatefile =  fopen("update.sql", "r");
    $update = fread($updatefile, filesize("update.sql"));
    $tags = array("!#prefix#!", "!#version#!", "!#nositename#!", "!#defaultgroup#!");
    $replacements   = array($database['prefix'], $version, $oldname, $defaultgroup);
    $update = str_replace($tags, $replacements, $update);
    $update = explode("#@#", $update);

    foreach($update as $query)
    {
    	$query = trim($query);
	if ($query != "")
	{
		$updatesql = mysql_query($query) or die("Error with SQL statement $query.<br />Error was: " . "Error at " . __LINE__ . ": " . mysql_error());
	}
    }  
    
	mysql_query("update {$database['prefix']}config set value='$version' where name = 'version'") or die("Error at " . __LINE__ . ": " . mysql_error());

	$name = mysql_fetch_array(mysql_query("select value from {$database['prefix']}config where name='troopname'"));
	$name = $name['value'];
	$address = mysql_fetch_array(mysql_query("select value from {$database['prefix']}config where name='siteaddress'"));
	$address = $address['value'];
						
    $tpl->assign("cmscoutaddress", $address);

    $name = urlencode($name);
    $address = urlencode($address);
    
    @file("http://www.cmscout.co.za/addsite.php?troopname=$name&address=$address&version=$version");
    
    $stage = 3;
}

$scriptList['gallery'] = 0;
$scriptList['datepicker'] = 0;
$scriptList['mooRainbow'] = 0;
$scriptList['mootabs'] = 0;
$scriptList['slimbox'] = 0;
$scriptList['tinyAdv'] = 0;
$scriptList['tinySimp'] = 0;
include('../scripts.php');

$tpl->assign("stage", $stage);
$tpl->assign("configstage", $configstage);
$tpl->assign("errors", $errors);
$tpl->assign("gotoplace", $gotoplace);
$tpl->assign("installed", $installed);
$tpl->assign("version", $version);
$tpl->assign("copyright", "Powered by CMScout &copy;2005, 2006, 2007 <a href=\"http://www.cmscout.co.za\" title=\"CMScout Group\" target=\"_blank\">CMScout Group</a>");
$tpl->display("migrate.tpl");
?>
