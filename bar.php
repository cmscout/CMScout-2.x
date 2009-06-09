<?php
/**************************************************************************
    FILENAME        :   bar.php
    PURPOSE OF FILE :   Creates a bar graph
    LAST UPDATED    :   31 December 2005
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
$width = isset($_GET['width']) ? $_GET['width'] : 102;
$height = isset($_GET['height']) ? $_GET['height'] : 10;

$rating = $_GET['rating'];
$ratingbar = (($rating/100)*$width)-2;

$image = imagecreate($width,$height);
//colors
$red = $_GET['red'];
$green = $_GET['green'];
$blue = $_GET['blue'];
$back = ImageColorAllocate($image,255,255,255);
$border = ImageColorAllocate($image,0,0,0);
$red = ImageColorAllocate($image,255,60,75);
$fill = ImageColorAllocate($image,$red,$green,$blue);

ImageFilledRectangle($image,0,0,$width-1,$height-1,$back);
ImageFilledRectangle($image,1,1,$ratingbar,$height-1,$fill);
ImageRectangle($image,0,0,$width-1,$height-1,$border);

Header("Content-type: image/png");
imagePNG($image);
imagedestroy($image);
?>