<?php
/**************************************************************************
    FILENAME        :   admin_forums.php
    PURPOSE OF FILE :   Manages forums
    LAST UPDATED    :   02 October 2006
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
	$module['Module Management']['Forum Manager'] = "forums";
    $moduledetails[$modulenumbers]['name'] = "Forum Manager";
    $moduledetails[$modulenumbers]['details'] = "Management of forums";
    $moduledetails[$modulenumbers]['access'] = "Allowed to access the Forum Manager";
    $moduledetails[$modulenumbers]['add'] = "Allowed to add forums and categories";
    $moduledetails[$modulenumbers]['edit'] = "Allowed to edit forums and categories";
    $moduledetails[$modulenumbers]['delete'] = "Allowed to delete forums and categories";
    $moduledetails[$modulenumbers]['publish'] = "notused";
    $moduledetails[$modulenumbers]['limit'] = "notused";    
    $moduledetails[$modulenumbers]['id'] = "forums";

	return;
}
else
{
    
    $action = $_GET['action'];
    
    $cid = safesql($_GET['cid'], "int");
    $fid = safesql($_GET['fid'], "int");
    
    $editFormAction = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING']))
    {
      $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
    }
    
    if ($action == "")
    {
        $sql = $data->select_query("forumscats", "ORDER BY pos ASC");
        $numcats = $data->num_rows($sql);
        $cats = array();
        while($cats[] = $data->fetch_array($sql));
        
        $tpl->assign("numcats", $numcats);
        $tpl->assign("cats", $cats);
    }
    elseif($action=="view")
    {
        $sql = $data->select_query("forumscats", "WHERE id=$cid");
        $catinfo = $data->fetch_array($sql);
    
        $sql = $data->select_query("forums", "WHERE cat=$cid AND parent=0 ORDER BY pos ASC");
        $numforums = $data->num_rows($sql);
        $forums = array();
        while($temp = $data->fetch_array($sql))
        {
            $i = 0;
            $j = 0;
            $sql2 = $data->select_query("forumtopics", "WHERE forum={$temp["id"]}");
            $temp["numtopics"] = $data->num_rows($sql2);
            while($temp2 = $data->fetch_array($sql2))
            {
                $i += $temp2["numviews"];
                $sql3 = $data->select_query("forumposts", "WHERE topic={$temp2["id"]}");
                $j += $data->num_rows($sql3);
            }
            $temp["numviews"] = $i;
            $temp["numposts"] = $j;
            
            $subsql = $data->select_query("forums", "WHERE cat=$cid AND parent='{$temp['id']}' ORDER BY pos ASC");
            $numsub = $data->num_rows($subsql);
            $subs = array();        
            while($subtemp = $data->fetch_array($subsql))
            {  
                $i = 0;
                $j = 0;
                $sql2 = $data->select_query("forumtopics", "WHERE forum={$subtemp["id"]}");
                $subtemp["numtopics"] = $data->num_rows($sql2);
                while($temp2 = $data->fetch_array($sql2))
                {
                    $i += $temp2["numviews"];
                    $sql3 = $data->select_query("forumposts", "WHERE topic={$temp2["id"]}");
                    $j += $data->num_rows($sql3);
                }
                $subtemp["numviews"] = $i;
                $subtemp["numposts"] = $j;  
                $subs[] = $subtemp;            
            }
            
            $temp['subforums'] = $subs;
            $temp['numsubs'] = $numsub;
            $forums[] = $temp;
        }
        
        $tpl->assign("numforums", $numforums);
        $tpl->assign("forums", $forums);
        $tpl->assign("catinfo", $catinfo);
    }
    elseif($action == "moveup" && pageauth("forums", "edit") == 1)
    {
        $sql = $data->select_query("forumscats", "WHERE id=$cid");
        $row = $data->fetch_array($sql);
        
        $pos1 = $row['pos'];
        $temppos = $pos1 -1;
        if($tempos <= 0) $tempos=1;
        $sql = $data->select_query("forumscats", "WHERE pos='$temppos'");
        $row2 = $data->fetch_array($sql);
        
        $pos2= $row2['pos'];
        $data->update_query("forumscats", "pos=$pos2", "id={$row['id']}", "", "", false);
        $data->update_query("forumscats", "pos=$pos1", "id={$row2['id']}", "", "", false);
        
        $server = $_SERVER['PHP_SELF'];
        header("Location: $server"."?page=forums");
    }
    elseif($action == "movedown" && pageauth("forums", "edit") == 1)
    {
        $sql = $data->select_query("forumscats", "WHERE id=$cid");
        $row = $data->fetch_array($sql);
        
        $pos1 = $row['pos'];
        $temppos = $pos1 + 1;
        $sql = $data->select_query("forumscats", "WHERE pos='$temppos'");
        $row2 = $data->fetch_array($sql);
        
        $pos2= $row2['pos'];
        $data->update_query("forumscats", "pos=$pos2", "id={$row['id']}", "", "", false);
        $data->update_query("forumscats", "pos=$pos1", "id={$row2['id']}", "", "", false);
        
        $server = $_SERVER['PHP_SELF'];
        header("Location: $server"."?page=forums");
    }
    elseif($action == "movefup" && pageauth("forums", "edit") == 1)
    {
        $sql = $data->select_query("forums", "WHERE id=$fid");
        $row = $data->fetch_array($sql);
        
        $pos1 = $row['pos'];
        $temppos = $pos1 -1;
        if($tempos <= 0) $tempos=1;
        if ($row['parent'] == 0)
        {
            $sql = $data->select_query("forums", "WHERE pos='$temppos' AND cat=$cid AND parent=0");
        }
        else
        {
            $sql = $data->select_query("forums", "WHERE pos='$temppos' AND cat=$cid AND parent={$row['parent']}");
        }
        
        $row2 = $data->fetch_array($sql);
        
        $pos2= $row2['pos'];
        $data->update_query("forums", "pos=$pos2", "id={$row['id']}", "", "", false);
        $data->update_query("forums", "pos=$pos1", "id={$row2['id']}", "", "", false);
        
        $server = $_SERVER['PHP_SELF'];
        header("Location: $server"."?page=forums&action=view&cid=$cid");
    }
    elseif($action == "movefdown" && pageauth("forums", "edit") == 1)
    {
        $sql = $data->select_query("forums", "WHERE id=$fid");
        $row = $data->fetch_array($sql);
        
        $pos1 = $row['pos'];
        $temppos = $pos1 + 1;
        if ($row['parent'] == 0)
        {
            $sql = $data->select_query("forums", "WHERE pos='$temppos' AND cat=$cid AND parent=0");
        }
        else
        {
            $sql = $data->select_query("forums", "WHERE pos='$temppos' AND cat=$cid AND parent={$row['parent']}");
        }
        $row2 = $data->fetch_array($sql);
        
        $pos2= $row2['pos'];
        $data->update_query("forums", "pos=$pos2", "id={$row['id']}", "", "", false);
        $data->update_query("forums", "pos=$pos1", "id={$row2['id']}", "", "", false);
        
        $server = $_SERVER['PHP_SELF'];
        header("Location: $server"."?page=forums&action=view&cid=$cid");
    }
    elseif($action == "add" && pageauth("forums", "add") == 1)
    {
        if ($_POST['Submit'] == "Submit")
        {
            if ($_POST['catname'] == '')
            {
                show_message_back("You need to supply a name for the category");
                exit;
            }
            $catname = safesql($_POST['catname'], "text");
        
            $pos = 1;
            do 
            {
                $temp = $data->select_query("forumscats", "WHERE pos = '$pos'");
                if ($data->num_rows($temp) != 0) 
                {
                    $pos++;
                }
            } while ($data->num_rows($temp) != 0); 	
            
            $sql = $data->insert_query("forumscats", "NULL, $catname, '$pos'", "Forums", "Category added");
            
            if ($sql)
            {
                show_admin_message("Category Added", "$pagename");
            }
        }
    }
    elseif($action == "edit" && pageauth("forums", "edit") == 1)
    {
        $sql = $data->select_query("forumscats", "WHERE id=$cid");
        $cat = $data->fetch_array($sql);
        $tpl->assign("cat", $cat);
        
        if ($_POST['Submit'] == "Submit")
        {
            if ($_POST['catname'] == '')
            {
                show_message_back("You need to supply a name for the category");
                exit;
            }
            $catname = safesql($_POST['catname'], "text");
        
            
            $sql = $data->update_query("forumscats", "name=$catname", "id=$cid", "Forums", "Category edited");
            
            if ($sql)
            {
                show_admin_message("Category Updated", "$pagename");
            }
        }
    }
    elseif($action == "addforum" && pageauth("forums", "add") == 1)
    {       
        $sql = $data->select_query("forums", "WHERE cat=$cid AND parent=0 ORDER BY pos ASC");
        $numparents = $data->num_rows($sql);
        $parent = array();
        while ($parent[] = $data->fetch_array($sql));
        
        $sql = $data->select_query("forums", "ORDER BY name ASC");
        $numforums = $data->num_rows($sql);
        $forums = array();
        while ($forums[] = $data->fetch_array($sql)); 
        
        $tpl->assign('parent', $parent);
        $tpl->assign('numparents', $numparents);
        $tpl->assign('forums', $forums);
        $tpl->assign('numforums', $numforums);
        
        if ($_POST['Submit'] == "Submit")
        {
            $forumname = safesql($_POST['name'], "text");
            $desc = safesql($_POST['desc'], "text");
            $parent = safesql(isset($_POST['parent']) ? $_POST['parent'] : 0, "int");     
            $limit = safesql($_POST['limit'], "int");   
            $copypermissions = $_POST['permissions'];
            
            $pos = 1;
            do 
            {
                if ($_POST['parent'] == 0)
                {
                    $temp = $data->select_query("forums", "WHERE pos = '$pos' AND cat=$cid");
                }
                else
                {
                    $temp = $data->select_query("forums", "WHERE pos = '$pos' AND cat=$cid AND parent=$parent");
                }
                if ($data->num_rows($temp) != 0) 
                {
                    $pos++;
                }
            } while ($data->num_rows($temp) != 0); 		
    
            $sql = $data->insert_query("forums", "NULL, $forumname, $desc, '', '', '', $cid, $pos, $parent, $limit");
            if($sql)
            {
                if ($copypermissions != 0)
                {
                    $sql = $data->select_query("forums", "WHERE name=$forumname AND cat=$cid");
                    $forum = $data->fetch_array($sql);
                    $copy = safesql($copypermissions, "int");
                    $auths = $data->select_fetch_one_row("forumauths", "WHERE forum_id=$copy");
                    $sql = $data->insert_query("forumauths", "'{$forum['id']}', '{$auths['new_topic']}', '{$auths['reply_topic']}', '{$auths['edit_post']}', '{$auths['delete_post']}', '{$auths['view_forum']}', '{$auths['read_topics']}', '{$auths['sticky']}', '{$auths['announce']}', '{$auths['poll']}'");
                } 
                show_admin_message("Forum Added", "$pagename&action=view&cid=$cid");                
            }
        }
    }
    elseif($action == "editforum" && pageauth("forums", "edit") == 1)
    {
        $sql = $data->select_query("forums", "WHERE id=$fid");
        $forum = $data->fetch_array($sql);
        
        $sql = $data->select_query("forums", "WHERE id != $fid AND cat=$cid AND parent=0 ORDER BY pos ASC");
        $numparents = $data->num_rows($sql);
        $parent = array();
        while ($parent[] = $data->fetch_array($sql));

        $sql = $data->select_query("forums", "WHERE id != $fid ORDER BY name ASC");
        $numforums = $data->num_rows($sql);
        $forums = array();
        while ($forums[] = $data->fetch_array($sql));

        $sql = $data->select_query("forumscats", "WHERE id != $cid ORDER BY pos ASC");
        $numcats = $data->num_rows($sql);
        $cats = array();
        while ($cats[] = $data->fetch_array($sql));

        if ($_POST['Submit'] == "Submit")
        {
            if ($_POST['name'] == '')
            {
                show_message_back("You need to supply a name for the forum");
                exit;
            }
            $forumname = safesql($_POST['name'], "text");
            $desc = safesql($_POST['desc'], "text");
            $parent = safesql($_POST['parent'], "text");          
            $limit = safesql($_POST['limit'], "int");          
    
            $copypermissions = $_POST['permissions'];
            $moveforum = $_POST['move'];
            if ($copypermissions != 0)
            {
                $data->delete_query("forumauths", "forum_id=$fid");
                $copy = safesql($copypermissions, "int");
                $auths = $data->select_fetch_one_row("forumauths", "WHERE forum_id=$copy");
                
                $sql = $data->insert_query("forumauths", "$fid, '{$auths['new_topic']}', '{$auths['reply_topic']}', '{$auths['edit_post']}', '{$auths['delete_post']}', '{$auths['view_forum']}', '{$auths['read_topics']}', '{$auths['sticky']}', '{$auths['announce']}', '{$auths['poll']}'");
            }
            
            if ($moveforum == 0)
            {
                $sql = $data->update_query("forums", "`name`=$forumname, `desc`=$desc, `parent` = $parent, `limit`=$limit", "id=$fid", "Forums", "Edited $forumname");
            }
            else
            {
                $moveforum = safesql($_POST['move'], "int");
                $sql = $data->update_query("forums", "`name`=$forumname, `desc`=$desc, `parent` = 0, `cat`=$moveforum, `limit`=$limit", "id=$fid", "Forums", "Edited $forumname");
                $cid = $_POST['move'];
            }
            if($sql)
            {
                show_admin_message("Forum Changed", "$pagename&action=view&cid=$cid"); 
            }
        }
        
        $tpl->assign('cats', $cats);
        $tpl->assign('numcats', $numcats);
        $tpl->assign('parent', $parent);
        $tpl->assign('numparents', $numparents);
        $tpl->assign('forums', $forums);
        $tpl->assign('numforums', $numforums);
        $tpl->assign("guest", $guest);
        $tpl->assign("forum", $forum);
    }
    elseif ($action == "permissions" && pageauth("forums", "edit") == 1)
    {
        $sql = $data->select_query("forums", "WHERE id=$fid");
        $forum = $data->fetch_array($sql);
        
        $groups = $data->select_fetch_all_rows($numgroups, "groups", "ORDER BY teamname ASC");
        
        $sql = $data->select_query("forums", "WHERE cat=$cid AND parent=0 ORDER BY pos ASC");
        $numparents = $data->num_rows($sql);
        $parent = array();
        while ($parent[] = $data->fetch_array($sql));
    
        
        $sql = $data->select_query("forumauths", "WHERE forum_id=$fid");
        if ($data->num_rows($sql))
        {
            $auth = $data->fetch_array($sql);
            
            $auths['new'] = unserialize($auth['new_topic']);
            
            $auths['reply'] = unserialize($auth['reply_topic']);
            
            $auths['edit'] = unserialize($auth['edit_post']);
            
            $auths['delete'] = unserialize($auth['delete_post']);
            
            $auths['view'] = unserialize($auth['view_forum']);
            
            $auths['read'] = unserialize($auth['read_topics']);
        
            $auths['sticky'] = unserialize($auth['sticky']);
            
            $auths['announce'] = unserialize($auth['announce']);
            
            $auths['poll'] = unserialize($auth['poll']);
        }

        if ($_POST['Submit'] == "Submit")
        {          
            $newtopic = $_POST["newtopic"];
            $reply = $_POST["reply"];
            $editpost = $_POST["edit"];
            $deletepost = $_POST["delete"];
            $view = $_POST["view"];
            $read = $_POST["read"];
            $sticky = $_POST["sticky"];
            $announce = $_POST["announce"];
            $poll = $_POST["poll"];
            
            $newtopic = safesql(@serialize($newtopic), "text");
            $reply = safesql(@serialize($reply), "text");
            $editpost = safesql(@serialize($editpost), "text");
            $deletepost = safesql(@serialize($deletepost), "text");
            $view = safesql(@serialize($view), "text");
            $read = safesql(@serialize($read), "text");
            $sticky = safesql(@serialize($sticky), "text");        
            $announce = safesql(@serialize($announce), "text");        
            $poll = safesql(@serialize($poll), "text");	
    
            if($data->num_rows($data->select_query("forumauths", "WHERE forum_id=$fid")))
            {
                $sql = $data->update_query("forumauths", "new_topic = $newtopic, reply_topic = $reply, edit_post = $editpost, delete_post = $deletepost, view_forum = $view, read_topics = $read, sticky = $sticky, announce = $announce, poll = $poll", "forum_id=$fid");
                if ($sql)
                {
                    show_admin_message("Permissions Updated", "$pagename&action=view&cid=$cid");
                }
            }
            else
            {
                $sql = $data->insert_query("forumauths", "$fid, $newtopic, $reply, $editpost, $deletepost, $view, $read, $sticky, $announce, $poll");
                if ($sql)
                {
                    show_admin_message("Permissions Updated", "$pagename&action=view&cid=$cid");
                }            
            }
        }        
        $tpl->assign("auths", $auths);
        $tpl->assign("forum", $forum);
        $tpl->assign('groups', $groups);
        $tpl->assign('numgroups', $numgroups);
    }
    elseif ($action == "delete" && pageauth("forums", "delete") == 1)
    {
        $sql = $data->select_query("forums", "WHERE cat=$cid");
        $numforums = $data->num_rows($sql);
        
        $sql = $data->select_query("forumscats");
        $numcats = $data->num_rows($sql);
        $cats = array();
        while ($cats[] = $data->fetch_array($sql));
        
        $sql = $data->select_query("forumscats", "WHERE id=$cid");
        $cat = $data->fetch_array($sql);
    
        $tpl->assign("cats", $cats);
        $tpl->assign("numcats", $numcats);
        $tpl->assign("numforums", $numforums);
        $tpl->assign("cat", $cat);
        
        if ($_POST['submit'] == "Delete")
        {
            $where = $_POST['cats'];
            if($where == "del" || empty($where))
            {
                $sql = $data->select_query("forums", "WHERE cat=$cid");
                while($temp=$data->fetch_array($sql))
                {
                    $sql2 = $data->select_query("forumtopics", "WHERE forum={$temp['id']}");
                    while($temp2 = $data->fetch_array($sql2))
                    {
                        $data->delete_query("forumposts", "topic={$temp2['id']}", "", "", false);
                    }
                    $data->delete_query("forumtopics", "forum={$temp['id']}", "", "", false);
                }
                $data->delete_query("forums", "cat=$cid", "", "", false);
                $sql = $data->delete_query("forumscats", "id=$cid", "Forums", "Category Deleted");
                if ($sql)
                {
                    show_admin_message("Category Deleted", "$pagename"); 
                }
            }
            else
            {
                $forumid = safesql($where, "int");
                $data->update_query("forums", "cat=$forumid", "cat=$cid", "", "", false);
                $sql = $data->delete_query("forumscats", "id=$cid", "Forums", "Category Deleted");
                if ($sql)
                {
                    show_admin_message("Category Deleted", "$pagename"); 
                }
            }
        }
    }
    elseif ($action == "deleteforum" && pageauth("forums", "delete") == 1)
    {
        $sql = $data->select_query("forumtopics", "WHERE forum=$fid");
        $numtopics = $data->num_rows($sql);
        
        $sql = $data->select_query("forums");
        $numforums = $data->num_rows($sql);
        $forums = array();
        while ($forums[] = $data->fetch_array($sql));
        
        $sql = $data->select_query("forums", "WHERE id=$fid");
        $forum = $data->fetch_array($sql);
    
        $tpl->assign("forums", $forums);
        $tpl->assign("numforums", $numforums);
        $tpl->assign("numtopics", $numtopics);
        $tpl->assign("forum", $forum);
        
        if ($_POST['submit'] == "Delete")
        {
            $where = $_POST['forum'];
            if($where == "del" || empty($where))
            {
                $sql2 = $data->select_query("forumtopics", "WHERE forum={$forum['id']}");
                while($temp2 = $data->fetch_array($sql2))
                {
                    $data->delete_query("forumposts", "topic={$temp2['id']}", "", "", false);
                }
                $data->delete_query("forumtopics", "forum={$forum['id']}", "", "", false);
                    
                $sql = $data->delete_query("forums", "id=$fid OR parent=$fid");
    
                if ($sql)
                {
                    show_admin_message("Forum Deleted", "$pagename&action=view&cid={$forum['cat']}"); 
                }
            }
            else
            {
                $forumid = safesql($where, "int");
                $data->update_query("forumtopics", "forum=$forumid", "forum=$fid", "", "", false);
                $sql = $data->delete_query("forums", "id=$fid", "Forums", "Forum Deleted");
                if ($sql)
                {
                    show_admin_message("Forum Deleted", "$pagename&action=view&cid={$forum['cat']}"); 
                }
            }
        }
    }
    elseif($action=="moderator" && pageauth("forums", "edit") == 1)
    {
        $sql = $data->select_query("forums", "WHERE id=$fid");
        $forum = $data->fetch_array($sql);
         
        if ($_POST['submit'] == "Add")
        {
            $mid = $_POST['user'];
            
            $mid = explode("_", $mid);
    
            $type = $mid[0] == 'u' ? 0 : 1;
            
            $mid = safesql($mid[1], "text");
            
            if ($data->num_rows($data->select_query("forummods", "WHERE fid=$fid AND mid=$mid AND type=$type")) == 0)
            {
                $data->insert_query("forummods", "'', $fid, $mid, $type");
            }
        }
        
        $sql = $data->select_query("groups", "", "id, teamname");
        $numgroups = $data->num_rows($sql);    
        $groups = array();
        while ($temp = $data->fetch_array($sql))
        {
            if ($data->num_rows($data->select_query("forummods", "WHERE fid=$fid AND mid={$temp['id']} AND type=1")) == 0)
            {
                $groups[] = $temp;
            }
            else
            {
                $numgroups--;
            }
        }
     
        $sql = $data->select_query("users", "", "id, uname");
        $numusers = $data->num_rows($sql);    
        $users = array();
        while ($temp = $data->fetch_array($sql))
        {
            if ($data->num_rows($data->select_query("forummods", "WHERE fid=$fid AND mid={$temp['id']} AND type=0")) == 0)
            {
                $users[] = $temp;
            }
            else
            {
                $numusers--;
            }
        }   
        
        $sql = $data->select_query("forummods", "WHERE fid=$fid");
        $nummods = $data->num_rows($sql);
        $mods = array();
        while ($temp = $data->fetch_array($sql))
        {
            if ($temp['type'] == 0)
            {
                $sql2 = $data->select_query("users", "WHERE id={$temp['mid']}", "uname");
                $temp2 = $data->fetch_array($sql2);
                $temp['name'] = "User: " . $temp2['uname'];
            }
            else
            {
                $sql2 = $data->select_query("groups", "WHERE id={$temp['mid']}", "teamname");
                $temp2 = $data->fetch_array($sql2);
                $temp['name'] = "Group: " . $temp2['teamname'];
            }
            $mods[] = $temp;
        }
        
        $tpl->assign("forum", $forum);
        $tpl->assign("groups", $groups);
        $tpl->assign("numgroups", $numgroups);
        $tpl->assign("users", $users);
        $tpl->assign("numusers", $numusers);
        $tpl->assign("mods", $mods);
        $tpl->assign("nummods", $nummods);
    }
    elseif($action=="deletemod" && pageauth("forums", "delete") == 1)
    {
        $id = safesql($_GET['id'], "int");
        $data->delete_query("forummods", "id=$id");
        show_admin_message("Moderator Deleted", "$pagename&action=moderator&fid=$fid&cid=$cid"); 
    }
    $tpl->assign('editFormAction', $editFormAction);   
    $tpl->assign('action', $action);
    $filetouse = "admin_forums.tpl";
}
?>