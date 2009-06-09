<?php
/**************************************************************************
    FILENAME        :   poll.php
    PURPOSE OF FILE :   Sidebox: Shows current sidebox poll
    LAST UPDATED    :   04 January 2006
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
    $sql = $data->select_query("polls", "WHERE id={$items['option']}");
    $poll[$items['id']] = $data->fetch_array($sql);
    
    if ($data->num_rows($sql) == 1)
    {

	    $poll[$items['id']]['question'] = censor($poll[$items['id']]['question']);
       $poll[$items['id']]['options'] = unserialize($poll[$items['id']]['options']);

       $options = array();
       foreach ($poll[$items['id']]['options'] as $id => $value)
       {
		$value = censor($value);
		$options[$id] = $value;
       }
       $poll[$items['id']]['options'] = $options;

	$poll[$items['id']]['results'] = unserialize($poll[$items['id']]['results']);
       $options = array();
       foreach ($poll[$items['id']]['results'] as $id => $value)
       {
		$id = censor($id);
		$options[$id] = $value;
       }
       $poll[$items['id']]['results'] = $options;
   
        $sql = $data->select_query("pollvoters", "WHERE user_id = '{$check['id']}' AND poll_id={$poll[$items['id']]['id']}");
        if (($data->num_rows($sql) > 0 || $check['id'] == "-1") || ($timestamp > $poll[$items['id']]['date_stop'] && $poll[$items['id']]['date_stop'] != NULL && $poll[$items['id']]['date_stop'] != 0))
        {
            $sideboxvoted[$items['id']] = true;
            $tpl->assign('sideboxvoted', $sideboxvoted);
        }
        else
        {
            $sideboxvoted[$items['id']] = false;
            $tpl->assign('sideboxvoted', $sideboxvoted);
        }

        if ($timestamp > $poll[$items['id']]['date_stop'] && $poll[$items['id']]['date_stop'] != NULL && $poll[$items['id']]['date_stop'] != 0)
        {
            $sideexpired[$items['id']] = true;
            $tpl->assign('sideexpired', $sideexpired);
        }
        else
        {
            $sideexpired[$items['id']] = false;
            $tpl->assign('sideexpired', $sideexpired);
        }

        $tpl->assign('sideboxpoll', $poll);

        $numsidepollitems[$items['id']] = count($poll[$items['id']]['options']);
        $tpl->assign('numsidepollitems', $numsidepollitems);
        $sideboxtotalvotes[$items['id']] = $data->num_rows($data->select_query("pollvoters", "WHERE poll_id={$poll[$items['id']]['id']}"));
        $tpl->assign('sideboxtotalvotes', $sideboxtotalvotes);
        $view = $_GET['sideview'];
        
        if ($view == $items['id'])
        {
            $sideboxshowresult[$items['id']] = true;
            $tpl->assign('sideboxshowresult', $sideboxshowresult);
        }
        else
        {
            $sideboxshowresult[$items['id']] = false;
            $tpl->assign('sideboxshowresult', $sideboxshowresult);
        }

        $query = $_SERVER['QUERY_STRING'];
        $query = str_replace("&sideview={$items['id']}", "", $query);
	    $query2 = $query;
        $query = htmlentities($query);
        $tpl->assign('pollpage',"index.php?$query");
        

        if ($_POST["sidepollvote_{$items['id']}"] == "Vote")
        {

            $item = $_POST["sidepoll_{$items['id']}"];
            
            $sql = $data->select_query("pollvoters", "WHERE (user_id = '{$check['id']}') AND poll_id={$poll['id']}");
            if ($data->num_rows($sql) == 0)
            {
                $results = $poll[$items['id']]['results'];
                $results[$item]++;
                $results = safesql(serialize($results), "text");
                $data->update_query("polls", "results=$results", "id={$poll[$items['id']]['id']}");
                $data->insert_query("pollvoters", "{$poll[$items['id']]['id']}, {$check['id']}, '{$_SERVER['REMOTE_ADDR']}'");
                show_message("Your vote has been counted", "index.php?$query2");
            }
        }
    }
?>