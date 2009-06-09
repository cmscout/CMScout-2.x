<?php
/**************************************************************************
    FILENAME        :   functions.php
    PURPOSE OF FILE :   General functions used through out CMScout
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
function error_message ($userdescription, $description=false)
{
    global $infoerror, $errormessage, $config;
    
    $infoerror = true;
    $errormessage = $userdescription;
    log_error($description);
    return;
}//error_message

function show_message ($message, $page=false, $postback = false, $customuid = false, $type=0)
{
    global $check, $data;
    $uid = safesql(($customuid ? $customuid : $check['uid']), "text");
    $message = safesql($message, "text", true, true, true);
    if ($postback)
    {
        $tempPost = $_POST;
        $post = array();
        foreach($tempPost as $id => $value)
        {
            if ($id != "captcha" && $id != "password" && $id != "repassword" && $id != "validation" && strtolower($id) != "submit")
            {
                $temp['id'] = $id;
                $temp['value'] = $value;
                $post[] = $temp;
            }
        }
    }
    $post = safesql(serialize($post), "text");
    $data->insert_query("messages", "'', $uid, $message, $type, $post");

    if ($page)
    {
        header("Location:$page");
        echo "<script>window.location='$page';</script>";
    }
    else
    {
        header("Location:index.php");
    }
    exit;
    return;
}

function show_admin_message($message, $page=false, $postback = false)
{
    $page = $page == false ? "admin.php" : $page;
    show_message ($message, $page, $postback, false, 3);
    return;
}

function die_horrible_death($error)
{
    trigger_error($error, E_USER_ERROR);
    return;
}

function read_config() 
{
	global $data;
	$query = $data->select_query("config");
	$configdata = array();
	while ($config = $data->fetch_array($query))
    {
		$config['value'] = $config['name'] == "exclusion" ? unserialize($config['value']) : $config['value'];

        $configdata[$config['name']] = $config['value'];
	}
	return $configdata;
}//read_config

function read_scout_language() 
{
	global $data;
	$query = $data->select_query("scoutlanguage");
	$configdata = array();
	while ($config = $data->fetch_array($query))
    {
		$config['value'] = $config['value'];

        $configdata[$config['name']] = $config['value'];
	}
	return $configdata;
}//read_scout_language

function location($location, $uid) 
{
    global $data, $check, $timestamp;
    if ($check['uname']) 
    {
        $location = safesql($location, "text",  true, true, true) ;
        $query = $data->update_query("onlineusers", "location = $location, locchange=$timestamp", "uid='$uid'", "", "", false);
    }
    return false;
}//location

function change_theme_dir($themeid = false, $useSmarty = true) 
{
	global $config, $tpl, $data, $check;
	
    if (!$themeid) 
    {
        $themeid = isset($check['theme_id']) ? $check['theme_id'] : 0;
    }
    
	if (!isset($themeid) || ($themeid == 0) || (empty($themeid))) 
    {
        $themeid = $config['defaulttheme'];
	} 
    
    $theme_select = $data->select_query("themes", "WHERE id='$themeid'");
    $theme = $data->fetch_array($theme_select);
    if (!$theme) 
    {
        $themeid = $config['defaulttheme'];
        $theme_select = $data->select_query("themes", "WHERE id='$themeid'");
        $theme = $data->fetch_array($theme_select);
        if (!$theme)
        {
            $theme_select = $data->select_query("themes");
            $theme = $data->fetch_array($theme_select);
            if(!$theme)
            {
                die_horrible_death("There are no templates installed");
            }            
        }
    } 
    
    $configfile = $theme['configfile'];
    $themedir = $theme['dir'];
    if (file_exists("templates/$themedir/index.tpl"))
    {
	    if ($useSmarty)
        {
            $tpl->template_dir = "templates/$themedir/";
            $templatedir = $tpl->template_dir;
        }
        else
        {
            $templatedir = "templates/$themedir/";
        }
        include("templates/$themedir/$configfile");
        return $templateinfo;
    }
    else
    {
        $themeid = $config['defaulttheme'];
        
        $theme_select = $data->select_query("themes", "WHERE id='$themeid'");
        $theme = $data->fetch_array($theme_select);
        if (!$theme) 
        {
                $theme_select = $data->select_query("themes");
                $theme = $data->fetch_array($theme_select);
                if(!$theme)
                {
                    die_horrible_death("There are no templates installed");
                }            
        } 
        
	
        $configfile = $theme['configfile'];
        $themedir = $theme['dir'];
        if (file_exists("templates/$themedir/index.tpl"))
	{
		if ($useSmarty)
		{
		    $tpl->template_dir = "templates/$themedir/";
		    $templatedir = $tpl->template_dir;
		}
		else
		{
		    $templatedir = "templates/$themedir/";
		}
		include("templates/$themedir/$configfile");
		return $templateinfo;
	}
	else
	{
		die_horrible_death("Default template files not found.");
	}
    }
    return;
} //change_theme_dir

function get_theme_css() 
{
	global $config, $tpl, $data, $currtheme;
	$theme_select = $data->select_query("themes", "WHERE id='$currtheme'");
	$theme = $data->fetch_array($theme_select);
	return $tpl->template_dir . $theme['cssfile'];
} //get_theme_css

function get_spec($page, &$location) 
{
    global $data;
    $page = safesql($page, "int");
    $pages = $data->select_query("static_content", "WHERE id=$page AND type=0");
    $page =  $data->fetch_array($pages);
    $location = $page['friendly'];
    return $page['content'];
} //get_spec

function get_page_id($page) {
    global $data;
    $pages = $data->select_query("static_content", "WHERE name='$page'");
    $page =  $data->fetch_array($pages);
    return $page['id'];
} //get_page_id

function get_page_name($id) {
    global $data;
    $pages = $data->select_query("static_content", "WHERE id='$id'");
    $page =  $data->fetch_array($pages);
    return $page['name'];
} //get_page_id

function get_patrol_page($page, $patrol) {
    global $data, $check;
    $pages = $data->select_query("patrolcontent", "WHERE name=$page AND patrol=$patrol");
    $page =  $data->fetch_array($pages);
    if ($page['public'] == 0 && $data->num_rows($pages) > 0)
    {
        if($check['team'] == $page['patrol'] || $check['level'] == 0 || $check['level'] == 1 || $check['level'] == 2)
        {
            return $page['content'];
        }
        else
        {
            return "$%$#PageOFF%$^$%";
        }
    }
    elseif ($page['public'] == 1)
    {
        return $page['content'];
    }
    else
    {
        return false;
    }
    return;
} //get_patrol_page

function get_patrol_page_id($page, $patrol) {
    global $data, $check;
    $pages = $data->select_query("patrolcontent", "WHERE name=$page AND patrol=$patrol");
    $page =  $data->fetch_array($pages);
    if ($page['public'] == 0 && $data->num_rows($pages) > 0)
    {
        if($check['team'] == $page['patrol'] || $check['level'] == 0 || $check['level'] == 1 && $check['level'] == 2)
        {
            return $page['id'];
        }
        else
        {
            return "$%$#PageOFF%$^$%";
        }
    }
    elseif ($page['public'] == 1)
    {
        return $page['id'];
    }
    else
    {
        return false;
    }
    return;
} //get_patrol_page

function get_frontpage_patrol($patrolname)
{
    global $data, $check;
    $pages = $data->select_query("static", "WHERE frontpage=1 AND patrol=$patrolname");
    $page =  $data->fetch_array($pages);
    
    return $page['content'];
}

function get_page_subs($page, $site, $type) 
{
    global $data, $check;
    $page = safesql($page, "text");
    $site = safesql($site, "text");
    $type = safesql($type, "text");
    $pages = $data->select_query("static_content", "WHERE id=$page AND type=$type AND pid=$site");
    $page =  $data->fetch_array($pages);
    
    return $page['content'];
} //get_sub_page

function get_page_id_subs($page, $site, $type) 
{
    global $data, $check;
    $page = safesql($page, "text");
    $site = safesql($site, "text");
    $type = safesql($type, "text");
    $pages = $data->select_query("static_content", "WHERE name=$page AND type=$type AND pid=$site");
    $page =  $data->fetch_array($pages);
    return $page['id'];
} //get_sub_page_id

function get_frontpage_subs($subsite, $type)
{
    global $data, $check;
    $subsite = safesql($subsite, "text");
    $type = safesql($type, "text");
    $pages = $data->select_query("static_content", "WHERE type=$type AND pid=$subsite AND frontpage=1");
    $page =  $data->fetch_array($pages);
    
    return $page['content'];
}

function get_temp($pagename, $pagenum) 
{
	global $data;
	if ((!isset($pagename) || !$pagename)) 
    {
        error_message("No page name specified");
    }
    
	if ($pagenum == 0) 
    {
        $pagenum = 1;
    }
    
	$get = $data->select_query("pagetracking", "WHERE pagename='$pagename'");
	$temp = $data->fetch_array($get);
    
	if (!isset($temp['id']) || !$temp['id']) 
    {
        error_message("Page not found in database");
    }
    
	if ($pagenum > $temp['numpages']) 
    {
        $pagenum = $temp['numpages'];
    }
    
	$pageid = $temp['id'];
	$pages = $data->select_query("pagecontent", "WHERE pageid='$pageid' AND pagenum='$pagenum'");
	$page =  $data->fetch_array($pages);
	if (!isset($page['content']) || !$page['content'])
    {
        error_message("No pages for this module exist");
    }
	return $page['content'];
} //get_temp


function uploadpic($file, $width, $height, $savejpeg = false, $path=false)
{
    global $config;

    list($width_orig, $height_orig) = getimagesize($file['tmp_name']);

    switch ($file['type'])
    {
        case 'image/gif':
            $image = imagecreatefromgif($file['tmp_name']);
            $type = "gif";
            break;
        case 'image/jpeg': case 'image/pjpeg':
            $image = imagecreatefromjpeg($file['tmp_name']);
            $type = "jpeg";
            break;
        case 'image/png':
            $image = imagecreatefrompng($file['tmp_name']);
            $type = "png";
            break;
    }  
    
    if($width_orig > $width || $height_orig > $height)
    {    
        if ($width && ($width_orig < $height_orig)) 
        {
            $width = ($height / $height_orig) * $width_orig;
        }
        else 
        {
            $height = ($width / $width_orig) * $height_orig;
        }
    }
    else
    {
        $height = $height_orig;
        $width = $width_orig;
    }
    
    if ($type != "gif")
    {
        $image_p = imagecreatetruecolor($width, $height);
    }
    else
    {
        $image_p = imagecreate($width, $height);
    }
    
    imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
    
    if (!$savejpeg)
    {
        switch ($type)
        {
            case 'gif':
                $output['filename'] = md5(rand()%time() . $file['name']).'.gif'; 
                if ($path == false)
                {
                    imagegif($image_p, $config["photopath"]."/".$output['filename']);
                }
                else
                {
                    imagegif($image_p, $path."/".$output['filename']);
                }
                break;
            case 'jpeg':
                $output['filename'] = md5(rand()%time() . $file['name']).'.jpeg'; 
                if ($path == false)
                {
                    imagejpeg($image_p, $config["photopath"]."/".$output['filename']);
                }
                else
                {
                    imagejpeg($image_p, $path."/".$output['filename']);
                }
                break;
            case 'png':
                imagealphablending($image_p, FALSE);
                imagesavealpha($image_p, true);
                $output['filename'] = md5(rand()%time() . $file['name']).'.png'; 
                if ($path == false)
                {
                    imagepng($image_p, $config["photopath"]."/".$output['filename']);
                }
                else
                {
                    imagepng($image_p, $path."/".$output['filename']);
                }
                break;
        }
    }
    else
    {
        $output['filename'] = md5(rand()%time() . $file['name']).'.jpeg'; 
        if ($path == false)
        {
            imagejpeg($image_p, $config["photopath"]."/".$output['filename']);
        }
        else
        {
            imagejpeg($image_p, $path."/".$output['filename']);
        }
    }
    imagedestroy($image);
    imagedestroy($image_p);
    
    return $output;
}

function safesql($theValue, $theType, $striptags = true, $addshlashes = true, $notpost = false, $theDefinedValue = "", $theNotDefinedValue = "") 
{
    $theValue = $striptags && $type == "text" ? strip_tags($theValue) : ($type == "text" ? safe_html($theValue) : $theValue);
    if ($notpost == false)
    {
	$theValue = (!get_magic_quotes_gpc()) ? addslashes(stripslashes($theValue)) : ($theValue);
    }
    else
    {
	$theValue = addslashes($theValue);
    }

  if ($addshlashes)
  {
	  switch ($theType) {
	    case "text":
	      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
	      break;    
	    case "long":
	    case "int":
	      $theValue = ($theValue != "") ? intval($theValue) : 0;
	      break;
	    case "double":
	      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
	      break;
	    case "date":
	      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
	      break;
	    case "defined":
	      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
	      break;
	  }
  }
  else
  {
	  switch ($theType) {
	    case "text":
	      $theValue = ($theValue != "") ? $theValue : "NULL";
	      break;    
	    case "long":
	    case "int":
	      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
	      break;
	    case "double":
	      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
	      break;
	    case "date":
	      $theValue = ($theValue != "") ? $theValue : "NULL";
	      break;
	    case "defined":
	      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
	      break;
	  }
  }
  return $theValue;
}

function strip_attributes ($html, $attrs) {
  if (!is_array($attrs)) {
    $array= array( "$attrs" );
    unset($attrs);
    $attrs= $array;
  }
  
  foreach ($attrs AS $attribute) {
    // once for ", once for ', s makes the dot match linebreaks, too.
    $search[]= "/".$attribute.'\s*=\s*".+"/Uis';
    $search[]= "/".$attribute."\s*=\s*'.+'/Uis";
    // and once more for unquoted attributes
    $search[]= "/".$attribute."\s*=\s*\S+/i";
  }
  $html= preg_replace($search, "", $html);

  // do another pass and strip_tags() if matches are still found
  foreach ($search AS $pattern) {
    if (preg_match($pattern, $html)) {
      $html= strip_tags($html);
      break;
    }
  }

  return $html;
}

function js_and_entity_check( $html ) {
  // anything with ="javascript: is right out -- strip all tags if found
  $pattern= "/=[\S\s]*s\s*c\s*r\s*i\s*p\s*t\s*:\s*\S+/Ui";
  if (preg_match($pattern, $html)) {
    return TRUE;
  }
  
  
  return FALSE;
}

/* safe_html.php
   Copyright 2003 by Chris Snyder (csnyder@chxo.com)
   Free to use and redistribute, but see License and Disclaimer below

     - Huge thanks to James Wetterau for initial testing and feedback!
     - Originally posted at http://lists.nyphp.org/pipermail/talk/2003-May/003832.html

Version History:
2007-01-29 - 0.6 -- added additional check after tag stripping, thanks to GÃ¶rg Pflug for exploit!
                             -- finally linked to standard tests page in demo
2005-09-05 - 0.5 -- upgrade to handle cases at http://ha.ckers.org/xss.html
2005-04-24 - 0.4 -- added check for encoded ascii entities
2003-05-31 - 0.3 -- initial public release

License and Disclaimer:
Copyright 2003 Chris Snyder. All rights reserved.

Redistribution and use in source and binary forms, with or without modification, 
are permitted provided that the following conditions are met:

1. Redistributions of source code must retain the above copyright notice, this 
   list of conditions and the following disclaimer.
2. Redistributions in binary form must reproduce the above copyright notice, this 
   list of conditions and the following disclaimer in the documentation and/or other 
   materials provided with the distribution.

THIS SOFTWARE IS PROVIDED ``AS IS'' AND ANY EXPRESS OR IMPLIED WARRANTIES,
INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND
FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
AUTHOR OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;  LOSS OF USE, DATA, OR PROFITS;
OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,
WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR
OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF
ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.  

*/
define( 'SAFE_HTML_VERSION', 'safe_html.php/0.6' );
function safe_html ($html, $allowedtags="") {
  
  // check for obvious oh-noes
  if ( js_and_entity_check( $html ) ) {
    $html= strip_tags($html);
    return $html;
  }
  
  // setup -- $allowedtags is an array of $tag=>$closeit pairs, 
  //   where $tag is an HTML tag to allow and $closeit is 1 if the tag 
  //   requires a matching, closing tag
  if ($allowedtags=="") {
        $allowedtags= array ( "p"=>1, "br"=>0, "a"=>1, "img"=>0, 
                            "li"=>1, "ol"=>1, "ul"=>1, 
                            "b"=>1, "i"=>1, "em"=>1, "strong"=>1, 
                            "del"=>1, "ins"=>1, "u"=>1, "code"=>1, "pre"=>1, 
                            "blockquote"=>1, "hr"=>0, "h1"=>1, "h2"=>1, "h3"=>1, 
                            "table"=>1, "tr"=>1,"td"=>1,"th"=>1, "div"=>1, "span"=>1, "font"=>1
        );
  }
  elseif (!is_array($allowedtags)) {
    $array= array( "$allowedtags" );
  }

  // there's some debate about this.. is strip_tags() better than rolling your own regex?
  // note: a bug in PHP 4.3.1 caused improper handling of ! in tag attributes when using strip_tags()
  $stripallowed= "";
  foreach ($allowedtags AS $tag=>$closeit) {
    $stripallowed.= "<$tag>";
  }

  //print "Stripallowed: $stripallowed -- ".print_r($allowedtags,1);
  $html= strip_tags($html, $stripallowed);

  // also, lets get rid of some pesky attributes that may be set on the remaining tags...
  // this should be changed to keep_attributes($htmlm $goodattrs), or perhaps even better keep_attributes
  //  should be run first. then strip_attributes, if it finds any of those, should cause safe_html to strip all tags.
  $badattrs= array("on\w+", "fs\w+", "seek\w+");
  $html= strip_attributes($html, $badattrs);

  // close html tags if necessary -- note that this WON'T be graceful formatting-wise, it just has to fix any maliciousness
  foreach ($allowedtags AS $tag=>$closeit) {
    if (!$closeit) continue;
    $patternopen= "/<$tag\b[^>]*>/Ui";
    $patternclose= "/<\/$tag\b[^>]*>/Ui";
    $totalopen= preg_match_all ( $patternopen, $html, $matches );
    $totalclose= preg_match_all ( $patternclose, $html, $matches2 );
    if ($totalopen>$totalclose) {
      $html.= str_repeat("</$tag>", ($totalopen - $totalclose));
    }
  }
  
  // check (again!) for obvious oh-noes that might have been caused by tag stipping
  if ( js_and_entity_check( $html ) ) {
    $html= strip_tags($html);
    return $html;
  }

  // close any open <!--'s and identify version just in case

  return $html;
}
//END SAFE HTML LICENSE

