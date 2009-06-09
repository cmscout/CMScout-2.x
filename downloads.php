<?php
/**************************************************************************
    FILENAME        :   downloads.php
    PURPOSE OF FILE :   Displays downloads, fetches downloads when user requests one
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
$location = "Downloads";

$isdown = "b";
$catid = isset($_GET['catid']) ? $_GET['catid'] : 0;
$action = isset($_GET['action']) ?$_GET['action'] : '';
$id = isset($_GET['id']) ? $_GET['id']: 0;
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) 
{
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ($action == 'down') 
{
	$id = safesql($id, "int");
	$down = $data->select_fetch_one_row("downloads", "WHERE id=$id");
	$catinfo = $data->select_fetch_one_row("download_cats", "WHERE id={$down['cat']}");
	$auth = unserialize($catinfo['downauth']);
	$allowed  = 0;
	if ($check['id'] == "-1")
	{
		if ($auth['-1'] == 1)
		{
			$allowed = 1;
		}
	}
	else
	{
		$usergroups = user_groups_id_array($check['id']);

		for($i=0;$i<count($usergroups);$i++)
		{                
		    if($auth[$usergroups[$i]] == 1)
		    {
			$allowed = 1;
		    }
		}
	}
	if ($allowed == 1)
	{
		if (file_exists($config["downloadpath"] . '/' . $down['saved_file']) && $check['bot'] == 0) 
		{
			location("Downloading {$down['name']}", $check["uid"]);
			$sql = $data->update_query("downloads", "numdownloads = numdownloads + 1", "id='$id'", "", "", false);
			header('Content-type: application/octet-stream');

			header('Content-Disposition: attachment; filename="'. $down['file'] .'"'); 

			echo file_get_contents($config["downloadpath"] . '/' . $down['saved_file']);
			exit;
		} 
		elseif ($check['bot'] == 0)
		{
			show_message('File not found, please contact the administrator', "index.php?page=downloads&catid={$catinfo['id']}&action=cat&menuid=$menuid");
		}
	}
	else
	{
			show_message('You do not have permission to download that file', "index.php?page=downloads&menuid=$menuid");
	}
}

if ($catid <> 0 && ($action == 'cat' || $action == 'down')) 
{
	$catid = safesql($catid, "int");
	$sql = $data->select_query("download_cats", "WHERE id=$catid");
	$catinfo = $data->fetch_array($sql);
	
	$sql = $data->select_query("downloads", "WHERE cat=$catid AND allowed = 1 AND trash=0");
	$numdown = $data->num_rows($sql);

	$downloads = array();
	while ($temp = $data->fetch_array($sql))
    {
        $temp['name'] = censor($temp['name']);
        $temp['descs'] = censor($temp['descs']);
        $downloads[] = $temp;
    }
    
    if ($numdown == 0)
    {
        $action = "";
    }
    else
    {
	    $auth = unserialize($catinfo['downauth']);
	    $allowed  = 0;
	    if ($check['id'] == "-1")
	    {
		if ($auth['-1'] == 1)
		{
			$allowed = 1;
		}
	    }
	    else
	    {
		$usergroups = user_groups_id_array($check['id']);
		
		for($i=0;$i<count($usergroups);$i++)
		{                
		    if($auth[$usergroups[$i]] == 1)
		    {
			$allowed = 1;
		    }
		}
	    }
	    if ($allowed == 1)
	    {
		$location = "{$catinfo['name']} Downloads";
		$tpl->assign("numdown", $numdown);
		$tpl->assign("downloads", $downloads);
		$tpl->assign("catinfo", $catinfo);
	    }
		else
		{
			show_message('You do not have permission to view that category', "index.php?page=downloads&menuid=$menuid");
		}
    }
} 

if ($action == "")
{
    $sql = $data->select_query("download_cats", "ORDER BY name ASC");
    $numcats = 0;
    $cats = array();
    while ($tempcat = $data->fetch_array($sql))
    {
        $numdownloads = $data->num_rows($data->select_query("downloads", "WHERE cat = '{$tempcat['id']}' AND allowed=1 AND trash=0"));
        $last = $data->fetch_array($data->select_query("downloads", "WHERE cat = '{$tempcat['id']}' AND allowed=1 AND trash=0 ORDER BY id DESC"));
        $tempcat['numdownloads'] = $numdownloads;
        $tempcat['quicklinkname'] = $last['name'];
        $tempcat['quickid'] = $last['id'];
        if ($numdownloads > 0)
        {
            $auth = unserialize($tempcat['downauth']);
            if ($check['id'] == "-1")
            {
                if ($auth['-1'] == 1)
                {
                    $cats[] = $tempcat;
                    $numcats++;
                }
            }
            else
            {
                $usergroups = user_groups_id_array($check['id']);
                
                for($i=0;$i<count($usergroups);$i++)
                {                
                    if($auth[$usergroups[$i]] == 1)
                    {
                        $cats[] = $tempcat;
                        $numcats++;
                        break;
                    }
                }
            }
        }
    }
}

$tpl->assign("action", $action);
$tpl->assign("numcats", $numcats);
$tpl->assign("cats", $cats);
$dbpage = true;
$pagename = "downloads";
?>