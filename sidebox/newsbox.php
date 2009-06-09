<?php
/**************************************************************************
    FILENAME        :   newsbox.php
    PURPOSE OF FILE :   SideBox: Fetches news items from database
    LAST UPDATED    :   24 May 2005
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

	$content = $data->select_query("newscontent","WHERE allowed = 1 AND trash=0 ORDER BY event DESC LIMIT {$config['numsidebox']}");
	if ($content) 
    {
		$newsbox = array();
		while ($temp = $data->fetch_array($content))
		{
			$temp['title'] = censor($temp['title']);
			$newsbox[] = $temp;
		}
		$tpl -> assign('newsbox',$newsbox);
        $numnewsbox = $data->num_rows($content);
        $tpl->assign("numnewsbox", $numnewsbox);
        
        $rssuname = safesql(md5($check['uname']), "text");
        if ($data->num_rows($data->select_query("rssfeeds", "WHERE itemid=1 AND type=6 AND uname=$rssuname", "id")))
        {
            $newsboxrss = 1;
        }
        else
        {
            $newsboxrss = 0;
        }  
        $tpl->assign("newsboxrss", $newsboxrss);        
	}
?>