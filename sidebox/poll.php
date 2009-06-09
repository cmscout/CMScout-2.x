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
    $sql = $data->select_query("polls", "WHERE sidebox=1");
    $poll = $data->fetch_array($sql);
    if ($data->num_rows($sql) == 1)
    {

	$poll['question'] = censor($poll['question']);
       $poll['options'] = unserialize($poll['options']);

       $options = array();
       foreach ($poll['options'] as $id => $value)
       {
		$value = censor($value);
		$options[$id] = $value;
       }
       $poll['options'] = $options;

	$poll['results'] = unserialize($poll['results']);
       $options = array();
       foreach ($poll['results'] as $id => $value)
       {
		$id = censor($id);
		$options[$id] = $value;
       }
       $poll['results'] = $options;
   
        $sql = $data->select_query("pollvoters", "WHERE user_id = '{$check['id']}' AND poll_id={$poll['id']}");
        if (($data->num_rows($sql) > 0 || $check['id'] == "-1") || ($timestamp > $poll['date_stop'] && $poll['date_stop'] != NULL && $poll['date_stop'] != 0))
        {
            $tpl->assign('sideboxvoted', true);
        }
        else
        {
            $tpl->assign('sideboxvoted', false);
        }
        
        if ($timestamp > $poll['date_stop'] && $poll['date_stop'] != NULL && $poll['date_stop'] != 0)
        {
            $tpl->assign('sideexpired', true);
        }
        else
        {
            $tpl->assign('sideexpired', false);
        }
        
        $tpl->assign('sideboxpoll', $poll);

        $tpl->assign('numsidepollitems', count($poll['options']));
        $tpl->assign('sideboxtotalvotes', $data->num_rows($data->select_query("pollvoters", "WHERE poll_id={$poll['id']}")));
        $view = $_GET['sideview'];
        
        if ($view == 1)
        {
            $tpl->assign('sideboxshowresult', true);
        }
        else
        {
            $tpl->assign('sideboxshowresult', false);
        }

        $query = $_SERVER['QUERY_STRING'];
        $query = str_replace("&sideview=1", "", $query);
	$query2 = $query;
        $query = htmlentities($query);
        $tpl->assign('pollpage',"index.php?$query");
        
        if ($_POST['sidepollvote'] == "Vote")
        {
            $item = $_POST['sidepoll'];
            
            $sql = $data->select_query("pollvoters", "WHERE (user_id = '{$check['id']}') AND poll_id={$poll['id']}");
            if ($data->num_rows($sql) == 0)
            {
                $results = $poll['results'];
                $results[$item]++;
                $results = safesql(serialize($results), "text");
                $data->update_query("polls", "results=$results", "id={$poll['id']}");
                $data->insert_query("pollvoters", "{$poll['id']}, {$check['id']}, '{$_SERVER['REMOTE_ADDR']}'");
                show_message("Your vote has been counted", "index.php?$query2");
            }
        }
    }
?>