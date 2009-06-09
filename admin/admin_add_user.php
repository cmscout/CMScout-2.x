<?php
/**************************************************************************
    FILENAME        :   admin_add_user.php
    PURPOSE OF FILE :   Allows the admin to add a user to the site
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
    if (pageauth("users", "add"))
    {   
        if ($_POST['Submit'] == "Submit")
        {           
            $firstname = safesql($_POST['firstname'], "text");
            $lastname = safesql($_POST['lastname'], "text");
            $email = safesql($_POST['email'], "text");
            $username = safesql($_POST['usernames'], "text");
            $password = safesql(md5($_POST['passwords']), "text");
            $status = safesql($_POST['status'], "int");
            $zone = safesql($_POST['zone'], "int");
            
            if ($config['dubemail'] == 0)
            {
                $email = safesql($_POST['email'], "text");
                $datas = $data->select_query("users", "WHERE email=$email");
                $numrows = $data->num_rows($datas);
                if ($numrows > 0) 
                {
                    show_admin_message("That email address has already been used, please use another email address.", "admin.php?page=users&subpage=add_user", true);
                } 
            }
                    
            if ($data->num_rows($data->select_query("users", "WHERE uname=$username")))
            {
                show_admin_message("There is already a user by that name, please change the user name", "admin.php?page=users&subpage=add_user", true);
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
            $custom = safesql(serialize($custom), "text");
                        
            $insertSQL = "'', '', $username, $password, $status, $timestamp, 0, 0, 0, 0, $zone, 0, $firstname, $lastname, $email, '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, $custom";

            if ($data->insert_query("users", $insertSQL)) 
            {
                $uinfo = $data->select_fetch_one_row("users", "WHERE uname=$username");
                
                $data->insert_query("usergroups", "{$config['defaultgroup']}, {$uinfo['id']}, 0");
                
                if ($_POST['member'] == 1)
                {
                    $type = safesql($_POST['type'], "int");
                    $sex = safesql($_POST['sex'], "int");
                    
                    $address = safesql('None', "text");
                    $tel = safesql('None', "text");
                    $cell = safesql('None', "text");
                    $data->insert_query("members", "'', $firstname, NULL, $lastname, '0', $sex, $address, $cell, $tel, NULL, $email, NULL, NULL, NULL, NULL, NULL, 0, 0, $type, {$uinfo['id']}, 0, 0, 0, 0, NULL");
                }
                
                show_admin_message("User added", "admin.php?page=users");
            }
        }
        
        $zone = $data->select_fetch_all_rows($numzones, "timezones", "ORDER BY offset ASC");
        
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
        
        $filetouse = "admin_add_user.tpl";
    }
}
?>