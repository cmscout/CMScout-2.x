<?php
/**************************************************************************
    FILENAME        :   admin_patrolart.php
    PURPOSE OF FILE :   Manages articles
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
	$module['Content Management']['Article Manager'] = "patrolart";
    $moduledetails[$modulenumbers]['name'] = "Article Manager";
    $moduledetails[$modulenumbers]['details'] = "Manages articles";
    $moduledetails[$modulenumbers]['access'] = "Allowed to view articles";
    $moduledetails[$modulenumbers]['add'] = "Allowed to add articles and topics";
    $moduledetails[$modulenumbers]['edit'] = "Allowed to edit articles and topics";
    $moduledetails[$modulenumbers]['delete'] = "Allowed to delete articles and topics";
    $moduledetails[$modulenumbers]['publish'] = "Allowed to publish/unpublish articles";
    $moduledetails[$modulenumbers]['limit'] = "Limit user to articles that are from groups they belong to and removes ability to modify topics";
    $moduledetails[$modulenumbers]['id'] = "patrolart";
	return;
}
else
{
    $action = $_GET['action'];
    $id = safesql($_GET['id'], "int");
    if ($action == 'delete' && pageauth("patrolart", "delete")) 
    {
        $sqlq = $data->update_query("patrol_articles", "trash=1", "ID=$id");
        if ($sqlq) 
        { 
            show_admin_message("Article deleted", "$pagename");
        }
    }
    elseif ($action == 'deltopic' && pageauth("patrolart", "delete")) 
    {
        $sqlq = $data->delete_query("articletopics", "id=$id");
        if ($sqlq) 
        { 
            show_admin_message("Article deleted", "$pagename");
        }
    }
    elseif ($action == 'publish' && pageauth("patrolart", "publish")) 
    {
        $sqlq = $data->update_query("patrol_articles", "allowed = 1", "ID=$id", "Admin Articles", "Published $id");
        if ($data->num_rows($data->select_query("review", "WHERE item_id=$id AND type='article'")))
        {        
            $item = $data->select_fetch_one_row("patrol_articles", "WHERE ID=$id");
            email('newitem', array("article", $item));
            $data->delete_query("review", "item_id=$id AND type='article'");
        }
        header("Location: $pagename");
    }
    elseif ($action == 'unpublish' && pageauth("patrolart", "publish")) {
        $sqlq = $data->update_query("patrol_articles", "allowed = 0", "ID=$id", "Admin Articles", "Unpublished $id");
        header("Location: $pagename");
    }
    elseif (($action == 'edit' && pageauth("patrolart", "edit")) || ($action == 'new' && pageauth("patrolart", "add")) ) 
    {
        if ($action == "edit")
        {
            $query = $data->select_query("patrol_articles", "WHERE ID=$id");
            $row = $data->fetch_array($query);
            $row['topics'] = unserialize($row['topics']);
            $row['related'] = unserialize($row['related']);  
            if($row['pic'])
            {
                $photoid = safesql($row['pic'], "int");
                $photo = $data->select_fetch_one_row("photos", "WHERE ID=$photoid", "album_id");
                
                $selectedAlbumInfo['photos'] = $data->select_fetch_all_rows($selectedAlbumInfo['numphotos'], "photos", "WHERE album_id = {$photo['album_id']} AND allowed = 1");
                $tpl->assign("selectedAlbumInfo", $selectedAlbumInfo);
                $tpl->assign("selectedAlbum", $photo['album_id']);
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
        
        if ($action != "edit")
        {
            $article = $data->select_fetch_all_rows($numarticles, "patrol_articles", "WHERE allowed=1 AND trash=0 ORDER BY title ASC");
        }
        else
        {
            $article = $data->select_fetch_all_rows($numarticles, "patrol_articles", "WHERE allowed=1 AND trash=0 AND ID != $id ORDER BY title ASC");
        }
        
        $tpl->assign('numarticles', $numarticles);
        $tpl->assign('article', $article); 
        $tpl->assign('numevents', $numevents);
        $tpl->assign('event', $event);
        $tpl->assign('numalbum', $numalbum);
        $tpl->assign('albums', $albums);

        $teams = array();
        if (pageauth("patrolart", "limit"))
        {
            $patrol = group_sql_list_id("id", "OR", true);
            $team_query = $data->select_query("groups", "WHERE ($patrol) AND ispublic = 1 ORDER BY teamname ASC");
        }
        else
        {
            $team_query = $data->select_query("groups", "WHERE ispublic = 1 ORDER BY teamname ASC");
        }
        $numteams = $data->num_rows($team_query);
        
        while ($teams[] = $data->fetch_array($team_query));
        
        $tpl->assign('teams',$teams);
        $tpl->assign('numteams', $numteams);
        
        $result = $data->select_query("articletopics", "ORDER BY title ASC", "id, title, groups");
        $numtopics = 0;
        while ($temp = $data->fetch_array($result))
        {
            $groups = unserialize($temp['groups']);
            
            $ingroup = in_group($groups);

            $temp['disabled'] = (!$ingroup && pageauth("patrolart", "limit")) ? 1 :0;
        
            $topics[] = $temp;
            $numtopics++;
        }        
        
        $submit=$_POST["Submit"];
        if ($submit == "Submit") 
        {
            $title = safesql($_POST['title'], "text");
            $photo = safesql($_POST['photo'], "int");
            $event = safesql($_POST['event'], "int");
            $story = safesql($_POST['editor'], "text", false);
            $auth = safesql($_POST['auth'], "text");
            $patrol = safesql($_POST['patrol'], "int");
            $topics = $_POST['topics'];
            $pic = safesql($_POST['articlephoto'], "int");

            $result = $data->select_query("articletopics", "ORDER BY title ASC", "id, groups");
            $numtopics = 0;
            while ($temp = $data->fetch_array($result))
            {
                if(in_group(unserialize($temp['groups'])) == false)
                {
                    $topics[$temp['id']] = 1;
                }
            }

            $topics = safesql(serialize($topics), "text");
            $order = safesql($_POST['order'], "int");
            $summary = safesql($_POST['summary'], "text");
            $related = safesql(serialize($_POST['articles']), "text");  
            
            if ($action == "edit")
            {
                $filename = safesql($filename, "text");
                $sql = $data->update_query("patrol_articles", "patrol=$patrol, title=$title, detail=$story, date_post=$timestamp, album_id=$photo, event_id=$event, author=$auth, pic=$pic, topics=$topics, `order`=$order, summary=$summary, related=$related","ID=$id");	
            }
            elseif ($action == "new")
            {
                $filename = safesql($filename, "text");
                $data->insert_query("patrol_articles", "'', $patrol, $pic, $title, $story, $timestamp, $photo, $event, $auth, 1, $topics, $order, $summary, $related, 0");
            }

            if($sql && $action == "edit")
            {
                show_admin_message("Article updated", "$pagename");
            } 	
            elseif ($sql && $action == "new")
            {
                show_admin_message("Article added", "$pagename");
            }            
        }
    
    }
    elseif ($action == "moveitem" && pageauth("patrolart", "edit"))
    {
        if (pageauth("patrolart", "limit"))
        {
            $patrol = group_sql_list_normal("id", "OR", true);
            $sql = $data->select_query("groups", "WHERE ($patrol) AND ispublic = 1 ORDER BY teamname ASC");
        }
        else
        {
            $sql = $data->select_query("groups", "WHERE ispublic = 1 ORDER BY teamname ASC");
        }
        $patrols = array();
        $numpatrols = $data->num_rows($sql);
        while ($patrols[] = $data->fetch_array($sql));
        
        if (!pageauth("patrolart", "limit"))
        {        
            $sql = $data->select_query("subsites", "ORDER BY name ASC");
            $subsites = array();
            $numsubsites = $data->num_rows($sql);
            while ($subsites[] = $data->fetch_array($sql));
        }
        
        $tpl->assign("numpatrols", $numpatrols);
        $tpl->assign("patrols", $patrols);
        $tpl->assign("numsubsites", $numsubsites);
        $tpl->assign("subsites", $subsites);   

        $submit=$_POST["Submit"];
        if ($submit == "Move") 
        {
            $preserve = $_POST['preserve'];
            
            $moveto = $_POST['place'];
            if ($moveto == '0')
            {
                $pid = 0;
                $type = 0;
            }
            else
            {
                $moveto = explode("_", $moveto);
                if ($moveto[0] == "group")
                {
                    $pid = safesql($moveto[1], "int");
                    $type = 1;
                }
                elseif ($moveto[0] == "site")
                {
                    $pid = safesql($moveto[1], "int");
                    $type = 2;                    
                }
            }
            
            $article = $data->select_fetch_one_row("patrol_articles", "WHERE ID=$id");
            
            $friendly = safesql($article['title'], "text");
            $name = safesql(str_replace(" ", "", $article['title']), "text");
            $content = safesql($article['detail'], "text");
            
            $Update = $data->insert_query("static_content", "NULL, $name, $content, $friendly, $type, 0, $pid, 0");
            
            if ($preserve == 1)
            {
                $data->update_query("patrol_articles", "allowed=0", "ID=$id");
            }
            else
            {
                $data->delete_query("patrol_articles", "ID=$id");
            }
            if($Update)
            {
                show_admin_message("Article moved", "$pagename");
            }
        }
    }
    elseif ($action == "edittopic" && pageauth("patrolart", "edit") && !pageauth("patrolart", "limit"))
    {
        $query = $data->select_query("articletopics", "WHERE id=$id");
        $topic = $data->fetch_array($query);
        $topic['groups'] = unserialize($topic['groups']);
        
        $teams = array();
        $team_query = $data->select_query("groups", "ORDER BY teamname ASC", "id, teamname");
        $numteams = $data->num_rows($team_query);
        
        while ($teams[] = $data->fetch_array($team_query));
        
        $tpl->assign('teams',$teams);
        $tpl->assign('numteams', $numteams);
        $tpl->assign('topic', $topic);
        
        $submit=$_POST["Submit"];
        if ($submit == "Submit") 
        {
            $title = safesql($_POST['title'], "text");
            $sort = safesql($_POST['sort'], "text");
            $order = safesql($_POST['order'], "text");
            $display = safesql($_POST['display'], "int");
            $groupallowed = safesql(serialize($_POST['groups']), "text");
            $description = safesql($_POST['description'], "text");
            $perpage = safesql($_POST['perpage'], "int");
            
            $sql = $data->update_query("articletopics", "`title` = $title, `description`=$description, `sort` = $sort, `order` = $order, `groups` = $groupallowed, display=$display, perpage=$perpage", "id = $id");
            if ($sql)
            {
                show_admin_message("Topic updated", "$pagename&activetab=topics");
            }
        }
    }
    elseif ($action == "newtopic" && pageauth("patrolart", "edit") && !pageauth("patrolart", "limit"))
    {       
        $teams = array();
        $team_query = $data->select_query("groups", "ORDER BY teamname ASC", "id, teamname");
        $numteams = $data->num_rows($team_query);
        
        while ($teams[] = $data->fetch_array($team_query));
        
        $tpl->assign('teams',$teams);
        $tpl->assign('numteams', $numteams);
        
        $submit=$_POST["Submit"];
        if ($submit == "Submit") 
        {
            $title = safesql($_POST['title'], "text");
            $sort = safesql($_POST['sort'], "text");
            $order = safesql($_POST['order'], "text");
            $display = safesql($_POST['display'], "int");        
            $groupallowed = safesql(serialize($_POST['groups']), "text");
            $description = safesql($_POST['description'], "text");
            $perpage = safesql($_POST['perpage'], "int");
            
            $sql = $data->insert_query("articletopics", "'', $title, $description, $sort, $order, $groupallowed, $display, $perpage");
            if ($sql)
            {
                show_admin_message("Topic added", "$pagename&activetab=topics");
            }
        }
    }
    else
    {
        $action = "";
    }
    if ($action == "") 
    {
        $row = array();
        if (pageauth("patrolart", "limit")) 
        {
            $patrol = group_sql_list_id("patrol", "OR", true);
            $result = $data->select_query("patrol_articles", "WHERE ($patrol) AND trash=0 ORDER BY date_post DESC");
        } 
        else 
        {
            $result = $data->select_query("patrol_articles", "WHERE trash=0 ORDER BY date_post DESC");
        }
        
        $numarticles = $data->num_rows($result);
        while ($temp = $data->fetch_array($result))
        {
            $sql = $data->select_fetch_one_row("groups", "WHERE id={$temp['patrol']}", "teamname");
            $temp['patrol'] = $sql['teamname'];
            
            $topics = unserialize($temp['topics']);
            $temp['topics'] = '';
            $num = 1;
            if (is_array($topics))
            {
                foreach($topics as $topicid => $value) 
                {
                    $topicdetail = $data->select_fetch_one_row("articletopics", "WHERE id = $topicid", "title");
                    $temp['topics'] .= $topicdetail['title'];
                    if ($num++ < count($topics)) $temp['topics'] .= ", ";
                }
            }
            else
            {
                $temp['topics'] = 'No topics';
            }

            $row[] = $temp;
        }
        
        $result = $data->select_query("articletopics", "ORDER BY `title` ASC", "id, `title`");
        $numtopics = $data->num_rows($result);
        $topics = array();
        while ($topics[] = $data->fetch_array($result));

    }
    
    $filetouse = "admin_patrolart.tpl";
    $tpl->assign('numtopics', $numtopics);
    $tpl->assign('topics', $topics);
    $tpl->assign('numarticles', $numarticles);
    $tpl->assign('detail', $detail);
    $tpl->assign("level", $check['level']);
    $tpl->assign('row', $row);
    $tpl->assign('action', $action);
    $tpl->assign("editor", true);
}
?>