function search_highlight($text, $terms_rx)
{

    $start = '(^|<(?:.*?)>)';
    $end   = '($|<(?:.*?)>)';
    return preg_replace(
        "/$start(.*?)$end/se",
        "StripSlashes('\\1').".
            "search_highlight_inner(StripSlashes('\\2'), \$terms_rx).".
            "StripSlashes('\\3')",
        $text
    );
    return;
}

function search_highlight_inner($text, $terms_rx)
{

    $colors = search_get_highlight_colors();
    foreach($terms_rx as $term_rx)
    {
        $color = array_shift($colors);

        $text = preg_replace(
                "/($term_rx)/ise",
                "search_highlight_do(StripSlashes('\\1'), \$color)", 
                $text
            );
    }

    return $text;
}

function search_get_highlight_colors()
{

    return array(
        array('#ffff66','#000000'),
        array('#A0FFFF','#000000'),
        array('#99ff99','#000000'),
        array('#ff9999','#000000'),
        array('#ff66ff','#000000'),
        array('#880000','#ffffff'),
        array('#00aa00','#ffffff'),
        array('#886800','#ffffff'),
        array('#004699','#ffffff'),
        array('#990099','#ffffff'),
    );
}

function search_highlight_do($fragment, $color)
{

    return "<span style=\"background-color: $color[0]; ".
        "color: $color[1]; font-weight: bold;\">".
        "$fragment</span>";
}

