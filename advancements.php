<?php
/**************************************************************************
    FILENAME        :   advancements.php
    PURPOSE OF FILE :   Displays a list of all available advancements and their requirements
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
$location = "Award Schemes";

if (isset($check["uname"])) 
{
 $tpl->assign('name',$check["uname"]);
}

if (isset($_GET['scheme']))
{
    $scheme = safesql($_GET['scheme'], "text");
    $advansql = $data->select_query("advancements", "WHERE scheme=$scheme ORDER BY position ASC");
    $numadva = $data->num_rows($advansql);
    $advancements = array();
    $numitems = 0;
    $sql = $data->select_query("awardschemes", "WHERE id=$scheme");
    $schemeinfo = $data->fetch_array($sql);
    
    while ($temp = $data->fetch_array($advansql)) 
    {
        $getrequirements = $data->select_query("requirements", "WHERE advancement = '{$temp["ID"]}' ORDER BY position ASC");
        $temp['numitems'] = $data->num_rows($getrequirements);
        while ($temp2 = $data->fetch_array($getrequirements))
        {
            $temp['items'][] = $temp2;
        }
        $advancements[] = $temp;
    }
    
    $sql = $data->select_query("awardschemes");
    $numschemes = $data->num_rows($sql);
    $schemes = array();
    while($temp = $data->fetch_array($sql))
    {
        if ($data->num_rows($data->select_query("advancements", "WHERE scheme={$temp['id']}", "id")) > 0)
        {
            $schemes[] = $temp;
        }
        else
        {
            $numschemes--;
        }
    }
    
    $tpl->assign("schemes", $schemes);
    $tpl->assign("numschemes", $numschemes);
    $tpl->assign("schemeNumber", $_GET['scheme']);
    
    $location = $schemeinfo['name'];
    
    $tpl->assign("advan", $advancements);
    $tpl->assign("schemeinfo", $schemeinfo);
    $tpl->assign("numadva", $numadva);
}
else
{
    $sql = $data->select_query("awardschemes");
    $numschemes = $data->num_rows($sql);
    $schemes = array();
    while($temp = $data->fetch_array($sql))
    {
        if ($data->num_rows($data->select_query("advancements", "WHERE scheme={$temp['id']}", "id")) > 0)
        {
            $schemes[] = $temp;
        }
        else
        {
            $numschemes--;
        }
    }
    
    $tpl->assign("schemes", $schemes);
    $tpl->assign("numschemes", $numschemes);
    
}

$dbpage = true;
$pagename='advancements';
?>