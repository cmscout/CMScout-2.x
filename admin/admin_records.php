<?php
/**************************************************************************
    FILENAME        :   admin_records.php
    PURPOSE OF FILE :   Manages users records
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

if( !empty($getmodules) )
{
	return;
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) 
{
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$action = $_GET['action'];
$what = $_POST['what'];
$id = $_GET['id'];
$safe_memberid = safesql($_GET['id'], "int");
$submit = $_POST['Submit'];

$memberdetails = $data->select_fetch_one_row("members", "WHERE id=$safe_memberid");
$safe_id = safesql($memberdetails['userId'], "int");
$safe_scheme = safesql((isset($_GET['scheme']) ? $_GET['scheme'] : $memberdetails['awardScheme']), "int");
$tpl->assign("schemeNumber", (isset($_GET['scheme']) ? $_GET['scheme'] : $memberdetails['awardScheme']));

$row = $data->select_fetch_one_row("users", "WHERE id=$safe_id");

$tpl->assign("memberdetails", $memberdetails);

if ($action == "delete_badge" && pageauth("troop", "edit") == 1)
{
    $bid = safesql($_GET['bid'], "int");
    $data->delete_query("userbadges", "userid = $safe_memberid AND id = $bid");
    show_admin_message("Badge Removed", "admin.php?page=$page&subpage=records&id=$id&action=view_badges");
}

if ($submit == "Submit")
{
    if ($action == "edit_advancements" && pageauth("troop", "edit") == 1)
    {       
        $recordsql = $data->select_query("scoutrecord", "WHERE userid=$safe_memberid AND scheme = $safe_scheme");
        if ($data->num_rows($recordsql) > 0)
        {
            $record = safesql(serialize($_POST['requirement']), "text");
            $comments = safesql(serialize($_POST['comment']), "text");
            $data->update_query("scoutrecord", "requirements=$record, comment=$comments", "userid=$safe_memberid AND scheme= $safe_scheme");
        }
        else
        {
            $record = safesql(serialize($_POST['requirement']), "text");
            $comments = safesql(serialize($_POST['comment']), "text");
            $data->insert_query("scoutrecord", "'', $safe_memberid, $record, $comments, $safe_scheme");
        }
        show_admin_message("Record Updated", "admin.php?page=$page&subpage=records&id=$id&action=view_advancements");
    }
    elseif ($action == "addbadge" && pageauth("troop", "edit") == 1)
    {
        $badgeid = safesql($_POST['bid'], "int");
        $comment = safesql($_POST['comment'], "text");
        $date = safesql(time(), "int");

        $data->insert_query("userbadges", "'', $safe_memberid, $badgeid, $comment, $date");
        show_admin_message("Badge Added", "admin.php?page=$page&subpage=records&id=$id&action=view_badges");
    }
}

$schemes = $data->select_fetch_all_rows($numschemes, "awardschemes", "ORDER BY name ASC");
$tpl->assign("schemes", $schemes);
$tpl->assign("numschemes", $numschemes);
if (($action == "view_advancements" || $action=="") || ($action == "edit_advancements"  && pageauth("troop", "edit") == 1))
{
    $advansql = $data->select_query("advancements", "WHERE scheme = $safe_scheme ORDER BY position ASC");
    $numadva = $data->num_rows($advansql);
    $advancements = array();
    $numitems = 0;
    $recordsql = $data->select_fetch_one_row("scoutrecord", "WHERE userid=$safe_memberid AND scheme = $safe_scheme");
    $scoutRecord['requirement'] = unserialize($recordsql['requirements']);
    $scoutRecord['comment'] = unserialize($recordsql['comment']);
    
    while ($temp = $data->fetch_array($advansql)) 
    {
        $getrequirements = $data->select_query("requirements", "WHERE advancement = '{$temp["ID"]}' ORDER BY position ASC");
        
        $temp['numitems'] = $data->num_rows($getrequirements);
        while ($temp['items'][] = $data->fetch_array($getrequirements));
        $advancements[] = $temp;
    }
    $tpl->assign("scoutRecord", $scoutRecord);
    $tpl->assign("advan", $advancements);
    $tpl->assign("numadva", $numadva);
}
elseif ($action == "view_badges")
{
    $badgesql = $data->select_query("userbadges", "WHERE userid = $safe_memberid");
    $numbadge = 0;
    $badges = array();
    while ($temp = $data->fetch_array($badgesql))
    {
        $result = $data->select_fetch_one_row("badges", "WHERE id = {$temp['badgeid']} AND scheme=$safe_scheme");
        if ($result != '')
        {
            $temp['name'] = $result['name'];
            $temp['description'] = $result['description'];
            $badges[] = $temp;
            $numbadge++;
        }
    }
    $result = $data->select_query("badges", "WHERE scheme = $safe_scheme ORDER BY name ASC");
    $available  = array();
    $numavailable = 0;
    while ($temp = $data->fetch_array($result))
    {
        $badgesql = $data->select_query("userbadges", "WHERE userid = $safe_memberid AND badgeid={$temp['id']}");
        if ($data->num_rows($badgesql) == 0)
        {
            $available[] = $temp;
            $numavailable++;
        }
    }
        
    $tpl->assign("badges", $badges);
    $tpl->assign("numbadge", $numbadge);
    $tpl->assign("available", $available);
    $tpl->assign("numavailable", $numavailable);
}
$tpl->assign("username", $username);
$tpl->assign("id", $id);

$tpl->assign('editFormAction', $editFormAction);
$tpl->assign("action", $action);
$filetouse='admin_records.tpl';
?>