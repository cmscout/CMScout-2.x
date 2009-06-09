<?php
/**************************************************************************
    FILENAME        :   calender.php
    PURPOSE OF FILE :   Displays the calender
    LAST UPDATED    :   24 September 2006
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
$location = "Calendar";
/********************************************Check if user is allowed*****************************************/
require_once "{$bit}includes/iCalcreator.class.php";

function number_days($month)
{
    global $year;
    switch($month)
    {
        case 1:
            $numdays = 31;
            break;
        case 2:
            if (($year%4 == 0) || ($year%100 == 0 && $year%400 == 0))
                $numdays =  29;
            else
                $numdays =  28;  
            break;
        case 3:
            $numdays =  31;
            break;
        case 4:
            $numdays =  30;
            break;
        case 5:
            $numdays = 31;
            break;
        case 6:
            $numdays = 30;
            break;
        case 7:
            $numdays = 31;
            break;
        case 8:
            $numdays = 31;
            break;
        case 9:
            $numdays = 30;
            break;
        case 10:
            $numdays = 31;
            break;
        case 11:
            $numdays = 30;
            break;
        case 12:
            $numdays = 31;
            break;
    }
    
    return $numdays;
}


$monthName = array(1 => "January",
                   2 => "February",
                   3 => "March",
                   4 => "April",
                   5 => "May",
                   6 => "June",
                   7 => "July",
                   8 => "August",
                   9 => "September",
                  10 => "October",
                  11 => "November",
                  12 => "December");
                  
$smonthName = array(1 => "Jan",
                   2 => "Feb",
                   3 => "Mar",
                   4 => "Apr",
                   5 => "May",
                   6 => "Jun",
                   7 => "Jul",
                   8 => "Aug",
                   9 => "Sep",
                  10 => "Oct",
                  11 => "Nov",
                  12 => "Dec");
                  
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

$date = getdate();

$today_date["month"] = $date['month'];
$today_date["year"] = $date['year'];
$today_date["day"] = $date['mday'];


