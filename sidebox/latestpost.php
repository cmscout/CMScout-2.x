<?php
/**************************************************************************
    FILENAME        :   latestpost.php
    PURPOSE OF FILE :   Sidebox: Latest posts in the forum
    LAST UPDATED    :   19 July 2006
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

	$sql = $data->select_query("forumtopics","ORDER BY lastdate DESC");
    $posts = array();

    while ($temp = $data->fetch_array($sql))
    {
        $sql2 = $data->select_query("forumauths", "WHERE forum_id={$temp['forum']}");
        $auth = $data->fetch_array($sql2);
        
        $view_forum = unserialize($auth['view_forum']);
        $read_topics = unserialize($auth['read_topics']);
        
        if ($check['id'] != "-1")
        {
            $usergroups = user_groups_id_array($check['id']);
        }
        else
        {
            $usergroups = array(0 => "-1");
        }
        $sideboxforumauth['view'] = 0;
        $sideboxforumauth['read'] = 0;
        for($i=0;$i<count($usergroups);$i++)
        {                
            $sideboxforumauth['view'] = $sideboxforumauth['view'] || $view_forum[$usergroups[$i]];
            $sideboxforumauth['read'] = $sideboxforumauth['read'] || $read_topics[$usergroups[$i]];
        }       
                
        if ($sideboxforumauth['view'] == 1 && $sideboxforumauth['read'] == 1)
        {
            $posts[] = $temp;
            $numlatestposts++;
        }
        if ($numlatestposts == $config['numsidebox'])
        {
            break;
        }
    }
    
    $tpl->assign('latestposts',$posts);
    $tpl->assign("numlatestposts", $numlatestposts);
?>