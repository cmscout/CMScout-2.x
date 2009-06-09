<?php
/**************************************************************************
    FILENAME        :   scripts.php
    PURPOSE OF FILE :   Includes scripts to be used in CMScout
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

$tinyMCE = array();
$scriptIncludes = array();
$cssIncludes = array();
$domReady = array();

$tinyMCEGzip ['advanced'] = 'tinyMCE_GZ.init({
  plugins : "inlinepopups,layer,spellchecker,media,emotions,table,advhr,insertdatetime,searchreplace,print,contextmenu,paste,fullscreen,style",
  themes : "advanced",
	languages : "en",
	disk_cache : true,
	debug : false
});';

$tinyMCE['advanced'] = 'tinyMCE.init
    ({
        mode : "exact",
        elements: "story",
        theme : "advanced",
        languages : "en",
        plugins : "emotions,table,advhr,insertdatetime,searchreplace,print,contextmenu,paste,fullscreen,inlinepopups,layer,spellchecker,media,style",
        theme_advanced_buttons1_add : "fontselect,fontsizeselect,styleprops,separator,spellchecker",
        theme_advanced_buttons2_add : "separator,insertdate,inserttime,media,separator,forecolor,backcolor",
        theme_advanced_buttons2_add_before: "cut,copy,paste,pastetext,pasteword,separator,search,replace,separator",
        theme_advanced_buttons3_add_before : "tablecontrols,separator",
        theme_advanced_buttons3_add : "emotions,advhr,separator,print,separator,fullscreen,separator,insertlayer",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true,
        theme_advanced_resize_horizontal : false,
        theme_advanced_path_location : "bottom",
        plugin_insertdate_dateFormat : "%Y-%m-%d",
        plugin_insertdate_timeFormat : "%H:%M:%S",
        spellchecker_languages : "+English=en",
        file_browser_callback : "fileBrowser",
        content_css : "default.css",
        extended_valid_elements : "a[name|href|target|title|style],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|name|style],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
        doctype : "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\"  \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">"
     });';

$tinyMCEGzip ['simple'] = "tinyMCE_GZ.init({
	plugins : 'emotions,contextmenu,paste,inlinepopups,spellchecker,media,searchreplace',
	themes : 'advanced',
	languages : 'en',
	disk_cache : true,
	debug : false
});";  

$tinyMCE['simple'] = 'tinyMCE.init({
		mode : "exact",
        elements: "story",
		theme : "advanced",
		plugins : "emotions,contextmenu,paste,inlinepopups,spellchecker,media,searchreplace",
        theme_advanced_buttons1_add : "separator,spellchecker",
        theme_advanced_buttons2_add : "media",
        theme_advanced_buttons2_add_before: "cut,copy,paste,pastetext,pasteword,separator,search,replace,separator",
        theme_advanced_buttons3_add : "emotions,separator,fontselect,fontsizeselect,forecolor,backcolor",
		theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true,
        theme_advanced_resize_horizontal : false,
        theme_advanced_path_location : "bottom",
	    plugin_insertdate_dateFormat : "%Y-%m-%d",
	    plugin_insertdate_timeFormat : "%H:%M:%S",
        file_browser_callback : "fileBrowser",
        content_css : "default.css",
		extended_valid_elements : "a[name|href|target|title],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|name|style],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
       doctype : "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\"  \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">"
	});';
    
$scriptIncludes['tinymce'] = 'tiny_mce/tiny_mce_gzip.js';
$scriptIncludes['mootools'] = 'scripts/mootools.js';
$scriptIncludes['gallery'] = 'scripts/jd.gallery.js';
$scriptIncludes['datepicker'] = 'scripts/datepicker.js';
$scriptIncludes['mooRainbow'] = 'scripts/mooRainbow.js';
$scriptIncludes['mootabs'] = 'scripts/SimpleTabs.js';
$scriptIncludes['slimbox'] = 'scripts/slimbox.js';

$cssIncludes['gallery'] = 'jd.gallery.css';
$cssIncludes['datepicker'] = 'datepicker.css';
$cssIncludes['slimbox'] = 'slimbox.css';
$cssIncludes['mooRainbow'] = 'mooRainbow.css';

$domReady['gallery'] = "

var myGallery = new gallery($('myGallery'), {});

";
$domReady['tips'] = "

var Tipies = new Tips($$('.hintanchor'), {
        initialize:function(){
            this.fx = new Fx.Style(this.toolTip, 'opacity', {duration: 500, wait: false}).set(0);
        },
        onShow: function(toolTip) {
            this.fx.start(0.98);
        },
        onHide: function(toolTip) {
            this.fx.start(0);
        },
        fixed: true,
        offsets: {'x':32,'y':32}
    });
    
    ";
$domReady['mootabs'] = "
  new SimpleTabs($('navcontainer'), {
          entrySelector: 'h4'
  });
";

$tinyMCETemplate = '';
$scriptIncludesTemplate = array();
$cssIncludesTemplate = array();
$domReadyTemplate = "{literal}" . $onDomReady;

$scriptIncludesTemplate[] = $scriptIncludes['mootools'];

foreach($scriptList as $scriptName => $enabled)
{
    if ($scriptName == "tinyAdv" && $enabled)
    {
        $tinyMCETemplate = $tinyMCE['advanced'];
        $tinyMCEGzipTemplate = $tinyMCEGzip['advanced'];
        $scriptName = "tinymce";
    }
    elseif ($scriptName == "tinySimp" && $enabled && $tinyMCETemplate == '')
    {
        $tinyMCETemplate = $tinyMCE['simple'];
        $tinyMCEGzipTemplate = $tinyMCEGzip['simple'];
        $scriptName = "tinymce";
    }
    
    if (isset($scriptIncludes[$scriptName]) && $enabled)
    {
        $scriptIncludesTemplate[] = $scriptIncludes[$scriptName];
    }
    
    if (isset($cssIncludes[$scriptName]) && $enabled)
    {
        $cssIncludesTemplate[] = $cssIncludes[$scriptName];
    }
    
    if (isset($domReady[$scriptName]) && $enabled)
    {
        $domReadyTemplate .= $domReady[$scriptName];
    }
}

$domReadyTemplate .= $domReady['tips'];

$tpl->assign("scriptInclude", $scriptIncludesTemplate);
$tpl->assign("cssInclude", $cssIncludesTemplate);
$tpl->assign("onDomReady", $domReadyTemplate . "{/literal}");
$tpl->assign("tinyMCE", $tinyMCETemplate);
$tpl->assign("tinyMCEGzip", $tinyMCEGzipTemplate);
$tpl->assign("scriptIncludeNum", count($scriptIncludesTemplate));
$tpl->assign("cssIncludeNum", count($cssIncludesTemplate));
?>
