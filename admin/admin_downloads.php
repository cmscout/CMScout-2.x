<?php
/**************************************************************************
    FILENAME        :   admin_downloads.php
    PURPOSE OF FILE :   Manage downloads and download categories
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
	$module['Content Management']['Download Manager'] = "downloads";
    $moduledetails[$modulenumbers]['name'] = "Download Manager";
    $moduledetails[$modulenumbers]['details'] = "Management of downloads and download categories";
    $moduledetails[$modulenumbers]['access'] = "Allowed to access the download manager";
    $moduledetails[$modulenumbers]['access'] = "Allowed to access the download manager";
    $moduledetails[$modulenumbers]['add'] = "Allowed to add a download category or download";
    $moduledetails[$modulenumbers]['edit'] = "Allowed to edit existing download or category";
    $moduledetails[$modulenumbers]['delete'] = "Allowed to delete existing downloads or categorys";
    $moduledetails[$modulenumbers]['publish'] = "Allowed to publish and unpublish downloads";
    $moduledetails[$modulenumbers]['limit'] = "notused";
    $moduledetails[$modulenumbers]['id'] = "downloads";

	return;
}
else
{
    
    $editFormAction = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING'])) 
    {
      $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
    }
    
    $action = $_GET['action'];
    $id = $_GET['id'];
    $did = $_GET['did'];
    
    if ($action == 'delete' && pageauth("downloads", "delete") == 1) 
    {
        $id = safesql($id, "int");
        $sqlq = $data->delete_query("download_cats", "id=$id", "", "", false);
        if ($sqlq) 
        { 
            $sqlq = $data->update_query("downloads", "cat=0", "cat=$id");		
            if ($sqlq)
            {
                show_admin_message("Download Category deleted", "$pagename");
            }
        }
    } 
    elseif ($action == "deletedown" && pageauth("downloads", "delete") == 1) 
    {
        $did = safesql($did, "int");
        $sqlq = $data->update_query("downloads", "trash=1", "id=$did");
        $action = "view";
        if ($sqlq)
        {
            show_admin_message("Download deleted", "$pagename&action=view&id=$id");
        }
    }
    elseif ($action == 'publish' && pageauth("downloads", "publish") == 1) 
    {
        $did= safesql($did, "int");
        $sqlq = $data->update_query("downloads", "allowed = 1", "id=$did", "Downloads", "Published $did");
        if ($data->num_rows($data->select_query("review", "WHERE item_id=$id AND type='download'")))
        {        
            $item = $data->select_fetch_one_row("downloads", "WHERE id=$id");
            email('newitem', array("download", $item));
            $data->delete_query("review", "item_id=$id AND type='download'");
        }
        header("Location: $pagename&action=view&id=$id");
    }
    elseif ($action == 'unpublish' && pageauth("downloads", "publish") == 1) 
    {
        $did= safesql($did, "int");
        $sqlq = $data->update_query("downloads", "allowed = 0", "id=$did", "Downloads", "Unpublished $did");
        header("Location: $pagename&action=view&id=$id");
    }
    elseif ($action == "down")
    {
        $did= safesql($did, "int");
        $sql = $data->select_query("downloads", "WHERE id=$did");
        $down = $data->fetch_array($sql);
        if (file_exists($config["downloadpath"] . '/' . $down['saved_file']) && $data->num_rows($sql) > 0) 
        {
            header('Content-type: application/octet-stream');

            header('Content-Disposition: attachment; filename="'. $down['file'] .'"'); 
            
            echo file_get_contents($config["downloadpath"] . '/' . $down['saved_file']);
            exit;
        } 
        else 
        {
            show_message('File not found, please contact the administrator', "index.php?page=downloads");
        }
    }
    
    
    $submit = $_POST['Submit'];
    if ($submit == "Submit") 
    {
        if ($action == "adddown" && pageauth("downloads", "add") == 1) 
        {
            $name = safesql($_POST['name'], "text");
            $desc = safesql($_POST['desc'], "text");
            $thumbnail = safesql($_POST['downloadphoto'], "text");
            $where = $config['downloadpath'] . "/";
            
            $filename = $_FILES['file']['name'];
            $savefile = md5($_FILES['file']['name'] . (microtime() + mktime()));
            if (($_FILES['file']['size']/1024 <= $config['uploadlimit']))
            {
                move_uploaded_file($_FILES['file']['tmp_name'],$where . $savefile);
            }
            
            $filename = safesql($filename, "text");
            $savefile = safesql($savefile, "text");
            $sql = $data->insert_query("downloads", "NULL, $name, $desc, $id, $filename, $savefile, $thumbnail, '0', '".ceil($_FILES['file']['size'] / 1024)."', 1, 0");
            if ($sql)
            {
                show_admin_message("Download added", "$pagename&action=view&id=$id");
            }
        } 
        elseif ($action == "editdown" && pageauth("downloads", "edit") == 1) 
        {
            $name = safesql($_POST['name'], "text");
            $desc = safesql($_POST['desc'], "text");
            $picture = safesql($_POST['downloadphoto'], "text");
            $where = $config['downloadpath'] . "/";
            $cat = safesql($_POST['cat'], "text");
            $did = safesql($did, "int");
            
            $event = $_GET['event'];
            if ($_FILES['file']['name'] != "")
            {
                $down = $data->select_fetch_one_row("downloads", "WHERE id=$did", "saved_file");
                $where = $config['downloadpath'] . "/";
                if ($down['saved_file'] != '')
                {
                    unlink($where . $down['saved_file']);
                }
                $filename = $_FILES['file']['name'];
                $savefile = md5($_FILES['file']['name'] . (microtime() + mktime()));
                if (($_FILES['file']['size']/1024 <= $config['uploadlimit']))
                {
                    move_uploaded_file($_FILES['file']['tmp_name'],$where . $savefile);
                }
                else
                {
                    show_admin_message("The file is larger than the maximum allowable file size (Upload Limit:{$config['uploadlimit']}Kb, File size: " . ceil($_FILES['file']['size']/1024) . "Kb ).", "$pagename&action=editdown&did=$did&id=$id" . isset($event) ? "&event=$event" : ""); 
                }

                if ($_FILES['file']['name'] != "" && (!file_exists($where . $savefile) || filesize($where . $savefile) == 0))
                {
                    show_admin_message("There was an error uploading the file. Try again, if the problem persists contact the administrator.", "$pagename&action=view&id=$id"); 
                }
                $filename = safesql($filename, "text");
                $savefile = safesql($savefile, "text");
            }

            if ($_FILES['file']['name'] != "")
            {
                $sql = $data->update_query("downloads", "name = $name, thumbnail=$picture, descs = $desc, cat = $cat, file = $filename, saved_file= $savefile, numdownloads = 0, size = '".ceil($_FILES['file']['size'] / 1024)."'", "id=$did");
            }
            else
            {
                $sql = $data->update_query("downloads", "name = $name, thumbnail=$picture, descs = $desc, cat = $cat", "id=$did");
            }


            show_admin_message("Download updated", isset($event) ? "admin.php?page=events&action=signups&id=$event&activetab=downloads" : "$pagename&action=view&id=$id");
            $action="view";
        }
        elseif ($action == "add" && pageauth("downloads", "add") == 1)
        {
            if ($_POST['catname'] == '')
            {
                show_message_back("You need to enter a name for the category");
                exit;
            }
            $catname = safesql($_POST['catname'], "text");
            
            $upauths = safesql(serialize($_POST['upload']), "text");
            $downauths = safesql(serialize($_POST['download']), "text");
            
            $sql = $data->insert_query("download_cats", "NULL, $catname, $upauths, $downauths");
            if ($sql)
            {
                show_admin_message("Category added", "$pagename");
            }
            $action = "";
        }
        elseif ($action == "edit" && pageauth("downloads", "edit") == 1)
        {
            if ($_POST['catname'] == '')
            {
                show_message_back("You need to enter a name for the category");
                exit;
            }
            $catname = safesql($_POST['catname'], "text");
            
            $upauths = safesql(serialize($_POST['upload']), "text");
            $downauths = safesql(serialize($_POST['download']), "text");
            
            $sql = $data->update_query("download_cats", "name = $catname, upauth = $upauths, downauth = $downauths", "id = $id");
            if ($sql)
            {
                show_admin_message("Category updated", "$pagename");
            }
        }
    }
    
    if ($action == "view") 
    {
        $query = $data->select_query("download_cats", "WHERE id = $id");
        $catinfo = $data->fetch_array($query);
        $down_query = $data->select_query("downloads", "WHERE cat='$id' AND trash=0");
        $numdown = $data->num_rows($down_query);
        $downloads = array();
        $downloads[] = $data->fetch_array($down_query);
        while ($downloads[] = $data->fetch_array($down_query));
        $tpl->assign("downloads", $downloads);
        $tpl->assign("numdown", $numdown);
        $tpl->assign("catinfo", $catinfo);
        $tpl->assign('id', $id);
        $tpl->assign("downpath", $config["downloadpath"] . "/");
    } 
    elseif ($action == "adddown" && pageauth("downloads", "add") == 1) 
    {
        $quer = $data->select_query("album_track", "WHERE allowed=1 AND trash=0 ORDER BY album_name ASC");
        $numalbum = $data->num_rows($quer);
        $albums = array();
        while ($temp = $data->fetch_array($quer))
        {
            $temp['photos'] = $data->select_fetch_all_rows($temp['numphotos'], "photos", "WHERE album_id = {$temp['ID']} AND allowed = 1");
            $albums[] = $temp;
        }
        $tpl->assign('numalbum', $numalbum);
        $tpl->assign('albums', $albums); 
    } 
    elseif ($action == "editdown" && pageauth("downloads", "edit") == 1) 
    {
        $que = $data->select_query("downloads", "WHERE id = '$did' AND trash=0");
        $download = $data->fetch_array($que);
        $sql = $data->select_query("download_cats", "ORDER BY name ASC");
        $cats = array();
        $numcats = $data->num_rows($sql);
        while($cats[] = $data->fetch_array($sql));
        
        $quer = $data->select_query("album_track", "WHERE allowed=1 AND trash=0 ORDER BY album_name ASC");
        $numalbum = $data->num_rows($quer);
        $albums = array();
        while ($temp = $data->fetch_array($quer))
        {
            $temp['photos'] = $data->select_fetch_all_rows($temp['numphotos'], "photos", "WHERE album_id = {$temp['ID']} AND allowed = 1");
            $albums[] = $temp;
        }
        $tpl->assign('numalbum', $numalbum);
        $tpl->assign('albums', $albums);    
        
        if($download['thumbnail'])
        {
            $photoid = safesql($download['thumbnail'], "int");
            $photo = $data->select_fetch_one_row("photos", "WHERE ID=$photoid", "album_id");
            
            $selectedAlbumInfo['photos'] = $data->select_fetch_all_rows($selectedAlbumInfo['numphotos'], "photos", "WHERE album_id = {$photo['album_id']} AND allowed = 1");
            $tpl->assign("selectedAlbumInfo", $selectedAlbumInfo);
            $tpl->assign("selectedAlbum", $photo['album_id']);
        }        
        
        $tpl->assign('cat', $cats);
        $tpl->assign('numcats', $numcats);
        $tpl->assign('download', $download);
    } 
    elseif ($action == "edit" && pageauth("downloads", "edit") == 1)
    {
        $sql = $data->select_query("download_cats", "WHERE id = $id");
        $cat = $data->fetch_array($sql);
        $cat['upauth'] = unserialize($cat['upauth']);
        $cat['downauth'] = unserialize($cat['downauth']);
    
        $sql = $data->select_query("groups", "ORDER BY teamname ASC", "id, teamname");
        $numgroups = $data->num_rows($sql);
        $groups = array();
        $groups[0]['id'] = -1;
        $groups[0]['teamname'] = "Guest";
        $groups[0]['download'] = $cat['downauth'][0]==1 ? 1 : 0;
        while ($groups[] = $data->fetch_array($sql));
        $tpl->assign('guest', $downloads[0]);
        $tpl->assign('item', $item);
        $tpl->assign('group', $groups);
        $tpl->assign('numgroups', $numgroups+1);
        $tpl->assign("cat", $cat);
    }
    elseif ($action == "add" && pageauth("downloads", "add") == 1)
    {
        $sql = $data->select_query("groups", "ORDER BY teamname ASC", "id, teamname");
        $numgroups = $data->num_rows($sql);
        $groups = array();
        $groups[0]['id'] = -1;
        $groups[0]['teamname'] = "Guest";
        while ($groups[] = $data->fetch_array($sql));
        $tpl->assign('group', $groups);
        $tpl->assign('numgroups', $numgroups+1);
    }
    else 
    {
        $cats = $data->select_query("download_cats", "ORDER BY name ASC");
        $row_cats = array();
        $row_cats[] = $data->fetch_array($cats);
        $num_cats = $data->num_rows($cats);
        while ($row_cats[] = $data->fetch_array($album)); 
        $tpl->assign('cats', $row_cats);
        $tpl->assign('num_cats', $num_cats);
    }
    
    $tpl->assign('editFormAction', $editFormAction);
    $tpl->assign('id', $id);
    $tpl->assign('action', $action);
    $filetouse = 'admin_downloads.tpl';
}
?>