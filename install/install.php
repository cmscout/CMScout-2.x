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
$version = "2.08";

if($stage == '' || !isset($stage))
{
    $stage = 0;
}
elseif ($stage == 0)
{
    $stage = 1;
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
    
    $database = isset($_POST['database']) ? $_POST['database'] : '';
    $admin = isset($_POST['admin']) ? $_POST['admin'] : '';
    $config = isset($_POST['config']) ? $_POST['config'] : '';
    
    if(empty($database) && empty($admin) && empty($config))
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
        
        $admin['name'] = $_POST['adminusername'];
        $admin['password'] = $_POST['adminpassword'];
        $admin['repass'] = $_POST['adminrepass'];
        $admin['first'] = $_POST['adminfirstname'];
        $admin['last'] = $_POST['adminlastname'];
        $admin['email'] = $_POST['adminemail'];
        $config['webemail'] = $_POST['webemail'];
        if ($admin['name'] == "")
        {
            $allok = false; 
            $errors .= "You need to supply a administrator username<br />";
            if ($gotoplace == "")
                $gotoplace = "admin";
        }
        if ($admin['password'] == "")
        {
            $allok = false; 
            $errors .= "You need to supply a administrator password<br />";
            if ($gotoplace == "")
                $gotoplace = "admin";
        }
        elseif ($admin['password'] != $admin['repass'])
        {
            $allok = false; 
            $errors .= "Passwords do not match<br />";
            if ($gotoplace == "")
                $gotoplace = "admin";
        }
        if ($admin['first'] == "")
        {
            $allok = false; 
            $errors .= "You need to supply a administrator first name<br />";
            if ($gotoplace == "")
                $gotoplace = "admin";
        }
        if ($admin['last'] == "")
        {
            $allok = false; 
            $errors .= "You need to supply a administrator last name<br />";
            if ($gotoplace == "")
                $gotoplace = "admin";
        }
        if ($admin['email'] == "")
        {
            $allok = false; 
            $errors .= "You need to supply a administrator email address<br />";
            if ($gotoplace == "")
                $gotoplace = "admin";
        }
    
        if ($config['webemail'] == "")
        {
            $allok = false; 
            $errors .= "You need to supply a website email address<br />";
            if ($gotoplace == "")
                $gotoplace = "admin";
        }

        
        $config['address'] = $_POST['cmscoutaddress'];
        $config['troopname'] = $_POST['troopname'];
        $config['troopdesc'] = $_POST['troopslogon'];
        $config['sample'] = $_POST['sample'];
        $config['timezone'] = $_POST['zone'];
        if ($config['address'] == "" || $config['address'] == "http://")
        {
            $allok = false; 
            $errors .= "You need to add the URL of your CMScout websiter<br />";
            if ($gotoplace == "")
                $gotoplace = "config";
        }
        if ($config['troopname'] == "")
        {
            $allok = false; 
            $errors .= "You need to supply a name for the website<br />";
            if ($gotoplace == "")
                $gotoplace = "config";
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
                }
            }
            
            $licenseagreement = $_POST['licenseagreement'];
            if ($licenseagreement == 0)
            {
                $errors .= "You need to accept the license agreement to install CMScout<br />";
                $allok = false; 
                if ($gotoplace == "")
                    $gotoplace = "license";
            }
    }
    else
    {
        $database = unserialize(stripslashes(html_entity_decode($database)));        
        $downloads = unserialize(stripslashes(html_entity_decode($downloads)));
        $admin = unserialize(stripslashes(html_entity_decode($admin)));
        $config = unserialize(stripslashes(html_entity_decode($config)));
    }
    
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
            if (filesize($cms_root . 'config.' . $phpEx) == 0 && is_writeable($cms_root . 'config.' . $phpEx))
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
            $tpl->assign("databaseStraight", $database);
        	$tpl->assign("database", htmlentities(serialize($database)));
            $tpl->assign("admin", htmlentities(serialize($admin)));
            $tpl->assign("config", htmlentities(serialize($config)));
            $tpl->assign("phpEx", $phpEx);
        }
    }
    else
    {
        $stage = 0;
        $tpl->assign("database", $database);
        $tpl->assign("admin", $admin);
        $tpl->assign("config", $config);
        $tpl->assign("licenseagreement", $licenseagreement);
    }
}

