<?php
/**************************************************************************
    FILENAME        :   common.php
    PURPOSE OF FILE :   Includes all required files, configures templates, etc.
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
define("SCOUT_NUKE", 1);
if (!$upgrader)
{
    $bit = $bit == '../' ? $bit : '';
    if (file_exists("{$bit}install/index.php") && (!file_exists("{$bit}config.php") || filesize("{$bit}config.php") == 0))
    {
        header("Location: install/index.php");
    }
    elseif (file_exists("{$bit}install/index.php") && file_exists("{$bit}config.php") && filesize("{$bit}config.php") > 0)
    {
        include("{$bit}config.php");
        if (isset($dbhost))
        {
            trigger_error("Please ensure that the install directory has been deleted before continuing",E_USER_ERROR);
        }
        else
        {
            header("Location: install/index.php");
        }
    }
    elseif (!file_exists("{$bit}install/index.php") && file_exists("{$bit}config.php") && filesize("{$bit}config.php") > 0)
    {
        if (!$limitedStartup)
        {
            require_once ("{$bit}includes/Smarty.class.php");
        }
        require_once ("{$bit}includes/class.phpmailer.php");
        require_once ("{$bit}includes/authorization.php");
        require_once ("{$bit}config.php");
        require_once ("{$bit}includes/db.php");
        require_once ("{$bit}includes/functions.php");
    }
    else
    {
        trigger_error("The configuration file is missing. Normally CMScout would try to install itself then, but it appears that the install file is also missing. Please fix this by either placing the correct configuration file or the install file where it is ment to be.", E_USER_ERROR);
    }
    /********************************************Start Smarty config***************************************************/
    if (!$limitedStartup)
    {
        if (class_exists('Smarty', false))
        {
            class Smarty_Site extends Smarty 
            {
	           function Smarty_Site()
	           {
	           
	                // Class Constructor. These automatically get set with each new instance.
	        
	                $this->Smarty();
	        
	                $this->template_dir = 'templates/';
	                $this->compile_dir = 'templates_c/';
	                $this->config_dir = 'configs/';
	                $this->cache_dir = 'cache/';
	                $this->compile_check = true;
	                
	                $this->caching = false;
	                $this->force_compile = true;
	           }
	        }
          $tpl = new Smarty_Site();
        }
        else
        {
           $tpl = new Smarty();
           $tpl->template_dir = 'templates/';
           $tpl->compile_dir = 'templates_c/';
           $tpl->config_dir = 'configs/';
           $tpl->cache_dir = 'cache/';
           $tpl->compile_check = true;
               
           $tpl->caching = false;
           $tpl->force_compile = true;
        }
    }
    /********************************************End Smarty config***************************************************/
    $data = new database($dbname, $dbhost, $dbusername, $dbpassword, $dbprefix, $dbport);
    $Auth = new auth();
    if ($data->SelectedDB == 0)
    {
        trigger_error(mysql_error(), E_USER_ERROR);
    }
    
    $debug = '';
    $config = read_config();
    
        class mailer extends PHPMailer
        { 
            var $priority = 3;
            var $to_name;
            var $to_email;
            var $From = null;
            var $FromName = null;
            var $Sender = null; 
            
            function mailer()
            {
                global $config;
                
                if($config['smtp'] == 1)
                {
                    $this->Host = $config['smtp_host'];
                    $this->Port = $config['smtp_port'];
                    if($config['smtp_username'])
                    {
                        $this->SMTPAuth = true;
                        $this->Username = $config['smtp_username'];
                        $this->Password = $config['smtp_password'];
                    }
                    $this->Mailer = "smtp";
                }
              
                if(!$this->From)
                {
                    $this->From = $config['sitemail'];
                }
                if(!$this->FromName)
                {
                    $this->FromName = "{$config['troopname']} Website";
                }
                if(!$this->Sender)
                {
                    $this->Sender = $config['sitemail'];
                }
                $this->Priority = $this->priority; 
            }
        }
	
    if (!$limitedStartup)
    {
        if ($config['softdebug'] == 1)
        {
            $starttime = microtime();
            $data->reset_counter();
        }
        
        $cookievalue = md5(time() . 'um');
        $expire = time() + $config['session_length'];
        
        if ($config['gzip'] == 1)
        {
            $tpl->load_filter('output','gzip');
        }
        
        if(!$skipUser)
        {
            $check = $Auth->page_check();
            $user_page_auths = get_user_auths();
            $user_groups = user_groups_id_key_array($check['id']);
            $onlineUserList = get_online_users_array();
            $userIdList = get_user_id_list_array();
        }
        $censorWords = get_censor();
        $scoutlanguage = read_scout_language();
        $tpl->assign("scoutlang", $scoutlanguage);
    }
    $timestamp = time();
}
else
{
    require_once ("config.php");
    require_once ("includes/db.php");
    require_once ("includes/functions.php");
    $data = new database($dbname, $dbhost, $dbusername, $dbpassword, $dbprefix, $dbport);

    $config = read_config();
}
?>