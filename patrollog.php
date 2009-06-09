<?php
/**************************************************************************
    FILENAME        :   patrollog.php
    PURPOSE OF FILE :   Displays group logs
    LAST UPDATED    :   23 May 2006
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

$location = "$patrolname Log";
$action = isset($_GET['action']) ? $_GET['action'] : "";
if ($action == "delete")
{
    $id = safesql($_GET['id'], "int");
    $data->delete_query("patrollog", "id = $id AND uid = {$check['id']}");
    show_message("Log item removed", "index.php?page=patrolpages&patrol={$safe_patrolid}&content=patrollog");
}

if ($action != "add")
{
    $privateitems = 1;
    
    if ($privateitems)
    {
        $sql = $data->select_query("patrollog", "WHERE `group`=$safe_patrolid");
    }
    else
    {
        $sql = $data->select_query("patrollog", "WHERE `group`=$safe_patrolid AND private != 1");
    }
    $numlog = $data->num_rows($sql);
    $limit = $config['numpage'];
    $start = isset($_GET['start']) ? $_GET['start'] : 0;
    $add = $user_groups[$_GET['patrol']] ? true : false;
    $addlink = "index.php?page=patrolpages&amp;patrol={$safe_patrolid}&amp;content=patrollog&amp;action=add";
    
    //Pagenation working out
    if ($numlog > 0) 
    {
        $num_pages = ceil($numlog / $limit);
    
        if ($start == 0 && $latest == 1)
        {
            $curr_page = $num_pages;
            $start = (($curr_page-1)*$limit);
        }
        else
        {
            $curr_page = floor($start/$limit) + 1;
        }
        
        if ($curr_page < $num_pages)
        {
            $next = true; 
            $next_start=(($curr_page-1)*$limit) + $limit;
        }
        if ($curr_page > 1) 
        {
            $prev = true; 
            $prev_start=(($curr_page-1)*$limit)- $limit;
        }
        
              
        $pagelimit = ($numlog-$start) <= $limit ? ($numlog-$start) : $limit;
        if ($privateitems)
        {
            $sql = $data->select_query("patrollog", "WHERE `group`=$safe_patrolid ORDER BY `dateposted` ASC LIMIT $start, $pagelimit");
        }
        else
        {
            $sql = $data->select_query("patrollog", "WHERE `group`=$safe_patrolid AND private != 1 ORDER BY `dateposted` ASC LIMIT $start, $pagelimit");
        }
        
        $logbook = array();
        while($temp = $data->fetch_array($sql))
        {
            $sql2 = $data->select_query("users", "WHERE id='{$temp['uid']}'", "uname, avyfile, sig, publicprofile");
            $temp2 = $data->fetch_array($sql2);
            $temp['useravy'] = $temp2['avyfile'];
            $temp['sig'] = censor($temp2['sig']);
            $temp['publicprofile'] = $temp2['publicprofile'];
            $temp['uname'] = $temp2['uname'];
            $temp['userstatus'] = user_online($temp2['uname']);
            $temp['title'] = censor($temp['title']);
            $temp['itemdetails'] = censor($temp['itemdetails']);
            $logbook[] = $temp;
        }
    }
    
    $rssuname = safesql(md5($check['uname']), "text");
    if ($data->num_rows($data->select_query("rssfeeds", "WHERE itemid=$safe_patrolid AND type=2 AND uname=$rssuname", "id")))
    {
        $rss = 1;
    }
    else
    {
        $rss = 0;
    }    
    
    if (isset($num_pages)) $tpl->assign('numpages', $num_pages);
    if (isset($curr_page)) $tpl->assign('currentpage', $curr_page);
    if (isset($next))$tpl->assign('next', $next);
    if (isset($prev))$tpl->assign('prev', $prev);
    $tpl->assign('num_per_page', $limit);
    if (isset($next_start)) $tpl->assign('next_start', $next_start);
    if (isset($prev_start)) $tpl->assign('prev_start', $prev_start);
    $tpl->assign("start", $_GET['start']);
    $tpl->assign("logbook", $logbook);
    $tpl->assign("numlog", $numlog);
    $tpl->assign("limit", $pagelimit);
    $tpl->assign("uname", $uname);
    $tpl->assign("rss", $rss);
}
else
{
    if ($_POST['submit'] == "Submit")
    {
        $title = safesql($_POST['title'], "text");
        $post = safesql($_POST['story'], "text", false);
        $private = safesql($_POST['private'], "int");
        
        $sql = $data->insert_query("patrollog", "NULL, '{$check['id']}', $timestamp, $title, $post, $safe_patrolid, $private", "", "", false);
        if ($sql)
        {
            show_message("Your log entry has been added", "index.php?page=patrolpages&patrol={$safe_patrolid}&content=patrollog");
        }
    }
    else
    {
        $scriptList['tinySimp'] = 1;
    }
    $pagenum = 2;
}
$dbpage = true;
$pagename = "patrollog";
?>