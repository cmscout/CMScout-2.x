<?php
/**************************************************************************
    FILENAME        :   admin_menus.php
    PURPOSE OF FILE :   Manages menus
    LAST UPDATED    :   03 October 2006
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

if( !empty($getmodules) )
{
	$module['Content Management']['Menu Manager'] = "menus";
    $moduledetails[$modulenumbers]['name'] = "Menu Manager";
    $moduledetails[$modulenumbers]['details'] = "Manages menu's";
    $moduledetails[$modulenumbers]['access'] = "Allowed to access the Menu Manager";
    $moduledetails[$modulenumbers]['add'] = "Allowed to add a new menu";
    $moduledetails[$modulenumbers]['edit'] = "Allowed to edit a existing menu";
    $moduledetails[$modulenumbers]['delete'] = "Allowed to delete a existing menu";
    $moduledetails[$modulenumbers]['publish'] = "Allowed to publish menu items";
    $moduledetails[$modulenumbers]['limit'] = "notused";
    $moduledetails[$modulenumbers]['id'] = "menus";

	return;
}
else
{
    function get_end_pos($catid, $parent=0)
    {
        global $data;
        
        $catid = safesql($catid, "int");
        $pos = 1;
        do 
        {
            if ($parent == 0)
            {
                $temp = $data->select_query("menu_items", "WHERE cat = $catid AND pos = '$pos'");
            }
            else
            {
                $temp = $data->select_query("menu_items", "WHERE cat = $catid AND pos = '$pos' AND parent=$parent");
            }
            if ($data->num_rows($temp) != 0) 
            {
                $pos++;
            }
        } while ($data->num_rows($temp) != 0); 
        return $pos;
    }
    
    $editFormAction = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING'])) 
    {
      $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
    }
    
    $id = isset($_GET['id']) ? safesql($_GET['id'], "int") : "";
    $cid = isset($_GET['cid']) ? safesql($_GET['cid'], "int") : "";
    $action = isset($_GET['action']) ? $_GET['action'] : "";
    $submit = isset($_POST['Submit']) ? $_POST['Submit'] : "";
    
    if ($action == "delcat" && pageauth("menus", "delete") == 1) 
    {
        $sql2 = $data->select_query("menu_cats", "WHERE id=$id", "side");
        $menu = $data->fetch_array($sql2);
        $sql = $data->delete_query("menu_items", "cat = '$id'");
        $data->delete_query("menu_cats", "id = '$id'", "Menus", "Deleted category $id");
        $action = "view";
        if ($sql)
        {
            show_admin_message("Category deleted", "$pagename&activetab={$menu['side']}");
        }
    } 
    elseif ($action == "delitem" && pageauth("menus", "delete") == 1) 
    {
        $rid = safesql($_GET['rid'], "int");
        $sql = $data->delete_query("menu_items", "parent=$rid");
        $sql = $data->delete_query("menu_items", "id = '$rid'", "Menus", "Deleted item $rid from $id");
        $data->update_query("menu_cats", "numitems = numitems - 1", "id = '$id'", "", "", false);
        $action = "catview";		
        if ($sql)
        {
            show_admin_message("Link deleted", "$pagename&id=$id&action=catview");
        }
    }
    
    if ($submit == "Submit") 
    {
        if ($action == "newitem" && pageauth("menus", "add") == 1) 
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
                    case "box":
                     $type = 3;
                     break;
                    case "dyn":
                     $type = 2;
                     break;
                    case "stat":
                     $type = 1;
                     break;
                    case "sub":
                     $type = 4;
                     break;
                    case "art":
                     $type = 6;
                     break;
                    case "group":
                        $type=7;
                        break;
                }
            }
            
            $item = $type==5 ? $url : safesql($item[0], "text");       
            $parent = isset($_POST['parent']) ? safesql($_POST['parent'], "int") : 0;
            $pos = get_end_pos($id, $parent);
            $target = safesql($_POST['target'], "text");
            
            $sql = $data->insert_query("menu_items", "NULL, $name,  $id, $item, $pos, $type, $parent, $target", "Menus", "Added menu item $name");
            $data->update_query("menu_cats", "numitems = numitems + 1", "id=$id", "", "", false);
            $action = "catview";
            if ($sql)
            {
                show_admin_message("Link added", "$pagename&id=$id&action=catview");
            }
        } 
        elseif ($action == "edititem" && pageauth("menus", "edit") == 1) 
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
                    case "box":
                     $type = 3;
                     break;
                    case "dyn":
                     $type = 2;
                     break;
                    case "stat":
                     $type = 1;
                     break;
                    case "sub":
                     $type = 4;
                     break;
                    case "art":
                     $type = 6;
                     break;
                    case "group":
                        $type=7;
                        break;
                }
            }
            
            $parent = isset($_POST['parent']) ? safesql($_POST['parent'], "int") : 0;
            
            $itemsql = $data->select_fetch_one_row("menu_items", "WHERE id=$id", "pos, parent");
  
            $pos = $itemsql['parent'] != $parent ? get_end_pos($_GET['cid'], $parent) : $itemsql['pos'];
            
            $target = safesql($_POST['target'], "text");

            $item = $type == 5 ? $url : safesql($item[0], "text");

            $sql = $data->update_query("menu_items", "name = $name, item = $item, type=$type, parent=$parent, target=$target, pos=$pos", "id=$id", "Menus", "Edited menu item $name");
            
            $action = "catview";
            $id = $_GET['cid'];
            if ($sql)
            {
                show_admin_message("Link updated", "$pagename&id=$id&action=catview");
            }
        } 
        elseif ($action == "newcat" && pageauth("menus", "add") == 1) 
        {
            $name = safesql($_POST['name'], "text");
            $side = safesql($_POST['location'], "text");
            $show = safesql($_POST['show'], "int");
            $showperm = safesql($_POST['showperm'], "int");
            $expanded = safesql($_POST['expanded'], "int");
            $groups = safesql(serialize($_POST['groups']), "text");
            $pos = 1;
            do 
            {
                $temp = $data->select_query("menu_cats", "WHERE position = '$pos' AND side=$side");
                if ($data->num_rows($temp) != 0) $pos++;
            } while ($data->num_rows($temp) != 0); 
            $sql = $data->insert_query("menu_cats", "NULL, $name, '0', '$pos', $side, '$show', '$showperm', '$expanded', 0, $groups", "Menus", "Added menu category $name");
            $action = "view";
            if ($sql)
            {
                 show_admin_message("Menu added", "$pagename&activetab={$_POST['location']}");
            }
        } 
        elseif ($action == "editcat" && pageauth("menus", "edit") == 1) 
        {
            $sql = $data->select_query("menu_cats", "WHERE id=$id");
            $oldcat = $data->fetch_array($sql);
            $name = safesql($_POST['name'], "text");
            $side = safesql($_POST['location'], "text");
            $expanded = safesql($_POST['expanded'], "int");
            $groups = safesql(serialize($_POST['groups']), "text");
            $pos = $oldcat['position'];
            if ($_POST['location'] != $oldcat['side'])
            {
                $pos = 1;
                do 
                {
                    $temp = $data->select_query("menu_cats", "WHERE position = '$pos' AND side=$side");
                    if ($data->num_rows($temp) != 0) $pos++;
                } while ($data->num_rows($temp) != 0); 
            }
            $showperm = safesql($_POST['showperm'], "int");
            $show = safesql($_POST['show'], "int");
            $sql = $data->update_query("menu_cats", "name =$name, position=$pos, side = $side, showhead='$show', showwhen = '$showperm', expanded='$expanded', `groups`=$groups", "id=$id", "Menus", "Edited menu category $name");
            $action = "view";
            if ($sql)
            {
                show_admin_message("Menu updated", "$pagename&activetab={$_POST['location']}");
            }
        }
    }
    
    if (($action =="") || ($action == "view")) 
    {
        $sql = $data->select_query("menu_cats", "WHERE side='left' ORDER BY position ASC");
        $numleft = $data->num_rows($sql);
        $left = array();
        $left[] = $data->fetch_array($sql);
        while ($left[] = $data->fetch_array($sql));
    
        $sql = $data->select_query("menu_cats", "WHERE side='right' ORDER BY position ASC");
        $numright = $data->num_rows($sql);
        $right = array();
        $right[] = $data->fetch_array($sql);
        while ($right[] = $data->fetch_array($sql));
    
        $sql = $data->select_query("menu_cats", "WHERE side='top' ORDER BY position ASC");
        $numtop = $data->num_rows($sql);
        $top = array();
        $top[] = $data->fetch_array($sql);
        while ($top[] = $data->fetch_array($sql));
    
        $tpl->assign("numleft", $numleft);
        $tpl->assign("left", $left);
        $tpl->assign("numright", $numright);
        $tpl->assign("right", $right);
        $tpl->assign("numtop", $numtop);
        $tpl->assign("top", $top);
    } 
    elseif (($action == "newitem" && pageauth("menus", "add") == 1) || ($action == "edititem" && pageauth("menus", "edit") == 1)) 
    {       
        $sql = $data->select_query("static_content", "WHERE type=0 AND trash=0 ORDER BY friendly ASC", "id, name, friendly");
        $numpages = $data->num_rows($sql);
        $pages = array();
        while ($pages[] = $data->fetch_array($sql));
        
        $sql = $data->select_query("subsites", "ORDER BY name ASC", "id, name");
        $numsub = $data->num_rows($sql);
        $subsite = array();
        while ($subsite[] = $data->fetch_array($sql));

        $sql = $data->select_query("patrol_articles", "WHERE trash=0 ORDER BY title ASC", "ID, title");
        $numarts = $data->num_rows($sql);
        $articles = array();
        while ($articles[] = $data->fetch_array($sql));

        $sql = $data->select_query("groups", "WHERE ispublic = 1 ORDER BY teamname ASC", "id, teamname");
        $numgroups = $data->num_rows($sql);
        $groups = array();
        while ($groups[] = $data->fetch_array($sql));


        if ($action != "edititem")
        {
            $sql = $data->select_query("menu_items", "WHERE cat=$id AND (type=1 OR type=2 OR type=4) AND parent=0");
        }
        else
        {
            $sql = $data->select_query("menu_items", "WHERE cat=$cid AND (type=1 OR type=2 OR type=4) AND parent=0 AND id != $id");
        }

        $numparents = $data->num_rows($sql);
        $parent = array();
        while ($parent[] = $data->fetch_array($sql));	
        
        $tpl->assign('parent', $parent);
        $tpl->assign('numparents', $numparents);
        $tpl->assign('page', $pages);
        $tpl->assign('numpages', $numpages);
        $tpl->assign('subsite', $subsite);
        $tpl->assign('numsub', $numsub);
        $tpl->assign('articles', $articles);
        $tpl->assign('numarts', $numarts);
        $tpl->assign('groups', $groups);
        $tpl->assign('numgroups', $numgroups);
        
        $func = array();
        $numfunc = 0;
        $sql = $data->select_query("functions", "WHERE type=2 OR type=1 ORDER BY name ASC", "id, name, type");
        $numfunc = $data->num_rows($sql);
        while ($func[] = $data->fetch_array($sql));
    
        $tpl->assign('func', $func);
        $tpl->assign('numfunc', $numfunc);
    
        if ($action == "edititem")
        {
            $sql = $data->select_query("menu_cats", "WHERE id = $cid");
        }
        else
        {
            $sql = $data->select_query("menu_cats", "WHERE id = $id");
        }
        $bit = $data->fetch_array($sql);
        
        $tpl->assign("catname", $bit['name']);
        if ($action == "edititem") 
        {
            $sql = $data->select_query("menu_items", "WHERE id='$id'");
            $item = $data->fetch_array($sql);
            $tpl->assign('item', $item);
        }
    } 
    elseif ($action == "catview") 
    {
        $sql = $data->select_query("menu_items", "WHERE cat=$id AND parent=0 ORDER BY pos ASC");
        $sql2 = $data->select_query("menu_cats", "WHERE id=$id");
        $menu = $data->fetch_array($sql2);
        $numitems = $data->num_rows($sql);
        $menuitems = array();
        while ($temp = $data->fetch_array($sql))
        {
            switch($temp['type'])
            {
                case 1:
                    $itemDetails = $data->select_fetch_one_row("static_content", "WHERE id='{$temp['item']}' AND trash=0", "name, friendly");
                    if (isset($itemDetails))
                    {
                        $temp['action'] = "Static Page: " . (isset($itemDetails['friendly']) ? $itemDetails['friendly'] : $itemDetails['name']);
                    }
                    else
                    {
                        $temp['action'] = "Static page could not be found";
                    }
                    break;
                case 2:
                    $itemDetails = $data->select_fetch_one_row("functions", "WHERE id='{$temp['item']}'", "name");
                    if (isset($itemDetails))
                    {
                        $temp['action'] = "Dynamic Page: " . $itemDetails['name'];
                    }
                    else
                    {
                        $temp['action'] = "Dynamic page could not be found";
                    }
                    break;
                case 3:
                    $itemDetails = $data->select_fetch_one_row("functions", "WHERE id='{$temp['item']}'", "name");
                    if (isset($itemDetails))
                    {
                        $temp['action'] = "Side Box: " . $itemDetails['name'];
                    }
                    else
                    {
                        $temp['action'] = "Side Box could not be found";
                    }
                    break;
                case 4:
                    $itemDetails = $data->select_fetch_one_row("subsites", "WHERE id='{$temp['item']}'", "name");
                    if (isset($itemDetails))
                    {
                        $temp['action'] = "Sub Site: " . $itemDetails['name'];
                    }
                    else
                    {
                        $temp['action'] = "Sub Site could not be found";
                    }
                    break;
                case 5:
                    $temp['action'] = "External Link: <a href=\"http://{$temp['item']}\">" . $temp['item'] . "</a>";
                    break;
                case 6:
                    $itemDetails = $data->select_fetch_one_row("patrol_articles", "WHERE ID='{$temp['item']}' AND trash=0", "title");
                    if (isset($itemDetails))
                    {
                        $temp['action'] = "Article: " . $itemDetails['title'];
                    }
                    else
                    {
                        $temp['action'] = "Article could not be found";
                    }
                    break;
                case 7:
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
                default:
                    $temp['action'] = "Unkown link type";                    
            }
            
            $sql2 = $data->select_query("menu_items", "WHERE parent='{$temp['id']}' ORDER BY pos ASC");
            $temp['subnumber'] = $data->num_rows($sql2);
            $temp['subitems'] = array();
            while ($temp2 = $data->fetch_array($sql2))
            {
                switch($temp2['type'])
                {
                    case 1:
                        $itemDetails = $data->select_fetch_one_row("static_content", "WHERE id='{$temp2['item']}' AND trash=0", "name, friendly");
                        if (isset($itemDetails))
                        {
                            $temp2['action'] = "Static Page: " . (isset($itemDetails['friendly']) ? $itemDetails['friendly'] : $itemDetails['name']);
                        }
                        else
                        {
                            $temp2['action'] = "Static page could not be found";
                        }
                        break;
                    case 2:
                        $itemDetails = $data->select_fetch_one_row("functions", "WHERE id='{$temp2['item']}'", "name");
                        if (isset($itemDetails))
                        {
                            $temp2['action'] = "Dynamic Page: " . $itemDetails['name'];
                        }
                        else
                        {
                            $temp2['action'] = "Dynamic page could not be found";
                        }
                        break;
                    case 3:
                        $itemDetails = $data->select_fetch_one_row("functions", "WHERE id='{$temp2['item']}'", "name");
                        if (isset($itemDetails))
                        {
                            $temp2['action'] = "Side Box: " . $itemDetails['name'];
                        }
                        else
                        {
                            $temp2['action'] = "Side Box could not be found";
                        }
                        break;
                    case 4:
                        $itemDetails = $data->select_fetch_one_row("subsites", "WHERE id='{$temp2['item']}'", "name");
                        if (isset($itemDetails))
                        {
                            $temp2['action'] = "Sub Site: " . $itemDetails['name'];
                        }
                        else
                        {
                            $temp2['action'] = "Sub Site could not be found";
                        }
                        break;
                    case 5:
                        $temp2['action'] = "External Link: <a href=\"http://{$temp2['item']}\">" . $temp2['item'] . "</a>";
                        break;
                    case 6:
                        $itemDetails = $data->select_fetch_one_row("patrol_articles", "WHERE ID='{$temp2['item']}' AND trash=0", "title");
                        if (isset($itemDetails))
                        {
                            $temp2['action'] = "Article: " . $itemDetails['title'];
                        }
                        else
                        {
                            $temp2['action'] = "Article could not be found";
                        }
                        break;
                    case 7:
                        $itemDetails = $data->select_fetch_one_row("groups", "WHERE id='{$temp2['item']}'", "teamname");
                        if (isset($itemDetails))
                        {
                            $temp2['action'] = "Group Site: " . $itemDetails['teamname'];
                        }
                        else
                        {
                            $temp2['action'] = "Group Site could not be found";
                        }
                        break;
                    default:
                        $temp2['action'] = "Unkown link type";                    
                }
                $temp['subitems'][] = $temp2;
            }
            $menuitems[] = $temp;
        }
        $tpl->assign('item', $menuitems);
        $tpl->assign('numitems', $numitems);
        $tpl->assign('menu', $menu);
    } 
    elseif($action == "editcat" && pageauth("menus", "edit") == 1)
    {
        $sql = $data->select_query("groups", "ORDER BY teamname ASC", "id, teamname");
        $numgroups = $data->num_rows($sql);
        $groups = array();
        while ($groups[] = $data->fetch_array($sql));
        $tpl->assign('group', $groups);
        $tpl->assign('numgroups', $numgroups);
        $sql2 = $data->select_query("menu_cats", "WHERE id='$id'");
        $menu = $data->fetch_array($sql2);
        $menu['groups'] = unserialize($menu['groups']);
        $tpl->assign('menu', $menu);
    }
    elseif($action == "newcat" && pageauth("menus", "add") == 1)
    {
        $sql = $data->select_query("groups", "ORDER BY teamname ASC", "id, teamname");
        $numgroups = $data->num_rows($sql);
        while ($groups[] = $data->fetch_array($sql));
        $tpl->assign('side', $_GET['side']);
        $tpl->assign('group', $groups);
        $tpl->assign('numgroups', $numgroups);
    }
    elseif($action == "moveup" && pageauth("menus", "edit") == 1)
    {
        $sql = $data->select_query("menu_cats", "WHERE id=$id");
        $row = $data->fetch_array($sql);
        
        $pos1 = $row['position'];
    
        $temppos = $pos1 - 1;
        $sql = $data->select_query("menu_cats", "WHERE side='{$row['side']}' AND position='$temppos'");
        $row2 = $data->fetch_array($sql);
        
        $pos2= $row2['position'];
        if ($pos2 == 0 || $pos1 == 0)
            header("Location: $server"."?page=menus&activetab={$row['side']}"); 
            
        $data->update_query("menu_cats", "position=$pos2", "id={$row['id']}");
        $data->update_query("menu_cats", "position=$pos1", "id={$row2['id']}");
        
        $server = $_SERVER['PHP_SELF'];
        header("Location: $server"."?page=menus");
    }
    elseif($action == "movedown" && pageauth("menus", "edit") == 1)
    {
        $sql = $data->select_query("menu_cats", "WHERE id=$id");
        $row = $data->fetch_array($sql);
        
        $pos1 = $row['position'];
        $temppos = $pos1 +1;
        $sql = $data->select_query("menu_cats", "WHERE side='{$row['side']}' AND position='$temppos'");
        $row2 = $data->fetch_array($sql);
        
        $pos2= $row2['position'];
        $data->update_query("menu_cats", "position=$pos2", "id={$row['id']}", "", "", false);
        $data->update_query("menu_cats", "position=$pos1", "id={$row2['id']}", "", "", false);
        
        $server = $_SERVER['PHP_SELF'];
        header("Location: $server"."?page=menus&activetab={$row['side']}");
    }
    elseif($action == "moveitemup" && pageauth("menus", "edit") == 1)
    {
        $sql = $data->select_query("menu_items", "WHERE id='$id'");
        $row = $data->fetch_array($sql);
        
        $pos1 = $row['pos'];
        $temppos = $pos1-1;
        if ($row['parent'] == 0)
        {
            $sql = $data->select_query("menu_items", "WHERE cat='$cid' AND pos='$temppos'");
        }
        else
        {
            $sql = $data->select_query("menu_items", "WHERE cat='$cid' AND pos='$temppos' AND parent='{$row['parent']}'");
        }
        $row2 = $data->fetch_array($sql);
        
        $pos2 = $row2['pos'];
        
        if ($pos2 == 0 || $pos1 == 0)
            header("Location: $server"."?page=menus&action=catview&id=$cid"); 
            
        $data->update_query("menu_items", "pos='$pos2'", "id={$row['id']}", "", "", false);
        $data->update_query("menu_items", "pos='$pos1'", "id={$row2['id']}", "", "", false);
        
        $server = $_SERVER['PHP_SELF'];
        header("Location: $server"."?page=menus&action=catview&id=$cid");
    }
    elseif($action == "moveitemdown" && pageauth("menus", "edit") == 1)
    {
        $sql = $data->select_query("menu_items", "WHERE id='$id'");
        $row = $data->fetch_array($sql);
        
        $pos1 = $row['pos'];
        $temppos = $pos1 + 1;
        if ($row['parent'] == 0)
        {
            $sql = $data->select_query("menu_items", "WHERE cat='$cid' AND pos='$temppos'");
        }
        else
        {
            $sql = $data->select_query("menu_items", "WHERE cat='$cid' AND pos='$temppos' AND parent='{$row['parent']}'");
        }
        $row2 = $data->fetch_array($sql);
        
        $pos2 = $row2['pos'];
        $data->update_query("menu_items", "pos='$pos2'", "id={$row['id']}", "", "", false);
        $data->update_query("menu_items", "pos='$pos1'", "id={$row2['id']}", "", "", false);
        
        $server = $_SERVER['PHP_SELF'];
        header("Location: $server"."?page=menus&action=catview&id=$cid");
    }
    elseif ($action=="fixcat" && pageauth("menus", "edit") == 1)
    {
        $sql = $data->select_query("menu_cats", "WHERE side='left' ORDER BY position ASC");
        if($data->num_rows($sql)>0)
        {
            $i = 1;
            while($temp=$data->fetch_array($sql))
            {
                $data->update_query("menu_cats", "position=$i", "id={$temp['id']}");
                $i++;
                $j=1;
                $sql2 = $data->select_query("menu_items", "WHERE cat={$temp['id']} ORDER BY pos ASC");
                while($temp2=$data->fetch_array($sql2))
                {
                    $data->update_query("menu_items", "pos=$j", "id={$temp2['id']}");
                    $j++;
                }
            }
        }
    
        $sql = $data->select_query("menu_cats", "WHERE side='right' ORDER BY position ASC");
        if($data->num_rows($sql)>0)
        {
            $i = 1;
            while($temp=$data->fetch_array($sql))
            {
                $data->update_query("menu_cats", "position=$i", "id={$temp['id']}");
                $i++;
                $j=1;
                $sql2 = $data->select_query("menu_items", "WHERE cat={$temp['id']} ORDER BY pos ASC");
                while($temp2=$data->fetch_array($sql2))
                {
                    $data->update_query("menu_items", "pos=$j", "id={$temp2['id']}");
                    $j++;
                }
            }
        }
    
        $sql = $data->select_query("menu_cats", "WHERE side='top' ORDER BY position ASC");
        if($data->num_rows($sql)>0)
        {
            $i = 1;
            while($temp=$data->fetch_array($sql))
            {
                $data->update_query("menu_cats", "position=$i", "id={$temp['id']}");
                $i++;
                $j=1;
                $sql2 = $data->select_query("menu_items", "WHERE cat={$temp['id']} ORDER BY pos ASC");
                while($temp2=$data->fetch_array($sql2))
                {
                    $data->update_query("menu_items", "pos=$j", "id={$temp2['id']}");
                    $j++;
                }
            }
        }
    
         header("Location: $server"."?page=menus");
    }
    elseif ($action == "publish" && pageauth("menus", "publish") == 1)
    {
        $id = safesql($_GET['id'], "int");
        $data->update_query("menu_cats", "published=1", "id=$id");
        show_admin_message("Menu published", "admin.php?page=menus&activetab={$_GET['activetab']}");
    }
    elseif ($action == "unpublish" && pageauth("menus", "publish") == 1)
    {
        $id = safesql($_GET['id'], "int");
        $data->update_query("menu_cats", "published=0", "id=$id");
        show_admin_message("Menu unpublished", "admin.php?page=menus&activetab={$_GET['activetab']}");
    }
    elseif ($action == "moveitem" && pageauth("menus", "edit") == 1)
    {
        $sql = $data->select_query("menu_cats", "ORDER BY name ASC");
        $numcats = $data->num_rows($sql);
        $cats = array();
        while ($cats[] = $data->fetch_array($sql));
        $tpl->assign('numcats', $numcats);
        $tpl->assign('cats', $cats);
        
        if ($submit == "Move") 
        {
            $newcat = safesql($_POST['newcat'], "int");
            $id = safesql($_GET['id'], "int");
            $cid = $_GET['cid'];
            $pos = safesql(get_end_pos($_POST['newcat']),"int");
            
            $data->update_query("menu_items", "cat=$newcat, pos=$pos", "id=$id OR parent=$id");
            show_admin_message("Item moved", "$pagename&id=$cid");
        }
    }
    
    $tpl->assign("activetab", $_GET['side']);
    $tpl->assign('cid', $cid);
    $tpl->assign('id', $id);
    $tpl->assign('action', $action);
    $tpl->assign('editFormAction', $editFormAction);
    $filetouse = "admin_menus.tpl";
}
?>