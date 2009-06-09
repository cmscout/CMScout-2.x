<?php
/**************************************************************************
    FILENAME        :   profile.php
    PURPOSE OF FILE :   Allows users to change password, theme and other information
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
if (!defined('SCOUT_NUKE'))
    die("You have accessed this page illegally, please go use the main menu");

if (isset($check["uname"])) 
{
 $tpl->assign('name',$check["uname"]);
} 

$action = $_GET['action'];

if ($action == "") 
{
    /********************************************Build page*****************************************/
    $currentPage = $_SERVER["PHP_SELF"];
    
    $id = safesql($check["id"], "int");
    
    $editFormAction = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING'])) 
    {
      $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
    }
    $location = "User Control Panel >> Profile";
    if ($_POST["submit"] == "Submit") 
    {
        if ($_FILES['avy']['name'] != '')
        {
            $avyfilename = "";
            $temp = $data->select_fetch_one_row("users", "WHERE id=$id");
            unlink($config['avatarpath']."/".$temp['avyfile']);
            if (($_FILES['avy']['type'] == 'image/gif') || ($_FILES['avy']['type'] == 'image/jpeg') || ($_FILES['avy']['type'] == 'image/png') || ($_FILES['avy']['type'] == 'image/pjpeg')) 
            {
                $filestuff = uploadpic($_FILES['avy'], $config['avyx'], $config['avyy'], false, $config['avatarpath']);
                $avyfilename = safesql($filestuff['filename'], "text");
            }
            else
            {
                show_message("Sorry, we only accept .gif, .jpg, .jpeg or .png images.", "index.php?page=profile");
            }
        }

        
        $errors .= "<span id=\"error\">";
                
        if (($_POST['newpassword'] != $_POST['repassword']) && ($_POST['newpassword'] != ''))
            $errors .= "Passwords do not match<br />";
        elseif ((strlen($_POST['newpassword']) < 6) && ($_POST['newpassword'] != ''))
            $errors .= "Minimum password length is 6 characters<br />";
        
        $sig = strip_tags($_POST['sig']);
        if (strlen($sig) > $config['sigsize'])
        {
            $errors .= "Your signature is to long, it can't be longer than {$config['sigsize']} characters";
        }  

        if (!isset($_POST['firstname']) || $_POST['firstname'] == "")
            $errors .= "You need to supply your first name<br />";
        if (!isset($_POST['lastname']) || $_POST['lastname'] == "")
            $errors .= "You need to supply your last name/surname<br />";
        
        if ($config['dubemail'] == 0)
        {
            $emailaddy = safesql($_POST['email'], "text");
            $datas = $data->select_query("users", "WHERE email=$emailaddy AND id != $id");
            $numrows = $data->num_rows($datas);
            if ($numrows > 0) 
            {
                $errors .= "That email address has already been used, please use another email address.<br />"; 
            } 
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
            if ($custom[$temp['name']] == '' && $temp['required'] == 1)
            {
                $errors .= "The <em>{$temp['query']}</em> field is required.<br />";
            }            
        }   
        $custom = serialize($custom);

        if ($errors != "<span id=\"error\">")
        {
            $errors .= "</span>";
            $exit = true;
        }
        else
            $errors = "";
        

        if (!$exit)
        {


            $insertSQL = sprintf("firstname=%s, lastname=%s, email=%s, newtopic=%s, allowemail=%s, newpm=%s, sig=%s, publicprofile=%s, showemail=%s, showname=%s, showrecord=%s,replytopic=%s, newarticle=%s, newevent=%s, newalbum=%s, newnews=%s, newdownload=%s, newpoll=%s, custom=%s",
                       safesql($_POST['firstname'], "text"),
                       safesql($_POST['lastname'], "text"),
                       safesql($_POST['email'], "text"),
                       safesql($_POST['newtopic'], "int"),
                       safesql($_POST['allowemail'], "int"),
                       safesql($_POST['newpm'], "int"),             
                       safesql($sig, "text"),
                       safesql($_POST['publicprofile'], "int"),
                       safesql($_POST['showemail'], "int"),
                       safesql($_POST['showname'], "int"),                      
                       safesql($_POST['showrecord'], "int"),
                       safesql($_POST['replytopic'], "int"),
                       safesql($_POST['newarticle'], "int"),
                       safesql($_POST['newevent'], "int"),
                       safesql($_POST['newalbum'], "int"),
                       safesql($_POST['newnews'], "int"),
                       safesql($_POST['newdownload'], "int"),
                       safesql($_POST['newpoll'], "int"),
                       safesql($custom, "text"));
                       
            if ($_FILES['avy']['name'] != '')
            {
                $insertSQL .= ", avyfile=" . $avyfilename;
            }
            
            $Result1 = $data->update_query("users", $insertSQL, "id=$id", "", "", false);
            if ($Result1) 
            {
                $themeid = $_POST['theme'];
                $templateinfo = ( isset($themeid) ) ? change_theme_dir($themeid) : change_theme_dir();
                $tpl->assign("templateinfo", $templateinfo);
                $zone = $_POST['zone'];
                $pass = md5($_POST['newpassword']);
                $repass = md5($_POST['repassword']);
                if ($pass != $repass) 
                {
                    show_message("Passwords don't match", "index.php?page=profile");
                }
                $oldpass = $check['passwd'];
                if ($pass == $oldpass) 
                { 
                    $pass=$oldpass; 
                } 
                elseif ($pass == md5('')) 
                { 
                    $pass=$oldpass; 
                }
        
                $insertSQL = sprintf("passwd=%s, theme_id=%s, timezone=%s",
                      safesql($pass, "text"),
                      safesql($themeid, "int"),
                      safesql($zone, "int"));
        
                $Result2 = $data->update_query("users", $insertSQL, "id=$id", "", "", false);					   
                if (($Result1) && ($Result2)) 
                { 
                    show_message("Information successfully updated", "index.php?page=profile");
                }
            } 	
        }
        else
        {
            $tpl->assign("errors", $errors);
        }
    }

        $theme = $data->select_fetch_all_rows($numthemes, "themes");
        
        $zone = $data->select_fetch_all_rows($numzones, "timezones", "ORDER BY offset ASC");
        
        $sql = $data->select_query("profilefields", "WHERE place=0 ORDER BY pos ASC");
        $fields = array();
        $numfields = $data->num_rows($sql);
        while ($temp =  $data->fetch_array($sql))
        {
            $temp['options'] = unserialize($temp['options']);
            $fields[] = $temp;
        }        
    
    if (!$exit)
    {        
       $row_scouts = $data->select_fetch_one_row("users", "WHERE id = $id");
       $row_scouts['custom'] = unserialize($row_scouts['custom']);
       $check = $Auth->page_check();
       $tpl->assign('personal', $row_scouts);
    }
    else
    {
        $row_scouts['firstname'] = $_POST['firstname'];
        $row_scouts['lastname'] = $_POST['lastname'];
        $row_scouts['tel'] = $_POST['tel'];
        $row_scouts['cell'] = $_POST['cell'];
        $row_scouts['address'] = $_POST['address'];
        $row_scouts['email'] = $_POST['email'];
        $row_scouts['newtopic'] = $_POST['newtopic'];
        $row_scouts['allowemail'] = $_POST['allowemail'];
        $row_scouts['newpm'] = $_POST['newpm'];    
        $row_scouts['sig'] = $_POST['sig'];
        $row_scouts['newcontent'] = $_POST['newcontent'];
        $row_scouts['publicprofile'] = $_POST['publicprofile'];
        $row_scouts['showemail'] = $_POST['showemail'];
        $row_scouts['showname'] = $_POST['showname'];
        $row_scouts['showrecord'] = $_POST['showrecord'];
        
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
        $row_scouts['custom'] = $custom;        
        $tpl->assign('personal', $row_scouts);
    }
    
    $tpl->assign('fields', $fields);
    $tpl->assign('numfields', $numfields);
    $tpl->assign('uinfo', $check);
    $tpl->assign('editFormAction', $editFormAction);
    $tpl->assign('numthemes', $numthemes);
    $tpl->assign('theme', $theme);
    $tpl->assign('zone', $zone);
    $tpl->assign('numzones', $numzones);
    $scriptList['datepicker'] = 1;
    $pagenum = 1;
}
else
{
    $id = safesql($_GET['user'], "int");

    $sql = $data->select_query("profilefields", "WHERE profileview = 1 AND place=0 ORDER BY pos ASC");
    $fields = array();
    $numfields = $data->num_rows($sql);
    while ($temp =  $data->fetch_array($sql))
    {
        $temp['options'] = unserialize($temp['options']);
        $fields[] = $temp;
    }      

    $info = $data->select_fetch_one_row("users", "WHERE id=$id");
    
    $info['custom'] = unserialize($info['custom']);
    $info['group'] = user_groups_list($info['id'], true);
    $info['online'] = user_online($info['uname']);
    $info['location'] = user_location($info['uname']);
    
    $location = "Profile of {$info['uname']}";

    $tpl->assign('fields', $fields);
    $tpl->assign('numfields', $numfields);   
    $tpl->assign('info', $info);
    $tpl->assign("timenow", time());
    
    $pagenum = 2;
}
$dbpage = true;
$pagename = "profile";
?>