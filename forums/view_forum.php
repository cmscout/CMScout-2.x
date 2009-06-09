<?php
/**************************************************************************
    FILENAME        :   view_forum.php
    PURPOSE OF FILE :   Displays main forum view, and seperate forum views
    LAST UPDATED    :   19 July 2006
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

if (isset($_GET['f'])) $fid = safesql($_GET['f'], "int");

//If forum number is empty, get all forums, else only forum that were looking at
if (empty($_GET['f']))
{
    $location = "Forums";
    $sql = $data->select_query("forumscats", "ORDER BY pos ASC");
    $numcats = $data->num_rows($sql);
    $cats = array();
    while($temp = $data->fetch_array($sql))
    {
        $sqls = $data->select_query("forums", "WHERE cat={$temp['id']} AND parent=0 ORDER BY pos ASC");
        $temp['numforums'] = $data->num_rows($sqls);
        $temp['forums'] = array();
        while($temp3 = $data->fetch_array($sqls))
        {
            $sql2 = $data->select_query("forums", "WHERE parent={$temp3['id']} ORDER BY pos ASC", "id, name, `limit`");
            $numsubs = $data->num_rows($sql2);
            if ($numsubs > 0)
            {
                $subs = array();
                while ($subtemp = $data->fetch_array($sql2))
                {
                    $sql3 = $data->select_query("forumauths", "WHERE forum_id={$subtemp['id']}");
                    $auth = $data->fetch_array($sql3);
                    
                    $view_forum = unserialize($auth['view_forum']);
                
                    if ($check['id'] != "-1")
                    {
                        $usergroups = user_groups_id_array($check['id']);
                    }
                    else
                    {
                        $usergroups = array(0 => "-1");
                    }
                    $viewauth = 0;
 
                    for($i=0;$i<count($usergroups);$i++)
                    {                
                        $viewauth = $viewauth || $view_forum[$usergroups[$i]];
                    }
                    
                    $info = $data->select_fetch_one_row("users", "WHERE id='{$check['id']}'", "numposts");
                    if ($info['numposts'] < $subtemp['limit'] && $check['id'] != "-1")
                    {
                        $viewauth = 0;
                    }

                    if($viewauth == 1)
                    {                    
                        $subs[] = $subtemp;
                    }
                    else
                    {
                        $numsubs--;
                    }
                }
                $temp3['subforums'] = $subs;
            }
            $temp3['numsubs'] = $numsubs;
            
            $sql2 = $data->select_query("forumauths", "WHERE forum_id={$temp3['id']}");
            $auth = $data->fetch_array($sql2);
            
            $view_forum = unserialize($auth['view_forum']);
            $read_topics = unserialize($auth['read_topics']);
            
            if ($check['id'] != "-1")
            {
                $usergroups = user_groups_id_array($check['id']);
            }
            else
            {
                $usergroups = array(0 => "-1");
            }
            $viewauth = 0;
            $readauth = 0;
            for($i=0;$i<count($usergroups);$i++)
            {                
                $viewauth = $viewauth || $view_forum[$usergroups[$i]];
                $readauth = $readauth || $read_topics[$usergroups[$i]];
            }

            $info = $check['id'] != "-1" ? $data->select_fetch_one_row("users", "WHERE id='{$check['id']}'", "uname, numposts") : false;
            if ($info['numposts'] < $temp3['limit'] && $check['id'] != "-1")
            {
                $oldview = $viewauth;
                $viewauth = 0;
                $readauth = 0;
            }

            if($viewauth == 1)
            {
                $i = 0;
                $j = 0;
                $sql2 = $data->select_query("forumtopics", "WHERE forum={$temp3["id"]}");
                $temp3["numtopics"] = $data->num_rows($sql2);
                while($temp2 = $data->fetch_array($sql2))
                {
                    $i += $temp2["numviews"];
                    $sql3 = $data->select_query("forumposts", "WHERE topic={$temp2["id"]}", "id");
                    $j += $data->num_rows($sql3);
        
                    if ($temp2['id'] == $temp3['lasttopic'])
                    {
                        $temp3['lastsubject'] = censor($temp2['subject']);
                    }
                }
                $temp3["numviews"] = $i;
                $temp3["numposts"] = $j;
                $sql3 = $data->select_query("forumnew", "WHERE uid='{$check['id']}' AND forum={$temp3["id"]}", "uid");
                if ($data->num_rows($sql3) > 0)
                {
                    $temp3["new"] = 1;
                }
                else
                {
                    $temp3["new"] = 0;
                }
                $temp3['allowed'] = 1;
                $temp3['read'] = $readauth;
                $temp3['lastpostname'] =  $userIdList[$temp3['lastpost']];
                $temp3['userstatus'] = user_online($temp3['lastpostname']);
                $rssuname = safesql(md5($check['uname']), "text");
                if ($data->num_rows($data->select_query("rssfeeds", "WHERE itemid={$temp3['id']} AND type=1 AND uname=$rssuname", "id")))
                {
                    $temp3['rss'] = 1;
                }
                else
                {
                    $temp3['rss'] = 0;
                }
                
                $temp3['nummods'] = 0;
                $sql4 = $data->select_query("forummods", "WHERE fid={$temp3["id"]}");
                $temp3['nummods'] = $data->num_rows($sql4);
                $temp3['mods'] = array();
                while ($temp4 = $data->fetch_array($sql4))
                {
                    if ($temp4['type'] == 0)
                    {
                        $sql5 = $data->select_query("users", "WHERE id='{$temp4['mid']}'", "uname, publicprofile");
                        $temp5 = $data->fetch_array($sql5);
                        $temp4['name'] = $temp5['uname'];
                        $temp4['clickable'] = $temp5['publicprofile'] && get_auth("profile", 0);
                        $temp4['type'] = 0;
                    }
                    else
                    {
                        $sql5 = $data->select_query("groups", "WHERE id={$temp4['mid']}", "teamname, ispublic");
                        $temp5 = $data->fetch_array($sql5);
                        $temp4['name'] = $temp5['teamname'];
                        $temp4['clickable'] = $temp5['ispublic'];
                        $temp4['type'] = 1;
                    }
                    $temp3['mods'][] = $temp4;
                }
                
                
                $temp['forums'][] = $temp3;
            }
            else
            {
                $temp2['name'] = $temp3['name'];
                $temp2['allowed'] = 0;
                if ($check['id'] != "-1" && $info['numposts'] >= $temp3['limit'] && $oldview != 1) 
                {
                    $temp2['desc'] = "You do not have access to the \"{$temp3['name']}\" forum. If you think you should have access, contact the administrator";
                }
                elseif ($info['numposts'] < $temp3['limit'] && $oldview == 1)
                {
                    $temp2['desc'] = "You do not have enough posts to access the \"{$temp3['name']}\" forum. You need at least {$temp3['limit']} posts.";
                }
                else
                {
                    $temp2['desc'] = "You do not have access to the \"{$temp3['name']}\" forum.";
                }
                $temp['forums'][] = $temp2;
            }
        }
        $cats[] = $temp;
    }
    
    
    $tpl->assign("numcats", $numcats);
    $tpl->assign("cats", $cats);
    $pagenum = 1;
}
else
{
    //First get normal posts
    $limit = $config['numpage'];
    $start = isset($_GET['start']) ? $_GET['start'] : 0;
    
    $sql = $data->select_query("forums", "WHERE id=$fid");
    $forum = $data->fetch_array($sql);
    $location = $forum['name'];
    
    if ($forum['parent'] != 0)
    {
        $sql = $data->select_query("forums", "WHERE id={$forum['parent']}");
        $parentforum = $data->fetch_array($sql);
        $tpl->assign("issubforum", 1);
        $tpl->assign("parentforum", $parentforum);
    }
    
    $sql = $data->select_query("forumtopics", "WHERE forum=$fid AND type = 0");
    $numtopics = $data->num_rows($sql);
    
    $pagelimit = ($numtopics-$start) <= $limit ? ($numtopics-$start) : $limit;
    $sql = $data->select_query("forumtopics", "WHERE forum=$fid AND type = 0 ORDER BY lastdate DESC LIMIT $start, $pagelimit");
    $topics = array();
    while($temp = $data->fetch_array($sql))
    {
        $sql3 = $data->select_query("forumposts", "WHERE topic={$temp["id"]}", "id");
        $temp['numposts'] = $data->num_rows($sql3);
        $temp['numpages'] = ceil($temp['numposts'] / $config['numpage']);
        
        $sql3 = $data->select_query("forumnew", "WHERE uid='{$check['id']}' AND topic={$temp["id"]}", "uid");
        if ($data->num_rows($sql3) > 0)
        {
            $temp["new"] = 1;
        }
        else
        {
            $temp["new"] = 0;
        }
        
        $temp['userpostedname'] = $userIdList[$temp['userposted']];

        $temp['lastpostname'] = $userIdList[$temp['lastpost']];
        
        $temp['userstatus1'] = user_online($temp['userpostedname']);
        $temp['userstatus2'] = user_online($temp['lastpostname']);
        $temp['subject'] = censor($temp['subject']);
        $temp['desc'] = censor($temp['desc']);
        $topics[] = $temp;
    }
    
    
    //Pagenation working out
    if ($numtopics > 0) 
    {
        $num_pages = ceil($numtopics / $limit);
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
    
    //Then get sticky and announcements. Sticky posts are only shown on the first page, while announcements are shown on all pages
    if ($curr_page == 1)
    {
        $sql = $data->select_query("forumtopics", "WHERE forum=$fid AND type = 2 ORDER BY lastdate DESC");
        $numsticky = $data->num_rows($sql);
        $sticky = array();
        while($temp = $data->fetch_array($sql))
        {
            $sql3 = $data->select_query("forumposts", "WHERE topic={$temp["id"]}", "id");
            $temp['numposts'] = $data->num_rows($sql3);
            $temp['numpages'] = ceil($temp['numposts'] / $config['numpage']);
            
            $sql3 = $data->select_query("forumnew", "WHERE uid='{$check['id']}' AND topic={$temp["id"]}", "uid");
            if ($data->num_rows($sql3) > 0)
            {
                $temp["new"] = 1;
            }
            else
            {
                $temp["new"] = 0;
            }
            
            $temp['userpostedname'] = $userIdList[$temp['userposted']];
            $temp['lastpostname'] = $userIdList[$temp['lastpost']];
            
            $temp['userstatus1'] = user_online($temp['userpostedname']);
            $temp['userstatus2'] = user_online($temp['lastpostname']);
           
            $temp['subject'] = censor($temp['subject']);
            $temp['desc'] = censor($temp['desc']);
            $sticky[] = $temp;
        }
    }
    
    $sql = $data->select_query("forumtopics", "WHERE forum=$fid AND type = 1 ORDER BY lastdate DESC");
    $numannounce = $data->num_rows($sql);
    $announce = array();
    while($temp = $data->fetch_array($sql))
    {
        $sql3 = $data->select_query("forumposts", "WHERE topic={$temp["id"]}", "id");
        $temp['numposts'] = $data->num_rows($sql3);
        
        $temp['numpages'] = ceil($temp['numposts'] / $config['numpage']);

        $sql3 = $data->select_query("forumnew", "WHERE uid='{$check['id']}' AND topic={$temp["id"]}", "uid");
        if ($data->num_rows($sql3) > 0)
        {
            $temp["new"] = 1;
        }
        else
        {
            $temp["new"] = 0;
        }

        $temp['userpostedname'] = $userIdList[$temp['userposted']];
        $temp['lastpostname'] = $userIdList[$temp['lastpost']];
        
        $temp['userstatus1'] = user_online($temp['userpostedname']);
        $temp['userstatus2'] = user_online($temp['lastpostname']);


        $temp['subject'] = censor($temp['subject']);
        $temp['desc'] = censor($temp['desc']);
        
        $announce[] = $temp;
    }
    

    $sqls = $data->select_query("forums", "WHERE parent=$fid ORDER BY pos ASC");
    $numsubs = $data->num_rows($sqls);
    $subforums = array();
    while($temp3 = $data->fetch_array($sqls))
    {
        $sql2 = $data->select_query("forumauths", "WHERE forum_id={$temp3['id']}");
        $auth = $data->fetch_array($sql2);
        
        $view_forum = unserialize($auth['view_forum']);
        $read_topics = unserialize($auth['read_topics']);
        
        if ($check['id'] != "-1")
        {
            $usergroups = user_groups_id_array($check['id']);
        }
        else
        {
            $usergroups = array(0 => "-1");
        }
        $viewauth = 0;
        $readauth = 0;
        for($i=0;$i<count($usergroups);$i++)
        {                
            $viewauth = $viewauth || $view_forum[$usergroups[$i]];
            $readauth = $readauth || $read_topics[$usergroups[$i]];
        }
        
        $info = $data->select_fetch_one_row("users", "WHERE id='{$check['id']}'", "numposts");
        if ($info['numposts'] < $temp3['limit'] && $check['uname'] != "Guest")
        {
            $oldview = $viewauth;
            $viewauth = 0;
            $readauth = 0;
        }   
        
        if($viewauth == 1)
        {
            $i = 0;
            $j = 0;
            $sql2 = $data->select_query("forumtopics", "WHERE forum={$temp3["id"]}");
            $temp3["numtopics"] = $data->num_rows($sql2);
            while($temp2 = $data->fetch_array($sql2))
            {
                $i += $temp2["numviews"];
                $sql3 = $data->select_query("forumposts", "WHERE topic={$temp2["id"]}", "id");
                $j += $data->num_rows($sql3);
    
                if ($temp2['id'] == $temp3['lasttopic'])
                {
                    $temp3['lastsubject'] = censor($temp2['subject']);
                }
            }
            $temp3["numviews"] = $i;
            $temp3["numposts"] = $j;
            $sql3 = $data->select_query("forumnew", "WHERE uid='{$check['id']}' AND forum={$temp3["id"]}", "uid");
            if ($data->num_rows($sql3) > 0)
            {
                $temp3["new"] = 1;
            }
            else
            {
                $temp3["new"] = 0;
            }
            $temp3['allowed'] = 1;
            $temp3['read'] = $readauth;
            $temp3['lastpostname'] = $userIdList[$temp3['lastpost']];
            $temp3['userstatus'] = user_online($temp3['lastpostname']);
            
            $rssuname = safesql(md5($check['uname']), "text");
            if ($data->num_rows($data->select_query("rssfeeds", "WHERE itemid={$temp3['id']} AND type=1 AND uname=$rssuname", "id")))
            {
                $temp3['rss'] = 1;
            }
            else
            {
                $temp3['rss'] = 0;
            }
        
            $temp3['nummods'] = 0;
            $sql4 = $data->select_query("forummods", "WHERE fid={$temp3["id"]}");
            $temp3['nummods'] = $data->num_rows($sql4);
            $temp3['mods'] = array();
            while ($temp4 = $data->fetch_array($sql4))
            {
                if ($temp4['type'] == 0)
                {
                    $sql5 = $data->select_query("users", "WHERE id='{$temp4['mid']}'", "uname, publicprofile");
                    $temp5 = $data->fetch_array($sql5);
                    $temp4['name'] = $temp5['uname'];
                    $temp4['clickable'] = $temp5['publicprofile'] && get_auth("profile", 0);
                    $temp4['type'] = 0;
                }
                else
                {
                    $sql5 = $data->select_query("groups", "WHERE id={$temp4['mid']}", "teamname, ispublic");
                    $temp5 = $data->fetch_array($sql5);
                    $temp4['name'] = $temp5['teamname'];
                    $temp4['clickable'] = $temp5['ispublic'];
                    $temp4['type'] = 1;
                }
                $temp3['mods'][] = $temp4;
            }
            $subforums[] = $temp3;
        }
        else
        {
            $name = $temp3['name'];
            $temp3['name'] = "You do not have access to the \"$name\" forum";
            $temp3['allowed'] = 0;
            if ($info['numposts'] < $temp3['limit'] && $oldview == 1)
            {
                $temp3['desc'] = "You do not have enough posts to access the \"{$name}\" forum. You need at least {$temp3['limit']} posts.";
            }
            elseif ($check['uname'] != "Guest") 
            {
                $temp3['desc'] = "You do not have access to the \"{$name}\" forum. If you think you should have access, contact the administrator";
            }
            else
            {
                $temp3['desc'] = "";                
            }
            $subforums[] = $temp3;
        }
    }
    
    $tpl->assign("subforums", $subforums);
    $tpl->assign("numsubs", $numsubs);
    if (isset($num_pages)) $tpl->assign('numpages', $num_pages);
    if (isset($curr_page)) $tpl->assign('currentpage', $curr_page);
    if (isset($next))$tpl->assign('next', $next);
    if (isset($prev))$tpl->assign('prev', $prev);
    $tpl->assign('num_per_page', $limit);
    if (isset($next_start)) $tpl->assign('next_start', $next_start);
    if (isset($prev_start)) $tpl->assign('prev_start', $prev_start);
    $tpl->assign("forum", $forum);
    $tpl->assign("topics", $topics);
    $tpl->assign("limit", $pagelimit);
    $tpl->assign("numtopics", $numtopics);
    $tpl->assign("numsticky", $numsticky);
    $tpl->assign("sticky", $sticky);
    $tpl->assign("numannounce", $numannounce);
    $tpl->assign("announce", $announce);
    $pagenum = 2;
}
?>