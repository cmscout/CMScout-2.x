<?php
/**************************************************************************
    FILENAME        :   admin_frontpage.php
    PURPOSE OF FILE :   Manage frontpage items
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
	$module['Module Management']['Frontpage Manager'] = "frontpage";
    $moduledetails[$modulenumbers]['name'] = "Frontpage Manager";
    $moduledetails[$modulenumbers]['details'] = "Management of frontpage items";
    $moduledetails[$modulenumbers]['access'] = "Allowed to access the frontpage manager";
    $moduledetails[$modulenumbers]['add'] = "Allowed to add an item on the frontpage";
    $moduledetails[$modulenumbers]['edit'] = "Allowed to modify an item on the frontpage";
    $moduledetails[$modulenumbers]['delete'] = "Allowed to delete an item from the frontpage";
    $moduledetails[$modulenumbers]['publish'] = "notused";
    $moduledetails[$modulenumbers]['limit'] = "notused";   
    $moduledetails[$modulenumbers]['id'] = "frontpage";

	return;
}
else
{
    
    $editFormAction = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING'])) 
    {
      $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
    }
    
    $id = safesql($_GET['id'], "int");
    $action = $_GET['action'];
    $submit = $_POST['Submit'];
    
    if ($action == "delete" && pageauth("frontpage", "delete") == 1) 
    {
        $sql = $data->delete_query("frontpage", "id = '$id'", "Frontpage Items", "$id removed from frontpage");
        $action = "";
        if ($sql)
        {
            show_admin_message("Item removed", "$pagename");
        }
    }
    
    if ($submit == "Submit") 
    {
        if ($action == "new" && pageauth("frontpage", "add") == 1) 
        {
            $temp = explode(".", $_POST['itemid']);
            $itemid = safesql($temp[0], "int");
            $type = safesql(($temp[1]=="dynamic" ? 1 : 0), "int"); 
            $pos = 1;
            do 
            {
                $temp = $data->select_query("frontpage", "WHERE pos = '$pos'");
                if ($data->num_rows($temp) != 0) {$pos++;}
            } while ($data->num_rows($temp) != 0); 	
            $sql = $data->insert_query("frontpage", "NULL, $itemid, $type, '$pos'");
            if ($sql)
            {
                show_admin_message("Item added", "$pagename");
            }
            $action = "";
        }
        elseif ($action == "edit" && pageauth("frontpage", "edit") == 1) 
        {
            $temp = explode(".", $_POST['itemid']);
            $itemid = safesql($temp[0], "int");
            $type = safesql(($temp[1]=="dynamic" ? 1 : 0), "int"); 
            $sql = $data->update_query("frontpage", "itemid = $itemid, type = $type", "id=$id");
            if ($sql)
            {
                show_admin_message("Item updated", "$pagename");
            }
            $action = "";
        }
    }
    
    if (($action =="") || ($action == "view"))
    {
        $sql = $data->select_query("frontpage", "ORDER BY pos ASC");
        $numfront = $data->num_rows($sql);
        $frontpages = array();
        while ($temp = $data->fetch_array($sql))
        {
            if ($temp['type'] == 0)
            {
                $temp2 = $data->select_fetch_one_row("static_content", "WHERE id={$temp['item']}");
                $temp['name'] = "<b>Static Page: </b>" . ($temp2['friendly'] == '' ? $temp2['name'] : $temp2['friendly']);
            }
            else
            {
                $temp2 = $data->select_fetch_one_row("functions", "WHERE id={$temp['item']}");
                $temp['name'] = "<b>Dynamic Page: </b>" . $temp2['name'];
            }
            $frontpages[] = $temp;
        }
        $tpl->assign('frontpages', $frontpages);
        $tpl->assign('numfront', $numfront);
    } 
    elseif (($action == "new") || ($action == "edit")) 
    {
        if ($action == "edit") 
        {
            $sql = $data->select_query("frontpage", "WHERE id=$id");
            $item = $data->fetch_array($sql);
            $tpl->assign('item', $item);
        }
        
        $sql = $data->select_query("functions", "WHERE type=2", "id, name");
        $numfunc = 0;
        $func = array();
        while ($temp = $data->fetch_array($sql))
        {
            $sql2 = $data->select_query("frontpage", "WHERE item='{$temp['id']}' AND type=1");
            if ($data->num_rows($sql2) == 0 || $item['item'] == $temp['id'])
            {
                $func[] = $temp;
                $numfunc++;
            }
        }
        
        $sql = $data->select_query("static_content", "WHERE type=0 ORDER BY friendly ASC", "id, name, friendly");
        $numpages = 0;
        $pages = array();
        while ($temp = $data->fetch_array($sql))
        {
            $sql2 = $data->select_query("frontpage", "WHERE item='{$temp['id']}' AND type=0");
            if ($data->num_rows($sql2) == 0 || $item['item'] == $temp['id'])
            {
                $pages[] = $temp;
                $numpages++;
            }
        }
        
        
        $tpl->assign('func', $func);
        $tpl->assign('numfunc', $numfunc);
        $tpl->assign('page', $pages);
        $tpl->assign('numpages', $numpages);
    }
    elseif($action == "moveup" && pageauth("frontpage", "edit") == 1)
    {
        $sql = $data->select_query("frontpage", "WHERE id=$id");
        $row = $data->fetch_array($sql);
        
        $pos1 = $row['pos'];
        $temppos = $pos1 -1;
        $sql = $data->select_query("frontpage", "WHERE pos='$temppos'");
        $row2 = $data->fetch_array($sql);
        
        $pos2= $row2['pos'];
        $data->update_query("frontpage", "pos=$pos2", "id={$row['id']}", "", "", false);
        $data->update_query("frontpage", "pos=$pos1", "id={$row2['id']}", "", "", false);
        
        $server = $_SERVER['PHP_SELF'];
        header("Location: $server"."?page=frontpage");
    }
    elseif($action == "movedown" && pageauth("frontpage", "edit") == 1)
    {
        $sql = $data->select_query("frontpage", "WHERE id=$id");
        $row = $data->fetch_array($sql);
        
        $pos1 = $row['pos'];
        $temppos = $pos1 +1;
        $sql = $data->select_query("frontpage", "WHERE pos='$temppos'");
        $row2 = $data->fetch_array($sql);
        
        $pos2= $row2['pos'];
        $data->update_query("frontpage", "pos=$pos2", "id={$row['id']}", "", "", false);
        $data->update_query("frontpage", "pos=$pos1", "id={$row2['id']}", "", "", false);
        
        $server = $_SERVER['PHP_SELF'];
        header("Location: $server"."?page=frontpage");
    }
    
    $tpl->assign('id', $id);
    $tpl->assign('action', $action);
    $tpl->assign('editFormAction', $editFormAction);
    $filetouse = "admin_frontpage.tpl";
}
?>