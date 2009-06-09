<?php
/**************************************************************************
    FILENAME        :   usercp.php
    PURPOSE OF FILE :   Displays user control panel
    LAST UPDATED    :   13 February 2006
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
    
    
$action = $_GET['action'];

if ($action == "rssfeedsdelete")
{
    $id = safesql($_GET['id'], "int");
    $data->delete_query("rssfeeds", "id=$id");
    show_message("Feed deleted", "index.php?page=usercp&menuid=$menuid");
}
if ($action == "forumpostdelete")
{
    $id = safesql($_GET['id'], "int");
    $user = safesql($check['id'], "int");
    $data->delete_query("forumstopicwatch", "topic_id=$id AND uid=$user");
    show_message("Watch deleted", "index.php?page=usercp&menuid=$menuid");
}


if ($action != "updatedetails" && $action != "updatechild")
{
    $location = "User Control Panel";

    $user = safesql($check['id'], "int");
    $sql = $data->select_query("forumstopicwatch", "WHERE uid = $user AND notify != 0");
    $numwatch = $data->num_rows($sql);
    $watch = array();
    while ($temp = $data->fetch_array($sql))
    {
        $itemid = $temp['topic_id'];
        $temp2 = $data->select_fetch_one_row("forumtopics","WHERE id=$itemid", "subject");
        $temp['title'] = $temp2['subject'];
        $watch[] = $temp;
    }

    $tpl->assign("numwatch", $numwatch);
    $tpl->assign("watch", $watch);

    $user = safesql(md5($check['uname']), "text");
    $sql = $data->select_query("rssfeeds", "WHERE uname = $user");
    $numfeeds = $data->num_rows($sql);
    $feeds = array();
    while ($temp = $data->fetch_array($sql))
    {
        $itemid = $temp['itemid'];
        switch($temp['type'])
        {
            case 1 :
                    $temp2 = $data->select_fetch_one_row("forums","WHERE id=$itemid", "name");
                    $temp['title'] = $temp2['name'];
                    break;
            case 2 :
                    $temp2 = $data->select_fetch_one_row("groups","WHERE id=$itemid", "teamname");
                    $temp['title'] = $temp2['teamname'] . " log";
                    break;
            case 3 :
                    $temp['title'] = "Events";
                    break;
            case 4 :
                    $temp['title'] = "General articles";
                    break;
            case 5 :
                    $temp2 = $data->select_fetch_one_row("groups","WHERE id=$itemid", "teamname");
                    $temp['title'] = $temp2['teamname'] . " articles";
                    break;
            case 6 :
                    $temp['title'] = "News";
                    break;
        }
        $feeds[] = $temp;
    }

    $tpl->assign("numfeeds", $numfeeds);
    $tpl->assign("feeds", $feeds);

    $sql = $data->select_query("profilefields", "WHERE profileview = 1 AND place=0 ORDER BY pos ASC");
    $fields = array();
    $numfields = $data->num_rows($sql);
    while ($temp =  $data->fetch_array($sql))
    {
        $temp['options'] = unserialize($temp['options']);
        $fields[] = $temp;
    }      

    $id = safesql($check['id'], "int");
    $info = $data->select_fetch_one_row("users", "WHERE id=$id");

    $info['custom'] = unserialize($info['custom']);
    $info['group'] = user_groups_list($info['id'], true);
    $info['online'] = user_online($info['uname']);
    $info['location'] = user_location($info['uname']);

    $tpl->assign('fields', $fields);
    $tpl->assign('numfields', $numfields);   
    $tpl->assign('info', $info);
    $tpl->assign("timenow", time());

    $sql = $data->select_query("members", "WHERE userId={$check['id']}");
    $tpl->assign("member", $data->num_rows($sql) > 0 ? 1 : 0);

    if ($data->num_rows($sql) > 0 )
    {
        $id = safesql($check['id'], "int");
        $member = $data->fetch_array($sql);
        $member['custom'] = unserialize($member['custom']);  
        
        $member['father'] = $data->select_fetch_one_row("members", "WHERE id={$member['fatherId']}", "firstName, lastName, cell, home, work, email");
        $member['mother'] = $data->select_fetch_one_row("members", "WHERE id={$member['motherId']}", "firstName, lastName, cell, home, work, email");
        $member['awardScheme'] = $data->select_fetch_one_row("awardschemes", "WHERE id={$member['awardScheme']}", "name");
        $member['user'] = $data->select_fetch_one_row("users", "WHERE id={$member['userId']}", "uname, firstname, lastname");
        $member['patrolname'] = $data->select_fetch_one_row("groups", "WHERE id={$member['patrol']}", "teamname");
        $member['section'] = $data->select_fetch_one_row("sections", "WHERE id={$member['section']}", "name");
        
        $sql = $member['type'] != 0 ? $data->select_query("profilefields", "WHERE place=1 AND register=0 AND profileview = 1 ORDER BY pos ASC") : $data->select_query("profilefields", "WHERE place=1 AND profileview = 1 ORDER BY pos ASC");
        $fields = array();
        $numfields = $data->num_rows($sql);
        while ($temp =  $data->fetch_array($sql))
        {
            $temp['options'] = unserialize($temp['options']);
            $fields[] = $temp;
        }
	
	if ($member['type'] == 1 || $member['type'] == 2)
	{
		$member['child'] = $member['type'] == 1 ? $data->select_fetch_all_rows($numchildren, "members", "WHERE fatherid = {$member['id']} ORDER BY lastName ASC") : $data->select_fetch_all_rows($numchildren, "members", "WHERE motherid = {$member['id']} ORDER BY lastName ASC");
		$tpl->assign("numchildren", $numchildren);
	}

        $tpl->assign('memberfields', $fields);
        $tpl->assign('nummemberfields', $numfields);

        $tpl->assign("memberinfo", $member);
    }

    $scriptList['mootabs'] = 1;
}
else
{
    $location = "User Control Panel >> Update Member Details";
    
    if ($action == "updatedetails")
    {
	    $sql = $data->select_query("members", "WHERE userId={$check['id']}");
    }
    else
    {
	    $id = safesql($_GET['id'], "int");
	    $user = $data->select_fetch_one_row("members", "WHERE userId={$check['id']}", "id");
	    $sql = $data->select_query("members", "WHERE id=$id AND (fatherId = {$user['id']} OR motherId = {$user['id']})");
    }

    if ($data->num_rows($sql) > 0 )
    {
        $id = safesql($check['id'], "int");
        $member = $data->fetch_array($sql);
        $member['custom'] = unserialize($member['custom']);  
        
	    $member['father'] = $data->select_fetch_one_row("members", "WHERE id={$member['fatherId']}", "firstName, lastName, cell, home, work, email");
	    $member['mother'] = $data->select_fetch_one_row("members", "WHERE id={$member['motherId']}", "firstName, lastName, cell, home, work, email");

        $tpl->assign("member", $member);
    }   
    $scriptList['datepicker'] = 1;
    if ($_POST['submit'] == "Submit")
    {
        $firstname = safesql($_POST['firstname'], "text");
        $middlename = safesql($_POST['middlename'], "text");
        $lastname = safesql($_POST['lastname'], "text");
        $sex = safesql($_POST['sex'], "int");
        $address = safesql($_POST['address'], "text");
        $homenumber = safesql($_POST['homenumber'], "text");
        $cellnumber = safesql($_POST['cellnumber'], "text");
        $worknumber = safesql($_POST['worknumber'], "text");
        $dob = safesql(strtotime($_POST['dob']), "int");
        $email = safesql($_POST['email'], "text");
        $medicalname = safesql($_POST['medicalname'], "text");
        $medicalnumber = safesql($_POST['medicalnumber'], "text");
        $docname = safesql($_POST['docname'], "text");
        $docnum = safesql($_POST['docnum'], "text");
        $medical = safesql($_POST['medical'], "text");
        
        $primaryGuard = safesql($_POST['primaryGuard'], "int");
        
        $id = safesql($member['id'], "int");
        
        $data->update_query("members", "firstName=$firstname, middleName=$middlename, lastName=$lastname, dob=$dob, sex=$sex, address=$address, cell=$cellnumber, home=$homenumber, work=$worknumber, email=$email, aidName=$medicalname, aidNumber=$medicalnumber, docName=$docname, docNumber=$docnum, medicalDetails=$medical, primaryGuard=$primaryGuard", "id=$id");
        
        if ($member['fatherId'])
        {
            $father = $_POST['father'];
            
            $father['home'] = safesql($father['home'], "text");
            $father['cell'] = safesql($father['cell'], "text");
            $father['work'] = safesql($father['work'], "text");
            $father['email'] = safesql($father['email'], "text");
            
            $data->update_query("members", "cell={$father['cell']}, home={$father['home']}, work={$father['work']}, email={$father['email']}", "id={$member['fatherId']}");
        }
        
        if ($member['motherId'])
        {
            $mother = $_POST['mother'];
            $mother['home'] = safesql($mother['home'], "text");
            $mother['cell'] = safesql($mother['cell'], "text");
            $mother['work'] = safesql($mother['work'], "text");
            $mother['email'] = safesql($mother['email'], "text");

            $data->update_query("members", "cell={$mother['cell']}, home={$mother['home']}, work={$mother['work']}, email={$mother['email']}", "id={$member['motherId']}");
        }
        show_message("Member Updated", "index.php?page=usercp&menuid=$menuid&activetab=member");
    }
    
    $pagenum=2;
}
$dbpage = true;
$pagename = "usercp";
?>