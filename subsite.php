<?php
/**************************************************************************
    FILENAME        :   subsite.php
    PURPOSE OF FILE :   Displays a Sub website
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
{
    die("You have accessed this page illegally, please go use the main menu");
}

$subsite= $_GET['site'];
$safe_subsite = safesql($subsite, "text");
$temp = $data->select_fetch_one_row("subsites", "WHERE id=$safe_subsite", "name");
$sitename = $temp['name'];
$location = $sitename;
$denied = false;
if (get_auth($subsite, 3) != 1)
{
    if ($check['uname'] != "Guest")
    {
        $dataC = true;
        $dbpage = false;
        $filetouse = "You do not have the required permisions to view this page.";
        $denied = true;
    }
    else
    {
        $query = $_SERVER['QUERY_STRING'];
        $redirectpage = str_replace("page=", "", $query);
        header("Location: index.php?page=logon&redirect=$redirectpage");
    }
}

/*********************************Begin Build Menu for Patrol Page*************************************/
if (!$denied)
{
    $perrow = 1;
    $sitemenu = "<ul class=\"left_title\">";
    
    //Side Menu
    $itemsql = $data->select_query("submenu", "WHERE site=$safe_subsite ORDER BY pos ASC");
    if ($data->num_rows($itemsql) > 0) 
    {   
        while ($items = $data->fetch_array($itemsql))
        {
            if ($items['type'] == 1) 
            {
                $pagelink = $items['item'];
                $sitemenu .= "<li><a href=\"index.php?page=subsite&amp;site=$subsite&amp;content=$pagelink&amp;menuid=$menuid\">{$items['name']}</a></li>";
            } 
            elseif ($items['type'] == 2) 
            {
                $t = $data->select_fetch_one_row("functions", "WHERE id = '{$items['item']}'");
                $code = $t['code'];
                if (($t['type'] == 6) && $code != "subsite") 
                {
                    $sitemenu .= "<li><a href=\"index.php?page=subsite&amp;site=$subsite&amp;content=$code&amp;menuid=$menuid\">{$items['name']}</a></li>";
                }
                elseif ($code == "subsite")
                {
                    $sitemenu .= "<li><a href=\"index.php?page=subsite&amp;site=$subsite&amp;menuid=$menuid\">{$items['name']}</a></li>";
                }
            } 
            elseif ($items['type'] == 5) 
            {
                $sitemenu .= "<li><a href=\"{$items['url']}\">{$items['name']}</a></li>";
            }
            elseif ($items['type'] == 3)
            {
                $sitemenu .= "<li><a href=\"index.php?page=patrolpages&amp;patrol={$items[item]}&amp;site=$subsite&amp;menuid=$menuid\">{$items['name']}</a></li>";
            }
            elseif($items['type'] == 4)
            {
                $sitemenu .= "<li><a href=\"index.php?page=patrolarticle&amp;id={$items[item]}&amp;action=view&amp;menuid=$menuid\">{$items['name']}</a></li>";
            }
        }
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

$sitecontent = get_page_subs($content, $subsite, 2);
if ($content == '' || !isset($content)) 
{
    $sitecontent = get_frontpage_subs($subsite, 2);	
    $edit = adminauth("subsite", "edit") ? true : false;
    $add = adminauth("subsite", "add") ? true : false;
    $addlink = "admin.php?page=subsite&amp;subpage=subcontent&amp;action=new&amp;sid=$subsite";
    $editlink = "admin.php?page=subsite&amp;subpage=submenu&amp;sid=$subsite";
}
elseif ($sitecontent == "" && file_exists($content . $phpex)) 
{
    if (get_auth($content) == 1)
    {   
        include($content . $phpex);
    }
    else
    {
        $dataC = true;
        $dbpage = false;
        $filetouse = "You do not have the required permisions to view this page.";
        $denied = true;
    }
}
else
{
    $edit = adminauth("subsite", "edit") ? true : false;
    $add = adminauth("subsite", "add") ? true : false;
    $addlink = "admin.php?page=subsite&amp;subpage=subcontent&amp;action=new&amp;sid=$subsite";
    $editlink = "admin.php?page=subsite&amp;subpage=subcontent&id=$content&action=edit&sid=$subsite";
}
  
if ($pagenum == 0) 
{
    $pagenum = 1;
}

if ($dbpage == true && isset($pagename) && $pagename != "" && $pagename != "frontpage" && $sitecontent == "") 
{
    $sitecontent = get_temp($pagename, $pagenum);
} 
elseif (isset($pagename) && $pagename == "frontpage")
{
    $sitecontent = $content;
}

if ($sitecontent == "")
{
    $sitecontent = $pagename;
}


if ((!isset($sitecontent) || $sitecontent == "") && (!isset($pagename) || $pagename == ""))
{
    $sitecontent = "";
}
 


    /*********************************End Get content for patrol page*************************************/
    
    $tpl->assign("sitecontent", $sitecontent);
    $tpl->assign("sitetitle", $sitename);
    $tpl->assign("sitepages", 1);
    $dbpage = false;
    $pagename = "subsite.tpl";
}
?>