if (isset($_GET['item'])) $itemid = safesql($_GET['item'], "int");
if ($_GET['action'] != "ical")
{
    if (isset($itemid))
    {
        $calsql = $data->select_query("calendar_items", "WHERE id = $itemid AND allowed = 1 AND trash=0");
        $item = $data->fetch_array($calsql);
        $item['summary'] = censor($item['summary']);
        $item['detail'] = censor($item['detail']);
        $show_detail = true;
        
        if ($item['signup'])
        {
            $member = $data->select_fetch_one_row("members", "WHERE userId={$check['id']}");
            if ($member)
            {
                $attendie = $data->select_fetch_one_row("attendies", "WHERE uid={$member['id']} AND eid = $itemid");
                if ($attendie)
                {
                    $downloadaccess = 1;
                    $member['attend'] = 1;
                    $member['attendoptions'] = unserialize($attendie['options']);
                }
                $item['patrols'] = unserialize($item['patrols']);
                if ($item['signupusers'] == 0)
                {
                    if ($member['type'] == 1 || $member['type'] == 2)
                    {
                        $signupallowed = 1;
                    }
                    elseif ($member['type'] == 0 && $item['patrols'][$member['patrol']] == 1)
                    {
                        $signupallowed = 1;
                    }
                }
                elseif($item['signupusers'] == 1 && $member['type'] == 0 && $item['patrols'][$member['patrol']] == 1)
                {
                    $signupallowed = 1;
                }
                elseif($item['signupusers'] == 2 && ($member['type'] == 1 || $member['type'] == 2))
                {
                    $signupallowed = 1;
                }
                elseif($item['signupusers'] == 3 && $item['patrols'][$member['id']] == 1)
                {
                    $signupallowed = 1;
                }
                
                if ($member['type'] == 1 || $member['type'] == 2)
                {
                    $sql = $data->select_query("members", ($member['type'] == 1 ? "WHERE fatherId={$member['id']}" : "WHERE motherId={$member['id']}"));
                    
                    $numkids = 0;
                    $kidlist = array();
                    $kidid = array();
                    
                    while ($temp = $data->fetch_array($sql))
                    {
                        $attendie = $data->select_fetch_one_row("attendies", "WHERE uid={$temp['id']} AND eid = $itemid");
                        if ($attendie)
                        {
                            $downloadaccess = 1;
                            $temp['attend'] = 1;
                            $temp['attendoptions'] = unserialize($attendie['options']);
                        }
                        if (($item['signupusers'] == 0 || $item['signupusers'] == 1) && $temp['type'] == 0 && $item['patrols'][$temp['patrol']] == 1)
                        {
                            $kidlist[] = $temp;
                            $kidid[] = $temp['id'];
                            $numkids++;
                        }
                        elseif($item['signupusers'] == 3 && $item['patrols'][$temp['id']] == 1)
                        {
                            $kidlist[] = $temp;
                            $kidid[] = $temp['id'];
                            $numkids++;
                        }   
                    }
                }
                $tpl->assign("member", $member);
                $tpl->assign("signupallowed", $signupallowed);
                $tpl->assign("numkids", $numkids);
                $tpl->assign("kidlist", $kidlist);
                if ($_POST['Submit'] == "Update")
                {
                    $attendies = $_POST['attend'];
                    $options = $_POST['options'];
                    
                    $userattend = $_POST['userattend'];
                    $useroptions = $_POST['useroptions'];
                    
                    $kidid[] = $member['id'];
                    $kididlist = sql_list($kidid, "uid", "OR");
                    $data->delete_query("attendies", "($kididlist) AND eid=$itemid");
                    
                    foreach($attendies as $uid => $attend)
                    {
                        if ($attend == 1)
                        {
                            $useroption = safesql(serialize($options[$uid]), "text");
                            if ($data->num_rows($data->select_query("attendies", "WHERE uid=$uid AND eid = $itemid")) == 0)
                            {
                                $data->insert_query("attendies", "'', $uid, $itemid, $useroption");
                            }
                            else
                            {
                                $data->update_query("attendies", "options = $useroption", "uid=$uid AND eid=$itemid");
                            }
                        }
                    }
                    
                    if ($userattend == 1)
                    {
                        $uid = $member['id'];
                        $useroption = safesql(serialize($useroptions), "text");
                        if ($data->num_rows($data->select_query("attendies", "WHERE uid=$uid AND eid = $itemid")) == 0)
                        {
                            $data->insert_query("attendies", "'', $uid, $itemid, $useroption");
                        }
                        else
                        {
                            $data->update_query("attendies", "options = $useroption", "uid=$uid AND eid=$itemid");
                        }
                    }
                    show_message("Attendies Updated", "index.php?page=calender&item=$itemid&menuid={$menuid}");
                }
            }
            $sql = $data->select_query("profilefields", "WHERE place=2 AND eventid=$itemid AND register=1 ORDER BY query ASC");
        
            $numfields = $data->num_rows($sql);
            $fields = array();
            while ($temp =  $data->fetch_array($sql))
            {
                $temp['options'] = unserialize($temp['options']);
                $fields[] = $temp;
            }
            $tpl->assign("numfields", $numfields);
            $tpl->assign("fields", $fields);
        }
        
        $edit = is_owner($item['id'], "events") ? true : false;
        $editlink = "index.php?page=mythings&amp;cat=events&amp;action=edit&amp;id={$item['id']}&amp;cal=1&amp;menuid=$menuid";
                
        $articlesql = $data->select_query("patrol_articles", "WHERE event_id={$item['id']} AND allowed=1 AND trash=0 ORDER BY title ASC", "ID, title");
        $numarticles = $data->num_rows($articlesql);
        $articlelist = array();
        while ($articlelist[] = $data->fetch_array($articlesql));
        
        $sql = $data->select_query("calendar_downloads", "WHERE eid=$itemid");
        $downloads = array();
        $numdownloads = 0;
        while ($temp = $data->fetch_array($sql))
        {
            $temp1 = $data->select_fetch_one_row("downloads", "WHERE id={$temp['did']}", "name, cat");
            $temp['name'] = $temp1['name'];
          
            
            if ($downloadaccess != 1 && $temp['permission'] == 0)
            {
                $tempcat = $data->select_fetch_one_row("download_cats", "WHERE id={$temp1['cat']}");
                
                $auth = unserialize($tempcat['downauth']);

                if ($check['uname'] == "Guest")
                {
                    if ($auth['Guest'] == 1)
                    {
                        $downloads[] = $temp;
                        $numdownloads++;
                    }
                }
                else
                {
                    $usergroups = user_groups_id_array($check['id']);
                    
                    for($i=0;$i<count($usergroups);$i++)
                    {                
                        if($auth[$usergroups[$i]] == 1)
                        {
                            $downloads[] = $temp;
                            $numdownloads++;
                            break;
                        }
                    }
                }
            }
            elseif ($downloadaccess == 1)
            {
                $downloads[] = $temp;
                $numdownloads++;                
            }
        }
        $tpl->assign("numdownloads", $numdownloads);
        $tpl->assign("downloads", $downloads);
        
        $tpl->assign("numarticles", $numarticles);
        $tpl->assign("articlelist", $articlelist);        
        $tpl->assign("item", $item);
    } 
    else 
    {
        $calendar = ""; 
        
        
        if (isset($_POST['month']) && $_POST['month'] != "") 
        {
            $month = $_POST["month"];
        }
        else
        {
            $month = $today_date['month'];
        }
        
        if (isset($_POST['year']) && $_POST['year'] != "") 
        {
            $year = $_POST["year"];
        }
        else
        {
            $year = $today_date['year'];
        }

        $found = false;
        $monthnum = 1;
        do 
        {
            if ($month == $monthName[$monthnum]) 
            {
                $found = true; 
                break; 
            }
            $monthnum++;
            if ($monthnum > 11) 
            {
                break;
            }
        } while ((!$found));

        
        if ($_GET['month'] != '' && !isset($_POST['month']))
        {
            $month = $monthName[$_GET['month']];
            $monthnum = $_GET['month'];
        }

        if ($_GET['year'] != ''&& !isset($_POST['year']))
        {
            $year = $_GET['year'];
        }
        
        if ($monthnum > 12)
        {
            $monthnum = 1;
            $month = $monthName[$monthnum];
            $year++;
        }
        if ($monthnum < 1)
        {
            $monthnum = 12;
            $month = $monthName[$monthnum];
            $year--;
        }
        if ($_GET['view'] == "year" || ($_GET['view'] == "" && $config['defaultview'] == "Year"))
        {           
            $summaries = array();
            $yearstart = strtotime("01/01/$year 00:00:00");
            $yearend = strtotime("12/31/$year 23:59:59"); 
	
            $calitems = $data->select_fetch_all_rows($numcalendar, "calendar_items", "WHERE  ((startdate >= $yearstart OR enddate >= $yearstart) AND startdate <= $yearend) AND allowed=1 AND trash=0 ORDER BY startdate ASC");

            $icalsql = $data->select_query("ical_items");
            $temp2 = $data->select_fetch_one_row("calendar_items", "ORDER BY id DESC", "id");
            $startid = $temp2['id'] + 1;
            if ($data->num_rows($icalsql) > 0)
            {
                while ($temp = $data->fetch_array($icalsql))
                {
                    $vcalendar = new vcalendar();
                    echo $vcalendar->parse('http://' . $temp['link']);
                    while( $vevent = $vcalendar->getComponent()) 
                    {
                        $item = array();
                        $item['id'] = $startid++;
                        $item['summary'] = censor($vevent->getProperty('summary') . " [{$temp['name']}]");
                        $item['detail'] = censor($vevent->getProperty('description'));
                        $tempdate = $vevent->getProperty('dtstart');
                        $item['startdate'] = mktime($tempdate['hour'], $tempdate['min'], 0, $tempdate['month'], $tempdate['day'], $tempdate['year']);
                        $tempdate = $vevent->getProperty('dtend');
                        $item['enddate'] = mktime($tempdate['hour'], $tempdate['min'], 0, $tempdate['month'], $tempdate['day'], $tempdate['year']);
                        $item['groups'] = $temp['groups'];
                        $item['colour'] = $temp['colour'];
                        $item['ical'] = true;
                        
                        $calitems[] = $item;
                        $numcalendar++;
                    }
                }
            }

            foreach ($calitems as $key => $row) {
                $startdate[$key] = $row['startdate'];
            }
            array_multisort($startdate, SORT_ASC, $calitems);
           
            $summaries = array();

            for($calnumber=0;$calnumber<$numcalendar;$calnumber++)
            {
                $groups = unserialize($calitems[$calnumber]['groups']);
                $startdate = getdate($calitems[$calnumber]['startdate']);
                $enddate = getdate($calitems[$calnumber]['enddate']); 

		if (($startdate['year'] >= $year || $enddate['year'] >= $year) && $startdate['year'] <= $year)
                {
                    if ($calitems[$calnumber]['groups'] != "N;")
                    {
                        $allowed = in_group($groups);
                    }
                    else
                    {
                        $allowed = true;
                    }
                    if ($allowed)
                    {
                        $display['id'] = $calitems[$calnumber]['id'];
                        $display['startdatestamp'] = strtotime(strftime("%Y-%m-%d", $calitems[$calnumber]['startdate']));
                        $display['enddatestamp'] = strtotime(strftime("%Y-%m-%d ", $calitems[$calnumber]['enddate']));
                        $display['startdate'] = strftime("%Y-%m-%d ", $calitems[$calnumber]['startdate']);
                        $display['enddate'] = strftime("%Y-%m-%d", $calitems[$calnumber]['enddate']);
                        $display['colour'] = $calitems[$calnumber]['colour'];
                        $display['summary'] = censor($calitems[$calnumber]['summary']);
                        $display['detail'] = censor(truncate(strip_tags($calitems[$calnumber]['detail']), 150));
                        $display['starttime'] = strftime("%H:%M", $calitems[$calnumber]['startdate']);
                        $display['endtime'] = strftime("%H:%M", $calitems[$calnumber]['enddate']);
                        
                        $calitems[$calnumber]['position'] = 0;
                        $startMonth = $startdate['mon'];
                        $endMonth = $enddate['mon'];
			
			if ($startdate['year'] < $year)
			{
				$startMonth = 1;
				$startdate['mday'] = 1;
			}
			
			if ($enddate['year'] > $year)
			{
				$endMonth = 12;
				$enddate['mday'] = 31;
			}

                        for($pos=0;$pos<=max(array_keys($summaries[$startMonth][$startdate['mday']]['item']))+1;$pos++)
                        {
                            if (!isset($summaries[$startMonth][$startdate['mday']]['item'][$pos]))
                            {
                                $calitems[$calnumber]['position'] = $pos;
                                break;
                            }
                        }
                        
                        for($monthLoop=$startMonth;$monthLoop<=$endMonth;$monthLoop++)
                        {
                            $startDayLoop = $monthLoop == $startMonth ? $startdate['mday'] : 1;
                            $endDayLoop = $monthLoop == $endMonth ? $enddate['mday'] : number_days($monthLoop);
                            
                            for($dayLoop=$startDayLoop;$dayLoop<=$endDayLoop;$dayLoop++)
                            {
                                $summaries[$monthLoop][$dayLoop]['item'][$calitems[$calnumber]['position']] = $display;
                            }
                        }
                    }
                }
            }
            
            for($months=1;$months<=12;$months++)
            {
                for($days=1;$days<=number_days($months);$days++)
                {
                    $items = $summaries[$months][$days]['item'];
                    ksort($items);
                    $summaries[$months][$days]['item'] = $items;
                    $summaries[$months][$days]['number'] = max(array_keys($items))+1;
                }
            }


            $previousYear = $year - 1;
            $nextYear = $year + 1;
            $calendar = "
            <form name=\"form1\" method=\"post\" action=\"\">
                <div align=\"center\">
                    <a href=\"index.php?page=calender&amp;view=year&amp;year={$today_date['year']}&amp;menuid={$menuid}\"><img src=\"{$templateinfo['imagedir']}today.png\" border=\"0\" title=\"Current Year\" alt=\"Current Year\"/></a>           
                </div>
                <div align=\"center\">
                  <a href=\"index.php?page=calender&amp;view=year&amp;year=$previousYear&amp;menuid={$menuid}\"><img src=\"{$templateinfo['imagedir']}first.png\" title=\"Previous Year\" alt=\"Previous Year\" border=\"0\"/></a>
                    &nbsp;<select name=\"year\" onchange=\"form1.submit()\" class=\"inputbox\">";
                    for ($i=$today_date['year']-5;$i<=$today_date['year']+5;$i++)  
                    {
                        $calendar .= "<option value=\"$i\"";
                        if ($year == $i) 
                        {
                            $calendar .= "selected=\"selected\"";
                        }
                        $calendar .= ">$i</option>";
                    }
                    
                $calendar .= "  </select>
                    &nbsp;<a href=\"index.php?page=calender&amp;view=year&amp;year=$nextYear&amp;menuid={$menuid}\"><img src=\"{$templateinfo['imagedir']}last.png\" title=\"Next Year\" alt=\"Next Year\" border=\"0\"/></a>
                  </div>
            </form>
            <table width=\"100%\" class=\"table\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
            <tr>
            <th class=\"normalhead\" colspan=\"24\" style=\"border:1px solid black;\">$year</th>
            </tr>
              <tr>";
                for ($i=1;$i<=12;$i++) 
                {
                    $calendar .= "
                    <th width=\"7%\" class=\"calendar_year_view_head\" colspan=\"2\">{$smonthName[$i]}<br /><a href=\"index.php?page=calender&amp;view=month&amp;month={$i}&amp;year=$year&amp;menuid=$menuid\"><img src=\"{$templateinfo['imagedir']}view_month.png\" title=\"View Month\" alt=\"View Month\" border=\"0\"/></a>
                    <a href=\"index.php?page=calender&amp;view=list&amp;month={$i}&amp;year=$year&amp;menuid={$menuid}\"><img src=\"{$templateinfo['imagedir']}list_view.png\" border=\"0\" title=\"List View\" alt=\"List View\"/></a></th>";
                }   
              $calendar .= "  </tr>	  
              ";
              
              for ($day=1;$day<=31;$day++)
              {
                    $calendar .= "<tr>";
                    for ($month=1;$month<=12;$month++) 
                    {
                        if ($day <= number_days($month))
                        {
                            if ($monthnums[$today_date['month']] == $month && $today_date['day'] == $day && $today_date['year'] == $year)
                            {
                                $calendar .= "<td class=\"calendar_year_view_row_today\" width=\"1%\">$day</td><td ";
                            }
                            else
                            {
                                $calendar .= "<td class=\"calendar_year_view_row\" width=\"1%\">$day</td><td ";
                            }
                            $done = false; 
                            if ($summaries[$month][$day]['number'] == 0)
                            {
                                $calendar .= "class=\"calendar_year_view_row\">";  
                            }
                            else
                            {
                                $width = $summaries[$month][$day]['number'] * 10;
                                $calendar .= "class=\"calendar_year_view_row2\">";
                                for ($k=0;$k<$summaries[$month][$day]['number'];$k++)
                                {
                                    if (isset($summaries[$month][$day]['item'][$k]))
                                    {
                                        $calendar .= "<div style=\"background-color:{$summaries[$month][$day]['item'][$k]['colour']};display:inline;height:100%;padding:2px;padding-top:4px;padding-bottom:4px;\"><span class=\"hintanchor\" style=\"padding:0;margin:0;\" title=\"{$summaries[$month][$day]['item'][$k]['summary']} :: <b>Start Date: </b>{$summaries[$month][$day]['item'][$k]['startdate']}<br /><b>Start Time: </b>{$summaries[$month][$day]['item'][$k]['starttime']}<br /><b>End Date: </b>{$summaries[$month][$day]['item'][$k]['enddate']}<br /><b>End Time: </b>{$summaries[$month][$day]['item'][$k]['endtime']}<br />{$summaries[$month][$day]['item'][$k]['detail']}\">&nbsp;</span></div>";
                                    }
                                    else
                                    {
                                        $calendar .= "<div style=\"background-color:#fff;display:inline;height:100%;padding:2px;padding-top:4px;padding-bottom:4px;\">&nbsp;</div>";
                                    }
                                }
                            }
                            $calendar .= "</td>";
                        }
                        else
                        {
                            $calendar .= "<td>&nbsp;</td><td>&nbsp;</td>";
                        }
                    }   
                    $calendar .= "</tr>";
              }

                          $calendar .= "
             </table>
	     <div class=\"smalltext\">You can see a month view by clicking on the <img src=\"{$templateinfo['imagedir']}view_month.png\" title=\"View Month\" alt=\"View Month\" border=\"0\"/> icon <br />You can view a list of events happening during the month by clicking on the <img src=\"{$templateinfo['imagedir']}list_view.png\" border=\"0\" title=\"List View\" alt=\"List View\"/> icon <br />You can goto the current year by clicking on the <img src=\"{$templateinfo['imagedir']}today.png\" border=\"0\" title=\"Current Year\" alt=\"Current Year\"/> icon</div>";
        }
        elseif ($_GET['view'] == "month" || ($_GET['view'] == "" && $config['defaultview'] == "Month"))
        {       
            $days = number_days($monthnum);

            $monthstart = strtotime("$year-$monthnum-01 00:00:00 ");
            $monthend = strtotime("$year-$monthnum-$days 23:59:59");

            $items = array();
            $maxHeight = 1;
            $dateArray = getdate(mktime(0,0,0,$monthnum,1,$year));
            
            $maxHeight = 0;
            $numberweeks = ceil(($days+$dateArray['wday'])/7);
            
            $calitems = $data->select_fetch_all_rows($numcalendar, "calendar_items", "WHERE ((((startdate >= $monthstart OR enddate >= $monthend) AND startdate <= $monthend) OR (startdate <= $monthstart AND enddate >= $monthstart)) AND allowed=1 AND trash=0) ORDER BY startdate ASC");

            $icalsql = $data->select_query("ical_items");
            $temp2 = $data->select_fetch_one_row("calendar_items", "ORDER BY id DESC", "id");
            $startid = $temp2['id'] + 1;
            while ($temp = $data->fetch_array($icalsql))
            {
                $vcalendar = new vcalendar();
                $vcalendar->parse('http://' . $temp['link']);
                while( $vevent = $vcalendar->getComponent()) 
                {
                    $startdate = $vevent->getProperty('dtstart');
                    $enddate = $vevent->getProperty('dtend');
                    if ($startdate['year'] <= $year && $enddate['year'] >= $year && $startdate['month'] <= $monthnum && $enddate['month'] >= $monthnum)
                    {
                        $item = array();
                        $item['id'] = $startid++;
                        $item['shortsummary'] = censor(trim($vevent->getProperty('summary')));
                        $item['summary'] = censor($item['shortsummary'] . "<span class=\"tinytext\">[{$temp['name']}]</span>");
                        $item['detail'] = censor(trim($vevent->getProperty('description')));
                        $item['startdate'] = mktime($startdate['hour'], $startdate['min'], 0, $startdate['month'], $startdate['day'], $startdate['year']);
                        $item['enddate'] = mktime($enddate['hour'], $enddate['min'], 0, $enddate['month'], $enddate['day'], $enddate['year']);
                        $item['groups'] = $temp['groups'];
                        $item['colour'] = $temp['colour'];
                        $item['ical'] = true;

                        $calitems[] = $item;
                        $numcalendar++;
                    }
                }
            }
           
            foreach ($calitems as $key => $row) {
                $startdate[$key] = $row['startdate'];
            }
            array_multisort($startdate, SORT_ASC, $calitems);

            for ($week=1;$week<=$numberweeks;$week++)
            {
                $weekStartDay = ($week*7-(7-1))-($dateArray['wday']);
                $weekEndDay = ($week*7-(7-7))-($dateArray['wday']);
                
                $weekStartDay = $weekStartDay < 1 ? 1 : $weekStartDay;
                $weekEndDay = $weekEndDay > $days ? $days : $weekEndDay;
                
                $weekStart = strtotime("$year-$monthnum-$weekStartDay 00:00:00");
                $weekEnd = strtotime("$year-$monthnum-$weekEndDay 23:59:59");
                
                $none['itemhere'] = false;
                
                $items[$week]['number'] = 0;
                $items[$week]['start'] =$weekStart;
                $items[$week]['end'] =$weekEnd;
                for($numcal=0;$numcal<=$numcalendar;$numcal++)
                {
                    $temp = $calitems[$numcal];
                    $startdate = $temp['startdate'];
                    $enddate = $temp['enddate'];

                    if ((($startdate >= $weekStart || $enddate >= $weekEnd) && $startdate <= $weekEnd) || ($startdate <= $weekStart && $enddate >= $weekStart))
                    {
                        $items[$week]['number']++;
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
                            $display['summary'] = censor($temp['summary']);
                            $display['shortsummary'] = censor(isset($temp['shortsummary']) ? $temp['shortsummary'] : $temp['summary']);
                            $display['id'] = $temp['id'];
                            $display['detail'] = censor(truncate(strip_tags($temp['detail']), 150));
                            $display['details'] = ($temp['detail'] == ''  || $temp['detail'] == NULL || $temp['ical']) ? 0 : 1;
                            $display['startdate'] = strftime("%Y-%m-%d", $temp['startdate']);
                            $display['enddate'] = strftime("%Y-%m-%d", $temp['enddate']);
                            $display['starttime'] = strftime("%H:%M", $temp['startdate']);
                            $display['endtime'] = strftime("%H:%M", $temp['enddate']);
                            $display['startdatestamp'] = strtotime(strftime("%Y-%m-%d", $temp['startdate']));
                            $display['enddatestamp'] = strtotime(strftime("%Y-%m-%d ", $temp['enddate']));
                            
                            $temp['startdate'] = ($temp['startdate'] < $weekStart) ? $weekStart : $display['startdatestamp'];

                            $temp['enddate'] = ($temp['enddate'] > $weekEnd) ? $weekEnd : $display['enddatestamp'];
                            
                            $display['length'] = floor((($temp['enddate']-$temp['startdate'])/3600)/24)+($temp['enddate'] > $weekEnd ? 0 : 1);
                            $display['placed'] = false;
                            $display['itemhere'] = true;
                            $display['color'] = $temp['colour'] != '' ? $temp['colour'] : $templateinfo['default'];
                            
                            for ($day=1;$day<=7;$day++)
                            {
                                $tempDay = ($week*7-(7-$day))-($dateArray['wday']);
                                $datestamp = strtotime("$year-$monthnum-$tempDay");
                                $items[$week][$day][] = ($datestamp <= $temp['enddate'] && $datestamp >= $temp['startdate']) ? $display : $none;
                            }
                        }
                    }
                }
                $maxHeight = $items[$week]['number'] > $maxHeight ? $items[$week]['number'] : $maxHeight;
            }
            
            $previousYear = $year - 1;
            $nextYear = $year + 1;
            $previousMonth = $monthnums[$month] - 1;
            $nextMonth = $monthnums[$month] + 1;
            $calendar = "<form name=\"form1\" method=\"post\" action=\"\">
                            <div align=\"center\">
                    <a href=\"index.php?page=calender&amp;view=month&amp;month={$monthnums[$today_date['month']]}&amp;year={$today_date['year']}&amp;menuid={$menuid}\"><img src=\"{$templateinfo['imagedir']}today.png\" border=\"0\" title=\"Today\" alt=\"Today\"/></a>&nbsp;
                    <a href=\"index.php?page=calender&amp;view=year&amp;year=$year&amp;menuid={$menuid}\"><img src=\"{$templateinfo['imagedir']}calendar.png\" border=\"0\" title=\"Year View\" alt=\"Year View\"/></a>&nbsp;
                    <a href=\"index.php?page=calender&amp;view=list&amp;month={$monthnum}&amp;year=$year&amp;menuid={$menuid}\"><img src=\"{$templateinfo['imagedir']}list_view.png\" border=\"0\" title=\"List View\" alt=\"List View\"/></a>        
                </div>
            <div align=\"center\">
                    <a href=\"index.php?page=calender&amp;view=month&amp;month={$monthnums[$month]}&amp;year=$previousYear&amp;menuid={$menuid}\"><img src=\"{$templateinfo['imagedir']}first.png\" title=\"Previous Year\" alt=\"Previous Year\" border=\"0\"/></a>&nbsp;
                    <a href=\"index.php?page=calender&amp;view=month&amp;month={$previousMonth}&amp;year=$year&amp;menuid={$menuid}\"><img src=\"{$templateinfo['imagedir']}prev.png\" title=\"Previous Month\" alt=\"Previous Month\" border=\"0\"/></a>&nbsp;

                      <select name=\"month\" onchange=\"form1.submit()\" class=\"inputbox\">";
                    for ($i=1;$i<=12;$i++) 
                    {
                        $calendar .= "<option value=\"{$monthName[$i]}\"";
                        if ($month == $monthName[$i]) 
                        {
                            $calendar .= "selected=\"selected\"";
                        }
                        $calendar .= ">{$monthName[$i]}</option>";
                    }
                    
                $calendar .= "  </select>&nbsp;
                   <select name=\"year\" onchange=\"form1.submit()\" class=\"inputbox\">";
                    for ($i=$today_date['year']-5;$i<=$today_date['year']+5;$i++)  
                    {
                        $calendar .= "<option value=\"$i\"";
                        if ($year == $i) 
                        {
                            $calendar .= "selected=\"selected\"";
                        }
                        $calendar .= ">$i</option>";
                    }
                    
                $calendar .= "  </select>&nbsp;

                    <a href=\"index.php?page=calender&amp;view=month&amp;month={$nextMonth}&amp;year=$year&amp;menuid={$menuid}\"><img src=\"{$templateinfo['imagedir']}next.png\" title=\"Next Month\" alt=\"Next Month\" border=\"0\"/></a>&nbsp;
                    <a href=\"index.php?page=calender&amp;view=month&amp;month={$monthnums[$month]}&amp;year=$nextYear&amp;menuid={$menuid}\"><img src=\"{$templateinfo['imagedir']}last.png\" title=\"Next Year\" alt=\"Next Year\" border=\"0\"/></a>
                  </div>
            </form>
            <table width=\"100%\" class=\"table\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
            <tr>
            <th colspan=\"7\" class=\"calendar_bighead\">$month - $year</th>
            </tr>
              <tr>
                <th width=\"14%\" class=\"calendar_head\">Sunday</th>
                <th width=\"14%\" class=\"calendar_head\">Monday</th>
                <th width=\"14%\" class=\"calendar_head\">Tuesday</th>
                <th width=\"14%\" class=\"calendar_head\">Wednesday</th>
                <th width=\"14%\" class=\"calendar_head\">Thursday</th>
                <th width=\"14%\" class=\"calendar_head\">Friday</th>
                <th width=\"14%\" class=\"calendar_head\">Saturday</th>
              </tr>";
             
            $dateArray = getdate(mktime(0,0,0,$monthnum,1,$year));
            $doneItems = 0;
            $startNum = $dateArray['wday'] + 1;
            for($week=1;$week<=ceil(($days+$dateArray['wday'])/7);$week++)
            {

                $calendar .= "<tr style=\"height:1em;\">";
                for ($j=1;$j<=7;$j++)
                {
                    $tempDay = ($week*7-(7-$j))-($dateArray['wday']);
                    if ((($j >= $startNum && $week == 1) || ($week > 1)) && $tempDay <= $days)
                    {
                        if ($monthnums[$today_date['month']] == $monthnum && $today_date['day'] == $tempDay && $today_date['year'] == $year)
                        {
                            $calendar .= "<td class=\"calendar_today\">$tempDay</td>";
                        }
                        else
                        {
                            $calendar .= "<td class=\"calendar_day\">$tempDay</td>";
                        }
                    }
                    else
                    {
                        $calendar .= "<td class=\"calendar_day_invalid\">&nbsp;</td>";
                    }
                }
                $calendar .= "</tr>";
                
                $numberRows = $items[$week]['number'];
                
                for ($k=0;$k<$numberRows;$k++)
                {
                    $calendar .= "<tr style=\"height:1.4em;\">";
                    for ($j=1;$j<=7;$j++)
                    {
                        $tempDay = ($week*7-(7-$j))-($dateArray['wday']);
                        
                        if ((($j >= $startNum && $week == 1) || ($week > 1)) && $tempDay <= $days)
                        {
                            if ($items[$week][$j][$k]['itemhere'] && !$items[$week][$j][$k]['placed'])
                            {
                                if ($tempDay + $items[$week][$j][$k]['length'] > $days)
                                {
                                    $items[$week][$j][$k]['length'] = ($days - $tempDay)+1;
                                }
                                $colour = rgb2hex2rgb($items[$week][$j][$k]['color']);
                                $colour1['b'] = $colour1['g'] = $colour1['r'] = ($colour['r'] + $colour['g'] + $colour['b'])/3 > 100 ? 0 : 255;
                                $colour = rgb2hex2rgb($colour1['r'] . '.' . $colour1['g'] . '.' . $colour1['b']);
                                $calendar .= "<td colspan=\"{$items[$week][$j][$k]['length']}\" class=\"calendar_item\" style=\"vertical-align:middle;\"><div class=\"calendar_item_div\" style=\"background-color:{$items[$week][$j][$k]['color']};color:{$colour};border-color:{$colour}\"><span style=\"color:{$colour};\" class=\"hintanchor\" title=\"{$items[$week][$j][$k]['shortsummary']} :: <b>Start Date: </b>{$items[$week][$j][$k]['startdate']}<br /><b>Start Time: </b>{$items[$week][$j][$k]['starttime']}<br /><b>End Date: </b>{$items[$week][$j][$k]['enddate']}<br /><b>End Time: </b>{$items[$week][$j][$k]['endtime']}<br />{$items[$week][$j][$k]['detail']}\">{$items[$week][$j][$k]['summary']}</span>". ($items[$week][$j][$k]['details'] == 1 ? "<a href=\"index.php?page=calender&amp;item={$items[$week][$j][$k]['id']}&amp;menuid={$menuid}\"><img src=\"{$templateinfo['imagedir']}page.gif\" alt=\"Read more\" title=\"Read more\" border=\"0\"/></a>" : '') ."</div></td>";
                                for ($l=$j;$l<=7;$l++)
                                {
                                    if ($items[$week][$l][$k]['itemhere'])
                                    {
                                        $items[$week][$l][$k]['placed'] = true;
                                    }
                                }
                            }
                            elseif (!$items[$week][$j][$k]['itemhere'])
                            {
                                $calendar .= "<td class=\"calendar_item\">&nbsp;</td>";
                            }
                        }
                        else
                        {
                            $calendar .= "<td class=\"calendar_day_invalid\">&nbsp;</td>";
                        }
                    }
                    $calendar .= "</tr>";
                }
                
                $bufferHeight = ($maxHeight - $numberRows)+2;
                $calendar .= "<tr style=\"height:{$bufferHeight}em;\">";
                for ($j=1;$j<=7;$j++)
                {
                    $tempDay = ($week*7-(7-$j))-($dateArray['wday']);
                    if ((($j >= $startNum && $week == 1) || ($week > 1)) && $tempDay <= $days)
                    {
                        $calendar .= "<td class=\"calendar_spacer\" >&nbsp;</td>";
                    }
                    else
                    {
                        $calendar .= "<td  class=\"calendar_spacer_invalid\">&nbsp;</td>";
                    }
                }
                $calendar .= "</tr>";
            }
		    
            $calendar .= "</table>	     
	    <div class=\"smalltext\">You can see a year view by clicking on the <img src=\"{$templateinfo['imagedir']}calendar.png\" border=\"0\" title=\"Year View\" alt=\"Year View\"/> icon <br />You can view a list of events happening during the month by clicking on the <img src=\"{$templateinfo['imagedir']}list_view.png\" border=\"0\" title=\"List View\" alt=\"List View\"/> icon <br />You can goto the current date by clicking on the <img src=\"{$templateinfo['imagedir']}today.png\" border=\"0\" title=\"Today\" alt=\"Today\"/> icon</div>";

            
        }
        elseif ($_GET['view'] == "list" || ($_GET['view'] == "" && $config['defaultview'] == "List"))
        {
	
	            $previousYear = $year - 1;
            $nextYear = $year + 1;
            $previousMonth = $monthnums[$month] - 1;
            $nextMonth = $monthnums[$month] + 1;
	    
            $calendar = "<form name=\"form1\" method=\"post\" action=\"\">
                            <div align=\"center\">
                    <a href=\"index.php?page=calender&amp;view=list&amp;month={$monthnums[$today_date['month']]}&amp;year={$today_date['year']}&amp;menuid={$menuid}\"><img src=\"{$templateinfo['imagedir']}today.png\" border=\"0\" title=\"Today\" alt=\"Today\"/></a>&nbsp;
                    <a href=\"index.php?page=calender&amp;view=month&amp;month={$monthnum}&amp;year=$year&amp;menuid={$menuid}\"><img src=\"{$templateinfo['imagedir']}view_month.png\" border=\"0\" title=\"Month View\" alt=\"Month View\"/></a>&nbsp; 
                    <a href=\"index.php?page=calender&amp;year=$year&amp;view=year&amp;menuid={$menuid}\"><img src=\"{$templateinfo['imagedir']}calendar.png\" border=\"0\" title=\"Year View\" alt=\"Year View\"/></a>                   
                </div>
            <div align=\"center\">
                    <a href=\"index.php?page=calender&amp;view=list&amp;month={$monthnums[$month]}&amp;year=$previousYear&amp;menuid={$menuid}\"><img src=\"{$templateinfo['imagedir']}first.png\" title=\"Previous Year\" alt=\"Previous Year\" border=\"0\"/></a>&nbsp;
                    <a href=\"index.php?page=calender&amp;view=list&amp;month={$previousMonth}&amp;year=$year&amp;menuid={$menuid}\"><img src=\"{$templateinfo['imagedir']}prev.png\" title=\"Previous Month\" alt=\"Previous Month\" border=\"0\"/></a>&nbsp;

                      <select name=\"month\" onchange=\"form1.submit()\" class=\"inputbox\">";
                    for ($i=1;$i<=12;$i++) 
                    {
                        $calendar .= "<option value=\"{$monthName[$i]}\"";
                        if ($month == $monthName[$i]) 
                        {
                            $calendar .= "selected=\"selected\"";
                        }
                        $calendar .= ">{$monthName[$i]}</option>";
                    }
                    
                $calendar .= "  </select>&nbsp;
                   <select name=\"year\" onchange=\"form1.submit()\" class=\"inputbox\">";
                    for ($i=$today_date['year']-5;$i<=$today_date['year']+5;$i++) 
                    {
                        $calendar .= "<option value=\"$i\"";
                        if ($year == $i) 
                        {
                            $calendar .= "selected=\"selected\"";
                        }
                        $calendar .= ">$i</option>";
                    }
                    
                $calendar .= "  </select>&nbsp;

                    <a href=\"index.php?page=calender&amp;view=list&amp;month={$nextMonth}&amp;year=$year&amp;menuid={$menuid}\"><img src=\"{$templateinfo['imagedir']}next.png\" title=\"Next Month\" alt=\"Next Month\" border=\"0\"/></a>&nbsp;
                    <a href=\"index.php?page=calender&amp;view=list&amp;month={$monthnums[$month]}&amp;year=$nextYear&amp;menuid={$menuid}\"><img src=\"{$templateinfo['imagedir']}last.png\" title=\"Next Year\" alt=\"Next Year\" border=\"0\"/></a>
                  </div>
            </form>";
            
            $days = number_days($monthnum);
            $monthStart = strtotime("$year-$monthnum-01 00:00:00");
            $monthEnd = strtotime("$year-$monthnum-$days 23:59:59");
            $numcalendar = 0;
            $calitems = $data->select_fetch_all_rows($numcalendar, "calendar_items", "WHERE ((((startdate >= $monthStart OR enddate >= $monthEnd) AND startdate <= $monthEnd) OR (startdate <= $monthStart AND enddate >= $monthStart)) AND allowed=1 AND trash=0) ORDER BY startdate ASC");

            $icalsql = $data->select_query("ical_items");
            $temp2 = $data->select_fetch_one_row("calendar_items", "ORDER BY id DESC", "id");
            $startid = $temp2['id'] + 1;
            while ($temp = $data->fetch_array($icalsql))
            {
                $vcalendar = new vcalendar();
                $vcalendar->parse('http://' . $temp['link']);
                while( $vevent = $vcalendar->getComponent()) 
                {
                    $startdate = $vevent->getProperty('dtstart');
                    $enddate = $vevent->getProperty('dtend');

                    if ($startdate['year'] <= $year && $enddate['year'] >= $year && $startdate['month'] <= $monthnum && $enddate['month'] >= $monthnum)
                    {
                        $item = array();
                        $item['id'] = $startid++;
                        $item['summary'] = trim($vevent->getProperty('summary')) . " [{$temp['name']}]";
                        $item['detail'] = trim($vevent->getProperty('description'));
                        $item['startdate'] = mktime($startdate['hour'], $startdate['min'], 0, $startdate['month'], $startdate['day'], $startdate['year']);
                        $item['enddate'] = mktime($enddate['hour'], $enddate['min'], 0, $enddate['month'], $enddate['day'], $enddate['year']);
                        $item['groups'] = $temp['groups'];
                        $item['colour'] = $temp['colour'];
                        $item['ical'] = true;
                        $calitems[] = $item;
                        $numcalendar++;
                    }
                }
            }
            
            foreach ($calitems as $key => $row) {
                $startdate[$key] = $row['startdate'];
            }
            array_multisort($startdate, SORT_ASC, $calitems);

            if ($numcalendar > 0)
            {
                for ($i=0;$i<$numcalendar;$i++)
                {
                    $temp = $calitems[$i];
                    $height = $temp['detail'] != NULL ? 'auto' : 50;
                    $temp['sdate'] = strftime("%Y-%m-%d", $temp['startdate']);
                    $temp['edate'] = strftime("%Y-%m-%d", $temp['enddate']);
                    $temp['stime'] = strftime("%H:%M", $temp['startdate']);
                    $temp['etime'] = strftime("%H:%M", $temp['enddate']);
                    $calendar .= "
    <div style=\"border:1px solid #000;margin:2px;padding:10px;clear:both;height:{$height}px\">
    <div style=\"clear:both\"><h3 style=\"text-align:left;margin:0px;padding:0px;\">" . censor($temp['summary']) ."</h3>
    <span class=\"smalltext\"><b>Start Date: </b>{$temp['sdate']} | <b>Start Time: </b>{$temp['stime']}</span><br />
    <span class=\"smalltext\"><b>End Date: </b>{$temp['edate']} | <b>End Time: </b>{$temp['etime']}</span>
    </div>";
                    if ($temp['detail'] != NULL)
                    {
                        $calendar .= "<div>".censor($temp['detail'])."</div>";  
                    }
                    $calendar .= "</div>";                
                }
            }
            else
            {
                $calendar .= "There are no events happening during $month.";
            }
	    $calendar .= "<div class=\"smalltext\">You can see a year view by clicking on the <img src=\"{$templateinfo['imagedir']}calendar.png\" border=\"0\" title=\"Year View\" alt=\"Year View\"/> icon <br />You can see a month view by clicking on the <img src=\"{$templateinfo['imagedir']}view_month.png\" title=\"View Month\" alt=\"View Month\" border=\"0\"/> icon <br />You can goto the current date by clicking on the <img src=\"{$templateinfo['imagedir']}today.png\" border=\"0\" title=\"Today\" alt=\"Today\"/> icon</div>";
        }
       
       
        $add = (get_auth('addevent') == 1) ? true : false;
        $addlink = "index.php?page=addevent&amp;menuid=$menuid";
        
        $rssuname = safesql(md5($check['uname']), "text");
        if ($data->num_rows($data->select_query("rssfeeds", "WHERE itemid=1 AND type=3 AND uname=$rssuname", "id")))
        {
            $rss = 1;
        }
        else
        {
            $rss = 0;
        } 
        $tpl->assign("calendar", $calendar);
        $tpl->assign("rss", $rss);
        $show_detail = false;
    }
}
else
{
    $calendar = new vcalendar();;
    $calsql = $data->select_query("calendar_items", "WHERE allowed = 1 AND trash=0");     
    while($temp = $data->fetch_array($calsql))
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
            $e = new vevent();
            
            $e->setProperty( 'categories', $config['troopname'] . " Calendar" );
            
            $date = getdate($temp['startdate']);                
            $e->setProperty( 'dtstart', $date['year'], $date['mon'], $date['mday'], $date['hours'], $date['minutes'], 00 );
            
            $date = getdate($temp['enddate']);
            $e->setProperty( 'dtend', $date['year'], $date['mon'], $date['mday'], $date['hours'], $date['minutes'], 00 );

            $e->setProperty( 'summary', censor($temp['summary']));

            $e->setProperty( 'description', $temp['detail'] != NULL ? truncate(strip_tags(censor($temp['detail']))) : "No details" );

            $calendar->addComponent( $e ); 
        }
    } 
    header('Content-type: text/calendar');

    header('Content-Disposition: attachment; filename="calendar.ics"');  
    
    echo $calendar->createCalendar();
    exit;
}
$tpl->assign("show_detail", $show_detail);
$pagename = "Calender";
$dbpage = true;
?>