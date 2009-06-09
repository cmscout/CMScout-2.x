<?php
/**************************************************************************
    FILENAME        :   admin_auth.php
    PURPOSE OF FILE :   Manage access authorizations
    LAST UPDATED    :   26 May 2006
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
	$module['Configuration']['Authorization Manager'] = "auth";
    $moduledetails[$modulenumbers]['name'] = "Authorization Manager";
    $moduledetails[$modulenumbers]['details'] = "Manages authorization of pages";
    $moduledetails[$modulenumbers]['access'] = "Allowed to access the authorization manager";
    $moduledetails[$modulenumbers]['add'] = "Allowed to add a authorization item for a user or group";
    $moduledetails[$modulenumbers]['edit'] = "Allowed to edit authorization items";
    $moduledetails[$modulenumbers]['delete'] = "Allowed to delete authorization items";
    $moduledetails[$modulenumbers]['publish'] = "notused";
    $moduledetails[$modulenumbers]['limit'] = "notused";
    $moduledetails[$modulenumbers]['id'] = "auth";

	return;
}
else
{     
    
    $editFormAction = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING']))
    {
      $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
    }
    
    $id = $_GET['id'];
    $action = $_GET['action'];
    $submit = $_POST['Submit'];
    
    if ($action == "delete" && pageauth("auth", "delete") == 1) 
    {
        $sql = $data->delete_query("auth", "id = $id", "Authorization", "Deleted auth setting");
        $action = "";
        if ($sql)
        {
            show_admin_message("Authorization item removed", $pagename);
        }
    }
    
    if ($submit == "Submit") 
    {
        if (($action == "new") && pageauth("auth", "add") == 1) 
        {
            $dynamic = $_POST['dynamic'];
            $permissions = $_POST['permissions'];
            $static = $_POST['static'];
            $subsites = $_POST['subsites'];

            $dynamic = safesql(serialize($dynamic), "text");
            $permissions = safesql(serialize($permissions), "text");
            $static = safesql(serialize($static), "text");
            $subsites = safesql(serialize($subsites), "text");
            $name = explode(".", $_POST['name']);
            if ($name[1] == "user")
            {
                $type = 1;
            }
            else
            {
                $type = 2;
            }
            $name = safesql($name[0], "text");
            $sql = $data->insert_query("auth", "'', $name, $dynamic, $permissions, $static, $subsites, $type");
            if ($sql)
            {
                show_admin_message("Authorization item added", $pagename);
            }
        } 
        elseif (($action == "edit") && pageauth("auth", "edit") == 1) 
        {
            $safe_id = safesql($_GET['id'], "int");
            
            $dynamic = $_POST['dynamic'];
            $permissions = $_POST['permissions'];
            $static = $_POST['static'];
            $subsites = $_POST['subsites'];
      
            $dynamic = safesql(serialize($dynamic), "text");
            $permissions = safesql(serialize($permissions), "text");
            $static = safesql(serialize($static), "text");
            $subsites = safesql(serialize($subsites), "text");
  
            $name = explode(".", $_POST['name']);
            if ($name[1] == "user")
            {
                $type = 1;
            }
            else
            {
                $type = 2;
            }
            $name = safesql($name[0], "text");

            $sql = $data->update_query("auth", "authname = $name, dynamic = $dynamic, permission = $permissions, static = $static, subsites = $subsites, type=$type", "id=$safe_id");
            if ($sql)
            {
                show_admin_message("Authorization item updated", $pagename);
            }
        }
    }
    
    if ((($action == "new") && pageauth("auth", "add") == 1) || (($action == "edit") && pageauth("auth", "edit") == 1)) 
    {
        $safe_id = safesql($id, "int");
        
        $sql = $data->select_query("functions", "WHERE type=2 ORDER BY name ASC", "id, name, code");
        $numdynamic = $data->num_rows($sql);
        $dynamic = array();
        while ($dynamic[] = $data->fetch_array($sql));

        $sql = $data->select_query("functions", "WHERE type=3 ORDER BY name ASC", "id, name, code");
        $numperms = $data->num_rows($sql);
        $permissions = array();
        while ($permissions[] = $data->fetch_array($sql));

        $sql = $data->select_query("static_content", "WHERE type=0 ORDER BY friendly ASC", "id, name, friendly");
        $numstatic = $data->num_rows($sql);
        $static = array();
        while ($static[] = $data->fetch_array($sql));

        $sql = $data->select_query("subsites", "ORDER BY name ASC", "id, name");
        $numsites = $data->num_rows($sql);
        $subsites = array();
        while ($subsites[] = $data->fetch_array($sql));

        $sql = $data->select_query("users");
        $users = array();
        while ($temp = $data->fetch_array($sql))
        {
            $sql2 = $data->select_query("auth", "WHERE authname='{$temp['id']}' AND type=1 AND id != $safe_id");
            if ($data->num_rows($sql2) == 0)
            {
                $users[] = $temp;
            }
        }
        $temp['id'] = -1;
        $temp['uname'] = "Guest";
        $sql2 = $data->select_query("auth", "WHERE authname='{$temp['id']}' AND type=1 AND id != $safe_id");
        if ($data->num_rows($sql2) == 0)
        {
            $users[] = $temp;
        }
        $numusers = count($users);

        $sql = $data->select_query("groups", "ORDER BY teamname ASC", "id, teamname");
        $groups = array();
        while ($temp = $data->fetch_array($sql))
        {
            
            $sql2 = $data->select_query("auth", "WHERE authname='{$temp['id']}' AND type=2 AND id != $safe_id");
            if ($data->num_rows($sql2) == 0)
            {
                $groups[] = $temp;
            }
        }
        $numgroups = count($groups);

        $tpl->assign('dynamic', $dynamic);
        $tpl->assign('numdynamic', $numdynamic);
        $tpl->assign('permissions', $permissions);
        $tpl->assign('numperms', $numperms);
        $tpl->assign('static', $static);
        $tpl->assign('numstatic', $numstatic);
        $tpl->assign('subsites', $subsites);
        $tpl->assign('numsites', $numsites);
        $tpl->assign('groups', $groups);
        $tpl->assign('numgroups', $numgroups);
        $tpl->assign('users', $users);
        $tpl->assign('numusers', $numusers);
        if ($action == "edit") 
        {
            $item = $data->select_fetch_one_row("auth", "WHERE id='$id'");
            $item['dynamic'] = unserialize($item['dynamic']);
            $item['permission'] = unserialize($item['permission']);
            $item['static'] = unserialize($item['static']);
            $item['subsites'] = unserialize($item['subsites']);
            $tpl->assign('item', $item);
        }
    }
    else
    {
        $sql = $data->select_query("auth");
        $numauth = $data->num_rows($sql);
        $auths = array();
        while ($temp = $data->fetch_array($sql))
        {
            if ($temp['type'] == 1)
            {
                if ($temp['authname'] != -1)
                {
                    $sql2 = $data->select_query("users", "WHERE id='{$temp['authname']}'");
                    $temp2 = $data->fetch_array($sql2);
                    $temp['authname'] = $temp2['uname'];
                }
                else
                {
                    $temp['authname'] = "Guest";
                }
            }
            elseif ($temp['type'] == 2)
            {
                $sql2 = $data->select_query("groups", "WHERE id='{$temp['authname']}'");
                $temp2 = $data->fetch_array($sql2);
                $temp['authname'] = $temp2['teamname'];
            }

            $auths[] = $temp;
        }
        $tpl->assign('auths', $auths);
        $tpl->assign('numauths', count($auths));
    }
    
    $tpl->assign('id', $id);
    $tpl->assign('action', $action);
    $tpl->assign('editFormAction', $editFormAction);
    $filetouse = "admin_auth.tpl";
}
?>