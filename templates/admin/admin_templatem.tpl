{literal}
<script type="text/javascript">
<!--
function uninstall(articleId) {
if (confirm("This will uninstall the template from the database. Continue?")){/literal}
document.location = "{$pagename}&action=uninstall&id=" + articleId;{literal}
}

function installtheme(articleId)
{
    if (confirm("This will install the template into the database. Continue?")){/literal}
    document.location = "{$pagename}&action=install&id=" + articleId;{literal}
}
//-->
</script>{/literal}<h2>Template Manager</h2>
 <div align="center"><div style="width:100%">
 <div id="navcontainer" align="center">
<ul class="mootabs_title">
<li title="installed">Installed Templates</li>
<li title="install">Available Templates</li>
</ul>

<div id="installed" class="mootabs_panel">
{if $numinstalledthemes > 0}
<table width="98%" cellpadding="0" cellspacing="0" border="0" align="center" class="table sortable-onload-2 rowstyle-alt paginate-15" id="sortTable">
<thead>
  <tr valign="top">
   {if $deleteallowed}
    <th width="5%" class="smallhead"></th>
 {/if}
    <th class="smallhead">Theme Name</th>
  </tr>
  </thead>
  <tbody>
 {section name=themeloop loop=$numinstalledthemes}
	  <tr valign="middle" class="text"> 
  {if $deleteallowed}
 		<td class="text"><div align="center"><a href="#" onclick="uninstall('{$installedthemes[themeloop].id}')"><img src="{$tempdir}admin/images/delete.gif" alt="Uninstall" border="0" /></a></div></td>
 {/if}
		<td class="text"><a href="index.php?theme={$installedthemes[themeloop].id}" title="Preview template" target="_blank">{$installedthemes[themeloop].name}</a></td>
	  </tr>  
	{/section}
    </tbody>
</table>
{else}
<div align="center">No installed templates</div>
{/if}
</div>

<div id="install" class="mootabs_panel">
{if $numnotinstalled > 0}
<table width="98%" cellpadding="0" cellspacing="0" border="0" align="center" class="table sortable-onload-2 rowstyle-alt paginate-15" id="sortTable1">
<thead>
  <tr valign="top"> 
     {if $addallowed}
    <th width="5%" class="smallhead"></th>
    {/if}
    <th class="smallhead">Theme Name</th>
  </tr>
  </thead>
  <tbody>
 {section name=themeloop loop=$numnotinstalled}
	  <tr valign="middle" class="text"> 
           {if $addallowed}
		<td class="text"><div align="center"><a href="#" onclick="installtheme('{$notinstalled[themeloop].directory}');"><img src="{$tempdir}admin/images/add.png" alt="Uninstall" border="0" /></a></div></td>{/if}
		<td class="text">{$notinstalled[themeloop].theme_name}</td>
	  </tr>  
	{/section}
    </tbody>
</table>
{else}
<div align="center">No templates available to install</div>
{/if}
</div>
</div>
</div>
</div>