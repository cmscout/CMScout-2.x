<?php
/**************************************************************************
    FILENAME        :   admin_user_list.php
    PURPOSE OF FILE :   Displays list of users with custom fields
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
	$module['User and Profile Management']['User List'] = "user_list";
    $moduledetails[$modulenumbers]['name'] = "User List";
    $moduledetails[$modulenumbers]['details'] = "Shows list of users with custom fields";
    $moduledetails[$modulenumbers]['access'] = "Allowed to access the user list";
    $moduledetails[$modulenumbers]['add'] = "notused";
    $moduledetails[$modulenumbers]['edit'] = "notused";
    $moduledetails[$modulenumbers]['delete'] = "notused";
    $moduledetails[$modulenumbers]['publish'] = "notused";
    $moduledetails[$modulenumbers]['limit'] = "Limit to only users that belong to the same groups as the user.";
    $moduledetails[$modulenumbers]['id'] = "user_list";
	return;
}
else
{
        $editFormAction = $_SERVER['PHP_SELF'];
        if (isset($_SERVER['QUERY_STRING'])) 
        {
          $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
        }
        
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

   		$sql = $data->select_query("profilefields", "ORDER BY pos ASC");
        
   		$fields = array();
	    $numfields = $data->num_rows($sql);
	    while ($fieldtemp =  $data->fetch_array($sql))
	    {
	        $fieldtemp['options'] = unserialize($fieldtemp['options']);
	        $fields[] = $fieldtemp;
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
            
			$temp['custom'] = unserialize($temp['custom']);
			
            $row[] = $temp;
        }

        $filetouse = "admin_user_list.tpl";
        
        $tpl->assign("action", $action);
        $tpl->assign('numusers', $numusers);
        $tpl->assign('editFormAction', $editFormAction);
        $tpl->assign('row', $row);
        $tpl->assign('fields', $fields);
        $tpl->assign('record', $record);
        $tpl->assign("uname", $check['uname']);
        $tpl->assign("ownerallowed", pageauth("owners", "edit"));
}
?>