{if $extra != "nomenu"}	<div class="outside-box" style="border-top: none;">
{if $extra != "nomenu"}
 {if ($adminpanel == "Y") && ($uname != "Guest")}<a href="admin.php">Goto Administration Panel</a><br />{/if}
{/if}
		 {eval var=$copyright}
	</div>
    {if $rssid != ""}
    <div style="text-align:right;">
		<a href="index.php?page=rss&amp;action=feed&amp;uid={$rssid}"><img border="0" src="{$templateinfo.imagedir}rss.png" alt="Site Syndication (RSS Feed)" title="Site Syndication (RSS Feed)" /></a>		
    </div>
    {/if}{/if}</center><div align="center">{$debug}</div></body>
    {if $repost}
<script type="text/javascript">
{section name=posts loop=$repost}
    document.getElementById('{$repost[posts].id}').value = '{$repost[posts].value}';
{/section}
</script>
{/if}
</html>

