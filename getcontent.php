<?php
/**************************************************************************
    FILENAME        :   getcontent.php
    PURPOSE OF FILE :   Fetches static and dynamic content and prepares it for display (Also checks authorisation)
    LAST UPDATED    :   15 January 2006
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
/********************************************Start Content Generation*****************************************/
    
    $page = (isset($_GET['page']) && $_GET['page'] != '') ? $_GET['page'] : "frontpage";
    $type = $_GET['type'] == "static" ? 1 : 0;
    $validdynamic = false;
    $safe_page = safesql($_GET['page'], "text");
    $exempt = array("rss"	=> true,
                "patrolpages"	=> true,
                "subsite"       	=> true,
                "logon"         	=> true,
                "register"      	=> true,
		"forgot"	    	=> true);
    if ($exempt[$page] == true || $data->num_rows($data->select_query("functions", "WHERE active = 1 AND code = $safe_page")))
    {
        $validdynamic = true;
    }
    
	$dataC = false;
	$dbpage = false;
	$pagenum = (isset($_GET['pagenum'])) ? $_GET['pagenum'] : 0;
    $filetouse = "";

    $othermessage = false;
    if (get_auth($page, $type) == 1)
    {
        if ($type == 1)
        {
            $filetouse = censor(get_spec($page, $location));
            if ($filetouse && $page != "frontpage" || !$validdynamic)
            {
                $dataC = true;
                
                location($location, $check["uid"]);
                
                $edit = adminauth("content", "edit") ? true : false;
                $editlink = "admin.php?page=content&amp;id=$page&amp;action=edit&amp;main=1";
            }
            else 
            {
                include('frontpage' . $phpex);	
            }
        }
        else
        {
            if (file_exists($page . $phpex) && $validdynamic) 
            {
                include($page . $phpex);
            } 
	    elseif(!$validdynamic && $page != "frontpage")
	    {
		show_message("The module you are trying to access is either not a valid module, or it has been disabled");
	    }
            else
            {
                include('frontpage' . $phpex);	
            } 
		$tpl->assign ("cpallowed",  get_auth("usercp", 0) == 1 && $data->num_rows($data->select_query("functions", "WHERE active = 1 AND code = 'usercp'")));
		$tpl->assign ("profileallowed", get_auth("profile", 0) == 1 && $data->num_rows($data->select_query("functions", "WHERE active = 1 AND code = 'profile'")));
		$tpl->assign ("contributionallowed", get_auth("mythings", 0) == 1 && $data->num_rows($data->select_query("functions", "WHERE active = 1 AND code = 'mythings'")));
		$tpl->assign ("groupsallowed", get_auth("mypatrol", 0) == 1 && $data->num_rows($data->select_query("functions", "WHERE active = 1 AND code = 'mypatrol'")));
		$tpl->assign ("pmallowed", get_auth("pmmain", 0) == 1 && $data->num_rows($data->select_query("functions", "WHERE active = 1 AND code = 'pmmain'")));
        }
       
           
        if ($pagenum == 0) 
        {
            $pagenum = 1;
        }
    
        if ($dbpage == true && isset($pagename) && $pagename != "" && $pagename != "frontpage")
        {
            $dataC = true;
            $filetouse = get_temp($pagename, $pagenum);
        } 
        elseif ($pagename == "frontpage")
        {
            $dataC = true;
            $filetouse = $content;
        }
        elseif ($dbpage == false && $message != "" && $othermessage == false)
        {
            $filetouse = $message;
            $dataC = true;
        }

        if ($filetouse == "")
        {
            $dataC = false;
            $filetouse = $pagename;
        }

        if ((!isset($filetouse) || $filetouse == "") && (!isset($pagename) || $pagename == ""))
        {
            $dataC = true;
	    show_message("That page or module could not be found");
        }
    } 
    else 
    {
        if ($check['id'] != -1)
        {
            $dataC = true;
            $dbpage = false;
	    show_message("You do not have the required permissions to view that page");
        }
        else
        {
            $query = $_SERVER['QUERY_STRING'];
            $redirectpage = str_replace("page=", "", $query);
            header("Location: index.php?page=logon&redirect=$redirectpage");
        }
    }
/********************************************End Content Generation*****************************************/
?>