function search_pretty_terms_highlighted($terms_html)
{

    $colors = search_get_highlight_colors();
    $temp = array();

    foreach($terms_html as $term_html){
        $color = array_shift($colors);
        $temp[] = search_highlight_do($term_html, $color);
    }

    return search_pretty_terms($temp);
}

function search_split_terms($terms)
{

    $terms = preg_replace("/\"(.*?)\"/e", "search_transform_term('\$1')", $terms);
    $terms = preg_split("/\s+|,/", $terms);

    $out = array();

    foreach($terms as $term){

        $term = preg_replace("/\{WHITESPACE-([0-9]+)\}/e", "chr(\$1)", $term);
        $term = preg_replace("/\{COMMA\}/", ",", $term);

        $out[] = $term;
    }

    return $out;
}

function search_transform_term($term)
{
    $term = preg_replace("/(\s)/e", "'{WHITESPACE-'.ord('\$1').'}'", $term);
    $term = preg_replace("/,/", "{COMMA}", $term);
    return $term;
}



function search_html_escape_terms($terms)
{
    $out = array();

    foreach($terms as $term){
        if (preg_match("/\s|,/", $term)){
            $out[] = '"'.HtmlSpecialChars($term).'"';
        }else{
            $out[] = HtmlSpecialChars($term);
        }
    }

    return $out;	
}

function search_pretty_terms($terms_html)
{

    if (count($terms_html) == 1){
        return array_pop($terms_html);
    }

    $last = array_pop($terms_html);

    return implode(' ', $terms_html)." $last";
}


function getoffset($id)
{
    global $data;
    if (isset($id))
    {
        $sql = $data->select_query("timezones", "WHERE id=$id");
        
        $d = $data->fetch_array($sql);
        
        return ($d['offset'] * 3600);
    }
    else
    {
        return 0;
    }
    return;
}

function getuseroffset($uname)
{
    global $data, $config;
    $uname = safesql($uname, "text");
    $sql = $data->select_query("users", "WHERE uname=$uname");
    $d = $data->fetch_array($sql);
    return getoffset($d['timezone']) - getoffset($config['zone']);
}

function get_path($where)
{
    global $config;
    
    $len = strlen($config['siteaddress']);
    if ($config['siteaddress'][$len-1] != "/")
    {
        return $config['siteaddress'] . "/" . $where;
    }
    else
    {
        return $config['siteaddress'] . $where;
    }
    return;
}

function get_online_users_array()
{
    global $data;
    
    $sql = $data->select_query("onlineusers");
    
    $userList = array();
    while ($temp = $data->fetch_array($sql))
    {
        $userList[$temp['uname']] = $temp['isactive'];
    }

    return $userList;
}

function get_user_id_list_array()
{
    global $data;
    
    $sql = $data->select_query("users", "", "id, uname");
    
    $userList = array();
    while ($temp = $data->fetch_array($sql))
    {
        $userList[$temp['id']] = $temp['uname'];
    }
    $userList[-1] = "Guest";
    
    return $userList;
}

function get_censor()
{
    global $data;
    return $data->select_fetch_all_rows($ignore, "censorwords");
}

function user_online($uname)
{
    global $onlineUserList;
    
    if (!isset($onlineUserList[$uname]))
    {
        return "offline";
    }
    elseif ($onlineUserList[$uname] == 0)
    {
        return "online and inactive";
    }
    elseif ($onlineUserList[$uname] == 1)
    {
        return "online and active";
    }
    return;
}

