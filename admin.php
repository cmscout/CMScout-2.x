<?php
/**************************************************************************
    FILENAME        :   admin.php
    PURPOSE OF FILE :   Main admin file. Calls admin modules and sets up menu
    LAST UPDATED    :   02 October 2006
    COPYRIGHT       :   © 2006 CMScout Group
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
$bit = "./";
$upgrader = false;
require_once ("{$bit}includes/error_handling.php");
set_error_handler('ErrorHandler');
error_reporting(E_ERROR|E_PARSE);
$upgrader = false;
$accessdenied = false;
$limitedStartup = false;
require_once ("common.php");
$users = new auth($dbname, $dbhost, $dbusername, $dbpassword, $dbprefix);
location("Admin", $check["uid"]);
$error = "";
$show = "yes";
$script = ''; 
$onDomReady = ''; 
 
function pageauth($moduleid, $typeauth)
{
    global $userauth;
    switch($typeauth)
    {
        case "access":  return $userauth['access'][$moduleid];
        case "add":  return $userauth['add'][$moduleid];
        case "edit":  return $userauth['edit'][$moduleid];
        case "delete":  return $userauth['delete'][$moduleid];
        case "publish":  return $userauth['publish'][$moduleid];
        case "limit":  return $userauth['limit'][$moduleid];
    }
}
/********************************************Begin Initilization of page*****************************************/

$menufile = 'menu.tpl';
$message = '';
$oldtpldir = $tpl->template_dir;
$tpl->template_dir = 'templates/';
$tpl->compile_dir = 'templates_c/';
$tpl->config_dir = 'configs/';
$tpl->cache_dir = 'cache/';

$tpl->assign('tempdir', $tpl->template_dir);
$tpl->assign('title','Administration Panel');
$tpl->assign('imagedir', $tpl->template_dir.'images');
if (isset($check["uname"])) 
{
    $tpl->assign('name',$check["uname"]);
    $tpl->assign('logged',true);
} 
else 
{
    $tpl->assign('logged',false);
}

$uname = $check["uname"];

$pageid = $_GET['page'];
/********************************************End Initilization of page*****************************************/

function cmp($a, $b) 
{
    return strcmp($a["name"], $b["name"]);
}

/********************************************Start Menu Building and Module Scanning*****************************************/

$dirname = @opendir("admin/");

$getmodules = 1;
$modulenumbers = 0;
$userauth = array();
while( $filename = @readdir($dirname) )
{
    if( preg_match("/^admin_.*?" . $phpex . "$/", $filename) )
    {
        include("admin/" . $filename);
        if (isset($moduledetails[$modulenumbers]['name']))
        {
            $userauth['access'][$moduledetails[$modulenumbers]['id']] = 0;
            $userauth['add'][$moduledetails[$modulenumbers]['id']] = 0;
            $userauth['edit'][$moduledetails[$modulenumbers]['id']] = 0;
            $userauth['delete'][$moduledetails[$modulenumbers]['id']] = 0;
            $userauth['publish'][$moduledetails[$modulenumbers]['id']] = 0;
            $userauth['limit'][$moduledetails[$modulenumbers]['id']] = 0;
            $moduledetails[$modulenumbers]['description'] = $moduledetails[$modulenumbers]['details'] . " :: ";

            $accesstimes = array( "access" => "Access Module", "add" => "Add Items", "edit" => "Edit Items", "delete" => "Delete Items", "publish" => "Publish Items", "limit" => "Limitations" );
            while( list($name, $descr)	= each($accesstimes) )
            {
                if ($moduledetails[$modulenumbers][$name] != "notused" && $moduledetails[$modulenumbers][$name] != "") 
                {
                    $moduledetails[$modulenumbers]['description'] .= "&lt;b&gt;$descr:&lt;/b&gt; " . $moduledetails[$modulenumbers][$name] . "&lt;br /&gt;"; 
                }
                else
                {
                    $moduledetails[$modulenumbers]['description'] .= "&lt;b&gt;$descr:&lt;/b&gt; Does Nothing&lt;br /&gt;";
                }
            }

            $modulenumbers++;
        }
    }
}

