<?php
/**************************************************************************
    FILENAME        :   admin_patrolart.php
    PURPOSE OF FILE :   Manages articles
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
	$module['Content Management']['Orphaned Items'] = "owners";
    $moduledetails[$modulenumbers]['name'] = "Orphaned Items";
    $moduledetails[$modulenumbers]['details'] = "Shows all items that don't have owners";
    $moduledetails[$modulenumbers]['access'] = "Allowed to view orphaned items";
    $moduledetails[$modulenumbers]['add'] = "notused";
    $moduledetails[$modulenumbers]['edit'] = "Allowed to set owner for items";
    $moduledetails[$modulenumbers]['delete'] = "notused";
    $moduledetails[$modulenumbers]['publish'] = "notused";
    $moduledetails[$modulenumbers]['limit'] = "notused";
    $moduledetails[$modulenumbers]['id'] = "owners";
	return;
}
else
{
    function getSqlList($type, $field, $userid)
    {
        global $data;
        
        if ($userid == "")
        {
            $owned = $data->select_fetch_all_rows($number, "owners", "WHERE item_type='$type'");
            $ownedSql = '';

            if ($number > 0)
            {
                for($i=0;$i<$number;$i++)
                {
                    $ownedSql .= "$field  != " . safesql($owned[$i]['item_id'], "int");
                    if ($i != $number - 1)
                    {
                        $ownedSql .= " AND ";
                    }
                }
            }
            else
            {
                $ownedSql = "$field >= 0";
            }
        }
        else
        {
            $ownedSql = owner_items_sql_list($field, $type, true, $userid);
        }

        return $ownedSql;
    }
    
    $action = $_GET['action'];
    
    switch ($action)
    {
        case "change":
            $cattype = safesql($cat, "text");
            $unsafeItemType = $_GET['itemType'];
            $id = $_GET['id'];
            $tpl->assign("itemid", $id);
            $tpl->assign("itemType", $unsafeItemType);
            $itemType = safesql($unsafeItemType, "text");
            $id  = safesql($id , "int");
            
            $sql = $data->select_query("owners", "WHERE item_id=$id AND item_type=$itemType");
            
            $itemowners = array();
            $numitemowners = $data->num_rows($sql);
            while ($temp = $data->fetch_array($sql))
            {
                if ($temp['owner_type'] == 0)
                {
                    $sql2 = $data->select_query("users", "WHERE id={$temp['owner_id']}", "id, uname");
                    $temp2 = $data->fetch_array($sql2);
                    $temp2['name'] = $temp2['uname'];
                }
                else
                {
                    $sql2 = $data->select_query("groups", "WHERE id={$temp['owner_id']}", "id, teamname");
                    $temp2 = $data->fetch_array($sql2);
                    $temp2['name'] = $temp2['teamname'];
                }

                $temp2['expired'] = ($temp['expire'] >= $timestamp || $temp['expire'] == 0) ? 0 : 1;
                $temp2['type'] = $temp['type_owner'];
                $temp2['expire'] = $temp['expire'];
                $temp2['id'] = $temp['id'];
                $itemowners[] = $temp2;
            }
            
            $tpl->assign("numitemowners", $numitemowners);
            $tpl->assign("itemowners", $itemowners);
            
            $sql = $data->select_query("users", "ORDER BY uname");
            $numpeople = 0;
            $people = array();
            while ($temp = $data->fetch_array($sql))
            {
                if ($data->num_rows($data->select_query("owners", "WHERE item_id=$id AND item_type=$itemType AND owner_id={$temp['id']} AND owner_type=0")) == 0)
                {
                    $people[] = $temp;
                    $numpeople++;
                }
            }
            $tpl->assign("numpeople", $numpeople);
            $tpl->assign("people", $people);
            $sql = $data->select_query("groups", "ORDER BY teamname");
            $numteams = 0;
            $groups = array();
            while ($temp = $data->fetch_array($sql))
            {
                if ($data->num_rows($data->select_query("owners", "WHERE item_id=$id AND item_type=$itemType AND owner_id={$temp['id']} AND owner_type=1")) == 0)
                {
                    $groups[] = $temp;
                    $numteams++;
                }
            }
            $tpl->assign("numteams", $numteams);
            $tpl->assign("groups", $groups);
            $userid = isset($_GET['uid']) ? $_GET['uid'] : false;
            $tpl->assign("userid", $userid);
            if($_POST['action'] == "Add")
            {
                $temp = $_POST['owner'];
                $temp = explode("_", $temp);
                $owner_type = ($temp[0] == "user") ? 0 : 1;
                $owner = safesql($temp[1], "text");
                $type_owner = safesql($_POST['type_owner'], "int");
                if ($_POST['expire'] != 0)
                {
                    $time = safesql($timestamp + 3600*$_POST['expire'], "int");
                }
                else
                {
                    $time = "0";
                }
                
                $sql = $data->insert_query("owners", "'', $id, $itemType, $owner, $owner_type, $type_owner, $time");
                if ($sql)
                {                  
                    echo "<script>window.location='admin.php?page=owners&itemType=$unsafeItemType&action=change&id={$id}". ($userid ? "&uid={$userid}" : '') ."'</script>";
                    exit;  
                }
            }        
            break;
        case "deleteowner":
            $id = safesql($_GET['id'], "int");
            $sqlq = $data->delete_query("owners", "id=$id");
            $userid = isset($_GET['uid']) ? $_GET['uid'] : false;
            if ($sqlq)
            {
                echo "<script>window.location = 'admin.php?page=owners&action=change&id={$_GET['itemid']}&itemType={$_GET['itemType']}". ($userid ? "&uid={$userid}" : '') ."';</script>\n";
                exit; 
            }   
            break;
        default:            
            
            $userid = isset($_GET['uid']) ? $_GET['uid'] : false;
            
            if ($userid)
            {
                $safeuid = safesql($userid, "int");
                
                $detail = $data->select_fetch_one_row("users", "WHERE id=$safeuid", "uname");
                
                $tpl->assign("uname", $detail['uname']);
                $tpl->assign("userid", $userid);
            }
            $ownedSql = getSqlList('album', "ID", $userid);
            $albums = $ownedSql ? $data->select_fetch_all_rows($numalbums, "album_track", "WHERE {$ownedSql} ORDER BY album_name") : '';
            
            $tpl->assign("albums", $albums);
            $tpl->assign("numalbums", $numalbums);
            
            $ownedSql = getSqlList('articles', "ID", $userid);
            $articles = $ownedSql ? $data->select_fetch_all_rows($numarticles, "patrol_articles", "WHERE {$ownedSql} ORDER BY title") : '';
            
            $tpl->assign("articles", $articles);
            $tpl->assign("numarticles", $numarticles);

            $ownedSql = getSqlList('events', "id", $userid);
            $events = $ownedSql ? $data->select_fetch_all_rows($numevents, "calendar_items", "WHERE {$ownedSql} ORDER BY summary") : '';
            
            $tpl->assign("events", $events);
            $tpl->assign("numevents", $numevents);

            $ownedSql = getSqlList('downloads', "id", $userid);
            $downloads = $ownedSql ? $data->select_fetch_all_rows($numdownloads, "downloads", "WHERE {$ownedSql} ORDER BY name") : '';
            
            $tpl->assign("downloads", $downloads);
            $tpl->assign("numdownloads", $numdownloads);
            
            $ownedSql = getSqlList('newsitem', "id", $userid);
            $news = $ownedSql ? $data->select_fetch_all_rows($numnews, "newscontent", "WHERE {$ownedSql} ORDER BY title") : '';
            
            $tpl->assign("news", $news);
            $tpl->assign("numnews", $numnews);
            
            $ownedSql = getSqlList('pollitems', "id", $userid);
            $polls = $ownedSql ? $data->select_fetch_all_rows($numpolls, "polls", "WHERE {$ownedSql} ORDER BY question") : '';
            
            $tpl->assign("polls", $polls);
            $tpl->assign("numpolls", $numpolls);
            
            $permissions['album'] = pageauth("photo", "edit");
            $permissions['patrolart'] = pageauth("patrolart", "edit");
            $permissions['events'] = pageauth("events", "edit");
            $permissions['downloads'] = pageauth("downloads", "edit");
            $permissions['news'] = pageauth("news", "edit");
            $permissions['polls'] = pageauth("poll", "edit");
            
            $tpl->assign('permissions', $permissions);
            
            break;
    }
    
    
    $filetouse = "admin_owners.tpl";
    $tpl->assign('action', $action);
}
?>