<?php
/**************************************************************************
    FILENAME        :   admin_users.php
    PURPOSE OF FILE :   Displays users
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
	$module['User and Profile Management']['User Manager'] = "users";
    $moduledetails[$modulenumbers]['name'] = "User Manager";
    $moduledetails[$modulenumbers]['details'] = "Management of users";
    $moduledetails[$modulenumbers]['access'] = "Allowed to access the user manager";
    $moduledetails[$modulenumbers]['add'] = "Allowed to add a a new user";
    $moduledetails[$modulenumbers]['edit'] = "Allowed to edit users";
    $moduledetails[$modulenumbers]['delete'] = "Allowed to delete users";
    $moduledetails[$modulenumbers]['publish'] = "notused";
    $moduledetails[$modulenumbers]['limit'] = "Limit to only users that belong to the same groups as the user. Also limits types of edits that can be performed (If editing is allowed)";
    $moduledetails[$modulenumbers]['id'] = "users";
	return;
}
else
{
    $subpage = $_GET['subpage'] != '' ? $_GET['subpage'] : '';
    
    if($_GET['action'] == "logout")
    {
        $id = safesql($_GET['id'], "int");
        $info = $data->select_fetch_one_row("users", "WHERE id=$id");
        $data->delete_query("onlineusers", "uname='{$info['uname']}'");
		$data->update_query("users", "uid = ''", "id=$id");
        header('location:admin.php');
    }
    if (!$subpage)
    {
        $editFormAction = $_SERVER['PHP_SELF'];
        if (isset($_SERVER['QUERY_STRING'])) 
        {
          $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
        }
        
        if(isset($_GET['action'])) $action=$_GET['action']; else $action = "";
        
        if ($_GET['groupid'])
        {
            $groupid = safesql($_GET['groupid'], "text");
            
            $sql = $data->select_query("groups", "WHERE id = $groupid", "teamname");
            $stuff = $data->fetch_array($sql);
            
            $cond = safesql($stuff['teamname'], "text");
            
            $sort = "uname";
            
            $order = "ASC";
            
            if ($field != 'none') 
            {
                $where = " team = $cond ";
            }
        }
        if ($action == "delete") 
        {
            $id = safesql($_GET['id'], "int");
            $temp= $data->select_fetch_one_row("users", "WHERE id = $id");
            $username = $temp['uname'];
            $sql2 = $data->delete_query("users", "id='$id'");
            $data->delete_query("usergroups", "userid='$id'", "", "", false);
            show_admin_message("$username deleted", "$pagename");
            $action = "";
        }
        
        $row = array();
        if (pageauth("users", "limit") == 1) 
        {
             $usergroups = user_groups_id_array($check['id']);
             
             $userquery = '';
             $first2 = true;
             for($i=0;$i<count($usergroups);$i++)
             {
                if ($first2 == false)
                {
                    $userquery .= " OR ";
                }
                else
                {
                    $first2 = false;
                }
                $group_ids = group_users_id_array($usergroups[$i]);
                $first = true;
                 for($j=0;$j<count($group_ids);$j++)
                 {
                    if ($first == false)
                    {
                        $userquery .= " OR ";
                    }
                    else
                    {
                        $first = false;
                    }
                    $userquery .= "id=".$group_ids[$j];
                 }
             }

            $sql = $data->select_query("users", "WHERE $userquery  ORDER BY uname ASC");
        } 
        else 
        {
            $sql = $data->select_query("users", "ORDER BY uname ASC");
        }
        
        $numusers = $data->num_rows($sql);
        while ($temp = $data->fetch_array($sql))
        {
            $temp['team'] = user_groups_list($temp['id']);

            $detail = "&lt;b&gt;Username:&lt;/b&gt; {$temp['uname']}&lt;br /&gt;";
            $detail .= "&lt;b&gt;Real Name:&lt;/b&gt; {$temp['firstname']} {$temp['lastname']}&lt;br /&gt;";
            $detail .= "&lt;b&gt;Email Address:&lt;/b&gt; {$temp['email']}&lt;br /&gt;";
              
            $detail .= "&lt;b&gt;Groups:&lt;/b&gt; ". strip_tags($temp['team']) ."&lt;br /&gt;";
            if ($temp['status'] == 1) $detail .= "&lt;b&gt;Status:&lt;/b&gt; Active&lt;br /&gt;";
            else $detail .= "&lt;b&gt;Status:&lt;/b&gt; Inactive&lt;br /&gt;";
            $temp['detail'] = $detail;
            $row[] = $temp;
        }
        $filetouse = "admin_users.tpl";
        
        $tpl->assign("action", $action);
        $tpl->assign('numusers', $numusers);
        $tpl->assign('editFormAction', $editFormAction);
        $tpl->assign('row', $row);
        $tpl->assign('record', $record);
        $tpl->assign("uname", $check['uname']);
        $tpl->assign("ownerallowed", pageauth("owners", "edit"));
    }
    else
    {
        $allowed = array('user_edit'=>true,
                         'users_view'=>true,
                         'add_user'=>true,
                         'records'=>true,
                         'usergroups'=>true,
                         'registerinfo'=>true);
        
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