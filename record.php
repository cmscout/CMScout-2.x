<?php
/**************************************************************************
    FILENAME        :   record.php
    PURPOSE OF FILE :   Displays users scouting record
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
/********************************************Check if user is allowed*****************************************/
$action = $_GET['action'];
$what = $_POST['what'];
$id = $_GET['id'];
$safe_userid = isset($_GET['user']) ? safesql($_GET['user'], "int") : safesql($check['id'], "int") ;
$type = isset($_GET['type']) ? 1 : 0;
$submit = $_POST['Submit'];

if ($type == 0)
{
    $memberdetails = $data->select_fetch_one_row("members", "WHERE userId=$safe_userid");
}
elseif ($type == 1)
{
    $tempdetail = $data->select_fetch_one_row("members", "WHERE userId = {$check['id']}", "id");
    $memberdetails = $data->select_fetch_one_row("members", "WHERE id=$safe_userid AND (fatherId = {$tempdetail['id']} OR motherId = {$tempdetail['id']})");    
}
$safe_id = safesql($memberdetails['id'], "int");
$safe_scheme = safesql((isset($_GET['scheme']) ? $_GET['scheme'] : $memberdetails['awardScheme']), "int");
$tpl->assign("schemeNumber", (isset($_GET['scheme']) ? $_GET['scheme'] : $memberdetails['awardScheme']));
$tpl->assign("memberdetails", $memberdetails);

$schemes = $data->select_fetch_all_rows($numschemes, "awardschemes", "ORDER BY name ASC");
$tpl->assign("schemes", $schemes);
$tpl->assign("numschemes", $numschemes);

$advansql = $data->select_query("advancements", "WHERE scheme = $safe_scheme ORDER BY position ASC");
$numadva = $data->num_rows($advansql);
$advancements = array();
$numitems = 0;
$recordsql = $data->select_fetch_one_row("scoutrecord", "WHERE userid=$safe_id AND scheme = $safe_scheme");
$scoutRecord['requirement'] = unserialize($recordsql['requirements']);
$scoutRecord['comment'] = unserialize($recordsql['comment']);

while ($temp = $data->fetch_array($advansql)) 
{
    $getrequirements = $data->select_query("requirements", "WHERE advancement = '{$temp["ID"]}' ORDER BY position ASC");
    
    $temp['numitems'] = $data->num_rows($getrequirements);
    while ($temp['items'][] = $data->fetch_array($getrequirements));
    $advancements[] = $temp;
}
$tpl->assign("scoutRecord", $scoutRecord);
$tpl->assign("advan", $advancements);
$tpl->assign("numadva", $numadva);

$badgesql = $data->select_query("userbadges", "WHERE userid = $safe_id");
$numbadge = 0;
$badges = array();
while ($temp = $data->fetch_array($badgesql))
{
    $result = $data->select_fetch_one_row("badges", "WHERE id = {$temp['badgeid']} AND scheme=$safe_scheme");
    if ($result != '')
    {
        $temp['name'] = $result['name'];
        $temp['description'] = $result['description'];
        $badges[] = $temp;
        $numbadge++;
    }
}
    
$tpl->assign("badges", $badges);
$tpl->assign("numbadge", $numbadge);

$tpl->assign("username", $username);
$tpl->assign("id", $id);

$location = "Advancement Progress";
$dbpage = true;
$pagename='record';
?>