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
    $mainpageauth['content'] = pageauth('content', "access");
    $mainpageauth['articles'] = pageauth('patrolart', "access");
    $mainpageauth['events'] = pageauth('events', "access");
    $mainpageauth['news'] = pageauth('news', "access");
    $mainpageauth['photo'] = pageauth('photo', "access");
    $mainpageauth['poll'] = pageauth('poll', "access");
    
    $tpl->assign("mainpageauth", $mainpageauth);

    $mainpageaddauth['content'] = pageauth('content', "add");
    $mainpageaddauth['articles'] = pageauth('patrolart', "add");
    $mainpageaddauth['events'] = pageauth('events', "add");
    $mainpageaddauth['news'] = pageauth('news', "add");
    $mainpageaddauth['photo'] = pageauth('photo', "add");
    $mainpageaddauth['poll'] = pageauth('poll', "add");
    
    $tpl->assign("mainpageaddauth", $mainpageaddauth);

    $mainpageeditauth['content'] = pageauth('content', "edit");
    $mainpageeditauth['articles'] = pageauth('patrolart', "edit");
    $mainpageeditauth['events'] = pageauth('events', "edit");
    $mainpageeditauth['news'] = pageauth('news', "edit");
    $mainpageeditauth['photo'] = pageauth('photo', "edit");
    $mainpageeditauth['poll'] = pageauth('poll', "edit");
    
    $tpl->assign("mainpageeditauth", $mainpageeditauth);
    
    $result = $data->select_query("static_content", "WHERE type=0 AND trash=0 ORDER BY friendly ASC");
    
    $content = array();
    $numcontent = $data->num_rows($result);
    while ($temp = $data->fetch_array($result))
    {
        $temp['size'] = strlen($temp['content']);
        $content[] = $temp;
    }
    
    $tpl->assign('numcontent', $numcontent);
    $tpl->assign('content', $content);


    if (pageauth("patrolart", "limit")) 
    {
        $patrol = group_sql_list_id("patrol", "OR", true);
        $result = $data->select_query("patrol_articles", "WHERE ($patrol) AND trash=0 ORDER BY date_post DESC");
    } 
    else 
    {
        $result = $data->select_query("patrol_articles", "WHERE trash=0 ORDER BY date_post DESC");
    }
    
    $content = array();
    $numcontent = $data->num_rows($result);
    while ($temp = $data->fetch_array($result))
    {
        $temp['size'] = strlen($temp['detail']);
        $content[] = $temp;
    }
    
    $tpl->assign('numarticles', $numcontent);
    $tpl->assign('articles', $content);

    $result = $data->select_query("calendar_items", "WHERE trash=0 ORDER BY startdate ASC");
    
    $content = array();
    $numcontent = $data->num_rows($result);
    while ($content[] = $data->fetch_array($result));
    
    $tpl->assign('numevents', $numcontent);
    $tpl->assign('events', $content);

    $result = $data->select_query("newscontent", "WHERE trash=0 ORDER BY title DESC");
    
    $content = array();
    $numcontent = $data->num_rows($result);
    while ($temp = $data->fetch_array($result))
    {
        $temp['size'] = strlen($temp['news']);
        $content[] = $temp;
    }
    
    $tpl->assign('numnews', $numcontent);
    $tpl->assign('news', $content);

    if (pageauth("photo", "limit")) 
    {
        $patrollist = group_sql_list_normal("patrol", "OR");
        $result = $data->select_query("album_track", "WHERE $patrollist AND trash=0 ORDER BY album_name ASC");
    } 
    else 
    {
        $result = $data->select_query("album_track", "WHERE trash=0 ORDER BY album_name ASC");
    }
    
    $content = array();
    $numcontent = $data->num_rows($result);
    while ($content[] = $data->fetch_array($result));
    
    $tpl->assign('numalbums', $numcontent);
    $tpl->assign('albums', $content);

    $result = $data->select_query("polls", "WHERE trash=0 ORDER BY date_start ASC");
    
    $content = array();
    $numcontent = $data->num_rows($result);
    while ($content[] = $data->fetch_array($result));
    
    $tpl->assign('numpolls', $numcontent);
    $tpl->assign('polls', $content);



    $filetouse='admin_contentManager.tpl';
}
?>