<?php
$upgrader = false;
$bit = "./";
$skipUser = true;
include("common.php");

$type = $_GET['type'];
switch ($type)
{
    case 'image':
        $browserType = "Image";
        break;
    case 'file':
        $browserType = "Content";
        break;
}

$tpl->assign("browserTitle", $browserType);

if ($type == "image")
{
    $quer = $data->select_query("album_track", "WHERE allowed = 1 AND trash=0 ORDER BY album_name ASC");
    $numalbum = $data->num_rows($quer);
    $albums = array();

    while ($temp = $data->fetch_array($quer))
    {
        $temp['photos'] = $data->select_fetch_all_rows($temp['numphotos'], "photos", "WHERE album_id = {$temp['ID']} AND allowed = 1");
        $albums[] = $temp;

    }
    $tpl->assign('albums', $albums);
    $tpl->assign('numalbum', $numalbum);
}
else
{
    $albums = $data->select_fetch_all_rows($numalbum, "album_track", "WHERE allowed = 1 AND trash=0 ORDER BY album_name ASC");

    $tpl->assign('albums', $albums);
    $tpl->assign('numalbum', $numalbum);
    
    $articles = $data->select_fetch_all_rows($numart, "patrol_articles", "WHERE allowed = 1 AND trash=0 ORDER BY title ASC");

    $tpl->assign('articles', $articles);
    $tpl->assign('numart', $numart);

    $events = $data->select_fetch_all_rows($numevents, "calendar_items", "WHERE allowed = 1 AND trash=0 AND detail != '' ORDER BY summary ASC");

    $tpl->assign('events', $events);
    $tpl->assign('numevents', $numevents);
    
    $downloads = $data->select_fetch_all_rows($numdown, "downloads", "WHERE allowed = 1 AND trash=0 ORDER BY name ASC");

    $tpl->assign('downloads', $downloads);
    $tpl->assign('numdown', $numdown);
    
    $newsitems = $data->select_fetch_all_rows($numnews, "newscontent", "WHERE allowed = 1 AND trash=0 ORDER BY title ASC");

    $tpl->assign('newsitems', $newsitems);
    $tpl->assign('numnews', $numnews);
    
    $pollitems = $data->select_fetch_all_rows($numpolls, "polls", "WHERE allowed = 1 AND trash=0 ORDER BY question ASC");

    $tpl->assign('pollitems', $pollitems);
    $tpl->assign('numpolls', $numpolls);
    
    $content = $data->select_fetch_all_rows($numcontent, "static_content", "WHERE type = 0 AND trash=0 ORDER BY friendly ASC");

    $tpl->assign('content', $content);
    $tpl->assign('numcontent', $numcontent);

}
$tpl->display('browser.tpl');
?>