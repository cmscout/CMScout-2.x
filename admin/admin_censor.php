<?php
/**************************************************************************
    FILENAME        :   admin_censor.php
    PURPOSE OF FILE :   Manages censor words
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
	$module['Module Management']['Word Censor Manager'] = "censor";
    $moduledetails[$modulenumbers]['name'] = "Word Censor Manager";
    $moduledetails[$modulenumbers]['details'] = "Manages censored words";
    $moduledetails[$modulenumbers]['access'] = "Allowed to view word censors";
    $moduledetails[$modulenumbers]['add'] = "Allowed to censor words";
    $moduledetails[$modulenumbers]['edit'] = "notused";
    $moduledetails[$modulenumbers]['delete'] = "Allowed to uncensor words";
    $moduledetails[$modulenumbers]['publish'] = "notused";
    $moduledetails[$modulenumbers]['limit'] = "notused";
    $moduledetails[$modulenumbers]['id'] = "censor";

	return;
}
else
{
       
    $Submit = $_POST['Submit'];
    $action = $_GET['action'];
    $id = $_GET['id'];
    
    if ($action == "add" && pageauth("censor", "add"))
    {    
        $word = safesql($_GET['word'], "text");
        $Add = $data->insert_query("censorwords", "NULL, $word");
        if ($Add)
        {
            show_admin_message("Word added", "$pagename");
        }
        $action="";
    }
    elseif ($action=="delete" && pageauth("censor", "delete"))
    {
        $Delete = $data->delete_query("censorwords", "id='$id'");	
        if ($Delete)
        {
            show_admin_message("Word removed", "$pagename"); 
        }
    }
    
    $result = $data->select_query("censorwords", "ORDER BY id DESC");
    
    $words = array();
    $numwords = $data->num_rows($result);
    while ($words[] = $data->fetch_array($result));
    
    $tpl->assign('numwords', $numwords);
    $tpl->assign('words', $words);
    $filetouse = "admin_censor.tpl";
}
?>