function get_user_auths()
{
    global $data, $config, $check;

    $userAuth = $data->select_fetch_one_row("auth", "WHERE authname='{$check['id']}' AND type=1");
    
    $userAuth['dynamic'] = unserialize($userAuth['dynamic']);
    $userAuth['permission'] = unserialize($userAuth['permission']);
    $userAuth['static'] = unserialize($userAuth['static']);
    $userAuth['subsites'] = unserialize($userAuth['subsites']);
    
    if ($check['id'] != -1)
    {
        $groups = group_sql_list_id("authname", "OR", true);
        
        $groupAuth = $data->select_fetch_all_rows($groupAuthNumber, "auth", "WHERE ($groups) AND type=2");
        
        for ($i=0;$i<$groupAuthNumber;$i++)
        {
            $groupAuth[$i]['dynamic'] = unserialize($groupAuth[$i]['dynamic']);
            $groupAuth[$i]['permission'] = unserialize($groupAuth[$i]['permission']);
            $groupAuth[$i]['static'] = unserialize($groupAuth[$i]['static']);
            $groupAuth[$i]['subsites'] = unserialize($groupAuth[$i]['subsites']);
        }

        $dynamic = array();
        $permission = array();
        $static = array();
        $subsites = array();
        
        for ($i=0;$i<$groupAuthNumber;$i++)
        {
            foreach ($groupAuth[$i]['dynamic'] as $key => $value)
            {
                if ($value == 1)
                {
                    $dynamic[$key] = 1;
                }
                elseif ($value == -1 && $dynamic[$key] != 1)
                {
                    $dynamic[$key] = 0;
                }
            }
            foreach ($groupAuth[$i]['permission'] as $key => $value)
            {
                if ($value == 1)
                {
                    $permission[$key] = 1;
                }
                elseif ($value == -1 && $permission[$key] != 1)
                {
                    $permission[$key] = 0;
                }
            }
            foreach ($groupAuth[$i]['static'] as $key => $value)
            {
                if ($value == 1)
                {
                    $static[$key] = 1;
                }
                elseif ($value == -1 && $static[$key] != 1)
                {
                    $static[$key] = 0;
                }
            }
            foreach ($groupAuth[$i]['subsites'] as $key => $value)
            {
                if ($value == 1)
                {
                    $subsites[$key] = 1;
                }
                elseif ($value == -1 && $subsites[$key] != 1)
                {
                    $subsites[$key] = 0;
                }
            }
        }
    }

    if (is_array($userAuth['dynamic']))
    {
        foreach ($userAuth['dynamic'] as $key => $value)
        {
            if ($value == 1)
            {
                $dynamic[$key] = 1;
            }
            elseif ($value == -1)
            {
                $dynamic[$key] = 0;
            }
        }
    }
    if (is_array($userAuth['permission']))
    {
        foreach ($userAuth['permission'] as $key => $value)
        {
            if ($value == 1)
            {
                $permission[$key] = 1;
            }
            elseif ($value == -1)
            {
                $permission[$key] = 0;
            }
        }
    }
    if (is_array($userAuth['static']))
    {
        foreach ($userAuth['static'] as $key => $value)
        {
            if ($value == 1)
            {
                $static[$key] = 1;
            }
            elseif ($value == -1)
            {
                $static[$key] = 0;
            }
        }
    }
    if (is_array($userAuth['subsites']))
    {
        foreach ($userAuth['subsites'] as $key => $value)
        {
            if ($value == 1)
            {
                $subsites[$key] = 1;
            }
            elseif ($value == -1)
            {
                $subsites[$key] = 0;
            }
        }   
    }    
    
    $auth['dynamic'] = $dynamic;
    $auth['permission'] = $permission;
    $auth['static'] = $static;
    $auth['subsites'] = $subsites;

    return $auth;
}


function get_auth($page, $type)
{
    global $data, $config, $check, $user_page_auths;
    
    $guestpages = array("register"      => true,
                        "logon"         => true,
                        "patrolpages"   => true,
                        "subsite"       => true,
                        "forgot"        => true,
                        "help"          => true,
                        'rss'           => true);
    
    $exempt = array("rss"           => true,
                    "patrolpages"   => true,
                    "subsite"       => true,
                    "help"          => true,
                    "frontpage"     => true);
                    
    if ($guestpages[$page] == true && $check['uname'] == "Guest") 
    {
        $userauth = 1;
    }
    elseif ($exempt[$page] == true && $check['uname'] != "Guest")
    {
        $userauth = 1;
    }
    else
    {
        if ($check['id'] !== -1)
        {
            $userauth = $config['defaultaccess'];
        }
        else
        {
            $userauth = 0;
        }
        switch($type)
        {
            case 0:
                $safe_page = safesql($page, "text");
                $pages = $data->select_fetch_one_row("functions", "WHERE code=$safe_page", "id");
                if ($user_page_auths['dynamic'][$pages['id']] === 1)
                {
                    $userauth = 1;
                }
                elseif ($user_page_auths['permission'][$pages['id']] === 1)
                {
                    $userauth = 1;
                }
                elseif ($user_page_auths['dynamic'][$pages['id']] === 0)
                {
                    $userauth = 0;
                }
                elseif ($user_page_auths['permission'][$pages['id']] === 0)
                {
                    $userauth = 0;
                }
                break;
            case 1:

                if ($user_page_auths['static'][$page] === 1)
                {
                    $userauth = 1;
                }
                elseif ($user_page_auths['static'][$page] === 0)
                {
                    $userauth = 10;
                }
                break;
            case 2:
                $safe_page = safesql($page, "text");
                $pages = $data->select_fetch_one_row("functions", "WHERE code=$safe_page", "id");
                if ($user_page_auths['permission'][$pages['id']] === 1)
                {
                    $userauth = 1;
                }
                elseif ($user_page_auths['permission'][$pages['id']] === 0)
                {
                    $userauth = 0;
                }
                break;
            case 3:
                if ($user_page_auths['subsites'][$page] === 1)
                {
                    $userauth = 1;
                }
                elseif ($user_page_auths['subsites'][$page] === 0)
                {
                    $userauth = 0;
                }
                break;
        }
    }

    return $userauth;
}

function censor($content)
{
    global $data, $censorWords;
    
    $words_list = array();
    foreach($censorWords as $temp['id'] => $temp['word'])
    {
	$temp['word'] = $temp['word']['word'];
        $size = strlen($temp['word']) - 1;
        $word = '/(?i)';
        $nowild = '';
        for ($i=0;$i<=$size;$i++)
        {
            $char = $temp['word'][$i];
            if ($char != "*" && $char != "?" && $i == 0)
            {
                $word .= "\\b";
            }
            elseif ($i == $size && !($char == "*" || $char == "?"))
            {
                $word .= $char . "\\b";
            }
            elseif ($char == "*" && !($i == 0 || $i == $size))
            {
                $word .= "\\[a-z0-9]*";
            }
            elseif ($char == "?")
            {
                $word .= "[a-z0-9]";
            }
    
            if ($char != "*" && $char != "?" && $i != $size)
            {
                $word .= $char;
            }
            if ($char != "*" && $char != "?")
            {
                $nowild .= $char;
            }
        }
        $word .= '/';

        $replace = "";
        for ($i=0;$i<strlen($nowild);$i++)
        {
            $replace .= "*";
        }
        $content = preg_replace($word, $replace, $content);
    }
    
   return $content;
}

function user_location($uname) 
{
    global $data;
    if ($uname) 
    {
        $uname = safesql($uname, "text");
        $query = $data->select_query("onlineusers", "WHERE uname = $uname", "location");
        if ($data->num_rows($query) > 0)
        {
            $temp = $data->fetch_array($query);
            return $temp['location'];
        }
        else
        {
            return "User not logged in";
        }
    }
    return false;
}//location

function user_groups_list($userid, $notype = false)
{
    global $data;
    $sql = $data->select_query("usergroups", "WHERE userid=$userid");
    if ($data->num_rows($sql) == 0)
    {
        $usersgroups = 'No Groups';
    }
    else
    {
        $usersgroups = '';
    }
    $first = true;
    while ($temp = $data->fetch_array($sql))
    {
        if ($first == false)
        {
            $usersgroups .= ", ";
        }
        else
        {
            $first = false;
        }
        $sql2 = $data->select_query("groups", "WHERE id={$temp['groupid']}", "teamname");
        $temp2 = $data->fetch_array($sql2);
        if (!$notype)
        {
            switch ($temp['utype'])
            {
                case 0 : $type = "#000";
                         break;
                case 1 : $type = "#4460ae";
                         break;
                case 2 : $type = "#356f18";
                         break;
            }
            $usersgroups .= "<span style=\"color:{$type};font-weight:bold;\">{$temp2['teamname']}</span>";
        }
        else
        {
            $usersgroups .= $temp2['teamname'];
        }
    }
    
    return $usersgroups;
}

function user_groups_array($userid)
{
    global $data;
    $sql = $data->select_query("usergroups", "WHERE userid=$userid");
    $usersgroups = array();

    while ($temp = $data->fetch_array($sql))
    {
        $sql2 = $data->select_query("groups", "WHERE id={$temp['groupid']}", "teamname");
        $temp2 = $data->fetch_array($sql2);
        $usersgroups[] = $temp2['teamname'];
    }
    
    return $usersgroups;
}

function user_groups_id_array($userid)
{
    global $data;
    $sql = $data->select_query("usergroups", "WHERE userid=$userid");
    $usersgroups = array();

    while ($temp = $data->fetch_array($sql))
    {
        $usersgroups[] = $temp['groupid'];
    }
    
    return $usersgroups;
}

function user_groups_id_key_array($userid)
{
    global $data;
    $sql = $data->select_query("usergroups", "WHERE userid=$userid");
    $usersgroups = array();

    while ($temp = $data->fetch_array($sql))
    {
        $usersgroups[$temp['groupid']] = 1;
    }
    
    return $usersgroups;
}

