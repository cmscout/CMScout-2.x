<?php
/**************************************************************************
    FILENAME        :   news.php
    PURPOSE OF FILE :   Fetches news items from database
    LAST UPDATED    :   18 December 2006
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

if (isset($_GET['id']))
{
	$id = safesql($_GET['id'], "int");
	$sql = $data->select_query("newscontent","WHERE id=$id AND allowed = 1");
	if ($sql) 
	{
		$newsitem = $data->fetch_array($sql);
        $newsitem['title']  = censor($newsitem['title']);
        $newsitem['news'] = censor($newsitem['news']);
        
        $attachmentTemp = explode('.', $newsitem['attachment']);
        $attachId = safesql($attachmentTemp[0], "int");
        $attachment = array();
        switch($attachmentTemp[1])
        {
            case 'article':
                $temp2 = $data->select_fetch_one_row("patrol_articles", "WHERE ID=$attachId", "ID, title");
                $attachment['name'] = $temp2['title'];
                $attachment['link'] = "index.php?page=patrolarticle&id={$attachId}&menuid={$menuid}&action=view";
                $attachment['type'] = "Article";
                break;
            case 'album':
                $temp2 = $data->select_fetch_one_row("album_track", "WHERE ID=$attachId", "ID, album_name");
                $attachment['name'] = $temp2['album_name'];
                $attachment['link'] = "index.php?page=photos&album={$attachId}&menuid={$menuid}";
                $attachment['type'] = "Photo Album";
                break;
            case 'event':
                $temp2 = $data->select_fetch_one_row("calendar_items", "WHERE id=$attachId", "id, summary");
                $attachment['name'] = $temp2['summary'];
                $attachment['link'] = "index.php?page=calender&item={$attachId}&menuid={$menuid}";
                $attachment['type'] = "Event";
                break;
            case 'download':
                $temp2 = $data->select_fetch_one_row("downloads", "WHERE id=$attachId", "id, name, cat");
                $attachment['name'] = $temp2['name'];
                $attachment['link'] = "index.php?page=downloads&id={$attachId}&action=down&catid={$temp2['cat']}&menuid={$menuid}";
                $attachment['type'] = "Download";
                break;
            case 'news':
                $temp2 = $data->select_fetch_one_row("newscontent", "WHERE id=$attachId", "id, title");
                $attachment['name'] = $temp2['title'];
                $attachment['link'] = "index.php?page=news&id={$attachId}&menuid={$menuid}";
                $attachment['type'] = "News";
                break;
        }
        $newsitem['attachment'] = $attachment;
            
        $edit = ($check['uname'] == $newsitem['owner']) ? true : false;
        $editlink = "index.php?page=mythings&amp;cat=newsitems&amp;action=edit&amp;id={$newsitem['id']}&amp;menuid=$menuid";
		$tpl -> assign('newsitem',$newsitem);
	}   
    $location = $newsitem['title'];

    $dbpage = true;
	$pagename = "newsitem";
}
else
{
    $location = "News";
    $limit = $config['numpage'];
    $start = $_GET['start'] > 0 ? $_GET['start'] : 0;
    
    $sort = $config['newssort'] == 1 ? "ASC" : "DESC";
	$sql = $data->select_query("newscontent","WHERE allowed = 1 AND trash=0", "id");
    $numnews = $data->num_rows($sql);

    //Pagenation working out
    if ($numnews > 0) 
    {
        $num_pages = ceil($numnews / $limit);

        if ($start == 0 && $latest == 1)
        {
            $curr_page = $num_pages;
            $start = (($curr_page-1)*$limit);
        }
        else
        {
            $curr_page = floor($start/$limit) + 1;
        }
        
        if ($curr_page < $num_pages)
        {
            $next = true; 
            $next_start=(($curr_page-1)*$limit) + $limit;
        }
        if ($curr_page > 1) 
        {
            $prev = true; 
            $prev_start=(($curr_page-1)*$limit)- $limit;
        }
    }
    
    $pagelimit = ($numnews-$start) <= $limit ? ($numnews-$start) : $limit;
    $sql = $data->select_query("newscontent","WHERE allowed = 1 AND trash=0 ORDER BY event $sort LIMIT $start, $pagelimit");
	if ($sql) 
	{
		$news = array();
		while ($temp = $data->fetch_array($sql))
        {
            $temp['title']  = censor($temp['title']);
            $temp['news'] = censor($temp['news']);
            $attachmentTemp = explode('.', $temp['attachment']);
            $attachId = safesql($attachmentTemp[0], "int");
            $attachment = array();
            switch($attachmentTemp[1])
            {
		    case 'article':
			$temp2 = $data->select_fetch_one_row("patrol_articles", "WHERE ID=$attachId", "ID, title");
			$attachment['name'] = $temp2['title'];
			$attachment['link'] = "index.php?page=patrolarticle&id={$attachId}&menuid={$menuid}&action=view";
			$attachment['type'] = "Article";
			break;
		    case 'album':
			$temp2 = $data->select_fetch_one_row("album_track", "WHERE ID=$attachId", "ID, album_name");
			$attachment['name'] = $temp2['album_name'];
			$attachment['link'] = "index.php?page=photos&album={$attachId}&menuid={$menuid}";
			$attachment['type'] = "Photo Album";
			break;
		    case 'event':
			$temp2 = $data->select_fetch_one_row("calendar_items", "WHERE id=$attachId", "id, summary");
			$attachment['name'] = $temp2['summary'];
			$attachment['link'] = "index.php?page=calender&item={$attachId}&menuid={$menuid}";
			$attachment['type'] = "Event";
			break;
		    case 'download':
			$temp2 = $data->select_fetch_one_row("downloads", "WHERE id=$attachId", "id, name, cat");
			$attachment['name'] = $temp2['name'];
			$attachment['link'] = "index.php?page=downloads&id={$attachId}&action=down&catid={$temp2['cat']}&menuid={$menuid}";
			$attachment['type'] = "Download";
			break;
		    case 'news':
			$temp2 = $data->select_fetch_one_row("newscontent", "WHERE id=$attachId", "id, title");
			$attachment['name'] = $temp2['title'];
			$attachment['link'] = "index.php?page=news&id={$attachId}&menuid={$menuid}";
			$attachment['type'] = "News";
			break;
            }
            $temp['attachment'] = $attachment;
            $news[] = $temp;
        }
		$tpl -> assign('arcnews',$news);
		$tpl -> assign('numnews',$data->num_rows($sql));
	}
    
    $tpl->assign('numpages', $num_pages);
    $tpl->assign('currentpage', $curr_page);
    $tpl->assign('next', $next);
    $tpl->assign('prev', $prev);
    $tpl->assign('num_per_page', $limit);
    $tpl->assign('next_start', $next_start);
    $tpl->assign('prev_start', $prev_start);
    $tpl->assign("start", $_GET['start']);
	$dbpage = true;
	$pagename = "oldnews";
}
?>