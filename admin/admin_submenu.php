<?php
/**************************************************************************
    FILENAME        :   admin_subment.php
    PURPOSE OF FILE :   Manages sub site menus
    LAST UPDATED    :   19 February 2006
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
	return;
}
else
{
    $siteid = $_GET['sid'];
    $safe_siteid= safesql($siteid, "int");
    $temp = $data->select_fetch_one_row("subsites", "WHERE id=$safe_siteid", "name");
    $sitename = $temp['name'];
    $safe_sitename = safesql($sitename, "text");
    
    $editFormAction = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING'])) 
    {
      $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
    }
    
    $id = $_GET['id'];
    $action = $_GET['action'];
    $submit = $_POST['Submit'];

    if ($action == "delete" && pageauth("subsite", "delete")) 
    {
        $delete = $data->delete_query("submenu", "id = '$id'");
        $action = "";		
        if ($delete)
        {   
            show_admin_message("Item deleted", "admin.php?page=subsite&subpage=submenu&sid=$siteid");   
        }  
    }
    
    
    if ($submit == "Submit") 
    {
        if ($action == "new" && pageauth("subsite", "add")) 
        {
            $name = safesql($_POST['name'], "text");
            $item = explode(".", $_POST['items']);
            if ($item[0] == "url")
            {
                $trans= array("&" => "&amp;");
                $url = strtr($_POST['url'], $trans);
                $url = safesql($url, "text");
                $type=5;
            }
            else
            {
                $url = safesql(NULL, "text");
                switch ($item[1])
                {
                    case "dyn":
                     $type = 2;
                     break;
                    case "stat":
                     $type = 1;
                     break;
                    case "art":
                     $type = 4;
                     break;
                    case "group":
                        $type=3;
                        break;
                }
            }
                      
            $item = $type == 5 ? $url : safesql($item[0], "text");
            
                     
            $pos = 1;
            do 
            {
                $temp = $data->select_query("submenu", "WHERE pos = '$pos' AND site=$safe_siteid");
                if ($data->num_rows($temp) != 0) 
                {
                    $pos++;
                }
            } while ($data->num_rows($temp) != 0); 

            $update = $data->insert_query("submenu", "'', $name, $item, $type, $safe_siteid, $pos");
            if ($update)
            {
                show_admin_message("Item added", "admin.php?page=subsite&subpage=submenu&sid=$siteid");
            }             
        } 
        elseif ($action == "edit" && pageauth("subsite", "edit")) 
        {
            $name = safesql($_POST['name'], "text");
            $item = explode(".", $_POST['items']);
            if ($item[0] == "url")
            {
                $trans= array("&" => "&amp;");
                $url = strtr($_POST['url'], $trans);
                $url = safesql($url, "text");
                $type=5;
            }
            else
            {
                $url = safesql(NULL, "text");
                switch ($item[1])
                {
                    case "dyn":
                     $type = 2;
                     break;
                    case "stat":
                     $type = 1;
                     break;
                    case "art":
                     $type = 4;
                     break;
                    case "group":
                        $type=3;
                        break;
                }
            }
                      
            $item = $type == 5 ? $url : safesql($item[0], "text");
            
            
            $itemsql = $data->select_fetch_one_row("submenu", "WHERE id=$id", "pos");
  
            
            $update = $data->update_query("submenu", "name = $name, item = $item, type=$type", "id=$id");
            
            if ($update)
            {
                show_admin_message("Item updated", "admin.php?page=subsite&subpage=submenu&sid=$siteid");
            }           
        }
    }
    
    if (($action =="") || ($action == "view")) 
    {
        $sql = $data->select_query("submenu", "WHERE site=$safe_siteid ORDER BY pos ASC");
        $numside = $data->num_rows($sql);
        $menuitems = array();
        while ($temp = $data->fetch_array($sql))
        {
            switch($temp['type'])
            {
                case 1:     //Static
                    $itemDetails = $data->select_fetch_one_row("static_content", "WHERE id='{$temp['item']}' AND type=2 AND pid=$safe_siteid", "name, friendly");
                    if (isset($itemDetails))
                    {
                        $temp['action'] = "Static Page: " . (isset($itemDetails['friendly']) ? $itemDetails['friendly'] : $itemDetails['name']);
                    }
                    else
                    {
                        $temp['action'] = "Static page could not be found";
                    }
                    break;
                case 2:     //dynamic
                    $itemDetails = $data->select_fetch_one_row("functions", "WHERE id='{$temp['item']}'", "name, type");
                    if (isset($itemDetails))
                    {
                        $temp['action'] = "Dynamic Page: " . $itemDetails['name'];
                    }
                    else
                    {
                        $temp['action'] = "Dynamic page could not be found";
                    }
                    break;
                case 3:     //Group
                    $itemDetails = $data->select_fetch_one_row("groups", "WHERE id='{$temp['item']}'", "teamname");
                    if (isset($itemDetails))
                    {
                        $temp['action'] = "Group Site: " . $itemDetails['teamname'];
                    }
                    else
                    {
                        $temp['action'] = "Group Site could not be found";
                    }
                    break;
                case 4:     //Article
                    $itemDetails = $data->select_fetch_one_row("patrol_articles", "WHERE ID='{$temp['item']}'", "title");
                    if (isset($itemDetails))
                    {
                        $temp['action'] = "Article: " . $itemDetails['title'];
                    }
                    else
                    {
                        $temp['action'] = "Article could not be found";
                    }
                    break;
                case 5:     //URL
                    $temp['action'] = "External Link: <a href=\"http://{$temp['item']}\">" . $temp['item'] . "</a>";
                    break;
                default:
                    $temp['action'] = "Unkown link type";  
            }
            $menuitems[] = $temp;
        }

        $tpl->assign("menuitems", $menuitems);
    } 
    elseif (($action == "new" && pageauth("subsite", "add")) || ($action == "edit" && pageauth("subsite", "edit"))) 
    {
        $sql = $data->select_query("functions", "WHERE type = 6", "id, name");
        $numfunc = $data->num_rows($sql);
        $func = array();
        while ($func[] = $data->fetch_array($sql));
        
        $sql = $data->select_query("static_content", "WHERE type=2 AND pid = $safe_siteid", "id, name, friendly");
        $numpages = $data->num_rows($sql);
        $pages = array();
        while ($pages[] = $data->fetch_array($sql));
        
        $sql = $data->select_query("groups", "WHERE ispublic=1 ORDER BY teamname ASC", "id, teamname");
        $numgroups = $data->num_rows($sql);
        $group = array();
        while ($group[] = $data->fetch_array($sql));
        
        $sql = $data->select_query("patrol_articles", "ORDER BY title ASC", "ID, title");
        $numarticles = $data->num_rows($sql);
        $articles = array();
        while ($articles[] = $data->fetch_array($sql)); 
        
        $tpl->assign('func', $func);
        $tpl->assign('numfunc', $numfunc);
        $tpl->assign('page', $pages);
        $tpl->assign('numpages', $numpages);
        $tpl->assign('group', $group);
        $tpl->assign('numgroups', $numgroups);
        $tpl->assign('articles', $articles);
        $tpl->assign('numarticles', $numarticles);
        
        if ($action == "edit") 
        {
            $sql = $data->select_query("submenu", "WHERE id='$id'");
            $item = $data->fetch_array($sql);
            $tpl->assign('item', $item);
        }
    } 
    elseif($action == "moveup" && pageauth("subsite", "edit"))
    {
        $sql = $data->select_query("submenu", "WHERE id=$id");
        $row = $data->fetch_array($sql);
        
        $pos1 = $row['pos'];
        $temppos = $pos1 -1;
        $sql = $data->select_query("submenu", "WHERE pos='$temppos' AND site=$safe_siteid");
        $row2 = $data->fetch_array($sql);
        
        $pos2= $row2['pos'];
        if ($pos2 == 0 || $pos1 == 0)
        {
            header("Location: $server"."?page=submenu&sid=$siteid");
        }
        $data->update_query("submenu", "pos=$pos2", "id='{$row['id']}'", "", "", false);
        $data->update_query("submenu", "pos=$pos1", "id='{$row2['id']}'", "", "", false);
        
        $server = $_SERVER['PHP_SELF'];
        header("Location: $server"."?page=subsite&subpage=submenu&sid=$siteid");
    }
    elseif($action == "movedown" && pageauth("subsite", "edit"))
    {
        $sql = $data->select_query("submenu", "WHERE id=$id");
        $row = $data->fetch_array($sql);
        
        $pos1 = $row['pos'];
        $temppos = $pos1 +1;
        $sql = $data->select_query("submenu", "WHERE pos='$temppos' AND site=$safe_siteid");
        $row2 = $data->fetch_array($sql);
        
        $pos2= $row2['pos'];
        $data->update_query("submenu", "pos=$pos2", "id={$row['id']}", "", "", false);
        $data->update_query("submenu", "pos=$pos1", "id={$row2['id']}", "", "", false);
        
        $server = $_SERVER['PHP_SELF'];
        header("Location: $server"."?page=subsite&subpage=submenu&sid=$siteid");
    }
    elseif ($action=="fixcat" && pageauth("subsite", "edit") == 1)
    {
        $sql = $data->select_query("submenu", "WHERE site=$safe_siteid ORDER BY pos ASC");
        if($data->num_rows($sql)>0)
        {
            $i = 1;
            while($temp=$data->fetch_array($sql))
            {
                $data->update_query("submenu", "pos=$i", "id={$temp['id']}");
                $i++;
            }
        }
    
        header("Location: $server"."?page=subsite&subpage=submenu&sid=$siteid");
    }
    
    $tpl->assign("sitename", $sitename);
    $tpl->assign("siteid", $siteid);
    $tpl->assign('cid', $cid);
    $tpl->assign('id', $id);
    $tpl->assign('action', $action);
    $tpl->assign('editFormAction', $editFormAction);
    $filetouse = "admin_submenu.tpl";
}
?>