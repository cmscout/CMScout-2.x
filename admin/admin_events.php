<?php
/**************************************************************************
    FILENAME        :   admin_events.php
    PURPOSE OF FILE :   Manages events on the calender
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
if( !empty($getmodules) )
{
	$module['Content Management']['Events Manager'] = "events";
    $moduledetails[$modulenumbers]['name'] = "Events Manager";
    $moduledetails[$modulenumbers]['details'] = "Management of calender events";
    $moduledetails[$modulenumbers]['access'] = "Allowed to access the Events Manager";
    $moduledetails[$modulenumbers]['add'] = "Allowed to add an event.";
    $moduledetails[$modulenumbers]['edit'] = "Allowed to edit existing events";
    $moduledetails[$modulenumbers]['delete'] = "Allowed to delete events";
    $moduledetails[$modulenumbers]['publish'] = "Allowed to publish and unpublish events";
    $moduledetails[$modulenumbers]['limit'] = "notused";
    $moduledetails[$modulenumbers]['id'] = "events";

	return;
}
else
{
    function get_end_pos()
    {
        global $data;
        
        $pos = 1;
        do 
        {
            $temp = $data->select_query("profilefields", "WHERE pos = '$pos' AND place=2");
            if ($data->num_rows($temp) != 0) 
            {
                $pos++;
            }
        } while ($data->num_rows($temp) != 0); 
        return $pos;
    }    
    
    $action = $_GET['action'];
    if (!isset($action)) {$action = "";}
     
    $id = safesql($_GET['id'], "int");
     
    if (($action == "edit" && pageauth("events", "edit") == 1) || ($action == "new" && pageauth("events", "add") == 1)) 
    {
        if ($action == "edit")
        {
            $calsql = $data->select_query("calendar_items", "WHERE id = $id");
            $items = $data->fetch_array($calsql);
            $items['groups'] = unserialize($items['groups']);
            $items['patrols'] = unserialize($items['patrols']);
        }
        if (pageauth("events", "limit") == 1)
        {
            $groups = group_sql_list_id("id", "OR"); 
            $teams = $data->select_fetch_all_rows($numteams, "groups", "WHERE ($groups) ORDER BY teamname ASC", "id, teamname");
            $patrols = $data->select_fetch_all_rows($numpatrols, "groups", "WHERE ($groups) AND ispatrol = 1 ORDER BY teamname ASC", "id, teamname");
            $groups = group_sql_list_id("patrol", "OR"); 
            $members = $data->select_fetch_all_rows($nummembers, "members", "WHERE ($groups) ORDER BY lastName,firstName ASC", "id, lastName, firstName");
        }
        else
        {
            $teams = $data->select_fetch_all_rows($numteams, "groups", "ORDER BY teamname ASC", "id, teamname");
            $patrols = $data->select_fetch_all_rows($numpatrols, "groups", "WHERE ispatrol = 1 ORDER BY teamname ASC", "id, teamname");
            $members = $data->select_fetch_all_rows($nummembers, "members", "ORDER BY lastName,firstName ASC", "id, lastName, firstName");
        }

        $colour = $action == "edit" ? rgb2hex2rgb($items['colour']) : array("red"=>255, "green"=>255, "blue"=>255);
        $startdate = $action == "edit" ? strftime("%Y/%m/%d", $items['startdate']): '';
        $enddate = $action == "edit" ? strftime("%Y/%m/%d", $items['enddate']) : '';
        $script .= "{literal}function makeTwoChars(inp) {
                    return String(inp).length < 2 ? \"0\" + inp : inp;
            }

            function initialiseInputs() {
                    // Clear any old values from the inputs (that might be cachedate by the browser after a page reload)
                    document.getElementById(\"sdate\").value = \"$startdate\";
                    document.getElementById(\"edate\").value = \"$enddate\";
                    // Add the onchange event handler to the start date input
                    document.getElementById(\"sdate\").onchange = setReservationDates;
                    
            }
            function setReservationDates(e) {
                    // Check the associatedate datePicker object is available (be safe)
                    if(!(\"sdate\" in datePickerController.datePickers)) {
                            return;
                    }
                    
                    // Check the value of the input is a date of the correct format
                    var dt = datePickerController.dateFormat(this.value, datePickerController.datePickers[\"sdate\"].format.charAt(0) == \"m\");
                    
                    // If the input's value cannot be parsedate as a valid date then return
                    if(dt == 0) return;

                    // Grab the value set within the endDate input and parse it using the dateFormat method
                    // N.B: The second parameter to the dateFormat function, if TRUE, tells the function to favour the m-d-y date format
                    var edatev = datePickerController.dateFormat(document.getElementById(\"edate\").value, datePickerController.datePickers[\"edate\"].format.charAt(0) == \"m\");

                    // Grab the end date datePicker Objects
                    var edate = datePickerController.datePickers[\"edate\"];

                    edate.setRangeLow( dt );
                    
                    // If theres a value already present within the end date input and it's smaller than the start date
                    // then clear the end date value
                    if(edatev < dt) {
                            document.getElementById(\"edate\").value = \"\";
                    }
            }

            datePickerController.addEvent(window, 'load', initialiseInputs);

            {/literal}";
            
$onDomReady .= "var r = new MooRainbow('colourSelector', {
                    'startColor': [{$colour['red']}, {$colour['green']}, {$colour['blue']}],
                    'onChange': function(color) {
                        $('colour').value = color.hex;
                        $('colour').style.backgroundColor = color.hex;
                    }
                });";
        $tpl->assign('teams',$teams);
        $tpl->assign('numteams', $numteams); 
        $tpl->assign('patrols',$patrols);
        $tpl->assign('numpatrols', $numpatrols); 
        $tpl->assign('members',$members);
        $tpl->assign('nummembers', $nummembers); 
        $submit = $_POST['Submit'];
        if ($submit == "Submit")
        {
            $summary = safesql($_POST['summary'], "text");
            $startdate = strtotime($_POST['sdate']) + $_POST['stime']['Time_Hour']*60*60 + $_POST['stime']['Time_Minute']*60;
            $enddate = strtotime($_POST['edate']) + $_POST['etime']['Time_Hour']*60*60 + $_POST['etime']['Time_Minute']*60;
		
            $detail = safesql($_POST['editor'], "text", false);
            $colour = safesql($_POST['colour'], "text");
            $groupallowed = safesql(serialize($_POST['groups']), "text");
            
            $signup = safesql($_POST['signup'], "int");
            $signupusers = safesql($_POST['signupusers'], "int");
            $patrols = $signupusers != 3 ? safesql(serialize($_POST['patrols']), "text") : safesql(serialize($_POST['invites']), "text");
            
            if ($action == "edit")
            {
                $sql = $data->update_query("calendar_items", "summary = $summary, startdate = $startdate, enddate = $enddate, detail = $detail, `groups` = $groupallowed, colour = $colour, signup=$signup, signupusers=$signupusers, patrols=$patrols", "id = $id");
                show_admin_message("Event updated", "$pagename");
            }
            elseif ($action == "new")
            {
                $sql = $data->insert_query("calendar_items", "'', $summary, $startdate, $enddate, $detail, 1, $groupallowed, $timestamp, $colour,$signup, $signupusers,$patrols, 0");
                show_admin_message("Event added", "$pagename");
            }
        
            $action = '';
        }
    } 
    elseif (($action == "editical" && pageauth("events", "edit") == 1) || ($action == "newical" && pageauth("events", "add") == 1)) 
    {
        if ($action == "editical")
        {
            $calsql = $data->select_query("ical_items", "WHERE id = $id");
            $items = $data->fetch_array($calsql);
            $items['groups'] = unserialize($items['groups']);
        }
        if (pageauth("events", "limit") == 1)
        {
            $groups = group_sql_list_id("id", "OR"); 
            $teams = $data->select_fetch_all_rows($numteams, "groups", "WHERE ($groups) ORDER BY teamname ASC", "id, teamname");
        }
        else
        {
            $teams = $data->select_fetch_all_rows($numteams, "groups", "ORDER BY teamname ASC", "id, teamname");
        }
        $colour = $action == "editical" ? rgb2hex2rgb($items['colour']) : array("red"=>255, "green"=>255, "blue"=>255);
            
$onDomReady .= "var r = new MooRainbow('colourSelector', {
                    'startColor': [{$colour['red']}, {$colour['green']}, {$colour['blue']}],
                    'onChange': function(color) {
                        $('colour').value = color.hex;
                        $('colour').style.backgroundColor = color.hex;
                    }
                });";
        $tpl->assign('teams',$teams);
        $tpl->assign('numteams', $numteams); 
        $submit = $_POST['Submit'];
        if ($submit == "Submit")
        {
            $name = safesql($_POST['name'], "text");
            $link = safesql($_POST['link'], "text");
            $colour = safesql($_POST['colour'], "text");
            $groupallowed = safesql(serialize($_POST['groups']), "text");
            
            if ($action == "editical")
            {
                $sql = $data->update_query("ical_items", "name = $name, link = $link, `groups` = $groupallowed, colour = $colour", "id = $id");
                show_admin_message("iCalendar link updated", "$pagename&activetab=ical");
            }
            elseif ($action == "newical")
            {
                $sql = $data->insert_query("ical_items", "'', $name, $link, $groupallowed, $colour");
                show_admin_message("iCalendar link added", "$pagename&activetab=ical");
            }
        
            $action = '';
        }
    } 
    elseif ($action == "delete" && pageauth("events", "delete") == 1) 
    {
        $sql = $data->update_query("calendar_items", "trash=1", "id = $id");
        if ($sql) 
        {
            show_admin_message("Event deleted", "$pagename");
        }
    }
    elseif ($action == 'publish' && pageauth("events", "publish") == 1) 
    {
        $sqlq = $data->update_query("calendar_items", "allowed = 1", "id = $id", "Calendar Admin", "Published $id");
        if ($data->num_rows($data->select_query("review", "WHERE item_id=$id AND type='event'")))
        {        
            $item = $data->select_fetch_one_row("calendar_items", "WHERE id=$id");
            email('newitem', array("event", $item));
            $data->delete_query("review", "item_id=$id AND type='event'");
        }
        show_admin_message("Event published", "$pagename");
    }
    elseif ($action == 'unpublish' && pageauth("events", "publish") == 1) 
    {
        $sqlq = $data->update_query("calendar_items", "allowed = 0", "id=$id", "Calendar Admin", "Unpublished $id");
        show_admin_message("Event unpublished", "$pagename");
    }
    elseif ($action == "signups")
    {
        $eventinfo = $data->select_fetch_one_row("calendar_items",  "WHERE id=$id");
        
        if ($eventinfo['signupusers'] == 0 || $eventinfo['signupusers'] == 3)
        {
            $sql = $data->select_query("members", "ORDER BY lastName, firstName ASC");
        }
        elseif ($eventinfo['signupusers'] == 1)
        {
            $sql = $data->select_query("members", "WHERE type = 0 ORDER BY lastName, firstName ASC");
        }
        elseif ($eventinfo['signupusers'] == 2)
        {
            $sql = $data->select_query("members", "WHERE type = 1 OR type = 2 ORDER BY lastName, firstName ASC");
        }

        $nummembers = $data->num_rows($sql);
        $members = array();
        while ($temp = $data->fetch_array($sql))
        {
            $attendie = $data->select_fetch_one_row("attendies", "WHERE uid={$temp['id']} AND eid = $id");
            if ($attendie)
            {
                $temp['attend'] = 1;
                $temp['attendoptions'] = unserialize($attendie['options']);
            }
            $members[] = $temp;
        }
        $tpl->assign("members", $members);
        $tpl->assign("nummembers", $nummembers);
        
        $sql = $data->select_query("profilefields", "WHERE place=2 AND eventid=$id ORDER BY query ASC");
        
        $numfields = $data->num_rows($sql);
        $fields = array();
        while ($temp =  $data->fetch_array($sql))
        {
            $temp['options'] = unserialize($temp['options']);
            $fields[] = $temp;
        }
        $tpl->assign("numfields", $numfields);
        $tpl->assign("fields", $fields);
        
        $tpl->assign("eventid", $id);
        $tpl->assign("eventinfo", $eventinfo);

        $sql = $data->select_query("download_cats", "", "id, name");
        $downloads = array();
        $numcategories = $data->num_rows($sql);
        while ($temp = $data->fetch_array($sql))
        {
            $sql1 = $data->select_query("downloads", "WHERE cat={$temp['id']}", "id, name");
            $downloadtemp = array();
            $tempnumber = 0;
            while ($temp2 = $data->fetch_array($sql1))
            {
                if ($data->num_rows($data->select_query("calendar_downloads", "WHERE eid=$id AND did = {$temp2['id']}")) == 0)
                {
                    $downloadtemp[] = $temp2;
                    $tempnumber++;
                }
            }
            $temp['number'] = $tempnumber;
            $temp['downloads'] = $downloadtemp;
            $downloads[] = $temp;
        }
        
        $tpl->assign("numcategories", $numcategories);
        $tpl->assign("downloads", $downloads);
        
        $sql = $data->select_query("calendar_downloads", "WHERE eid=$id");
        $event_downloads = array();
        $numeventdownloads = $data->num_rows($sql);
        while ($temp = $data->fetch_array($sql))
        {
            $temp1 = $data->select_fetch_one_row("downloads", "WHERE id={$temp['did']}", "name");
            $temp['name'] = $temp1['name'];
            $event_downloads[] = $temp;
        }

        $tpl->assign("numeventdownloads", $numeventdownloads);
        $tpl->assign("event_downloads", $event_downloads);
        $tpl->assign("download_editallowed", pageauth("downloads", "edit"));

        if ($_POST['Submit'] == "Update")
        {
            $attendies = $_POST['attend'];
            $options = $_POST['options'];
            $data->delete_query("attendies", "eid=$id");
            foreach($attendies as $uid => $attend)
            {
                if ($attend == 1)
                {
                    $useroption = safesql(serialize($options[$uid]), "text");
                    if ($data->num_rows($data->select_query("attendies", "WHERE uid=$uid AND eid = $id")) == 0)
                    {
                        $data->insert_query("attendies", "'', $uid, $id, $useroption");
                    }
                    else
                    {
                        $data->update_query("attendies", "options = $useroption", "uid=$uid AND eid=$id");
                    }
                }
            }
            show_admin_message("Attendies Updated", "{$pagename}&action=signups&id=$id&activetab=events");
        }
    }
    elseif ($action == "adddownload")
    {
        $download = safesql($_POST['download'], "text");
        $permissions = safesql($_POST['permissions'], "text");
        
        if ($download != 0)
        {
            $data->insert_query("calendar_downloads", "'', $id, $download, $permissions");
            show_admin_message("Download Added", "{$pagename}&action=signups&id=$id&activetab=downloads");
        }
        else
        {
            show_admin_message("Please select a download", "{$pagename}&action=signups&id=$id&activetab=downloads");
        }
    }
    elseif ($action == "newfield" || $action == "editfield")
    {
        $eventid = safesql($_GET['event'], "int");
        if ($action == "editfield")
        {
            $item = $data->select_fetch_one_row("profilefields", "WHERE id=$id");
        
            $item['options'] = unserialize($item['options']);

            $tpl->assign("item", $item);
        }
        if ($_POST['Submit'] == "Submit")
        {       
            $name = safesql(str_replace(" ", "", $_POST['name']), "text");
            if (check_duplicate("profilefields", "name", $name, $id))
            {
                show_admin_message("A field with that name already exists");
            }
            $query = safesql($_POST['query'], "text");
            $hint = safesql($_POST['hint'], "text");
            $required = safesql($_POST['required'], "int");
            $register = safesql($_POST['register'], "int");
            $type = safesql($_POST['type'], "int");
            
            switch ($_POST['type'])
            {
                case 1:
                        $options = $_POST['options'];
                        break;
                case 2:
                        $options = $_POST['options'];
                        break;
                case 3: case 4: case 5:
                        $options = array();
                        $options[0] = $_POST['numoptions'];
                        for ($i=1;$i<=$_POST['numoptions'];$i++)
                        {
                            $temp = $_POST['option' . $i];
                            if ($temp != '')
                            {
                                $options[] = urlencode(stripslashes($temp));
                            }
                            else
                            {
                                --$options[0];
                            }
                        }
                        break;
                case 6:
                    $options = "''";
            }
            
            $pos = 0;
            $options = safesql(serialize($options), "text");
            if ($action == "newfield")
            {
                $data->insert_query("profilefields", "'', $name, $query, $options, $hint, $type, $required, $register, 0, $pos, 2, $eventid");
                show_admin_message("Field Added", "{$pagename}&action=signups&id=$eventid&activetab=ical");
            }
            elseif ($action == "editfield")
            {
                $data->update_query("profilefields", "query=$query, options=$options, hint=$hint, type=$type, required=$required, register=$register", "id=$id");
                show_admin_message("Field Updated", "{$pagename}&action=signups&id=$eventid&activetab=ical");
            }
        }
    }
    elseif ($action == "deletefield")
    {
        $eventid = safesql($_GET['event'], "int");
        $data->delete_query("profilefields", "id=$id");
        show_admin_message("Field Deleted", "{$pagename}&action=signups&id=$eventid&activetab=ical");
    }
    elseif ($action == "deletedownload")
    {
        $eventid = safesql($_GET['event'], "int");
        $data->delete_query("calendar_downloads", "id=$id");
        show_admin_message("Download Removed", "{$pagename}&action=signups&id=$eventid&activetab=ical");
    }
    elseif ($action == "newattend")
    {
        $sql = $data->select_query("users");
        $users = array();
        while ($temp = $data->fetch_array($sql))
        {
            $sql2 = $data->select_query("auth", "WHERE authname='{$temp['id']}' AND type=1 AND id != $safe_id");
            if ($data->num_rows($sql2) == 0)
            {
                $users[] = $temp;
            }
        }
    }
    
    
    if (!$action) 
    {
        $calsql = $data->select_query("calendar_items", "WHERE trash=0 ORDER BY startdate ASC");
        $numitems = $data->num_rows($calsql);
        $items = array();
        while ($items[] = $data->fetch_array($calsql));
        
        $calsql = $data->select_query("ical_items", "ORDER BY name ASC");
        $numical = $data->num_rows($calsql);
        $ical = array();
        while ($ical[] = $data->fetch_array($calsql));
    }
    
    $date = getdate();
    $tpl->assign("year", $date['year']);
    $tpl->assign('events', $items);
    $tpl->assign('numevents', $numitems);
    $tpl->assign('ical', $ical);
    $tpl->assign('numical', $numical);
    $tpl->assign("editor", true);
    $tpl->assign('action', $action);
    $filetouse = "admin_events.tpl";
}
?>