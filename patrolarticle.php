<?php
/**************************************************************************
    FILENAME        :   patrolarticle.php
    PURPOSE OF FILE :   Displays articles. 
    LAST UPDATED    :   18 July 2006
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
    
$patrol = isset($_GET['patrol']) ? $_GET['patrol'] : "";
$patrolpage = false;
if ($patrol != "")
{
    $temp = safesql($patrol, "int");
    $sql = $data->select_query("groups", "WHERE id=$temp", "id, teamname");
    if ($data->num_rows($sql) > 0)
    {
        $temp = $data->fetch_array($sql);
        $patrol = safesql($temp['teamname'], "text");
        $patrol1 = $temp['teamname'];
        $patrolid = $temp['id'];
    }
    else
    {
        $patrol = safesql($patrol, "text");
        $sql = $data->select_query("groups", "WHERE teamname=$patrol", "id");
        if ($data->num_rows($sql) > 0)
        {
            $temp = $data->fetch_array($sql);
            $patrol1 = $temp['teamname'];
            $patrolid = $temp['id'];
        }
    }
    $patrolpage = true;
}
$tpl->assign("patrolpage", $patrolpage);

$location = $patrol1 != "" ? "$patrol1 Articles" : "General Articles";

$id = safesql($_GET['id'], "int");
$tid = safesql($_GET['tid'], "int");
$action = $_GET['action'];

if ($action == '' || ($action == "view" && $id == 'NULL') || ($action == "topic" && $tid == 'NULL')) 
{
	$mode = 'topiclist';
    $patrolarts = array();
    
	$currentPage = $_SERVER["PHP_SELF"];
    if (isset($patrolid) && $patrolid != '')
    {
        $sql = "WHERE patrol = $patrolid AND allowed = 1 AND trash=0";
    }
    elseif ($config['articledisplay'] == 0)
    {
        $sql = "WHERE patrol = 0 AND allowed = 1 AND trash=0";    
    }
    else
    {
        $sql = "WHERE allowed = 1 AND trash=0";
    }

    $topics = $data->select_fetch_all_rows($numtopics, "articletopics", "ORDER BY title ASC", "id, title, description");

    $sql = $data->select_query("patrol_articles", $sql . " ORDER BY date_post DESC");
    $numarts = 0;
	while($temp = $data->fetch_array($sql))
    {
        $tempTopics = unserialize($temp['topics']);
        for($i=0;$i<$numtopics;$i++)
        {
            $topics[$i]['number'] = $topics[$i]['number'] > 0 ? $topics[$i]['number'] : 0;
            if ($tempTopics[$topics[$i]['id']] == 1)
            {
                $topics[$i]['number']++;
            }
        }
        if ($temp['topics'] == "N;" || $temp['topics'] == "")
        {
            $temp['title'] = censor($temp['title']);
            $patrolarts[] = $temp;
            $numarts++;
        }
    }   
    
    $tpl->assign("topics", $topics);
    $tpl->assign("numtopics", $numtopics);
    
    $add = get_auth('addarticle');
    $addlink = "index.php?page=addarticle&amp;menuid=$menuid";

    $rssuname = safesql(md5($check['uname']), "text");
    if ($patrol != "")
    {
        if ($data->num_rows($data->select_query("rssfeeds", "WHERE itemid=$patrolid AND type=5 AND uname=$rssuname", "id")))
        {
            $rss = 1;
        }
        else
        {
            $rss = 0;
        }    
    }
    else
    {
        if ($data->num_rows($data->select_query("rssfeeds", "WHERE itemid=0 AND type=4 AND uname=$rssuname", "id")))
        {
            $rss = 1;
        }
        else
        {
            $rss = 0;
        } 
    }
    $tpl->assign("rss", $rss);
    $tpl->assign("numarts", $numarts);
    $tpl->assign("patrolarts", $patrolarts);
    $tpl->assign("art", $art);
    
} 
elseif ($action == "view" && $id != 'NULL')
{
    $mode = "viewarticle";
    $highlight = unserialize(stripslashes(html_entity_decode($_GET['highlight'])));

    $editFormAction = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING'])) 
    {
      $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
    }
    $tpl->assign('editFormAction', $editFormAction);

	$showarticle = 'yes';
    $sql = $data->select_query("patrol_articles", "WHERE ID=$id AND allowed=1 AND trash=0");
    $article = $data->fetch_array($sql);
    
    $edit = is_owner($article['ID'], "articles") ? true : false;
    $editlink = "index.php?page=mythings&amp;cat=articles&amp;action=edit&amp;id={$article['ID']}&amp;menuid=$menuid";

    
	if ($article['album_id'] != 0) 
    { 
        $photo = $data->select_fetch_all_rows($number_of_photos, "photos", "WHERE album_id={$article['album_id']}");
        $scriptList['gallery'] = 1;
        $tpl->assign("photo", $photo);
        $tpl->assign("number_of_photos", $number_of_photos);
	} 

	if (isset($article['event_id'])) 
    { 
        $event = $data->select_fetch_one_row("calendar_items", "WHERE id = {$article['event_id']} AND trash=0", "id, summary, startdate, enddate");
        $tpl->assign("event", $event);
    } 

    $article['relatedlist'] = '';
    $article['related'] = unserialize($article['related']);
    $num = 1;
    $topicid = $_GET['tid'];
    if (is_array($article['related']))
    {
        foreach($article['related'] as $articleid => $value) 
        {
            $topicdetail = $data->select_fetch_one_row("patrol_articles", "WHERE ID = $articleid", "title");
            if ($topicid)
            {
                if ($patrolid)
                {
                    $article['relatedlist'] .= "<a href=\"index.php?page=patrolpages&amp;patrol={$patrolid}&amp;content=patrolarticle&amp;id={$articleid}&amp;tid={$topicid}&amp;menuid={$menuid}&amp;action=view\">" . $topicdetail['title'] . "</a>";
                }
                else
                {
                    $article['relatedlist'] .= "<a href=\"index.php?page=patrolarticle&amp;id={$articleid}&amp;tid={$topicid}&amp;menuid={$menuid}&amp;action=view\">" . $topicdetail['title'] . "</a>";
                }
            }
            else
            {
                if ($patrolid)
                {
                    $article['relatedlist'] .= "<a href=\"index.php?page=patrolpages&amp;patrol={$patrolid}&amp;content=patrolarticle&amp;id={$articleid}&amp;menuid={$menuid}&amp;action=view\">" . $topicdetail['title'] . "</a>";
                }
                else
                {
                    $article['relatedlist'] .= "<a href=\"index.php?page=patrolarticle&amp;id={$articleid}&amp;menuid={$menuid}&amp;action=view\">" . $topicdetail['title'] . "</a>";
                }
            }
            if ($num++ < count($article['related'])) $article['relatedlist'] .= ", ";
        }
    }

    if (isset($_POST['submit']) && $_POST['submit'] == "Post Comment")
    {
        $comment = safesql(strip_tags($_POST['comment']), "text");
        if ($config['confirmcomment'] == 1 && !($check['level'] == 0 || $check['level'] == 1)) $allowed = 0;
        else $allowed = 1;
        $timestamp = time();
        $data->insert_query("comments", "'', $id, '{$check['id']}', 0, $timestamp, $comment, $allowed", "", "", false);
        
        if (confirm('comment'))
        {
            $page = $_SERVER['PHP_SELF'];
            if (isset($_SERVER['QUERY_STRING'])) 
            {
                $page .= "?" . $_SERVER['QUERY_STRING'];
            }
            $comment = $data->select_fetch_one_row("comments", "WHERE uid='{$check['id']}' AND item_id=$id AND date=$timestamp");
            confirmMail("comment", $comment);
            show_message("The comment first needs to be reviewed before it will be visible", $currentPage);
        }
        show_message("Thank you for your comment.", $currentPage);
    }
     
    $sql = $data->select_query("comments", "WHERE item_id=$id AND type=0 AND allowed = 1");
    $numcom = $data->num_rows($sql);
    $comments = array();
    while ($temp = $data->fetch_array($sql))
    {
        $temp['comment'] = censor($temp['comment']);
        $temp2 = $data->select_fetch_one_row("users", "WHERE id={$temp['uid']}", "uname");
        $temp['uname'] = $temp2['uname'];
        $comments[] = $temp;
    }

    $article['detail'] = censor($article['detail']);
    $article['title'] = censor($article['title']);
    
    $location = $article['title'];
    $tpl->assign("numcom", $numcom);
    $tpl->assign("com", $comments);
    $tpl->assign("comviewallowed", get_auth('comviewallowed', 2));
    $tpl->assign("compostallowed", get_auth('compostallowed', 2));
    $tpl->assign("article", $article);
    $tpl->assign("topicid", $tid);
}
elseif ($action == "topic" && $tid != 'NULL')
{
    $mode = "viewtopic";
    
    $topic = $data->select_fetch_one_row("articletopics", "WHERE id=$tid");
    
    if (isset($patrolid) && $patrolid != '')
    {
        $sql = $data->select_query("patrol_articles", "WHERE patrol = $patrolid AND topics <> 'N;' AND topics <> '' ORDER BY {$topic['sort']} {$topic['order']}");
    }
    else
    {
        $sql = $data->select_query("patrol_articles", "WHERE topics <> 'N;' AND topics <> '' ORDER BY {$topic['sort']} {$topic['order']}");
    }
    $articles = array();
    $numarticles = 0;
    $numon = 0;
    $numbertodisplay = $topic['perpage'];
    $start = isset($_GET['start']) ? $_GET['start'] : 0 ;
    while ($temp = $data->fetch_array($sql))
    {
        $topics = unserialize($temp['topics']);

        if ($topics[$topic['id']] == 1)
        {
            if ($numon >= $start && $numon < ($numbertodisplay+$start))
            {
                if ($topic['display'] == 3 || $topic['display'] == 4)
                {
                    if ($temp['pic'] != 0)
                    {
                        $temp['showpic'] = $temp['pic'];
                    }
                    elseif ($temp['album_id'])
                    {
                        if ($data->num_rows($data->select_query("photos", "WHERE album_id = '{$temp['album_id']}'")) > 0)
                        {
                            $sql2 = $data->select_query("photos", "WHERE album_id = '{$temp['album_id']}'");
                            $tempphoto = array();
                            while($tempphoto[] = $data->fetch_array($sql2));
                            $number = rand(0, $data->num_rows($sql2)-1);
                            $temp['showpic'] = $tempphoto[$number]['ID'];
                        }
                    }
                    else
                    {
                        $temp['showpic'] = 0;
                    }
                }
                $articles[] = $temp;
                $numarticles++;
            }
            $numon++;
        }
    }
    
    //Pagenation working out
    if ($numon > 0) 
    {
        $num_pages = ceil($numon / $numbertodisplay);
        $curr_page = floor($start/$numbertodisplay) + 1;
        if ($curr_page < $num_pages)
        {
            $next = true; 
            $next_start=(($curr_page-1)*$numbertodisplay) + $numbertodisplay;
        }
        if ($curr_page > 1) 
        {
            $prev = true; 
            $prev_start=(($curr_page-1)*$numbertodisplay)- $numbertodisplay;
        }
    }
    
    $tpl->assign('num_per_page', $numbertodisplay);
    $tpl->assign('numpages', $num_pages);
    $tpl->assign('currentpage', $curr_page);
    $tpl->assign('next_start', $next_start);
    $tpl->assign('prev_start', $prev_start);
    $tpl->assign('next', $next);
    $tpl->assign('prev', $prev);    
    $tpl->assign("articles", $articles);
    $tpl->assign("numarticles", $numarticles);
    
    $tpl->assign("topic", $topic);
}
$tpl->assign("mode", $mode);
$tpl->assign("patrol", $patrol1);

$dbpage = true;
$pagename = "patrolarticle";
?>