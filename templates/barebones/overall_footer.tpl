<!--Shows the admin panel link-->
{if ($adminpanel == "Y") && ($uname != "Guest")}
    <a href="admin.php">Goto Administration Panel</a><br />
{/if}

<!--Shows the CMScout and website copyright information. Please do not remove this without permission from the CMScout admin-->
{eval var=$copyright}

<!--Shows the RSS link, you may remove this-->
{if $rssid != ""}
    <a href="index.php?page=rss&amp;action=feed&amp;uid={$rssid}"><img border="0" src="{$templateinfo.imagedir}rss.png" alt="Site Syndication (RSS Feed)" title="Site Syndication (RSS Feed)" /></a>		
{/if}

<!--Shows debug information if it is activated-->
{$debug}

</body>

<!--Used by the script validation system-->
{if $repost}
    <script type="text/javascript">
        {section name=posts loop=$repost}
            document.getElementById('{$repost[posts].id}').value = '{$repost[posts].value}';
        {/section}
    </script>
{/if}

</html>

