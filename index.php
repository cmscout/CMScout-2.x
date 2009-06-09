<?php
/**************************************************************************
    FILENAME        :   index.php
    PURPOSE OF FILE :   Main file, fetches pages
    LAST UPDATED    :   25 May 2006
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
$infoerror = false;
$isproblem = false;
$infomessage = false;
require_once ("includes/error_handling.php");
set_error_handler('ErrorHandler');
error_reporting(E_ERROR|E_PARSE);
$upgrader = false;
$limited_startup = false;
require_once("common.php");
$error = false;
$logout = false;
$currentPage = "index.php?" . $_SERVER['QUERY_STRING'];

if (isset($_GET['ex'])) $extra = $_GET['ex']; else $extra = "";
if (isset($_GET['theme'])) $template = $_GET['theme']; else $template = "";
if ($template == "")
{
    $templateinfo = ( isset($check['theme_id']) ) ? change_theme_dir($check['theme_id']) : change_theme_dir();
}
else
{
    $templateinfo = change_theme_dir($template);
}
$edit = false;
$editlink = '';
$add = false;
$addlink = '';
$script = '';

$scriptList['gallery'] = 0;
$scriptList['datepicker'] = 0;
$scriptList['mooRainbow'] = 0;
$scriptList['mootabs'] = 0;
$scriptList['slimbox'] = 0;
$scriptList['tinyAdv'] = 0;
$scriptList['tinySimp'] = 0;

$tpl->assign("templateinfo", $templateinfo);
$islogged = false;
if (isset($_GET['action'])) $action = $_GET['action'];
else $action = "";

if ($action == 'logout') 
{
    $islogged = false;
    $check = $Auth->logout();
    $tempcss = change_theme_dir();
    $loggedout = true;
    $panel = false;
    $tpl->assign('adminpanel', $panel);
    show_message("You have been logged out.");
    exit;
}
	/********************************************Begin Initilization of page****************************************/
    require_once("menu.php");
	$tpl->assign('logout',$logout);
	$tpl->assign('islogout', '0');

    if (isset($check["uname"]) && $check['id'] != -1) 
    {
        $tpl->assign('name',$check["uname"]);
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

            $adminpanel = $adminpanel || $tempauth['adminpanel'];
        }

        $panel = false;
        if ($adminpanel == 1) 
        {
            $panel = true;
        }
	} 
    else 
    {
	    $islogged = false;
	    $panel = false;
	}
    
	$userdisp = '';
	if (isset($loggedout)) 
    {
	 $tpl->assign('islogout', '1');
	 $islogged = false;
	}
   
if (!$config['disablesite']) 
{   
    $userdisp = "Welcome <strong>Guest</strong><br />";
    if ($config['register'] == 1)
    {
        $userdisp .= "<a href=\"index.php?page=register\">Register</a> | ";
    }
    $userdisp .= "<a href=\"index.php?page=logon\" style=\"margin:0px;\">Login</a>";
        
    $uname = $check["uname"];
    if ($check["id"] != -1) 
    {
        $islogged = true;
        $userdisp = "Welcome <strong>$uname</strong><br /><a href=\"index.php?action=logout\">Logout</a>";
        
        $lastlogged = $check['prevlogin'];

        
        $sql = $data->select_query("pms", "WHERE type=1 AND newpm=1 AND touser='{$check["id"]}'");
        if ($data->num_rows($sql) && $_GET['page'] != "pmmain")
        {
            $tpl->assign("newpm", 1);
        }
        else
        {
            $tpl->assign("newpm", 0);
        }
        
        $sql = $data->select_query("pms", "WHERE type=1 AND readpm=0 AND touser='{$check["id"]}'");
        if ($data->num_rows($sql) && $_GET['page'] != "pmmain")
        {
            $tpl->assign("nummessagepm", $data->num_rows($sql));
        }
        else
        {
            $tpl->assign("nummessagepm", 0);
        }
    }
    $inarticle = false;
    
    //Advert Code
    $tpl->assign('adcode', $config['adcode']);

    if ($check['id'] != -1)
    {
        $tpl->assign("rssid", md5($check['uname']));
    }
	/********************************************End Initilization of page*****************************************/
    if ($_GET['page'] == "register" && $check['id'] != -1) $_GET['page'] = '';
	require ("getcontent.php");
} 
else 
{
	$message = "Sorry, the site has been disabled. Only the site administrator can reenable it.<br /><br />The reason for the site being disabled is:<br /> ";
	$message .= $config['disablereason'];
    
    $userdisp = 'Welcome Guest<br />Unfortunately the site is currently disabled';
    
    $dataC = true;
    $filetouse = "";
    $filetouse = "Sorry, the site has been disabled. Only the site administrator can reenable it.<br /><br />The reason for the site being disabled is:<br /> ";
    $filetouse .= "<b>".$config['disablereason']."</b>";
    
    $islogged = false;
    if ($check["id"] != -1) 
    {
        $islogged = true;
        $userdisp = "Welcome $uname<br /><a href=\"index.php?action=logout\" class=\"top\">Logout</a>";
        $tpl->assign('loggedin','true');
    }
}


location($location, $check["uid"]);
$tpl->assign('location',$location);
$tpl->assign("pagename", $page);
$tpl->assign('config', $config);
$tpl->assign('debug', $debug);
$tpl->assign('adminpanel', $panel);
$tpl->assign('extra', $extra);
$tpl->assign('content', $filetouse);
$tpl->assign('dataC', $dataC);
$tpl->assign("photopath", $config["photopath"] . "/");
$tpl->assign('userdisp', $userdisp);
$tpl->assign('islogged', $islogged);
$tpl->assign('usersname', $check['uname']);
$tpl->assign('uname', $check['uname']);
$tpl->assign('userid', $check['id']);
$tpl->assign("timeoffset", getuseroffset($check['uname']));
$tpl->assign("serverOffset", getoffset($config['zone']));
$tpl->assign("editable", $edit);
$tpl->assign("editlink", $editlink);
$tpl->assign("addable", $add);
$tpl->assign("addlink", $addlink);
$tpl->assign("script", $script);
$tpl->assign("profileAllowed", get_auth("profile", 0));
$tpl->assign("pmAllowed", get_auth("pmmain", 0));
include("page_footer.php");


//Check for user message
$uid = safesql($check['uid'], "text");
$messages = $data->select_fetch_one_row("messages", "WHERE uid=$uid AND type <> 3");
$data->delete_query("messages", "uid=$uid");
if ($messages)
{
    $tpl->assign("ismessage", true);
    $tpl->assign("infomessage", $messages['message'] . ($messages['type'] == 0 ? " (Click on the message to hide)" : ""));
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

if ($_GET['ae'] == 2)
{
    $tpl->assign("infomessage", "Your account has been activated. You can now login. (Click on the message to hide)");
}
elseif ($_GET['ae'] == 1)
{
    $tpl->assign("infomessage", "The account you are trying to activate does not exist, or your activation code is incorrect. (Click on the message to hide)");
}

include('scripts.php');

$tpl->display('index.tpl');
?>
