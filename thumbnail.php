<?php
/**************************************************************************
    FILENAME        :   thumbnail.php
    PURPOSE OF FILE :   Displays a thumbnail image
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
error_reporting(0);
$upgrader = false;
$bit = "./";
$limitedStartup = true;
include("common.php");
$id = safesql($_GET['pic'], "int");
$uploaddir=$config['photopath']."/";
$cachedir=$config['photopath']."/thumbnails/";

if ($id > 0)
{
    $sql = $data->select_query("photos", "WHERE ID=$id");
    $pics = $data->fetch_array($sql);
    $file = $pics['filename'];
}
else
{
    $file = "nopic.jpeg";
}

if (!file_exists($cachedir . "thumb_" . $file))
{
    $image = imagecreatefromjpeg($uploaddir.$file);
    $width = 120;
    $height = 120;

    list($width_orig, $height_orig) = getimagesize($uploaddir.$file);

    if ($width && ($width_orig < $height_orig)) 
    {
        $width = ($height / $height_orig) * $width_orig;
    }
    else 
    {
        $height = ($width / $width_orig) * $height_orig;
    }

    $image_p = imagecreatetruecolor($width, $height);
    $image = imagecreatefromjpeg($uploaddir.$file);

    imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

    imagejpeg($image_p,$cachedir . "thumb_" . $file, 80);

    header('Content-type: image/jpeg');
    imagejpeg($image_p, null, 80);
    exit();
}
else
{
    header('Content-type: image/jpeg');
    echo file_get_contents($cachedir . "thumb_" . $file);
    exit;
}
?>