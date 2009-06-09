<?php
/**************************************************************************
    FILENAME        :   patrolpages.php
    PURPOSE OF FILE :   Displays a groups website
    LAST UPDATED    :   25 May 2006
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
{
    die("You have accessed this page illegally, please go use the main menu");
}

$patrolid = $_GET['patrol'];
$location = "$patrolname Homepage";
/*********************************Begin Build Menu for Patrol Page*************************************/
$perrow = 1;
$sitemenu = "<ul class=\"left_title\">";

//Side Menu
$site = NULL;
if ($_GET['site'] != "")
{
     $site = $_GET['site'];
     $tpl->assign("siteid", $site);
}

$safe_patrolid = safesql($patrolid, "int");
$sql = $data->select_query("groups", "WHERE id=$safe_patrolid", "id, teamname");
if ($data->num_rows($sql) > 0)
{
    $temp = $data->fetch_array($sql);
    $safepatrolname = safesql($temp['teamname'], "text");
    $patrolname = $temp['teamname'];
    $patrolid = $temp['id'];
    $safepatrolid = safesql($temp['id'], "int");
}

$itemsql = $data->select_query("patrolmenu", "WHERE patrol=$safe_patrolid ORDER BY pos ASC");
if ($data->num_rows($itemsql) > 0) 
{   
    while ($items = $data->fetch_array($itemsql))
    {
        if ($items['type'] == 1) 
        {
            $pages = $items['item'];
            if ($site != NULL)
            {
                $sitemenu .= "<li><a href=\"index.php?page=patrolpages&amp;patrol=$patrolid&amp;site=$site&amp;content=$pages&amp;menuid=$menuid\" class=\"$style\">{$items['name']}</a></li>";
            }
            else
            {
                $sitemenu .= "<li><a href=\"index.php?page=patrolpages&amp;patrol=$patrolid&amp;content=$pages&amp;menuid=$menuid\" class=\"$style\">{$items['name']}</a></li>";
            }
        } 
        elseif ($items['type'] == 2) 
        {
            $t = $data->fetch_array($data->select_query("functions", "WHERE id = '{$items['item']}'"));
            $codes = $t['code'];
            if (($t['type'] == 4 || $t['type'] == 5) && $code != "patrolpages") 
            {
                if ($site != NULL)
                {
                    $sitemenu .= "<li><a href=\"index.php?page=patrolpages&amp;patrol=$patrolid&amp;site=$site&amp;content=$codes&amp;menuid=$menuid\" class=\"$style\">{$items['name']}</a></li>";
                }
                else
                {
                    $sitemenu .= "<li><a href=\"index.php?page=patrolpages&amp;patrol=$patrolid&amp;content=$codes&amp;menuid=$menuid\" class=\"$style\">{$items['name']}</a></li>";
                }
            }
            elseif ($code == "patrolpages")
            {
                if ($site != NULL)
                {
                    $sitemenu .= "<li><a href=\"index.php?page=patrolpages&amp;patrol=$patrolid&amp;site=$site&amp;menuid=$menuid\" class=\"$style\">{$items['name']}</a></li>";
                }
                else
                {
                    $sitemenu .= "<li><a href=\"index.php?page=patrolpages&amp;patrol=$patrolid&amp;menuid=$menuid\" class=\"$style\">{$items['name']}</a></li>";
                }
            }
        } 
        elseif ($items['type'] == 5) 
        {
            $sitemenu .= "<li><a href=\"{$items['url']}\" class=\"$style\">{$items['name']}</a></li>";
        } 
    }
}

if ($_GET['site'] != "")
{
     $site = $_GET['site'];
     $sitemenu .= "<li><a href=\"index.php?page=subsite&amp;site=$site&amp;menuid=$menuid\">Return to Site</a></li>";
}
$sitemenu .= "</ul>";
$tpl->assign("sitemenu", $sitemenu);
/*********************************End Build Menu for Patrol Page*************************************/

/*********************************Begin Get content for patrol page*************************************/
if (isset($_GET['content'])) $content = $_GET['content']; else $content = "";

$dataC = false;
$dbpage = false;
if (isset($_GET['pagenum'])) $pagenum = $_GET['pagenum']; else $pagenum = 0;
$sitecontent = "";

