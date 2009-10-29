<?php
/**************************************************************************
    FILENAME        :   upgrade.php
    PURPOSE OF FILE :   Upgrades from one version to another (This file: 1.10 to 1.20)
    LAST UPDATED    :   20 November 2007
    COPYRIGHT       :   © 2007 CMScout Group
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
$bit = "./../";
$upgrader = true;
require_once ("../common.php");
$version  = "2.08";
$oldversion = "2.07";

$step = isset($_GET['step']) ? $_GET['step'] : 1;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>CMScout Installation System (Upgrading to V<?php echo $version ?>)</title>
<link href="install.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="content">
<?php 
switch ($step)
{
    case 1:
        if ($config['version'] == $oldversion)
        {
?>
You are currently using <strong>CMScout V<?php echo $config['version'] ?></strong> <br />
This upgrade script will upgrade your CMScout to <strong>V<?php echo $version ?></strong><br />
It is strongly recommended that you backup your database before upgrading.<br />
<input type="button" value="Continue" class="button" onclick="window.location='upgrade.php?step=2'" />
<?php
        }
        elseif ($config['version'] == $version)
        {
?>
You are already using CMScout <strong>V<?php echo $version ?></strong> <br />
CMScout Upgrade script will not continue.
<?php
        }
        else
        {
?>
Sorry, this upgrade script is only to upgrade CMScout <strong>V<?php echo $oldversion ?></strong> to <strong>V<?php echo $version ?></strong>.<br />
You are currently using CMScout <strong>V<?php echo $config['version'] ?></strong>. This may, or may not be newer than <strong>V<?php echo $version ?></strong>.<br />
If it is older than <strong>V<?php echo $oldversion ?></strong>, please upgrade your CMScout version to <strong>V<?php echo $oldversion ?></strong> before upgrading it to <strong>V<?php echo $version ?></strong><br />
This is to ensure that all necessary updates are performed.
<?php
        }
        break;
    case 2:
        if ($config['version'] != $version)
        { 
            $dbconnection = mysql_connect("$dbhost:$dbport", $dbusername, $dbpassword);
            $selectedb = mysql_select_db($dbname);
            if($dbconnection)
            {
            ?>
                Now upgrading Database.<br />
                Please wait.... <br />               
        <?php
                $numsql = count($sql);

		$requiredfile =  fopen("upgrade.sql", "r");
		$required = fread($requiredfile, filesize("upgrade.sql"));
		$tags = array("!#prefix#!", "!#version#!");
		$replacements   = array($dbprefix, $version);
		$required = str_replace($tags, $replacements, $required);
		$required = explode("#@#", $required);
                $isok = true;
                $i = 1;
		foreach($required as $query)
		{
                  $query = trim($query);
                  if ($query != "")
                  {
                          $requiredsql = mysql_query($query);
                          if($requiredsql)
                              {
                                  echo "<span style=\"color:#168700\">SQL statement $i completed</span><br /><br />";
                              }
                              else
                              {
                                  echo "<span style=\"color:#bc0101\">Error with SQL statement $i. The error was: " . mysql_error() . "</span><br /><br />";
                                  $isok = false;
                              }
                  }
                  $i++;
		}
                
                if ($isok)
                {
                    $name = urlencode($config['troopname']);
                    $address = urlencode($config['siteaddress']);
                    
                    @file("http://www.cmscout.co.za/addsite.php?troopname=$name&address=$address&version=$version");

                    echo "Congratulations. The CMScout database has now been updated to V$version. Don't forget to update the files too. Please delete the install directory before continuing to use your site.";
                }
                else
                {
                    echo "There was some sort of error while updating the CMScout. Restore to the backup you made previously and try again. If the error persists contact CMScout support.";
                }
            }
            else
            {
                echo "Error connection to database. Please make sure that your config.php script exists and contains the correct information.";
            }
        }
        else
        {
?>
You are already using CMScout <strong>V<?php echo $version ?></strong> <br />
CMScout Upgrade script will not continue.
<?php
        }
}
?>
</div>
</body>
</html>
