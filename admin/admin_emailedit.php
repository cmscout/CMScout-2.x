<?php
/**************************************************************************
    FILENAME        :   admin_emailedit.php
    PURPOSE OF FILE :   Allows editing of emails
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
	$module['Configuration']['Email Content Manager'] = "emailedit";
    $moduledetails[$modulenumbers]['name'] = "User Emails";
    $moduledetails[$modulenumbers]['details'] = "Allows you to edit the content of emails that are sent to users";
    $moduledetails[$modulenumbers]['access'] = "Allowed to access the email content manager";
    $moduledetails[$modulenumbers]['add'] = "notused";
    $moduledetails[$modulenumbers]['edit'] = "Allowed to edit a email";
    $moduledetails[$modulenumbers]['delete'] = "notused";
    $moduledetails[$modulenumbers]['publish'] = "notused";
    $moduledetails[$modulenumbers]['limit'] = "notused";
    $moduledetails[$modulenumbers]['id'] = "emailedit";
	return;
}
else
{
    
    $editFormAction = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING'])) 
    {
      $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
    }
    $Submit = $_POST['Submit'];
    $id = $_GET['id'];
    $action = $_GET['action'];
    
    // Edit content
    if ($Submit == "Update" && pageauth("emailedit", "edit") == 1)
    {
        $id = safesql($id, "int");
        $subject = safesql($_POST['subject'], "text");
        $email = safesql($_POST['email'], "text");
        
        if ($data->update_query("emails", "subject=$subject, email=$email", "id=$id"))
        {
            show_admin_message("Email updated", $pagename);
        }
    } 
    
    // Show specific content
    if ($id != "" && pageauth("emailedit", "edit") == 1)
    {
        // Show selected content
        $id = safesql($id, "int");
        $email = $data->select_fetch_one_row("emails", "WHERE id=$id");
        $tpl->assign("email", $email);
    }
    
    // Show all news
    $emails = $data->select_fetch_all_rows($numemails, "emails", "ORDER BY name ASC");
    
    $tpl->assign('action', $action);
    $tpl->assign('numemails', $numemails);
    $tpl->assign('emails', $emails);
    
    $filetouse = "admin_emailedit.tpl";
}
?>