if($stage == 2)
{
    
    if ($direct == false)
    {
        $database = $_POST['database'];
        $admin =  $_POST['admin'];
        $config = $_POST['config'];
    
        if((isset($database) && $database != "") && (isset($admin) && $admin != "") && (isset($config) && $config != ""))
        {
            $database = unserialize(stripslashes(html_entity_decode($database)));
            $downloads = unserialize(stripslashes(html_entity_decode($downloads)));
            $admin = unserialize(stripslashes(html_entity_decode($admin)));
            $config = unserialize(stripslashes(html_entity_decode($config)));
        }
    }
    
    $address = $config['address'];
    $name = $config['troopname'];
    $admin['name'] = safesql($admin['name'], "text");
    $admin['password'] = safesql(md5($admin['password']), "text");
    $admin['first'] = safesql($admin['first'], "text");
    $admin['last'] = safesql($admin['last'], "text");
    $admin['email'] = safesql($admin['email'], "text");
    $config['webemail'] = safesql($config['webemail'], "text");
    $config['address'] = safesql($config['address'], "text");
    $config['notroopname'] = safesql($config['troopname'], "text", true, false);
    $config['troopname'] = safesql($config['troopname'], "text");
    $config['troopdesc'] = safesql($config['troopdesc'], "text");
    $config['timezone'] = safesql($config['timezone'], "int");
    
    
    $database['prefix'] = trim($database['prefix']);
    
    $dbconnection = mysql_connect("{$database['hostname']}:{$database['port']}", $database['username'], $database['password']);
    $selectedb = mysql_select_db($database['name']);

    $timestamp = time();

    $tablefile =  fopen("tables.sql", "r");
    $tables = fread($tablefile, filesize("tables.sql"));
    $tags = array("!#prefix#!");
    $replacements   = array($database['prefix']);
    $tables = str_replace($tags, $replacements, $tables);
    $tables = explode(";", $tables);

    foreach($tables as $query)
    {
    	$query = trim($query);
	if ($query != "")
	{
		$tablesql = mysql_query($query) or die("Error with SQL statement.<br />Error was: " . mysql_error());
	}
    }

    $requiredfile =  fopen("required.sql", "r");
    $required = fread($requiredfile, filesize("required.sql"));
    $tags = array("!#prefix#!", "!#version#!", "!#sitename#!", "!#sitedescription#!", "!#sitemail#!", "!#siteaddress#!", "!#adminuser#!", "!#adminpassword#!", "!#timestamp#!", "!#adminfirstname#!", "!#adminlastname#!", "!#adminemail#!", "!#nositename#!");
    $replacements   = array($database['prefix'], $version, $config['troopname'], $config['troopdesc'], $config['webemail'], $config['address'], $admin['name'], $admin['password'], $timestamp, $admin['first'], $admin['last'], $admin['email'], $config['notroopname']);
    $required = str_replace($tags, $replacements, $required);
    $required = explode("#@#", $required);

    foreach($required as $query)
    {
    	$query = trim($query);
	if ($query != "")
	{
		$requiredsql = mysql_query($query) or die("Error with SQL statement $query.<br />Error was: " . mysql_error());
	}
    }
    
    if ($config['sample'])
    {
	    $samplefile =  fopen("sample.sql", "r");
	    $sample = fread($samplefile, filesize("sample.sql"));
	    $tags = array("!#prefix#!", "!#version#!", "!#sitename#!", "!#sitedescription#!", "!#sitemail#!", "!#siteaddress#!", "!#adminuser#!", "!#adminpassword#!", "!#timestamp#!", "!#adminfirstname#!", "!#adminlastname#!", "!#adminemail#!", "!#nositename#!");
	    $replacements   = array($database['prefix'], $version, $config['troopname'], $config['troopdesc'], $config['webemail'], $config['address'], $admin['name'], $admin['password'], $timestamp, $admin['first'], $admin['last'], $admin['email'], $config['notroopname']);
	    $sample = str_replace($tags, $replacements, $sample);
	    $sample = explode("#@#", $sample);

	    foreach($sample as $query)
	    {
		$query = trim($query);
		if ($query != "")
		{
			$samplesql = mysql_query($query) or die("Error with SQL statement $query.<br />Error was: " . mysql_error());
		}
	    }
    }
 
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
$tpl->assign("errors", $errors);
$tpl->assign("gotoplace", $gotoplace);
$tpl->assign("installed", $installed);
$tpl->assign("version", $version);
$tpl->assign("copyright", "Powered by CMScout &copy;2009 <a href=\"http://www.cmscout.co.za\" title=\"CMScout Group\" target=\"_blank\">CMScout Group</a>");
$tpl->display("install.tpl");
?>
