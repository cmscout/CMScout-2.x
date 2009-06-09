<?php
/**************************************************************************
    FILENAME        :   admin_users_view.php
    PURPOSE OF FILE :   Displays a users details
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
else
{
    
    $id = $_GET['id'];
    
    $user_query = $data->select_query("users", "WHERE id=$id");
    $users = $data->fetch_array($user_query);
    
    $users['team'] = user_groups_list($users['id']);
    $users['custom'] = unserialize($users['custom']);
  
    $sql = $data->select_query("profilefields", "ORDER BY pos ASC");
    $fields = array();
    $numfields = $data->num_rows($sql);
    while ($temp =  $data->fetch_array($sql))
    {
        $temp['options'] = unserialize($temp['options']);
        $fields[] = $temp;
    }

    $tpl->assign('fields', $fields);
    $tpl->assign('numfields', $numfields);
    
    $action = "view";
    
    $tpl->assign('uinfo', $users);
    $tpl->assign('details', $record);
    $tpl->assign('action', $action);
    $filetouse = "admin_users.tpl";
}
?>