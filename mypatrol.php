<?php
/**************************************************************************
    FILENAME        :   mypatrol.php
    PURPOSE OF FILE :   Displays a list of users in current users group. Allows users to email and private message each other.
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

if (isset($_GET['action'])) $action = $_GET['action'];
$message = "";
$pagenum = 1;
$groups = user_public_array($check['id']);

$tpl->assign("group", $groups);
$tpl->assign("numpublic", count($groups));

$selectedGroup = safesql($_GET['id'], "int");
if ($selectedGroup != 'NULL')
{

	$groupusers = $data->select_query("usergroups", "WHERE groupid = $selectedGroup AND userid != {$check['id']}");
	$nummem = $data->num_rows($groupusers);
	$patrollist = array();
	$i = 0;
	while($temp = $data->fetch_array($groupusers))
	{
	    $tempdetails = $data->select_fetch_one_row("users", "WHERE id='{$temp['userid']}'", "id, uname, firstname, lastname");
	    $patrollist[$i]['id'] = $tempdetails['id'];
	    $patrollist[$i]['uname'] = $tempdetails['uname'];
	    $patrollist[$i]['firstname'] = $tempdetails['firstname'];
	    $patrollist[$i]['lastname'] = $tempdetails['lastname'];
	    $patrollist[$i]['online'] = user_online($tempdetails['uname']);
	    $i++;
	}

	$location = "User Control Panel >> Groups";

	$tpl->assign("nummem", $nummem);
	$tpl->assign("patrollist", $patrollist);
	$tpl->assign("username", $check['uname']);
}

$tpl->assign("selectedGroup", $selectedGroup);
$dbpage = true;

$location = "User Control Panel >> Groups";
$pagename = "mypatrol";
?>