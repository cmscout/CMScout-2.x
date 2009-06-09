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
	$module['Member Management']['Section Manager'] = "sections";
    $moduledetails[$modulenumbers]['name'] = "Section Manager";
    $moduledetails[$modulenumbers]['details'] = "Manages different sections, like Cubs, Scouts, etc.";
    $moduledetails[$modulenumbers]['access'] = "Allowed to view sections";
    $moduledetails[$modulenumbers]['add'] = "Allowed to add a new section";
    $moduledetails[$modulenumbers]['edit'] = "Allowed to edit an existing section )";
    $moduledetails[$modulenumbers]['delete'] = "Allowed to delete an existing section";
    $moduledetails[$modulenumbers]['publish'] = "notused";
    $moduledetails[$modulenumbers]['limit'] = "notused";
    $moduledetails[$modulenumbers]['id'] = "sections";

	return;
}
else
{		
    $id = safesql($_GET['id'], "int");
    $Submit = $_POST['Submit'];
    $action = $_GET['action'];
    
    if ($Submit == 'Submit') 
    {
        if ($action == "new" && pageauth("sections", "add"))
        {
            $name = safesql($_POST['name'], "text");	
            $sql = $data->insert_query("sections", "NULL, $name");
            if ($sql)
            {
                show_admin_message("Section added", $pagename);
            }
        }
        elseif ($action == "edit" && pageauth("sections", "edit"))
        {
            $name = safesql($_POST['name'], "text");
            $sql = $data->update_query("sections", "name = $name", "id = $id");		
            if ($sql)
            {
                show_admin_message("Section updated", $pagename);
            }
        }
    }
    
    if($action == "edit" && pageauth("sections", "edit"))
    {
        $result = $data->select_query("sections", "WHERE id = '$id'");
        $section = $data->fetch_array($result);
        
        $tpl->assign("section", $section);
    }
    elseif ($action == "delete" && pageauth("sections", "delete")) 
    {
        $sql = $data->delete_query("sections", "id = '$id'");	
        if ($sql)
        {
            show_admin_message("Section deleted", $pagename);
        }
    }
    else
    {
        $result = $data->select_query("sections", "ORDER BY name ASC");
        $sections  = array();
        $numsections = $data->num_rows($result);
        while ($sections[] = $data->fetch_array($result));
        $tpl->assign('sections', $sections);
        $tpl->assign('numsections', $numsections);
    }
    

    $tpl->assign('action',$action);
    
    $filetouse = "admin_sections.tpl";
}
?>