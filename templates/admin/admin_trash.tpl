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
    <li title="album">Photo Albums</li>
    <li title="article">Articles</li>
    <li title="event">Events</li>
    <li title="download">Downloads</li>
    <li title="news">News Items</li>
    <li title="poll">Polls</li>
    <li title="content">Content</li>
</ul>

<div id="album" class="mootabs_panel">
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

<div id="article" class="mootabs_panel">
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

<div id="event" class="mootabs_panel">
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

<div id="download" class="mootabs_panel">
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

<div id="news" class="mootabs_panel">
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

<div id="poll" class="mootabs_panel">
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

<div id="content" class="mootabs_panel">
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
