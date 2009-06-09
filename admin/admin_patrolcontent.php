<?php
/**************************************************************************
    FILENAME        :   admin_patrolcontent.php
    PURPOSE OF FILE :   Manages patrol site content
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
	return;
}
else
{
    $patrolid = $_GET['pid'];
    $safe_patrolid = safesql($patrolid, "int");
    $temp = $data->select_fetch_one_row("groups", "WHERE id=$safe_patrolid", "teamname");
    $patrolname = $temp['teamname'];
    $safe_patrolname = safesql($patrolname, "text");
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
    if ($Submit == "Submit" && $action == "edit" && pageauth("patrol", "edit") == 1)
    {
        $trans= array("%7B" => "{", "%7D" => "}");
        $content = strtr($_POST['editor'], $trans);
        $content = safesql($content, "text", false);
        $public = safesql($_POST['public'], "int");
        $friendly = safesql($_POST['fname'], "text");
        
        if ($content == NULL || $content == "" ||$content == "NULL") $content = "Nothing here";
        
        $Update = $data->update_query("static_content", " content=$content, friendly=$friendly, special=$public", "id=$safe_id");    
        if ($Update)
        {
            show_admin_message("Content updated", "admin.php?page=patrol&subpage=patrolcontent&pid=$patrolid");
        }
        $action = "";
    } 
    elseif ($Submit == "Submit" && $action == "new" && pageauth("patrol", "add") == 1) 
    {
        $name = safesql(str_replace(" ", "", $_POST['name']), "text");
        $friendly = safesql($_POST['fname'], "text");
        $trans= array("%7B" => "{", "%7D" => "}");
        $content = strtr($_POST['editor'], $trans);
        $content = safesql($content, "text", false);
        if ($content == NULL || $content == "" ||$content == "NULL") $content = "Nothing here";
        $public = safesql($_POST['public'], "int");
        $Update = $data->insert_query("static_content", "NULL, $name, $content, $friendly, 1, 0, $safe_patrolid, $public, 0");
        if($Update)
        {
            show_admin_message("Content added", "admin.php?page=patrol&subpage=patrolcontent&pid=$patrolid");
        }
        $action = "";
    }
    
    // Show specific content
    if ($id != "")
    {
        // Show selected content
        $item  = $data->select_fetch_one_row("static_content", "WHERE id=$safe_id AND type=1 AND pid=$safe_patrolid");
    }
    
    if ($action=="edit") 
    {
        $tpl->assign("cmtags_active", true);
        $tpl->assign("cmtag_list", "Number of articles=\{\$groupstats.articles};Number of photo albums=\{\$groupstats.albums};Number of users=\{\$groupstats.users};Number of log book entries=\{\$groupstats.logbook}");
    }
    elseif ($action=="new")
    {
        $tpl->assign("cmtags_active", true);
        $tpl->assign("cmtag_list", "Number of articles=\{\$groupstats.articles};Number of photo albums=\{\$groupstats.albums};Number of users=\{\$groupstats.users};Number of log book entries=\{\$groupstats.logbook}");
    }
    elseif ($action=="delete" && pageauth("patrol", "delete") == 1) 
    {
        $delete = $data->update_query("static_content", "trash=1", "id=$safe_id");
        if ($delete)
        {   
            show_admin_message("Content sent to trash, Contact the Administrator if you wish to recover it.", "admin.php?page=patrol&subpage=patrolcontent&pid=$patrolid");
        }  
        $action = "";
    }
    elseif ($action == "putfront" && pageauth("patrol", "edit"))
    {
        $sql = $data->update_query("static_content", "frontpage=0", "type=1 AND pid=$safe_patrolid");
        $sql = $data->update_query("static_content", "frontpage=1", "type=1 AND id=$safe_id");
    }
    elseif ($action == "moveitem"  && pageauth("patrol", "edit") && !pageauth("patrol", "limit"))
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
            show_admin_message("Content moved", "admin.php?page=patrol&subpage=patrolcontent&pid=$patrolid");
        }
    }
    
    $result = $data->select_query("static_content", "WHERE type=1 AND pid=$safe_patrolid ORDER BY friendly ASC");
    
    $content = array();
    $content[] = $data->fetch_array($result);
    $numcontent = $data->num_rows($result);
    while ($content[] = $data->fetch_array($result));
 
    $tpl->assign("item", $item);
    $tpl->assign("patrolname", $patrolname);
    $tpl->assign("patrolid", $patrolid);
    $tpl->assign('name', $name);
    $tpl->assign('action', $action);
    $tpl->assign('numcontent', $numcontent);
    $tpl->assign('content', $content);
    $tpl->assign('editFormAction',$editFormAction);
    $tpl->assign("editor", true);
    $filetouse = "admin_patrolcontent.tpl";
}
?>