function user_patrol_array($userid)
{
    global $data;
    $sql = $data->select_query("usergroups", "WHERE userid=$userid");
    $usersgroups = array();

    while ($temp = $data->fetch_array($sql))
    {
        $sql2 = $data->select_query("groups", "WHERE id={$temp['groupid']} and ispatrol=1", "teamname");
        $temp2 = $data->fetch_array($sql2);
        if ($data->num_rows($sql2) > 0)
            $usersgroups[] = $temp2['teamname'];
    }
    
    return $usersgroups;
}

function user_patrol_id_array($userid)
{
    global $data;
    $sql = $data->select_query("usergroups", "WHERE userid=$userid");
    $usersgroups = array();

    while ($temp = $data->fetch_array($sql))
    {
        $sql2 = $data->select_query("groups", "WHERE id={$temp['groupid']} and ispatrol=1", "id");
        $temp2 = $data->fetch_array($sql2);
        if ($data->num_rows($sql2) > 0)
            $usersgroups[] = $temp2['id'];
    }
    
    return $usersgroups;
}


function user_public_id_array($userid)
{
    global $data;
    $sql = $data->select_query("usergroups", "WHERE userid=$userid");
    $usersgroups = array();

    while ($temp = $data->fetch_array($sql))
    {
        $sql2 = $data->select_query("groups", "WHERE id={$temp['groupid']} and ispublic=1", "id");
        $temp2 = $data->fetch_array($sql2);
        if ($data->num_rows($sql2) > 0)
            $usersgroups[] = $temp2['id'];
    }
    
    return $usersgroups;
}

function user_public_array($userid)
{
    global $data;
    $sql = $data->select_query("usergroups", "WHERE userid=$userid");
    $usersgroups = array();

    while ($temp = $data->fetch_array($sql))
    {
        $sql2 = $data->select_query("groups", "WHERE id={$temp['groupid']} and ispublic=1", "id, teamname");
        $temp2 = $data->fetch_array($sql2);
        if ($data->num_rows($sql2) > 0)
	{
            $usersgroups[] = $temp2;
	}
    }
    
    return $usersgroups;
}

function group_users_list($groupid)
{
    global $data;
    $sql = $data->select_query("usergroups", "WHERE groupid=$groupid");
    if ($data->num_rows($sql) == 0)
    {
        $groupsusers = 'No Users';
    }
    else
    {
        $groupsusers = '';
    }
    $first = true;
    while ($temp = $data->fetch_array($sql))
    {
        if ($first == false)
        {
            $groupsusers .= ", ";
        }
        else
        {
            $first = false;
        }
        $sql2 = $data->select_query("users", "WHERE id={$temp['userid']}", "uname");
        $temp2 = $data->fetch_array($sql2);
        switch ($temp['utype'])
        {
            case 0 : $type = "";
                     break;
            case 1 : $type = "AGL";
                     break;
            case 2 : $type = "GL";
                     break;
        }
        $groupsusers .= $temp2['uname'] . " ($type)";
    }
    
    return $groupsusers;
}

function group_users_linked_list($groupid)
{
    global $data;
    $sql = $data->select_query("usergroups", "WHERE groupid=$groupid");
    if ($data->num_rows($sql) == 0)
    {
        $groupsusers = 'No Users';
    }
    else
    {
        $groupsusers = '';
    }
    $first = true;
    $users = group_users_array($groupid);

    for ($i=0;$i<$data->num_rows($sql);$i++)
    {
        if ($first == false)
        {
            $groupsusers .= ", ";
        }
        else
        {
            $first = false;
        }
        $uname = safesql($users[$i], "text");
        $sql2 = $data->select_query("users", "WHERE uname=$uname", "id,uname");
        $temp2 = $data->fetch_array($sql2);
        switch ($temp['utype'])
        {
            case 0 : $type = "Normal User";
                     break;
            case 1 : $type = "Assistant Group Leader";
                     break;
            case 2 : $type = "Group Leader";
                     break;
        }
        $groupsusers .= "<a href=\"admin.php?page=users&subpage=users_view&id={$temp2['id']}\" title=\"$type\">" . $temp2['uname'] . "</a>";
    }
    
    return $groupsusers;
}

function group_users_array($groupid)
{
    global $data;
    $sql = $data->select_query("usergroups", "WHERE groupid=$groupid");
    $groupsusers = array();
    
    while ($temp = $data->fetch_array($sql))
    {
        $sql2 = $data->select_query("users", "WHERE id={$temp['userid']}", "uname");
        $temp2 = $data->fetch_array($sql2);
        $groupsusers[] = $temp2['uname'];
    }
    sort($groupsusers);
    return $groupsusers;
}

function group_users_id_array($groupid)
{
    global $data;
    $sql = $data->select_query("usergroups", "WHERE groupid=$groupid");
    $groupsusers = array();

    while ($temp = $data->fetch_array($sql))
    {
        $groupsusers[] = $temp['userid'];
    }
    
    return $groupsusers;
}

function user_type($userid, $groupid)
{
    global $data;

    if (is_int($groupid))
    {
        $temp = $data->select_fetch_one_row("usergroups", "WHERE userid=$userid AND groupid=$groupid", "type");
    }
    else
    {
        $groupid = safesql($groupid, "text");
        $sql = $data->select_fetch_one_row("groups", "WHERE teamname=$groupid", "id");
        $temp = $data->select_fetch_one_row("usergroups", "WHERE userid=$userid AND groupid={$sql['id']}", "utype");
    }
    return $temp['utype'];
}

function group_sql_list($field, $connector)
{
    global $check;
    $userpatrols = user_patrol_array($check['id']);
    if (count($userpatrols) > 0)
    {
        $patrols = '';
        for($i=0;$i<count($userpatrols);$i++)
        {
            if (user_type($check['id'], $userpatrols[$i]) == 2 || user_type($check['id'], $userpatrols[$i]) == 1)
            {
                $patrols .= "$field = " . safesql($userpatrols[$i], "text");
                if (($i < count($userpatrols)-1) && (user_type($check['id'], $userpatrols[$i+1]) == 2 || user_type($check['id'], $userpatrols[$i+1]) == 1))
                {
                    $patrols .= " $connector ";
                }
            }
        }
    }
    else
    {
	    return "0=1";
    }
    
    return $patrols;
}

function group_sql_list_normal($field, $connector, $type=false)
{
    global $check;
    
    if ($type=false)
    {
        $userpatrols = user_patrol_array($check['id']);
    }
    elseif ($type=true)
    {
        $userpatrols = user_groups_array($check['id']);
    }
    if (count($userpatrols) > 0)
    {
        for($i=0;$i<count($userpatrols);$i++)
        {
            $patrols .= "$field = " . safesql($userpatrols[$i], "text");
            if ($i < count($userpatrols)-1)
                $patrols .= " $connector ";
        }
    }
   else
    {
	    return "0=1";
    }
    
    return $patrols;
}

function group_sql_list_id($field, $connector, $type=false, $uid=false)
{
    global $check;
    
    $uid = $uid ? $uid : $check['id'];
    if ($type=false)
    {
        $userpatrols = user_patrol_id_array($uid);
    }
    elseif ($type=true)
    {
        $userpatrols = user_groups_id_array($uid);
    }
    if (count($userpatrols) > 0)
    {
        for($i=0;$i<count($userpatrols);$i++)
        {
            $patrols .= "$field = " . safesql($userpatrols[$i], "int");
            if ($i < count($userpatrols)-1)
                $patrols .= " $connector ";
        }
    }
   else
    {
	    return "0=1";
    }
    
    return $patrols;
}

function public_group_sql_list_id($field, $connector)
{
    global $check;
    
    $userpatrols = user_public_id_array($check['id']);

    if (count($userpatrols) > 0)
    {
        for($i=0;$i<count($userpatrols);$i++)
        {
            $patrols .= "$field = " . safesql($userpatrols[$i], "text");
            if ($i < count($userpatrols)-1)
                $patrols .= " $connector ";
        }
    }
   else
    {
	    return "0=1";
    }
    
    return $patrols;
}

