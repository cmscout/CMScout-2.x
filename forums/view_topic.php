<?php
/**************************************************************************
    FILENAME        :   view_topic.php
    PURPOSE OF FILE :   Allows viewing of a topic
    LAST UPDATED    :   5 June 2006
    COPYRIGHT       :   © 2006 CMScout Group
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

$postaction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) 
{
  $postaction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$tpl->assign("postaction", $postaction);

$tid = safesql($_GET['t'], "int");
$latest = isset($_GET['late']) ?$_GET['late'] : 0;

if ($tid != 'NULL')
{
    if ($_POST['vote'] == "Vote")
    {
        $item = $_POST['poll'];
        
        $sql = $data->select_query("forumpollitems", "WHERE id=$item");
        $poll = $data->fetch_array($sql);
        
        $sql = $data->select_query("forumpollvoters", "WHERE (user_id = '{$check['id']}' OR ip = '{$_SERVER['REMOTE_ADDR']}') AND poll_id={$poll['poll_id']}");
        if ($data->num_rows($sql) == 0)
        {
            $data->update_query("forumpollitems", "results=results + 1", "id=$item");
            $data->insert_query("forumpollvoters", "{$poll['poll_id']}, {$check['id']}, '{$_SERVER['REMOTE_ADDR']}'");
            echo "<script> alert('Your vote has been counted'); window.location = 'index.php?page=forums&action=topic&t=$tid';</script>\n";
            exit;
        }
    }

    $limit = $config['numpage'];
    $start = $_GET['start'] > 0 ? safesql($_GET['start'], "int") : 0;
       
    $sql = $data->select_query("forumtopics", "WHERE id=$tid");
    $topic = $data->fetch_array($sql);
    $topic['subject'] = censor($topic['subject']);
    $location = $topic['subject'];

    $sql = $data->select_query("forums", "WHERE id={$topic['forum']}");
    $forum = $data->fetch_array($sql);

    if ($forum['parent'] != 0)
    {
        $sql = $data->select_query("forums", "WHERE id={$forum['parent']}");
        $parentforum = $data->fetch_array($sql);
        $tpl->assign("issubforum", 1);
        $tpl->assign("parentforum", $parentforum);
    }

    $sql = $data->select_query("forumposts", "WHERE topic=$tid", "id");
    $numposts = $data->num_rows($sql);

    //Pagenation working out
    if ($numposts > 0) 
    {
        $num_pages = ceil($numposts / $limit);

        if ($start == 0 && $latest == 1)
        {
            $curr_page = $num_pages;
            $start = (($curr_page-1)*$limit);
        }
        else
        {
            $curr_page = floor($start/$limit) + 1;
        }
        
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

    $pagelimit = ($numposts-$start) <= $limit ? ($numposts-$start) : $limit;
    $sql = $data->select_query("forumposts", "WHERE topic=$tid ORDER BY dateposted ASC LIMIT $start, $pagelimit");
    $posts = array();
    while($temp = $data->fetch_array($sql))
    {
       $attachmentTemp = NULL;
       $attachment = NULL;
        $sql2 = $data->select_query("users", "WHERE id='{$temp['userposted']}'", "uname,avyfile, sig, numposts, publicprofile, numtopics");
        $temp2 = $data->fetch_array($sql2);
        $temp['useravy'] = $temp2['avyfile'];
        $temp['sig'] = censor($temp2['sig']);
        $temp['numposts'] = $temp2['numposts'];
        $temp['numtopics'] = $temp2['numtopics'];
        $temp['posttext'] = censor($temp['posttext']);
        $temp['subject'] = censor($temp['subject']);
        $temp['userpostedname'] = get_username($temp['userposted']);
        $temp['userstatus'] = user_online($temp['userpostedname']);

        if ($temp['edituser'])
        {
            $editinfo = $data->select_fetch_one_row("users", "WHERE id={$temp['edituser']}", "uname");
            $temp['editusername'] = $temp['edituser'] != -1 ? $editinfo['uname'] : "Guest";
        }
        $attachmentTemp = explode('.', $temp['attachment']);
        $attachId = safesql($attachmentTemp[0], "int");
        switch($attachmentTemp[1])
        {
            case 'article':
                $temp2 = $data->select_fetch_one_row("patrol_articles", "WHERE ID=$attachId", "ID, title");
                $attachment['name'] = $temp2['title'];
                $attachment['link'] = "index.php?page=patrolarticle&id={$attachId}&menuid={$menuid}&action=view";
                $attachment['type'] = "Article";
                break;
            case 'album':
                $temp2 = $data->select_fetch_one_row("album_track", "WHERE ID=$attachId", "ID, album_name");
                $attachment['name'] = $temp2['album_name'];
                $attachment['link'] = "index.php?page=photos&album={$attachId}&menuid={$menuid}";
                $attachment['type'] = "Photo Album";
                break;
            case 'event':
                $temp2 = $data->select_fetch_one_row("calendar_items", "WHERE id=$attachId", "id, summary");
                $attachment['name'] = $temp2['summary'];
                $attachment['link'] = "index.php?page=calender&item={$attachId}&menuid={$menuid}";
                $attachment['type'] = "Event";
                break;
            case 'download':
                $temp2 = $data->select_fetch_one_row("downloads", "WHERE id=$attachId", "id, name, cat");
                $attachment['name'] = $temp2['name'];
                $attachment['link'] = "index.php?page=downloads&id={$attachId}&action=down&catid={$temp2['cat']}&menuid={$menuid}";
                $attachment['type'] = "Download";
                break;
            case 'news':
                $temp2 = $data->select_fetch_one_row("newscontent", "WHERE id=$attachId", "id, title");
                $attachment['name'] = $temp2['title'];
                $attachment['link'] = "index.php?page=news&id={$attachId}&menuid={$menuid}";
                $attachment['type'] = "News";
                break;
        }
        $temp['attachment'] = $attachment;
        $posts[] = $temp;
    }

    $data->delete_query("forumnew", "uid='{$check['id']}' AND topic=$tid", "", "", false);

    if ($check['bot'] == 0)
    {
        $data->update_query("forumtopics", "numviews = numviews + 1", "id=$tid", "", "", false);
    }

    $sql = $data->select_query("forumpolls", "WHERE topic_id=$tid");

    if ($data->num_rows($sql) == 1)
    {
        $poll = $data->fetch_array($sql);
        
        $sql = $data->select_query("forumpollitems", "WHERE poll_id={$poll['id']} ORDER BY pos ASC");
        $numitems = $data->num_rows($sql);
        $pollitem = array();
        $total = 0;
        
        while($temp = $data->fetch_array($sql))
        {
            $total += $temp['results'];
            $temp['value'] = censor($temp['value']);
            $pollitem[] = $temp;
        }
        
        $sql = $data->select_query("forumpollvoters", "WHERE (user_id = '{$check['id']}' OR ip = '{$_SERVER['REMOTE_ADDR']}') AND poll_id={$poll['id']}");
        if ($data->num_rows($sql) > 0 || $check['id'] == -1)
        {
            $tpl->assign('voted', true);
        }
        else
        {
            $tpl->assign('voted', false);
        }
        
        $tpl->assign('pollq', censor($poll['question']));
        $tpl->assign('numitems', $numitems);
        $tpl->assign('pollitem', $pollitem);
        $tpl->assign('totalvotes', $total);
        $view = $_GET['view'];
        
        if ($view == 1)
        {
            $tpl->assign('showresult', true);
        }
        else
        {
            $tpl->assign('showresult', false);
        }
    }

    if (isset($num_pages)) $tpl->assign('numpages', $num_pages);
    if (isset($curr_page)) $tpl->assign('currentpage', $curr_page);
    if (isset($next))$tpl->assign('next', $next);
    if (isset($prev))$tpl->assign('prev', $prev);
    $tpl->assign('num_per_page', $limit);
    if (isset($next_start)) $tpl->assign('next_start', $next_start);
    if (isset($prev_start)) $tpl->assign('prev_start', $prev_start);
    $tpl->assign("start", $_GET['start']);
    $tpl->assign("posts", $posts);
    $tpl->assign("topic", $topic);
    $tpl->assign("numposts", $numposts);
    $tpl->assign("limit", $pagelimit);
    $tpl->assign("forum", $forum);
    $pagenum = 3;
}
else
{
    show_message("Topic not found", "index.php?page=forums");
}
?>