<?php
/**************************************************************************
    FILENAME        :   addphotoalbum.php
    PURPOSE OF FILE :   Add a users photo album to the database
    LAST UPDATED    :   17 May 2006
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
$location = "Add Photo Album";
/********************************************Check if user is allowed*****************************************/
if (isset($check["uname"])) 
{
 $tpl->assign('name',$check["uname"]);
} 

$uname = $check["uname"];
if (!$error) 
{
    $currentPage = $_SERVER["PHP_SELF"];

    $editFormAction = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING']))
    {
      $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
    }
    $tpl->assign('editFormAction', $editFormAction);

    if (isset($_POST["submit"])) 
    {
        if (validate($_POST['validation']))
        {
            $album_name = strip_tags($_POST['album_name']);
            $patrol = $_POST['patrol'];
            
            $insertSQL = sprintf("NULL, %s, %s",
                                       safesql($album_name, "text"),
                                       safesql($patrol, "int"));	
                                       
            if (confirm('album'))
            {
                $message = "Your album has been added, but first needs to be reviewed by an administrator.";
                $insertSQL .= ", 0";
            }
            else
            {
                $message = "Your album has been added.";
                $insertSQL .= ", 1";
            }
            
            $album_name = safesql($album_name, "text");
            if ($data->insert_query("album_track", $insertSQL . ", 0"))
            {
                $album = $data->select_fetch_one_row("album_track", "WHERE album_name=$album_name ORDER BY ID DESC");
                $data->update_query("users", "numalbums = numalbums + 1", "uname='{$check['uname']}'");
                $data->insert_query("owners", "'', {$album['ID']}, 'album', {$check['id']}, 0, 0, 0");
                if (confirm('album'))
                {
                    confirmMail("album", $album);
                }
                else
                {
                    email('newitem', array("album", $album));
                }
                show_message("Your photo album has been created. {$extra}", "index.php?page=mythings&cat=album&action=edit&id={$album['ID']}&menuid=$menuid");
            }
            else
            {
                show_message("There was an error adding your photo album. If this error persists please contact the site administrator.", "index.php?page=addphotoalbum", true);
            }
        }
        else
        {
            show_message("There where some errors with some fields, please check them again and resubmit.", "index.php?page=addphotoalbum&menuid=$menuid", true);        
        }
    }
    
    $groups = public_group_sql_list_id("id", "OR");    
    $teams = array();
    $team_query = $data->select_query("groups", "WHERE ($groups) AND ispublic=1");
    $numteams = $data->num_rows($team_query);
    while ($teams[] = $data->fetch_array($team_query));
    
    $tpl->assign('teams',$teams);
    $tpl->assign('numteams', $numteams);
    $tpl->assign("post", $post);
}

$dbpage = true;
$pagename = "addalbum";
?>