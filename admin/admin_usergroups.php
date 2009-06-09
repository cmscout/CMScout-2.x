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
    
    if ($action == "delete" && pageauth("users", "edit") == 1)
    {
        if ($uid != null && $gid != null && $uid != 0 && $gid != 0)
        {
            $sql = $data->delete_query("usergroups", "groupid=$gid AND userid=$uid");
        }
    }
    elseif ($action == "add" && pageauth("users", "edit") == 1)
    {
        $gid = safesql($_POST['gid'], "int");
        $utype = safesql($_POST['utype'], "int");
        $sql = $data->select_query("usergroups", "WHERE userid=$uid AND groupid=$gid");
        if ($data->num_rows($sql) == 0 && $gid != 0)
        {
            $data->insert_query("usergroups", "$gid, $uid, $utype");
        }
    }
    $sql = $data->select_query("users", "WHERE id=$uid");
    $userinfo = $data->fetch_array($sql);
    
    $sql = $data->select_query("usergroups", "WHERE userid=$uid");
    $usergroups = array();
    $numusergroups = $data->num_rows($sql);
    while ($temp = $data->fetch_array($sql))
    {
        $sql2 = $data->select_query("groups", "WHERE id={$temp['groupid']}", "id, teamname");
        $temp2 = $data->fetch_array($sql2);
        $temp2['type'] = $temp['utype'];
        $usergroups[] = $temp2;
    }
    
    $sql = $data->select_query("groups");
    $numgroups = 0;
    $groups = array();
    while ($temp = $data->fetch_array($sql))
    {
        if ($data->num_rows($data->select_query("usergroups", "WHERE userid=$uid and groupid={$temp['id']}")) == 0)
        {
            $groups[] = $temp;
            $numgroups++;
        }
    }
    $tpl->assign("editallowed_page", pageauth("users", "edit"));
    $tpl->assign("numgroups", $numgroups);
    $tpl->assign("groups", $groups);
    $tpl->assign("numusergroups", $numusergroups);
    $tpl->assign("usergroups", $usergroups);
    $tpl->assign("userinfo", $userinfo);
    $filetouse = "admin_usergroups.tpl";
}
?>