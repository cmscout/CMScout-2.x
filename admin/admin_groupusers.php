<?php
/**************************************************************************
    FILENAME        :   admin_usergroups.php
    PURPOSE OF FILE :   Manage a users groups
    LAST UPDATED    :   30 August 2006
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
else
{
    $uid = safesql($_GET['uid'], "int");
    $gid = safesql($_GET['gid'], "int");
    
    $action = $_GET['action'];
    
    if ($action == "delete" && pageauth("group", "edit") == 1)
    {
        if ($uid != null && $gid != null && $uid != 0 && $gid != 0)
        {
            $sql = $data->delete_query("usergroups", "groupid=$gid AND userid=$uid");
        }
    }
    elseif ($action == "add" && pageauth("group", "edit") == 1)
    {
        $uid = safesql($_POST['uid'], "int");
        $utype = safesql($_POST['utype'], "int");
        $sql = $data->select_query("usergroups", "WHERE userid=$uid AND groupid=$gid");
        if ($data->num_rows($sql) == 0 && $gid != 0)
        {
            $data->insert_query("usergroups", "$gid, $uid, $utype");
        }
    }
    elseif ($action == "moveup")
    {
        $uid = safesql($_GET['uid'], "int");
        $userGroups = $data->select_fetch_one_row("usergroups", "WHERE userid=$uid AND groupid=$gid");
        $userGroups['utype'] = $userGroups['utype'] + 1;
        if ($userGroups['type'] <= 2)
        {
        	$data->update_query("usergroups", "utype={$userGroups['utype']}", "userid = $uid AND groupid=$gid");
        }
        show_admin_message("User type changed", str_replace('&amp;', '&', $pagename) . "&gid=$gid");
    }
    elseif ($action == "movedown")
    {
        $uid = safesql($_GET['uid'], "int");
        $userGroups = $data->select_fetch_one_row("usergroups", "WHERE userid=$uid AND groupid=$gid");
        $userGroups['utype'] = $userGroups['utype'] - 1;
        if ($userGroups['type'] >= 0)
        {
        	$data->update_query("usergroups", "utype={$userGroups['utype']}", "userid = $uid AND groupid=$gid");
        }
        show_admin_message("User type changed", str_replace('&amp;', '&', $pagename) . "&gid=$gid");
    }
    
    $sql = $data->select_query("groups", "WHERE id=$gid");
    $groupinfo = $data->fetch_array($sql);
    
    $sql = $data->select_query("usergroups", "WHERE groupid=$gid");
    $groupusers = array();
    $numgroupusers = $data->num_rows($sql);
    while ($temp = $data->fetch_array($sql))
    {
        $sql2 = $data->select_query("users", "WHERE id={$temp['userid']}", "id, uname");
        $temp2 = $data->fetch_array($sql2);
        $temp2['type'] = $temp['utype'];
        $groupusers[] = $temp2;
    }
    
    $sql = $data->select_query("users", "ORDER BY uname ASC");
    $numusers = 0;
    $users = array();
    while ($temp = $data->fetch_array($sql))
    {
        if ($data->num_rows($data->select_query("usergroups", "WHERE groupid=$gid and userid={$temp['id']}")) == 0)
        {
            $users[] = $temp;
            $numusers++;
        }
    }
    
    $tpl->assign("editallowed_page", pageauth("group", "edit"));
    
    $tpl->assign("numusers", $numusers);
    $tpl->assign("users", $users);
    $tpl->assign("uname", $check['uname']);
    $tpl->assign("numgroupusers", $numgroupusers);
    $tpl->assign("groupusers", $groupusers);
    $tpl->assign("groupinfo", $groupinfo);
    $filetouse = "admin_groupusers.tpl";
}
?>