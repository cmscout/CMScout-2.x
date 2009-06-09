<?php
/**************************************************************************
    FILENAME        :   admin_content.php
    PURPOSE OF FILE :   Static Content Manager
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
	$module['Content Management']['Content Manager'] = "content";
    $moduledetails[$modulenumbers]['name'] = "Content Manager";
    $moduledetails[$modulenumbers]['details'] = "Manages static content items";
    $moduledetails[$modulenumbers]['access'] = "Allowed to access the content manager";
    $moduledetails[$modulenumbers]['add'] = "Allowed to add a new content page";
    $moduledetails[$modulenumbers]['edit'] = "Allowed to edit existing content pages";
    $moduledetails[$modulenumbers]['delete'] = "Allowed to delete existing content pages";
    $moduledetails[$modulenumbers]['publish'] = "notused";
    $moduledetails[$modulenumbers]['limit'] = "notused";
    $moduledetails[$modulenumbers]['id'] = "content";

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
    $id = $_GET['id'];
    $safe_id = safesql($id, "int");
    $action = $_GET['action'];
    
    // Edit content
    if ($Submit == "Submit" && $action == "edit" && pageauth("content", "edit") == 1)
    {
        $trans= array("%7B" => "{", "%7D" => "}");
        $content = strtr($_POST['editor'], $trans);
        $content = safesql($content, "text", false);
        $friendly = safesql($_POST['fname'], "text");
        if ($content == NULL || $content == "" || $content == "NULL") $content = safesql("Nothing here", "text");
        $Update = $data->update_query("static_content", " content=$content, friendly = $friendly", "id=$safe_id", "Content", "Updated $name");
        if ($Update)
        {   
            if ($_GET['main'] == 1)
            {
                $pagename = "index.php?page=$id&type=static";
            }
            show_admin_message("Page updated", "$pagename"); 
        } 
        $action = "";
    } 
    elseif ($Submit == "Submit" && $action == "new" && pageauth("content", "add") == 1) 
    {
        $name = safesql(str_replace(" ", "", $_POST['name']), "text");
        $trans= array("%7B" => "{", "%7D" => "}");
        $content = strtr($_POST['editor'], $trans);
        $content = safesql($content, "text", false);
        $friendly = safesql($_POST['fname'], "text");
        if ($content == NULL || $content == "" || $content == "NULL") $content = safesql("Nothing here", "text");
	if ($data->num_rows($data->select_query("static_content", "WHERE name=$name")) == 0)
	{
		$Update = $data->insert_query("static_content", "NULL, $name, $content, $friendly, 0, 0, 0, 0, 0", "id=$safe_id");
		if ($Update)
		{   
		   $item = $data->select_fetch_one_row("static_content", "WHERE name=$name", "id");
		    if($_POST['access'] == 1)
		    {
			$default = $data->select_fetch_one_row("auth", "WHERE authname={$config['defaultgroup']} AND type=2");
			$auth = unserialize($default['static']);
			$auth[$item['id']] = 1;
			$auth = safesql(serialize($auth), "text", true, true, true);
			$data->update_query("auth", "static=$auth", "id={$default['id']}");
		    }
		    if($_POST['gaccess'] == 1)
		    {
			$default = $data->select_fetch_one_row("auth", "WHERE authname=-1 AND type=1");
			$auth = unserialize($default['static']);
			$auth[$item['id']] = 1;
			$auth = safesql(serialize($auth), "text", true, true, true);
			$data->update_query("auth", "static=$auth", "id={$default['id']}");
		    }
		    show_admin_message("Content added", "$pagename");  
		}
	}
	else
	{
		    show_admin_message("Item with that name already exists", "$pagename&action=new", true);  
	}
        $action = "";
    }
    
    // Show specific content
    if ($id != "")
    {
        // Show selected content
        $Show = $data->select_query("static_content", "WHERE id='$id' AND trash=0");
        $ShowRow = $data->fetch_array($Show);
        $Showcontent = $ShowRow["content"];
        $name = $ShowRow['name'];
        $tpl->assign("contents", $ShowRow);
        $tpl->assign("editor", true);
    }
    
    if ($action=="delete" && pageauth("content", "delete") == 1) 
    {
        $delete = $data->update_query("static_content", "trash=1", "id=$safe_id");
        if ($delete)
        {   
            show_admin_message("Content sent to trash", "$pagename");
        }  
        $action = "";
    }
    elseif ($action=="new")
    {
        $tpl->assign("editor", true);
    }
    elseif ($action == "moveitem"  && pageauth("content", "edit"))
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
            show_admin_message("Content moved", "$pagename");
        }
    }
    else
    {
        $result = $data->select_query("static_content", "WHERE type=0 AND trash=0 ORDER BY friendly ASC");
        
        $content = array();
        $numcontent = $data->num_rows($result);
        while ($content[] = $data->fetch_array($result));
    }
    
    
    $tpl->assign('Showcontent', $Showcontent);
    $tpl->assign('name', $name);
    $tpl->assign('action', $action);
    $tpl->assign('numcontent', $numcontent);
    $tpl->assign('content', $content);
    $tpl->assign('editFormAction',$editFormAction);
    $filetouse = "admin_content.tpl";
}
?>
