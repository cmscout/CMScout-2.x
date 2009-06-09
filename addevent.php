<?php
/**************************************************************************
    FILENAME        :   addevent.php
    PURPOSE OF FILE :   Add a users event to the database
    LAST UPDATED    :   25 September 2006
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
$location = "Add Event";
/********************************************Check if user is allowed*****************************************/
if (isset($check["uname"])) {
 $tpl->assign('name',$check["uname"]);
}

$message = "";
$uname = $check["uname"];
if (!$error) 
{
	$submit = $_POST['Submit'];
	if ($submit == "Submit") 
    {        
        if (validate($_POST['validation']))
        {
            $sdatetime = strtotime($_POST['sdate']) + $_POST['stime']['Hour']*60*60 + $_POST['stime']['Minute']*60;
            $edatetime = strtotime($_POST['edate']) + $_POST['etime']['Hour']*60*60 + $_POST['etime']['Minute']*60;
            
            $insert = sprintf("NULL, %s, %s, %s, %s", 
                                safesql($_POST['summary'], "text"),
                                safesql($sdatetime, "int"),
                                safesql($edatetime, "int"),
                                safesql($_POST['story'], "text", false));
                                
            $colour = safesql($_POST['colour'], "text");
            $colour = $colour == 'NULL' ? safesql(sprintf("#%X", 0xaaaaaa + dechex(rand(1245, 11184810))), "text") : $colour;

            if (confirm('event'))
            {
                $message = "Your event has been added, but first needs to be reviewed by an administrator.";
                $allow = 0;
            }
            else
            {
                $message = "Your event has been added.";
                $allow = 1;
            }
            
            $groupallowed = safesql(serialize($_POST['groups']), "text");
            $signup = safesql($_POST['signup'], "int");
            $signupusers = safesql($_POST['signupusers'], "int");
            $patrols = $signupusers != 3 ? safesql(serialize($_POST['patrols']), "text") : safesql(serialize($_POST['invites']), "text");
            $timestamp = time();
            
            if($data->insert_query("calendar_items", "$insert, $allow, $groupallowed, $timestamp, $colour,$signup, $signupusers,$patrols, 0"))
            {
                $title = safesql($_POST['summary'], "text");
                $article = $data->select_fetch_one_row("calendar_items", "WHERE summary=$title AND date_post=$timestamp");
                $data->insert_query("owners", "'', {$article['id']}, 'events', {$check['id']}, 0, 0, 0");
                $data->update_query("users", "numevent = numevent + 1", "uname='{$check['uname']}'");
                if (confirm('event'))
                {
                    confirmMail("event", $article);
                }
                else
                {
                    email('newitem', array("event", $article));
                }
                show_message($message, "index.php?page=mythings&menuid=$menuid");
            }
            else
            {
                show_message("There was an error adding your event. If this error persists please contact the site administrator.", "index.php?page=addevent", true);
            }
        }
        else
        {
            show_message("There where some errors with some fields, please check them again and resubmit.", "index.php?page=addevent&menuid=$menuid", true);        
        }
	}
                
    $groups = group_sql_list_id("id", "OR");    

    $teams = array();
    $team_query = $data->select_query("groups", "WHERE ($groups) ORDER BY teamname ASC", "id, teamname");
    $numteams = $data->num_rows($team_query);
    while ($teams[] = $data->fetch_array($team_query));

    $patrols = $data->select_fetch_all_rows($numpatrols, "groups", "WHERE ($groups) AND ispatrol = 1 ORDER BY teamname ASC", "id, teamname");
    $groups = group_sql_list_id("patrol", "OR"); 
    $members = $data->select_fetch_all_rows($nummembers, "members", "WHERE ($groups) ORDER BY lastName,firstName ASC", "id, lastName, firstName");

    $tpl->assign('teams',$teams);
    $tpl->assign('numteams', $numteams); 
    $tpl->assign('patrols',$patrols);
    $tpl->assign('numpatrols', $numpatrols); 
    $tpl->assign('members',$members);
    $tpl->assign('nummembers', $nummembers);
    
    $copyitem = isset($_GET['copyitem']) ? $_GET['copyitem'] : 0;
    
    if ($copyitem)
    {
        $copyitem = safesql($copyitem, "int");
        $item = $data->select_fetch_one_row("calendar_items", "WHERE id = $copyitem", "summary, detail");
        
        $tpl->assign("copyitem", $item);
    }

    $script .= "{literal}function makeTwoChars(inp) {
        return String(inp).length < 2 ? \"0\" + inp : inp;
}

function initialiseInputs() {
        // Clear any old values from the inputs (that might be cachedate by the browser after a page reload)
        document.getElementById(\"sdate\").value = \"\";
        document.getElementById(\"edate\").value = \"\";

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
                    'onChange': function(color) {
                        $('colour').value = color.hex;
                        $('colour').style.backgroundColor = color.hex;
                    }
                });";
}

$scriptList['tinyAdv'] = 1;
$scriptList['datepicker'] = 1;
$scriptList['mooRainbow'] = 1;
$dbpage = true;
$pagename = "addevent";
?>