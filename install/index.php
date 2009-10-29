<?php
/**************************************************************************
    FILENAME        :   install.php
    PURPOSE OF FILE :   Installs CMScout
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
require_once("../includes/Smarty.class.php");
require_once("../includes/functions.php");
$version = "2.08";
$oldversion = "2.07";
$tpl = new smarty();
$tpl->template_dir = "../install/";
$tpl->compile_dir = '../templates_c/';
$tpl->force_compile = true;

$tpl->assign("clean", file_exists("install.php"));
$tpl->assign("migrate", file_exists("migrate.php"));
$tpl->assign("upgrade", file_exists("upgrade.php"));

$tpl->assign("version", $version);
$tpl->assign("oldversion", $oldversion);
$tpl->assign("copyright", "Powered by CMScout &copy;2009 <a href=\"http://www.cmscout.za.net\" title=\"CMScout Group\" target=\"_blank\">CMScout Group</a>");
$tpl->display("index.tpl");
?>