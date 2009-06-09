<?php
/**************************************************************************
    FILENAME        :   polls.php
    PURPOSE OF FILE :   Displays polls
    LAST UPDATED    :   14 February 2006
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
if (!defined('SCOUT_NUKE'))
    die("You have accessed this page illegally, please go use the main menu");
$location = "Polls";

$id = $_GET['id'];

if (!isset($id))
{
    $sql = $data->select_query("polls", "WHERE trash=0 ORDER BY date_stop");
    $numpolls = $data->num_rows($sql);
    $polls = array();
    while ($temp = $data->fetch_array($sql))
    {
	    $temp['question'] = censor($temp['question']);
	    $polls[] = $temp;
    }
    $tpl->assign("action", $action);
    $tpl->assign("numpolls", $numpolls);
    $tpl->assign("polls", $polls);
}
else
{
    $poll = $data->select_fetch_one_row("polls", "WHERE id=$id");
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
    $location = $poll['question'];    
    
    $sql = $data->select_query("pollvoters", "WHERE user_id = '{$check['id']}' AND poll_id={$poll['id']}");
    if (($data->num_rows($sql) > 0 || $check['id'] == "-1") || ($timestamp > $poll['date_stop'] && $poll['date_stop'] != NULL && $poll['date_stop'] != 0))
    {
        $tpl->assign('mainpagevoted', true);
    }
    else
    {
        $tpl->assign('mainpagevoted', false);
    }
    
    if ($timestamp > $poll['date_stop'] && $poll['date_stop'] != NULL && $poll['date_stop'] != 0)
    {
        $tpl->assign('mainexpired', true);
    }
    else
    {
        $tpl->assign('mainexpired', false);
    }
    
    $tpl->assign('mainpagepoll', $poll);
    $tpl->assign('nummainpollitems', count($poll['options']));
    $tpl->assign('mainpagetotalvotes', $data->num_rows($data->select_query("pollvoters", "WHERE poll_id={$poll['id']}")));
    $view = $_GET['view'];
    
    if ($view == 1)
    {
        $tpl->assign('mainpageshowresult', true);
    }
    else
    {
        $tpl->assign('mainpageshowresult', false);
    }

    $tpl->assign('mainpollpage',"index.php?page=polls&id=$id");
    
    if ($_POST['mainpollvote'] == "Vote")
    {
        $item = $_POST['mainpoll'];
        
        $sql = $data->select_query("pollvoters", "WHERE (user_id = '{$check['id']}') AND poll_id={$poll['id']}");
        if ($data->num_rows($sql) == 0)
        {
            $results = $poll['results'];
            $results[$item]++;
            $results = safesql(serialize($results), "text");
            $data->update_query("polls", "results=$results", "id={$poll['id']}");
            $data->insert_query("pollvoters", "{$poll['id']}, {$check['id']}, '{$_SERVER['REMOTE_ADDR']}'");
            show_message("Your vote has been counted", "index.php?page=polls&id={$poll['id']}");
        }
    }
    $pagenum = 2;
}
$dbpage = true;
$pagename = "polls";
?>