function group_users_sql_list($field, $connector, $type=false)
{
    global $data, $check;
    
    if ($type=false)
    {
        $userpatrols = user_patrol_array($check['id']);
    }
    elseif ($type=true)
    {
        $userpatrols = user_groups_id_array($check['id']);
    }
    if (count($userpatrols) > 0)
    {
        for($i=0;$i<count($userpatrols);$i++)
        {
            $userlist = group_users_id_array($userpatrols[$i]);

            for($j=0;$j<count($userlist);$j++)
            {
                $uname = $data->select_fetch_one_row("users", "WHERE id={$userlist[$j]}", "uname");
                $patrols .= "$field = " . safesql($uname['uname'], "text");
                if (!($i == (count($userpatrols) - 1) && $j == (count($userlist) - 1)))
                {
                    $patrols .= " $connector ";
                }
            }
        }
    }
   else
    {
	    return "0=1";
    }
    
    return $patrols;
}

function user_group($uid, $groupname)
{
    global $data;
    $groupname = safesql($groupname, "text");
    $sql1 = $data->select_query("groups", "WHERE teamname = $groupname");
    
    if ($data->num_rows($sql1) > 0)
    {
        $groupid = $data->fetch_array($sql1);
        $groupid = $groupid['id'];
        $uid = safesql($uid, "int");
        $sql = $data->select_query("usergroups", "WHERE userid = $uid AND groupid = $groupid");
        
        if ($data->num_rows($sql) > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    else
    {
        return false;
    }
    return;
}

function user_group_id($uid, $gid)
{
    global $data;
    $gid = safesql($gid, "int");
    $uid = safesql($uid, "int");
    $sql = $data->select_query("usergroups", "WHERE userid = $uid AND groupid = $gid");
    
    if ($data->num_rows($sql) > 0)
    {
        return true;
    }
    else
    {
        return false;
    }
    return;
}

function owner_items_sql_list($field, $type, $groups=true, $uid=false)
{
    global $data, $check;
    $type = safesql($type, "text");
    $uid = $uid ? $uid : $check['id'];

    if ($groups)
    {
        $grouplist = group_sql_list_id("owner_id", "OR", true, $uid);
        
        $uid = safesql($uid, "int");
        $sql = $data->select_query("owners", "WHERE `item_type` = $type AND ((owner_id=$uid AND owner_type=0) OR (($grouplist) AND owner_type=1))");
    }
    else
    {
        $uid = safesql($uid, "int");
        $sql = $data->select_query("owners", "WHERE `item_type` = $type AND owner_id=$uid AND owner_type=0");
    }
    
    $sqlitem = '';
    $i = 0;
    $numitems = $data->num_rows ($sql);
    if ($numitems > 0)
    {
	    while ($temp = $data->fetch_array($sql))
	    {
		$sqlitem .= "$field=" . $temp["item_id"];
		if ($i < $numitems-1)
		{
		    $sqlitem .= " OR ";
		}
		$i++;
	    }
    }
   else
    {
	    return "0=1";
    }
    return $sqlitem;
}

function is_owner($itemid, $itemtype)
{
	global $data, $check;
        $grouplist = group_sql_list_id("owner_id", "OR", true);
	$itemid = safesql($itemid, "int");
	$itemtype = safesql($itemtype, "text");
        
        $uid = safesql($check['id'], "int");
	$timestamp = time();
       return $data->num_rows($data->select_query("owners", "WHERE `item_id` = $itemid AND `item_type` = $itemtype AND ((owner_id=$uid AND owner_type=0) OR (($grouplist) AND owner_type=1)) AND (expire > $timestamp OR expire = 0)"));
}


function check_duplicate($table, $field, $safe_item, $safe_id = false)
{
    global $data;
    
    $where = '';
    if ($safe_id)
    {
        $where = "AND NOT(id = $safe_id)"; 
    }
    if ($data->num_rows($data->select_query($table, "WHERE $field = $safe_item $where")))
    {
        return true;
    }
    else
    {
        return false;
    }
    return;
}

function checkValid($id, $type, $required, $minlength, $maxlength, $regex)
{
    $name = trim($_POST[$id]);
    $valid = false;

    switch ($type)
    {
        case "text":
            if ($required == 'false' || $name != "")
            {
                $valid = true;  
                if ($minlength > 0 || $maxlength > 0)
                { 
                    if ($minlength == 0 && strlen($name) > $maxlength && $maxlength > 0)
                    {
                        $valid = false;
                    }
                    else if ($maxlength == 0 && strlen($name) < $minlength && $minlength > 0)
                    {
                        $valid = false;
                    }
                    else if ((strlen($name) < $minlength && $minlength > 0) || (strlen($name) > $maxlength && $maxlength > 0))
                    {
                        $valid = false;
                    }
                }
            }
            break;
        case "date":
            $dateregex = "/^(19|20)\d\d[- \/.](0[1-9]|1[012])[- \/.](0[1-9]|[12][0-9]|3[01])/";
            if ($required == 'true' || $name != "")
            {            
                if (preg_match($dateregex, $name))
                {
                    $valid = true;
                }
            }
            elseif ($required == 'false' && $name == "")
            {
                $valid = true;
            }
            break;
        case "number":
            $numberreg = "/^[0-9]*$/";
            if ($required == 'true' || $name != "")
            {            
                if (preg_match($numberreg, $name))
                {
                    $valid = true;
                }
            }
            else if ($required == 'false' && $name == "")
            {
                $valid = true;
            }        
            break;
        case "email":
	        $emailreg  = "/^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/";
            if ($required == 'true' || $name != "")
            {            
                if (preg_match($emailreg, $name))
                {
                    $valid = true;
                }
            }
            else if ($required == 'false' && $name == "")
            {
                $valid = true;
            }
            break;
        case "custom":
            if ($required == 'true' || $name != "")
            {            
                if (preg_match($regex, $name))
                {
                    $valid = true;
                }
            }
            else if ($required == 'false' && $name == "")
            {
                $valid = true;
            }
            break;
        case "duplicate":
            if ($required == 'true' || $name != "")
            {            
                if ($_POST[$regex] == $name)
                {
                    $valid = true;
                }
            }
            else if ($required == 'false' && $name == "")
            {
                $valid = true;
            }
            break;
    }
    
    if ($required == 'true' && $name == "")
    {
        $valid = false;
    }

    return $valid;
}

function validate($validators)
{
    $validate = explode(";", $validators);
    $overall = true;
    for ($i=0;$i<sizeof($validate);$i++)
    {
        $validateargs = explode(",", $validate[$i]);
        $valid = checkValid(trim($validateargs[0]),trim($validateargs[1]),trim($validateargs[2]),trim($validateargs[3]),trim($validateargs[4]),trim($validateargs[5]));
        
        $overall = $overall && $valid;
    }  

    return $overall;
}

function rgb2hex2rgb($c)
{
    if(!$c) return false;
    $c = trim($c);
    $out = false;
    if(eregi("^[0-9ABCDEFabcdef\#]+$", $c))
    {
        $c = str_replace('#','', $c);
        $l = strlen($c);
        if($l == 3)
        {
            unset($out);
            $out[0] = $out['r'] = $out['red'] = hexdec(substr($c, 0,1));
            $out[1] = $out['g'] = $out['green'] = hexdec(substr($c, 1,1));
            $out[2] = $out['b'] = $out['blue'] = hexdec(substr($c, 2,1));
        }
        elseif($l == 6)
        {
            unset($out);
            $out[0] = $out['r'] = $out['red'] = hexdec(substr($c, 0,2));
            $out[1] = $out['g'] = $out['green'] = hexdec(substr($c, 2,2));
            $out[2] = $out['b'] = $out['blue'] = hexdec(substr($c, 4,2));
        }
        else
        {
            $out = false;
        }
       
    }
    elseif (eregi("^[0-9]+(,| |.)+[0-9]+(,| |.)+[0-9]+$", $c))
    {
        if(eregi(",", $c))
            $e = explode(",",$c);
        else if(eregi(" ", $c))
            $e = explode(" ",$c);
        else if(eregi(".", $c))
            $e = explode(".",$c);
        else return false;
       
        if(count($e) != 3) return false;
       
        $out = '#';
        for($i = 0; $i<3; $i++)
            $e[$i] = dechex(($e[$i] <= 0)?0:(($e[$i] >= 255)?255:$e[$i]));
       
        for($i = 0; $i<3; $i++)
            $out .= ((strlen($e[$i]) < 2)?'0':'').$e[$i];
           
        $out = strtoupper($out);
    }
    else
    {
        $out = false;
    }
   
    return $out;
}

function in_group($groups)
{
    global $check, $user_groups;
    
    $allowed = false;
    while (list($groupid, $value) = each($groups)) 
    {
        if ($user_groups[$groupid] && $value == 1)
        {
            $allowed = true;
            break;
        }
    }
    
    if ($check['uname'] == "Guest" && $groups[0] == 1)
    {
        $allowed = true;
    }
    return $allowed;
}

function confirm($type)
{
    global $config;
    
    $confirm = true;
    
    switch($type)
    {
        case 'article':
            $confirmtype = $config['confirmarticle'];
            break;
        case 'poll':
            $confirmtype = $config['confirmpoll'];
            break;
        case 'event':
            $confirmtype = $config['confirmevent'];
            break;
        case 'album':
            $confirmtype = $config['confirmalbum'];
            break;
        case 'download':
            $confirmtype = $config['confirmdownload'];
            break;
        case 'news':
            $confirmtype = $config['confirmnews'];
            break;
        case 'photo':
            $confirmtype = $config['confirmphoto'];
            break;
        case 'comment':
            $confirmtype = $config['confirmcomment'];
            break;
    }
    
    switch($confirmtype)
    {
        case 2:
            $confirm = true;
            break;
        case 1:
            $confirm = !in_group($config['exclusion']);
            break;
        case 0:
            $confirm = false;
            break;
    }    
    return $confirm;
}

function email($type, $options)
{
    global $data, $check, $config;
    $safe_type = safesql($type, "text");
    $email = $data->select_fetch_one_row("emails", "WHERE type=$safe_type");
    
    $postuname = $check['uname'];
    $website = $config['troopname'];

    switch($type)
    {
        case "newitem":
            switch($options[0])
            {
                case 'article':
                    $title = $options[1]['title'];
                    $type = "article";
                    $link = $config['siteaddress'] . "index.php?page=patrolarticle&action=view&id={$options[1]['ID']}";
                    $extract = truncate(strip_tags($options[1]['detail']), 100);
                    break;
                case 'poll':
                    $title = $options[1]['question'];
                    $type = "poll";
                    $link = $config['siteaddress'] . "index.php?page=polls&id={$options[1]['id']}";
                    $extract = "None";
                    break;
                case 'event':
                    $title = $options[1]['summary'];
                    $type = "event";
                    $startdate = strftime("%Y-%m-%d", $options[1]['startdate']);
                    $starttime = strftime("%H:%M", $options[1]['startdate']);
                    $enddate = strftime("%Y-%m-%d", $options[1]['enddate']);
                    $endtime = strftime("%H:%M", $options[1]['enddate']);
                    
                    $dateDetails = "Start Date: $startdate\r\nStart Time: $starttime\r\nEnd Date: $enddate\r\nEnd Time: $endtime";
                    
                    $date = getdate($options[1]['startdate']);
                    $month = $date['mon'];
                    $year = $date['year'];
                    
                    $link = $options[1]['detail'] ? $config['siteaddress'] . "index.php?page=calender&id={$options[1]['id']}" : $config['siteaddress'] . "index.php?page=calender&view=month&month=$month&year=$year";
                    $extract = $options[1]['detail'] ? $dateDetails . "\r\n\r\n" . truncate(strip_tags($options[1]['detail']), 100) : $dateDetails;
                    break;
                case 'album':
                    $title = $options[1]['album_name'];
                    $type = "album";
                    $link = $config['siteaddress'] . "index.php?page=photos&album={$options[1]['ID']}";
                    $extract = "None";
                    break;
                case 'download':
                    $title = $options[1]['name'];
                    $type = "download";
                    $link = $config['siteaddress'] . "index.php?page=downloads&id={$options[1]['id']}&action=down&catid={$options[1]['cat']}";
                    $extract = truncate($options[1]['descs'], 100);
                    break;
                case 'news':
                    $title = $options[1]['title'];
                    $type = "news item";
                    $link = $config['siteaddress'] . "index.php?page=news&id={$options[1]['id']}";
                    $extract = truncate($options[1]['news'], 100);
                    break;
            }
            break;
    }
    
    $cmscoutTags = array("!#postuname#!", "!#title#!", "!#type#!", "!#link#!", "!#extract#!", "!#website#!");
    $replacements   = array($postuname, $title, $type, $link, $extract, $website);

    $emailContent = str_replace($cmscoutTags, $replacements, $email['email']);

    emailUsers($email['subject'], $emailContent, $type);
}

function confirmMail($type, $item)
{
    global $config, $data, $check;
    
    if ($config['notify'])
    {
        $safe_type = safesql("confirm", "text");
        $email = $data->select_fetch_one_row("emails", "WHERE type=$safe_type");
        $postuname = $check['uname'];
        $website = $config['troopname'];
        switch($type)
        {
            case 'article':
                $title = $item['title'];
                $type = "article";
                $link = $config['siteaddress'] . "index.php?page=patrolarticle&action=view&id={$item['ID']}";
                $extract = truncate(strip_tags($item['detail']), 100);
                $id = $item['ID'];
                break;
            case 'poll':
                $title = $item['question'];
                $type = "poll";
                $link = $config['siteaddress'] . "index.php?page=polls&id={$item['id']}";
                $extract = "None";
                $id = $item['id'];
                break;
            case 'event':
                $title = $item['summary'];
                $type = "event";
                $link = $item['detail'] ? $config['siteaddress'] . "index.php?page=calender&id={$item['id']}" : "No link";
                $extract = $item['detail'] ? truncate(strip_tags($item['detail']), 100) : "None";
                $id = $item['id'];
                break;
            case 'album':
                $title = $item['album_name'];
                $type = "album";
                $link = $config['siteaddress'] . "index.php?page=photos&album={$item['ID']}";
                $extract = "None";
                $id = $item['ID'];
                break;
            case 'download':
                $title = $item['name'];
                $type = "download";
                $link = $config['siteaddress'] . "index.php?page=downloads&id={$item['id']}&action=down&catid={$item['cat']}";
                $extract = truncate($item['descs'], 100);
                $id = $item['id'];
                break;
            case 'news':
                $title = $item['title'];
                $type = "news";
                $link = $config['siteaddress'] . "index.php?page=news&id={$item['id']}";
                $extract = truncate($item['news'], 100);
                $id = $item['id'];
                break;
            case 'photo':
                $title = "photo in" . $item['album_name'];
                $type = "photo";
                $link = $config['siteaddress'] . "index.php?page=photos&album={$item['ID']}";
                $extract = "none";
                $id = $item['ID'];
                break;
        }
            
        $cmscoutTags = array("!#uname#!", "!#postuname#!", "!#title#!", "!#type#!", "!#link#!", "!#extract#!", "!#website#!");
        $replacements   = array("webmaster", $postuname, $title, $type, $link, $extract, $website);

        $emailContent = str_replace($cmscoutTags, $replacements, $email['email']);
        
        if ($type != "photo")
        {
            $type = safesql($type, "text");
            $data->insert_query("review", "$id, $type");
        }
        
        sendMail($config['sitemail'], "Webmaster",$config['emailPrefix'] . $email['subject'], $emailContent);
    }
}

function emailUsers($subject, $email, $type)
{
    global $check, $data, $config;
    
    switch($type)
    {
        case 'article':
            $userSQL = $data->select_query("users", "WHERE id != {$check['id']} AND newarticle=1 AND allowemail=1", "uname, email");
            break;
        case 'poll':
            $userSQL = $data->select_query("users", "WHERE id != {$check['id']} AND newpoll=1 AND allowemail=1", "uname, email");
            break;
        case 'event':
            $userSQL = $data->select_query("users", "WHERE id != {$check['id']} AND newevent=1 AND allowemail=1", "uname, email");
            break;
        case 'album':
            $userSQL = $data->select_query("users", "WHERE id != {$check['id']} AND newalbum=1 AND allowemail=1", "uname, email");
            break;
        case 'download':
            $userSQL = $data->select_query("users", "WHERE id != {$check['id']} AND  newdownload=1 AND allowemail=1", "uname, email");
            break;
        case 'news':
            $userSQL = $data->select_query("users", "WHERE id != {$check['id']} AND newnews=1 AND allowemail=1", "uname, email");
            break;         
    }
    
    while($uinfo = $data->fetch_array($userSQL))
    {
        sendMail($uinfo['email'], $uinfo['uname'], $config['emailPrefix'] . $subject, str_replace("!#uname#!", $uinfo['uname'], $email));
    }
}

function sendMail($address, $name, $subject, $message)
{
    global $config;
    
    if ($config['allowemails'])
    {
        // instantiate the class
        $mail = new mailer();

        // Set the subject
        $mail->Subject = $subject;

        // Body
        $mail->Body = $message;

        // Add an address to send to.
        $mail->AddAddress($address, $name);

        $mail->Send();

        $mail->ClearAddresses();
        $mail->ClearAttachments();
    }
}

function truncate($string, $length = 80, $etc = '...',$break_words = false, $middle = false)
{
    if ($length == 0)
        return '';

    if (strlen($string) > $length) {
        $length -= min($length, strlen($etc));
        if (!$break_words && !$middle) {
            $string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length+1));
        }
        if(!$middle) {
            return substr($string, 0, $length) . $etc;
        } else {
            return substr($string, 0, $length/2) . $etc . substr($string, -$length/2);
        }
    } else {
        return $string;
    }
}

