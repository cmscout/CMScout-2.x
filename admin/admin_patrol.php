<?php
/**************************************************************************
    FILENAME        :   admin_patrol.php
    PURPOSE OF FILE :   Displays patrols and gives access to patrol content manager and menu manager
    LAST UPDATED    :   27 May 2006
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
	$module['Module Management']['Group Site Manager'] = "patrol";
    $moduledetails[$modulenumbers]['name'] = "Group Site Manager";
    $moduledetails[$modulenumbers]['details'] = "Manages group websites";
    $moduledetails[$modulenumbers]['access'] = "Allowed to access group site manager";
    $moduledetails[$modulenumbers]['add'] = "Allowed to add new pages and menu items";
    $moduledetails[$modulenumbers]['edit'] = "Allowed to edit existing pages and menu items";
    $moduledetails[$modulenumbers]['delete'] = "Allowed to delete pages and menu items";
    $moduledetails[$modulenumbers]['publish'] = "notused";
    $moduledetails[$modulenumbers]['limit'] = "Limit to groups the user is part of";
    $moduledetails[$modulenumbers]['id'] = "patrol";
	return;
}
else
{
    $subpage = $_GET['subpage'] != '' ? $_GET['subpage'] : '';
    
    if (!$subpage)
    {   
        $action = $_GET['action'];
        
        if (pageauth("patrol", "limit")) 
        {
            $patrollist = group_sql_list_normal("teamname", "OR");
            $result = $data->select_query("groups", "WHERE ($patrollist) AND ispublic=1 ORDER BY teamname ASC");
        } 
        else 
        {
            $result = $data->select_query("groups" , "WHERE ispublic=1 ORDER BY teamname ASC");
        }

        $patrol = array();
        while ($patrol[] = $data->fetch_array($result));
        $numpatrols = $data->num_rows($result);
        
        $tpl->assign('patrol', $patrol);
        $tpl->assign('patrolInfo', $patrolInfo);
        $tpl->assign('action', $action);
        $tpl->assign('numpatrol', $numpatrols);
        $filetouse = "admin_patrol.tpl";
    }
    else
    {
        $allowed = array('patrolcontent'=>true, 'patrolmenus'=>true);
        
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