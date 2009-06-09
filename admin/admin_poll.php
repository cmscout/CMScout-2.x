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
	$module['Content Management']['Poll Manager'] = "poll";
    $moduledetails[$modulenumbers]['name'] = "Poll Manager";
    $moduledetails[$modulenumbers]['details'] = "Management of polls";
    $moduledetails[$modulenumbers]['access'] = "Allowed to access the poll manager";
    $moduledetails[$modulenumbers]['add'] = "Allowed to add a poll";
    $moduledetails[$modulenumbers]['edit'] = "Allowed to modify a poll";
    $moduledetails[$modulenumbers]['delete'] = "Allowed to delete a poll";
    $moduledetails[$modulenumbers]['publish'] = "Allowed to publish polls";
    $moduledetails[$modulenumbers]['limit'] = "notused";   
    $moduledetails[$modulenumbers]['id'] = "poll";

	return;
}
else
{
    $action = $_GET['action'];
    
    if ($action == "new" && pageauth("poll", "add") && $_POST['Submit'] == "Submit")
    {
        $tpl->assign("numoptions", 1);
        
        if ($_POST['Submit'] == "Submit")
        {
            $options = safesql(serialize($_POST['option']), "text");
            
            $results = array();
            for($i=0;$i<count($_POST['option']);$i++)
            {
                $results[str_replace(' ', '', $_POST['option'][$i])] = 0;
            }
            $results = safesql(serialize($results), "text");
            
            $stopdate = $_POST['date_stop'] != "" ? safesql(strtotime($_POST['date_stop']), "int") : 0;
            $pollq = safesql($_POST['question'], "text");

            $sql = $data->insert_query("polls", "'', {$pollq}, 0, $timestamp, {$stopdate}, $options, $results, 1, 0");
            show_admin_message("Poll added", "$pagename"); 
        }        
    }
    elseif ($action == "edit" && pageauth("poll", "edit"))
    {
        $id = safesql($_GET['id'], "int");
        $item = $data->select_fetch_one_row("polls", "WHERE id=$id");

        $item['options'] = unserialize($item['options']);
        $numoptions = count($item['options']);

        if ($_POST['Submit'] == "Submit")
        {
            $options = safesql(serialize($_POST['option']), "text");

            if (count($_POST['option']) != $numoptions)
            {
                $results = array();
                for($i=0;$i<count($_POST['option']);$i++)
                {
                    $results[str_replace(' ', '', $_POST['option'][$i])] = 0;
                }
                $results = safesql(serialize($results), "text");
            }
            
            $stopdate = $_POST['date_stop'] != "" ? safesql(strtotime($_POST['date_stop']), "int") : 0;
            $pollq = safesql($_POST['question'], "text");
            
            $sql = $data->update_query("polls", "question = {$pollq}, date_stop={$stopdate}, options=$options, results=$results", "id=$id");
            show_admin_message("Poll added", "$pagename");  
        }         
        
        
        $tpl->assign("item", $item);
        $tpl->assign("numoptions", $numoptions);
    }
    elseif ($action == "delete")
    {
        $id = safesql($_GET['id'], "int");
        $sqlq = $data->update_query("polls", "trash=1", "id=$id");
        if ($sqlq) 
        { 
            show_admin_message("Poll deleted", "$pagename");  
        }
    }
    elseif ($action == 'publish' && pageauth("poll", "publish") == 1) 
    {
        $id = safesql($_GET['id'], "int");
        $sqlq = $data->update_query("polls", "allowed=1", "id=$id");
        if ($data->num_rows($data->select_query("review", "WHERE item_id=$id AND type='poll'")))
        {        
            $item = $data->select_fetch_one_row("polls", "WHERE id=$id");
            email('newitem', array("poll", $item));
            $data->delete_query("review", "item_id=$id AND type='poll'");
        }
        show_admin_message("Poll published", "$pagename");
    }
    elseif ($action == 'unpublish' && pageauth("poll", "publish") == 1) 
    {
        $id = safesql($_GET['id'], "int");
        $sqlq = $data->update_query("polls", "allowed=0", "id=$id");
        show_admin_message("Poll unpublished", "$pagename");
    }
    else
    {
        $pollitems = $data->select_fetch_all_rows($numpolls, "polls", "WHERE trash=0 ORDER BY date_start ASC");
        
        $tpl->assign("numpolls", $numpolls);
        $tpl->assign("pollitems", $pollitems);
    }
    
    $tpl->assign("action", $action);
    $filetouse = "admin_poll.tpl";
}
?>