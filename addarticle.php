<?php
/**************************************************************************
    FILENAME        :   addarticle.php
    PURPOSE OF FILE :   Add a users article to the database
    LAST UPDATED    :   06 May 2006
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
/********************************************Check if user is allowed*****************************************/
if (!defined('SCOUT_NUKE'))
    die("You have accessed this page illegally, please go use the main menu");
$location = "Add Article";
if (isset($check["uname"])) 
{
 $tpl->assign('name',$check["uname"]);
} 

$message = "";
$uname = $check["uname"];
if (!$error) 
{
    $currentPage = $_SERVER["PHP_SELF"];

    $where = $config['photopath'] . "/";
	if ((isset($_POST["Submit"])) && ($_POST["Submit"] == "Submit")) 
    {
        if (validate($_POST['validation']))
        {
            $insertSQL = sprintf("NULL, %s, %s, %s, %s, %s, %d, %s, %s",
                               safesql(isset($_POST['patrol']) ? $_POST['patrol'] : 0, "int"),
                               safesql($_POST['articlephoto'], "int"),
                               safesql($_POST['title'], "text"),
                               safesql($_POST['story'], "text", false),                         
                               $timestamp,									   
                               safesql(isset($_POST['photo']) ? $_POST['photo'] : 0, "int"),									   
                               safesql(isset($_POST['event']) ? $_POST['event'] : 0, "int"),									   
                               safesql($_POST['auth'], "text"));
            
            if (confirm('article'))
            {
                $message = "Your article has been added, but first needs to be reviewed by an administrator.";
                $insertSQL .= ", 0";
            }
            else
            {
                $message = "Your article has been added.";
                $insertSQL .= ", 1";
            }

            $topics = safesql(serialize($_POST['topics']), "text");
            $order = safesql($_POST['order'], "int");
            $summary = safesql($_POST['summary'], "text");
            $related = safesql(serialize($_POST['articles']), "text");
            
            $insertSQL .= ", $topics, $order, $summary, $related";

            if ($data->insert_query("patrol_articles", $insertSQL . ", 0")) 
            { 
                $title = safesql($_POST['title'], "text");
                $article = $data->fetch_array($data->select_query("patrol_articles", "WHERE title=$title AND date_post=$timestamp"));
                $data->update_query("users", "numarticles = numarticles + 1", "id='{$check['id']}'");
                $data->insert_query("owners", "'', {$article['ID']}, 'articles', {$check['id']}, 0, 0, 0");
                if (confirm('article'))
                {
                    confirmMail("article", $article);
                }
                else
                {
                    email('newitem', array("article", $article));
                }
                show_message($message, "index.php?page=mythings&menuid=$menuid");
            }
            else
            {
                show_message("There was an error adding your article. If this error persists please contact the site administrator.", "index.php?page=addarticle&menuid=$menuid", true);
            }
        }
        else
        {
            show_message("There where some errors with some fields, please check them again and resubmit.", "index.php?page=addarticle&menuid=$menuid", true);        
        }
    }
    elseif($_POST['preview'] == "Preview Article")
    { 
        if (validate($_POST['validation']))
        {    
            $post['patrol'] = $_POST['patrol'];
            $post['title'] = $_POST['title'];
            $post['story'] = stripslashes($_POST['story']);
            $post['photo'] = $_POST['photo'];
            $post['articlephoto'] = $_POST['articlephoto'];
            $post['event'] = $_POST['event'];
            $post['auth'] = $_POST['auth'];
            $post['datepost'] = $timestamp;
            $post['topics'] = $_POST['topics'];
            $post['order'] = $_POST['order'];
            $post['summary'] = $_POST['summary'];
            $post['related'] = $_POST['articles'];   
            
            if ($post['photo'] != 0) 
            { 
                $photoid = safesql($post['photo'], "int");
                $photo = $data->select_fetch_all_rows($number_of_photos, "photos", "WHERE album_id=$photoid");
                $scriptList['gallery'] = 1;
                $tpl->assign("previewphoto", $photo);
                $tpl->assign("number_of_photos", $number_of_photos);
            } 
            
            if($post['articlephoto'])
            {
                $photoid = safesql($post['articlephoto'], "int");
                $photo = $data->select_fetch_one_row("photos", "WHERE ID=$photoid", "album_id");
                
                $selectedAlbumInfo['photos'] = $data->select_fetch_all_rows($selectedAlbumInfo['numphotos'], "photos", "WHERE album_id = {$photo['album_id']} AND allowed = 1");
                $tpl->assign("selectedAlbumInfo", $selectedAlbumInfo);
                $tpl->assign("selectedAlbum", $photo['album_id']);
            }

            if ($post['event'] != 0) 
            { 
                $eventid = safesql($post['event'], "int");
                $event = $data->select_fetch_one_row("calendar_items", "WHERE id = {$eventid}", "id, summary, startdate, enddate");
               
                $tpl->assign("previewevent", $event);
            } 
  
            $temp['related'] = '';
            $num = 1;
            while (list($articleid, $value) = each($post['related'])) 
            {
                $articleid = safesql($articleid, "int");            
                $topicdetail = $data->select_fetch_one_row("patrol_articles", "WHERE ID = $articleid", "title");
                $temp['related'] .= $topicdetail['title'];
                if ($num++ < count($post['related'])) $temp['related'] .= ", ";
            }
            
            $post['relatedlist'] = $temp['related'];
            $tpl->assign('post', $post);

            $tpl->assign("preview", "true");
        }
        else
        {
            echo "<script> alert('Some of your inputs might not be correct. Please check them again');</script>\n";
        }
    }
        

    $quer = $data->select_query("album_track", "WHERE allowed=1 AND trash=0 ORDER BY album_name ASC");
    $numalbum = $data->num_rows($quer);
    $albums = array();
    while ($temp = $data->fetch_array($quer))
    {
        $temp['photos'] = $data->select_fetch_all_rows($temp['numphotos'], "photos", "WHERE album_id = {$temp['ID']} AND allowed = 1");
        $albums[] = $temp;
    }
    
    $event = $data->select_fetch_all_rows($numevents, "calendar_items", "WHERE allowed=1 AND trash=0 ORDER BY summary ASC"); 
    
    $groups = public_group_sql_list_id("id", "OR");    
    if ($groups)
    {    
        $teams = array();
        $team_query = $data->select_query("groups", "WHERE ($groups) AND ispublic=1");
        $numteams = $data->num_rows($team_query);
        while ($teams[] = $data->fetch_array($team_query));
    }
    else
    {
        $numteams = 0;
    }

    $result = $data->select_query("articletopics", "ORDER BY title ASC", "id, title, groups");
    $numtopics = 0;
    $topics = array();
    while ($temp = $data->fetch_array($result))
    {
        $topicgroups = unserialize($temp['groups']);

        if (in_group($topicgroups))
        {
            $topics[] = $temp;
            $numtopics++;
        }
    }    
    
    $article = $data->select_fetch_all_rows($numarticles, "patrol_articles", "WHERE allowed=1 AND trash=0 ORDER BY title ASC"); 
    
    $tpl->assign('numarticles', $numarticles);
    $tpl->assign('article', $article);    
    $tpl->assign('numtopics', $numtopics);
    $tpl->assign('topics', $topics);
    $tpl->assign('teams',$teams);
    $tpl->assign('numteams', $numteams);    
    $tpl->assign('numevents', $numevents);
    $tpl->assign('event', $event);
    $tpl->assign('numalbum', $numalbum);
    $tpl->assign('albums', $albums);
    $scriptList['tinyAdv'] = 1;
}

$validator = true;
$dbpage = true;
$pagename = "addarticle";
?>