@closedir($dirname);

unset($getmodules);

ksort($module);
usort($moduledetails, "cmp");
$action = "";

$sql = $data->select_query("usergroups", "WHERE userid = {$check['id']}");

$adminpanel = 0;
while ($temp = $data->fetch_array($sql))
{
    $sql2 = $data->select_query("groups", "WHERE id = {$temp['groupid']}");
    $groupinfo = $data->fetch_array($sql2);
    
    if ($temp['utype'] == 0)
    {
        $tempauth = unserialize($groupinfo['normaladmin']);
    }
    elseif ($temp['utype'] == 1)
    {
        $tempauth = unserialize($groupinfo['agladmin']);
    }
    elseif ($temp['utype'] == 2)
    {
        $tempauth = unserialize($groupinfo['gladmin']);
    }
    
    for($i=0;$i<$modulenumbers;$i++)
    {
        $userauth['access'][$moduledetails[$i]['id']] = $userauth['access'][$moduledetails[$i]['id']] || $tempauth['access'][$moduledetails[$i]['id']];
        $userauth['add'][$moduledetails[$i]['id']] = $userauth['add'][$moduledetails[$i]['id']] || $tempauth['add'][$moduledetails[$i]['id']];
        $userauth['edit'][$moduledetails[$i]['id']] = $userauth['edit'][$moduledetails[$i]['id']] || $tempauth['edit'][$moduledetails[$i]['id']];
        $userauth['delete'][$moduledetails[$i]['id']] = $userauth['delete'][$moduledetails[$i]['id']] || $tempauth['delete'][$moduledetails[$i]['id']];
        $userauth['publish'][$moduledetails[$i]['id']] = $userauth['publish'][$moduledetails[$i]['id']] || $tempauth['publish'][$moduledetails[$i]['id']];
        $userauth['limit'][$moduledetails[$i]['id']] = $userauth['limit'][$moduledetails[$i]['id']] || $tempauth['limit'][$moduledetails[$i]['id']];
    }
    
    $adminpanel = $adminpanel || $tempauth['adminpanel'];
}

