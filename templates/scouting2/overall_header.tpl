<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>..:.:.:: {$config.troopname} :: {$config.troop_description} :: {$location} ::.:.:..</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="{$templateinfo.cssfile}" rel="stylesheet" type="text/css" />
<link rel="icon" type="image/gif"  href="{$templateinfo.imagedir}favico.gif" />
{if $rssid != ""}<link rel="alternate" type="application/rss+xml" title="{$config.troopname} RSS Feed" href="index.php?page=rss&amp;action=feed&amp;uid={$rssid}" />{/if}
<!--[if lt IE 7.]>
<script defer type="text/javascript" src="scripts/pngfix.js"></script>
<![endif]-->
<script type="text/JavaScript" src="includes/functions.js"></script>
{section name=scripts loop=$scriptIncludeNum}
<script src="{$scriptInclude[scripts]}" type="text/javascript"></script>
{/section}

<script type="text/javascript">
{$tinyMCEGzip}
</script>

<script type="text/javascript">
{$tinyMCE}
</script>

{section name=css loop=$cssIncludeNum}
<link rel="stylesheet" href="{$templateinfo.directory}{$cssInclude[css]}" type="text/css" media="screen" />
{/section}
{literal}
<script type="text/javascript">
function initilize() 
{
    {/literal}{eval var=$onDomReady}{literal}
}
window.addEvent('domready', initilize); 
</script>
<script type="text/javascript">
{/literal}{eval var=$script}{literal}
</script>
{/literal}
</head>
<body>