$sitecontent = get_page_subs($content, $patrolid, 1);
$pageid = get_page_id_subs($content, $patrolid, 1);
if ($content == '' || !isset($content)) 
{
    $sitecontent = get_frontpage_subs($patrolid, 1);	
    $edit = (adminauth("patrol", "edit") && !adminauth("patrol", "limit")) || (adminauth("patrol", "edit") && adminauth("patrol", "limit") && user_group_id($check['id'], $patrolid)) ? true : false;
    $add = (adminauth("patrol", "add") && !adminauth("patrol", "limit")) || (adminauth("patrol", "add") && adminauth("patrol", "limit") && user_group_id($check['id'], $patrolid)) ? true : false;
    $addlink = "admin.php?page=patrol&amp;subpage=patrolcontent&amp;action=new&amp;pid=$patrolid";
    $editlink = "admin.php?page=patrol&amp;subpage=patrolmenus&amp;pid=$patrolid";
}
elseif ($sitecontent == "" && file_exists($content . $phpex)) 
{
    if (get_auth($content, 0) == 1)
    {
        include($content . $phpex);
    }
    else
    {
        $dataC = true;
        $dbpage = false;
        show_message("You do not have the required permissions to view that page", "index.php?page=patrolpages&patrol=$patrolid&menuid=$menuid");
    }
}
else
{
    $edit = (adminauth("patrol", "edit") && !adminauth("patrol", "limit")) || (adminauth("patrol", "edit") && adminauth("patrol", "limit") && user_group_id($check['id'], $patrolid)) ? true : false;
    $add = (adminauth("patrol", "add") && !adminauth("patrol", "limit")) || (adminauth("patrol", "add") && adminauth("patrol", "limit") && user_group_id($check['id'], $patrolid)) ? true : false;
    $addlink = "admin.php?page=patrol&amp;subpage=patrolcontent&amp;action=new&amp;pid=$patrolid";
    $editlink = "admin.php?page=patrol&amp;subpage=patrolcontent&amp;id=$content&amp;action=edit&amp;pid=$patrolid";
}

if ($sitecontent === false)
{
    show_message("That page is only accessible by members of the group", $site != NULL ? "index.php?page=patrolpages&patrol=$patrolid&menuid=$menuid&site=$site" : "index.php?page=patrolpages&patrol=$patrolid&menuid=$menuid");
}
   
if ($pagenum == 0) 
{
    $pagenum = 1;
}

if ($dbpage == true && isset($pagename) && $pagename != "" && $pagename != "frontpage") 
{
    $sitecontent = get_temp($pagename, $pagenum);
} 
elseif (isset($pagename) && $pagename == "frontpage")
{
    $sitecontent = $content;
}
elseif ($dbpage == false && $message != "")
{
    $sitecontent = $message;
    $dataC = true;
    $othermessage = true;
}

if ($sitecontent == "")
{
    $sitecontent = $pagename;
}


if ((!isset($sitecontent) || $sitecontent == "") && (!isset($pagename) || $pagename == ""))
{
    $sitecontent = "No Content";
}

$groupstats[0] = $data->num_rows($data->select_query("patrol_articles", "WHERE allowed=1 AND trash=0 AND patrol=$safepatrolid"));
$groupstats[1] = $data->num_rows($data->select_query("album_track", "WHERE allowed=1 AND trash=0 AND patrol=$safepatrolid"));
$groupstats[2] = 0;
$tempsql = $data->select_query("album_track", "WHERE allowed=1 AND trash=0 AND patrol=$safepatrolid");
while ($temp = $data->fetch_array($tempsql))
{
    $groupstats[2] += $data->num_rows($data->select_query("photos", "WHERE allowed=1 AND album_id={$temp['ID']}"));
}
$groupstats[3] = $data->num_rows($data->select_query("usergroups", "WHERE groupid=$safepatrolid"));
$groupstats[4] = $data->num_rows($data->select_query("members", "WHERE patrol=$safepatrolid"));
$groupstats[5] = $data->num_rows($data->select_query("patrollog", "WHERE `group`=$safepatrolname"));

$cmscoutTags = array("!#articles#!", "!#albums#!", "!#photos#!", "!#users#!", "!#members#!", "!#log#!");
$sitecontent = str_replace($cmscoutTags, $groupstats, $sitecontent);
/*********************************End Get content for patrol page*************************************/

$tpl->assign("patrolpages", 1);
$tpl->assign("sitecontent", $sitecontent);
$tpl->assign("patrolname", $patrolname);
$tpl->assign("sitetitle", $patrolname . " Homepage");
$tpl->assign("patrolid", $patrolid);
$dbpage = false;
$pagename = "subsite.tpl";
?>
