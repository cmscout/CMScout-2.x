<?php
/**************************************************************************
    FILENAME        :   error_handling.php
    PURPOSE OF FILE :   Error Handling
    LAST UPDATED    :   15 August 2006
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
function ErrorHandler($errno, $errstr ,$errfile,$errline, $errcontext) 
{   
   $error_msg = ": $errstr occured in $errfile on line $errline";
   $remote_dbg = "localhost";
   $log_file = "";
   
   $stdlog = true;
   $display = true;
   
   $notify = true;
   $halt_script = true;
   
    switch($errno) 
    {
        case E_USER_NOTICE:
        case E_NOTICE:
                        $halt_script = false; 
                        $notify = false;
                        $stdlog = false;                        
                        $type = "Notice";
                        break;
        case E_USER_WARNING:
        case E_COMPILE_WARNING:
        case E_CORE_WARNING:
        case E_WARNING:
                        $halt_script = false;       
                        $notify = false;                        
                        $stdlog = false;                        
                        $type = "Warning";
                        break;
        case E_USER_ERROR:
        case E_COMPILE_ERROR:
        case E_CORE_ERROR:
        case E_ERROR:
                        $type = "Fatal Error";
                        break;
        case E_PARSE:
                        $type = "Parse Error";
                        break;
        default:
                        $halt_script = false;       
                        $notify = false;                        
                        $stdlog = false;  
                        $type = "Unknown Error";
                        break;
  }

  if($notify) 
  {
   
       $error_msg = $type . $error_msg;
       
       if($display) 
       {
            ?>
            <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
            <html>
                <head>
                    <title>A critical error has occured and CMScout can not continue running</title>
                </head>
                <body>
                    <div align="center"><img src="images/errorlogo.gif" alt="Error" /></div>
                    <div align="center">
                        A critical error has occured and CMScout can not continue running. Please review the logfile for more information regarding this error.<br />
                        Timestamp: <?php echo date("D M j G:i:s T Y"); ?>
                        <?php
                            if (!is_writable("logfile.txt"))
                            {
                            ?>
                        <br /><br />Logfile.txt is not writable, the error is being placed here<br />
                            <?php echo $error_msg; ?>                        
                            <?php
                            }
                        ?>
                    </div>
                </body>
            </html>
            <?php
       }
       if($stdlog) 
       {
            log_error($error_msg);
       }
   }

   if($halt_script) exit -1;
   
   return;
}

function log_error($error)
{
    $error = date("D M j G:i:s T Y") . chr(13) . chr(10) .'---------------------' .chr(13) . chr(10) . $error . chr(13) . chr(10) . "---------------------" . chr(13) . chr(10) . chr(13) . chr(10);
    $logfile = fopen("logfile.txt", "a");
    fwrite($logfile, $error);
    fclose($logfile);
}//log_error

?>