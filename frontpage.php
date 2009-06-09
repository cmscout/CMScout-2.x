<?php
/**************************************************************************
    FILENAME        :   welcome.php
    PURPOSE OF FILE :   Builds the frontpage
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
$level = $check['level'];

$frontsql = $data->select_query("frontpage", "ORDER BY pos ASC");

$content = "";
while ($item = $data->fetch_array($frontsql))
{
  if ($item['type'] == 0) 
  {   
    if (get_auth($item['item'], 1)==1)
    {
        $pagesql = $data->select_query("static_content", "WHERE id = '{$item['item']}' AND trash=0");
        $stuff = $data->fetch_array($pagesql);
        $content .= "<div class=\"frontpage\">".censor($stuff['content'])."</div>";
    }
  } 
  elseif ($item['type'] == 1) 
  {
    $funsql = $data->select_query("functions", "where id = '{$item['item']}'");
    $stuff = $data->fetch_array($funsql);

    if (get_auth($stuff['code'], 0)==1)
    {    
        if (file_exists($stuff['code'] . $phpex)) 
        {
            include($stuff['code'] . $phpex);
        }
        
        if ($dbpage == true && isset($pagename) && $pagename != "" && $pagename != "frontpage") 
        {
            $content .= "<div class=\"frontpage\">".get_temp($pagename, $pagenum)."</div>";
        }  
    }
  }
  $content .= "<br />";
}

if ($content == "")
{
    $content = "No frontpage defined";
}
$add = false;
$edit = false;
$dbpage = true;
$pagename='frontpage';
$location = "Home Page";
$helpid = 1;
?>