<?php
/**************************************************************************
FILENAME        :   search.php
PURPOSE OF FILE :   Searches database for given term
LAST UPDATED    :   13 February 2006
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
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) 
{
    $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if(isset($_POST['Submit'])) $submit = $_POST['Submit']; else $submit = "";
if (isset($_POST['search'])) $search = $_POST['search'];
$safe_search = safesql( $search, "text");

if ($submit == "Search" || isset($_POST['templatesearch']))
{
    if ( isset($_POST['templatesearch']))
    {
	$search = $_POST['templatesearch'];
	$safe_search = safesql($search, "text");
	$_POST['content'] = 1;
	$_POST['article'] = 1;
	$_POST['forum'] = 1;
	$_POST['news'] = 1;
	$_POST['events'] = 1;
	$_POST['pm'] = 1;
    }
    
    $numcontentresults = 0;
    $contentresults = array();
    if ($_POST['content'] == 1)
    {
        $sql = $data->select_query("static_content", "WHERE (MATCH(friendly, content) AGAINST ($safe_search IN BOOLEAN MODE)) HAVING score > 0.1 ORDER BY score DESC", "*, MATCH(friendly, content) AGAINST ($safe_search) AS score");

        $numcontentresults += $data->num_rows($sql);
        while($temp = $data->fetch_array($sql))
	{
		if ($temp['type'] == 0)
		{
			$temp['itemtype'] = 1;
		}
		elseif ($temp['type'] == 1)
		{
			$sql2 = $data->select_fetch_one_row("groups", "WHERE id = {$temp['pid']}", "teamname");
			$temp['groupname'] = $sql2['teamname'];
			$temp['itemtype'] = 2;
		}
		elseif ($temp['type'] == 2)
		{
			$sql2 = $data->select_fetch_one_row("subsites", "WHERE id = {$temp['pid']}", "name");
			$temp['site'] = $sql2['name'];
			$temp['itemtype'] = 8;
		}
		$contentresults[] = $temp;
	}
    }
    
    if ($_POST['article'] == 1)
    {
        $sql = $data->select_query("patrol_articles", "WHERE (MATCH(title, detail) AGAINST ($safe_search IN BOOLEAN MODE)) HAVING score > 0.1 ORDER BY score DESC", "*, MATCH(title, detail) AGAINST ($safe_search) AS score");

        $numcontentresults += $data->num_rows($sql);
        while($temp = $data->fetch_array($sql))
	{
		$temp['itemtype'] = 3;
		$contentresults[] = $temp;
	}
    }
    
    if ($_POST['forum'] == 1)
    {
        $sql = $data->select_query("forumposts", "WHERE (MATCH(subject, posttext) AGAINST ($safe_search IN BOOLEAN MODE)) HAVING score > 0.1 ORDER BY score DESC", "*, MATCH(subject, posttext) AGAINST ($safe_search) AS score");

        $numforumresults = $data->num_rows($sql);
        $forumresults = array();
        while($temp = $data->fetch_array($sql))
        {
            $repeat = false;
            for ($i=0;$i<count($forumresults);$i++)
            {
                if ($forumresults[$i]['id'] == $temp['topic'])
                {
                    $repeat = true;
                    $repeatnumber = $i;
                    break;
                }                
            }
            if (!$repeat)
            {
                $topic = $data->select_fetch_one_row("forumtopics", "WHERE id={$temp['topic']}");
                $temp2['subject'] = $topic['subject'];
                $temp2['id'] = $topic['id'];
                $temp2['numposts'] = 1;
		$temp2['itemtype'] = 4;
		$temp2['score'] = $temp['score'];
                $forumresults[] = $temp2;
            }
            else
            {
                $forumresults[$i]['numposts']++;
                $numforumresults--;
            }
        }
	
	$contentresults = array_merge($contentresults, $forumresults);
	$numcontentresults += $numforumresults;
    }
    
    if ($_POST['news'] == 1)
    {
        $sql = $data->select_query("newscontent", "WHERE (MATCH(title, news) AGAINST ($safe_search IN BOOLEAN MODE)) HAVING score > 0.1 ORDER BY score DESC", "*, MATCH(title, news) AGAINST ($safe_search) AS score");

        $numcontentresults += $data->num_rows($sql);
        while($temp = $data->fetch_array($sql))
	{
		$temp['itemtype'] = 5;
		$contentresults[] = $temp;
	}
    }
    
    if ($_POST['events'] == 1)
    {
        $sql = $data->select_query("calendar_items", "WHERE (MATCH(summary, detail) AGAINST ($safe_search IN BOOLEAN MODE)) HAVING score > 0.1 ORDER BY score DESC", "*, MATCH(summary, detail) AGAINST ($safe_search) AS score");

        $numeventresults = $data->num_rows($sql);
        $eventresults = array();
        while($temp = $data->fetch_array($sql))
        {
            $groups = unserialize($temp['groups']);
            
            if (is_array($groups))
            {
                $allowed = in_group($groups);
            }
            else
            {
                $allowed = true;
            }
            if ($allowed)
            {
		$temp['itemtype'] = 6;
                $eventresults[] = $temp;
            }
            else
            {
                $numeventresults--;
            }
        }

	$contentresults = array_merge($contentresults, $eventresults);
	$numcontentresults += $numeventresults;
    }
    
    if ($_POST['pm'] == 1 && $check['id'] != -1)
    {
        $username = safesql($check['id'], "int");
        $sql = $data->select_query("pms", "WHERE (MATCH(subject, text) AGAINST ($safe_search IN BOOLEAN MODE)) HAVING score > 0.1 AND (((type=1 OR type=3) AND touser=$username) OR ((type=2 OR type=4) AND fromuser=$username)) ORDER BY score DESC", "*, MATCH(subject, text) AGAINST ($safe_search) AS score");

        $numcontentresults += $data->num_rows($sql);
        while($temp = $data->fetch_array($sql))
	{
		$temp['itemtype'] = 7;
		$contentresults[] = $temp;
	}
    }


    $tpl->assign("results", $numcontentresults);
    $tpl->assign('term_list', stripslashes($search));
    $tpl->assign('terms', HtmlSpecialChars(serialize(search_html_escape_terms(search_split_terms($search)))));
    $tpl->assign("searched", 1);
    $tpl->assign("search", stripslashes($search));
    $tpl->assign("forumcheck", $_POST['forum']);
    $tpl->assign("articlecheck", $_POST['article']);
    $tpl->assign("contentcheck", $_POST['content']);
    $tpl->assign("newscheck", $_POST['news']);
    $tpl->assign("eventscheck", $_POST['events']);
    $tpl->assign("pmcheck", $_POST['pm']);
    
        function cmp($a, $b)
	{
		    if ($a['score'] == $b['score']) {
			return 0;
		    }
		    return ($a['score'] < $b['score']) ? 1 : -1;
	}
	usort($contentresults, "cmp");
   $tpl->assign('contentresults', $contentresults);
   $tpl->assign('numcontentresults', $numcontentresults);

}
$tpl->assign("editFormAction", $editFormAction);
$pagename = "search";
$dbpage = true;
$location = "Search";
?>