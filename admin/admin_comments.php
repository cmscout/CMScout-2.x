<?php
/**************************************************************************
    FILENAME        :   admin_comments.php
    PURPOSE OF FILE :   Manage article comments
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
	$module['Content Management']['Comment Manager'] = "comments";
    $moduledetails[$modulenumbers]['name'] = "Comment Manager";
    $moduledetails[$modulenumbers]['details'] = "Manages user comments";
    $moduledetails[$modulenumbers]['access'] = "Allowed to access the comments manager";
    $moduledetails[$modulenumbers]['add'] = "notused";
    $moduledetails[$modulenumbers]['edit'] = "notused";
    $moduledetails[$modulenumbers]['delete'] = "Allowed to delete comments";
    $moduledetails[$modulenumbers]['publish'] = "Allowed to publish/unpublish comments";
    $moduledetails[$modulenumbers]['limit'] = "notused";
    $moduledetails[$modulenumbers]['id'] = "comments";

	return;
}
else
{
    $action = $_GET['action'];
    $id = $_GET['id'];
    if ($action == 'delete' && pageauth("comments", "delete") == 1) 
    {
        $sqlq = $data->delete_query("comments", "id=$id", "Comments", "Deleted Comment");
        if ($sqlq) 
        { 
            show_admin_message("Comment removed", "$pagename");
        }
    }
    elseif ($action == 'publish' && pageauth("comments", "publish") == 1) 
    {
        $sqlq = $data->update_query("comments", "allowed = 1", "id=$id", "Comments", "Published $id");
        header("Location: $pagename");
    }
    elseif ($action == 'unpublish' && pageauth("comments", "publish") == 1) 
    {
        $sqlq = $data->update_query("comments", "allowed = 0", "id=$id", "Comments", "Unpublished $id");
        header("Location: $pagename");
    }
    
    
    $note = $data->select_query("comments", "ORDER BY date DESC");
    
    if ($action == "") 
    {
        $totalRows_note = $data->num_rows($note);
        $row_note = array();
        while ($temp = $data->fetch_array($note))
        {
            if ($temp['type'] == 0)
            {
                $sql = $data->select_query("patrol_articles", "WHERE id = {$temp['item_id']}");
                $temp2 = $data->fetch_array($sql);
                $temp['title'] = $temp2['title'];
            }
            elseif ($temp['type'] == 1)
            {
                $sql = $data->select_query("album_track", "WHERE ID = {$temp['item_id']}");
                $temp2 = $data->fetch_array($sql);
                $temp['title'] = $temp2['album_name'];
            }
            $temp2 = $data->select_fetch_one_row("users", "WHERE id={$temp['uid']}", "uname");
            $temp['uname'] = $temp2['uname'];
            $row_note[] = $temp;
        }
    }
    $tpl->assign('comments', $row_note);
    $tpl->assign('number', $totalRows_note);
    $tpl->assign('action', $action);
    $filetouse = 'admin_comments.tpl';
}
?>