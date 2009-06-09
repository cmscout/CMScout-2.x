<?php
/**************************************************************************
    FILENAME        :   admin_subsite.php
    PURPOSE OF FILE :   Displays subsites and gives access to subsite content manager and menu manager
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
	$module['Module Management']['Sub Site Manager'] = "subsite";
    $moduledetails[$modulenumbers]['name'] = "Sub Site Manager";
    $moduledetails[$modulenumbers]['details'] = "Manages sub sites";
    $moduledetails[$modulenumbers]['access'] = "Allowed to access Sub Site manager";
    $moduledetails[$modulenumbers]['add'] = "Allowed to add new pages and menu items";
    $moduledetails[$modulenumbers]['edit'] = "Allowed to edit existing pages and menu items";
    $moduledetails[$modulenumbers]['delete'] = "Allowed to delete pages and menu items";
    $moduledetails[$modulenumbers]['publish'] = "notused";
    $moduledetails[$modulenumbers]['limit'] = "notused";
    $moduledetails[$modulenumbers]['id'] = "subsite";   
	return;
}
else
{
    $subpage = $_GET['subpage'] != '' ? $_GET['subpage'] : '';
    
    if (!$subpage)
    {   
    
        $action = $_GET['action'];
        
        $sql = $data->select_query("subsites");
        
        if ($action == "") 
        {
            $numsites = $data->num_rows($sql);
            $sites = array();
            $sites[] = $data->fetch_array($sql);
            while  ($sites[] = $data->fetch_array($sql));
        }
        elseif ($action == "edit") 
        {
            $id = $_GET['id'];
            $sql = $data->select_query("subsites", "WHERE id = $id");
            $stuff = $data->fetch_array($sql);
            $tpl->assign('site', $stuff);
            $submit = $_POST['Submit'];
            $oldname = safesql($stuff['name'], "text");
            if ($submit == 'Submit') 
            {
                $teamname = safesql($_POST['name'], "text");
                
                $sql3 = $data->update_query("subsites", "name=$teamname", "id = $id");
                if ($sql3)
                {
                    show_admin_message("Sub site updated", "$pagename");
                }
            }
        
        } 
        elseif ($action == "Add") 
        {
            $submit = $_POST['Submit'];
            if ($submit == 'Submit') 
            {
                $teamname = safesql($_POST['name'], "text");

                $sql3 = $data->insert_query("subsites", "'', $teamname");
                if ($sql3)
                {
                    show_admin_message("Sub site added", "$pagename");
                }
            }
        } 
        elseif ($action == "delete") 
        {
            $id = $_GET['id'];
            
            $sql3 = $data->delete_query("subsites", "id=$id");
            $data->delete_query("static_content", "type=2 AND pid=$id");
            $data->delete_query("submenu", "site=$oldname");
            if ($sql3)
            {
                show_admin_message("Sub site deleted", "$pagename");
            }
        }
        
        
        $editFormAction = $_SERVER['PHP_SELF'];
        if (isset($_SERVER['QUERY_STRING'])) 
        {
          $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
        }
        
        $tpl->assign('editFormAction', $editFormAction);
        
        $tpl->assign('sites', $sites);
        $tpl->assign('action', $action);
        $tpl->assign('numsites', $numsites);
        $filetouse = "admin_subsite.tpl";
    }
    else
    {
        $allowed = array('subcontent'=>true, 'submenu'=>true);
        
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