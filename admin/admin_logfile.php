<?php
/**************************************************************************
    FILENAME        :   admin_config.php
    PURPOSE OF FILE :   Manage configuration of site
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
if( !empty($getmodules) )
{
	$module['Configuration']['Logfile viewer'] = "logfile";
    $moduledetails[$modulenumbers]['name'] = "Logfile viewer";
    $moduledetails[$modulenumbers]['details'] = "Easy viewing of the logfile";
    $moduledetails[$modulenumbers]['access'] = "Allowed to view the logfile";
    $moduledetails[$modulenumbers]['add'] = "notused";
    $moduledetails[$modulenumbers]['edit'] = "Allowed to clear the logfile";
    $moduledetails[$modulenumbers]['delete'] = "notused";
    $moduledetails[$modulenumbers]['publish'] = "notused";
    $moduledetails[$modulenumbers]['limit'] = "notused";
    $moduledetails[$modulenumbers]['id'] = "logfile";

	return;
}
else
{
    if ($_GET['action'] == "clear")
    {
        $file = fopen("logfile.txt", "w");
        fclose($file);
        show_admin_message("Logfile cleared", $pagename);
    }
    
    $lines = file("logfile.txt");
    
    $logfileDump = array();
    $date = true;
    $temp = '';
    foreach ($lines as $line)
    {
        $line = trim($line);
        
        if ($line != "---------------------" && $line != "")
        {
            if ($date)
            {
                $temp['date'] = $line;
                $date = false;
            }
            else
            {
                $temp['error'] = $line;
                $date = true;
            }
            if ($date)
            {
                $logfileDump[] = $temp;
            }
        }
    }
    
    $tpl->assign("logfile", $logfileDump);
    
    $filetouse = 'admin_logfile.tpl';
}
?>