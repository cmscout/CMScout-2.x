<?php
/**************************************************************************
    FILENAME        :   rss.php
    PURPOSE OF FILE :   Rss feed for forum
    LAST UPDATED    :   1 June 2006
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
if ($action == "feed")
{
    $rssid = safesql($_GET['uid'], "text");
    $format="%a, %d %b %Y %T %z";
    $timeformat = "%H:%M %b %e, %Y";
    if (substr(PHP_OS,0,3) == 'WIN') 
    {
           $_win_from = array ('%e',  '%T',       '%D');
           $_win_to   = array ('%#d', '%H:%M:%S', '%m/%d/%y');
           $format = str_replace($_win_from, $_win_to, $format);
           $timeformat = str_replace($_win_from, $_win_to, $timeformat);
    }
    
    $timegenerated = strftime($format, $timestamp);
    header("content-type: text/xml");
    
    $type = $_GET['type'];
    
    switch($type)
    {
	case "news" :
		$feedtype = "News";
		break;
	case "forum" :
		$feedtype = "Forum";
		break;
	case "events" :
		$feedtype = "Event";
		break;
	case "articles" :
		$feedtype = "Article";
		break;
	default :
		if (isset($_GET['uid']))
		{
			$feedtype = "Personal";
		}
		else
		{
			$feedtype = "News";
		}
		break;
    }
    
    
    $link =isset($_GET['uid']) ?  $config['siteaddress'] . "index.php?page=rss&amp;action=feed&amp;uid={$_GET['uid']}" : isset($_GET['type']) ?  $config['siteaddress'] . "index.php?page=rss&amp;action=feed&amp;type=$type" :  $config['siteaddress'] . "index.php?page=rss&amp;action=feed";
    echo "<?xml version=\"1.0\"?>
    <rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">
    <channel>
    <atom:link href=\"$link\" rel=\"self\" type=\"application/rss+xml\" />
    <title>{$config['troopname']} $feedtype RSS Feed</title> 
    <link>{$config['siteaddress']}</link> 
    <description>RSS Feed for the {$config['troopname']} website</description> 
    <language>en-us</language> 
    <pubDate>{$timegenerated}</pubDate> 
    <lastBuildDate>{$timegenerated}</lastBuildDate> 
    <generator>CMScout {$config['version']}</generator> 
    <webMaster>{$config['sitemail']} (Webmaster)</webMaster>";
    
    if (isset($_GET['uid']) && !isset($_GET['type']))
    {
        $forums = array();
        $blog = array();
        $articles = array();
        $news = false;
        $calendar = false;
        $rsssql = $data->select_query("rssfeeds", "WHERE uname = $rssid");
        if ($data->num_rows($rsssql))
        {
            while ($rssfeeds = $data->fetch_array($rsssql))
            {
                $itemid = $rssfeeds['itemid'];
		$userid = $rssfeeds['userid'];
                if ($rssfeeds['type'] == 1)
                {
                    $forums[] = $itemid;
                }
                elseif ($rssfeeds['type'] == 2)
                {
                    $blog[] = $itemid;
                }
                elseif ($rssfeeds['type'] == 3)
                {
                    $calendar = true;
                }
                elseif ($rssfeeds['type'] == 4 || $rssfeeds['type'] == 5)
                {
                    $articles[] = $itemid;
                }
                elseif ($rssfeeds['type'] == 6)
                {
                    $news = true;
                }
            }
	    
	    $usergroups = user_groups_id_array($userid);
                   
            $forumlist = sql_list($forums, "forum", "OR");
            $bloglist = sql_list($blog, "`group`", "OR");
            $articlelist = sql_list($articles, "patrol", "OR");
          
            if ($forumlist != "")
            {
                $sql = $data->select_query("forumtopics","WHERE $forumlist ORDER BY lastdate DESC LIMIT {$config['numsidebox']}");

                if ($data->num_rows($sql))
                {
                    while ($temp = $data->fetch_array($sql))
                    {
			$temp2 = $data->select_fetch_one_row("forumauths", "WHERE forum_id={$temp['forum']}");
			$view_forum = unserialize($temp2['view_forum']);
			$read_topics = unserialize($temp2['read_topics']);

			$viewauth = 0;
			$readauth = 0;
			for($i=0;$i<count($usergroups);$i++)
			{                
				$viewauth = $viewauth || $view_forum[$usergroups[$i]];
				$readauth = $readauth || $read_topics[$usergroups[$i]];
			}
			
			if ($viewauth && $readauth)
			{
				$newtopic = $data->num_rows($data->select_query("forumnew","WHERE topic={$temp['id']} AND uid = {$userid}", "id"));
				$temp2 = $data->select_fetch_one_row("forumposts","WHERE topic={$temp['id']} AND dateposted = {$temp['lastdate']}", "posttext");
				$description = truncate(html_decode(strip_tags($temp2['posttext'])), 100);
				$temp['subject'] =  ( $newtopic ? " --New-- " : "") . $temp['subject'];
				$lastdate = strftime($format, $temp['lastdate']);
				$guid = "forum." . $temp['id'];
				echo "
				<item>
				<title>{$temp['subject']} [Forum Post]</title> 
				<guid isPermaLink=\"false\">$guid</guid>
				<link>{$config['siteaddress']}index.php?page=forums&amp;action=topic&amp;t={$temp['id']}&amp;late=1</link> 
				<description>$description</description> 
				<pubDate>{$lastdate}</pubDate> 
				</item>";
			}
                    }
                }
            }
            
            if ($bloglist != "")
            {
                $sql = $data->select_query("patrollog", "WHERE $bloglist AND private != 1 ORDER BY `dateposted` DESC LIMIT {$config['numsidebox']}");
                if ($data->num_rows($sql))
                {
                    while ($temp = $data->fetch_array($sql))
                    {
                        $lastdate = strftime($format, $temp['dateposted']);
                        $description = truncate(html_decode(strip_tags($temp['itemdetails'])), 100);
			$guid = "blog." . $temp['id'];
                        echo "
                        <item>
                        <title>{$temp['title']} [{$rssfeeds['itemid']} Log]</title> 
			<guid isPermaLink=\"false\">$guid</guid>
                        <link>{$config['siteaddress']}index.php?page=patrolpages&amp;patrol={$temp['group']}&amp;content=patrollog</link> 
                        <description>{$description}</description> 
                        <pubDate>{$lastdate}</pubDate> 
                        </item>";
                    }
                }
            }
            
            if ($articlelist != "")
            {
                $sql = $data->select_query("patrol_articles", "WHERE $articlelist AND allowed = 1 AND trash=0 ORDER BY `date_post` DESC LIMIT {$config['numsidebox']}");
                while ($temp = $data->fetch_array($sql))
                {
                    $lastdate = strftime($format, $temp['date_post']);
                    $description = truncate(html_decode(strip_tags($temp['summary'])), 100);
			$guid = "article." . $temp['ID'];
                    if ($temp['patrol'] != 0)
                    {
                        echo "
                        <item>
                        <title>{$temp['title']} [Article]</title>
                        <link>{$config['siteaddress']}index.php?page=patrolpages&amp;patrol={$temp['patrol']}&amp;content=patrolarticle&amp;id={$temp['ID']}&amp;action=view</link>";
                    }
                    else
                    {
                        echo "
                        <item>
                        <title>{$temp['title']} [Article]</title>
                        <link>{$config['siteaddress']}index.php?page=patrolarticle&amp;id={$temp['ID']}&amp;action=view</link>";
                    }
                    echo "
            		<guid isPermaLink=\"false\">$guid</guid>
                    <description>{$description}</description> 
                    <pubDate>{$lastdate}</pubDate> 
                    </item>";
                }
            }
            
            if ($calendar)
            {
                    $monthdate = $timestamp + 2592000;
                    $sql = $data->select_query("calendar_items", "WHERE startdate >= $timestamp AND startdate <= $monthdate  AND trash=0 AND allowed = 1 ORDER BY startdate ASC");
                    
                    while ($temp = $data->fetch_array($sql))
                    {
                        $lastdate = strftime($format, $temp['date_post']);

			$startdate = strftime($timeformat, $temp['startdate']);
			$enddate = strftime($timeformat, $temp['enddate']);
			
                        if ($temp['detail'] != "")
                        {
                            $link = "{$config['siteaddress']}index.php?page=calender&amp;item={$temp['id']}";
                        }
                        else
                        {
                            $link = "{$config['siteaddress']}index.php?page=calender";
                        }
			$guid = "event." . $temp['id'];
                        echo "
                        <item>
                        <title>{$temp['summary']} [Event]</title> 
			<guid isPermaLink=\"false\">$guid</guid>
                        <link>{$link}</link> 
                        <description>Runs from {$startdate} till {$enddate}</description> 
                        <pubDate>{$lastdate}</pubDate> 
                        </item>";
                    }
            }
            
            if ($news)
            {
                $sql = $data->select_query("newscontent", "WHERE allowed = 1  AND trash=0 ORDER BY `event` DESC LIMIT {$config['numsidebox']}");
                while ($temp = $data->fetch_array($sql))
                {
                    $description = truncate(html_decode(strip_tags($temp['news'])), 100);
                    $lastdate = strftime($format, $temp['event']);
			$guid = "news." . $temp['id'];
                    echo "
                    <item>
                    <title>{$temp['title']} [News]</title> 
			<guid isPermaLink=\"false\">$guid</guid>
                    <link>{$config['siteaddress']}index.php?page=news&amp;id={$temp['id']}</link> 
                    <description>{$description}</description> 
                    <pubDate>{$lastdate}</pubDate> 
                    </item>";
                }
            }

        }
        else
        {
                $lastdate = strftime($format, $timestamp);
                echo "
                <item>
                <title>You have not subscribed to any feeds</title> 
		<guid isPermaLink=\"false\">None</guid>
                <link>{$config['siteaddress']}</link> 
                <description>To subscribe you need to login and look out for the RSS logo</description> 
                <pubDate>{$lastdate}</pubDate> 
                </item>";
        }
    }
    else
    {
	$type = $_GET['type'];
        switch($type)
	{
		case "news" :
			$sql = $data->select_query("newscontent", "WHERE allowed = 1 ORDER BY `event` DESC LIMIT {$config['numsidebox']}");
			while ($temp = $data->fetch_array($sql))
			{
			    $lastdate = strftime($format, $temp['event']);
			    $description = truncate(html_decode(strip_tags($temp['news'])), 100);
			   $guid = "news." . $temp['id'];
			    echo "
			    <item>
			    <title>{$temp['title']} [News]</title> 
			    <guid isPermaLink=\"false\">$guid</guid>
			    <link>{$config['siteaddress']}index.php?page=news&amp;id={$temp['id']}</link> 
			    <description>{$description}</description> 
			    <pubDate>{$lastdate}</pubDate> 
			    </item>";
			}
			break;
		case "forum" :
			     $sqls = $data->select_query("forums", "", "id");
			     $forums = array();
				while($temp3 = $data->fetch_array($sqls))
				{
					$temp2 = $data->select_fetch_one_row("forumauths", "WHERE forum_id={$temp3['id']}");
					$view_forum = unserialize($temp2['view_forum']);
					$read_topics = unserialize($temp2['read_topics']);

					$viewauth = 0;
					$readauth = 0;
					for($i=0;$i<count($usergroups);$i++)
					{                
						$viewauth = $viewauth || $view_forum[-1];
						$readauth = $readauth || $read_topics[-1];
					}
					
					if ($viewauth && $readauth)
					{
						$forums[] = $temp3['id'];
					}
				}
				 $forumlist = sql_list($forums, "forum", "OR");
			    if ($forumlist != "")
			    {
				$sql = $data->select_query("forumtopics","WHERE $forumlist ORDER BY lastdate DESC LIMIT {$config['numsidebox']}");

				if ($data->num_rows($sql))
				{
				    while ($temp = $data->fetch_array($sql))
				    {
					$temp2 = $data->select_fetch_one_row("forumposts","WHERE topic={$temp['id']} AND dateposted = {$temp['lastdate']}", "posttext");
					$description = truncate(html_decode(strip_tags($temp2['posttext'])), 100);
					$temp['subject'] =  $temp['subject'];
					$temp2 = $data->select_fetch_one_row("forumposts","WHERE topic={$temp['id']} AND dateposted = {$temp['lastdate']}", "posttext");
					$temp2 = $data->select_fetch_one_row("forumposts","WHERE topic={$temp['id']} AND dateposted = {$temp['lastdate']}", "posttext");
					$lastdate = strftime($format, $temp['lastdate']);
					$guid = "forum." . $temp['id'];
					echo "
					<item>
					<title>{$temp['subject']} [Forum Post]</title> 
					<guid isPermaLink=\"false\">$guid</guid>
					<link>{$config['siteaddress']}index.php?page=forums&amp;action=topic&amp;t={$temp['id']}&amp;late=1</link> 
					<description>$description</description> 
					<pubDate>{$lastdate}</pubDate> 
					</item>";
				    }
				}
			    }
			break;
		case "events" :
			    $monthdate = $timestamp + 2592000;
			    $sql = $data->select_query("calendar_items", "WHERE startdate >= $timestamp AND startdate <= $monthdate AND allowed = 1 AND trash=0 ORDER BY startdate ASC");
			    
			    while ($temp = $data->fetch_array($sql))
			    {
				$lastdate = strftime($format, $temp['date_post']);

				$startdate = strftime($timeformat, $temp['startdate']);
				$enddate = strftime($timeformat, $temp['enddate']);
				
				if ($temp['detail'] != "")
				{
				    $link = "{$config['siteaddress']}index.php?page=calender&amp;item={$temp['id']}";
				}
				else
				{
				    $link = "{$config['siteaddress']}index.php?page=calender";
				}
				$guid = "event." . $temp['id'];
				echo "
				<item>
				<title>{$temp['summary']} [Event]</title> 
				<guid isPermaLink=\"false\">$guid</guid>
				<link>{$link}</link> 
				<description>Runs from {$startdate} till {$enddate}</description> 
				<pubDate>{$lastdate}</pubDate> 
				</item>";
			    }
			break;
		case "articles" :
			$sql = $data->select_query("patrol_articles", "WHERE allowed = 1 AND trash=0 ORDER BY `date_post` DESC LIMIT {$config['numsidebox']}");
			while ($temp = $data->fetch_array($sql))
			{
			    $lastdate = strftime($format, $temp['date_post']);
			    $description = truncate(html_decode(strip_tags($temp['summary'])), 100);
				$guid = "article." . $temp['ID'];
			    if ($temp['patrol'] != 0)
			    {
				echo "
				<item>
				<title>{$temp['title']} [Article]</title>
				<link>{$config['siteaddress']}index.php?page=patrolpages&amp;patrol={$temp['patrol']}&amp;content=patrolarticle&amp;id={$temp['ID']}&amp;action=view</link>";
			    }
			    else
			    {
				echo "
				<item>
				<title>{$temp['title']} [Article]</title>
				<link>{$config['siteaddress']}index.php?page=patrolarticle&amp;id={$temp['ID']}&amp;action=view</link>";
			    }
			    echo "
				<guid isPermaLink=\"false\">$guid</guid>
			    <description>{$description}</description> 
			    <pubDate>{$lastdate}</pubDate> 
			    </item>";
			}
			break;
		default :
			$sql = $data->select_query("newscontent", "WHERE allowed = 1 ORDER BY `event` DESC LIMIT {$config['numlatest']}");
			while ($temp = $data->fetch_array($sql))
			{
			    $lastdate = strftime($format, $temp['event']);
			    $description = truncate(html_decode(strip_tags($temp['news'])), 100);
			   $guid = "news." . $temp['id'];
			    echo "
			    <item>
			    <title>{$temp['title']} [News]</title> 
			    <guid isPermaLink=\"false\">$guid</guid>
			    <link>{$config['siteaddress']}index.php?page=news&amp;id={$temp['id']}</link> 
			    <description>{$description}</description> 
			    <pubDate>{$lastdate}</pubDate> 
			    </item>";
			}
			break;
	}
    }
    echo "
    </channel>
    </rss>";
    exit;
}
elseif ($action == "add")
{
    $type = safesql($_GET['type'], "int");
    $id = safesql($_GET['id'], "text");
    $uname = safesql(md5($check['uname']), "text");
    $data->insert_query("rssfeeds", "NULL, $id, $type, $uname, {$check['id']}");

    switch ($_GET['type'])
    {
        case 1:
                echo "<script>window.location='index.php?page=forums&menuid={$menuid}';</script>";
                break;
        case 2:
                echo "<script>window.location='index.php?page=patrolpages&patrol={$_GET['id']}&content=patrollog&menuid={$menuid}';</script>";
                break;
        case 3:
                echo "<script>window.location='index.php?page=calender&menuid={$menuid}';</script>";
                break;
        case 4:
                echo "<script>window.location='index.php?page=patrolarticle&menuid={$menuid}';</script>";
                break;
        case 5:
                echo "<script>window.location='index.php?page=patrolpages&patrol={$_GET['id']}&content=patrolarticle&menuid={$menuid}';</script>";
                break;
        case 6:
            echo "<script>window.location='index.php';</script>";
            break;
    }
    exit;
}
elseif ($action == "delete")
{
    $type = safesql($_GET['type'], "int");
    $id = safesql($_GET['id'], "text");
    $uname = safesql(md5($check['uname']), "text");
    $data->delete_query("rssfeeds", "itemid = $id AND type = $type AND uname=$uname");

    switch ($_GET['type'])
    {
        case 1:
                echo "<script>window.location='index.php?page=forums&menuid={$menuid}';</script>";
                break;
        case 2:
                echo "<script>window.location='index.php?page=patrolpages&patrol={$_GET['id']}&content=patrollog&menuid={$menuid}';</script>";
                break;
        case 3:
                echo "<script>window.location='index.php?page=calender&menuid={$menuid}';</script>";
                break;
        case 4:
                echo "<script>window.location='index.php?page=patrolarticle&menuid={$menuid}';</script>";
                break;
        case 5:
                echo "<script>window.location='index.php?page=patrolpages&patrol={$_GET['id']}&content=patrolarticle&menuid={$menuid}';</script>";
                break;
        case 6:
            echo "<script>window.location='index.php';</script>";
            break;
    }
    exit;
}
else
{
    echo "<script>history.go(-1);</script>";
    exit;
}
?>