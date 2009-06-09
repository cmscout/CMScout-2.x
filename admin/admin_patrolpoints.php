<?php
/**************************************************************************
    FILENAME        :   admin_patrolpoints.php
    PURPOSE OF FILE :   Manages points
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
if( !empty($getmodules) )
{
	$module['Member Management']['Points Manager'] = "patrolpoints";
    $moduledetails[$modulenumbers]['name'] = "Points Manager";
    $moduledetails[$modulenumbers]['details'] = "Manages patrol/group points";
    $moduledetails[$modulenumbers]['id'] = "patrolpoints";
    $moduledetails[$modulenumbers]['access'] = "Allowed to access the points manager";
    $moduledetails[$modulenumbers]['add'] = "notused";
    $moduledetails[$modulenumbers]['edit'] = "notused";
    $moduledetails[$modulenumbers]['delete'] = "notused";
    $moduledetails[$modulenumbers]['publish'] = "notused";
    $moduledetails[$modulenumbers]['limit'] = "Points can only be viewed if this is selected.";
	return;
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) 
{
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$edit = true;
if (pageauth("patrolpoints", "limit") == 0) 
{
    $what = $_POST['Submit'];
	if ($what == "Submit") 
    {
        $sql = $data->select_query("groups", "WHERE getpoints=1");
        while($temp=$data->fetch_array($sql))
        {
            $tpoint = safesql($_POST[$temp['id']], "int");
            $action = safesql($_POST["how_".$temp['id']], "int");
            switch($action)
            {
                case 0:
                    $tpoint = $temp['points'] + $tpoint;
                    break;
                case 1:
                    $tpoint = $temp['points'] - $tpoint >= 0 ? $temp['points'] - $tpoint : 0;
                    break;
                case 2:
                    $tpoint = $tpoint;
                    break;
                case 3:
                    $tpoint = $temp['points'];
                    break;
            }
            $up = $data->update_query("groups", "points=$tpoint", "teamname='{$temp['teamname']}'");
        }
	}
} 
else 
{
 $edit = false;
}

$points_qu = $data->select_query("groups", "WHERE getpoints=1 ORDER BY teamname ASC");
$numpoints = $data->num_rows($points_qu);
$points = array();
while ($points[] = $data->fetch_array($points_qu));

$tpl->assign('points', $points);
$tpl->assign('edits', $edit);
$tpl->assign('editFormAction', $editFormAction);
$tpl->assign('numpoints', $numpoints);
$filetouse = "admin_patrolpoints.tpl";
?>