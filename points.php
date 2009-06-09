<?php
/**************************************************************************
    FILENAME        :   points.php
    PURPOSE OF FILE :   Shows patrol points
    LAST UPDATED    :   26 May 2006
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

if (isset($_GET['site']))
{
    $location = "{$_GET['site']} Points";
}
else
{
    $location = "Points";
}

$message = "";
$pagenum = 1;

if (isset($_GET['site']))
{
    $subsitesql = safesql($_GET['site'], "text");
    $patrolpoints = $data->select_fetch_all_rows($numpoints, "groups", "WHERE getpoints = 1 AND subsite = $subsitesql ORDER BY Points DESC");
}
else
{
    $patrolpoints = $data->select_fetch_all_rows($numpoints, "groups", "WHERE getpoints = 1 ORDER BY Points DESC");
}

$tpl->assign("patrolpoints", $patrolpoints);
$tpl->assign("numpoints", $numpoints);
$tpl->assign("userpatrol", $check['team']);
$dbpage = true;
$pagename = "points";
?>