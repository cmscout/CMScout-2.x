<?php
/**************************************************************************
    FILENAME        :   getphoto.php
    PURPOSE OF FILE :   Retrieves photo from database and displays photo.
    LAST UPDATED    :   25 May 2006
    COPYRIGHT       :   © 2005 CMScout Group
    WWW             :   www.cmscout.za.net
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
//error_reporting(0);
$upgrader = false;
$bit = "./";
$limitedStartup = true;
include("common.php");
require_once ("{$bit}includes/error_handling.php");
$id = safesql($_GET['pic'], 'int');
$maxsizex = isset($_GET['maxsizex']) ? $_GET['maxsizex'] : 0;
$maxsizey = isset($_GET['maxsizey']) ? $_GET['maxsizey'] : 0;
if (isset($_GET['where'])) $where = $_GET['where']; else $where = "";

$uploaddir=$config['photopath']."/";

if($where == "")
{
    $sql = $data->select_query("photos", "WHERE ID=$id AND allowed = 1");
    $pics = $data->fetch_array($sql);
    
    $image = imagecreatefromjpeg($uploaddir.$pics['filename']);
}
elseif ($where == "article")
{
    $sql = $data->select_query("patrol_articles", "WHERE ID=$id AND allowed=1");
    $article = $data->fetch_array($sql);
    $image = imagecreatefromjpeg($uploaddir.$article['pic']);
}

$width_orig = imagesx($image);
$height_orig = imagesy($image);

$width = $maxsizex;
$height = $maxsizey;
if ($maxsizex > 0 && $maxsizey > 0)
{
    if (($width_orig < $height_orig) && ($maxsizex < $width_orig)) 
    {
        $width = ($maxsizey / $height_orig) * $width_orig;
    }
    elseif (($width_orig > $height_orig) && ($maxsizex < $width_orig))
    {
        $height = ($maxsizex / $width_orig) * $height_orig;
    }
    elseif (($width_orig > $height_orig) && ($maxsizey < $height_orig))
    {
        $width = ($maxsizey / $height_orig) * $width_orig;
    }
    elseif (($width_orig < $height_orig) && ($maxsizey < $height_orig))
    {
        $height = ($maxsizex / $width_orig) * $height_orig;
    }
    elseif ($width_orig == $height_orig)
    {
        if ($maxsizex < $maxsizey)
        {
            $height = $maxsizex;
            $width = $maxsizex;
        }
        elseif ($maxsizex > $maxsizey)
        {
            $height = $maxsizey;
            $width = $maxsizey;
        }

    }
    else
    {
        $height = $height_orig;
        $width = $width_orig;
    }
}
else
{
    $height = $height_orig;
    $width = $width_orig;
}

$image_p = imagecreatetruecolor($width, $height);

imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

header('Content-type: image/jpeg');
imagejpeg($image_p, null, 100);
exit();
?>