if ($adminpanel == 0)
{
$tpl->assign('config', $config);
  $tpl->assign("loggedin", $check['id'] != -1 ? 1 : 0);
   $tpl->display("admin/access_denied.tpl");
   exit;
}
else
{
    $menuitems = array();
    $catnums = 0;
    while( list($cats, $action_array) = each($module) )
    {
        $temp = $action_array;
        ksort($temp);
        $catitems = false;
        while( list($names, $pages)	= each($temp) )
        {
            
            if ($pages == $pageid)
            {
                $pagetitle = $names;
            }
            
            if ($userauth['access'][$pages]==1)
            {
                $catitems = true;
            }
    
        }
        
        if ($catitems)
        {
            $menuitems[$catnums]['catname'] = $cats;
            ksort($action_array);
            $itemnums = 0;
            while( list($names, $pages)	= each($action_array) )
            {
                if ($userauth['access'][$pages]==1)
                {
                    $menuitems[$catnums]['items'][$itemnums]['page'] = $pages;
                    $menuitems[$catnums]['items'][$itemnums]['name'] = $names;
                    $itemnums++;
                }
            }
            $menuitems[$catnums]['numitems'] = $itemnums;
            $catnums++;
        }
    }
    
      $page = $_GET['page'];
      

   $menuOpen = 0;
    $mainmenu = '<p class="accToggler">Main Menu</p>
                    <div class="accContent"><ul class="adminNavigation">
                        <li><a href="admin.php">Admin Home</a></li>
                        <li><a href="index.php">Site Home</a></li>
                        <li><a href="http://manual.cmscout.za.net/index.php?title=The_backend">Help</a></li>
                        </ul></div>';

     for ($i=0;$i<$catnums;$i++)
    {
        $mainmenu .= "<p class=\"accToggler\">{$menuitems[$i]['catname']}</p>
        <div class=\"accContent\"><ul class=\"adminNavigation\">";
        for ($j=0;$j<$menuitems[$i]['numitems'];$j++)
        {
		if ($page == $menuitems[$i]['items'][$j]['page'])
		{
			$menuOpen = $i+1;
		}
	   $mainmenu .= "<li><a href=\"admin.php?page={$menuitems[$i]['items'][$j]['page']}\">{$menuitems[$i]['items'][$j]['name']}</a></li>";
        }
        $mainmenu .= "</ul></div>";
    }                 
    
    $tpl->assign("mainmenu", $mainmenu);   
    $tpl->assign("menuOpen", $menuOpen);   
    
    /********************************************End Menu Building and Module Scanning*****************************************/
    
    
    /********************************************Start Content Generation*****************************************/
    
  

    $pagename = "admin.php";
    $pagename = isset($_GET['subpage']) ? $pagename . "?page=$page&amp;subpage=" . $_GET['subpage'] : $pagename . "?page=$page";
    if (file_exists("admin/admin_" . $page . $phpex))
    {
        if ((pageauth($page, "access") == 0) && $page != "contentManager")
        {
            echo "<script>alert('You are not allowed to access this admin module');window.location='admin.php'</script>";
            exit;
        }   
        include("admin/admin_" . $page . $phpex);
    }
    else 
    {
        include("admin/admin_main.php");
    }
    
    $ex = ( isset($_GET['ex']) ) ? $_GET['ex'] : "";
    if ($accessdenied === false)
    {
        $tpl->assign('file', $filetouse);
    }
    else
    {
        include ("admin/admin_main.php");
        $tpl->assign('file', $filetouse);
    }
    
    $tpl->assign("mainpage", $page);
    $tpl->assign("pagename", $pagename);
    $tpl->assign("ex", $ex);
    $tpl->assign("error", $error);
    $tpl->assign('menufile', $menufile);
    $tpl->assign('message', $message);
    $tpl->assign('show', $show);
    $tpl->assign('userlevel', $check['level']);
    $tpl->assign('notsecond', $notsecond);
    $tpl->assign("timeoffset", getuseroffset($check['uname']));


//Check for user message
$uid = safesql($check['uid'], "text");
$messages = $data->select_fetch_one_row("messages", "WHERE uid=$uid AND type = 3");
$data->delete_query("messages", "uid=$uid AND type = 3");
if ($messages)
{
    $tpl->assign("infomessage", $messages['message'] . ($messages['type'] == 3 ? " (Click on the message to hide)" : ""));
    if ($messages['post'] != NULL)
    {
        $post = unserialize($messages['post']);
        $tpl->assign("repost", $post);
    }
    if ($messages['type'] == 1)
    {
        $tpl->assign("nohide", true);
    }
}
    /********************************************End Content Generation*****************************************/
    //Compile page
    if ($config['softdebug'] == 1)
    {
        $endtime = microtime();
        $totaltime = $endtime - $starttime;
        $counter = $data->get_counter();
        $debug .= "<br />This page took $totaltime seconds to render<br />CMScout performed $counter database queries";
    }
    $tpl->assign('debug', $debug);
    $tpl->assign('config', $config);
    
    $tpl->assign("addallowed", pageauth($page, "add"));
    $tpl->assign("editallowed", pageauth($page, "edit"));
    $tpl->assign("deleteallowed", pageauth($page, "delete"));
    $tpl->assign("publishallowed", pageauth($page, "publish"));
    $tpl->assign("limitgroup", pageauth($page, "limit"));
    
    $tpl->assign("script", $script);
    $tpl->assign("onDomReady", $onDomReady);
    $tpl->assign("activetab", $_GET['activetab']);
    
    $templateinfo['invalid'] = "#ad0000";
    $templateinfo['valid'] = "#06ad00";
    $templateinfo['default'] = "#deffff";
    $tpl->assign("templateinfo", $templateinfo);
    $tpl->display('admin/admin.tpl');
    $error = false;
    $loggedout = false;
}
?>