<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>{$title}</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="{$tempdir}admin/admin.css" rel="stylesheet" type="text/css" />
<!--[if lt IE 7.]>
<script defer type="text/javascript" src="scripts/pngfix.js"></script>
<![endif]-->
{if $editor == true}
{literal}
<script language="javascript" type="text/javascript" src="tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
tinyMCE.init
    ({
{/literal}{if $editormode != true}
        mode : "exact",
        elements: "editor",{else}mode : "textareas",{/if}{literal}
        theme : "advanced",
        languages : 'en',
        plugins : "advimage,advlink,table,advhr,emotions,insertdatetime,searchreplace,print,contextmenu,paste,fullscreen,inlinepopups,layer,spellchecker,media,ibrowser,style",
        theme_advanced_buttons1_add : "fontselect,fontsizeselect,styleprops,separator,spellchecker",
        theme_advanced_buttons2_add : "separator,insertdate,inserttime,media,separator,forecolor,backcolor",
        theme_advanced_buttons2_add_before: "cut,copy,paste,pastetext,pasteword,separator,search,replace,separator",
        theme_advanced_buttons3_add_before : "tablecontrols,separator",
        theme_advanced_buttons3_add : "emotions,advhr,separator,print,separator,fullscreen,separator,insertlayer,ibrowser",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true,
        theme_advanced_resize_horizontal : false,
        theme_advanced_path_location : "bottom",
        plugin_insertdate_dateFormat : "%Y-%m-%d",
        plugin_insertdate_timeFormat : "%H:%M:%S",
        content_css : "default.css",
        spellchecker_languages : "+English=en",
        file_browser_callback : "fileBrowser",
        button_tile_map : true,
        extended_valid_elements : "a[name|href|target|title|style],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|name|style],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
       });
</script>
{/literal}
{/if}
<script language="JavaScript" type="text/JavaScript" src="includes/functions.js"> </script>
<script type="text/javascript" src="scripts/mootools.js"></script>
<script type="text/javascript" src="scripts/SimpleTabs.js"></script>
<script src="scripts/datepicker.js" type="text/javascript"></script>
<link rel="stylesheet" href="{$tempdir}admin/datepicker.css" type="text/css" media="screen" />
<script type="text/javascript" src="scripts/mooRainbow.js"></script>
<link rel="stylesheet" href="{$tempdir}admin/mooRainbow.css" type="text/css" media="screen" />
<script type="text/javascript" src="scripts/slimbox.js"></script>
<link rel="stylesheet" href="{$tempdir}admin/slimbox.css" type="text/css" media="screen" />
<script type="text/javascript" src="scripts/tablesort.js"></script>
{literal}
<script type="text/javascript">
function initilize(){
    new SimpleTabs($('navcontainer'), {
    		  show: {/literal}{if $activetab == 'right'}1{elseif $activetab == 'top'}2{else}0{/if}{literal},
              entrySelector: 'h4'
      });

    {/literal}
     {$onDomReady}
     {literal}
    var Tipies = new Tips($$('.hintanchor'), {
        initialize:function(){
            this.fx = new Fx.Style(this.toolTip, 'opacity', {duration: 500, wait: false}).set(0);
        },
        onShow: function(toolTip) {
            this.fx.start(0.8);
        },
        onHide: function(toolTip) {
            this.fx.start(0);
        },
        fixed: true{/literal}{if $file == "admin_main.tpl" || $file == "admin_contentManager.tpl"}{literal},
        offsets: {'x':0,'y':78}{/literal}{/if}{literal}
    });

}

window.onDomReady(initilize); 
</script>
<script type="text/javascript">
var accordion;
var accordionTogglers;
var accordionContents;

window.onload = function() {
  accordionTogglers = document.getElements('.accToggler');
  
  accordionTogglers.each(function(toggler){
    //remember the original color
    toggler.origColor = toggler.getStyle('background-color');
    //set the effect
    toggler.fx = new Fx.Style(toggler, 'background-color');
  });
  
  accordionContents = document.getElements('.accContent');
  
  accordion = new Fx.Accordion(accordionTogglers, accordionContents,{
    //when an element is opened change the background color to blue
    onActive: function(toggler){
      toggler.fx.start('#6899CE');
    },
    onBackground: function(toggler){
      //change the background color to the original (green)
      //color when another toggler is pressed
      toggler.setStyle('background-color', toggler.origColor);
    },
    show: {/literal}{$menuOpen}{literal}
  });
  
  accordionTogglers2 = document.getElements('.accTitle2');
  
  accordionTogglers2.each(function(toggler2){
    //remember the original color
    toggler2.origColor = toggler2.getStyle('background-color');
    //set the effect
    toggler2.fx = new Fx.Style(toggler2, 'background-color');
  });
  
  accordionContents2 = document.getElements('.accContent2');
  
  accordion2 = new Fx.Accordion(accordionTogglers2, accordionContents2,{
    //when an element is opened change the background color to blue
    onActive: function(toggler2){
      toggler2.fx.start('#6899CE');
    },
    onBackground: function(toggler2){
      //change the background color to the original (green)
      //color when another toggler is pressed
      toggler2.setStyle('background-color', toggler2.origColor);
    },
    show:0
  });  
};
</script>
{/literal}
{if $script != ""}
<script type="text/javascript">
{eval var=$script}
</script>
{/if}
<script type="text/javascript">
{include file="../scripts/validator.tpl"}
</script>
</head>
<body>

