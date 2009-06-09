<?php
/**************************************************************************
    FILENAME        :   admin_config.php
    PURPOSE OF FILE :   Manage configuration of site
    LAST UPDATED    :   25 September 2006
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
	$module['Member Management']['Scouting Language'] = "language";
    $moduledetails[$modulenumbers]['name'] = "Scouting Language";
    $moduledetails[$modulenumbers]['details'] = "Manages scouting language of CMScout";
    $moduledetails[$modulenumbers]['access'] = "Allowed to access the language page";
    $moduledetails[$modulenumbers]['add'] = "notused";
    $moduledetails[$modulenumbers]['edit'] = "Allowed to change language";
    $moduledetails[$modulenumbers]['delete'] = "notused";
    $moduledetails[$modulenumbers]['publish'] = "notused";
    $moduledetails[$modulenumbers]['limit'] = "notused";
    $moduledetails[$modulenumbers]['id'] = "language";

	return;
}
else
{

    $allowed_array = array( 'patrol' => true,
                            'troop' => true,
                            'advancement_badges' => true,
                            'award_scheme' => true,
                            'member' => true,
                            'members' => true,
                            'badges' => true);
                            
    $submit = $_POST['Submit'];
    $new = array();
    if ($submit == "Update") 
    {
        $result = $data->select_query("scoutlanguage");
        $iserror = false;
        while ( $row = $data->fetch_array($result) )
        {
            $config_name = $row['name'];
            
            $newvalue = safesql($_POST[$config_name], "text", false);
            $sql = $data->update_query("scoutlanguage","value = $newvalue", "name = '$config_name'");
        }
        show_admin_message("Scout Language Updated", "admin.php?page=language");
    }
    
    $filetouse = 'admin_language.tpl';
}
?>