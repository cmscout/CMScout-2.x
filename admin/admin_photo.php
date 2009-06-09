<?php
/**************************************************************************
    FILENAME        :   admin_photo.php
    PURPOSE OF FILE :   Manages photos and photo albums
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
	$module['Content Management']['Photo Album Manager'] = "photo";
    $moduledetails[$modulenumbers]['name'] = "Photo Album Manager";
    $moduledetails[$modulenumbers]['details'] = "Manages photo albums";
    $moduledetails[$modulenumbers]['access'] = "Allowed to access the photo album manager";
    $moduledetails[$modulenumbers]['add'] = "Allowed to add a new photo album";
    $moduledetails[$modulenumbers]['edit'] = "Allowed to edit photos";
    $moduledetails[$modulenumbers]['delete'] = "Allowed to delete photo albums and photos";
    $moduledetails[$modulenumbers]['publish'] = "Allowed to publish/unpublish photo albums and photos";
    $moduledetails[$modulenumbers]['limit'] = "Limit to photos belonging to groups the user is a part of";
    $moduledetails[$modulenumbers]['id'] = "photo";

	return;
}
else
{
    $subpage = $_GET['subpage'] != '' ? $_GET['subpage'] : '';
    
    if (!$subpage)
    {

        $action = $_GET['action'];
        $id = $_GET['id'];

        if ($action == 'delete' && pageauth("photo", "delete") == 1) 
        {
            $sqlq = $data->update_query("album_track", "trash=1", "ID=$id");
            
            if ($sqlq) 
            { 		
                show_admin_message("Album deleted", "$pagename");   
            }
        }
        elseif ($action == "deletephoto" && pageauth("photo", "delete") == 1) 
        {
            $pid = $_GET['pid'];
            $sqlq = $data->delete_query("photos", "ID=$pid AND album_id='$id'");
            $act = "view";
            if($sqlq)
            {
                show_admin_message("Photo deleted", "$pagename&action=view&id=$id"); 
            } 
        }
        elseif ($action == 'publishart' && pageauth("photo", "publish") == 1) 
        {
            $ext = $_GET['photo'];
            if ($ext == "yes")
            {
                $sqlq = $data->update_query("photos", "allowed = 1", "album_id=$id", "", "", false);                    
            }
            $sqlq = $data->update_query("album_track", "allowed = 1", "ID=$id", "Albums", "Published $id");
            if ($data->num_rows($data->select_query("review", "WHERE item_id=$id AND type='album'")))
            {        
                $item = $data->select_fetch_one_row("album_track", "WHERE ID=$id");
                email('newitem', array("album", $item));
                $data->delete_query("review", "item_id=$id AND type='album'");
            }
            header("Location: $pagename");
        }
        elseif ($action == 'unpublishart' && pageauth("photo", "publish") == 1) 
        {
            $sqlq = $data->update_query("album_track", "allowed = 0", "ID=$id", "Albums", "Unpublished $id");
            header("Location: $pagename");
        }
        elseif ($action == 'publishphoto' && pageauth("photo", "publish") == 1) 
        {
            $pid = $_GET['pid'];
            $sqlq = $data->update_query("photos", "allowed = 1", "ID=$pid", "Photos", "Published $id");
            header("Location: $pagename&action=view&id=$id");
        }
        elseif ($action == 'unpublishphoto' && pageauth("photo", "publish") == 1) 
        {
            $pid = $_GET['pid'];
            $sqlq = $data->update_query("photos", "allowed = 0", "ID=$pid", "Photos", "Unpublished $id");
            header("Location: $pagename&action=view&id=$id");
        }

        if ($action == "view") 
        {
	   $scriptList['slimbox'] = 1;
            $id = safesql($_GET['id'], "int");
            $query = $data->select_query("album_track", "WHERE id = $id AND trash=0");
            $albuminfo = $data->fetch_array($query);
            $photo_query = $data->select_query("photos", "WHERE album_id='".$albuminfo['ID']."'");
            $numphotos = $data->num_rows($photo_query);
            $photo = array();
            $photo[] = $data->fetch_array($photo_query);
            while ($photo[] = $data->fetch_array($photo_query));
            
            if (pageauth("photo", "limit") == 1)
            {
                $groupsqllist = group_sql_list_id("id", "OR", true);     
                $teams = $data->select_fetch_all_rows($numteams, "groups", "WHERE ($groupsqllist) AND ispublic=1");
            }
            else
            {
                $teams = $data->select_fetch_all_rows($numteams, "groups", "WHERE ispublic=1");
            }
            
            $tpl->assign('teams',$teams);
            $tpl->assign('numteams', $numteams);
            $tpl->assign("photos", $photo);
            $tpl->assign("numphotos", $numphotos);
            $tpl->assign("albuminfo", $albuminfo);
            $tpl->assign('id', $id);
            $tpl->assign("photopath", $config["photopath"] . "/");
           
            
            if ($_POST['Submit'] == "Update")
            {
                $group = safesql($_POST['group'], "int");
                $name = safesql($_POST['name'], "text");
                $data->update_query("album_track", "album_name=$name, patrol=$group", "ID = $id");
                show_admin_message("Album updated", "$pagename&action=view&id=$id"); 
            }
            elseif($_POST['Submit'] == "Upload Photo")
            {
                if ($_FILES['filename']['name'] == '')
                {
                    show_message("You need to select a file to upload", "$pagename&action=view&id={$id}");
                    exit;
                }
                if (($_FILES['filename']['type'] == 'image/gif') || ($_FILES['filename']['type'] == 'image/jpeg') || ($_FILES['filename']['type'] == 'image/png') || ($_FILES['filename']['type'] == 'image/pjpeg')) 
                {
                    $filestuff = uploadpic($_FILES['filename'], $config['photox'], $config['photoy'], true);
                    $filename = $filestuff['filename'];
                    $desc = $_POST['caption'];
                    $insert = sprintf("NULL, %s, %s, %s, $timestamp, 1",
                                        safesql($filename, "text"),
                                        safesql($desc, "text"),
                                        safesql($id, "int"));

                    $data->insert_query("photos", $insert, "", "", false);
                    
                    show_admin_message("Photo added", "$pagename&action=view&id=$id"); 
                } 
                else
                {
                    show_message("Sorry, we only accept .gif, .jpg, .jpeg or .png images.<br />And the file that you wish to upload is a {$_FILES['filename']['type']}", "$pagename&action=view&id={$id}");
                }
            }
            elseif ($_POST['Submit'] == "Update Photo") 
            {
                $photoid = safesql($_POST['photoid'],"int");
                
                if ($_FILES['editfilename']['name'] != '') 
                {
                    if (($_FILES['editfilename']['type'] == 'image/gif') || ($_FILES['editfilename']['type'] == 'image/jpeg') || ($_FILES['editfilename']['type'] == 'image/png') || ($_FILES['editfilename']['type'] == 'image/pjpeg')) 
                    {
                        $filestuff = uploadpic($_FILES['editfilename'], $config['photox'], $config['photoy'], true);
                        $filename = safesql($filestuff['filename'], "text");;
                        $desc = safesql($_POST['editcaption'], "text");

                        $data->update_query("photos", "filename=$filename, date='$timestamp', caption = $desc", "ID=$photoid");
                    } 
                    else
                    {
                        show_admin_message("Sorry, we only accept .gif, .jpg, .jpeg or .png images", "$pagename&action=view&id=$id");
                    }
                } 
                else
                {
                    $desc = safesql($_POST['editcaption'], "text");
                    if ($desc != '')
                    {
                        $data->update_query("photos", "caption = $desc", "ID='$photoid'");	
                    }
                }
                show_admin_message("Photo updated", "$pagename&action=view&id=$id"); 
            }
        } 
        elseif ($action == "new")
        {
            if (pageauth("photo", "limit") == 1)
            {
                $groupsqllist = group_sql_list_id("id", "OR", true);     
                $teams = $data->select_fetch_all_rows($numteams, "groups", "WHERE ($groupsqllist) AND ispublic=1");
            }
            else
            {
                $teams = $data->select_fetch_all_rows($numteams, "groups", "WHERE ispublic=1");
            }
            
            $tpl->assign('teams',$teams);
            $tpl->assign('numteams', $numteams);
            
            if ($_POST['submit'] == "Add Album")
            {
                $group = safesql($_POST['patrol'], "int");
                $name = safesql($_POST['album_name'], "text");
                $data->insert_query("album_track", "'', $name, $group, 1, 0");
                show_admin_message("Album added", "$pagename"); 
            }
        }
        elseif ($action == "")
        {
            
            if (pageauth("photo", "limit")) 
            {
                $patrollist = group_sql_list_id("patrol", "OR");
                $result = $data->select_query("album_track", "WHERE ($patrollist) AND trash=0 ORDER BY album_name ASC");
            } 
            else 
            {
                $result = $data->select_query("album_track", "WHERE trash=0 ORDER BY album_name ASC");
            }

            $albums = array();
            while ($temp = $data->fetch_array($result))
            {
                if ($temp['patrol'] > 0)
                {                
                    $temp2 = $data->select_fetch_one_row("groups", "WHERE id={$temp['patrol']}", "teamname");
                    $temp['patrol'] = $temp2['teamname'];
                }
                elseif ($temp['patrol'] == 0)
                {
                    $temp['patrol'] = "None";
                }

                $albums[] = $temp;
            }
            $numalbums = $data->num_rows($result);
            
            $tpl->assign('albums', $albums);
            $tpl->assign('numalbums', $numalbums);
        }
         $tpl->assign('action', $action);
        $filetouse = 'admin_photo.tpl';
    }
    else
    {
        $allowed = array('photo_edit'=>true);
        
        if (array_key_exists($subpage, $allowed))
        {
            include("admin/admin_$subpage.php");
        }
    }
}
?>