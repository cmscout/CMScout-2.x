<?php
/**************************************************************************
    FILENAME        :   activate.php
    PURPOSE OF FILE :   Activates accounts
    LAST UPDATED    :   09 May 2006
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
$bit = "./";
require_once ("{$bit}includes/error_handling.php");
set_error_handler('ErrorHandler');
error_reporting(E_ERROR|E_PARSE);
$upgrader = false;
$limitedStartup = true;
require_once("common.php");

$id = isset($_GET['id']) ? safesql($_GET['id'], "text") : '';
$code = isset($_GET['code']) ? safesql($_GET['code'], "text") : '';

if ($code != '')
{
    if ($data->update_query("users", "status=1, activationcode=0", "id=$id AND activationcode=$code") != 0)
    {
        show_message("Account Activated. You can now log in");
    }
    else
    {
        header("location:index.php");
    }
}
else
{
    $temp = $data->select_fetch_one_row("users", "WHERE id=$id", "id, uname, email");
    $activecode = md5($temp['uname'] . (microtime() + mktime()));

    $safe_active = safesql($activecode, "text");
    $data->update_query("users", "activationcode=$safe_active", "id=$id");

    $email = $data->select_fetch_one_row("emails", "WHERE type='reactivate'");
    $link = "{$config['siteaddress']}activate.php?id={$temp["id"]}&code={$activecode}";
    $cmscoutTags = array("!#uname#!", "!#postuname#!", "!#title#!", "!#type#!", "!#link#!", "!#extract#!", "!#website#!");
    $replacements   = array($temp['uname'], '', "activation link", "activation link", $link, '', $config['troopname']);

    $emailContent = str_replace($cmscoutTags, $replacements, $email['email']); 

    sendMail($temp['email'], $temp['uname'], $config['emailPrefix'] . $email['subject'], $emailContent);
    header("location:index.php");
}

?>