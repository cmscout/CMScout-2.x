{literal}
<script type="text/javascript">
<!--
function confirmDelete(type, id) {
if (confirm("This will permentaly delete this item. Continue?")){/literal}
document.location = "{$pagename}&action=delete&type=" + type + "&id=" + id;{literal}
}
//-->
</script>
{/literal}
<h2>Trash</h2>
<div align="center"><div style="width:85%">
<div id="navcontainer" align="center">
<ul class="mootabs_title">
    

</ul>

<h4 title="album">Photo Albums</h4>
<div id="album">
{if $numalbums}
<table width="98%" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-15" id="sortTable">
<thead>
  <tr>
    <th width="5%"class="smallhead"></th>
    <th class="smallhead">Title</th>
  </tr>
  </thead>
  <tbody>
  {section name=items loop=$numalbums}
  <tr class="text">
	<td class="text" style="text-align:center;"><a href="admin.php?page=trash&amp;action=recover&amp;type=album&amp;id={$album[items].ID}"><img src="{$tempdir}admin/images/restore.png" border="0" alt="Recover" title="Recover" /></a>&nbsp;&nbsp;<a href="javascript:confirmDelete('album', {$album[items].ID})"><img src="{$tempdir}admin/images/delete.gif" border="0" alt="Permentaly Delete" title="Permentaly Delete" /></a></td>
	<td class="text"><div align="left">{$album[items].album_name}</div></td>
  </tr>
  {/section}  
  </tbody>
</table>
{else}
<div align="center">No photo albums in the trash</div>
{/if}
</div>

<h4 title="article">Articles</h4>
<div id="article">
{if $numarticles}
<table width="98%" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-15" id="sortTable1">
<thead>
  <tr>
    <th width="5%"class="smallhead"></th>
    <th class="smallhead">Title</th>
  </tr>
  </thead>
  <tbody>
  {section name=items loop=$numarticles}
  <tr class="text">
	<td class="text" style="text-align:center;"><a href="admin.php?page=trash&amp;action=recover&amp;type=article&amp;id={$article[items].ID}"><img src="{$tempdir}admin/images/restore.png" border="0" alt="Recover" title="Recover" /></a>&nbsp;&nbsp;<a href="javascript:confirmDelete('article', {$article[items].ID})"><img src="{$tempdir}admin/images/delete.gif" border="0" alt="Permentaly Delete" title="Permentaly Delete" /></a></td>
	<td class="text"><div align="left">{$article[items].summary}</div></td>
  </tr>
  {/section}  
  </tbody>
</table>
{else}
<div align="center">No articles in the trash</div>
{/if}
</div>

<h4 title="event">Events</h4>
<div id="event">
{if $numevents}
<table width="98%" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-15" id="sortTable2">
<thead>
  <tr>
    <th width="5%"class="smallhead"></th>
    <th class="smallhead">Title</th>
  </tr>
  </thead>
  <tbody>
  {section name=items loop=$numevents}
  <tr class="text">
	<td class="text" style="text-align:center;"><a href="admin.php?page=trash&amp;action=recover&amp;type=event&amp;id={$event[items].id}"><img src="{$tempdir}admin/images/restore.png" border="0" alt="Recover" title="Recover" /></a>&nbsp;&nbsp;<a href="javascript:confirmDelete('event', {$event[items].id})"><img src="{$tempdir}admin/images/delete.gif" border="0" alt="Permentaly Delete" title="Permentaly Delete" /></a></td>
	<td class="text"><div align="left">{$event[items].summary}</div></td>
  </tr>
  {/section}  
  </tbody>
</table>
{else}
<div align="center">No events in the trash</div>
{/if}
</div>

