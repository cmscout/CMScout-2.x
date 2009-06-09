<?php
/**************************************************************************
    FILENAME        :   menu.php
    PURPOSE OF FILE :   Builds the menu
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
//Build menu
$menu = array();
$menu['left'] = array();
$menu['right'] = array();
$menu['top'] = array();

if (!$config['disablesite'])
{
    $catnum['left'] = 0;
    $catnum['right'] = 0;
    $catnum['top'] = 0;

    $menuid = isset($_GET['menuid']) ? $_GET['menuid'] : 0;
    $tpl->assign("menuid", $menuid);

    $catsql = $data->select_query("menu_cats", "WHERE published=1 ORDER BY Position ASC");
    if ($data->num_rows($catsql) > 0) 
    {
        $staticsql = $data->select_query("static_content", "WHERE trash=0", "id");
        $staticContent = array();
        while ($temp = $data->fetch_array($staticsql))
        {
            $staticContent[$temp['id']] = 1;
        }
        $staticsql = $data->select_query("functions", "WHERE active=1", "id, code, filetouse, mainmodule");
        $functionList = array();
        while ($temp = $data->fetch_array($staticsql))
        {
            $functionList[$temp['id']]['code'] = $temp['code'];
            $functionList[$temp['id']]['filetouse'] = $temp['filetouse'];
            $functionList[$temp['id']]['mainmodule'] = $temp['mainmodule'];
        }
        $staticsql = $data->select_query("subsites", "", "id");
        $subsiteList = array();
        while ($temp = $data->fetch_array($staticsql))
        {
            $subsiteList[$temp['id']] = 1;
        }        
        $staticsql = $data->select_query("patrol_articles", "WHERE allowed=1 AND trash=0", "ID");
        $articleList = array();
        while ($temp = $data->fetch_array($staticsql))
        {
            $articleList[$temp['ID']] = 1;
        }
        $staticsql = $data->select_query("groups", "WHERE ispublic=1", "id");
        $groupList = array();
        while ($temp = $data->fetch_array($staticsql))
        {
            $groupList[$temp['id']] = 1;
        }
        
        while ($menucats = $data->fetch_array($catsql))
        {
            $show = 0;
            if ($check['id'] != "-1")
            {
                $groups = unserialize($menucats['groups']);
                $usergroups = user_groups_id_array($check['id']);
                for($i=0;$i<count($usergroups);$i++)
                {
                    if($groups[$usergroups[$i]] == 1)
                    {
                        $show = 1;
                        break;
                    }
                }
            }
            else
            {
                $show = 0;
            }
            $side = $menucats['side'];
            if ($menucats['showwhen'] == 0 || ($menucats['showwhen'] == 1 && $show == 1) || ($menucats['showwhen'] == 2 && $check['id'] == "-1"))
            {
                if ($data->num_rows($data->select_query("menu_items", "WHERE cat='{$menucats['id']}' AND parent = 0"))>0)
                {
                    $menu[$side][$catnum[$side]]['name'] = $menucats['name'];
                    $menu[$side][$catnum[$side]]['showhead'] = $menucats['showhead'];
    
                    $itemsql = $data->select_query("menu_items", "WHERE cat = '{$menucats['id']}' AND parent = 0 ORDER BY pos ASC");
                    $itemnum = 0;
                                   
                    if ($data->num_rows($itemsql) > 0) 
                    {                       
                        while ($items = $data->fetch_array($itemsql))
                        {
                            $subitemnum = 0;
                            if ($menuid == $items['id'] || $templateinfo['always_show_submenu'] == true || $menucats['expanded'] == 1)
                            {
                                if ($menuid != "" && $templateinfo['always_show_submenu'] == false && $menucats['expanded'] == 0)
                                {
                                    $qparent = safesql($menuid, "int");
                                    $parentsql = $data->select_query("menu_items", "WHERE parent = $qparent ORDER BY pos ASC");
                                    $subitemnum = 0;
                                }
                                if ($templateinfo['always_show_submenu'] == true || $menucats['expanded'] == 1)
                                {
                                    $parentsql = $data->select_query("menu_items", "WHERE parent = {$items['id']} ORDER BY pos ASC");
                                }
                                while ($subitems = $data->fetch_array($parentsql))
                                { 
                                    switch($subitems['type'])
                                    {
                                        case 1:
                                            if ($staticContent[$subitems['item']])
                                            {
                                                $menu[$side][$catnum[$side]]['items'][$itemnum]['subitem'][$subitemnum]['type'] = 1;
                                                $menu[$side][$catnum[$side]]['items'][$itemnum]['subitem'][$subitemnum]['name'] = $subitems['name'];
                                                $menu[$side][$catnum[$side]]['items'][$itemnum]['subitem'][$subitemnum]['link'] = $subitems['item'] . "&amp;type=static";
                                                $menu[$side][$catnum[$side]]['items'][$itemnum]['subitem'][$subitemnum]['parent'] = $subitems['parent'];
                                                $menu[$side][$catnum[$side]]['items'][$itemnum]['subitem'][$subitemnum]['target'] = $subitems['target'];
                                            }
                                            else
                                            {
                                                $subitemnum--;
                                            }
                                            break;
                                        case 2:
                                            if (isset($functionList[$subitems['item']]['code']))
                                            { 
                                                $menu[$side][$catnum[$side]]['items'][$itemnum]['subitem'][$subitemnum]['type'] = 1;
                                                $menu[$side][$catnum[$side]]['items'][$itemnum]['subitem'][$subitemnum]['name'] = $subitems['name'];
                                                $menu[$side][$catnum[$side]]['items'][$itemnum]['subitem'][$subitemnum]['link'] = $functionList[$subitems['item']]['code'];
                                                $menu[$side][$catnum[$side]]['items'][$itemnum]['subitem'][$subitemnum]['parent'] = $subitems['parent'];
                                                $menu[$side][$catnum[$side]]['items'][$itemnum]['subitem'][$subitemnum]['target'] = $subitems['target'];
                                            }
                                            else
                                            {
                                                $subitemnum--;
                                            }
                                            break;
                                        case 3:
                                            if (isset($functionList[$subitems['item']]['code']))
                                            {
                                                $menus = false;
                                                $sub =true;
						if($data->num_rows($data->select_query("functions", "WHERE active = 1 AND code = '{$functionList[$items['item']]['mainmodule']}'")) > 0)
					       {
							if ($functionList[$subitems['item']]['filetouse'] != "" && file_exists("sidebox/{$functionList[$subitems['item']]['filetouse']}".$phpex))
							{
							    include_once("sidebox/{$functionList[$subitems['item']]['filetouse']}".$phpex);
							}
							if (!$menus)
							{
							    $menu[$side][$catnum[$side]]['items'][$itemnum]['subitem'][$subitemnum]['type'] = 4;
							    $menu[$side][$catnum[$side]]['items'][$itemnum]['subitem'][$subitemnum]['name'] = $subitems['name'];
							    $menu[$side][$catnum[$side]]['items'][$itemnum]['subitem'][$subitemnum]['link'] = $functionList[$subitems['item']]['code'];
							}
						}
                                            }
                                            else
                                            {
                                                $subitemnum--;
                                            }
                                            break;
                                        case 4:
                                            if ($subsiteList[$subitems['item']])
                                            {
                                                $menu[$side][$catnum[$side]]['items'][$itemnum]['subitem'][$subitemnum]['type'] = 2;
                                                $menu[$side][$catnum[$side]]['items'][$itemnum]['subitem'][$subitemnum]['name'] = $subitems['name'];
                                                $menu[$side][$catnum[$side]]['items'][$itemnum]['subitem'][$subitemnum]['link'] = "index.php?page=subsite&amp;site={$subitems['item']}&amp;menuid={$subitems['parent']}";
                                                $menu[$side][$catnum[$side]]['items'][$itemnum]['subitem'][$subitemnum]['target'] = $subitems['target'];
                                            }
                                            else
                                            {
                                                $subitemnum--;
                                            }
                                            break;
                                        case 5:
                                            $menu[$side][$catnum[$side]]['items'][$itemnum]['subitem'][$subitemnum]['type'] = 3;
                                            $menu[$side][$catnum[$side]]['items'][$itemnum]['subitem'][$subitemnum]['name'] = $subitems['name'];
                                            $menu[$side][$catnum[$side]]['items'][$itemnum]['subitem'][$subitemnum]['link'] = "http://" . $subitems['item'];
                                            $menu[$side][$catnum[$side]]['items'][$itemnum]['subitem'][$subitemnum]['target'] = $subitems['target'];
                                            break;
                                        case 6:
                                            if ($articleList[$subitems['item']])
                                            {
                                                $menu[$side][$catnum[$side]]['items'][$itemnum]['subitem'][$subitemnum]['type'] = 2;
                                                $menu[$side][$catnum[$side]]['items'][$itemnum]['subitem'][$subitemnum]['name'] = $subitems['name'];
                                                $menu[$side][$catnum[$side]]['items'][$itemnum]['subitem'][$subitemnum]['link'] = "index.php?page=patrolarticle&amp;id={$subitems['item']}&amp;menuid={$subitems['parent']}&amp;action=view";
                                                $menu[$side][$catnum[$side]]['items'][$itemnum]['subitem'][$subitemnum]['parent'] = $subitems['parent'];
                                                $menu[$side][$catnum[$side]]['items'][$itemnum]['subitem'][$subitemnum]['target'] = $subitems['target'];
                                            }
                                            else
                                            {
                                                $subitemnum--;
                                            }
                                            break;
                                        case 7:
                                            if ($groupList[$subitems['item']])
                                            {
                                                $menu[$side][$catnum[$side]]['items'][$itemnum]['subitem'][$subitemnum]['type'] = 2;
                                                $menu[$side][$catnum[$side]]['items'][$itemnum]['subitem'][$subitemnum]['name'] = $subitems['name'];
                                                $menu[$side][$catnum[$side]]['items'][$itemnum]['subitem'][$subitemnum]['link'] = "index.php?page=patrolpages&amp;patrol={$subitems['item']}&amp;menuid={$subitems['parent']}";
                                                $menu[$side][$catnum[$side]]['items'][$itemnum]['subitem'][$subitemnum]['id'] = $subitems['id'];                            
                                                $menu[$side][$catnum[$side]]['items'][$itemnum]['subitem'][$subitemnum]['target'] = $subitems['target'];
                                            }
                                            else
                                            {
                                                $subitemnum--;
                                            }
                                            break;
                                        default:
                                            $subitemnum--;
                                            break;
                                    }
                                    $subitemnum++;                                   
                                }
                            }
                            
                            $menu[$side][$catnum[$side]]['items'][$itemnum]['subitems'] = $subitemnum;
                            
                            switch($items['type'])
                            {
                                case 1:
                                    if ($staticContent[$items['item']])
                                    {
                                        $menu[$side][$catnum[$side]]['items'][$itemnum]['type'] = 1;
                                        $menu[$side][$catnum[$side]]['items'][$itemnum]['name'] = $items['name'];
                                        $menu[$side][$catnum[$side]]['items'][$itemnum]['link'] = $items['item'] . "&amp;type=static";
                                        $menu[$side][$catnum[$side]]['items'][$itemnum]['id'] = $items['id'];
                                        $menu[$side][$catnum[$side]]['items'][$itemnum]['target'] = $items['target'];
                                    }
                                    else
                                    {
                                        $itemnum--;
                                    }
                                    break;
                                case 2:
                                    if (isset($functionList[$items['item']]['code']))
                                    {                                    
                                        $menu[$side][$catnum[$side]]['items'][$itemnum]['type'] = 1;
                                        $menu[$side][$catnum[$side]]['items'][$itemnum]['name'] = $items['name'];
                                        $menu[$side][$catnum[$side]]['items'][$itemnum]['link'] = $functionList[$items['item']]['code'];
                                        $menu[$side][$catnum[$side]]['items'][$itemnum]['id'] = $items['id'];
                                        $menu[$side][$catnum[$side]]['items'][$itemnum]['target'] = $items['target'];
                                    }
                                    else
                                    {
                                        $itemnum--;
                                    }
                                    break;
                                case 3:
                                    if (isset($functionList[$items['item']]['code']))
                                    {
                                        $menus = false;
					if($data->num_rows($data->select_query("functions", "WHERE active = 1 AND code = '{$functionList[$items['item']]['mainmodule']}'")) > 0)
					{
						 if ($functionList[$items['item']]['filetouse'] != "" && file_exists("sidebox/{$functionList[$items['item']]['filetouse']}".$phpex))
						{
						    include_once("sidebox/{$functionList[$items['item']]['filetouse']}".$phpex);
						}
						if (!$menus)
						{
						    $menu[$side][$catnum[$side]]['items'][$itemnum]['type'] = 4;
						    $menu[$side][$catnum[$side]]['items'][$itemnum]['name'] = $items['name'];
						    $menu[$side][$catnum[$side]]['items'][$itemnum]['link'] = $functionList[$items['item']]['code'];
						}
					}
                                    }
                                    else
                                    {
                                        $itemnum--;
                                    }
                                    break;
                                case 4:
                                    if ($subsiteList[$items['item']])
                                    {
                                        $menu[$side][$catnum[$side]]['items'][$itemnum]['type'] = 2;
                                        $menu[$side][$catnum[$side]]['items'][$itemnum]['name'] = $items['name'];
                                        $menu[$side][$catnum[$side]]['items'][$itemnum]['link'] = "index.php?page=subsite&amp;site={$items['item']}&amp;menuid={$items['id']}";
                                        $menu[$side][$catnum[$side]]['items'][$itemnum]['id'] = $items['id'];
                                        $menu[$side][$catnum[$side]]['items'][$itemnum]['target'] = $items['target'];
                                    }
                                    else
                                    {
                                        $itemnum--;
                                    }
                                    break;
                                case 5:
                                    $menu[$side][$catnum[$side]]['items'][$itemnum]['type'] = 3;
                                    $menu[$side][$catnum[$side]]['items'][$itemnum]['name'] = $items['name'];
                                    $menu[$side][$catnum[$side]]['items'][$itemnum]['link'] = "http://" . $items['item'];
                                    $menu[$side][$catnum[$side]]['items'][$itemnum]['id'] = $items['id'];
                                    $menu[$side][$catnum[$side]]['items'][$itemnum]['target'] = $items['target'];
                                    break;
                                case 6:
                                    if ($articleList[$items['item']])
                                    {
                                        $menu[$side][$catnum[$side]]['items'][$itemnum]['type'] = 2;
                                        $menu[$side][$catnum[$side]]['items'][$itemnum]['name'] = $items['name'];
                                        $menu[$side][$catnum[$side]]['items'][$itemnum]['link'] = "index.php?page=patrolarticle&amp;id={$items['item']}&amp;menuid={$items['id']}&amp;action=view";
                                        $menu[$side][$catnum[$side]]['items'][$itemnum]['id'] = $items['id'];                            
                                        $menu[$side][$catnum[$side]]['items'][$itemnum]['target'] = $items['target'];
                                    }
                                    else
                                    {
                                        $itemnum--;
                                    }
                                    break;
                                case 7:
                                    if ($groupList[$items['item']])
                                    {
                                        $menu[$side][$catnum[$side]]['items'][$itemnum]['type'] = 2;
                                        $menu[$side][$catnum[$side]]['items'][$itemnum]['name'] = $items['name'];
                                        $menu[$side][$catnum[$side]]['items'][$itemnum]['link'] = "index.php?page=patrolpages&amp;patrol={$items['item']}&amp;menuid={$items['id']}";
                                        $menu[$side][$catnum[$side]]['items'][$itemnum]['id'] = $items['id'];                            
                                        $menu[$side][$catnum[$side]]['items'][$itemnum]['target'] = $items['target'];
                                    }
                                    else
                                    {
                                        $itemnum--;
                                    }
                                    break;
                                default:
                                    $itemnum--;
                                    break;
                            }
                            $itemnum++;
                        } //End Item While
                    } //End Item If
                    
                    $menu[$side][$catnum[$side]]['numitems'] = $itemnum;
                    $catnum[$side]++;
                } //End If show cat
            } //End Cat While
        }
    } //End Cat If
}

if ($config['disablesite'] || ($catnum['left'] == 0 && $catnum['right'] == 0 && $catnum['top'] == 0))
{  
    $t = $data->fetch_array($data->select_query("functions", "WHERE name = 'Logon Box'"));
    if ($t['filetouse'] != "" && file_exists("sidebox/{$t['filetouse']}".$phpex))
    {
        include_once("sidebox/{$t['filetouse']}".$phpex);
    }
    $menu['left'][0]['name'] = 'Login';
    $menu['left'][0]['showhead'] = 1;
    $menu['left'][0]['items'][0]['type'] = 4;
    $menu['left'][0]['items'][0]['name'] = $items['name'];
    $menu['left'][0]['items'][0]['link'] = $t['code'];
    $menu['left'][0]['numitems'] = 1;
    $catnum['left']=1;
}
$tpl->assign('menu', $menu);
$tpl->assign('nummenucats', $catnum);

?>