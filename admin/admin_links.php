<?php
/**************************************************************************
    FILENAME        :   admin_links.php
    PURPOSE OF FILE :   Manage links and link categories
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
	$module['Content Management']['Web Links Manager'] = "links";
    $moduledetails[$modulenumbers]['name'] = "Web Links Manager";
    $moduledetails[$modulenumbers]['details'] = "Manages web links and web link categories";
    $moduledetails[$modulenumbers]['access'] = "Allowed to access the Web links Manager";
    $moduledetails[$modulenumbers]['add'] = "Allowed to add links and categories";
    $moduledetails[$modulenumbers]['edit'] = "Allowed to edit links and categories";
    $moduledetails[$modulenumbers]['delete'] = "Allowed to delete links and categories";
    $moduledetails[$modulenumbers]['publish'] = "notused";
    $moduledetails[$modulenumbers]['limit'] = "notused";
    $moduledetails[$modulenumbers]['id'] = "links";    
	return;
}
else
{
    
    $editFormAction = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING'])) 
    {
      $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
    }
    
    $action = $_GET['action'];
    $id = safesql($_GET['id']);
    $did =safesql($_GET['did']);
    
    if ($action == 'delete' && pageauth("links", "delete") == 1) 
    {
        $sqlq = $data->delete_query("links_cats", "id=$id");
        if ($sqlq) 
        { 
            $sqlq = $data->delete_query("links","cat=$id");		
            if ($sqlq)
            {
                show_admin_message("Link category deleted", "$pagename");
            }
        }
    } 
    elseif ($action == "deletedown" && pageauth("links", "delete") == 1) 
    {
        $sqlq = $data->delete_query("links", "id=$did");
        $action = "view";
        if ($sqlq)
        {
            show_admin_message("Link deleted", "$pagename&action=view&id=$id");
        }
    }

    function get_end_pos($type, $catid=0)
    {
        global $data;
        
        $pos = 1;
        do 
        {
            if ($parent == 0)
            {
                $temp = $data->select_query("$type", "WHERE position = '$pos'");
            }
            else
            {
                $temp = $data->select_query("$type", "WHERE cat = $catid AND position = '$pos'");
            }
            if ($data->num_rows($temp) != 0) 
            {
                $pos++;
            }
        } while ($data->num_rows($temp) != 0); 
        return $pos;
    }
    
    $submit = $_POST['Submit'];
    if ($submit == "Submit") 
    {
        if ($action == "addlink" && pageauth("links", "add") == 1) 
        {
            $name = safesql($_POST['name'], "text");
            $desc = safesql($_POST['desc'], "text");
            $url = safesql($_POST['url'], "text");
	   $pos = get_end_pos("links", $id);
            $sql = $data->insert_query("links", "NULL, $name, $url, $desc, '$id', $pos");
            $action="view";
            if ($sql)
            {
                show_admin_message("Link added", "$pagename&action=view&id=$id");
            }
        } 
        elseif ($action == "editlink" && pageauth("links", "edit") == 1) 
        {
            $name = safesql($_POST['name'], "text");
            $desc = safesql($_POST['desc'], "text");
            $url = safesql($_POST['url'], "text");
            $cat = safesql($_POST['cat'], "text");
            $sql = $data->update_query("links", "name=$name, url=$url, `desc`=$desc, cat=$cat", "id=$did");	
            if ($sql)
            {
                show_admin_message("Link updated", "$pagename&action=view&id=$id");
            }
        }
        elseif ($action == "add" && pageauth("links", "add") == 1)
        {
            $catname = safesql($_POST['catname'], "text");
            
	   $pos = get_end_pos("links_cats");
            
            $sql = $data->insert_query("links_cats", "NULL, $catname, $pos");
            if ($sql)
            {
                show_admin_message("Category added", "$pagename");
            }
        }
        elseif ($action == "edit" && pageauth("links", "edit") == 1)
        {
            $catname = safesql($_POST['catname'], "text");
            
            $sql = $data->update_query("links_cats", "name = $catname", "id = $id");
            if ($sql)
            {
                show_admin_message("Category updated", "$pagename");
            }
        }
    }
    
    if ($action == "view") 
    {
        $query = $data->select_query("links_cats", "WHERE id = $id ORDER BY position ASC");
        $catinfo = $data->fetch_array($query);
        $down_query = $data->select_query("links", "WHERE cat='$id' ORDER BY position ASC");
        $numlinks = $data->num_rows($down_query);
        $links = array();
        while ($links[] = $data->fetch_array($down_query));
        $tpl->assign("links", $links);
        $tpl->assign("numlinks", $numlinks);
        $tpl->assign("catinfo", $catinfo);
        $tpl->assign('id', $id);
    } 
    elseif ($action == "addlink") 
    {
    } 
    elseif ($action == "editlink") 
    {
        $que = $data->select_query("links", "WHERE id = '$did'");
        $links = $data->fetch_array($que);
        $sql = $data->select_query("links_cats", "ORDER BY position ASC");
        $cats = array();
        $numcats = $data->num_rows($sql);
        while($cats[] = $data->fetch_array($sql));
        
        $tpl->assign('cat', $cats);
        $tpl->assign('numcats', $numcats);
        $tpl->assign('links', $links);
    } 
    elseif ($action == "edit")
    {
        $sql = $data->select_query("links_cats", "WHERE id = $id");
        $cat = $data->fetch_array($sql);
    
        $tpl->assign("cat", $cat);
    }
    elseif ($action == "add")
    {

    }
    elseif ($action=="fixcat" && pageauth("links", "edit") == 1)
    {
        $sql = $data->select_query("links_cats", "ORDER BY position ASC");
        if($data->num_rows($sql)>0)
        {
            $i = 1;
            while($temp=$data->fetch_array($sql))
            {
                $data->update_query("links_cats", "position=$i", "id={$temp['id']}");
                $i++;
                $j=1;
                $sql2 = $data->select_query("links", "WHERE cat={$temp['id']} ORDER BY position ASC");
                while($temp2=$data->fetch_array($sql2))
                {
                    $data->update_query("links", "position=$j", "id={$temp2['id']}");
                    $j++;
                }
            }
        }
    
         header("Location: $server"."?page=links");
    }    
    elseif($action == "moveup" && pageauth("links", "edit") == 1)
    {
        $sql = $data->select_query("links_cats", "WHERE id=$id");
        $row = $data->fetch_array($sql);
        
        $pos1 = $row['position'];
    
        $temppos = $pos1 - 1;
        $sql = $data->select_query("links_cats", "WHERE position='$temppos'");
        $row2 = $data->fetch_array($sql);
        
        $pos2= $row2['position'];
        if ($pos2 == 0 || $pos1 == 0)
            header("Location: $server"."?page=links"); 
            
        $data->update_query("links_cats", "position=$pos2", "id={$row['id']}");
        $data->update_query("links_cats", "position=$pos1", "id={$row2['id']}");
        
        $server = $_SERVER['PHP_SELF'];
        header("Location: $server"."?page=links");
    }
    elseif($action == "movedown" && pageauth("links", "edit") == 1)
    {
        $sql = $data->select_query("links_cats", "WHERE id=$id");
        $row = $data->fetch_array($sql);
        
        $pos1 = $row['position'];
        $temppos = $pos1 +1;
        $sql = $data->select_query("links_cats", "WHERE position='$temppos'");
        $row2 = $data->fetch_array($sql);
        
        $pos2= $row2['position'];
        $data->update_query("links_cats", "position=$pos2", "id={$row['id']}", "", "", false);
        $data->update_query("links_cats", "position=$pos1", "id={$row2['id']}", "", "", false);
        
        $server = $_SERVER['PHP_SELF'];
        header("Location: $server"."?page=links");
    }
    elseif($action == "moveitemup" && pageauth("links", "edit") == 1)
    {
        $sql = $data->select_query("links", "WHERE id='$did'");
        $row = $data->fetch_array($sql);
        
        $pos1 = $row['position'];
        $temppos = $pos1-1;
        $sql = $data->select_query("links", "WHERE cat='$id' AND position='$temppos'");

        $row2 = $data->fetch_array($sql);
        
        $pos2 = $row2['position'];
        
        if ($pos2 == 0 || $pos1 == 0)
            header("Location: $server"."?page=links&action=view&id=$id"); 
            
        $data->update_query("links", "position='$pos2'", "id={$row['id']}", "", "", false);
        $data->update_query("links", "position='$pos1'", "id={$row2['id']}", "", "", false);
        
        $server = $_SERVER['PHP_SELF'];
        header("Location: $server"."?page=links&action=view&id=$id");
    }
    elseif($action == "moveitemdown" && pageauth("links", "edit") == 1)
    {
        $sql = $data->select_query("links", "WHERE id='$did'");
        $row = $data->fetch_array($sql);
        
        $pos1 = $row['position'];
        $temppos = $pos1 + 1;

        $sql = $data->select_query("links", "WHERE cat='$id' AND position='$temppos'");

        $row2 = $data->fetch_array($sql);
        
        $pos2 = $row2['position'];
        $data->update_query("links", "position='$pos2'", "id={$row['id']}", "", "", false);
        $data->update_query("links", "position='$pos1'", "id={$row2['id']}", "", "", false);
        
        $server = $_SERVER['PHP_SELF'];
        header("Location: $server"."?page=links&action=view&id=$id");
    }
    else 
    {
        $cats = $data->select_query("links_cats", "ORDER BY position ASC");
        $row_cats = array();
        $num_cats = $data->num_rows($cats);
        while ($row_cats[] = $data->fetch_array($album)); 
        $tpl->assign('cats', $row_cats);
        $tpl->assign('num_cats', $num_cats);
    }
    
    $tpl->assign('editFormAction', $editFormAction);
    $tpl->assign('id', $id);
    $tpl->assign('action', $action);
    $filetouse = 'admin_links.tpl';
}
?>