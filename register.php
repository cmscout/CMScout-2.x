<?php
/**************************************************************************
    FILENAME        :   register.php
    PURPOSE OF FILE :   Allows users to register on the site
    LAST UPDATED    :   25 September 2006
    COPYRIGHT       :   © 2005 CMScout Group
    WWW             :   www.cmscout.za.net
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
if (!defined('SCOUT_NUKE'))
    die("You have accessed this page illegally, please go use the main menu");

$errors = '';
$exit = false;
$script = "";
if ($check["id"] == -1) 
{
    if ($_POST['submit'] == 'I Agree' || ($_GET['stage'] == 2 && $_POST['Submit'] != 'Register'))
    {
        $zone = $data->select_fetch_all_rows($numzones, "timezones", "ORDER BY offset ASC");
        
        $sql = $data->select_query("profilefields", "WHERE place=0 AND register=1 ORDER BY pos ASC");
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
        $scriptList['datepicker'] = 1;
        $pagenum = 2;
    }
    elseif ($_POST['Submit'] == 'Register')
    {
        if(validate($_POST['validation']))
        {
            session_start();

            if ($config['registerimage'])
            {
                if(!empty($_SESSION['freecap_word_hash']) && !empty($_POST['captcha']))
                {
                    if($_SESSION['hash_func'](strtolower($_POST['captcha']))==$_SESSION['freecap_word_hash'])
                    {
                        $_SESSION['freecap_attempts'] = 0;
                        $_SESSION['freecap_word_hash'] = false;

                        $word_ok = true;
                    } 
                    else 
                    {
                        $word_ok = false;
                    }
                } 
                else 
                {
                    $word_ok = false;
                }
            }
            else
            {
                $word_ok = true;
            }

            if ($word_ok == true)
            {
                $firstname = safesql($_POST['firstname'], "text");
                $lastname = safesql($_POST['lastname'], "text");
                $address = safesql($_POST['address'], "text");
                $email = safesql($_POST['email'], "text");
                $username = safesql($_POST['usernames'], "text");
                $password = safesql(md5($_POST['password']), "text");
                $zone = safesql($_POST['zone'], "int");
                if ($config['dubemail'] == 0)
                {
                    $datas = $data->select_query("users", "WHERE email=$email", "id");
                    $numrows = $data->num_rows($datas);
                    if ($numrows > 0) 
                    {
                        show_message("That email address has already been used, please use another email address.", "index.php?page=register&stage=2", true);
                    } 
                }
                
                if ($data->num_rows($data->select_query("users", "WHERE uname=$username")))
                {
                    show_message("There is already a user with that username, please select another username", "index.php?page=register&stage=2", true);
                }
                
                $sql = $data->select_query("profilefields", "WHERE place=0 AND register=1 ORDER BY pos ASC");
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
                
                $status = $config['accountactivation'] != 0 ? 0 : 1;
                
                $activecode = md5($username . $password . (microtime() + mktime()));
                $safe_activecode = $config['accountactivation'] != 0  ? safesql($activecode,"text") : 0;
                
                $insertSQL = "'', '', $username, $password, $status, $timestamp, 0, 0, 0, 0, $zone, $safe_activecode, $firstname, $lastname, $email, '', '', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, $custom";
                
                if ($data->insert_query("users", $insertSQL)) 
                {
                    $uinfo = $data->select_fetch_one_row("users", "WHERE uname=$username");
                    
                    $data->insert_query("usergroups", "{$config['defaultgroup']}, {$uinfo['id']}, 0");
                    
                    if ($config['accountactivation'] == 0)
                    {
                        $activateinfo = "You can login right away.";
                    }
                    elseif ($config['accountactivation'] == 1)
                    {
                        $activateinfo = "You need to activate your account before you can use it. To activate it goto {$config['siteaddress']}activate.php?id={$uinfo["id"]}&code={$activecode}.";
                        $link = "{$config['siteaddress']}activate.php?id={$uinfo["id"]}&code={$activecode}";
                    }
                    elseif ($config['accountactivation'] == 2)
                    {
                        $activateinfo = "The site administrator needs to activate your account before you can start using it. You will be emailed once the administrator has activated your account.";
                    }
                    
                    $uname = $_POST['usernames'];
                    $password = $_POST['password'];
                    $emailAddress = $_POST['email'];

                    $email = $data->select_fetch_one_row("emails", "WHERE type='register'");
                    $cmscoutTags = array("!#uname#!", "!#postuname#!", "!#title#!", "!#type#!", "!#link#!", "!#extract#!", "!#website#!", "!#password#!", "!#activateinfo#!");
                    $replacements   = array($uname, '', "registration", "registration", $link, '', $config['troopname'], $password, $activateinfo);

                    $emailContent = str_replace($cmscoutTags, $replacements, $email['email']); 
			
                    sendMail($emailAddress, $uname, $config['emailPrefix'] . $email['subject'], $emailContent);

                    $email = $data->select_fetch_one_row("emails", "WHERE type='adminregister'");
                    if ($config['accountactivation'] == 0)
                    {
                        $activateinfo = "";
                    }
                    elseif ($config['accountactivation'] == 1)
                    {
                        $activateinfo = "";
                    }
                    elseif ($config['accountactivation'] == 2)
                    {
                        $activateinfo = "The user will not be able to access the website until you have activated them.";
                    }
                    $cmscoutTags = array("!#uname#!", "!#postuname#!", "!#title#!", "!#type#!", "!#link#!", "!#extract#!", "!#website#!", "!#activateinfo#!");
                    $replacements   = array($uname, '', "registration", "registration", '', '', $config['troopname'], $activateinfo);

                    $emailContent = str_replace($cmscoutTags, $replacements, $email['email']); 

                    sendMail($config['sitemail'], "Webmaster", $config['emailPrefix'] . $email['subject'], $emailContent);

                    $pagenum = 3;
                    $tpl->assign("username", $username);
                }
            }
            else
            {
                show_message("The captcha code you entered was incorrect, please try again", "index.php?page=register&stage=2", true);
            }
        }
        else
        {
            show_message("You need to fill in all required fields.", "index.php?page=register&stage=2", true);
        }
    }
} 
else 
{
    show_message("You are already logged in.");
}

$dbpage = true;
$pagename = "register";
$location = "Registration";

?>