<?php
/**************************************************************************
    FILENAME        :   logon.php
    PURPOSE OF FILE :   Checks users logon credientials and sends user to correct place
    LAST UPDATED    :   04 May 2
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
// Start Code
$limitedStartup = true;
require_once ("includes/error_handling.php");
require_once ("common.php");
if($_POST['Login'] == "Login")
{
    $query = $_SERVER['QUERY_STRING'];
    $redirectpage = str_replace("redirect=", "", $query);
    
    $uname = strip_tags(trim($_POST['username']));
	$pass = md5(strip_tags(trim($_POST['password'])));
	$detail = $Auth->authenticate($uname, $pass);

   if ($detail['id'] == -1 && $detail['status'] == 1)
  {
        if ($redirectpage != "" && $redirectpage != "page=logon" && $_GET['redirect'] != "administration_panel")
        {
            show_message("Incorrect username or password", 'index.php?page=' . $redirectpage, false, $detail['uid']);
        }
        else
        {
            show_message("Incorrect username or password", false, false, $detail['uid']);
        } 
    }
    elseif ($detail['id'] == -1 && $detail['status'] == 0)
    {
        $uname = safesql($uname, "text");
        $temp = $data->select_fetch_one_row("users", "WHERE uname = $uname", "id");
        if ($redirectpage != "" && $redirectpage != "page=logon" && $_GET['redirect'] != "administration_panel")
        {
            show_message("<a href=\"activate.php?id={$temp['id']}\">Your account has not been activated yet. Click here to resend the activation email.</a>", 'index.php?page=' . $redirectpage, false, $detail['uid'], 1);
        }
        else
        {
            show_message("<a href=\"activate.php?id={$temp['id']}\">Your account has not been activated yet. Click here to resend the activation email.</a>", false, false, $detail['uid'], 1);
        }  
    }
    elseif ($detail['id'] == -1 && $detail['status'] == -1)
    {
        $uname = safesql($uname, "text");
        $temp = $data->select_fetch_one_row("users", "WHERE uname = $uname", "id");
        if ($redirectpage != "" && $redirectpage != "page=logon" && $_GET['redirect'] != "administration_panel")
        {
            show_message("Your account has been blocked. Please contact the administrator to unblock it.", 'index.php?page=' . $redirectpage, false, $detail['uid']);
        }
        else
        {
            show_message("Your account has been blocked. Please contact the administrator to unblock it.", false, false, $detail['uid']);
        }  
    }
	else 
	{

        if ($redirectpage != "" && $_GET['redirect'] != "register" && $_GET['redirect'] != "forgot" && $redirectpage != "page=logon" && $_GET['redirect'] != "administration_panel")
        {
            header("Location: index.php?page=$redirectpage");
        }
        elseif ($_GET['redirect'] == "administration_panel")
        {
		header("Location: admin.php");
        }
	else
	{
            header("Location: index.php");
	}
    }
    exit;
}
else
{
    $action = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING'])) {
      $action .= "?" . htmlentities($_SERVER['QUERY_STRING']);
    }  
    $location = "Login";
    if ($_POST['relogon'] == "Login")
    {
    	$query = $_SERVER['QUERY_STRING'];
        $redirectpage = str_replace("page=logon&redirect=", "", $query);
        
        $uname = strip_tags($_POST['username2']);
	    $pass = md5(strip_tags($_POST['password2']));
        $detail = $Auth->authenticate($uname, $pass);
       
        if ($detail==0 || $detail['uname'] == "Guest")
        {
            if ($redirectpage != "" && $redirectpage != "page=logon")
            {
                show_message("Incorrect username or password", 'index.php?page=' . $redirectpage);
            }
            else
            {
                show_message("Incorrect username or password");
            } 
        }
        elseif ($detail==1)
        {
            if ($redirectpage != "" && $redirectpage != "page=logon")
            {
            show_message("<a href=\"activate.php?uname=$uname\">Your account has not been activated yet. Click here to activate it.</a>", 'index.php?page=' . $redirectpage, false, $detail['uid'], 1);
        }
        else
        {
            show_message("<a href=\"activate.php?uname=$uname\">Your account has not been activated yet. Click here to activate it.</a>", false, false, $detail['uid'], 1);
            }  
        }
        else 
        {
            if ($redirectpage != "page=logon")
            {
                header("Location: index.php?page=$redirectpage");
            }
            else
            {
                header("Location: index.php");
            }
        }
        exit;
    }
    
    
    $tpl->assign("action", $action);
    $dbpage = true;
    $pagename="logon";
}
?>