<h4 title="download">Downloads</h4>
<div id="download">
{if $numdownloads}
<table width="98%" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-15" id="sortTable3">
<thead>
  <tr>
    <th width="5%"class="smallhead"></th>
    <th class="smallhead">Title</th>
  </tr>
  </thead>
  <tbody>
  {section name=items loop=$numdownloads}
  <tr class="text">
	<td class="text" style="text-align:center;"><a href="admin.php?page=trash&amp;action=recover&amp;type=download&amp;id={$download[items].id}"><img src="{$tempdir}admin/images/restore.png" border="0" alt="Recover" title="Recover" /></a>&nbsp;&nbsp;<a href="javascript:confirmDelete('download', {$download[items].id})"><img src="{$tempdir}admin/images/delete.gif" border="0" alt="Permentaly Delete" title="Permentaly Delete" /></a></td>
	<td class="text"><div align="left">{$download[items].name}</div></td>
  </tr>
  {/section}  
  </tbody>
</table>
{else}
<div align="center">No downloads in the trash</div>
{/if}
</div>

<h4 title="news">News Items</h4>
<div id="news">
{if $numnews}
<table width="98%" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-15" id="sortTable4">
<thead>
  <tr>
    <th width="5%"class="smallhead"></th>
    <th class="smallhead">Title</th>
  </tr>
   </thead>
  <tbody>
 {section name=items loop=$numnews}
  <tr class="text">
	<td class="text" style="text-align:center;"><a href="admin.php?page=trash&amp;action=recover&amp;type=news&amp;id={$news[items].id}"><img src="{$tempdir}admin/images/restore.png" border="0" alt="Recover" title="Recover" /></a>&nbsp;&nbsp;<a href="javascript:confirmDelete('news', {$news[items].id})"><img src="{$tempdir}admin/images/delete.gif" border="0" alt="Permentaly Delete" title="Permentaly Delete" /></a></td>
	<td class="text"><div align="left">{$news[items].title}</div></td>
  </tr>
  {/section}  
  </tbody>
</table>
{else}
<div align="center">No news items in the trash</div>
{/if}
</div>

<h4 title="poll">Polls</h4>
<div id="poll">
{if $numpolls}
<table width="98%" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-15" id="sortTable5">
<thead>
  <tr>
    <th width="5%"class="smallhead"></th>
    <th class="smallhead">Title</th>
  </tr>
   </thead>
  <tbody>
 {section name=items loop=$numpolls}
  <tr class="text">
	<td class="text" style="text-align:center;"><a href="admin.php?page=trash&amp;action=recover&amp;type=poll&amp;id={$poll[items].id}"><img src="{$tempdir}admin/images/restore.png" border="0" alt="Recover" title="Recover" /></a>&nbsp;&nbsp;<a href="javascript:confirmDelete('poll', {$poll[items].id})"><img src="{$tempdir}admin/images/delete.gif" border="0" alt="Permentaly Delete" title="Permentaly Delete" /></a></td>
	<td class="text"><div align="left">{$poll[items].question}</div></td>
  </tr>
  {/section}  
  </tbody>
</table>
{else}
<div align="center">No polls in the trash</div>
{/if}
</div>

<h4 title="content">Content</h4>
<div id="content">
{if $numcontents}
<table width="98%" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-15" id="sortTable6">
<thead>
  <tr>
    <th width="5%"class="smallhead"></th>
    <th class="smallhead">Title</th>
  </tr>
   </thead>
  <tbody>
 {section name=items loop=$numcontents}
  <tr class="text">
	<td class="text" style="text-align:center;"><a href="admin.php?page=trash&amp;action=recover&amp;type=content&amp;id={$content[items].id}"><img src="{$tempdir}admin/images/restore.png" border="0" alt="Recover" title="Recover" /></a>&nbsp;&nbsp;<a href="javascript:confirmDelete('content', {$content[items].id})"><img src="{$tempdir}admin/images/delete.gif" border="0" alt="Permentaly Delete" title="Permentaly Delete" /></a></td>
	<td class="text"><div align="left">{if $content[items].friendly}{$content[items].friendly}{else}{$content[items].name}{/if}</div></td>
  </tr>
  {/section}  
  </tbody>
</table>
{else}
<div align="center">No content items in the trash</div>
{/if}
</div>

</div></div>
</div>
