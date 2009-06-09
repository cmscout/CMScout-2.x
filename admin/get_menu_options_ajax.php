<?php
/**************************************************************************
    FILENAME        :   activate.php
    PURPOSE OF FILE :   Activates accounts
    LAST UPDATED    :   09 May 2006
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
$bit = "../";
require_once ("{$bit}includes/error_handling.php");
set_error_handler('ErrorHandler');
error_reporting(E_ERROR|E_PARSE);
$upgrader = false;
$limitedStartup = true;
require_once("{$bit}common.php");

foreach ($_GET as $value => $throwAway)
{
  break;
}
$item = explode("_", $value);

$item[0] = safesql($item[0], "int");
switch ($item[1])
{
    case "dynamic":
    case "dyn":
    case "box":
      $itemDetails = $data->select_fetch_one_row("functions", "WHERE id={$item[0]}");
      $itemOptions = explode(",", $itemDetails['options']);
      if ($itemOptions[0] != "")
      {
        $itemSectionSql = $data->select_query($itemOptions[0], "ORDER BY {$itemOptions[2]}", "{$itemOptions[1]},{$itemOptions[2]}");
        echo "<label for=\"options\" class=\"label\">{$itemOptions[4]}</label> 
           <div class=\"inputboxwrapper\">
           <select name=\"options\" id=\"options\" class=\"inputbox\">
           <option value=\"0\">{$itemOptions[3]}</option>";
           while ($temp = $data->fetch_array($itemSectionSql))
           {
             echo "<option value=\"{$temp[$itemOptions[1]]}\">{$temp[$itemOptions[2]]}</option>";
           }
        echo "</select>
        </div><br />";
      }
      break;
    case "sub":
      $itemSectionSql = $data->select_query("static_content", "WHERE type=2 AND pid = {$item[0]} ORDER BY friendly", "id,friendly");
      echo "<label for=\"options\" class=\"label\">Page</label> 
         <div class=\"inputboxwrapper\">
         <select name=\"options\" id=\"options\" class=\"inputbox\">
         <option value=\"0\">Site home page</option>";
         while ($temp = $data->fetch_array($itemSectionSql))
         {
           echo "<option value=\"{$temp['id']}\">{$temp['friendly']}</option>";
         }
      echo "</select>
      </div><br />";
      break;
    case "group":
      $itemSectionSql = $data->select_query("static_content", "WHERE type=1 AND pid = {$item[0]} ORDER BY friendly", "id,friendly");
      echo "<label for=\"options\" class=\"label\">Page</label> 
         <div class=\"inputboxwrapper\">
         <select name=\"options\" id=\"options\" class=\"inputbox\">
         <option value=\"0\">Site home page</option>";
         while ($temp = $data->fetch_array($itemSectionSql))
         {
           echo "<option value=\"{$temp['id']}\">{$temp['friendly']}</option>";
         }
      echo "</select>
      </div><br />";      
      break;
    case "art":
      break;
    case "static":
    case "stat":
      break;
}

?>