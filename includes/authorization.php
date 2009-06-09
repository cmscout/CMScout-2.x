<?php
/**************************************************************************
    FILENAME        :   authorization.php
    PURPOSE OF FILE :   Class for user authorization
    LAST UPDATED    :   31 May 2006
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
//require_once ('../config.php');
class auth
{
	// CHANGE THESE VALUES TO REFLECT YOUR SERVER'S SETTINGS
	var $dbhost;	// Change this to the proper DB HOST
	var $dbusername;	// Change this to the proper DB USERNAME
	var $dbpassword ;	// Change this to the proper DB USER PASSWORD
	var $dbname;	// Change this to the proper DB NAME
    var $dbprefix;

	function set_cookie($username) 
    {
		global $config;
        
		$uids = "";
 		$cookiename = $config['cookiename'];
		setcookie($cookiename, "", 0);
        
		$uids = md5($_SERVER['REMOTE_ADDR'] . $username . time());
		$cookieinfo = $uids;
        
		setcookie ($cookiename, $cookieinfo, time() + $config['session_length']);
        
		return $uids;
	}
	
	function reset_cookie($username, $uid) 
    {
		global $config;
        
 		$cookiename = $config['cookiename'];
		$cookieinfo = $uid;
        
		setcookie ($cookiename, $cookieinfo, time() + $config['session_length']);
        
		return true;
	}
	
	function read_cookies() 
    {
		global $config;
        
 		$info = "";
		$cookiename = $config['cookiename'];
        
        if(isset($_COOKIE[$cookiename]))
        {
            $info = $_COOKIE[$cookiename];
     		return $info;
        }
	}//read_cookies
	
	function get_active() 
    {
		global $config, $data;
        
        $sql = $data->select_query("onlineusers", "WHERE isbot=0");
		$onlineuser = array();
		$onlineuser[0] = 0;
		$onlineuser[1] = 0;
        while ($temp = $data->fetch_array($sql))
        {      
            $timediff = time() - $temp['lastupdate'];
            if ($timediff >= $config['session_length']) 
			{
				$id = $temp['uid'];
				$data->delete_query("onlineusers", "uid='$id'", "", "", false);                
			} 
            elseif ($timediff >= $config['activetime']) 
			{
				$id = $temp['uid'];
				$data->update_query("onlineusers", "isactive=0", "uid='$id'", "", "", false);
			} 
            elseif ($timediff <= $config['activetime']) 
			{
		        if ($temp['isguest'] != 1)
                {
                    $onlineuser[0]++;
                    $temp2 = $data->select_fetch_one_row("users", "WHERE uname='{$temp['uname']}'", "id");
                    $temp['id'] = $temp2['id'];
	    			$onlineuser[] = $temp;
                }
                else
                {
                    $onlineuser[1]++;
                }
			} 
        }
		return $onlineuser;
	}
	
	function logout() 
    {
		global $config, $data;
        
 		$cookiename = $config['cookiename'];
		$uid = $this->read_cookies();;
        
		$data->delete_query("onlineusers", "uid='$uid'", "", "", false);
		$data->update_query("users", "uid = ''", "uid='$uid'", "", "", false);
        
		setcookie ($cookiename, "", 0);
		return $this->addguest(0);
	}
    
    function auth() 
    {
        return true;
	} //database
    
	// AUTHENTICATE
	function authenticate($username, $password) 
    {
        global $data;
        
	$username = safesql($username, "text");
	$password = safesql($password, "text");
		$uid = "";
		$olduid = $this->read_cookies();

        $data->delete_query("onlineusers", "uid='$olduid'");

        $result = $data->select_query("users", "WHERE uname=$username AND passwd=$password");
		
        $numrows = $data->num_rows($result);
		$row = $data->fetch_array($result);
        
		$nuid = $this->set_cookie($username);
		$ntime = time();
        
		$uid = $this->read_cookies();
        
		// CHECK IF THERE ARE RESULTS
		// Logic: If the number of rows of the resulting recordset is 0, that means that no
		// match was found. Meaning, wrong username-password combination.
		if ($numrows == 0 || $row['status'] == 0 || $row['status'] == -1) 
        {
			return $this->addguest($numrows > 0 ? ($row['status'] == 1 ? 1 : -1) : 0);
		}
		else 
        {
            $data->delete_query("onlineusers", "uname=$username");
            $data->update_query("users", "uid = '$nuid', prevlogin = lastlogin, lastlogin = $ntime, logincount = logincount + 1", "uname=$username");
            $ip = safesql($_SERVER['REMOTE_ADDR'], "text");
            $data->insert_query("onlineusers", "'$nuid', '{$row['uname']}', $ntime, $ntime, 1, 0, '', 0, $ip, 0, 0");
			return $row;
		}
	} // End: function authenticate

    function addguest($status)
    {
        global $data;
        
	$botlist = array(   
			"Teoma",                   
			"alexa",
			"froogle",
			"inktomi",
			"looksmart",
			"URL_Spider_SQL",
			"Firefly",
			"NationalDirectory",
			"Ask Jeeves",
			"TECNOSEEK",
			"InfoSeek",
			"WebFindBot",
			"girafabot",
			"crawler",
			"www.galaxy.com",
			"Googlebot",
			"Scooter",
			"Slurp",
			"appie",
			"FAST",
			"WebBug",
			"Spade",
			"ZyBorg",
			"rabaz",
			"msnbot");

	$botdetect = 0;

	foreach($botlist as $bot) 
	{
		if(ereg($bot, $_SERVER['HTTP_USER_AGENT'])) 
		{
			if($bot == "Googlebot") 
			{
				if (substr($REMOTE_HOST, 0, 11) == "216.239.46.") $bot = "Googlebot Deep Crawl";
				elseif (substr($REMOTE_HOST, 0,7) == "64.68.8") $bot = "Google Freshbot";
			}
			elseif($bot == "Slurp") 
			{
				$bot = "Yahoo! Slurp";
			}
			elseif($bot == "msnbot") 
			{
				$bot = "MSNBot";
			}
			$botdetect = 1;
			break;
		}
	} 
	
        $username = !$botdetect ? "Guest" : $bot;
        $nuid = $this->set_cookie($username);
	$ntime = time();
        
	$username = safesql($username, "text");
        $ip = safesql($_SERVER['REMOTE_ADDR'], "text");	
	$data->delete_query("onlineusers", "uid='$nuid'");
	if ($botdetect == 1)
	{
		$data->delete_query("onlineusers", "uname='$bot'");
	}
	if (!$data->num_rows($data->select_query("onlineusers", "WHERE uid='$nuid'")))
	{
		$data->insert_query("onlineusers", "'$nuid', $username, '$ntime', '$ntime', 1, 0, '', 0, $ip, 1, $botdetect");
	}
        
        $check['id'] = -1;
        $check['bot'] = $botdetect;
        $check['uname'] = !$botdetect ? "Guest" : $bot;
        $check['team'] = "Guest";
        $check['uid'] = $nuid;
        $check['status'] = $status == 1 ? 0 : ($status == -1 ? -1 : 1);
        return $check;
    }

	// PAGE CHECK
	// This function is the one used for every page that is to be secured. This is not the same one
	// used in the initial login screen
	function page_check() 
    {
        global $data;
    
		$cookiestuff = 
		
		$uid = $this->read_cookies();
		$ok = false;
		$error = 0;
    
        if(isset($uid))
        {
            $result = $data->select_query("onlineusers", "WHERE uid='$uid'");
            
            
            $numrows = $data->num_rows($result);
            $row = $data->fetch_array($result);

            $username = $row['uname'];

            if ($numrows != 0) 
            { 
                $ok = true;
            }
            else
            {
                $ok = false;
                $error = 1;
            }            

            // CHECK IF THERE ARE RESULTS
            // Logic: If the number of rows of the resulting recordset is 0, that means that no
            // match was found. Meaning, wrong username-password combination.
            if (!$ok) 
            {
                $data->delete_query("onlineusers", "uid='$uid'");
                return $this->addguest();
            }
            elseif ($ok && !$row['isguest'])
            {
                $ntime = time();
                $data->update_query("onlineusers", "lastupdate = '$ntime', isactive = 1, pages = pages + 1", "uid = '$uid'");
                $bla = $this->reset_cookie($username, $uid);
                $sql = $data->select_query("users", "WHERE uid='$uid'");
                return $data->fetch_array($sql);
            }
            else
            {
                $ntime = time();
                $data->update_query("onlineusers", "lastupdate = '$ntime', isactive = 1, pages = pages + 1", "uid = '$uid'");
                
                $bla = $this->reset_cookie($username, $uid);
                $check['uname'] =$username;
                $check['id'] = -1;
                $check['team'] = "Guest";
                $check['uid'] = $uid;
		 $check['bot'] = $row['isbot'];
                return $check;
            }
        }
        else
        {
            return $this->addguest(1);
        }
	} // End: function page_check
	
} // End: class auth
?>