<?php
/**************************************************************************
    FILENAME        :   addpoll.php
    PURPOSE OF FILE :   Add a users poll to the database
    LAST UPDATED    :   18 February 2006
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
/********************************************Check if user is allowed*****************************************/
if (!defined('SCOUT_NUKE'))
    die("You have accessed this page illegally, please go use the main menu");
$location = "Add Poll";
if (isset($check["uname"])) 
{
 $tpl->assign('name',$check["uname"]);
} 

if ($_POST['Submit'] == "Submit")
{  
    if (validate($_POST['validation']))
    {
        $options = safesql(serialize($_POST['option']), "text");

        $poll['stopdate'] = $_POST['date_stop']; 
        $poll['pollq'] = $_POST['question'];
        if ($poll['pollq'] != "")
        {
            
            $poll['pollq'] = safesql($poll['pollq'], "text");
            $poll['stopdate'] = $poll['stopdate'] != "" ? safesql(strtotime($poll['stopdate']), "int") : 0;
            
            if (confirm('poll'))
            {
                $message = "Your poll has been added, but first needs to be reviewed by an administrator.";
                $allow = 0;
            }
            else
            {
                $message = "Your poll has been added.";
                $allow = 1;
            }
            
            $results = array();
            for($i=0;$i<count($_POST['option']);$i++)
            {
                $results[str_replace(' ', '', $_POST['option'][$i])] = 0;
            }
            $results = safesql(serialize($results), "text");

            $sql = $data->insert_query("polls", "NULL, {$poll['pollq']}, $timestamp, {$poll['stopdate']}, $options, $results, $allow, 0");
            if ($sql)
            {
                $polling = $data->select_fetch_one_row("polls", "WHERE question = {$poll['pollq']} AND date_start=$timestamp ORDER BY id DESC", "id");

                if ($data->insert_query("owners", "'', {$polling['id']}, 'pollitems', {$check['id']}, 0, 0, 0"))
                {
                    if (confirm('poll'))
                    {
                        confirmMail("poll", $polling);
                    }
                    else
                    {
                        email('newitem', array("poll", $polling));
                    }
                    show_message($message, "index.php?page=mythings&menuid=$menuid");
                }
                else
                {
                    show_message("There was an error adding your poll. If this error persists please contact the site administrator.", "index.php?page=addpoll&menuid=$menuid", true);
                }
            }
        }
    }
    else
    {
        show_message("There where some errors with some fields, please check them again and resubmit.", "index.php?page=addpoll&menuid=$menuid", true);        
    }
}

    $script .= "{literal}
function initialiseInputs() {
        // Clear any old values from the inputs (that might be cachedate by the browser after a page reload)
        document.getElementById(\"sdate\").value = \"\";
}

datePickerController.addEvent(window, 'load', initialiseInputs);
{/literal}";
$scriptList['datepicker'] = 1;

$sql = $data->select_query("polls", "WHERE sidebox=1", "question");
$sideboxpoll = $data->fetch_array($sql);
$tpl->assign('sideboxpoll', $sideboxpoll);

$authorization['sideboxpoll']=get_auth('sideboxpoll');

$tpl->assign('auth', $authorization);

$dbpage = true;
$pagename = "addpoll";
?>