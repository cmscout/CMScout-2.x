<?php
/**************************************************************************
    FILENAME        :   statistics.php
    PURPOSE OF FILE :   Displays per group stats
    LAST UPDATED    :   24 May 2006
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


$location = "Statistics";
$user = safesql($_GET['user'], "text");
//Group Stats
$groupsql = $data->select_query("groups", "WHERE ispatrol=1 ORDER BY teamname ASC", "id, teamname");
$numgroups = $data->num_rows($groupsql);
$statspage = array();
while ($temp = $data->fetch_array($groupsql))
{
    $groupinfo['name'] = $temp['teamname'];
    $groupinfo['id'] = $temp['id'];
    
    $albumsql = $data->select_query("album_track", "WHERE patrol='{$groupinfo['id']}' AND allowed=1 AND trash=0", "id");
    $groupinfo['albums'] = $data->num_rows($albumsql);

    $groupinfo['photos'] = $number;

    $groupinfo['articles'] = $data->num_rows($data->select_query("patrol_articles", "WHERE patrol='{$groupinfo['id']}' AND allowed=1 AND trash=0", "ID"));
    $groupinfo['pages'] = $data->num_rows($data->select_query("static_content", "WHERE pid='{$groupinfo['id']}' AND type=1 AND trash=0", "id"));
    $groupinfo['logentries'] = $data->num_rows($data->select_query("patrollog", "WHERE `group`='{$groupinfo['id']}'", "id"));

    
    $gusql = $data->select_query("usergroups", "WHERE groupid='{$groupinfo['id']}'", "userid");
    $groupinfo['numusers'] = $data->num_rows($gusql);
    $groupinfo['comments'] = 0;
    $groupinfo['posts'] = 0;
    $groupinfo['topics'] = 0;
    while ($usertemp = $data->fetch_array($gusql))
    {
        $groupinfo['comments'] += $data->num_rows($data->select_query("comments", "WHERE uid='{$usertemp['userid']}' AND allowed=1", "id"));      
        $groupinfo['posts'] += $data->num_rows($data->select_query("forumposts", "WHERE userposted='{$usertemp['userid']}'", "id"));
        $groupinfo['topics'] += $data->num_rows($data->select_query("forumtopics", "WHERE userposted='{$usertemp['userid']}'", "id"));
    }
    
    $statspage[] = $groupinfo;
}

//Site Stats
$siteinfo['name'] = "General Items";
$albumsql = $data->select_query("album_track", "WHERE allowed=1 AND trash=0", "id");
$siteinfo['albums'] = $data->num_rows($albumsql);

$photosql = $data->select_query("photos", "WHERE allowed=1", "id");
$siteinfo['photos'] = $data->num_rows($photosql);

$siteinfo['articles'] = $data->num_rows($data->select_query("patrol_articles", "WHERE allowed=1 AND trash=0", "ID"));
$siteinfo['pages'] = $data->num_rows($data->select_query("static_content", "WHERE trash=0", "id"));

$siteinfo['numusers'] = $data->num_rows($data->select_query("users", "", "id, uname"));

$siteinfo['comments'] = $data->num_rows($data->select_query("comments", "WHERE allowed=1", "id"));      
$siteinfo['posts'] = $data->num_rows($data->select_query("forumposts", "", "id"));
$siteinfo['topics'] = $data->num_rows($data->select_query("forumtopics", "", "id"));

$statspage['site'] = $siteinfo;

$tpl->assign("statspage", $statspage);
$tpl->assign("numgroups", $numgroups);
$dbpage = true;
$pagename = "statistics";
?>