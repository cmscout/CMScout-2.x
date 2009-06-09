<?php
/**************************************************************************
    FILENAME        :   pmmain.php
    PURPOSE OF FILE :   Manages the private messenger
    LAST UPDATED    :   02 January 2006
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
if (!defined('SCOUT_NUKE'))
    die("You have accessed this page illegally, please go use the main menu");

if (isset($_GET['action'])) $action = $_GET['action'];
$pagenum = 1;

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING']))
{
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$editit = false;
$reply = false;
$sendit= false;
$userid = safesql($check['id'], "int");

if ($_POST['delete'] == "Delete Selected Messages")
{
    $username = $check['uname'];
    $page = $_POST['oldpage'];
    switch($page)
    {
        case "Inbox" : $type=1;break;
        case "Drafts" : $type=4;break;
        case "Sentbox" : $type=2;break;
        case "Savebox" : $type=3;break;
    }
    
    switch ($type)
    {
        case 1:
            $sql = $data->select_query("pms", "WHERE touser=$userid  AND type=1");
            while($temp = $data->fetch_array($sql))
            {
                if ($_POST['pm_' . $temp['id']] == 1)
                {
                    $data->delete_query("pms", "id={$temp['id']}");
                }
            }
            show_message("Messages deleted", "index.php?page=pmmain&menuid=$menuid");
            break;
        case 2:
            $sql = $data->select_query("pms", "WHERE fromuser=$userid  AND type=2");
            while($temp = $data->fetch_array($sql))
            {
                if ($_POST['pm_' . $temp['id']] == 1)
                {
                    $data->delete_query("pms", "id={$temp['id']}");
                }
            }
            show_message("Messages deleted", "index.php?page=pmmain&action=sentbox&menuid=$menuid");
            break;
        case 3:
            $sql = $data->select_query("pms", "WHERE touser=$userid  AND type=3");
            while($temp = $data->fetch_array($sql))
            {
                if ($_POST['pm_' . $temp['id']] == 1)
                {
                    $data->delete_query("pms", "id={$temp['id']}");
                }
            }
            show_message("Messages deleted", "index.php?page=pmmain&action=savebox&menuid=$menuid");
            break;
        case 4:
            $sql = $data->select_query("pms", "WHERE fromuser=$userid  AND type=4");
            while($temp = $data->fetch_array($sql))
            {
                if ($_POST['pm_' . $temp['id']] == 1)
                {
                    $data->delete_query("pms", "id={$temp['id']}");
                }
            }
            show_message("Messages deleted", "index.php?page=pmmain&action=drafts&menuid=$menuid");
            break;
    }
    exit;
}
elseif ($_POST['deleteall'] == "Delete All Messages")
{
    $username = $check['uname'];
    $page = $_POST['oldpage'];
    switch($page)
    {
        case "Inbox" : $type=1;break;
        case "Drafts" : $type=4;break;
        case "Sentbox" : $type=2;break;
        case "Savebox" : $type=3;break;
    }
    
    switch ($type)
    {
        case 1:
            $sql = $data->delete_query("pms", "touser=$userid  AND type=1");
            show_message("Messages have been deleted", "index.php?page=pmmain&menuid=$menuid");
        case 2:
            $sql = $data->delete_query("pms", "fromuser=$userid  AND type=2");
            show_message("Messages have been deleted", "index.php?page=pmmain&action=sentbox&menuid=$menuid");
            break;
        case 3:
            $sql = $data->delete_query("pms", "touser=$userid  AND type=3");
            show_message("Messages have been deleted", "index.php?page=pmmain&action=savebox&menuid=$menuid");
            break;
        case 4:
            $sql = $data->delete_query("pms", "fromuser=$userid  AND type=4");
            show_message("Messages have been deleted", "index.php?page=pmmain&action=drafts&menuid=$menuid");
            break;
    }
}

switch($action)
{
    case "sentbox": 
        include("pm/sentbox.php");
        break;
    case "savebox": 
        include("pm/savebox.php");
        break;
    case "drafts": 
        include("pm/drafts.php");
        break;
    case "readpm":
        include("pm/readpm.php");
        break;
    case "typepm":
        include("pm/typepm.php");
        break;
    case "send":
        $sendit= true;
        include("pm/typepm.php");
        break;
    case "edit":
        $editit = true;
        include("pm/typepm.php");
        break;    
    case "reply":
        $reply = true;
        include("pm/typepm.php");
        break;
    case "save":
        $pid = $_GET['id'];
        $sql = $data->select_query("pms", "WHERE type=3 AND touser=$userid  ORDER BY date ASC", "id");
        if ($data->num_rows($sql) >= $config['numpm'])
        {
            $temp = $data->fetch_array($sql);
            $data->delete_query("pms", "id={$temp['id']}");
        }
        $sql = $data->update_query("pms", "type=3", "id=$pid", "", "", false);
        if($sql)
        {
            show_message("Message saved", "index.php?page=pmmain&menuid=$menuid");
        }
        break;
    case "delete":
        $pid = $_GET['id'];
        $sql = $data->delete_query("pms", "id=$pid", "", "", false);
        $oldpage = $_GET['old'];
        if($sql)
        {
            switch ($oldpage)
            {
                case "Inbox":
                    show_message("Message deleted", "index.php?page=pmmain&menuid=$menuid");
                    break;
                case "Sentbox":
                    show_message("Message deleted", "index.php?page=pmmain&action=sentbox&menuid=$menuid");
                    break;
                case "Savebox":
                    show_message("Message deleted", "index.php?page=pmmain&action=savebox&menuid=$menuid");
                    break;
                case "Drafts":
                    show_message("Message deleted", "index.php?page=pmmain&action=drafts&menuid=$menuid");
                    break;
                case "readpm":
                    show_message("Message deleted", "index.php?page=pmmain&menuid=$menuid");
                    break;
                default:
                    show_message("Message deleted", "index.php?page=pmmain&menuid=$menuid");
                    break;
            }
            exit;
        }
        break;
    default: include("pm/inbox.php");
}

$tpl->assign("username", $check['uname']);
$tpl->assign("userauths", $userauths);
$tpl->assign('editFormAction', $editFormAction);  
$dbpage = true;
$pagename = "pm";
?>