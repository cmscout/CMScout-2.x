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
	$module['Configuration']['Website Configuration'] = "config";
    $moduledetails[$modulenumbers]['name'] = "Website Configuration";
    $moduledetails[$modulenumbers]['details'] = "Manages website configuration";
    $moduledetails[$modulenumbers]['access'] = "Allowed to access the configuration of the website";
    $moduledetails[$modulenumbers]['add'] = "notused";
    $moduledetails[$modulenumbers]['edit'] = "Allowed to change configuration options";
    $moduledetails[$modulenumbers]['delete'] = "notused";
    $moduledetails[$modulenumbers]['publish'] = "notused";
    $moduledetails[$modulenumbers]['limit'] = "notused";
    $moduledetails[$modulenumbers]['id'] = "config";

	return;
}
else
{

    $allowed_array = array( 'troopname' => true,
                            'disablesite' => true,
                            'patrolpage' => true,
                            'defaulttheme' => true,
                            'cookiename' => true,
                            'disablereason' => true,
                            'adcode' => true,
                            'session_length' => true,
                            'troop_description' => true,
                            'sitemail' => true,
                            'numsidebox' => true,
                            'confirmarticle' => true,
                            'confirmalbum' => true,
                            'confirmphoto' => true,
                            'confirmevent' => true,
                            'confirmdownload' => true,
                            'confirmnews' => true,
                            'confirmcomment' => true,
                            'avyx' => true,
                            'avyy' => true,
                            'sigsize' => true,
                            'activetime' => true,
                            'register' => true,
                            'softdebug' => true,
                            'zone' => true,
                            'numpage' => true,
                            'photoy' => true,
                            'photox' => true,
                            'allowtemplate' => true,
                            'registerimage' => true,
                            'gzip' => true,
                            'numpm' => true,
                            'confirmpoll' => true,
                            'defaultaccess' => true,
                            'siteaddress' => true,
                            'accountactivation' => true,
                            'uploadlimit' => true,
                            'newssort' => true,
                            'dubemail' => true,
                            'albumdisplay' => true,
                            'articledisplay' => true,
                            'defaultgroup' => true,
                            'emailPrefix' => true,
                            "notify" => true,
                            "smtp" => true,
                            "smtp_host" => true,
                            "smtp_port" => true,
                            "smtp_username" => true,
                            "smtp_password" => true,
                            "privacy" => true,
                            "welcomemessage"=>true,
                            "copyright"=>true,
                            "disclaimer"=>true,
                            "allowemails"=>true,
                            "defaultview" => true,
                            "startday" => true,
                            "pagephoto" => true,
    						"defaultZone" => true);
                            
    $submit = $_POST['Submit'];
    $new = array();
    if ($submit == "Update Config") 
    {
        $result = $data->select_query("config");
        $iserror = false;
        while ( $row = $data->fetch_array($result) )
        {
            $config_name = $row['name'];
            $config_value = $row['value'];
            $default_config[$config_name] = $config_value;
            
            $new[$config_name] = $default_config[$config_name];
            $errorconfig = '';
            if ($allowed_array[$config_name] && isset($_POST[$config_name]) )
            {
                if ($config_name == "siteaddress" && $config['siteaddress'] != $newvalue)
                {
                    $new = urlencode($_POST[$config_name]);
                    $old = urlencode($config['siteaddress']);
                    @file("http://www.cmscout.co.za/newaddress.php?address=$old&new=$new");
                }
                $newvalue = safesql($_POST[$config_name], "text", false);
                echo $newvalue. '<br>';
                $sql = $data->update_query("config","value = $newvalue", "name = '$config_name'", "", "", false);
            }
        }
        $config_name = 'exclusion';
        $_POST[$config_name] = serialize(is_array($_POST[$config_name]) ? $_POST[$config_name] : '');
        $newvalue = safesql($_POST[$config_name], "text", false);
        $sql = $data->update_query("config","value = $newvalue", "name = '$config_name'", "", "", false);
        show_admin_message("Configuration Updated", "admin.php?page=config");
    }
    
    $theme_q = $data->select_query("themes", "ORDER BY name ASC");
    $theme = array();
    $numthemes = $data->num_rows($theme_q);
    while ($theme[] =  $data->fetch_array($theme_q));
    
    $sql = $data->select_query("timezones", "ORDER BY offset ASC");
    $zone = array();
    $numzones = $data->num_rows($sql);
    while ($zone[] =  $data->fetch_array($sql));

    $sql = $data->select_query("groups", "ORDER BY teamname ASC", "id, teamname");
    $group = array();
    $numgroups = $data->num_rows($sql);
    while ($group[] =  $data->fetch_array($sql));

    $config = read_config();
    $tpl->assign('configs', $config);
    $tpl->assign('theme', $theme);
    $tpl->assign('numthemes', $numthemes);
    $tpl->assign('zone', $zone);
    $tpl->assign('numzones', $numzones);
    $tpl->assign('group', $group);
    $tpl->assign('numgroups', $numgroups);
    $tpl->assign("editor", true);
    $tpl->assign("editormode", true);
    $filetouse = 'admin_config.tpl';
}
?>
