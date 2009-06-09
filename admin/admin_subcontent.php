<?php
/**************************************************************************
    FILENAME        :   admin_subcontent.php
    PURPOSE OF FILE :   Manages sub site content
    LAST UPDATED    :   20 February 2006
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
    $safe_siteid = safesql($siteid, "int");
    $temp = $data->select_fetch_one_row("subsites", "WHERE id=$safe_siteid", "name");
    $sitename = $temp['name'];
    $safe_sitename = safesql($sitename, "text");
    
    $editFormAction = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING'])) 
    {
      $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
    }
    $Submit = $_POST['Submit'];
    $id = $_GET['id'];
    $safe_id = safesql($id, "text");
    $action = $_GET['action'];
    
    // Edit content
    if ($Submit == "Submit" && $action == "edit" && pageauth("subsite", "edit"))
    {
        $trans= array("%7B" => "{", "%7D" => "}");
        $content = strtr($_POST['editor'], $trans);
        $content = safesql($content, "text", false);
        $friendly = safesql($_POST['fname'], "text");
        
        if ($content == NULL || $content == "" ||$content == "NULL") $content = "Nothing here";
        
        $Update = $data->update_query("static_content", " content=$content, friendly=$friendly", "id=$safe_id");    
        if ($Update)
        {
            show_admin_message("Content updated", "admin.php?page=subsite&subpage=subcontent&sid=$siteid"); 
        }
    } 
    elseif ($Submit == "Submit" && $action == "new" && pageauth("subsite", "add")) 
    {
        $name = safesql(str_replace(" ", "", $_POST['name']), "text");
        $friendly = safesql($_POST['fname'], "text");
        $trans= array("%7B" => "{", "%7D" => "}");
        $content = strtr($_POST['editor'], $trans);
        $content = safesql($content, "text", false);
        if ($content == NULL || $content == "" ||$content == "NULL") $content = "Nothing here";
        $public = safesql($_POST['public'], "int");
	if ($data->num_rows($data->select_query("static_content", "WHERE name=$name")) == 0)
	{
		$Update = $data->insert_query("static_content", "NULL, $name, $content, $friendly, 2, 0, $safe_siteid, 0, 0");
		if($Update)
		{
		    show_admin_message("Content added", "admin.php?page=subsite&subpage=subcontent&sid=$siteid");
		}
	}
	else
	{
		    show_admin_message("Item with that name already exists", "admin.php?page=subsite&subpage=subcontent&sid=$siteid&action=new", true);  
	}
    }
    
    // Show specific content
    if ($id != "")
    {
        // Show selected content
        $item = $data->select_fetch_one_row("static_content", "WHERE id=$safe_id AND type=2 AND pid=$safe_siteid");
    }
    
    if ($action=="delete" && pageauth("subsite", "delete") == 1) 
    {
        $delete = $data->update_query("static_content", "trash=1", "id=$safe_id");
        if ($delete)
        {   
            show_admin_message("Content sent to trash, Contact the Administrator if you wish to recover it.", "admin.php?page=subsite&subpage=subcontent&sid=$siteid");
        }  
        $action = "";
    }
    elseif ($action == "putfront" && pageauth("subsite", "edit"))
    {
        $sql = $data->update_query("static_content", "frontpage=0", "type=2 AND pid=$safe_siteid");
        $sql = $data->update_query("static_content", "frontpage=1", "type=2 AND id=$safe_id");
    }
    elseif ($action == "moveitem"  && pageauth("subsite", "edit"))
    {
        $sql = $data->select_query("groups", "WHERE ispublic = 1 ORDER BY teamname ASC");
        $patrols = array();
        $numpatrols = $data->num_rows($sql);
        while ($patrols[] = $data->fetch_array($sql));
        
        $sql = $data->select_query("subsites", "ORDER BY name ASC");
        $subsites = array();
        $numsubsites = $data->num_rows($sql);
        while ($subsites[] = $data->fetch_array($sql));
        
        $tpl->assign("numpatrols", $numpatrols);
        $tpl->assign("patrols", $patrols);
        $tpl->assign("numsubsites", $numsubsites);
        $tpl->assign("subsites", $subsites);
        if ($Submit == "Move")
        {
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
            
            $data->update_query("static_content", "type=$type, frontpage=0, pid=$pid", "id=$safe_id");
            show_admin_message("Content moved", "admin.php?page=subsite&subpage=subcontent&sid=$siteid");
        }
    }

    // Show all news
    $result = $data->select_query("static_content", "WHERE type=2 AND pid=$safe_siteid ORDER BY friendly ASC");
    
    $content = array();
    $content[] = $data->fetch_array($result);
    $numcontent = $data->num_rows($result);
    while ($content[] = $data->fetch_array($result));
    
    $tpl->assign("item", $item);
    $tpl->assign("siteid", $siteid);
    $tpl->assign("sitename", $sitename);
    $tpl->assign('name', $name);
    $tpl->assign('action', $action);
    $tpl->assign('numcontent', $numcontent);
    $tpl->assign('content', $content);
    $tpl->assign("editor", true);
    $tpl->assign('editFormAction',$editFormAction);
    $filetouse = "admin_subcontent.tpl";
}
?>