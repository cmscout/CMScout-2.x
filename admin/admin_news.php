<?php
/**************************************************************************
    FILENAME        :   admin_news.php
    PURPOSE OF FILE :   Manages news
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
	$module['Content Management']['News Manager'] = "news";
    $moduledetails[$modulenumbers]['name'] = "News Manager";
    $moduledetails[$modulenumbers]['details'] = "Manages news items";
    $moduledetails[$modulenumbers]['access'] = "Allowed to access the News Manager";
    $moduledetails[$modulenumbers]['add'] = "Allowed to add a new news item";
    $moduledetails[$modulenumbers]['edit'] = "Allowed to edit a existing news item";
    $moduledetails[$modulenumbers]['delete'] = "Allowed to delete a existing news item";
    $moduledetails[$modulenumbers]['publish'] = "Allowed to publish news items";
    $moduledetails[$modulenumbers]['limit'] = "notused";
    $moduledetails[$modulenumbers]['id'] = "news";
	return;
}
else
{	
    
    $editFormAction = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING']))
    {
      $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
    }
    
    $Submit = $_POST['Submit'];
    $action = $_GET['action'];
    $id = safesql($_GET['id'], "int");
    
    // Add / Edit news
    if ($Submit == "Add" && pageauth("news", "add") == 1)
    {     
        $news = safesql($_POST['editor'], "text", false);
        $title = safesql($_POST['title'], "text");
        $attachment = safesql($_POST['attachment'], "text");
        $Add = $data->insert_query("newscontent", "NULL, $title, $news, $timestamp, $attachment, 1, 0");
        if ($Add)
        {
            show_admin_message("News added", "$pagename");
        }
        $action="";
    }
    elseif ($Submit == "Modify" && pageauth("news", "edit") == 1)
    {
        $news = safesql($_POST['editor'], "text", false);
        $title = safesql($_POST['title'], "text");
        $attachment = safesql($_POST['attachment'], "text");

        $Update = $data->update_query("newscontent", "title=$title, news=$news, attachment=$attachment", "id='$id'", 'News Admin', "Updated news item $id");
        if ($Update)
        {
            show_admin_message("News updated", "$pagename");
        }
        $action = "";
    }
    
    // Delete News
    if ($action=="delete" && pageauth("news", "delete") == 1)
    {
        $Delete = $data->update_query("newscontent", "trash=1", "id='$id'");	
        if ($Delete)
        {
            show_admin_message("News trashed", "$pagename");
        }
    }
    elseif ($action == 'publish' && pageauth("news", "publish") == 1) 
    {
        $sqlq = $data->update_query("newscontent", "allowed = 1", "id=$id");
        if ($data->num_rows($data->select_query("review", "WHERE item_id=$id AND type='news'")))
        {        
            $item = $data->select_fetch_one_row("newscontent", "WHERE id=$id");
            email('newitem', array("news", $item));
            $data->delete_query("review", "item_id=$id AND type='news'");
        }
        header("Location: $pagename");
    }
    elseif ($action == 'unpublish' && pageauth("news", "publish") == 1) 
    {
        $sqlq = $data->update_query("newscontent", "allowed = 0", "id=$id");
        header("Location: $pagename");
    }
    
    // Show specific news
    if ($id != "")
    {
        // Show selected news
        $Show = $data->select_query("newscontent", "WHERE id='$id' AND trash=0");
        $shownews = $data->fetch_array($Show);
        $tpl->assign('shownews', $shownews);
        $tpl->assign("editor", true);
    }
    if ($action == "new")
    {
        $tpl->assign("editor", true);
    }
    
    if ($action == "new" || $action == "edit")
    {
        $sql = $data->select_query("album_track", "WHERE trash=0 ORDER BY album_name ASC");
        $numalbums = $data->num_rows($sql);
        $album = array();
        while ($temp = $data->fetch_array($sql))
        {
            $temp['idType'] = $temp['ID'] . ".album";
            $album[] = $temp;
        }

        $sql = $data->select_query("patrol_articles", "WHERE trash=0 ORDER BY title ASC");
        $numart = $data->num_rows($sql);
        $articles = array();
        while ($temp = $data->fetch_array($sql))        
        {
            $temp['idType'] = $temp['ID'] . ".article";
            $articles[] = $temp;
        }

        $sql = $data->select_query("calendar_items", "WHERE trash=0 ORDER BY summary ASC");
        $numevents = $data->num_rows($sql);
        $events = array();
        while ($temp = $data->fetch_array($sql))
        {
            $temp['idType'] = $temp['id'] . ".event";
            $events[] = $temp;
        }

        $sql = $data->select_query("downloads", "WHERE trash=0 ORDER BY name ASC");
        $numdown = $data->num_rows($sql);
        $downloads = array();
        while ($temp = $data->fetch_array($sql))
        {
            $temp['idType'] = $temp['id'] . ".download";
            $downloads[] = $temp;
        }

        $sql = $data->select_query("newscontent", "WHERE trash=0 ORDER BY title ASC");
        $numnews = $data->num_rows($sql);
        $newsitems = array();
        while ($ntemp = $data->fetch_array($sql))
        {
            $temp['idType'] = $temp['id'] . ".news";
            $newsitems[] = $temp;
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
    }
    else
    {
        // Show all news
        $result = $data->select_query("newscontent", "WHERE trash=0 ORDER BY title DESC");
        
        $news = array();
        $numnews = $data->num_rows($result);
        while ($news[] = $data->fetch_array($result));
        $tpl->assign('numnews', $numnews);
        $tpl->assign('news', $news);
    }
    
    $tpl->assign('action', $action);

    $tpl->assign('editFormAction',$editFormAction);
    $filetouse = "admin_news.tpl";
}
?>