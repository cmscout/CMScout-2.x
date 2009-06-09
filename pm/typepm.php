<?php
/**************************************************************************
    FILENAME        :   typepm.php
    PURPOSE OF FILE :   Sends Personal Messages to users
    LAST UPDATED    :   14 February 2006
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

function sendpmmail($uid, $pm)
{
    global $config, $check, $data;
    $tempsql = $data->select_query("users", "WHERE id='$uid'", "id, uname, allowemail, newpm, email");
    $temp = $data->fetch_array($tempsql);
    
    if($temp['allowemail'] && $temp['newpm'])
    {
        $email = $data->select_fetch_one_row("emails", "WHERE type='newpm'");
        
        $link = $config['siteaddress'] . "index.php?page=pmmain&action=readpm&id={$pm['id']}";
        
        $cmscoutTags = array("!#uname#!", "!#postuname#!", "!#title#!", "!#type#!", "!#link#!", "!#extract#!", "!#website#!");
        $replacements   = array($temp['uname'], $check['uname'], $pm['subject'], "personal message", $link, truncate($pm['text'], 300), $config['troopname']);

        $emailContent = str_replace($cmscoutTags, $replacements, $email['email']);
        
        sendMail($temp['email'], $temp['uname'], $config['emailPrefix'] . $email['subject'], $emailContent);
    }
}

$postaction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $postaction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$tpl->assign("postaction", $postaction);

if ($editit == true)
{
    $pid = $_GET['id'];
    $sql = $data->select_query("pms", "WHERE id=$pid");
    $pm = $data->fetch_array($sql);
    
    $tousers = explode(',', strip_tags($pm['touser']));
    $touser = array();
    for ($i=0;$i<count($tousers);$i++)
    {
        $bla = trim($tousers[$i]);
        $touser[] = $userIdList[$bla];
    }
    $pm['touser'] = implode(", ", $touser);
    $tpl->assign("editpm", $pm);
    $tpl->assign("editmode", true);
}
elseif ($reply == true)
{
    $pid = $_GET['id'];
    $sqls = $data->select_query("pms", "WHERE id=$pid");
    $pm = $data->fetch_array($sqls);
    $pm['text'] = trim($pm['text']);
    $newpm['text'] = "[quote={$userIdList[$pm['fromuser']]}]{$pm['text']}[/quote]";
    
    $newpm['subject'] = "Re: {$pm['subject']}";

    $newpm['touser'] = $userIdList[$pm['fromuser']];
    
    $tpl->assign("editpm", $newpm);
}
elseif ($sendit == true)
{
    $pid = $_GET['id'];
    $sqls = $data->select_query("pms", "WHERE id=$pid");
    $pm = $data->fetch_array($sqls);
    
    $subject = safesql($pm['subject'], "text");
    
    $userpm = safesql($pm['text'], "text", false);
    $username = safesql($pm['fromuser'], "int");
    
    $tousers = explode(',', strip_tags($pm['touser']));
    $okusers = array();
    $notokusers = array();
    for($i=0;$i<count($tousers);$i++)
    {
        $message = "";
        $to = safesql(trim($tousers[$i]), "int");
        $sql = $data->select_query("users", "WHERE id = $to");
        $userinfo = $data->fetch_array($sql);
        if ($data->num_rows($sql) > 0 && $tousers[$i] != $check['id'])
        {
            $sql = $data->select_query("pms", "WHERE type=1 AND touser=$to ORDER BY date ASC", "id");
            if ($data->num_rows($sql) >= $config['numpm'])
            {
                $temp = $data->fetch_array($sql);
                $data->delete_query("pms", "id={$temp['id']}");
            }
            $sql = $data->insert_query("pms", "NULL, $subject, $userpm, $timestamp, 1, 0, 1, $username, $to", "", "", false);
            if ($sql)
            {
                $sql = $data->select_query("pms", "WHERE subject=$subject AND text=$userpm AND touser=$to AND fromuser=$username");
                $sentpm = $data->fetch_array($sql);
                sendpmmail($tousers[$i], $sentpm);
                $okusers[] = $tousers[$i];
                $usernames[] = $userinfo['uname'];
            }
        }
    }

    if (count($okusers) == 1)
    {
        $userlist = implode(', ', $okusers);
        $usernames = implode(', ', $usernames);
        $users = safesql($userlist, "text");
        $sql = $data->select_query("pms", "WHERE type=2 AND fromuser=$username ORDER BY date ASC", "id");
        if ($data->num_rows($sql) >= $config['numpm'])
        {
            $temp = $data->fetch_array($sql);
            $data->delete_query("pms", "id={$temp['id']}");
        }
        $sql = $data->update_query("pms", "date = $timestamp, type=2, touser=$users", "id=$pid", "", "", false);
        $message .= "Your message has been sent to the following user: $usernames.";
    }
    elseif (count($okusers) > 1)
    {
        $userlist = implode(', ', $okusers);
        $usernames = implode(', ', $usernames);
        $users = safesql($userlist, "text");
        $sql = $data->select_query("pms", "WHERE type=2 AND fromuser=$username ORDER BY date ASC", "id");
        if ($data->num_rows($sql) >= $config['numpm'])
        {
            $temp = $data->fetch_array($sql);
            $data->delete_query("pms", "id={$temp['id']}");
        }
        $sql = $data->update_query("pms", "date = $timestamp, type=2, touser=$users", "id=$pid", "", "", false);
        $message .= "Your message has been sent to the following users: $usernames.";
    }
    
    show_message($message, "index.php?page=pmmain&menuid={$menuid}");
}

if(isset($_GET['user']))
{
   $user = safesql($_GET['user'], "int");
   $user = $data->select_fetch_one_row("users", "WHERE id=$user", "uname");   
   $newpm['touser'] = $user['uname'];
    $tpl->assign("editpm", $newpm);
}
elseif (isset($_GET['group']))
{
	$groupid = safesql($_GET['group'], "int");
	if (user_group_id($check['id'], $_GET['group']))
	{
		$groupusers = $data->select_query("usergroups", "WHERE groupid = {$groupid} AND userid != {$check['id']}");

		$names = array();
		while($temp = $data->fetch_array($groupusers))
		{
			$names[] = $userIdList[$temp['userid']];
		}

		$newpm['touser'] = implode(', ', $names);

		$tpl->assign("editpm", $newpm);
    }
}

if (($_POST['submit'] == "Send PM" || $_POST['submit'] == "Save PM") && $editit==true && isset($_GET['id']))
{
    $pid = $_GET['id'];
    $data->delete_query("pms", "id=$pid", "", "", false);
}

if ($_POST['submit'] == "Send PM")
{
    $tousers = explode(',', strip_tags(trim($_POST['touser'])));
    $subject = safesql($_POST['subject'], "text");
    $pm = safesql($_POST['story'], "text", false);
    $username = safesql($check['id'], "int");
    $okusers = array();
    $notokusers = array();
    for($i=0;$i<count($tousers);$i++)
    {
        $message = "";
        $to = safesql(trim($tousers[$i]), "text");
        $sql = $data->select_query("users", "WHERE uname = $to", "id");
        $userinfo = $data->fetch_array($sql);
        if ($data->num_rows($sql) > 0 && $tousers[$i] != $check['uname'])
        {
            $sql = $data->select_query("pms", "WHERE type=1 AND touser={$userinfo['id']} ORDER BY date ASC", "id");
            if ($data->num_rows($sql) >= $config['numpm'])
            {
                $temp = $data->fetch_array($sql);
                $data->delete_query("pms", "id={$temp['id']}");
            }
            $sql = $data->insert_query("pms", "NULL, $subject, $pm, $timestamp, 1, 0, 1, $username, {$userinfo['id']}", "", "", false);
            if ($sql)
            {
                $sql = $data->select_query("pms", "WHERE touser={$userinfo['id']} AND fromuser=$username AND date=$timestamp");
                $sentpm = $data->fetch_array($sql);
                sendpmmail($userinfo['id'], $sentpm);
                $okusers[] = $userinfo['id'];
                $usernames[] = $tousers[$i];
            }
        }
	elseif ($tousers[$i] == $check['uname'] && count($tousers) == 1)
	{
		show_message("Sorry, You can't send a message to yourself", $postaction, true);	
	}
    }

    if (count($okusers) == 1)
    {
        $userlist = implode(', ', $okusers);
        $usernames = implode(', ', $usernames);
        $users = safesql($userlist, "text");
        $sql = $data->select_query("pms", "WHERE type=2 AND fromuser={$check['id']} ORDER BY date ASC", "id");
        if ($data->num_rows($sql) >= $config['numpm'])
        {
            $temp = $data->fetch_array($sql);
            $data->delete_query("pms", "id={$temp['id']}");
        }
        $sql2 = $data->insert_query("pms", "NULL, $subject, $pm, $timestamp, 2, 1, 0, {$check['id']}, $users", "", "", false);
        $message .= "Your message has been sent to the following user: $usernames.";
    }
    elseif (count($okusers) > 1)
    {
        $userlist = implode(', ', $okusers);
        $usernames = implode(', ', $usernames);
        $users = safesql($userlist, "text");
        $sql = $data->select_query("pms", "WHERE type=2 AND fromuser={$check['id']} ORDER BY date ASC", "id");
        if ($data->num_rows($sql) >= $config['numpm'])
        {
            $temp = $data->fetch_array($sql);
            $data->delete_query("pms", "id={$temp['id']}");
        }
        $sql2 = $data->insert_query("pms", "NULL, $subject, $pm, $timestamp, 2, 1, 0, {$check['id']}, $users", "", "", false);
        $message .= "Your message has been sent to the following users: $usernames.";
    }
    else
    {
        $message .= "There was something wrong with your message, please try again or contact the site administrator.";
    }
    
    show_message($message, "index.php?page=pmmain&menuid={$menuid}");
}
elseif ($_POST['submit'] == "Save PM")
{
    $tousers = explode(',', strip_tags($_POST['touser']));
    $subject = safesql($_POST['subject'], "text");
    $pm = safesql($_POST['story'], "text", false);
    $username = safesql($check['uname'], "text");
    $okusers = array();
    $notokusers = array();
    for($i=0;$i<count($tousers);$i++)
    {
        $message = "";
        $to = safesql(trim($tousers[$i]), "text");
        $sql = $data->select_query("users", "WHERE uname = $to", "id");
        $userinfo = $data->fetch_array($sql);
        if ($data->num_rows($sql) > 0 && $tousers[$i] != $check['uname'])
        {
            if ($sql)
            {
                $okusers[] = $userinfo['id'];
            }
        }        
    }

    if (count($okusers) > 0)
    {
        $userlist = implode(', ', $okusers);
        $to = safesql($userlist, "text");
        $sql = $data->select_query("pms", "WHERE type=4 AND fromuser={$check['id']} ORDER BY date ASC", "id");
        if ($data->num_rows($sql) >= $config['numpm'])
        {
            $temp = $data->fetch_array($sql);
            $data->delete_query("pms", "id={$temp['id']}");
        }
        $sql = $data->insert_query("pms", "NULL, $subject, $pm, $timestamp, 4, 1, 0, {$check['id']}, $to", "", "", false);
        $message .= "Your message has been saved in your drafts folder. ";
    }
    
    show_message($message, "index.php?page=pmmain&action=drafts&menuid={$menuid}");
}
$tpl->assign("isedit", "simp");
$location = "User Control Panel >> New Message";
$tpl->assign("pm", $inboxpm);
$tpl->assign("numpm", $numpm);
$tpl->assign("onpage", "New Personal Message");
$scriptList['tinyAdv'] = 1;
$pagenum = 3;
?>