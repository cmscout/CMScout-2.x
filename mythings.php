<?php
/**************************************************************************
    FILENAME        :   mythings.php
    PURPOSE OF FILE :   Displays items that a user owns. Allows user to edit those items (And add new items).
    LAST UPDATED    :   24 September 2006
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

/********************************************Check if user is allowed*****************************************/
$uname = $check["uname"];
$pagenum = 1;

if (isset($_GET['cat'])) $cat = $_GET['cat']; else $cat = "";
if (isset($_GET['action'])) $action = $_GET['action'];
if (isset($_GET['id'])) $id = $_GET['id'];
$safe_id = safesql($id, "int");
$groupsqllist = group_sql_list_id("id", "OR", true);     
$teams = array();
$team_query = $data->select_query("groups", "WHERE ($groupsqllist) AND ispublic=1");
$numteams = $data->num_rows($team_query);
while ($teams[] = $data->fetch_array($team_query));


$location = "User Control Panel >> Contributions";  

if ($cat != "" || $action != "")
{
    switch($cat)
    {
        case "album" :
            $pagenum = 2;
            $scriptList['slimbox'] = 1;
            if ($_POST['Submit'] == "Update")
            {
                $group = safesql($_POST['group'], "int");
                $name = safesql($_POST['name'], "text");
                $data->update_query("album_track", "album_name=$name, patrol=$group", "ID = $safe_id");
                show_message("Album updated.", "index.php?page=mythings&cat=album&action=edit&id={$id}&menuid={$menuid}");
            }
            $album = $data->select_fetch_one_row("album_track", "WHERE ID = $safe_id");
            
            if (!user_group_id($check['id'], $album['patrol']) && $album['patrol'] != 0 && $album['patrol'] != -1)
            {
                $temp = $data->select_fetch_one_row("groups", "WHERE id={$album['patrol']}");
                $teams[] = $temp;
                $numteams++;
            }
            
            $sql = $data->select_query("photos", "WHERE album_id = '$id'");
            $numphotos = $data->num_rows($sql);
            $photos = array();
            while ($photos[] = $data->fetch_array($sql));
            
            $location = "Edit " . censor($album['album_name']) . " photo album";
            $tpl->assign("album", $album);
            $tpl->assign("numphotos", $numphotos);
            $tpl->assign("photos", $photos);
            $tpl->assign("photopath", $config["photopath"] . "/");
            
            if($_POST['Submit'] == "Upload Photo")
            {
                if ($_FILES['filename']['name'] == '')
                {
                    show_message("You need to select a file to upload", "index.php?page=mythings&cat=album&action=edit&id={$id}&menuid={$menuid}");
                    exit;
                }
                if (($_FILES['filename']['type'] == 'image/gif') || ($_FILES['filename']['type'] == 'image/jpeg') || ($_FILES['filename']['type'] == 'image/png') || ($_FILES['filename']['type'] == 'image/pjpeg')) 
                {
                    $filestuff = uploadpic($_FILES['filename'], $config['photox'], $config['photoy'], true);
                    $filename = $filestuff['filename'];
                    $desc = $_POST['caption'];
                    $album = $data->fetch_array($data->select_query("album_track", "WHERE ID=$id"));
                    $insert = sprintf("NULL, %s, %s, %s, $timestamp",
                                        safesql($filename, "text"),
                                        safesql($desc, "text"),
                                        safesql($id, "int"));
                    if(confirm('photo') == 1 && $album['allowed'] == 1)
                    {
                        $insert .= ", 0";
                    }
                    else
                    {
                        $insert .= ", 1";
                    }
                    $data->insert_query("photos", $insert, "", "", false) ;
                    if(confirm('photo') == 1 && $album['allowed'] == 1)
                    {
                        $extrabit = "It first needs to be reviewed before it will be visible on the website.";
                        confirmMail("photo", $album);
                    }
                    $data->update_query("users", "numphotos = numphotos + 1", "id='{$check['id']}'");
                    show_message("Your photo has been added. $extrabit", "index.php?page=mythings&cat=album&action=edit&id={$id}&menuid={$menuid}");
                } 
                else
                {
                    show_message("Sorry, we only accept .gif, .jpg, .jpeg or .png images.", "index.php?page=mythings&cat=album&action=edit&id={$id}&menuid={$menuid}");
                }
            }
            elseif ($_POST['Submit'] == "Update Photo") 
            {
                $photoid = safesql($_POST['photoid'],"int");
                
                if ($_FILES['editfilename']['name'] != '') 
                {
                    if (($_FILES['editfilename']['type'] == 'image/gif') || ($_FILES['editfilename']['type'] == 'image/jpeg') || ($_FILES['editfilename']['type'] == 'image/png') || ($_FILES['editfilename']['type'] == 'image/pjpeg')) 
                    {
                        $filestuff = uploadpic($_FILES['editfilename'], $config['photox'], $config['photoy'], true);
                        $filename = safesql($filestuff['filename'], "text");;
                        $desc = safesql($_POST['editcaption'], "text");
                        if(confirm('photo') && $album['allowed'] == 1)
                        {
                            $data->update_query("photos", "filename=$filename, date='$timestamp', caption = $desc, allowed = 0", "ID=$photoid");
                        }
                        else
                        {
                            $data->update_query("photos", "filename=$filename, date='$timestamp', caption = $desc", "ID=$photoid");
                        }
                        if(confirm('photo') == 1 && $album['allowed'] == 1)
                        {
                            $extrabit = "It first needs to be reviewed before it will be visible on the website.";
                            confirmMail("photo", $album);
                        }
                    } 
                    else
                    {
                        show_message("Sorry, we only accept .gif, .jpg, .jpeg or .png images.", "index.php?page=mythings&cat=album&action=edit&id={$id}&menuid={$menuid}");
                    }
                } 
                else
                {
                    $desc = safesql($_POST['editcaption'], "text");
                    if ($desc != '')
                    {
                        $data->update_query("photos", "caption = $desc", "ID='$photoid'");	
                    }
                }
                show_message("Your photo has been updated. $extrabit", "index.php?page=mythings&cat=album&action=edit&id={$id}&menuid={$menuid}");
                $noshow = true;
            }
            
            if($action=="delphoto")
            {
                $pid = $_GET['pid'];
                if ($data->num_rows($data->select_query("album_track",  "WHERE ID = $id")))
                {    
                    $sql = $data->select_query("photos", "WHERE ID=$pid");
                    $photo = $data->fetch_array($sql);
                    unlink($config['photopath'] . '/' . $photo['filename']);
                    $sqlq = $data->delete_query("photos", "ID=$pid AND album_id='$id'", "Albums", "Photo for album $aid deleted by {$uname}");
                    $data->update_query("album_track", "numphotos = numphotos - 1", "ID=$id", "", "", false);
                    header("location: index.php?page=mythings&cat=album&id=$id&menuid=$menuid");
                } 
            }
            elseif ($action == "delete")
            {
                $sqlq = $data->update_query("album_track", "trash=1", "ID=$id");
                if ($sqlq) 
                { 	
                    show_message("Album deleted", "index.php?page=mythings&menuid=$menuid");               
                } 
            } 
            break;
        case "articles":
            if ($action == "edit")
            {
                $pagenum=6;
                $query = $data->select_query("patrol_articles", "WHERE ID=$safe_id");
                $post = $data->fetch_array($query);

                if($post['pic'])
                {
                    $photoid = safesql($post['pic'], "int");
                    $photo = $data->select_fetch_one_row("photos", "WHERE ID=$photoid", "album_id");
                    
                    $selectedAlbumInfo['photos'] = $data->select_fetch_all_rows($selectedAlbumInfo['numphotos'], "photos", "WHERE album_id = {$photo['album_id']} AND allowed = 1");
                    $tpl->assign("selectedAlbumInfo", $selectedAlbumInfo);
                    $tpl->assign("selectedAlbum", $photo['album_id']);
                }
            
                $location = "Edit " . censor($post['title']) . " article";
                
                $quer = $data->select_query("album_track", "WHERE allowed=1 AND trash=0 ORDER BY album_name ASC");
                $numalbum = $data->num_rows($quer);
                $albums = array();
                while ($temp = $data->fetch_array($quer))
                {
                    $temp['photos'] = $data->select_fetch_all_rows($temp['numphotos'], "photos", "WHERE album_id = {$temp['ID']} AND allowed = 1");
                    $albums[] = $temp;
                }
                
                $event = $data->select_fetch_all_rows($numevents, "calendar_items", "WHERE allowed=1 AND trash=0 ORDER BY summary ASC");
                
                $groups = public_group_sql_list_id("id", "OR");    
                if ($groups)
                {    
                    $teams = array();
                    $team_query = $data->select_query("groups", "WHERE ($groups) AND ispublic=1");
                    $numteams = $data->num_rows($team_query);
                    while ($teams[] = $data->fetch_array($team_query));
                }
                else
                {
                    $numteams = 0;
                } 
                
                $post['topics'] = unserialize($post['topics']);
                $post['related'] = unserialize($post['related']);
                
                $tpl->assign('numevents', $numevents);
                $tpl->assign('event', $event);
                $tpl->assign('numalbum', $numalbum);
                $tpl->assign('albums', $albums);
                $tpl->assign("post", $post);
                
                $result = $data->select_query("articletopics", "ORDER BY title ASC", "id, title, groups");
                $numtopics = 0;
                $topics = array();
                while ($temp = $data->fetch_array($result))
                {
                    $topicgroups = unserialize($temp['groups']);

                    if (in_group($topicgroups))
                    {
                        $topics[] = $temp;
                        $numtopics++;
                    }
                }    
                
                $article = $data->select_fetch_all_rows($numarticles, "patrol_articles", "WHERE allowed=1 AND trash=0 AND ID != $safe_id ORDER BY title ASC");
                
                $tpl->assign('numarticles', $numarticles);
                $tpl->assign('article', $article); 
                
                $tpl->assign('numarticles', $numarticles);
                $tpl->assign('article', $article);    
                $tpl->assign('numtopics', $numtopics);
                $tpl->assign('topics', $topics);
                $scriptList['tinyAdv'] = 1;
                $submit=$_POST["Submit"];
                if ($submit == "Submit") 
                {
                    if (validate($_POST['validation']))
                    {
                        
                        $title = safesql($_POST['title'], "text");
                        $photo = safesql($_POST['photo'], "int");
                        $event = safesql($_POST['event'], "int");
                        $story = safesql($_POST['story'], "text", false);
                        $auth = safesql($_POST['auth'], "text");
                        $patrol = safesql($_POST['patrol'], "int");
                        $pic = safesql($_POST['articlephoto'], "int");
                        
                        $allow = confirm('article') ? 0 : 1;

                        $topics = safesql(serialize($_POST['topics']), "text");
                        $order = safesql($_POST['order'], "int");
                        $summary = safesql($_POST['summary'], "text");
                        $related = safesql(serialize($_POST['articles']), "text");

                        $sql = $data->update_query("patrol_articles", "patrol=$patrol, pic=$pic, title=$title, detail=$story, date_post=$timestamp, album_id=$photo, event_id=$event, author=$auth, allowed = $allow, topics=$topics, `order`=$order, summary=$summary, related=$related","ID=$id");	

                        if (confirm('article')) 
                        {
                            $article = $data->select_fetch_one_row("patrol_articles", "WHERE ID=$safe_id");
                            confirmMail("article", $article);
                            $extra = "The administrator needs to republish your article now that you have edited it.";
                        }
                        else $extra = "";
                        
                        show_message("Your Article has been updated. $extra", "index.php?page=mythings&menuid=$menuid"); 

                    }
                    else
                    {
                        show_message("Some of your inputs might not be correct. Please check them again", "index.php?page=mythings&cat=articles&action=edit&id={$id}&menuid={$menuid}", true);
                    }
                } 
                elseif($_POST['preview'] == "Preview Article")
                {       
                    if (validate($_POST['validation']))
                    {    
                        $post['patrol'] = $_POST['patrol'];
                        $post['title'] = $_POST['title'];
                        $post['detail'] = $_POST['story'];
                        $post['album_id'] = $_POST['photo'];
                        $post['event_id'] = $_POST['event'];
                        $post['auth'] = $_POST['auth'];
                        $post['datepost'] = $timestamp;
                        $post['topics'] = $_POST['topics'];
                        $post['order'] = $_POST['order'];
                        $post['summary'] = $_POST['summary'];
                        $post['related'] = $_POST['articles'];   
                        $post['pic'] = $_POST['articlephoto'];
                        
                        if ($post['album_id'] != 0) 
                        { 
                            $album_id = safesql($post['album_id'], "int");
                            $photo = $data->select_fetch_all_rows($number_of_photos, "photos", "WHERE album_id={$album_id}");
                            
                            $tpl->assign("photo", $photo);
                            $tpl->assign("number_of_photos", $number_of_photos);
                        } 

                        if ($post['event_id'] != 0) 
                        { 
                            $eventid = safesql($post['event_id'], "int");
                            $event = $data->select_fetch_one_row("calendar_items", "WHERE id = {$eventid}", "id, summary, startdate, enddate");
                            $tpl->assign("event", $event);
                        } 
              
                        $temp['related'] = '';
                        $num = 1;
                        while (list($articleid, $value) = each($post['related'])) 
                        {
                            $articleid = safesql($articleid, "int");
                            $topicdetail = $data->select_fetch_one_row("patrol_articles", "WHERE ID = $articleid", "title");
                            $temp['related'] .= $topicdetail['title'];
                            if ($num++ < count($post['related'])) $temp['related'] .= ", ";
                        }
                        
                        $post['relatedlist'] = $temp['related'];
                        $tpl->assign('post', $post);

                        $tpl->assign("preview", "true");
                    }
                    else
                    {
                        show_message("Some of your inputs might not be correct. Please check them again", "index.php?page=mythings&cat=articles&action=edit&id={$id}&menuid={$menuid}", true);
                    }
                }
            }
            elseif ($action == "delete")
            {
                $sqlq = $data->update_query("patrol_articles", "trash=1", "ID=$id", "Articles", "Deleted $id");
                if ($sqlq)
                {
                    show_message("Your Article has been deleted.", "index.php?page=mythings&menuid=$menuid");               
                }
            }
            break;
        case "events":
            if ($action == "edit")
            {
                $pagenum = 10;
                $calsql = $data->select_query("calendar_items", "WHERE id = $id");
                $items = $data->fetch_array($calsql);
                
                $startdate = strftime("%Y/%m/%d", $items['startdate']);
                $enddate = strftime("%Y/%m/%d", $items['enddate']);
                
                $groups = group_sql_list_id("id", "OR"); 
                $teams = array();
                $team_query = $data->select_query("groups", "WHERE ($groups) ORDER BY teamname ASC", "id, teamname");
                $numteams = $data->num_rows($team_query);
                while ($teams[] = $data->fetch_array($team_query));

                $patrols = $data->select_fetch_all_rows($numpatrols, "groups", "WHERE ($groups) AND ispatrol = 1 ORDER BY teamname ASC", "id, teamname");
                $groups = group_sql_list_id("patrol", "OR"); 
                $members = $data->select_fetch_all_rows($nummembers, "members", "WHERE ($groups) ORDER BY lastName,firstName ASC", "id, lastName, firstName");
                
                $tpl->assign('teams',$teams);
                $tpl->assign('numteams', $numteams);      
                $tpl->assign('patrols',$patrols);
                $tpl->assign('numpatrols', $numpatrols); 
                $tpl->assign('members',$members);
                $tpl->assign('nummembers', $nummembers);                
                
                $items['groups'] = unserialize($items['groups']);    
                $items['patrols'] = unserialize($items['patrols']);                
                $tpl->assign('item', $items);
                $location = "Edit " . censor($items['summary']) . " event";
                
                $colour = rgb2hex2rgb($items['colour']);

                    $script .= "{literal}function makeTwoChars(inp) {
                            return String(inp).length < 2 ? \"0\" + inp : inp;
                    }

                    function initialiseInputs() {
                            // Clear any old values from the inputs (that might be cachedate by the browser after a page reload)
                            document.getElementById(\"sdate\").value = \"$startdate\";
                            document.getElementById(\"edate\").value = \"$enddate\";
                            // Add the onchange event handler to the start date input
                            document.getElementById(\"sdate\").onchange = setReservationDates;
                            
                    }
                    function setReservationDates(e) {
                            // Check the associatedate datePicker object is available (be safe)
                            if(!(\"sdate\" in datePickerController.datePickers)) {
                                    return;
                            }
                            
                            // Check the value of the input is a date of the correct format
                            var dt = datePickerController.dateFormat(this.value, datePickerController.datePickers[\"sdate\"].format.charAt(0) == \"m\");
                            
                            // If the input's value cannot be parsedate as a valid date then return
                            if(dt == 0) return;

                            // Grab the value set within the endDate input and parse it using the dateFormat method
                            // N.B: The second parameter to the dateFormat function, if TRUE, tells the function to favour the m-d-y date format
                            var edatev = datePickerController.dateFormat(document.getElementById(\"edate\").value, datePickerController.datePickers[\"edate\"].format.charAt(0) == \"m\");

                            // Grab the end date datePicker Objects
                            var edate = datePickerController.datePickers[\"edate\"];

                            edate.setRangeLow( dt );
                            
                            // If theres a value already present within the end date input and it's smaller than the start date
                            // then clear the end date value
                            if(edatev < dt) {
                                    document.getElementById(\"edate\").value = \"\";
                            }
                    }
                    datePickerController.addEvent(window, 'load', initialiseInputs);{/literal}";
                
                $onDomReady .= "var r = new MooRainbow('colourSelector', {
                                    'startColor': [{$colour['red']}, {$colour['green']}, {$colour['blue']}],
                                    'onChange': function(color) {
                                        $('colour').value = color.hex;
                                        $('colour').style.backgroundColor = color.hex;
                                    }
                                });";
                $scriptList['tinyAdv'] = 1;
                $scriptList['datepicker'] = 1;
                $scriptList['mooRainbow'] = 1;
                $submit = $_POST['Submit'];
                if ($submit == "Update Item") 
                {
                    if (validate($_POST['validation']))
                    {
                        $summary = safesql($_POST['summary'], "text");

                        $startdate = safesql(strtotime($_POST['sdate']) + $_POST['stime']['Hour']*60*60 + $_POST['stime']['Minute']*60);
                        $enddate = safesql(strtotime($_POST['edate']) + $_POST['etime']['Hour']*60*60 + $_POST['etime']['Minute']*60);
                        $detail = safesql($_POST['story'], "text", false);
                        $colour = safesql($_POST['colour'], "text");

                        if (confirm('event'))
                        {
                            $message = "Your event has been updated, but first needs to be reviewed by an administrator.";
                            $allow = 0;
                        }
                        else
                        {
                            $message = "Your event has been updated.";
                            $allow = 1;
                        }
                                
                        $groupallowed = safesql(serialize($_POST['groups']), "text");
                        $signup = safesql($_POST['signup'], "int");
                        $signupusers = safesql($_POST['signupusers'], "int");
                        $patrols = $signupusers != 3 ? safesql(serialize($_POST['patrols']), "text") : safesql(serialize($_POST['invites']), "text");
            
                        $sql = $data->update_query("calendar_items", "summary = $summary, startdate = $startdate, enddate = $enddate, detail = $detail, `groups` = $groupallowed, colour = $colour, signup=$signup, signupusers=$signupusers, patrols=$patrols, allowed=$allow", "id = $id");
                            if ($sql)
                            {
                                if (confirm('event'))
                                {
                                    $event = $data->select_fetch_one_row("calendar_items", "WHERE id = $id");
                                    confirmMail("event", $event);
                                }

                                show_message($message, "index.php?page=mythings&menuid=$menuid");  
                            }
                    }
                    else
                    {
                        show_message("Some of your inputs might not be correct. Please check them again", "index.php?page=mythings&cat=events&action=edit&id={$id}&menuid={$menuid}", true);
                    }
                }
            }
            elseif ($action == "adddownload")
            {
                $download = safesql($_POST['download'], "int");
                $permissions = safesql($_POST['permissions'], "int");
                
                if ($download != 0)
                {
                    $data->insert_query("calendar_downloads", "'', $id, $download, $permissions");
                    show_message("Download Added", "index.php?page=mythings&cat=events&action=signups&id=$id&activetab=events");
                }
                else
                {
                    show_message("Please select a download", "index.php?page=mythings&cat=events&action=signups&id=$id&activetab=events");
                }
            }
            elseif ($action == "deletefield")
            {
                $eventid = safesql($_GET['event'], "int");
                $data->delete_query("profilefields", "id=$id");
                show_message("Field Deleted", "index.php?page=mythings&cat=events&action=signups&id=$eventid&activetab=ical&menuid=$menuid");
            }
            elseif ($action == "deletedownload")
            {
                $eventid = safesql($_GET['event'], "int");
                $data->delete_query("calendar_downloads", "id=$id");
                show_message("Download Removed", "index.php?page=mythings&cat=events&action=signups&id=$eventid&activetab=ical&menuid=$menuid");
            }
            elseif ($action == "newfield" || $action == "editfield")
            {
                $pagenum = 12;
                $tpl->assign("action", $action);
                $eventid = safesql($_GET['event'], "int");
                if ($action == "editfield")
                {
                    $item = $data->select_fetch_one_row("profilefields", "WHERE id=$id");
                
                    $item['options'] = unserialize($item['options']);

                    $tpl->assign("item", $item);
                }
                if ($_POST['Submit'] == "Submit")
                {       
                    $name = safesql(str_replace(" ", "", $_POST['name']), "text");
                    if (check_duplicate("profilefields", "name", $name, $id))
                    {
                        show_message("A field with that name already exists");
                    }
                    $query = safesql($_POST['query'], "text");
                    $hint = safesql($_POST['hint'], "text");
                    $required = safesql($_POST['required'], "int");
                    $register = safesql($_POST['register'], "int");
                    $type = safesql($_POST['type'], "int");
                    
                    switch ($_POST['type'])
                    {
                        case 1:
                                $options = $_POST['options'];
                                break;
                        case 2:
                                $options = $_POST['options'];
                                break;
                        case 3: case 4: case 5:
                                $options = array();
                                $options[0] = $_POST['numoptions'];
                                for ($i=1;$i<=$_POST['numoptions'];$i++)
                                {
                                    $temp = $_POST['option' . $i];
                                    if ($temp != '')
                                    {
                                        $options[] = urlencode(stripslashes($temp));
                                    }
                                    else
                                    {
                                        --$options[0];
                                    }
                                }
                                break;
                        case 6:
                            $options = "''";
                    }
                    
                    $pos = 0;
                    $options = safesql(serialize($options), "text");
                    if ($action == "newfield")
                    {
                        $data->insert_query("profilefields", "'', $name, $query, $options, $hint, $type, $required, $register, 0, $pos, 2, $eventid");
                        show_message("Field Added", "index.php?page=mythings&cat=events&action=signups&id=$eventid&activetab=events");
                    }
                    elseif ($action == "editfield")
                    {
                        $data->update_query("profilefields", "query=$query, options=$options, hint=$hint, type=$type, required=$required, register=$register", "id=$id");
                        show_message("Field Updated", "index.php?page=mythings&cat=events&action=signups&id=$eventid&activetab=events");
                    }
                }
            }
            elseif ($action == "signups")
            {
                $pagenum = 11;
                $scriptList['mootabs'] = 1;
                
                $eventinfo = $data->select_fetch_one_row("calendar_items",  "WHERE id=$id");
                
                $groups = group_sql_list_id("patrol", "OR"); 
                
                if ($eventinfo['signupusers'] == 0 || $eventinfo['signupusers'] == 3)
                {
                    $sql = $data->select_query("members", "WHERE ($groups) OR patrol = 0 ORDER BY lastName, firstName ASC");
                }
                elseif ($eventinfo['signupusers'] == 1)
                {
                    $sql = $data->select_query("members", "WHERE ($groups) AND type = 0 ORDER BY lastName, firstName ASC");
                }
                elseif ($eventinfo['signupusers'] == 2)
                {
                    $sql = $data->select_query("members", "WHERE type = 1 OR type = 2 ORDER BY lastName, firstName ASC");
                }

                $nummembers = $data->num_rows($sql);
                $members = array();
                while ($temp = $data->fetch_array($sql))
                {
                    $attendie = $data->select_fetch_one_row("attendies", "WHERE uid={$temp['id']} AND eid = $id");
                    if ($attendie)
                    {
                        $temp['attend'] = 1;
                        $temp['attendoptions'] = unserialize($attendie['options']);
                    }
                    $members[] = $temp;
                }
                $tpl->assign("members", $members);
                $tpl->assign("nummembers", $nummembers);
                
                $sql = $data->select_query("profilefields", "WHERE place=2 AND eventid=$id ORDER BY query ASC");
                
                $numfields = $data->num_rows($sql);
                $fields = array();
                while ($temp =  $data->fetch_array($sql))
                {
                    $temp['options'] = unserialize($temp['options']);
                    $fields[] = $temp;
                }
                $tpl->assign("numfields", $numfields);
                $tpl->assign("fields", $fields);
                
                $tpl->assign("eventid", $id);
                $tpl->assign("eventinfo", $eventinfo);

                $sql = $data->select_query("download_cats", "", "id, name, downauth");
                $downloads = array();
                $numcategories = 0;
                while ($temp = $data->fetch_array($sql))
                {
			$auth = unserialize($temp['downauth']);
			$allowed  = 0;
			$usergroups = user_groups_id_array($check['id']);

			for($i=0;$i<count($usergroups);$i++)
			{                
			    if($auth[$usergroups[$i]] == 1)
			    {
				$allowed = 1;
			    }
			}
			if ($allowed == 1)
			{
			    $sql1 = $data->select_query("downloads", "WHERE cat={$temp['id']} AND trash = 0", "id, name");
			    $downloadtemp = array();
			    $tempnumber = 0;
			    while ($temp2 = $data->fetch_array($sql1))
			    {
				if ($data->num_rows($data->select_query("calendar_downloads", "WHERE eid=$id AND did = {$temp2['id']}")) == 0)
				{
					$downloadtemp[] = $temp2;
					$tempnumber++;
				}
			    }
			    $temp['number'] = $tempnumber;
			    $temp['downloads'] = $downloadtemp;
			    $downloads[] = $temp;
			    $numcategories++;
			}
                }
                
                $tpl->assign("numcategories", $numcategories);
                $tpl->assign("downloads", $downloads);
                
                $sql = $data->select_query("calendar_downloads", "WHERE eid=$id");
                $event_downloads = array();
                $numeventdownloads = $data->num_rows($sql);
                while ($temp = $data->fetch_array($sql))
                {
                    $temp1 = $data->select_fetch_one_row("downloads", "WHERE id={$temp['did']}", "name");
                    $temp['name'] = $temp1['name'];
                    $event_downloads[] = $temp;
                }

                $tpl->assign("numeventdownloads", $numeventdownloads);
                $tpl->assign("event_downloads", $event_downloads);

                if ($_POST['Submit'] == "Update")
                {
                    $attendies = $_POST['attend'];
                    $options = $_POST['options'];
                    $data->delete_query("attendies", "eid=$id");
                    foreach($attendies as $uid => $attend)
                    {
                        if ($attend == 1)
                        {
                            $useroption = safesql(serialize($options[$uid]), "text");
                            if ($data->num_rows($data->select_query("attendies", "WHERE uid=$uid AND eid = $id")) == 0)
                            {
                                $data->insert_query("attendies", "'', $uid, $id, $useroption");
                            }
                            else
                            {
                                $data->update_query("attendies", "options = $useroption", "uid=$uid AND eid=$id");
                            }
                        }
                    }
                    show_message("Attendies Updated", "index.php?page=mythings&cat=events&action=signups&id=$id&activetab=events");
                }                
            }
            elseif ($action == "delete")
            {
                $sqlq = $data->update_query("calendar_items", "trash=1", "id=$id", "Calendar", "Deleted $id");
                if ($sqlq)
                {
                    show_message("Event has been deleted.", "index.php?page=mythings&menuid=$menuid");                 
                }
            }
            break;
        case "downloads":
            if ($action == "edit")
            {
                $pagenum = 8;
                $sql = $data->select_query("downloads", "WHERE id=$id ");
                $down = $data->fetch_array($sql);
                $location = "Edit " . censor($down['name']) . " download";
                $sql = $data->select_query("download_cats");
                $cats = array();
                $numcats = 0;
                while ($row = $data->fetch_array($sql))
                {
                    $temp = unserialize($row['auth']);
                    if($temp == "")
                    {
                        $cats[] = $row;
                        $numcats++;
                    }
                    else
                    {
                        if($temp[$check['team']] == 1)
                        {
                            $cats[] = $row;
                            $numcats++;
                        }
                    }
                }
                
                $quer = $data->select_query("album_track", "WHERE allowed=1 AND trash=0 ORDER BY album_name ASC");
                $numalbum = $data->num_rows($quer);
                $albums = array();
                while ($temp = $data->fetch_array($quer))
                {
                    $temp['photos'] = $data->select_fetch_all_rows($temp['numphotos'], "photos", "WHERE album_id = {$temp['ID']} AND allowed = 1");
                    $albums[] = $temp;
                }
                $tpl->assign('numalbum', $numalbum);
                $tpl->assign('albums', $albums);    
                
                if($down['thumbnail'])
                {
                    $photoid = safesql($down['thumbnail'], "int");
                    $photo = $data->select_fetch_one_row("photos", "WHERE ID=$photoid", "album_id");
                    
                    $selectedAlbumInfo['photos'] = $data->select_fetch_all_rows($selectedAlbumInfo['numphotos'], "photos", "WHERE album_id = {$photo['album_id']} AND allowed = 1");
                    $tpl->assign("selectedAlbumInfo", $selectedAlbumInfo);
                    $tpl->assign("selectedAlbum", $photo['album_id']);
                }
                
                if($_POST['Submit'] == 'Submit')
                {
                    $name = safesql($_POST['name'], "text");
                    $desc = safesql($_POST['desc'], "text");
                    $picture = safesql($_POST['downloadphoto'], "text");
                    $cid = safesql($_POST['cat'], "text", false);
                    $filename = "";
                    if ($_FILES['file']['name'] != "")
                    {
                        $where = $config['downloadpath'] . "/";
                        if ($down['saved_file'] != '')
                        {
                            unlink($where . $down['saved_file']);
                        }
                        $filename = $_FILES['file']['name'];
                        $savefile = md5($_FILES['file']['name'] . (microtime() + mktime()));
                        if (($_FILES['file']['size']/1024 <= $config['uploadlimit']))
                        {
                            move_uploaded_file($_FILES['file']['tmp_name'],$where . $savefile);
                        }
                        else
                        {
                            show_message("The file is larger than the maximum allowable file size (Upload Limit:{$config['uploadlimit']}Kb, File size: " . ceil($_FILES['file']['size']/1024) . "Kb ).", "index.php?page=mythings&menuid=$menuid"); 
                        }

                        if ($_FILES['file']['name'] != "" && (!file_exists($where . $savefile) || filesize($where . $savefile) == 0))
                        {
                            show_message("There was an error uploading the file. Try again, if the problem persists contact the administrator.", "index.php?page=mythings&menuid=$menuid"); 
                        }
                        $filename = safesql($filename, "text");
                        $savefile = safesql($savefile, "text");
                    }
                    
                    if (confirm('download'))
                    {
                        if ($_FILES['file']['name'] != "")
                        {
                            $sql = $data->update_query("downloads", "name = $name, thumbnail=$picture, descs = $desc, cat = $cid, file = $filename, saved_file= $savefile, numdownloads = 0, size = '".ceil($_FILES['file']['size'] / 1024)."', allowed = 0", "id=$id");
                            $download = $data->select_fetch_one_row("downloads", "WHERE id=$id ");
                            $extra = "It first needs to be reviewed before it will be available on the site.";
                            confirmMail("download", $download);
                        }
                        else
                        {
                            $sql = $data->update_query("downloads", "name = $name, thumbnail=$picture, descs = $desc, cat = $cid", "id=$id", "Downloads", "Updated Download $name");
                        }
                    }
                    else
                    {
                        if ($_FILES['file']['name'] != "")
                        {
                            $sql = $data->update_query("downloads", "name = $name, thumbnail=$picture, descs = $desc, cat = $cid, file = $filename, saved_file= $savefile, numdownloads = 0, size = '".ceil($_FILES['file']['size'] / 1024)."'", "id=$id", "Downloads", "Updated Download $name");
                        }
                        else
                        {
                            $sql = $data->update_query("downloads", "name = $name, thumbnail=$picture, descs = $desc, cat = $cid", "id=$id", "Downloads", "Updated Download $name");
                        }
                    }
                    if ($sql)
                    {
                        show_message("Your download has been updated. $extra", "index.php?page=mythings&menuid=$menuid"); 
                    }
                }
        
                $tpl->assign("cat", $cats);
                $tpl->assign("numcats", $numcats);
                $tpl->assign("down", $down);
                $tpl->assign("action", "edit");
            }
            elseif ($action == "delete")
            {
                $sqlq = $data->update_query("downloads", "trash=1", "id=$id", "Downloads", "Deleted $id");
                if ($sqlq)
                {
                    show_message("Your download has been deleted.", "index.php?page=mythings&menuid=$menuid");             
                }
            }
            break;
        case "newsitems":
            if ($action == "edit")
            {
                $pagenum = 9;
                $sql = $data->select_query("newscontent", "WHERE id=$id");

                $shownews = $data->fetch_array($Show);
                $shownews['news'] = $shownews['news'];
                $tpl->assign("shownews", $shownews);
                $location = "Edit " . censor($shownews['title']) . " news item";
                $scriptList['tinyAdv'] = 1;
                $submit=$_POST["submit"];
                if ($submit == "Submit") 
                {            
                    $news = safesql($_POST['story'], "text", false);
                    $title = safesql($_POST['title'], "text");
                    if (confirm('news')) $allow = 0;
                    else $allow = 1;
                    $sql = $data->update_query("newscontent", "title=$title, news=$news, allowed = $allow",
                                                    "id=$id", "News", "Edited News $id");		
                    if (confirm('news')) 
                    {
                        $extra = "The administrator needs to republish your news item now that you have edited it.";
                        $news = $data->select_fetch_one_row("newscontent", "WHERE id=$id");
                        confirmMail("news", $news);
                    }
                    else $extra = "";
    
                    show_message("Your news item has been updated. $extra", "index.php?page=mythings&menuid=$menuid");                                                        
                }
            }
            elseif ($action == "delete")
            {
                $sqlq = $data->update_query("newscontent", "trash=1", "id=$id", "News Items", "Deleted news");
                if ($sqlq)
                {
                    show_message("Your news item has been deleted.", "index.php?page=mythings&menuid=$menuid");               
                }
            }
            break;
            case "pollitems":
                if ($action == "delete")
                {
                    $sqlq = $data->delete_query("polls", "trash=1", "id=$id");
                    if ($sqlq)
                    {
                        show_message("Your poll has been deleted.", "index.php?page=mythings&menuid=$menuid");               
                    }
                }
                break;
    }

    if ($action=="owner")
    {
        $pagenum=5;
        $cattype = safesql($cat, "text");
        $tpl->assign("itemid", $id);
        $tpl->assign("cat", $cat);
        $sql = $data->select_query("owners", "WHERE item_id=$id AND item_type=$cattype");
        $itemowners = array();
        $numitemowners = $data->num_rows($sql);
        while ($temp = $data->fetch_array($sql))
        {
            if ($temp['owner_type'] == 0)
            {
                $sql2 = $data->select_query("users", "WHERE id={$temp['owner_id']}", "id, uname");
                $temp2 = $data->fetch_array($sql2);
                $temp2['name'] = $temp2['uname'];
            }
            else
            {
                $sql2 = $data->select_query("groups", "WHERE id={$temp['owner_id']}", "id, teamname");
                $temp2 = $data->fetch_array($sql2);
                $temp2['name'] = $temp2['teamname'];
            }

            $temp2['expired'] = ($temp['expire'] >= $timestamp || $temp['expire'] == 0) ? 0 : 1;
            $temp2['type'] = $temp['type_owner'];
            $temp2['expire'] = $temp['expire'];
            $temp2['id'] = $temp['id'];
            $itemowners[] = $temp2;
        }
        
        $tpl->assign("numitemowners", $numitemowners);
        $tpl->assign("itemowners", $itemowners);
        
        $sql = $data->select_query("users", "ORDER BY uname");
        $numpeople = 0;
        $people = array();
        while ($temp = $data->fetch_array($sql))
        {
            if ($data->num_rows($data->select_query("owners", "WHERE item_id=$id AND item_type=$cattype AND owner_id={$temp['id']} AND owner_type=0")) == 0)
            {
                $people[] = $temp;
                $numpeople++;
            }
        }
        $tpl->assign("numpeople", $numpeople);
        $tpl->assign("people", $people);
        $sql = $data->select_query("groups", "ORDER BY teamname");
        $numteams = 0;
        $groups = array();
        while ($temp = $data->fetch_array($sql))
        {
            if ($data->num_rows($data->select_query("owners", "WHERE item_id=$id AND item_type=$cattype AND owner_id={$temp['id']} AND owner_type=1")) == 0)
            {
                $groups[] = $temp;
                $numteams++;
            }
        }
        $tpl->assign("numteams", $numteams);
        $tpl->assign("groups", $groups);
        
        if($_POST['action'] == "Add")
        {
            $temp = $_POST['owner'];
            $temp = explode("_", $temp);
            $owner_type = ($temp[0] == "user") ? 0 : 1;
            $owner = safesql($temp[1], "text");
            $type_owner = safesql($_POST['type_owner'], "int");
            if ($_POST['expire'] != 0)
            {
                $time = safesql($timestamp + 3600*$_POST['expire'], "int");
            }
            else
            {
                $time = "0";
            }
            
            $sql = $data->insert_query("owners", "'', $id, $cattype, $owner, $owner_type, $type_owner, $time");
            if ($sql)
            {                  
                show_message("Owner added", "index.php?page=mythings&cat=$cat&action=owner&id={$id}&menuid=$menuid"); 
            }
        }
    } 
    elseif ($action=="adddown")
    {
        $pagenum = 8;
        $sql = $data->select_query("download_cats");
        $cats = array();
        $numcats = 0;
        while ($row = $data->fetch_array($sql))
        {
            $temp = unserialize($row['upauth']);
            $usergroups = user_groups_id_array($check['id']);
            $allowed = 0;
            for($i=0;$i<count($usergroups);$i++)
            {
                if($temp[$usergroups[$i]] == 1)
                {
                    $allowed = 1;
                }
            }

            if($allowed == 1)
            {
                $cats[] = $row;
                $numcats++;
            }
        }
        
        $quer = $data->select_query("album_track", "WHERE allowed=1 AND trash=0 ORDER BY album_name ASC");
        $numalbum = $data->num_rows($quer);
        $albums = array();
        while ($temp = $data->fetch_array($quer))
        {
            $temp['photos'] = $data->select_fetch_all_rows($temp['numphotos'], "photos", "WHERE album_id = {$temp['ID']} AND allowed = 1");
            $albums[] = $temp;
        }
        $tpl->assign('numalbum', $numalbum);
        $tpl->assign('albums', $albums);       
        if ($numcats > 0)
        {
            $tpl->assign("cat", $cats);
            $tpl->assign("numcats", $numcats);
        }
        else
        {
            show_message("No download categories available.", "index.php?page=mythings&menuid=$menuid"); 
        }
        
        if($_POST['Submit'] == 'Submit')
        {
            if (validate($_POST['validation']))
            {  
                $name = safesql($_POST['name'], "text");
                $desc = safesql($_POST['desc'], "text");
                if ($desc == NULL) $desc = "''";
                $cid = safesql($_POST['cat'], "int");
                $thumbnail = safesql($_POST['downloadphoto'], "int");
                $where = $config['downloadpath'] . "/";
                
                $filename = $_FILES['file']['name'];
                $savefile = md5($_FILES['file']['name'] . (microtime() + mktime()));
                if (($_FILES['file']['size']/1024 <= $config['uploadlimit']))
                {
                    move_uploaded_file($_FILES['file']['tmp_name'],$where . $savefile);
                }
                else
                {
                    show_message("The file is larger than the maximum allowable file size (Upload Limit:{$config['uploadlimit']}Kb, File size: " . ceil($_FILES['file']['size']/1024) . "Kb ).", "index.php?page=mythings&menuid=$menuid"); 
                }

                if ($_FILES['file']['name'] != "" && (!file_exists($where . $savefile) || filesize($where . $savefile) == 0))
                {
                    show_message("There was an error uploading the file. Try again, if the problem persists contact the administrator.", "index.php?page=mythings&menuid=$menuid"); 
                }
                $filename = safesql($filename, "text");
                $savefile = safesql($savefile, "text");
                if (confirm('download'))
                {
                    $data->insert_query("downloads", "NULL, $name, $desc, $cid, $filename, $savefile, $thumbnail, '0', '".ceil($_FILES['file']['size'] / 1024)."', 0, 0");
                    $addon = "The download first needs to be reviewed before it will be available on the site.";
                }
                else
                {
                    $data->insert_query("downloads", "NULL, $name, $desc, $cid, $filename, $savefile, $thumbnail, '0', '".ceil($_FILES['file']['size'] / 1024)."', 1, 0");
                }
                $data->update_query("users", "numdown = numdown + 1", "uname='{$check['uname']}'");
                $article = $data->fetch_array($data->select_query("downloads", "WHERE name=$name ORDER BY id DESC"));
                $data->insert_query("owners", "'', {$article['id']}, 'downloads', {$check['id']}, 0, 0, 0");
                if (confirm('download'))
                {
                    confirmMail("download", $article);
                }
		else
                {
                    email('newitem', array("download", $article));
                }
                show_message("Your file has been added. $addon", "index.php?page=mythings&menuid=$menuid");
            }
            else
            {
                show_message("There where some errors with some fields, please check them again and resubmit.", "index.php?page=mythings&action=adddown&menuid=$menuid", true);        
            }
        }
    }
    elseif ($action=="addnews")
    {
        $pagenum = 9;
        $scriptList['tinyAdv'] = 1;
        
        $sql = $data->select_query("album_track", "WHERE trash=0 ORDER BY album_name ASC");
        $numalbums = $data->num_rows($sql);
        $album = array();
        while ($temp = $data->fetch_array($sql))
        {
            $temp['idType'] = $temp['ID'] . ".album";
            $album[] = $temp;
        }

        $sql = $data->select_query("patrol_articles", "WHERE trash=0 ORDER BY title ASC");
        $numart = $data->num_rows($sql);
        $articles = array();
        while ($temp = $data->fetch_array($sql))        
        {
            $temp['idType'] = $temp['ID'] . ".article";
            $articles[] = $temp;
        }

        $sql = $data->select_query("calendar_items", "WHERE trash=0 ORDER BY summary ASC");
        $numevents = $data->num_rows($sql);
        $events = array();
        while ($temp = $data->fetch_array($sql))
        {
            $temp['idType'] = $temp['id'] . ".event";
            $events[] = $temp;
        }

        $sql = $data->select_query("downloads", "WHERE trash=0 ORDER BY name ASC");
        $numdown = $data->num_rows($sql);
        $downloads = array();
        while ($temp = $data->fetch_array($sql))
        {
            $temp['idType'] = $temp['id'] . ".download";
            $downloads[] = $temp;
        }

        $sql = $data->select_query("newscontent", "WHERE trash=0 ORDER BY title ASC");
        $numnews = $data->num_rows($sql);
        $newsitems = array();
        while ($temp = $data->fetch_array($sql))
        {
            $temp['idType'] = $temp['id'] . ".news";
            $newsitems[] = $temp;
        }
        

        $tpl->assign("numalbums", $numalbums);
        $tpl->assign("album", $album);
        
        $tpl->assign("numarticles", $numart);
        $tpl->assign("article", $articles);
        
        $tpl->assign("numevents", $numevents);
        $tpl->assign("event", $events);
        
        $tpl->assign("numdownloads", $numdown);
        $tpl->assign("download", $downloads);
        
        $tpl->assign("numnews", $numnews);
        $tpl->assign("news", $newsitems);
        
        if($_POST['submit'] == "Submit")
        { 
            if (validate($_POST['validation']))
            { 
                $news = safesql($_POST['story'], "text", false);
                $title = safesql($_POST['title'], "text");
                $attachment = safesql($_POST['attachment'], "text");
                if (confirm('news'))
                {
                    $Add = $data->insert_query("newscontent", "NULL, $title, $news, $timestamp, $attachment, 0, 0");
                    $addon = "The news item first needs to be reviewed before it will be available on the site.";
                }
                else
                {
                    $Add = $data->insert_query("newscontent", "NULL, $title, $news, $timestamp, $attachment, 1, 0");
                }
                $data->update_query("users", "numnews = numnews + 1", "id='{$check['id']}'");                
                $article = $data->fetch_array($data->select_query("newscontent", "WHERE title=$title AND event=$timestamp ORDER BY id DESC", "id, title, news"));
                if (confirm('news'))
                {
                    confirmMail("news", $article);
                }
                else
                {
                    email('newitem', array("news", $article));
                }
                $data->insert_query("owners", "'', {$article['id']}, 'newsitem', {$check['id']}, 0, 0, 0");
                show_message("Your news item has been added. $addon", "index.php?page=mythings&menuid=$menuid");
            }
            else
            {
                show_message("There where some errors with some fields, please check them again and resubmit.", "index.php?page=mythings&action=addnews&menuid=$menuid", true);        
            }
        }
    }
    elseif ($action=="deleteowner")
    {
        $sqlq = $data->delete_query("owners", "id=$id");
        if ($sqlq)
        {
            show_message("Owner removed.", "index.php?page=mythings&cat={$_GET['cat']}&action=owner&id={$_GET['itemid']}&menuid=$menuid"); 
        }                        
    }
    if ($action == "delete")
        header("location: index.php?page=mythings");
}
else
{
    $uname = $check['uname'];
    $grouplist = group_sql_list_id("owner_id", "OR", true);
   
   $pagesused = array("patrolarticle", "calender", "downloads", "news", "polls", "photos");
   $pageactive = array();
   
   foreach ($pagesused as $pagename)
   {
	$pageactive[$pagename] = $data->num_rows($data->select_query("functions", "WHERE active = 1 AND code = '$pagename'")) > 0 ? 1 : 0;
   }

   
   if ($pageactive['photos'])
   {
	    $sql = $data->select_query("album_track", "WHERE trash=0");
	    $numalbums = 0;
	    $album = array();
	    while ($temp = $data->fetch_array($sql))
	    {
		$sql2 = $data->select_query("owners", "WHERE item_id={$temp['ID']} AND item_type='album' AND ((owner_id={$check['id']} AND owner_type=0) OR ($grouplist AND owner_type=1))");
		$temp2 = $data->fetch_array($sql2);
		if ($data->num_rows($sql2) > 0)
		{
		    $numalbums++;
		    $temp['expire'] = $temp2['expire'];
		    $temp['type_owner'] = $temp2['type_owner'];
		    $temp['expired'] = ($temp2['expire'] >= $timestamp || $temp2['expire'] == 0) ? 0 : 1;
		    $album[] = $temp;
		}
	    }
    }

   if ($pageactive['patrolarticle'])
   {
	    $sql = $data->select_query("patrol_articles", "WHERE trash=0");
	    $numart = 0;
	    $articles = array();
	    while ($temp = $data->fetch_array($sql))
	    {
		$sql2 = $data->select_query("owners", "WHERE item_id={$temp['ID']} AND item_type='articles' AND ((owner_id={$check['id']} AND owner_type=0) OR ($grouplist AND owner_type=1))");
		$temp2 = $data->fetch_array($sql2);
		if ($data->num_rows($sql2) > 0)
		{
		    $numart++;
		    $temp['expire'] = $temp2['expire'];
		    $temp['type_owner'] = $temp2['type_owner'];
		    $temp['expired'] = ($temp2['expire'] >= $timestamp || $temp2['expire'] == 0) ? 0 : 1;
		    $articles[] = $temp;
		}
	    }
    }

   if ($pageactive['calender'])
   {
	    $sql = $data->select_query("calendar_items", "WHERE trash=0");
	    $numevents = 0;
	    $events = array();
	    while ($temp = $data->fetch_array($sql))
	    {
		$sql2 = $data->select_query("owners", "WHERE item_id={$temp['id']} AND item_type='events' AND ((owner_id={$check['id']} AND owner_type=0) OR ($grouplist AND owner_type=1))");
		$temp2 = $data->fetch_array($sql2);
		if ($data->num_rows($sql2) > 0)
		{
		    $numevents++;
		    $temp['expire'] = $temp2['expire'];
		    $temp['type_owner'] = $temp2['type_owner'];
		    $temp['expired'] = ($temp2['expire'] >= $timestamp || $temp2['expire'] == 0) ? 0 : 1;
		    $events[] = $temp;
		}
	    }
    }

   if ($pageactive['downloads'])
   {
	    $sql = $data->select_query("downloads", "WHERE trash=0");
	    $numdown = 0;
	    $downloads = array();
	    while ($temp = $data->fetch_array($sql))
	    {
		$sql2 = $data->select_query("owners", "WHERE item_id={$temp['id']} AND item_type='downloads' AND ((owner_id={$check['id']} AND owner_type=0) OR ($grouplist AND owner_type=1))");
		$temp2 = $data->fetch_array($sql2);
		if ($data->num_rows($sql2) > 0)
		{
		    $numdown++;
		    $temp['expire'] = $temp2['expire'];
		    $temp['type_owner'] = $temp2['type_owner'];
		    $temp['expired'] = ($temp2['expire'] >= $timestamp || $temp2['expire'] == 0) ? 0 : 1;
		    $downloads[] = $temp;
		}
	    }
    }

   if ($pageactive['news'])
   {
	    $sql = $data->select_query("newscontent", "WHERE trash=0");
	    $numnews = 0;
	    $newsitems = array();
	    while ($temp = $data->fetch_array($sql))
	    {
		$sql2 = $data->select_query("owners", "WHERE item_id={$temp['id']} AND item_type='newsitem' AND ((owner_id={$check['id']} AND owner_type=0) OR ($grouplist AND owner_type=1))");
		$temp2 = $data->fetch_array($sql2);
		if ($data->num_rows($sql2) > 0)
		{
		    $numnews++;
		    $temp['expire'] = $temp2['expire'];
		    $temp['type_owner'] = $temp2['type_owner'];
		    $temp['expired'] = ($temp2['expire'] >= $timestamp || $temp2['expire'] == 0) ? 0 : 1;
		    $newsitems[] = $temp;
		}
	    }
    }

   if ($pageactive['polls'])
   {
	    $sql = $data->select_query("polls", "WHERE trash=0");
	    $numpolls = 0;
	    $pollitems = array();
	    while ($temp = $data->fetch_array($sql))
	    {
		$sql2 = $data->select_query("owners", "WHERE item_id={$temp['id']} AND item_type='pollitems' AND ((owner_id={$check['id']} AND owner_type=0) OR ($grouplist AND owner_type=1))");
		$temp2 = $data->fetch_array($sql2);
		if ($data->num_rows($sql2) > 0)
		{
		    $numpolls++;
		    $temp['expire'] = $temp2['expire'];
		    $temp['type_owner'] = $temp2['type_owner'];
		    $temp['expired'] = ($temp2['expire'] >= $timestamp || $temp2['expire'] == 0) ? 0 : 1;
		    $pollitems[] = $temp;
		}
	    }
    }
    
    $authorization = array();
    $authorization['album']=get_auth('addphotoalbum', 2);
    $authorization['article']=get_auth('addarticle', 2);
    $authorization['notice']=get_auth('addnotice', 2);
    $authorization['event']=get_auth('addevent', 2);
    $authorization['down']=get_auth('adddown', 2);
    $authorization['news']=get_auth('addnews', 2);
    $authorization['poll']=get_auth('addpoll', 2);

    $tpl->assign("pageactive", $pageactive);
    $tpl->assign("action", $action);
    $tpl->assign("auth", $authorization);
    $tpl->assign("numalbums", $numalbums);
    $tpl->assign("albums", $album);
    $tpl->assign("numart", $numart);
    $tpl->assign("articles", $articles);
    $tpl->assign("numevents", $numevents);
    $tpl->assign("events", $events);
    $tpl->assign("numdown", $numdown);
    $tpl->assign("downloads", $downloads);
    $tpl->assign("numnews", $numnews);
    $tpl->assign("newsitems", $newsitems);
    $tpl->assign("numpolls", $numpolls);
    $tpl->assign("pollitems", $pollitems);
    $scriptList['mootabs'] = 1;
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$tpl->assign('teams',$teams);
$tpl->assign('numteams', $numteams);
$tpl->assign('editFormAction', $editFormAction);
$dbpage = true;
$pagename = "mythings";
?>
