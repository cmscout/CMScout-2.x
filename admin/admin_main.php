<?php
/**************************************************************************
    FILENAME        :   admin_main.php
    PURPOSE OF FILE :   Displays current users. To be expanded to show site stats, etc.
    LAST UPDATED    :   02 October 2006
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
	return;
}
else
{
    $Auth->get_active();
    $sql = $data->select_query("onlineusers", "ORDER BY lastupdate DESC");
    $onlineuser = array();
    $numusers = $data->num_rows($sql);
    
    while($temp = $data->fetch_array($sql))
    {
        $sql2 = $data->select_query("users", "WHERE uname='{$temp['uname']}'");
        $temp2 = $data->fetch_array($sql2);
        $temp['id'] = $temp2['id'];
        $onlineusers[] = $temp;
    };
    
        $newversion = file("http://www.cmscout.co.za/latestversion.txt");
        if (is_array($newversion))
        {
          $comp = strcasecmp(trim($config['version']), trim($newversion[0]));
          if ($comp < 0)
          {
              $tpl->assign("latest", false);
              $tpl->assign("newversion", trim($newversion[0]));
          }
          elseif ($comp > 0)
          {
              $tpl->assign("beyond", true);
              $tpl->assign("newversion", trim($newversion[0]));
          }
          else
          {
              $tpl->assign("latest", true);
              $tpl->assign("newversion", trim($newversion[0]));
          }
          $tpl->assign("cmscoutmessage", trim($newversion[1]));
        }
        else
        {
          $tpl->assign("newversion", false);
        }

    
    $albumsql = $data->select_query("album_track", "WHERE allowed=1 AND trash=0", "id");
    $siteinfo['albums'] = $data->num_rows($albumsql);
    $albumsql = $data->select_query("photos", "WHERE allowed=1", "id");
    $siteinfo['photos'] = $data->num_rows($albumsql);

    $siteinfo['articles'] = $data->num_rows($data->select_query("patrol_articles", "WHERE allowed=1 AND trash=0", "ID"));
    $siteinfo['pages'] = $data->num_rows($data->select_query("static_content", "", "id"));
    
    $siteinfo['numusers'] = $data->num_rows($data->select_query("users", "", "id, uname"));
          
    $siteinfo['posts'] = $data->num_rows($data->select_query("forumposts", "", "id"));
    $siteinfo['topics'] = $data->num_rows($data->select_query("forumtopics", "", "id"));
    
    $groupsql = $data->select_query("groups", "", "id, teamname");
    $numgroups = $data->num_rows($groupsql);
    $groups = array();
    while ($temp = $data->fetch_array($groupsql))
    {
        $groupinfo['name'] = $temp['teamname'];
        $groupinfo['id'] = $temp['id'];
        
        $gusql = $data->select_query("usergroups", "WHERE groupid='{$groupinfo['id']}'");
        $groupinfo['numusers'] = $data->num_rows($gusql);
        
        $groups[] = $groupinfo;
    }
    

    $sql = $data->select_query("members", "WHERE type=0 ORDER BY lastName, firstName ASC");
    $nummembers = $data->num_rows($sql);
    $members = array();
    
    while ($temp = $data->fetch_array($sql))
    {
        if ($temp['type'] == 0)
        {
            $pa = $data->select_fetch_one_row("members", "WHERE id={$temp['fatherId']}");
            $ma = $data->select_fetch_one_row("members", "WHERE id={$temp['motherId']}");
            $temp['relations'] = "Father: <b>" . (isset($pa['firstName']) ? $pa['lastName'] . ', ' . $pa['firstName'] : "Not in System") . "</b><br />Mother: <b>" . (isset($ma['firstName']) ? $ma['lastName'] . ', ' . $ma['firstName'] : "Not in System") . "</b>";
        }
        $members[] = $temp;
    }
    
    $mainpageauth['logfile'] = pageauth('logfile', "access");
    $mainpageauth['menus'] = pageauth('menus', "access");
    $mainpageauth['users'] = pageauth('users', "access");
    $mainpageauth['group'] = pageauth('group', "access");
    $mainpageauth['patrol'] = pageauth('patrol', "access");
    $mainpageauth['subsite'] = pageauth('subsite', "access");
    $mainpageauth['config'] = pageauth('config', "access");
    
    $tpl->assign("mainpageauth", $mainpageauth);
    
    $tpl->assign("members", $members);
    $tpl->assign("nummembers", $nummembers); 
    $tpl->assign("groups", $groups);
    $tpl->assign("numgroups", $numgroups);
    $tpl->assign("stats", $siteinfo);
    $tpl->assign("numusers", $numusers);
    $tpl->assign("onlineusers", $onlineusers);
    $filetouse='admin_main.tpl';
}
?>