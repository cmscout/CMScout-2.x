<?php
/**************************************************************************
    FILENAME        :   randphoto.php
    PURPOSE OF FILE :   Sidebox: Random photo from the photo albums
    LAST UPDATED    :   20 July 2006
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
if ($items['option'])
{
    $sql = $data->select_query("photos", "WHERE album_id={$items['option']} ORDER BY id DESC", "id");
}
else
{
    $sql = $data->select_query("photos", "ORDER BY id DESC", "id");
}
$temp = $data->fetch_array($sql);

if ($data->num_rows($sql))
{
    $max = $temp['id'];
    $ok = false;

    do
    {
        $randid = rand(1, $max);
        if ($items['option'])
        {
            $sql = $data->select_query("photos", "WHERE id=$randid AND album_id={$items['option']}");
        }
        else
        {
            $sql = $data->select_query("photos", "WHERE id=$randid");
        }
        if ($data->num_rows($sql) > 0)
        {
            $sideboxphoto[$items['id']] = $data->fetch_array($sql);
            $ok = true;
        }
    } while ($ok == false);
}
$tpl->assign("sideboxphoto", $sideboxphoto);
?>