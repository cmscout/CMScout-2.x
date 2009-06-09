<?php
/**************************************************************************
    FILENAME        :   photos.php
    PURPOSE OF FILE :   Shows photo albums
    LAST UPDATED    :   22 May 2006
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

/*******************Initilize Varibles*********************/
$num_albums = 0;
$number_photos = 0;
if (!$inarticle) {$albumid = 0;}
$album_name = '';
$limit = $config['numpage'];
$where =  $config['photopath'] . "/";
$number_of_photos = 0;
$ppatrol = isset($_GET['patrol']) ? $_GET['patrol'] : "";
 
 /*****************Build list of all patrol albums**************/
 if (!$inarticle) 
 {
    $pagenum=1;
    if ($ppatrol != "")
    {
        $temp = safesql($ppatrol, "int");
        $sql = $data->select_query("groups", "WHERE id=$temp", "id, teamname");
        if ($data->num_rows($sql) > 0)
        {
            $temp = $data->fetch_array($sql);
            $patrolname = $temp['teamname'];
            $patrolid = $temp['id'];
        }
        else
        {
            $temp = safesql($ppatrol, "text");
            $sql = $data->select_query("groups", "WHERE teamname=$temp", "id, teamname");
            if ($data->num_rows($sql) > 0)
            {
                $temp = $data->fetch_array($sql);
                $patrolname = $temp['teamname'];
                $patrolid = $temp['id'];
            }
        }
    }
    $location = $patrolname != "" ? "$patrolname Photo Albums": "General Photo Albums";
    
     if ($ppatrol != "") 
     {
        $sql = $data->select_query("album_track", "WHERE patrol = $patrolid AND allowed = 1 AND trash=0 ORDER BY album_name ASC");
     } 
     elseif ($config['albumdisplay'] == 0)
     {
        $sql = $data->select_query("album_track", "WHERE patrol = 0 AND allowed = 1 AND trash=0 ORDER BY album_name ASC"); 
     }
     else
     {
        $sql = $data->select_query("album_track", "WHERE patrol <> -1 AND allowed = 1 AND trash=0 ORDER BY album_name ASC"); 
     }
    $num_albums = $data->num_rows($sql);
   
   if ($num_albums == 0 && $patrolname != "")
    {
        $content = "This group does not have any published photo albums";
    }
    elseif ($num_albums == 0)
    {
        $content = "There are no photo albums here yet.";
    }
        
     if (!$num_albums)
     {
        $albumid = 0;
     }
     else
     { 
        $album_array = array();
        $ranphoto = array();
        $num_albums = 0;
        while ($temp = $data->fetch_array($sql))
        {
            if ($data->num_rows($data->select_query("photos", "WHERE album_id = '{$temp['ID']}'")) > 0)
            {
                $sql2 = $data->select_query("photos", "WHERE album_id = '{$temp['ID']}'");
                $tempphoto = array();
                while($tempphoto[] = $data->fetch_array($sql2));
                $number = rand(0, $data->num_rows($sql2)-1);
                $temp['numphotos'] = $data->num_rows($sql2);
                $temp['randomphoto'] = $tempphoto[$number]['ID'];
                $num_albums++;
                $temp['album_name'] = censor($temp['album_name']);
                $album_array[] = $temp;
            }
        }
     }
     /***********Get posted varibles*************/
    if (isset($_GET['album']) && $_GET['album'] != 0) 
     {
        $albumid = $_GET['album']; 
     }
     if (isset($_GET['start'])) $start = $_GET['start'];
     if (!isset($start))
     {
        $start = 0;
     }
 }
 
 /*************Display album on screen******************/
 if ($albumid != 0) 
 {
    //First get check if the album exists
    $sql = $data->select_query("album_track", " WHERE ID = $albumid AND allowed=1");
    $number_albums = $data->num_rows($sql);
    $album_info = $data->fetch_array($sql);
    $view_album_name = censor($album_info['album_name']);
    if ($number_albums == 0 && !$inarticle) 
    { 
        show_message_back("No such album");
    }
    elseif ($number_albums == 0 && $inarticle)
    {
        $number_of_photos = 0;
    }
    else
    {
        if (!$inarticle)
        {
            $pagenum=2;
            $edit = is_owner($album_info['ID'], "album") ? true : false;
            $editlink = "index.php?page=mythings&cat=album&action=edit&id={$album_info['ID']}&menuid=$menuid";
            $articlesql = $data->select_query("patrol_articles", "WHERE album_id={$album_info['ID']} AND allowed=1 ORDER BY title ASC", "ID, title");
            $numarticles = $data->num_rows($articlesql);
            $articlelist = array();
            while ($articlelist[] = $data->fetch_array($articlesql));
            $tpl->assign("numarticles", $numarticles);
            $tpl->assign("articlelist", $articlelist);
        }
        
        
        $next = false;
        $prev=false;
        
        //then get photo file names and captions from database
        $photosql = $data->select_query("photos", "WHERE album_id = $albumid AND allowed = 1 ORDER BY date ASC");
        $number_of_photos = $data->num_rows($photosql);
        $limit = $config['pagephoto'] == 1 ? $limit : $number_of_photos;
        $pagelimit = ($number_of_photos-$start) <= $limit ? ($number_of_photos-$start) : $limit;
        if (!$inarticle && $config['pagephoto'] == 1) 
        {
            $photosql = $data->select_query("photos", "WHERE album_id = $albumid AND allowed = 1 ORDER BY date ASC LIMIT $start, $pagelimit");
        }
    }
    
    //Pagenation working out
    if (!$inarticle && $config['pagephoto'] == 1)
    { 
        if ($number_of_photos > 0) 
        {
            $num_pages = ceil($number_of_photos / $limit);
            $curr_page = floor($start/$limit) + 1;
            if ($curr_page < $num_pages)
            {
                $next = true; 
                $next_start=(($curr_page-1)*$limit) + $limit;
            }
            if ($curr_page > 1) 
            {
                $prev = true; 
                $prev_start=(($curr_page-1)*$limit)- $limit;
            }
        } 
        else 
        {
            $content = "There are no photos in that album yet.";
        }
    }
    
    if($number_of_photos > 0)
    {
        //display all photos
        $photo = array();
        while ($temp = $data->fetch_array($photosql))
        {
            $temp['caption'] = censor($temp['caption']);
            $photo[] = $temp;
        }
    }
    if (!$inarticle) 
    {
        $editFormAction = $_SERVER['PHP_SELF'];
        if (isset($_SERVER['QUERY_STRING'])) 
        {
          $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
        }
        $tpl->assign('editFormAction', $editFormAction);
    
        $id = safesql($albumid, "int");
        if (isset($_POST['submit']) && $_POST['submit'] == "Post Comment")
        {
            $comment = safesql(strip_tags($_POST['comment']), "text");
            if ($config['confirmcomment'] == 1) $allowed = 0;
            else $allowed = 1;
            $timestamp = time();
            $data->insert_query("comments", "'', $id, '{$check['id']}', 1, $timestamp, $comment, $allowed", "", "", false);
            
            if (confirm('comment'))
            {
                $page = $_SERVER['PHP_SELF'];
                if (isset($_SERVER['QUERY_STRING'])) 
                {
                    $page .= "?" . $_SERVER['QUERY_STRING'];
                }
                $comment = $data->select_fetch_one_row("comments", "WHERE uid='{$check['id']}' AND item_id=$id AND date=$timestamp");
                confirmMail("comment", $comment);
                show_message("The comment first needs to be reviewed before it will be visible", $page);
            }
        }
         
        $sql = $data->select_query("comments", "WHERE item_id=$id AND type=1 AND allowed = 1");
        $numcom = $data->num_rows($sql);
        $comments = array();
        while ($temp = $data->fetch_array($sql))
        {
            $temp['comment'] = censor($temp['comment']);
            $temp2 = $data->select_fetch_one_row("users", "WHERE id={$temp['uid']}", "uname");
            $temp['uname'] = $temp2['uname'];
            $comments[] = $temp;
        }
        
        $tpl->assign("numcom", $numcom);
        $tpl->assign("com", $comments);
        $tpl->assign("comviewallowed", get_auth('comviewallowed', 2));
        $tpl->assign("compostallowed", get_auth('compostallowed', 2));
        
        $scriptList['slimbox'] = 1;
        
        $location = $view_album_name . " Photo Album";    
    }
}
else
{
    if (!$inarticle) 
    {
        $add = (get_auth('addphotoalbum') == 1) ? true : false;
        $addlink = "index.php?page=addphotoalbum&amp;menuid=$menuid";
    }        
}

$tpl->assign('number_of_albums', $num_albums);
if (isset($album_array)) $tpl->assign('albums', $album_array);
$tpl->assign('album_id', $albumid);
if (isset($view_album_name))$tpl->assign('view_album_name', $view_album_name);
if (isset($number_of_photos))$tpl->assign('number_of_photos', $number_of_photos); 
$tpl->assign('location', $where);
if (isset($photo))$tpl->assign('photo', $photo);
if (isset($num_pages)) $tpl->assign('numpages', $num_pages);
if (isset($curr_page)) $tpl->assign('currentpage', $curr_page);
if (isset($next))$tpl->assign('next', $next);
if (isset($prev))$tpl->assign('prev', $prev);
$tpl->assign('num_per_page', $limit);
if (isset($next_start)) $tpl->assign('next_start', $next_start);
if (isset($prev_start)) $tpl->assign('prev_start', $prev_start);
$tpl->assign('limit', $pagelimit);
$tpl->assign("numphotos", $number_of_photos);
$dbpage = true;
$pagename = "photoalbum";
?>