function forumEmail($type, $post, $fid, $topic_id=false)
{
    global $data, $check, $config;
    
    $user_id = safesql($check['id'], "int");
    
    $postuname = $check['uname'];
    $website = $config['troopname'];
    $link = get_path("index.php?page=forums&action=topic&t=$topic_id&late=1");
    $extract = truncate($post['posttext'], 300);
    $id = $post['id'];   
    if ($type == "reply")
    {
        $sqls = $data->select_query("forumstopicwatch", "WHERE topic_id=$topic_id AND uid != $user_id AND (notify=1 OR notify=2)");
        $email = $data->select_fetch_one_row("emails", "WHERE type='reply'");
        $topic = $data->select_fetch_one_row("forumtopics", "WHERE id=$topic_id");
        $title = $topic['subject'];
        while($topicwatch = $data->fetch_array($sqls))
        {
            $tempsql = $data->select_query("users", "WHERE id='{$topicwatch['uid']}'");
            $temp = $data->fetch_array($tempsql);
            
            if ($check['id'] != "-1")
            {
                $usergroups = user_groups_id_array($check['id']);
            }
            else
            {
                $usergroups = array(0 => "-1");
            }
            
            $sql2 = $data->select_query("forumauths", "WHERE forum_id=$fid");
            $auth = $data->fetch_array($sql2);
            
            $view_forum = unserialize($auth['view_forum']);
            $read_topics = unserialize($auth['read_topics']);
            
            $viewauth = 0;
            $readauth = 0;
            for($i=0;$i<count($usergroups);$i++)
            {                
                $viewauth = $viewauth || $view_forum[$usergroups[$i]];
                $readauth = $readauth || $read_topics[$usergroups[$i]];
            }
            if($topicwatch['notify'] == 1 )
            {
                $data->update_query("forumstopicwatch", "notify=0", "topic_id=$topic_id AND uid='{$topicwatch['username']}'");
            }
            if ($viewauth == 1 && $readauth == 1 && $temp['allowemail'] == 1 && $temp['replytopic'] == 1)
            {
                $type = "forum reply";
                $cmscoutTags = array("!#uname#!", "!#postuname#!", "!#title#!", "!#type#!", "!#link#!", "!#extract#!", "!#website#!");
                $replacements   = array($temp['uname'], $postuname, $title, $type, $link, $extract, $website);
                $emailContent = str_replace($cmscoutTags, $replacements, $email['email']);
                sendMail($temp['email'], $temp['uname'], $config['emailPrefix'] . $email['subject'], $emailContent);
            }
        }
    }
    elseif ($type == "newtopic")
    {
        $email = $data->select_fetch_one_row("emails", "WHERE type='newtopic'");
        $topic = $data->select_fetch_one_row("forumtopics", "WHERE id=$topic_id");
        $title = $topic['subject'];
        $sqls = $data->select_query("users", "WHERE newtopic=1 AND allowemail=1 AND id != {$check['id']}", "id, uname, email");
        $topicpath = get_path("index.php?page=forums&action=topic&t={$topic['id']}");
        while($topicwatch = $data->fetch_array($sqls))
        {
            $tempstuff = $data->fetch_array($sql2);
            $usergroups = user_groups_id_array($topicwatch['id']);
            
            $sql = $data->select_query("forumauths", "WHERE forum_id=$fid");
            $auth = $data->fetch_array($sql);
            $view_forum = unserialize($auth['view_forum']);
            $read_topics = unserialize($auth['read_topics']);
            
            $viewauth = 0;
            $readauth = 0;
            for($i=0;$i<count($usergroups);$i++)
            {                
                $viewauth = $viewauth || $view_forum[$usergroups[$i]];
                $readauth = $readauth || $read_topics[$usergroups[$i]];
            }
          
            if ($readauth == 1 && $viewauth == 1)
            {
                $type = "new topic";
                $cmscoutTags = array("!#uname#!", "!#postuname#!", "!#title#!", "!#type#!", "!#link#!", "!#extract#!", "!#website#!");
                $replacements   = array($topicwatch['uname'], $postuname, $title, $type, $link, $extract, $website);
                $emailContent = str_replace($cmscoutTags, $replacements, $email['email']);
                sendMail($topicwatch['email'], $topicwatch['uname'], $config['emailPrefix'] . $email['subject'], $emailContent);
            }
        }
    }
}

