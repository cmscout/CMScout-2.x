<?php
/**************************************************************************
    FILENAME        :   drafts.php
    PURPOSE OF FILE :   Draft box for Personal Messengers
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

$sql = $data->select_query("pms", "WHERE fromuser=$userid AND type=4 ORDER BY date ASC");
$numpm = $data->num_rows($sql);
$inboxpm = array();
while($temp = $data->fetch_array($sql))
{
    $temp2 = $data->select_fetch_one_row("users", "WHERE id={$temp['fromuser']}", "uname");
    $temp['fromuserid'] = $temp['fromuser'];
    $temp['fromuser'] = $temp2['uname'];
    
    $tousers = explode(',', strip_tags($temp['touser']));
    $temp['touser'] = array();
    $temp['touser']['number'] = count($tousers);
    for ($i=0;$i<count($tousers);$i++)
    {
        $bla = trim($tousers[$i]);
        $temp2 = $data->select_fetch_one_row("users", "WHERE id={$bla}", "uname");
        $temp['touser']['users'][$i]['uname'] = $temp2['uname'];
        $temp['touser']['users'][$i]['id'] = $bla;
        $temp['touser']['users'][$i]['status'] = user_online($temp2['uname']);
    }
    $temp['subject'] = censor($temp['subject']);
    $inboxpm[] = $temp;
}
$location = "User Control Panel >> Draft Messages";
$tpl->assign("pm", $inboxpm);
$tpl->assign("numpm", $numpm);
$tpl->assign("onpage", "Drafts");
$pagenum = 1;
?>