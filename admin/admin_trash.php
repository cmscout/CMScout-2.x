<?php
/**************************************************************************
    FILENAME        :   admin_trash.php
    PURPOSE OF FILE :   Trash Manager
    LAST UPDATED    :   25 September 2006
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
	$module['Content Management']['Trash'] = "trash";
    $moduledetails[$modulenumbers]['name'] = "Trash";
    $moduledetails[$modulenumbers]['details'] = "Allows recovory of trashed items";
    $moduledetails[$modulenumbers]['access'] = "Allowed to access the trash box";
    $moduledetails[$modulenumbers]['add'] = "notused";
    $moduledetails[$modulenumbers]['edit'] = "Allowed to recover deleted items";
    $moduledetails[$modulenumbers]['delete'] = "Allowed to permentaly delete items";
    $moduledetails[$modulenumbers]['publish'] = "notused";
    $moduledetails[$modulenumbers]['limit'] = "notused";
    $moduledetails[$modulenumbers]['id'] = "trash";

	return;
}
else
{
    if ($_GET['action'] == "recover")
    {
        $type = $_GET['type'];
        $id = safesql($_GET['id'], "int");
        switch ($type)
        {
            case "album":
                $sqlq = $data->update_query("album_track", "trash=0", "ID=$id");
                break;
            case "article":
                $sqlq = $data->update_query("patrol_articles", "trash=0", "ID=$id");
                break;
            case "event":
                $sqlq = $data->update_query("calendar_items", "trash=0", "id=$id");
                break;
            case "download":
                $sqlq = $data->update_query("downloads", "trash=0", "id=$id");
                break;
            case "news":
                $sqlq = $data->update_query("newscontent", "trash=0", "id=$id");
                break;
            case "poll":
                $sqlq = $data->update_query("polls", "trash=0", "id=$id");
                break;
            case "content":
                $sqlq = $data->update_query("static_content", "trash=0", "id=$id");
                break;
        }
        show_admin_message("Item recovered", "$pagename&activetab=$type");
    }
    elseif ($_GET['action'] == "delete")
    {
    	function album($id)
    	{
    		global $data;
    		   	$sqlq = $data->delete_query("album_track", "ID=$id");
              	$sqlq = $data->delete_query("comments", "item_id=$id AND type=1");
                
                $sqlq = $data->select_query("photos", "WHERE album_id=$id");
                while ($temp = $data->fetch_array($sqlq))
                {
                    unlink($config['photopath'] . "/" . $temp['filename']);
                }
                $sqlq = $data->delete_query("photos", "album_id=$id");
    	}
    	
    	function article ($id)
    	{
    		global $data;
    		    $sqlq = $data->delete_query("patrol_articles", "ID=$id");
                $sqlq = $data->delete_query("comments", "item_id=$id AND type=0");
    	}
    	
    	function event($id)
    	{
    		global $data;
    		$sqlq = $data->delete_query("calendar_items", "id=$id");
    	}
    	
    	function download($id)
    	{
    		global $data;
    		$temp = $data->select_fetch_one_row("downloads", "WHERE id=$id");
            unlink($config['downloadpath'] . "/" . $temp['file']);
            $sqlq = $data->delete_query("downloads", "id=$id");
    	}
    	
    	function news($id)
    	{
    		global $data;
    		$sqlq = $data->delete_query("newscontent", "id=$id");
    	}
    	
    	function poll($id)
    	{
    		global $data;
    		$sqlq = $data->delete_query("polls", "id=$id");
    	}
    	
    	function content($id)
    	{
    		global $data;
    		$sqlq = $data->delete_query("static_content", "id=$id");
    		$sqlq = $data->delete_query("frontpage", "item=$id AND type=0");
    		$sqlq = $data->delete_query("menu_items", "item=$id AND type=1");
    	}
    	
    	function _selectDel($tableName, $function)
    	{
    		global $data;
    		$items = $data->select_query($tableName, 'WHERE `trash`=1');
    		
    		while ($item = $data->fetch_array($items))
    		{
    			if(isset($item['id']))
    			{
    				$function($item['id']);
    			}
    			elseif(isset($item['ID']))
    			{
    				$function($item['ID']);
    			}
    		}
    	}
    	
    	function all($id)
    	{
    		_selectDel('album_track', 'album');
    		_selectDel('patrol_articles', 'article');
    		_selectDel('calendar_items', 'event');
    		_selectDel('downloads', 'download');
    		_selectDel('newscontent', 'news');
    		_selectDel('polls', 'poll');
    		_selectDel('static_content', 'content');
    	}
    	
        $type = $_GET['type'];
        $id = safesql($_GET['id'], "int");

        if($type != '')
        {
	        $type($id);
	        
	        if($type != 'all')
	        	show_admin_message("Item permanently deleted", "$pagename&activetab=$type");
	        else
	         	show_admin_message("Items permanently deleted", "$pagename");
        }       
    }
    
    
    $result = $data->select_query("album_track", "WHERE trash=1");

    $album = array();
    $numalbums = $data->num_rows($result);
    while ($album[] = $data->fetch_array($result));
        
    $tpl->assign("album", $album);
    $tpl->assign("numalbums", $numalbums);
    
    $result = $data->select_query("patrol_articles", "WHERE trash=1");

    $article = array();
    $numarticles = $data->num_rows($result);
    while ($article[] = $data->fetch_array($result));
        
    $tpl->assign("article", $article);
    $tpl->assign("numarticles", $numarticles);
    
    $result = $data->select_query("calendar_items", "WHERE trash=1");

    $event = array();
    $numevents = $data->num_rows($result);
    while ($event[] = $data->fetch_array($result));
        
    $tpl->assign("event", $event);
    $tpl->assign("numevents", $numevents);    
    
    $result = $data->select_query("downloads", "WHERE trash=1");

    $download = array();
    $numdownloads = $data->num_rows($result);
    while ($download[] = $data->fetch_array($result));
        
    $tpl->assign("download", $download);
    $tpl->assign("numdownloads", $numdownloads);    
    
    $result = $data->select_query("newscontent", "WHERE trash=1");

    $news = array();
    $numnews = $data->num_rows($result);
    while ($news[] = $data->fetch_array($result));
        
    $tpl->assign("news", $news);
    $tpl->assign("numnews", $numnews);    
    
    $result = $data->select_query("polls", "WHERE trash=1");

    $poll = array();
    $numpolls = $data->num_rows($result);
    while ($poll[] = $data->fetch_array($result));
        
    $tpl->assign("poll", $poll);
    $tpl->assign("numpolls", $numpolls);    
    
    $result = $data->select_query("static_content", "WHERE trash=1");

    $content = array();
    $numcontents = $data->num_rows($result);
    while ($content[] = $data->fetch_array($result));
        
    $tpl->assign("content", $content);
    $tpl->assign("numcontents", $numcontents); 

    $filetouse = 'admin_trash.tpl';
}
?>