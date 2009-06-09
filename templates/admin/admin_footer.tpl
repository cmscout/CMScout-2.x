<br />
<div align="left" class="copyright">Powered by CMScout V{$config.version} &copy;2005,2006,2007 <a href="http://www.cmscout.za.net" title="CMScout Group" target="_blank">CMScout Group</a><br />{$debug}</div>
</body>
    {if $repost}
<script type="text/javascript">
{section name=posts loop=$repost}
    document.getElementById('{$repost[posts].id}').value = '{$repost[posts].value}';
{/section}
</script>
{/if}
</html>
