<?php
/**************************************************************************
    FILENAME        :   admin_advancements.php
    PURPOSE OF FILE :   Manages award schemes
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
	$module['Module Management']['Award Scheme Manager'] = "advancements";
    $moduledetails[$modulenumbers]['name'] = "Award Scheme Manager";
    $moduledetails[$modulenumbers]['details'] = "Management of different award schemes";
    $moduledetails[$modulenumbers]['access'] = "Allowed to access award schemes module";
    $moduledetails[$modulenumbers]['add'] = "Allowed to add award schemes, advancement badges and requirements";
    $moduledetails[$modulenumbers]['edit'] = "Allowed to edit award schemes, advancement badges and requirements";
    $moduledetails[$modulenumbers]['delete'] = "Allowed to delete award schemes, advancement badges and requirements";
    $moduledetails[$modulenumbers]['publish'] = "notused";
    $moduledetails[$modulenumbers]['limit'] = "notused";
    $moduledetails[$modulenumbers]['id'] = "advancements";
    
    return;
}
else
{  
    $editFormAction = $_SERVER['PHP_SELF'];
    $editFormAction .= "?page=advancements";
    
    $id = safesql($_GET['id'], "int");
    $rid = safesql($_GET['rid'], "int");
    $sid = safesql($_GET['sid'], "int");
    $Submit = $_POST['Submit'];
    $action = $_GET['action'];
    
    if ($action == "deladd" && pageauth("advancements", "delete")) 
    {
        $data->delete_query("requirements", "advancement = $id");
        $sql = $data->delete_query("advancements", "id = $id");
        if ($sql)
        {
            show_admin_message("Award Badged Deleted", "$pagename&action=viewsch&id=$sid");
        }
    } 
    elseif ($action == "delreq" && pageauth("advancements", "delete")) 
    {
        $sql = $data->delete_query("requirements", "id = '$rid'", "Advancements", "Deleted requirement $rid from $id");	
        if ($sql)
        {
            show_admin_message("Requirement deleted", "$pagename&action=viewadd&id=$id&sid=$sid");
        }
    }
    elseif ($action == "delsch" && pageauth("advancements", "delete"))
    {
        $sql = $data->select_query("advancements", "WHERE scheme=$id");
        while ($temp = $data->fetch_array($sql))
        {
            $data->delete_query("requirements", "advancement = '{$temp['ID']}'");
        }
        $sql = $data->delete_query("advancements", "scheme = '$id'");
        $sql = $data->delete_query("awardschemes", "id = '$id'");
        if ($sql)
        {
            show_admin_message("Award Scheme Deleted", "$pagename");
        }
    }
    elseif($action == "moveup" && pageauth("advancements", "edit"))
    {
        $sql = $data->select_query("advancements", "WHERE ID=$id");
        $row = $data->fetch_array($sql);
        
        $pos1 = $row['position'];
        $temppos = $pos1 -1;
        if($tempos <= 0) $tempos=1;
        $sql = $data->select_query("advancements", "WHERE position='$temppos'");
        $row2 = $data->fetch_array($sql);
        
        $pos2= $row2['position'];
        $data->update_query("advancements", "position=$pos2", "ID={$row['ID']}", "", "", false);
        $data->update_query("advancements", "position=$pos1", "ID={$row2['ID']}", "", "", false);
        
        $server = $_SERVER['PHP_SELF'];
        header("Location: $server"."?page=advancements");
    }
    elseif($action == "movedown" && pageauth("advancements", "edit"))
    {
        $sql = $data->select_query("advancements", "WHERE ID=$id");
        $row = $data->fetch_array($sql);
        
        $pos1 = $row['position'];
        $temppos = $pos1 +1;
        $sql = $data->select_query("advancements", "WHERE position='$temppos'");
        $row2 = $data->fetch_array($sql);
        
        $pos2= $row2['position'];
        $data->update_query("advancements", "position=$pos2", "ID={$row['ID']}", "", "", false);
        $data->update_query("advancements", "position=$pos1", "ID={$row2['ID']}", "", "", false);
        
        $server = $_SERVER['PHP_SELF'];
        header("Location: $server"."?page=advancements");
    }
    elseif($action == "moveitemup" && pageauth("advancements", "edit"))
    {
        $sql = $data->select_query("requirements", "WHERE ID='$rid'");
        $row = $data->fetch_array($sql);
        
        $pos1 = $row['position'];
        $temppos = $pos1 -1;
        if($tempos <= 0) $tempos=1;
        $sql = $data->select_query("requirements", "WHERE advancement='$id' AND position='$temppos'");
        $row2 = $data->fetch_array($sql);
        
        $pos2 = $row2['position'];
        $data->update_query("requirements", "position='$pos2'", "ID={$row['ID']}", "", "", false);
        $data->update_query("requirements", "position='$pos1'", "ID={$row2['ID']}", "", "", false);
        
        $server = $_SERVER['PHP_SELF'];
        header("Location: $server"."?page=advancements&action=viewadd&id=$id&sid=$sid");
    }
    elseif($action == "moveitemdown" && pageauth("advancements", "edit"))
    {
        $sql = $data->select_query("requirements", "WHERE ID='$rid'");
        $row = $data->fetch_array($sql);
        
        $pos1 = $row['position'];
        $temppos = $pos1 +1;

        $sql = $data->select_query("requirements", "WHERE advancement='$id' AND position='$temppos'");
        $row2 = $data->fetch_array($sql);
        
        $pos2 = $row2['position'];

        $data->update_query("requirements", "position='$pos2'", "ID={$row['ID']}", "", "", false);
        $data->update_query("requirements", "position='$pos1'", "ID={$row2['ID']}", "", "", false);
        
        $server = $_SERVER['PHP_SELF'];
        header("Location: $server"."?page=advancements&action=viewadd&id=$id&sid=$sid");
    }
    
    if ($Submit == 'Submit') 
    {
        if ($action == "newadd" && pageauth("advancements", "add")) 
        {
            $scheme = safesql($_GET['sid'], "int");
            $add = safesql($_POST['adv'], "text");
            $pos = 1;
            do 
            {
                $temp = $data->select_query("advancements", "WHERE position = '$pos'");
                if ($data->num_rows($temp) != 0) 
                {
                    $pos++;
                }
            } while ($data->num_rows($temp) != 0); 		
            $sql = $data->insert_query("advancements", "NULL, $add, '$pos', $scheme", "Advancements", "Added $add");
            if ($sql)
            {
                show_admin_message("Award Badged added", "$pagename&action=viewsch&id=$scheme");
            }
        } 
        elseif ($action == "editadd" && pageauth("advancements", "edit")) 
        {
            $scheme = safesql($_GET['sid'], "int");
            $add = safesql($_POST['adv'], "text");
            $sql = $data->update_query("advancements", "advancement = $add", "id = '$id'", "Advancements", "Changed $id to $add");		
            if ($sql)
            {
                show_admin_message("Award Badged updated", "$pagename&action=viewsch&id=$scheme");
            }
        } 
        elseif ($action == "editreq" && pageauth("advancements", "edit")) 
        {
            $reqid = $_GET['rid'];
            $req= safesql($_POST['req'], "text");
            $desc= safesql($_POST['desc'], "text");
            $sql = $data->update_query("requirements", "item = $req, description = $desc","id = '$reqid'", "Advancements", "Changed $reqid to $req");
            $action = "viewadd";
            if ($sql)
            {
                show_admin_message("Requirement updated", "$pagename&action=viewadd&id=$id&sid=$sid");
            }
        } 
        elseif ($action == "newreq" && pageauth("advancements", "add")) 
        {
            $req= safesql($_POST['req'], "text");
            $desc= safesql($_POST['desc'], "text");
            $pos = 1;
            do 
            {
                $temp = $data->select_query("requirements", "WHERE advancement = '$id' AND position = '$pos'");
                if ($data->num_rows($temp) != 0) 
                {
                    $pos++;
                }
            } while ($data->num_rows($temp) != 0); 
            $sql = $data->insert_query("requirements", "NULL, $req, $desc, '$id', '$pos'", "Advancements", "Added $req to $id");
            $action = "viewadd";		
            if ($sql)
            {
                show_admin_message("Requirement added", "$pagename&action=viewadd&id=$id&sid=$sid"); 
            }
        }
        elseif ($action == "newsch" && pageauth("advancements", "add"))
        {
            $add = safesql($_POST['adv'], "text");	
            $sql = $data->insert_query("awardschemes", "NULL, $add");
            if ($sql)
            {
                show_admin_message("Award Scheme added", "$pagename");
            }
        }
        elseif ($action == "editsch" && pageauth("advancements", "edit"))
        {
            $add = safesql($_POST['adv'], "text");
            $sql = $data->update_query("awardschemes", "name = $add", "id = '$id'");		
            if ($sql)
            {
                show_admin_message("Award Scheme updated", "$pagename");
            }
        }
        elseif ($action == "newbadge" && pageauth("advancements", "add"))
        {
            $name= safesql($_POST['name'], "text");
            $desc= safesql($_POST['desc'], "text");

            $sql = $data->insert_query("badges", "'', $name, $desc, $sid");
            if ($sql)
            {
                show_admin_message("Badge added", "$pagename&action=viewsch&id=$sid");
            }
        }
        elseif ($action == "editbadge" && pageauth("advancements", "edit"))
        {
            $name= safesql($_POST['name'], "text");
            $desc= safesql($_POST['desc'], "text");

            $sql = $data->update_query("badges", "name=$name, description=$desc", "id=$id");
            if ($sql)
            {
                show_admin_message("Badge updated", "$pagename&action=viewsch&id=$sid"); 
            }
        }
    }
    
    if ($action == "viewadd")
    {
        $result = $data->select_query("advancements", "WHERE id = '$id'");
        $row = $data->fetch_array($result);
        $advan = $row['advancement'];
        $result = $data->select_query("requirements", "WHERE advancement = '$id' ORDER BY position ASC");
        $req  = array();
        $numreqs = $data->num_rows($result);
        while ($req[]= $data->fetch_array($result));
        
        $tpl->assign("advan", $advan);
        $tpl->assign("req", $req);
        $tpl->assign("numreqs", $numreqs);
        $tpl->assign("sid", $sid);
        $tpl->assign("id", $id);
    
    }
    elseif ($action == "viewsch")
    {
        $result = $data->select_query("advancements", "WHERE scheme = $id ORDER BY position ASC");
        $adv  = array();
        $numads = $data->num_rows($result);
        while ($row = $data->fetch_array($result))
        {
            $sql = $data->select_query("requirements", "WHERE advancement={$row['ID']}");
            $row['numitems'] = $data->num_rows($sql);
            $adv[] = $row;
        }

        $result = $data->select_query("badges", "WHERE scheme = $id ORDER BY name ASC");
        $badge  = array();
        $numbadges = $data->num_rows($result);
        while ($badge[] = $data->fetch_array($result));
        
        $result = $data->select_query("awardschemes", "WHERE id = '$id'");
        $row = $data->fetch_array($result);
        $scheme = $row['name'];   
        
        $tpl->assign("scheme", $scheme);
        $tpl->assign("sid", $id);
        $tpl->assign('badge', $badge);
        $tpl->assign('numbadges', $numbadges);
        
    }
    elseif ($action == "editreq" && pageauth("advancements", "edit")) 
    {
        $result = $data->select_query("advancements", "WHERE id = '$id'");
        $row = $data->fetch_array($result);
        $advan = $row['advancement'];
        $rid = $_GET['rid'];
        $result = $data->select_query("requirements", "WHERE id = '$rid'");
        $row = $data->fetch_array($result);
        $tpl->assign("requirement", $row);	
        $tpl->assign("id", $id);
        $tpl->assign("rid", $rid);
        $tpl->assign("advan", $advan);
        $tpl->assign("sid", $sid);
    }
    elseif($action == "newreq" && pageauth("advancements", "add"))
    {
        $result = $data->select_query("advancements", "WHERE id = '$id'");
        $row = $data->fetch_array($result);
        $advan = $row['advancement'];
        $tpl->assign("advan", $advan);
        $tpl->assign("id", $id);
        $tpl->assign("sid", $sid);
    }
    elseif($action == "editsch" && pageauth("advancements", "edit"))
    {
        $result = $data->select_query("awardschemes", "WHERE id = '$id'");
        $row = $data->fetch_array($result);
        $advan = $row['name'];
        
        $tpl->assign("advan", $advan);
        $tpl->assign("id", $id);
    }
    elseif ($action == "editadd" && pageauth("advancements", "edit"))
    {
        $result = $data->select_query("advancements", "WHERE ID = '$id'");
        $row = $data->fetch_array($result);
        $advan = $row['advancement'];
        $tpl->assign("sid", $sid);
        $tpl->assign("advan", $advan);
        $tpl->assign("id", $id);
    }
    elseif ($action == "newadd" && pageauth("advancements", "add"))
    {
        $tpl->assign("sid", $sid);
    }
    elseif ($action == "newbadge" && pageauth("advancements", "add"))
    {
        $tpl->assign("sid", $sid);
    }
    elseif ($action == "editbadge" && pageauth("advancements", "edit"))
    {
        $result = $data->select_query("badges", "WHERE id = '$id'");
        $row = $data->fetch_array($result);

        $tpl->assign("sid", $sid);
        $tpl->assign("badge", $row);
        $tpl->assign("id", $id);
    }
    else
    {
        $result = $data->select_query("awardschemes");
        $adv  = array();
        $numschemes = $data->num_rows($result);
        while ($row = $data->fetch_array($result))
        {
            $sql = $data->select_query("advancements", "WHERE scheme ={$row['id']}");
            $row['numitems'] = $data->num_rows($sql);
            $schemes[] = $row;
        }
    }
    
    $tpl->assign('numreqs', $numreqs);
    $tpl->assign('adv', $adv);
    $tpl->assign('numads', $numads);
    $tpl->assign('schemes', $schemes);
    $tpl->assign('numschemes', $numschemes);
    $tpl->assign('action',$action);
    $tpl->assign('editFormAction',$editFormAction);

    
    $filetouse = "admin_advancements.tpl";
}
?>