function email_user($id, $safe_type)
{
    global $data, $check, $config;
    $safe_type = safesql($type, "text");
    $email = $data->select_fetch_one_row("emails", "WHERE type=$safe_type");

     $temp = $data->select_fetch_one_row("users", "WHERE id = $id", "id, uname, email");

    $postuname = $check['uname'];
    $website = $config['troopname'];

    $cmscoutTags = array("!#postuname#!", "!#title#!", "!#type#!", "!#link#!", "!#extract#!", "!#website#!");
    $replacements   = array($postuname, $title, '', '', '', $website);

    $emailContent = str_replace($cmscoutTags, $replacements, $email['email']);
    
    sendMail($temp['email'], $temp['uname'], $config['emailPrefix'] . $email['subject'], $emailContent);
}

function sql_list($array, $field, $connector)
{
    if (count($array) > 0)
    {
        $list = '';
        for($i=0;$i<count($array);$i++)
        {
            $list .= "$field = " . safesql($array[$i], "text");
            if (($i < count($array)-1))
            {
                $list .= " $connector ";
            }
        }
    }
    
    return $list;
}

function adminauth($moduleid, $typeauth)
{
    global $data, $check;
    
        $sql = $data->select_query("usergroups", "WHERE userid = {$check['id']}");
        
	$userauth = array();
        while ($temp = $data->fetch_array($sql))
        {
            $sql2 = $data->select_query("groups", "WHERE id = {$temp['groupid']}");
            $groupinfo = $data->fetch_array($sql2);
            
            if ($temp['utype'] == 0)
            {
                $tempauth = unserialize($groupinfo['normaladmin']);
            }
            elseif ($temp['utype'] == 1)
            {
                $tempauth = unserialize($groupinfo['agladmin']);
            }
            elseif ($temp['utype'] == 2)
            {
                $tempauth = unserialize($groupinfo['gladmin']);
            }

    
	    foreach($tempauth['access'] as $key => $notused)
	    {
		$userauth['access'][$key] = $userauth['access'][$key] || $tempauth['access'][$key];
		$userauth['add'][$key] = $userauth['add'][$key] || $tempauth['add'][$key];
		$userauth['edit'][$key] = $userauth['edit'][$key] || $tempauth['edit'][$key];
		$userauth['delete'][$key] = $userauth['delete'][$key] || $tempauth['delete'][$key];
		$userauth['publish'][$key] = $userauth['publish'][$key] || $tempauth['publish'][$key];
		$userauth['limit'][$key] = $userauth['limit'][$key] || $tempauth['limit'][$key];
	    }
        }
	
    switch($typeauth)
    {
        case "access":  return $userauth['access'][$moduleid];
        case "add":  return $userauth['access'][$moduleid] && $userauth['add'][$moduleid];
        case "edit":  return $userauth['access'][$moduleid] && $userauth['edit'][$moduleid];
        case "delete":  return $userauth['access'][$moduleid] && $userauth['delete'][$moduleid];
        case "publish":  return $userauth['access'][$moduleid] && $userauth['publish'][$moduleid];
        case "limit":  return$userauth['access'][$moduleid] &&  $userauth['limit'][$moduleid];
    }
}


function html_decode($string ) 
{
	$arrayuse = array_flip(get_html_translation_table(HTML_SPECIALCHARS));
	$arrayuse['&nbsp;'] = " ";
	return strtr($string, $arrayuse);
}


?>
