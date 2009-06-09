<?php
/**************************************************************************
    FILENAME        :   forgot.php
    PURPOSE OF FILE :   Resets users password and emails user with new password.
    LAST UPDATED    :   13 February 2006
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

if ($check['id'] != -1)
{
    show_message("You can change your password in your profile", "index.php?page=profile");
}

if ($_POST['submit'] == "Submit")
{
    $email = safesql($_POST['email'], "text");
    $uname = safesql($_POST['uname'], "text");

    $sql = $data->select_query("users", "WHERE email=$email AND uname=$uname");
        
    if ($data->num_rows($sql) > 0)
    {    
        $temp = $data->fetch_array($sql);

        $id = safesql($temp['id'], "text");
        $password = "";
        srand(time(NULL));
        $passlen = rand(6,10);
        for($i=0;$i<=$passlen;$i++)
        {
            $what = rand(0,3);
            if($what == 1)
            {
                $password .= chr(rand(48, 57));
            }
            elseif ($what == 2)
            {
                $password .= chr(rand(65, 90));
            }
            elseif ($what == 3)
            {
                $password .= chr(rand(97, 122));
            }
            elseif ($what == 0)
            {
                $password .= chr(rand(48, 57));
            }
        }
        $mdpassword = safesql(md5($password), "text");
        $data->update_query("users", "passwd=$mdpassword", "id=$id");
        
        $email = $data->select_fetch_one_row("emails", "WHERE type='pwreset'");
        
        $cmscoutTags = array("!#uname#!", "!#postuname#!", "!#title#!", "!#type#!", "!#link#!", "!#extract#!", "!#website#!", "!#password#!");
        $replacements   = array($temp['uname'], '', '', '', '', '', $config['troopname'], $password);

        $emailContent = str_replace($cmscoutTags, $replacements, $email['email']);
        sendMail($temp['email'], $temp['uname'], $email['subject'], $emailContent);

        show_message("Your new password has been emailed to you.");
    }
    elseif ($data->num_rows($sql) == 0)
    {
        show_message("That email address does not seem to exist in our database.");
    }
}

$dbpage = true;
$pagename='forgot';
$location = "Password Reset";
?>