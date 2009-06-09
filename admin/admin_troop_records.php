<?php
/**************************************************************************
    FILENAME        :   admin_records.php
    PURPOSE OF FILE :   Manages users records
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
	$module['Member Management']['Progress charts'] = "troop_records";
    $moduledetails[$modulenumbers]['name'] = "Progress charts";
    $moduledetails[$modulenumbers]['details'] = "Management of user progress charts";
    $moduledetails[$modulenumbers]['access'] = "Allowed to access the troop record manager";
    $moduledetails[$modulenumbers]['add'] = "notused";
    $moduledetails[$modulenumbers]['edit'] = "Allowed to edit troop records";
    $moduledetails[$modulenumbers]['delete'] = "notused";
    $moduledetails[$modulenumbers]['publish'] = "notused";
    $moduledetails[$modulenumbers]['limit'] = "Limit to only users that belong to the same groups as the user.";
    $moduledetails[$modulenumbers]['id'] = "troop_records";
	return;
}
else
{
    $action = $_GET['action'];

    $schemes = $data->select_fetch_all_rows($numschemes, "awardschemes", "ORDER BY name ASC");
    $tpl->assign("schemes", $schemes);
    $tpl->assign("numschemes", $numschemes);

    $safe_scheme = safesql((isset($_GET['scheme']) ? $_GET['scheme'] : $schemes[0]['id']), "int");
    $tpl->assign("schemeNumber", $safe_scheme);
    
    if ($_POST['Submit'] == "Submit")
    {
        if ($action == "edit_advancements" && pageauth("troop_records", "edit") == 1)
        {        
            if (pageauth("troop_records", "limit")) 
            {
                $patrollist = group_sql_list_id("patrol", "OR");
                $membersql = $data->select_query("members", "WHERE ($patrollist) AND type=0 AND awardScheme = $safe_scheme ORDER BY lastName, firstName ASC", "firstName, lastName, id");
            } 
            else 
            {
                $membersql = $data->select_query("members", "WHERE type=0 AND awardScheme = $safe_scheme ORDER BY lastName, firstName ASC", "firstName, lastName, id");
            }
            $nummembers = $data->num_rows($membersql);
            $member = array();
            $record = $_POST["requirement"];
            while ($memberTemp = $data->fetch_array($membersql))
            {
                $safe_memberid = safesql($memberTemp['id'], "int");

                $recordsql = $data->select_query("scoutrecord", "WHERE userid=$safe_memberid AND scheme = $safe_scheme");
                if ($data->num_rows($recordsql) > 0)
                {
                    $recordTemp = safesql(serialize($record[$safe_memberid]), "text");
                    $data->update_query("scoutrecord", "requirements=$recordTemp", "userid=$safe_memberid AND scheme= $safe_scheme");
                }
                else
                {
                    $recordTemp = safesql(serialize($record[$safe_memberid]), "text");
                    $comments = "";
                    $comments = safesql(serialize($comments), "text");
                    $data->insert_query("scoutrecord", "'', $safe_memberid, $recordTemp, $comments, $safe_scheme");
                }
            }
            header("Location: admin.php?page=troop_records&scheme=$safe_scheme");
        }
        elseif ($action == "addbadge" && pageauth("troop_records", "edit") == 1)
        {
            $badges = $_POST['badge'];
            $user = $_POST['user'];
            $comment = safesql($_POST['comment'], "text");
            $date = safesql(time(), "int");

            for($i=0;$i<count($user);$i++)
            {
                $safe_memberid = safesql($user[$i], "int");
                for ($j=0;$j<count($badges);$j++)
                {
                    $badgeid = safesql($badges[$j], "int");
                    if ($data->num_rows($data->select_query("userbadges", "WHERE userid=$safe_memberid AND badgeid=$badgeid")) == 0)
                    {
                        $data->insert_query("userbadges", "'', $safe_memberid, $badgeid, $comment, $date");
                    }
                }
            }

            header("Location: admin.php?page=$page&action=view_badges&scheme=$safe_scheme");
        }
    }    
    if (($action == "view_advancements" || $action=="") || ($action == "edit_advancements" && pageauth("troop_records", "edit") == 1))
    {
        $advansql = $data->select_query("advancements", "WHERE scheme = $safe_scheme ORDER BY position ASC");
        $numadva = $data->num_rows($advansql);
        $advancements = array();
        $numitems = 0;
        while ($temp = $data->fetch_array($advansql)) 
        {
            $getrequirements = $data->select_query("requirements", "WHERE advancement = '{$temp["ID"]}' ORDER BY position ASC");

            $temp['numitems'] = $data->num_rows($getrequirements);
            while ($temp2 = $data->fetch_array($getrequirements))
            {
                $temp['items'][] = $temp2;
            }
            $advancements[] = $temp;
        }
        
        if (pageauth("troop_records", "limit")) 
        {
            $patrollist = group_sql_list_id("patrol", "OR");
            $membersql = $data->select_query("members", "WHERE ($patrollist) AND type=0 AND awardScheme = $safe_scheme ORDER BY lastName, firstName ASC", "firstName, lastName, id");
        } 
        else 
        {
            $membersql = $data->select_query("members", "WHERE type=0 AND awardScheme = $safe_scheme ORDER BY lastName, firstName ASC", "firstName, lastName, id");
        }
        $nummembers = $data->num_rows($membersql);
        $member = array();
        while ($memberTemp = $data->fetch_array($membersql))
        {
            $safe_memberid = safesql($memberTemp['id'], "int");

            $recordsql = $data->select_fetch_one_row("scoutrecord", "WHERE userid=$safe_memberid AND scheme =  $safe_scheme");
            $memberTemp['require'] = unserialize($recordsql['requirements']);
            $memberTemp['comment'] = unserialize($recordsql['comment']);

            $member[] = $memberTemp;
        }
    }
    elseif ($action == "view_badges")
    {
        if (pageauth("troop_records", "limit")) 
        {
            $patrollist = group_sql_list_id("patrol", "OR");
            $membersql = $data->select_query("members", "WHERE ($patrollist) AND type=0 AND awardScheme = $safe_scheme ORDER BY lastName, firstName ASC", "firstName, lastName, id");
        } 
        else 
        {
            $membersql = $data->select_query("members", "WHERE type=0 AND awardScheme = $safe_scheme ORDER BY lastName, firstName ASC", "firstName, lastName, id");
        }
        $nummembers = $data->num_rows($membersql);
        $memberBadges = array();
        
        while ($memberTemp = $data->fetch_array($membersql))
        {
            $safe_memberid = safesql($memberTemp['id'], "int");
            
            $badgesql = $data->select_query("userbadges", "WHERE userid = $safe_memberid");
            $numbadge = 0;
            while ($temp = $data->fetch_array($badgesql))
            {
                $result = $data->select_fetch_one_row("badges", "WHERE id = {$temp['badgeid']} AND scheme=$safe_scheme");
                if ($result != '')
                {
                    $temp['name'] = $result['name'];
                    $temp['description'] = $result['description'];
                    $memberTemp['badge'][] = $temp;
                    $numbadge++;
                }
            }
            $memberTemp['numbadge'] = $numbadge;
            $memberBadges[] = $memberTemp;
        }

        
        $available = $data->select_fetch_all_rows($numavailable, "badges", "WHERE scheme = $safe_scheme ORDER BY name ASC");
            
        $tpl->assign("memberBadges", $memberBadges);
        $tpl->assign("nummembers", $nummembers);
        $tpl->assign("available", $available);
        $tpl->assign("numavailable", $numavailable);
    }

    $tpl->assign("member", $member);
    $tpl->assign("nummembers", $nummembers);
    $tpl->assign("advan", $advancements);
    $tpl->assign("numadva", $numadva);
 
    $tpl->assign('editFormAction', $editFormAction);
    $tpl->assign("action", $action);
    $filetouse='admin_troop_records.tpl';
}

?>