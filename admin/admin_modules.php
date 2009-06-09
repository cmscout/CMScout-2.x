<?php
/**************************************************************************
    FILENAME        :   admin_content.php
    PURPOSE OF FILE :   Static Content Manager
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
	$module['Configuration']['Module Manager'] = "modules";
    $moduledetails[$modulenumbers]['name'] = "Module Manager";
    $moduledetails[$modulenumbers]['details'] = "Manage CMScout modules";
    $moduledetails[$modulenumbers]['access'] = "Allowed to access the module manager";
    $moduledetails[$modulenumbers]['add'] = "notused";
    $moduledetails[$modulenumbers]['edit'] = "notused";
    $moduledetails[$modulenumbers]['delete'] = "notused";
    $moduledetails[$modulenumbers]['publish'] = "Allowed to deactivate and reactivate modules";
    $moduledetails[$modulenumbers]['limit'] = "notused";
    $moduledetails[$modulenumbers]['id'] = "modules";

	return;
}
else
{
    $id = safesql($_GET['id'], "int");
    if ($_GET['action'] == 'activate' && pageauth("modules", "publish")) 
    {
        $sqlq = $data->update_query("functions", "active = 1", "id=$id");
        header("Location: $pagename");
    }
    elseif ($_GET['action'] == 'deactivate' && pageauth("modules", "publish")) 
    {
        $sqlq = $data->update_query("functions", "active = 0", "id=$id");
        header("Location: $pagename");
    }
    
    $modules = $data->select_fetch_all_rows($nummodule, "functions", "WHERE type != 3 ORDER BY name ASC", "id, name, type, active");

    $tpl->assign("modules", $modules);
    $tpl->assign("nummodule", $nummodule);

    $filetouse = "admin_modules.tpl";
}
?>