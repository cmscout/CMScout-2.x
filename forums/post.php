<?php
/**************************************************************************
    FILENAME        :   post.php
    PURPOSE OF FILE :   Manages posting new posts and editing of posts
    LAST UPDATED    :   19 July 2006
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

if (isset($_GET['t'])) $tid = safesql($_GET['t'], "int");
if (isset($_GET['p'])) $pid = safesql($_GET['p'], "int");
if (isset($_GET['f'])) $fid = safesql($_GET['f'], "int");

$postaction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $postaction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$tpl->assign("postaction", $postaction);

session_start();

if ($config['registerimage'] && $check['id'] == -1 && !$edit)
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

if(!$edit && !$new && $userauths['reply'])
{
    if ($_POST['submit'] == "Submit" && $word_ok)
    {
        $sql = $data->select_query("forumtopics", "WHERE id=$tid");
        $topic = $data->fetch_array($sql);
        $fid = $topic['forum'];
        $subject = safesql($_POST['subject'], "text");
        $post = safesql($_POST['story'], "text", false);
        $attach = safesql($_POST['attach'], "text");

        $notify = $_POST['notify'];

        $username = safesql($check['id'], "int");

        $sql = $data->insert_query("forumposts", "NULL, $subject, $post, $username, $timestamp, $tid, 0, 0, $attach", "", "", false);
        if ($sql)
        {
            $data->update_query("users", "numposts = numposts + 1", "id=$username");
 
            $post = $data->select_fetch_one_row("forumposts", "WHERE userposted=$username AND dateposted=$timestamp");

            forumEmail("reply", $post, $fid, $tid);
 
            if ($notify == 1 || $notify == 2)
            {
                $sql=$data->select_query("forumstopicwatch", "WHERE topic_id=$tid AND uid=$username");
                if($data->num_rows($sql) == 0)
                {
                    $data->insert_query("forumstopicwatch", "$tid, $username, $notify");
                }
                else
                {
                    $data->update_query("forumstopicwatch", "notify = $notify", "topic_id=$tid AND uid=$username");
                }
            }
            $data->update_query("forums", "lasttopic=$tid, lastpost='{$check['id']}', lastdate=$timestamp", "id=$fid", "", "", false);
            $data->update_query("forumtopics", "lastpost='{$check['id']}', lastdate=$timestamp", "id=$tid", "", "", false);
            
            $data->delete_query("forumnew", "topic=$tid");
            $sql = $data->select_query("users", "", "id");
            while($temp = $data->fetch_array($sql))
            {
                $id = safesql($temp['id'], "text");
                $data->insert_query("forumnew", "NULL, $id, $tid, $fid", "", "", false);
            }

            show_message("Thank you for your post.", "index.php?page=forums&action=topic&t=$tid&late=1&menuid=$menuid");
        }
    }
    elseif($_POST['submit'] == "Submit" && !$word_ok)
    {
	show_message("You entered the CAPTCHA code incorrectly, please try again.", "index.php?page=forums&action=post&t=$tid&menuid=$menuid", true);
    }
    elseif($_POST['preview'] == "Preview Post")
    {
        $post['subject'] = stripslashes($_POST['subject']);
        $post['posttext'] =stripslashes($_POST['story']);
        $post['attachment'] = $_POST['attach'];
        
        $tpl->assign('post', $post);

        $tpl->assign("preview", "true");
    }
  

    $username = safesql($check['id'], "int");
    $sql=$data->select_query("forumstopicwatch", "WHERE topic_id=$tid AND uid=$username");
    $watch = $data->fetch_array($sql);
    
    $sql = $data->select_query("forumtopics", "WHERE id=$tid");
    $topic = $data->fetch_array($sql);
    $topic['subject'] = censor($topic['subject']);
    $location = "Replying to " . $topic['subject'];
    
    $sql = $data->select_query("forums", "WHERE id={$topic['forum']}");
    $forum = $data->fetch_array($sql);
    if ($forum['parent'] != 0)
    {
        $sql = $data->select_query("forums", "WHERE id={$forum['parent']}");
        $parentforum = $data->fetch_array($sql);
        $tpl->assign("issubforum", 1);
        $tpl->assign("parentforum", $parentforum);
    }    
    $sql = $data->select_query("forumposts", "WHERE topic=$tid");
    $numposts = $data->num_rows($sql);
    
    if ($pid != "" && $_POST['preview'] != "Preview Post")
    {
        $quote = $data->select_fetch_one_row("forumposts", "WHERE id=$pid");
	    $quote['userposted'] = $userIdList[$quote['userposted']];
        $quote['posttext'] = preg_replace("/\[quote.*?\[\/quote\]/ism", "", $quote['posttext']);
        $quote['posttext'] = str_replace(array("<p></p>", "<p>&nbsp;</p>"), array("", ""), $quote['posttext']);
        $post['posttext'] = "[quote={$quote['userposted']}]" . $quote['posttext'] . "[/quote]";
        $tpl->assign("post", $post);
    }
    
    $limit = $config['numpage'];
    $sql = $data->select_query("forumposts", "WHERE topic=$tid ORDER BY dateposted DESC LIMIT 0, $limit");
    $numposts = $data->num_rows($sql);
    $posts = array();
    while($temp = $data->fetch_array($sql))
    {
        $temp['userposted'] = $userIdList[$temp['userposted']];
	    $temp['posttext'] = censor($temp['posttext']);
        $posts[] = $temp;
    }
    
    $tpl->assign("watch", $watch);
    $tpl->assign("posts", $posts);
    $tpl->assign("topic", $topic);
    $tpl->assign("numposts", $numposts);
    $tpl->assign("forum", $forum);
}
elseif ($new && !$edit && $userauths['new'])
{    
    if ($_POST['submit'] == "Submit" && $word_ok)
    {
        $subject = safesql($_POST['subject'], "text");
        $desc = safesql($_POST['desc'], "text");
        $post = safesql($_POST['story'], "text", false);
        $type = isset($_POST['type']) ? $_POST['type'] : 0;
        $attach = safesql($_POST['attach'], "text");
        $number = $_POST['numoptions'];
        $notify = $_POST['notify'];
        
        $pollq = safesql($_POST['question'], "text");
        $pollopt = $_POST['option'];
        
        $username = safesql($check['id'], "text");
        
        $sql = $data->insert_query("forumtopics", "NULL, $subject, $desc ,0 , $username, $timestamp,$username, $timestamp, $type, $fid, 0");
        if ($sql)
        {
            $sql = $data->select_query("forumtopics", "WHERE subject=$subject AND numviews=0");
            $topic = $data->fetch_array($sql);
            
            $sql = $data->insert_query("forumposts", "NULL, $subject, $post, $username, $timestamp, {$topic['id']}, 0, 0, $attach");
            if ($sql)
            {
                $data->update_query("users", "numposts = numposts + 1, numtopics = numtopics + 1", "id='{$check['id']}'");
                
                if ($pollq != "" && $number > 1)
                {
                    $sql = $data->insert_query("forumpolls", "NULL, {$topic['id']}, $pollq");
                    if ($sql)
                    {
                        $sql = $data->select_query("forumpolls", "WHERE topic_id = {$topic['id']} AND question = $pollq", "id");
                        $poll = $data->fetch_array($sql);
                        for($i=1;$i<=$number;$i++)
                        {
                            $data->insert_query("forumpollitems", "NULL, {$poll['id']}, {$pollopt[$i]}, $i, 0");
                        }
                    }
                }
                
                $post = $data->select_fetch_one_row("forumposts", "WHERE userposted={$check['id']} AND dateposted=$timestamp");

                forumEmail("newtopic", $post, $fid, $topic['id']);
                
                if ($notify == 1 || $notify == 2)
                {
                    $sql=$data->select_query("forumstopicwatch", "WHERE topic_id={$topic['id']} AND uid=$username");
                    if($data->num_rows($sql) == 0)
                    {
                        $data->insert_query("forumstopicwatch", "{$topic['id']}, $username, $notify", "", "", false);
                    }
                    else
                    {
                        $data->update_query("forumstopicwatch", "notify = $notify", "topic_id={$topic['id']} AND uid=$username", "", "", false);
                    }
                }
            
                $data->update_query("forums", "lasttopic={$topic['id']}, lastpost='{$check['id']}', lastdate=$timestamp", "id=$fid");             

                $sql = $data->select_query("users", "", "id");
                while($temp = $data->fetch_array($sql))
                {
                    if ($temp['id'] != $check['id'])
                    {
                        $id = safesql($temp['id'], "text");
                        $data->insert_query("forumnew", "NULL, $id, {$topic['id']}, $fid", "", "", false);
                    }
                }
                show_message("Thank you for your post.", "index.php?page=forums&action=topic&t={$topic['id']}&menuid=$menuid");
            }
        }
    }
    elseif($_POST['submit'] == "Submit" && !$word_ok)
    {
	show_message("You entered the CAPTCHA code incorrectly, please try again.", "index.php?page=forums&action=new&f=$fid&menuid=$menuid", true);
    }
    elseif($_POST['preview'] == "Preview Post")
    {
        $post['subject'] = stripslashes($_POST['subject']);
        $post['desc'] = stripslashes($_POST['desc']);
        $post['posttext'] =stripslashes($_POST['story']);
        $topic['type'] = $_POST['type'];
        $post['attachment'] = $_POST['attach'];
        
        $number = $_POST['numoptions'];
        
        $pollq = $_POST['question'];
        $pollopt = $_POST['option'];

        $tpl->assign('post', $post);
        $tpl->assign('topic', $topic);
        $tpl->assign("numoptions", $number);
        $tpl->assign("pollq", $pollq);
        $tpl->assign("pollopts", $pollopt);
        $tpl->assign("preview", "true");        
    }
    else
    {
        $tpl->assign("numopts", 1);
    }
    $sql = $data->select_query("forums", "WHERE id=$fid");
    $forum = $data->fetch_array($sql);
    $location = "New Topic";
    if ($forum['parent'] != 0)
    {
        $sql = $data->select_query("forums", "WHERE id={$forum['parent']}");
        $parentforum = $data->fetch_array($sql);
        $tpl->assign("issubforum", 1);
        $tpl->assign("parentforum", $parentforum);
    }        
    $tpl->assign("forum", $forum);
    $tpl->assign("new", $new);
}
elseif (!$new && $edit && $userauths['edit'])
{    
    if ($_POST['submit'] == "Submit")
    {       
        $subject = safesql($_POST['subject'], "text");
        $post = safesql($_POST['story'], "text", false);
        $type = safesql($_POST['type'], "text");
        
        $sql = $data->select_query("forumposts", "WHERE id=$pid");
        $posts = $data->fetch_array($sql);
       
        $sql = $data->select_query("forumposts", "WHERE topic={$posts['topic']} ORDER BY dateposted ASC");
        $first = $data->fetch_array($sql);
        $attach = safesql($_POST['attach'], "text");
        
        if ($first['dateposted'] == $posts['dateposted'])
        {
            $firstpost = 1;
        }
        else
        {
            $firstpost = 0;
        }
        
        if ($firstpost == 1)
        {
            $data->update_query("forumtopics", "type=$type", "id={$posts['topic']}");
        }
        $sql = $data->update_query("forumposts", "subject = $subject, posttext = $post, edittime=$timestamp, edituser='{$check['id']}', attachment=$attach", "id=$pid", "", "", false);
        if ($sql)
        {
            $sql = $data->select_query("forumposts", "WHERE id=$pid");
            $post = $data->fetch_array($sql);
            show_message("Thank you for your edit", "index.php?page=forums&action=topic&t={$post['topic']}&menuid=$menuid");
        }
    }
    elseif($_POST['preview'] == "Preview Post")
    {
        $post['subject'] = $_POST['subject'];
        $post['desc'] = $_POST['desc'];
        $post['posttext'] = $_POST['story'];
        $topic['type'] = $_POST['type'];
        $post['attachment'] = $_POST['attach'];
        
        $tpl->assign('post', $post);
        $tpl->assign('topic', $topic);
        $tpl->assign("preview", "true"); 
    }

    $sql = $data->select_query("forumposts", "WHERE id=$pid");
    $post = $data->fetch_array($sql);
    $post['posttext'] = htmlspecialchars($post['posttext']);
    $sql = $data->select_query("forumposts", "WHERE topic={$post['topic']} ORDER BY dateposted ASC");
    $first = $data->fetch_array($sql);
    $location = "Edit Post";
    
    if ($first['dateposted'] == $post['dateposted'])
    {
        $tpl->assign('firstpost', 1);
    }
    else
    {
        $tpl->assign('firstpost', 0);            
    }
    if ($_POST['preview'] != "Preview Post")
    {
        $tpl->assign("post", $post);
    }
    
    $tpl->assign("edit", $edit);                
    $sql = $data->select_query("forumtopics", "WHERE id={$post['topic']}");
    $topic = $data->fetch_array($sql);
    $tpl->assign("topic", $topic);
    $sql = $data->select_query("forums", "WHERE id={$topic['forum']}");
    $forum = $data->fetch_array($sql);
    if ($forum['parent'] != 0)
    {
        $sql = $data->select_query("forums", "WHERE id={$forum['parent']}");
        $parentforum = $data->fetch_array($sql);
        $tpl->assign("issubforum", 1);
        $tpl->assign("parentforum", $parentforum);
    }
    $tpl->assign("forum", $forum);
}

    $uname = $check['uname'];
    $grouplist = group_sql_list_id("owner_id", "OR", true);

    $sql = $data->select_query("album_track", "WHERE trash=0 ORDER BY album_name ASC");
    $numalbums = 0;
    $album = array();
    while ($temp = $data->fetch_array($sql))
    {
        $sql2 = $data->select_query("owners", "WHERE item_id={$temp['ID']} AND item_type='album' AND ((owner_id={$check['id']} AND owner_type=0) OR ($grouplist AND owner_type=1))");
        $temp2 = $data->fetch_array($sql2);
        if (($data->num_rows($sql2) > 0 && ($temp2['expire'] >= $timestamp || $temp2['expire'] == 0)))
        {
            $numalbums++;
            $album[] = $temp;
        }
    }

    $sql = $data->select_query("patrol_articles", "WHERE trash=0 ORDER BY title ASC");
    $numart = 0;
    $articles = array();
    while ($temp = $data->fetch_array($sql))
    {
        $sql2 = $data->select_query("owners", "WHERE item_id={$temp['ID']} AND item_type='articles' AND ((owner_id={$check['id']} AND owner_type=0) OR ($grouplist AND owner_type=1))");
        $temp2 = $data->fetch_array($sql2);
        if (($data->num_rows($sql2) > 0 && ($temp2['expire'] >= $timestamp || $temp2['expire'] == 0)))
        {
            $numart++;
            $articles[] = $temp;
        }
    }

    $sql = $data->select_query("calendar_items", "WHERE trash=0 ORDER BY summary ASC");
    $numevents = 0;
    $events = array();
    while ($temp = $data->fetch_array($sql))
    {
        $sql2 = $data->select_query("owners", "WHERE item_id={$temp['id']} AND item_type='events' AND ((owner_id={$check['id']} AND owner_type=0) OR ($grouplist AND owner_type=1))");
        $temp2 = $data->fetch_array($sql2);
        if (($data->num_rows($sql2) > 0 && ($temp2['expire'] >= $timestamp || $temp2['expire'] == 0)))
        {
            $numevents++;
            $events[] = $temp;
        }
    }

    $sql = $data->select_query("downloads", "WHERE trash=0 ORDER BY name ASC");
    $numdown = 0;
    $downloads = array();
    while ($temp = $data->fetch_array($sql))
    {
        $sql2 = $data->select_query("owners", "WHERE item_id={$temp['id']} AND item_type='downloads' AND ((owner_id={$check['id']} AND owner_type=0) OR ($grouplist AND owner_type=1))");
        $temp2 = $data->fetch_array($sql2);
        if (($data->num_rows($sql2) > 0 && ($temp2['expire'] >= $timestamp || $temp2['expire'] == 0)))
        {
            $numdown++;
            $downloads[] = $temp;
        }
    }

    $sql = $data->select_query("newscontent", "WHERE trash=0 ORDER BY title ASC");
    $numnews = 0;
    $newsitems = array();
    while ($temp = $data->fetch_array($sql))
    {
        $sql2 = $data->select_query("owners", "WHERE item_id={$temp['id']} AND item_type='newsitem' AND ((owner_id={$check['id']} AND owner_type=0) OR ($grouplist AND owner_type=1))");
        $temp2 = $data->fetch_array($sql2);
        if (($data->num_rows($sql2) > 0 && ($temp2['expire'] >= $timestamp || $temp2['expire'] == 0)))
        {
            $numnews++;
            $newsitems[] = $temp;
        }
    }

    $tpl->assign("numalbums", $numalbums);
    $tpl->assign("album", $album);
    
    $tpl->assign("numarticles", $numart);
    $tpl->assign("article", $articles);
    
    $tpl->assign("numevents", $numevents);
    $tpl->assign("event", $events);
    
    $tpl->assign("numdownloads", $numdown);
    $tpl->assign("download", $downloads);
    
    $tpl->assign("numnews", $numnews);
    $tpl->assign("news", $newsitems);

$tpl->assign("guest", $check['id'] == -1);
$scriptList['tinySimp'] = 1;
$tpl->assign("isforumpost", true);
$pagenum = 4;
?>