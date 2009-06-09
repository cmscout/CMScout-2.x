<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>{$config.troopname} - {$config.troop_description} - {$location}</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="{$templateinfo.cssfile}" rel="stylesheet" type="text/css" />

{if $rssid != ""}
    <link rel="alternate" type="application/rss+xml" title="{$config.troopname} RSS Feed" href="index.php?page=rss&amp;action=feed&amp;uid={$rssid}" />
{/if}

<!--This is the IE hack that fixes issues with PNG pictures-->
<!--[if lt IE 7.]>
<script defer type="text/javascript" src="scripts/pngfix.js"></script>
<![endif]-->

<!--Required javascript file-->
<script type="text/JavaScript" src="includes/functions.js"></script>

<!--This automatically includes any Javascript files that is required by the current page-->
{section name=scripts loop=$scriptIncludeNum}
    <script src="{$scriptInclude[scripts]}" type="text/javascript"></script>
{/section}

<!--This activates the TinyMCE editor-->
{if $tinyMCEGzip}
    <script type="text/javascript">
        {$tinyMCEGzip}
    </script>
{/if}

{if $tinyMCE}
    <script type="text/javascript">
        {$tinyMCE}
    </script>
{/if}

<!--Includes any CSS files that is required by scripts running on the current page-->
{section name=css loop=$cssIncludeNum}
    <link rel="stylesheet" href="{$templateinfo.directory}{$cssInclude[css]}" type="text/css" media="screen" />
{/section}

<!--Runs numerous scripts including the tips and the tabs-->
{if $onDomReady}
    {literal}
        <script type="text/javascript">
            function initilize() 
            {
                {/literal}{eval var=$onDomReady}{literal}
            }
            window.onDomReady(initilize); 
        </script>
    {/literal}
{/if}

<!--This is for any extra scripts that the page might need-->
{if $script}
    <script type="text/javascript">
        {eval var=$script}
    </script>
{/if}
</head>
<body>