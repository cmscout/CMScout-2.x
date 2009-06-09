<?php
/**************************************************************************
    FILENAME        :   admin_user_edit.php
    PURPOSE OF FILE :   Edits users profiles
    LAST UPDATED    :   09 May 2006
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
    $id = (isset($_GET['id'])) ? $_GET['id'] : admin_error_message("Something is wrong. Try again");
    $safe_id = safesql($id, "int");
    $action = $_GET['action'];	
    
    $message = "";
    /********************************************Build page*****************************************/
    $currentPage = $_SERVER["PHP_SELF"];
    
    $editFormAction = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING'])) 
    {
      $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
    }
    
    if ($action == "Edit" && $_POST['Submit'] == 'Edit') 
    {
        $user_query = $data->select_query("users", "WHERE id=$safe_id");
        $user = $data->fetch_array($user_query);
        
        $exit = false;
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $username = $_POST['usernames'];
        $password = $_POST['passwords'];
        $repass = $_POST['repass'];
        $team = $_POST['team'];
        $status = $_POST['status'];
        $oldname = $_POST['oldname'];
        
        $action = $_POST['Submit'];
        
        if ($config['dubemail'] == 0)
        {
            $email = safesql($_POST['email'], "text");
            $datas = $data->select_query("users", "WHERE email=$email AND id != $safe_id");
            $numrows = $data->num_rows($datas);
            if ($numrows > 0) 
            {
                show_admin_message("That email address has already been used, please use another email address.", "admin.php?page=$page&subpage=user_edit&action=Edit&id=$id", true);
            } 
        }
                
        $sql = $data->select_query("profilefields", "WHERE place=0 ORDER BY pos ASC");
        $numfields = $data->num_rows($sql);
        $custom = array();
        while ($temp =  $data->fetch_array($sql))
        {
            $temp['options'] = unserialize($temp['options']);
            if ($temp['type'] == 4)
            {
                $temp2 = array();
                $temp2[] = 0;
                for($i=1;$i<=$temp['options'][0];$i++)
                {
                    $temp2[] = $_POST[$temp['name'] . $i] ? 1 : 0;
                }
                $custom[$temp['name']] = $temp2;
            }
            else
            {
                $custom[$temp['name']] = $_POST[$temp['name']];
            }
        }   
        $custom = serialize($custom);
        if ((pageauth("users", "limit") == 0))
        {
            if ($status != $user['status'])
            {
                if ($status == 1)
                {
                    email_user($id, "account_actived");
                }
                else
                {
                    email_user($id, "account_deactived");
                }
            }
                        
            $insertSQL = sprintf("uname=%s, status=%s, timezone=%s, firstname=%s, lastname=%s, email=%s, custom=%s",
               safesql($username, "text"),
               safesql($status, "text"),
               safesql($_POST['zone'], "text"),
               safesql($firstname, "text"),
               safesql($lastname, "text"), 
               safesql($email, "text"), 
               safesql($custom, "text"));
            
            if ($password)
            {
                $insertSQL .= ", passwd=" . safesql(md5($password), "text");
            }
            
            $Result1 = $data->update_query("users", $insertSQL, "id=$id");
            if ($Result1) 
            {
                show_admin_message("User details updated", "admin.php?page=users");
            }
        }
        else
        {
                $insertSQL = sprintf("firstname=%s, lastname=%s, email=%s, custom=%",
                   safesql($firstname, "text"),
                   safesql($lastname, "text"), 
                   safesql($email, "text"), 
                   safesql($custom, "text"));

                $Result1 = $data->update_query("users", $insertSQL, "id=$id");
                if ($Result1) 
                {
                    show_admin_message("User details updated", "admin.php?page=users");
                }
        }
    } 
    
    if ($action == "Edit") 
    {
        $user_query = $data->select_query("users", "WHERE id=$safe_id");
        $users = $data->fetch_array($user_query);

        $action = 'Edit'; 
    } 
    
    
    $sql = $data->select_query("timezones", "ORDER BY offset ASC");
    $zone = array();
    $numzones = $data->num_rows($sql);
    while ($zone[] =  $data->fetch_array($sql));
    
    
    $sql = $data->select_query("profilefields", "WHERE place=0 ORDER BY pos ASC");
    $fields = array();
    $numfields = $data->num_rows($sql);
    while ($temp =  $data->fetch_array($sql))
    {
        $temp['options'] = unserialize($temp['options']);
        $fields[] = $temp;
    }

    $tpl->assign('fields', $fields);
    $tpl->assign('numfields', $numfields);
    $tpl->assign('zone', $zone);
    $tpl->assign('numzones', $numzones);
    $tpl->assign('uinfo', $users);
    $tpl->assign('details', $record);
    $tpl->assign('editFormAction', $editFormAction);
    $tpl->assign('action', $action);

    $filetouse = "admin_$page.tpl";
}
?>