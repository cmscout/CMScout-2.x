<?php
/**************************************************************************
    FILENAME        :   admin_users.php
    PURPOSE OF FILE :   Displays users
    LAST UPDATED    :   02 October 2006
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
	$module['Member Management']['Member Manager'] = "troop";
    $moduledetails[$modulenumbers]['name'] = "Member Manager";
    $moduledetails[$modulenumbers]['details'] = "Management of users that are part of the troop";
    $moduledetails[$modulenumbers]['access'] = "Allowed to access the member manager";
    $moduledetails[$modulenumbers]['add'] = "Allowed to add a a new member";
    $moduledetails[$modulenumbers]['edit'] = "Allowed to edit members";
    $moduledetails[$modulenumbers]['delete'] = "Allowed to delete members";
    $moduledetails[$modulenumbers]['publish'] = "notused";
    $moduledetails[$modulenumbers]['limit'] = "Limit to only members that belong to the same groups as the user. Will also only allow editing of record, not of member details (if editing is enabled)";
    $moduledetails[$modulenumbers]['id'] = "troop";
	return;
}
else
{
    $subpage = $_GET['subpage'] != '' ? $_GET['subpage'] : '';
    if (!$subpage)
    {
        if ($_POST['submit'] == "Submit")
        {
            if ($_GET['id'])
            {
                $id = safesql($_GET['id'], "int");
                $temp = $data->select_fetch_one_row("members", "WHERE id=$id");
                $custom = unserialize($temp['custom']);
            }
            else
            {
                $custom = array();
            }
            $sql = $data->select_query("profilefields", "WHERE place=1 ORDER BY pos ASC");
            $numfields = $data->num_rows($sql);
            while ($temp =  $data->fetch_array($sql))
            {
                $temp['options'] = unserialize($temp['options']);
                if ($temp['type'] == 4)
                {
                    $temp2 = array();
                    for($i=1;$i<=$temp['options'][0];$i++)
                    {
                        $temp2[$i] = $_POST[$temp['name'] . $i] ? 1 : 0;
                    }
                    $custom[$temp['name']] = $temp2;
                }
                else
                {
                    if (isset($_POST[$temp['name']]))
                    {
                        $custom[$temp['name']] = $_POST[$temp['name']];
                    }
                }
                if ($custom[$temp['name']] == '' && $temp['required'] == 1 && (($temp['register'] == 1 && $_POST['type'] == 0) || $temp['register'] == 0))
                {
                    show_message_back("The {$temp['query']} field is required.");
                    exit;
                }
            }   
            $custom = safesql(serialize($custom), "text");
            if ($_GET['action'] == "new")
            {
                $firstname = safesql($_POST['firstname'], "text");
                $middlename = safesql($_POST['middlename'], "text");
                $lastname = safesql($_POST['lastname'], "text");
                $sex = safesql($_POST['sex'], "int");            
                $address = safesql($_POST['address'], "text");
                $homenumber = safesql($_POST['homenumber'], "text");
                $cellnumber = safesql($_POST['cellnumber'], "text");
                $worknumber = safesql($_POST['worknumber'], "text");
                $dob = safesql(strtotime($_POST['dob']), "int");
                $email = safesql($_POST['email'], "text");
                $medicalname = safesql($_POST['medicalname'], "text");
                $medicalnumber = safesql($_POST['medicalnumber'], "text");
                $docname = safesql($_POST['docname'], "text");
                $docnum = safesql($_POST['docnum'], "text");
                $medical = safesql($_POST['medical'], "text");
                $type = safesql($_POST['type'], "int");
                $userId = safesql($_POST['userId'], "int");
                $fatherId = $type == 0 ? safesql($_POST['fatherId'], "int") : 0;
                $motherId = $type == 0 ? safesql($_POST['motherId'], "int") : 0;
                $primaryGuard = safesql($_POST['primaryGuard'], "int");
                $patrol = safesql($_POST['patrol'], "int");
                $awardScheme = safesql($_POST['awardScheme'], "int");
                $section = safesql($_POST['section'], "int");
                
                if ($type == 0)
                {
                    $copy = $_POST['copy'];
                    $father = $data->select_fetch_one_row("members", "WHERE id=$fatherId");
                    $mother = $data->select_fetch_one_row("members", "WHERE id=$motherId");
                    
                    if($copy['father']['home'])
                    {
                        $homenumber = safesql($father['home'], "text");
                    }
                    if($copy['father']['cell'])
                    {
                        $cellnumber = safesql($father['cell'], "text");
                    }
                    if($copy['father']['work'])
                    {
                        $worknumber = safesql($father['work'], "text");
                    }
                    if($copy['father']['email'])
                    {
                        $email = safesql($father['email'], "text");
                    }
                    if($copy['father']['medical'])
                    {
                        $medicalname = safesql($father['aidName'], "text");
                        $medicalnumber = safesql($father['aidNumber'], "text");
                        $docname = safesql($father['docName'], "text");
                        $docnum = safesql($father['docNumber'], "text");
                    }
                    
                    if($copy['mother']['home'])
                    {
                        $homenumber = safesql($mother['home'], "text");
                    }
                    if($copy['mother']['cell'])
                    {
                        $cellnumber = safesql($mother['cell'], "text");
                    }
                    if($copy['mother']['work'])
                    {
                        $worknumber = safesql($mother['work'], "text");
                    }
                    if($copy['mother']['email'])
                    {
                        $email = safesql($mother['email'], "text");
                    }
                    if($copy['mother']['medical'])
                    {
                        $medicalname = safesql($mother['aidName'], "text");
                        $medicalnumber = safesql($mother['aidNumber'], "text");
                        $docname = safesql($mother['docName'], "text");
                        $docnum = safesql($mother['docNumber'], "text");
                    }
                }
                
                $data->update_query("members", "userId=0", "userId=$userId");
                
                $data->insert_query("members", "'', $firstname, $middlename, $lastname, $dob, $sex, $address, $cellnumber, $homenumber, $worknumber, $email, $medicalname, $medicalnumber, $docname, $docnum, $medical, $section, $patrol, $type, $userId, $fatherId, $motherId, $primaryGuard, $awardScheme, $custom");
                
                show_admin_message("Member Added", $pagename);
            }
            elseif ($_GET['action'] == "edit")
            {
                $firstname = safesql($_POST['firstname'], "text");
                $middlename = safesql($_POST['middlename'], "text");
                $lastname = safesql($_POST['lastname'], "text");
                $sex = safesql($_POST['sex'], "int");
                $address = safesql($_POST['address'], "text");
                $homenumber = safesql($_POST['homenumber'], "text");
                $cellnumber = safesql($_POST['cellnumber'], "text");
                $worknumber = safesql($_POST['worknumber'], "text");
                $dob = safesql(strtotime($_POST['dob']), "int");
                $email = safesql($_POST['email'], "text");
                $medicalname = safesql($_POST['medicalname'], "text");
                $medicalnumber = safesql($_POST['medicalnumber'], "text");
                $docname = safesql($_POST['docname'], "text");
                $docnum = safesql($_POST['docnum'], "text");
                $medical = safesql($_POST['medical'], "text");
                $type = safesql($_POST['type'], "int");
                $userId = safesql($_POST['userId'], "int");
                $fatherId = $type == 0 ?safesql($_POST['fatherId'], "int") : 0;
                $motherId = $type == 0 ? safesql($_POST['motherId'], "int") : 0;
                $id = safesql($_GET['id'], "int");
                $primaryGuard = safesql($_POST['primaryGuard'], "int");
                $patrol = safesql($_POST['patrol'], "int");
                $awardScheme = safesql($_POST['awardScheme'], "int");
                $section = safesql($_POST['section'], "int");
                
                if ($type == 0)
                {
                    $copy = $_POST['copy'];
                    $father = $data->select_fetch_one_row("members", "WHERE id=$fatherId");
                    $mother = $data->select_fetch_one_row("members", "WHERE id=$motherId");
                    
                    if($copy['father']['home'])
                    {
                        $homenumber = safesql($father['home'], "text");
                    }
                    if($copy['father']['cell'])
                    {
                        $cellnumber = safesql($father['cell'], "text");
                    }
                    if($copy['father']['work'])
                    {
                        $worknumber = safesql($father['work'], "text");
                    }
                    if($copy['father']['email'])
                    {
                        $email = safesql($father['email'], "text");
                    }
                    if($copy['father']['medical'])
                    {
                        $medicalname = safesql($father['aidName'], "text");
                        $medicalnumber = safesql($father['aidNumber'], "text");
                        $docname = safesql($father['docName'], "text");
                        $docnum = safesql($father['docNumber'], "text");
                    }
                    
                    if($copy['mother']['home'])
                    {
                        $homenumber = safesql($mother['home'], "text");
                    }
                    if($copy['mother']['cell'])
                    {
                        $cellnumber = safesql($mother['cell'], "text");
                    }
                    if($copy['mother']['work'])
                    {
                        $worknumber = safesql($mother['work'], "text");
                    }
                    if($copy['mother']['email'])
                    {
                        $email = safesql($mother['email'], "text");
                    }
                    if($copy['mother']['medical'])
                    {
                        $medicalname = safesql($mother['aidName'], "text");
                        $medicalnumber = safesql($mother['aidNumber'], "text");
                        $docname = safesql($mother['docName'], "text");
                        $docnum = safesql($mother['docNumber'], "text");
                    }
                }
                
                $data->update_query("members", "userId=0", "userId=$userId");
                
                $data->update_query("members", "firstName=$firstname, middleName=$middlename, lastName=$lastname, dob=$dob, sex=$sex, address=$address, cell=$cellnumber, home=$homenumber, work=$worknumber, email=$email, aidName=$medicalname, aidNumber=$medicalnumber, docName=$docname, docNumber=$docnum, medicalDetails=$medical, type=$type, userId=$userId, fatherId=$fatherId, motherId=$motherId, primaryGuard=$primaryGuard, custom=$custom, awardScheme = $awardScheme, patrol=$patrol, section=$section", "id=$id");
                
                show_admin_message("Member Updated", $pagename);
            }
        }
        
        if ($_GET['action'] == "new" || $_GET['action'] == "edit")
        {
            $id = safesql($_GET['id'], "int");
            $sql = $data->select_query("users", "ORDER BY lastname, firstname ASC", "id, uname, firstname, lastname");
            $numusers = $data->num_rows($sql);
            $user = array();
            while ($temp = $data->fetch_array($sql))
            {
                $sql2 = $data->select_query("members", "WHERE userId={$temp['id']} AND id != $id");
                if ($data->num_rows($sql2))
                {
                    $temp2 = $data->fetch_array($sql2);
                    $temp['selectedlast'] = $temp2['lastName'];
                    $temp['selectedfirst'] = $temp2['firstName'];
                    $temp['selected'] = true;
                }
                $user[] = $temp;
            }

            $fathers = $data->select_fetch_all_rows($numfathers, "members", "WHERE sex=0 AND type=1 ORDER BY lastName, firstName ASC");
            $legal = $data->select_fetch_all_rows($numlegal, "members", "WHERE type=3 ORDER BY lastName, firstName ASC");
            $mother = $data->select_fetch_all_rows($nummothers, "members", "WHERE sex=1 AND type=2 ORDER BY lastName, firstName ASC");

            if ($_GET['action'] == "edit")
            {
                $member = $data->select_fetch_one_row("members", "WHERE id=$id");
                $member['custom'] = unserialize($member['custom']);
                $tpl->assign("member", $member);
            }
            
            $sql = $data->select_query("profilefields", "WHERE place=1 AND register=0 ORDER BY pos ASC");
            $fields = array();
            $numfields = $data->num_rows($sql);
            while ($temp =  $data->fetch_array($sql))
            {
                $temp['options'] = unserialize($temp['options']);
                $fields[] = $temp;
            }
            
            $sql = $data->select_query("profilefields", "WHERE place=1 AND register=1 ORDER BY pos ASC");
            $scoutfields = array();
            $numscoutfields = $data->num_rows($sql);
            while ($temp =  $data->fetch_array($sql))
            {
                $temp['options'] = unserialize($temp['options']);
                $scoutfields[] = $temp;
            }

            $patrol = $data->select_fetch_all_rows($numpatrols, "groups" , "WHERE ispatrol=1 ORDER BY teamname ASC");
            $id = safesql($_GET['id'], "int");
            $member = $data->select_fetch_one_row("members", "WHERE id=$id");
            $member['custom'] = unserialize($member['custom']);
            
            $schemes = $data->select_fetch_all_rows($numschemes, "awardschemes", "ORDER BY name ASC");
            $sections = $data->select_fetch_all_rows($numsections, "sections", "ORDER BY name ASC");
            
            $tpl->assign("schemes", $schemes);
            $tpl->assign("numschemes", $numschemes);           
            $tpl->assign("sections", $sections);
            $tpl->assign("numsections", $numsections);           
            $tpl->assign("member", $member);
            $tpl->assign('patrol', $patrol);
            $tpl->assign('numpatrols', $numpatrols);
            $tpl->assign('scoutfields', $scoutfields);
            $tpl->assign('numscoutfields', $numscoutfields);
            
            $tpl->assign('fields', $fields);
            $tpl->assign('numfields', $numfields);
            $tpl->assign("user", $user);
            $tpl->assign("numusers", $numusers);
            $tpl->assign("fathers", $fathers);
            $tpl->assign("numfathers", $numfathers);
            $tpl->assign("legal", $legal);
            $tpl->assign("numlegal", $numlegal);
            $tpl->assign("mother", $mother);
            $tpl->assign("nummothers", $nummothers);
        }
	elseif ($_GET['action'] == "import")
	{
		$step =  $_GET['step'] == 2 ? 2 : 1;
		if ($_POST['Submit'] == "Submit" && $_GET['step'] != 2)
		{
			if ($_FILES['file']['error'] == 0)
			{
				move_uploaded_file($_FILES['file']['tmp_name'], "cache/import.csv");
			}
		    
			$handle = fopen("cache/import.csv", "r");
			$csvdata = array();
			$headers = fgetcsv($handle, 2000, ",");
			while (($temp = fgetcsv($handle, 2000, ",")) !== FALSE)
			{
				if ($temp)
					$csvdata[] = $temp;
			}
			fclose($handle);
			
			$tpl->assign("headers", $headers);
			$tpl->assign("csvdata", $csvdata);
			
			$schemes = $data->select_fetch_all_rows($numschemes, "awardschemes", "ORDER BY name ASC");
			$sections = $data->select_fetch_all_rows($numsections, "sections", "ORDER BY name ASC");
			
			$tpl->assign("schemes", $schemes);
			$tpl->assign("numschemes", $numschemes);           
			$tpl->assign("sections", $sections);
			$tpl->assign("numsections", $numsections); 
			
			$step = 2;
		}
		elseif ($step == 2)
		{
			$handle = fopen("cache/import.csv", "r");
			$csvdata = array();
			$headers = fgetcsv($handle, 2000, ",");

			while (($temp = fgetcsv($handle, 2000, ",")) !== FALSE)
			{
				if ($temp)
					$csvdata[] = $temp;
			}
			fclose($handle);

			foreach($csvdata as $memberinfo)
			{
				$firstname = safesql($memberinfo[$_POST['firstname']], "text", true, true, true);
				$middlename = safesql($memberinfo[$_POST['middlename']], "text", true, true, true);
				$lastname = safesql($memberinfo[$_POST['lastname']], "text", true, true, true);
				$sex = safesql($memberinfo[$_POST['sex']], "int", true, true, true);
				$address = safesql($memberinfo[$_POST['address']] . "\n" . $memberinfo[$_POST['address2']] . "\n" .$memberinfo[$_POST['address3']] . "\n" .$memberinfo[$_POST['address4']], "text", true, true, true);
				$homenumber = safesql($memberinfo[$_POST['homenumber']], "text", true, true, true);
				$cellnumber = safesql($memberinfo[$_POST['cellnumber']], "text", true, true, true);
				$worknumber = safesql($memberinfo[$_POST['worknumber']], "text", true, true, true);
				$dob = safesql(strtotime($memberinfo[$_POST['dob']]), "int", true, true, true);
				$email = safesql($memberinfo[$_POST['email']], "text", true, true, true);
				$medicalname = safesql($memberinfo[$_POST['medicalname']], "text", true, true, true);
				$medicalnumber = safesql($memberinfo[$_POST['medicalnumber']], "text", true, true, true);
				$docname = safesql($memberinfo[$_POST['docname']], "text", true, true, true);
				$docnum = safesql($memberinfo[$_POST['docnum']], "text", true, true, true);
				$medical = safesql($memberinfo[$_POST['medical']], "text", true, true, true);
				$patrol = safesql($memberinfo[$_POST['patrol']], "text", true, true, true);
	
				$type = safesql($_POST['type'], "int");
				$primaryGuard = safesql($_POST['primaryGuard'], "int");
				$awardScheme = safesql($_POST['awardScheme'], "int");
				$section = safesql($_POST['section'], "int");

				$motherId = 0;
				$fatherId = 0;
				
				if ($type == 0)
				{
					$father['firstname'] = safesql($memberinfo[$_POST['fatherFirst']], "text", true, true, true);
					$father['lastname'] = safesql($memberinfo[$_POST['fatherLast']], "text", true, true, true);
					$father['address'] = safesql($memberinfo[$_POST['fatherAddress']] . "\n" . $memberinfo[$_POST['fatherAddress2']] . "\n" .$memberinfo[$_POST['fatherAddress3']] . "\n" .$memberinfo[$_POST['fatherAddress4']], "text", true, true, true);
					$father['homenumber'] = safesql($memberinfo[$_POST['fatherHome']], "text", true, true, true);
					$father['cellnumber'] = safesql($memberinfo[$_POST['fatherCell']], "text", true, true, true);
					$father['worknumber'] = safesql($memberinfo[$_POST['fatherWork']], "text", true, true, true);
					$father['email'] = safesql($memberinfo[$_POST['fatherEmail']], "text", true, true, true);
					
					$mother['firstname'] = safesql($memberinfo[$_POST['motherFirst']], "text", true, true, true);
					$mother['lastname'] = safesql($memberinfo[$_POST['motherLast']], "text", true, true, true);
					$mother['address'] = safesql($memberinfo[$_POST['motherAddress']] . "\n" . $memberinfo[$_POST['motherAddress2']] . "\n" .$memberinfo[$_POST['motherAddress3']] . "\n" .$memberinfo[$_POST['motherAddress4']], "text", true, true, true);
					$mother['homenumber'] = safesql($memberinfo[$_POST['motherHome']], "text", true, true, true);
					$mother['cellnumber'] = safesql($memberinfo[$_POST['motherCell']], "text", true, true, true);
					$mother['worknumber'] = safesql($memberinfo[$_POST['motherWork']], "text", true, true, true);
					$mother['email'] = safesql($memberinfo[$_POST['motherEmail']], "text", true, true, true);
					
					if ($father['firstname'] != 'NULL' && $father['lastname'] != 'NULL')
					{
						$sql = $data->select_query("members",  "WHERE firstName = {$father['firstname']} AND lastName = {$father['lastname']} AND type= 1");
						if ($data->num_rows($sql) == 0)
						{
							$data->insert_query("members", "'', {$father['firstname']}, NULL, {$father['lastname']}, NULL, 0, {$father['address']}, {$father['homenumber']}, {$father['cellnumber']}, {$father['worknumber']}, {$father['email']}, NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 0, 0, 0, 0, 0, ''");
							$sql = $data->select_query("members",  "WHERE firstName = {$father['firstname']} AND lastName = {$father['lastname']} AND type= 1");
						}
						
						$fatherDetails = $data->fetch_array($sql);
						$fatherId = $fatherDetails['id'];
					}

					if ($mother['firstname'] != 'NULL' && $mother['lastname'] != 'NULL')
					{
						$sql = $data->select_query("members",  "WHERE firstName = {$mother['firstname']} AND lastName = {$mother['lastname']} AND type= 2");
						if ($data->num_rows($sql) == 0)
						{
							$data->insert_query("members", "'', {$mother['firstname']}, NULL, {$mother['lastname']}, NULL, 1, {$mother['address']}, {$mother['homenumber']}, {$mother['cellnumber']}, {$mother['worknumber']}, {$mother['email']}, NULL, NULL, NULL, NULL, NULL, 0, 0, 2, 0, 0, 0, 0, 0, ''");
							$sql = $data->select_query("members",  "WHERE firstName = {$mother['firstname']} AND lastName = {$mother['lastname']} AND type= 2");
						}
						
						$motherDetails = $data->fetch_array($sql);
						$motherId = $motherDetails['id'];
					}
				}
				
				$sql = $data->select_query("groups", "WHERE teamname=$patrol", "id");
				if ($data->num_rows($sql))
				{	
					$patrol = $data->fetch_array($sql);
					
					if ($patrol['ispatrol'] == 0)
					{
						$data->update_query("groups", "ispatrol = 1", "id={$patrol['id']}");
					}
					
					$patrol = $patrol['id'];
				}
				else
				{
					$data->insert_query("groups", "NULL, $patrol, 1, 0, 0, 0, '', '', ''");
					$sql = $data->select_fetch_one_row("groups", "WHERE teamname=$patrol", "id");
					$patrol = $sql['id'];
				}
				
				$data->insert_query("members", "'', $firstname, $middlename, $lastname, $dob, $sex, $address, $cellnumber, $homenumber, $worknumber, $email, $medicalname, $medicalnumber, $docname, $docnum, $medical, $section, $patrol, $type, 0, $fatherId, $motherId, $primaryGuard, $awardScheme, ''");
			}
			
			unlink ("cache/import.csv");
			show_admin_message("Members Imported", $pagename);
		}
		$tpl->assign("step", $step);
	}
        elseif ($_GET['action'] == "view")
        {
            $id = safesql($_GET['id'], "int");
            $member = $data->select_fetch_one_row("members", "WHERE id=$id");
            $member['custom'] = unserialize($member['custom']);
                
            $member['father'] = $data->select_fetch_one_row("members", "WHERE id={$member['fatherId']}", "firstname, lastname, cell, home, work, email");
            $member['mother'] = $data->select_fetch_one_row("members", "WHERE id={$member['motherId']}", "firstname, lastname, cell, home, work, email");
            $member['awardScheme'] = $data->select_fetch_one_row("awardschemes", "WHERE id={$member['awardScheme']}", "name");
            $member['user'] = $data->select_fetch_one_row("users", "WHERE id={$member['userId']}", "uname, firstname, lastname");
            $member['patrolname'] = $data->select_fetch_one_row("groups", "WHERE id={$member['patrol']}", "teamname");
            $member['section'] = $data->select_fetch_one_row("sections", "WHERE id={$member['section']}", "name");

            $tpl->assign("member", $member);
            
            $sql = $member['type'] != 0 ? $data->select_query("profilefields", "WHERE place=1 AND register=0 ORDER BY pos ASC") : $data->select_query("profilefields", "WHERE place=1 ORDER BY pos ASC");
            $fields = array();
            $numfields = $data->num_rows($sql);
            while ($temp =  $data->fetch_array($sql))
            {
                $temp['options'] = unserialize($temp['options']);
                $fields[] = $temp;
            }

            $tpl->assign('fields', $fields);
            $tpl->assign('numfields', $numfields);
        }
        elseif ($_GET['action'] == "delete") 
        {
            $id = safesql($_GET['id'], "int");
            $sql2 = $data->delete_query("members", "id='$id'");
            $data->delete_query("scoutrecord", "userid='$id'", "", "", false);
            $data->delete_query("userbadges", "userid='$id'", "", "", false);
            show_admin_message("Member deleted", "$pagename");
            $action = "";
        }
        elseif ($_GET['action'] == "")
        {
            if (pageauth("troop", "limit")) 
            {
                $patrollist = group_sql_list_id("patrol", "OR");
                $sql = $data->select_query("members", "WHERE ($patrollist) ORDER BY lastName, firstName ASC");
            } 
            else 
            {
                $sql = $data->select_query("members", "ORDER BY lastName, firstName ASC");
            }
            $nummembers = $data->num_rows($sql);
            $members = array();
            
            while ($temp = $data->fetch_array($sql))
            {
                if ($temp['type'] == 0)
                {
                    $pa = $data->select_fetch_one_row("members", "WHERE id={$temp['fatherId']}");
                    $ma = $data->select_fetch_one_row("members", "WHERE id={$temp['motherId']}");
                    $temp['relations'] = "Father: <span style=\"font-weight:bold;color:". (($temp['primaryGuard'] == 0 && $temp['fatherId']) || (($temp['primaryGuard'] == 1 && !$temp['fatherId'])) ? '#ff0000' : '#000') ."\">" . (isset($pa['firstName']) ? $pa['lastName'] . ', ' . $pa['firstName'] : "Not in System") . "</span><br />Mother: <span style=\"font-weight:bold;color:". (($temp['primaryGuard'] == 1 && $temp['motherId']) || (($temp['primaryGuard'] == 0 && !$temp['fatherId'])) ? '#ff0000' : '#000') ."\">" . (isset($ma['firstName']) ? $ma['lastName'] . ', ' . $ma['firstName'] : "Not in System") . "</span>";
                }
                elseif ($temp['type'] == 1 || $temp['type'] == 2)
                {
                    if ($temp['sex'] == 0)
                    {
                        $sql2 = $data->select_query("members", "WHERE fatherId = {$temp['id']} ORDER BY lastName, firstName ASC");
                    }
                    elseif ($temp['sex'] == 1)
                    {
                        $sql2 = $data->select_query("members", "WHERE motherId = {$temp['id']} ORDER BY lastName, firstName ASC");
                    }
                    $number = $data->num_rows($sql2);
                    $num=0;
                    while ($temp2 = $data->fetch_array($sql2))
                    {
                        if ($temp2['sex'] == 0)
                        {
                            $temp['relations'] .= "Son: <b>";
                        }
                        else
                        {
                            $temp['relations'] .= "Daughter: <b>";
                        }
                        $temp['relations'] .= $temp2['lastName'] . ', ' . $temp2['firstName'] . "</b>";
                        if ($num++ < $number) $temp['relations'] .= "<br />";
                    }
                }
                elseif ($temp['type'] == 3)
                {
                    $sql2 = $data->select_query("members", "WHERE fatherId = {$temp['id']} ORDER BY lastName, firstName ASC");
                    $number = $data->num_rows($sql2);
                    $num=0;
                    while ($temp2 = $data->fetch_array($sql2))
                    {
                        $temp['relations'] .= "Ward: <b>";
                        $temp['relations'] .= $temp2['lastName'] . ', ' . $temp2['firstName'] . "</b>";
                        if ($num++ < $number) $temp['relations'] .= "<br />";
                    }
                }
                
                $uinfo = $data->select_fetch_one_row("users", "WHERE id={$temp['userId']}", "uname");
                
                $temp['uname'] = isset($uinfo['uname']) ? $uinfo['uname'] : "Not a site user";
                
                $members[] = $temp;
            }
            
            $tpl->assign("members", $members);
            $tpl->assign("nummembers", $nummembers);
        }
        elseif ($_GET['action'] == "scouting")
        {
            $sql = $data->select_query("profilefields", "WHERE place=1 AND register=1 ORDER BY pos ASC");
            $fields = array();
            $numfields = $data->num_rows($sql);
            while ($temp =  $data->fetch_array($sql))
            {
                $temp['options'] = unserialize($temp['options']);
                $fields[] = $temp;
            }

            $patrol = $data->select_fetch_all_rows($numpatrols, "groups" , "WHERE ispatrol=1 ORDER BY teamname ASC");
            $id = safesql($_GET['id'], "int");
            $member = $data->select_fetch_one_row("members", "WHERE id=$id");
            $member['custom'] = unserialize($member['custom']);
            
            $schemes = $data->select_fetch_all_rows($numschemes, "awardschemes", "ORDER BY name ASC");
            
            $tpl->assign("schemes", $schemes);
            $tpl->assign("numschemes", $numschemes);           
            $tpl->assign("member", $member);
            $tpl->assign('patrol', $patrol);
            $tpl->assign('numpatrols', $numpatrols);
            $tpl->assign('fields', $fields);
            $tpl->assign('numfields', $numfields);
        }
        
        $tpl->assign("action", $_GET['action']);
        $filetouse = "admin_troop.tpl";
    }
    else
    {
        $allowed = array('records'=>true);
        
        if (array_key_exists($subpage, $allowed))
        {
            include("admin/admin_$subpage.php");
        }
        else
        {
            include("admin/admin_main.php");
        }
    }
}
?>