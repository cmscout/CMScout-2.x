<?php
/**************************************************************************
    FILENAME        :   linksdata.php
    PURPOSE OF FILE :   Displays links in the database
    LAST UPDATED    :   13 February 2006
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
$location = "Links";

$sql = $data->select_query("links_cats", "ORDER BY position ASC");
$numcats = $data->num_rows($sql);
$links = array();

while ($temp = $data->fetch_array($sql))
{
    $sql2 = $data->select_query("links", "WHERE cat={$temp['id']} ORDER BY position ASC");
    $temp['numlinks'] = $data->num_rows($sql2);
    while($temp2 = $data->fetch_array($sql2))
    {
        $temp['links'][] = $temp2;
    }
    $links[] = $temp;
}

$edit = adminauth("links", "edit") ? true : false;
$editlink = "admin.php?page=links";

$tpl->assign("action", $action);
$tpl->assign("numcats", $numcats);
$tpl->assign("links", $links);
$dbpage = true;
$pagename = "linksdata";

$scriptList['mootabs'] = 1;
?>