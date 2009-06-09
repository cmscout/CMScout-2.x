<?php
/**************************************************************************
    FILENAME        :   admin_group.php
    PURPOSE OF FILE :   Manage groups and patrols
    LAST UPDATED    :   02 October 2006
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
	$module['User and Profile Management']['Group Manager'] = "group";
    $moduledetails[$modulenumbers]['name'] = "Group Manager";
    $moduledetails[$modulenumbers]['details'] = "Manages groups and group administration panel permisions";
    $moduledetails[$modulenumbers]['access'] = "Allowed to view groups";
    $moduledetails[$modulenumbers]['add'] = "Allowed to add a new group";
    $moduledetails[$modulenumbers]['edit'] = "Allowed to edit a existing group (Includes group users)";
    $moduledetails[$modulenumbers]['delete'] = "Allowed to delete a existing group";
    $moduledetails[$modulenumbers]['publish'] = "Allowed to change admin panel permissions on a group";
    $moduledetails[$modulenumbers]['limit'] = "notused";
    $moduledetails[$modulenumbers]['id'] = "group";

	return;
}
else
{		
    $subpage = $_GET['subpage'] != '' ? $_GET['subpage'] : '';
    
    if (!$subpage)
    {    
        $listusers = $data->select_query("users");
        
        $editFormAction = $_SERVER['PHP_SELF'];
        if (isset($_SERVER['QUERY_STRING'])) 
        {
          $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
        }
        
        $action = $_GET['action'];
        $id = $_GET['id'];
        if ($action == "edit" && pageauth("group", "edit") == 1) 
        {           
            $sql = $data->select_query("groups", "WHERE id = $id");
            $stuff = $data->fetch_array($sql);
            $tpl->assign('group', $stuff);
            $submit = $_POST['Submit'];
            if ($submit == 'Submit') 
            {
                $teamname = safesql($_POST['name'], "text");
                $ispatrol =	safesql($_POST['patrol'], "int");
                $ispublic =	safesql($_POST['publicgroup'], "int");
                $getpoints = safesql($_POST['points'], "int");
                $copypermissions = safesql($_POST['permissions'], "int");

                if ($data->num_rows($data->select_query("groups", "WHERE teamname=$teamname AND id!=$id"))>0)
                {
                    show_message_back("There is already a group by that name");
                    exit;
                }
                
                if ($copypermissions != 0)
                {
                	$otherGroup = $data->select_fetch_one_row("groups", "WHERE id=$copypermissions");
                	$normaladmin = safesql($otherGroup['normaladmin'], "text");
                	$agladmin = safesql($otherGroup['agladmin'], "text");
                	$gladmin = safesql($otherGroup['gladmin'], "text");
                	$sql3 = $data->update_query("groups", "teamname=$teamname, ispatrol=$ispatrol, ispublic=$ispublic, getpoints=$getpoints, normaladmin=$normaladmin, agladmin=$agladmin, gladmin=$gladmin", "id = $id");
                }
                else
                {
                	$sql3 = $data->update_query("groups", "teamname=$teamname, ispatrol=$ispatrol, ispublic=$ispublic, getpoints=$getpoints", "id = $id");
                }
                if ($ispublic == 1) 
                {
                    $sql = $data->select_query("static_content", "WHERE type = 1 AND pid=$id");
                    if ($data->num_rows($sql) == 0)
                    {
                        $data->insert_query("static_content", "'', 'frontpage', 'The group leader has not put any information here yet.', 'Frontpage', 1, 1, $id, 1, 0");
                    }
                    $sql = $data->select_query("patrolmenu", "WHERE patrol = $id");
                    if ($data->num_rows($sql) == 0) 
                    {
                        $data->insert_query("patrolmenu", "'', 'Articles', 26, 2, $id, 3");
                        $data->insert_query("patrolmenu", "'', 'Photos',  27, 2, $id, 2");
                        $data->insert_query("patrolmenu", "'', 'Home', 28, 2, $id, 1");
                    }
                }
                else
                {
                        $data->delete_query("static_content", "type = 1 AND pid=$id");
                        $data->delete_query("patrolmenu", "patrol=$id");
                }
        
                if ($sql3)
                {
                    show_admin_message("Group Updated", $pagename);
                }
                $action = '';
            }
        } 
        elseif ($action == "Add" && pageauth("group", "add") == 1) 
        {
            $submit = $_POST['Submit'];
            if ($submit == 'Submit') 
            {
                $teamname = safesql($_POST['name'], "text");
                $ispatrol =	safesql($_POST['patrol'], "int");
                $ispublic =	safesql($_POST['publicgroup'], "int");
                $getpoints = safesql($_POST['points'], "int");
                $copypermissions = safesql($_POST['permissions'], "int");
                
                if ($data->num_rows($data->select_query("groups", "WHERE teamname=$teamname"))>0)
                {
                    show_message_back("There is already a group by that name");
                    exit;
                }
                if ($copypermissions != 0)
                {
                	$otherGroup = $data->select_fetch_one_row("groups", "WHERE id=$copypermissions");
                	$normaladmin = safesql($otherGroup['normaladmin'], "text");
                	$agladmin = safesql($otherGroup['agladmin'], "text");
                	$gladmin = safesql($otherGroup['gladmin'], "text");
                	$sql3 = $data->insert_query("groups", "NULL, $teamname, $ispatrol, $ispublic, $getpoints, 0, $normaladmin, $agladmin, $gladmin");
                }
                else
                {
                	$sql3 = $data->insert_query("groups", "NULL, $teamname, $ispatrol, $ispublic, $getpoints, 0, '', '', ''");
                }
                if ($ispublic == 1) 
                {
                    $temp = $data->select_fetch_one_row("groups", "WHERE teamname = $teamname", "id");
                    $id = $temp['id'];
                    $sql = $data->select_query("static_content", "WHERE type = 1 AND pid=$id");
                    if ($data->num_rows($sql) == 0)
                    {
                        $data->insert_query("static_content", "'', 'frontpage', 'The group leader has not put any information here yet.', 'Frontpage', 1, 1, $id, 1, 0");
                    }
                    $sql = $data->select_query("patrolmenu", "WHERE patrol = $id");
                    if ($data->num_rows($sql) == 0) 
                    {
                        $data->insert_query("patrolmenu", "'', 'Articles', 26, 2, $id, 3");
                        $data->insert_query("patrolmenu", "'', 'Photos',  27, 2, $id, 2");
                        $data->insert_query("patrolmenu", "'', 'Home', 28, 2, $id, 1");
                    }
                }

                if ($sql3)
                {
                    show_admin_message("Group Added", $pagename); 
                }
            }
        } 
        elseif ($action == "delete" && pageauth("group", "delete") == 1) 
        {
            $sql3 = $data->delete_query("groups", "id=$id");
            
            if ($sql3)
            {
                $data->delete_query("static_content", "type=1 AND pid=$id");
                $data->delete_query("patrolmenu", "patrol=$id");
                $data->delete_query("auth", "authname=$id AND type=2");
                $data->delete_query("usergroups", "groupid=$id");
                $data->update_query("patrol_articles", "patrol=0", "patrol=$id");
                $data->update_query("album_track", "patrol=0", "patrol=$id");
                $data->delete_query("forummods", "mid=$id AND type=1");
                $data->delete_query("owners", "owner_id=$id AND owner_type=1");
                show_admin_message("Group Deleted", $pagename); 
            }
        }
        elseif ($action == "auth" && pageauth("group", "publish") == 1)
        {        
            if ($_POST['Submit'] == "Submit")
            {
                $user = array();
                $ass = array();
                $gpl = array();
                for($i=0;$i<$modulenumbers;$i++)
                {
                    $moduleid = $moduledetails[$i]['id'];
                    
                    $user['adminpanel'] = $_POST["user_adminpanel"] == 1 ? 1 : 0;
                    $ass['adminpanel'] = $_POST["ass_adminpanel"] == 1 ? 1 : 0;
                    $gpl['adminpanel'] = $_POST["gpl_adminpanel"] == 1 ? 1 : 0;

                    $user['access'][$moduleid] = $_POST["user_" . $moduleid . "_access"] == 1 ? 1 : 0;
                    $user['add'][$moduleid] = $_POST["user_" . $moduleid . "_add"] == 1 ? 1 : 0;
                    $user['edit'][$moduleid] = $_POST["user_" . $moduleid . "_edit"] == 1 ? 1 : 0;
                    $user['delete'][$moduleid] = $_POST["user_" . $moduleid . "_delete"] == 1 ? 1 : 0;
                    $user['publish'][$moduleid] = $_POST["user_" . $moduleid . "_pub"] == 1 ? 1 : 0;
                    $user['limit'][$moduleid] = $_POST["user_" . $moduleid . "_limit"] == 1 ? 1 : 0;

                    $ass['access'][$moduleid] = $_POST["ass_" . $moduleid . "_access"] == 1 ? 1 : 0;
                    $ass['add'][$moduleid] = $_POST["ass_" . $moduleid . "_add"] == 1 ? 1 : 0;
                    $ass['edit'][$moduleid] = $_POST["ass_" . $moduleid . "_edit"] == 1 ? 1 : 0;
                    $ass['delete'][$moduleid] = $_POST["ass_" . $moduleid . "_delete"] == 1 ? 1 : 0;
                    $ass['publish'][$moduleid] = $_POST["ass_" . $moduleid . "_pub"] == 1 ? 1 : 0;
                    $ass['limit'][$moduleid] = $_POST["ass_" . $moduleid . "_limit"] == 1 ? 1 : 0;

                    $gpl['access'][$moduleid] = $_POST["gpl_" . $moduleid . "_access"] == 1 ? 1 : 0;
                    $gpl['add'][$moduleid] = $_POST["gpl_" . $moduleid . "_add"] == 1 ? 1 : 0;
                    $gpl['edit'][$moduleid] = $_POST["gpl_" . $moduleid . "_edit"] == 1 ? 1 : 0;
                    $gpl['delete'][$moduleid] = $_POST["gpl_" . $moduleid . "_delete"] == 1 ? 1 : 0;
                    $gpl['publish'][$moduleid] = $_POST["gpl_" . $moduleid . "_pub"] == 1 ? 1 : 0;
                    $gpl['limit'][$moduleid] = $_POST["gpl_" . $moduleid . "_limit"] == 1 ? 1 : 0;
                }
                $user = safesql(serialize($user), "text");
                $ass = safesql(serialize($ass), "text");
                $gpl = safesql(serialize($gpl), "text");
                
                $data->update_query("groups", "normaladmin = $user, agladmin = $ass, gladmin = $gpl", "id=$id");
                show_admin_message("Group administration panel access updated", "$pagename");
            }
            else
            {
                $tpl->assign("nummodules", $modulenumbers);
                $tpl->assign("modules", $moduledetails);
                $sql = $data->select_query("groups", "WHERE id=$id");
                $group = $data->fetch_array($sql);
                $user = unserialize($group['normaladmin']);
                $ass = unserialize($group['agladmin']);
                $gpl = unserialize($group['gladmin']);
                
                $tpl->assign("group", $group);
                $tpl->assign("user", $user);
                $tpl->assign("ass", $ass);
                $tpl->assign("gpl", $gpl);
                
            }
        }
        $message = "";
        
        $result = $data->select_query("groups", "ORDER BY teamname ASC");
        $row_teaminfo = array();
        $numteams = $data->num_rows($result);
        while ($row_teaminfo[] = $data->fetch_array($result));
        
        $tpl->assign('editFormAction', $editFormAction);
        $tpl->assign('action', $action);
        $tpl->assign('teamname', $teamname);
        $tpl->assign('numgroups', $numteams);
        $tpl->assign('groups',$row_teaminfo);
        
        $filetouse = "admin_group.tpl";
    }
    else
    {
        $allowed = array('groupusers'=>true);
        
        if (array_key_exists($subpage, $allowed))
        {
            include("admin/admin_$subpage.php");
        }
        else
        {
            include("admin/admin_main.php");
        }
    }
}
?>