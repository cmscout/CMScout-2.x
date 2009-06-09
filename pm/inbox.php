<?php
/**************************************************************************
    FILENAME        :   inbox.php
    PURPOSE OF FILE :   Inbox for Personal Messengers
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

$username = $check['uname'];
$sql = $data->select_query("pms", "WHERE touser=$userid AND type=1 ORDER BY date DESC");

$numpm = $data->num_rows($sql);
$inboxpm = array();
while($temp = $data->fetch_array($sql))
{
    $temp['touserid'] = $temp['touser'];
    $temp['touser'] = get_username($temp['touser']);
    $temp2 = $data->select_fetch_one_row("users", "WHERE id={$temp['fromuser']}", "uname");
    $temp['fromuserid'] = $temp['fromuser'];
    $temp['fromuser'] = get_username($temp['fromuser']);
    $temp['subject'] = censor($temp['subject']);
    $temp['userstatus'] = user_online($temp['fromuser']);
    $inboxpm[] = $temp;
}

$data->update_query("pms", "newpm='0'", "type=1 AND touser='$username'", "", "", false);

$location = "User Control Panel >> Received Messages";

$tpl->assign("pm", $inboxpm);
$tpl->assign("numpm", $numpm);
$tpl->assign("onpage", "Inbox");
$pagenum = 1;
?>