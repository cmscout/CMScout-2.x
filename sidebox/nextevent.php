<?php
/**************************************************************************
    FILENAME        :   nextevent.php
    PURPOSE OF FILE :   Sidebox: Shows the next calender event
    LAST UPDATED    :   25 September 2006
    COPYRIGHT       :   © 2006 CMScout Group
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
$monthnums = array("January" => 1,
                   "February" => 2,
                   "March" => 3,
                   "April" => 4,
                   "May" => 5,
                   "June" => 6,
                   "July" => 7,
                   "August" => 8,
                   "September" => 9,
                   "October" => 10,
                   "November" => 11,
                   "December" => 12);
                   
$sql = $data->select_query("calendar_items", "WHERE startdate > $timestamp ORDER BY startdate ASC");
$nextevent = array();
while($temp = $data->fetch_array($sql))
{
    $groups = unserialize($temp['groups']);
    
    if (is_array($groups))
    {
        $allowed = in_group($groups);
    }
    else
    {
        $allowed = true;
    }
    
    if ($allowed)
    {
        $temp['detail'] = truncate(strip_tags($temp['detail']), 150);
        $nextevent[] = $temp;
    }
    if (count($nextevent) >= $config['numsidebox'])
    {
        break;
    }
}

$tpl->assign("nextevent", $nextevent);
?>