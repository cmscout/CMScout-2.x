{if $action == ""}
{if $uname == ""}
<h2>Orphaned Items</h2>
{else}
<h2>Items for {$uname}</h2>
{/if}
<div align="center"><div style="width:100%">
<div id="navcontainer" align="center">
    <ul class="mootabs_title">
        <li title="albums">Photo Albums</li>
        <li title="articles">Articles</li>
        <li title="events">Events</li>
        <li title="downloads">Downloads</li>
        <li title="news">News Items</li>
        <li title="polls">Polls</li>
        </ul>
        
        <div id="albums" class="mootabs_panel">
            <table width="98%" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-15" id="sortTable">
<thead>
                <tr><th class="smallhead" width="8%"></th><th class="smallhead">Name</th></tr></thead>
                <tbody>
                {section name=album loop=$numalbums}
                    <tr class="text"><td class="text"><div align="center">{if $permissions.album}<a href="admin.php?page=photo&amp;id={$albums[album].ID}&amp;action=view"><img src="{$tempdir}admin/images/edit.gif" border="0" alt="Edit {$albums[album].album_name}" title="Edit {$albums[album].album_name}" /></a>{else}<img src="{$tempdir}admin/images/edit_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;&nbsp;{if $editallowed}<a href="{$pagename}&amp;action=change&amp;itemType=album&amp;id={$albums[album].ID}{if $userid}&amp;uid={$userid}{/if}"><img src="{$tempdir}admin/images/group.gif" border="0" alt="Owners" /></a>{else}<img src="{$tempdir}admin/images/group_grey.gif" border="0" alt="Not allowed to change owners" title="Not allowed to change owners" />{/if}</div></td>
                    <td class="text">{$albums[album].album_name}</td></tr>
                {sectionelse}
                <tr><td class="text" colspan="2">{if !$uname}No orphaned photo albums{else}{$uname} does not own any photo albums{/if}</td></tr>
                {/section}
                </tbody>
            </table>
        </div>
        
        <div id="articles" class="mootabs_panel">
                <table width="98%" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-15" id="sortTable1">
<thead>
                <tr><th class="smallhead" width="8%"></th><th class="smallhead">Name</th></tr></thead>
                <tbody>
                {section name=album loop=$numarticles}
                    <tr class="text"><td class="text"><div align="center">{if $permissions.patrolart}<a href="admin.php?page=patrolart&amp;id={$articles[album].ID}&amp;action=edit"><img src="{$tempdir}admin/images/edit.gif" border="0" alt="Edit {$articles[album].title}" title="Edit {$articles[album].title}" /></a>{else}<img src="{$tempdir}admin/images/edit_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;&nbsp;{if $editallowed}<a href="{$pagename}&amp;action=change&amp;itemType=articles&amp;id={$articles[album].ID}{if $userid}&amp;uid={$userid}{/if}"><img src="{$tempdir}admin/images/group.gif" border="0" alt="Owners" /></a>{else}<img src="{$tempdir}admin/images/group_grey.gif" border="0" alt="Not allowed to change owners" title="Not allowed to change owners" />{/if}</div></td><td class="text">{$articles[album].title}</td></tr>
                {sectionelse}
                <tr><td class="text" colspan="2">{if !$uname}No orphaned articles{else}{$uname} does not own any articles{/if}</td></tr>
                {/section}</tbody>
            </table>        
        </div>   
        
        <div id="events" class="mootabs_panel">
                <table width="98%" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-15" id="sortTable2">
<thead>
                <tr><th class="smallhead" width="8%"></th><th class="smallhead">Name</th></tr></thead>
                <tbody>
                {section name=album loop=$numevents}
                    <tr class="text"><td class="text"><div align="center">{if $permissions.events}<a href="admin.php?page=events&amp;id={$events[album].id}&amp;action=edit"><img src="{$tempdir}admin/images/edit.gif" border="0" alt="Edit {$events[album].summary}" title="Edit {$events[album].summary}" /></a>{else}<img src="{$tempdir}admin/images/edit_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;&nbsp;{if $editallowed}<a href="{$pagename}&amp;action=change&amp;itemType=events&amp;id={$events[album].id}{if $userid}&amp;uid={$userid}{/if}"><img src="{$tempdir}admin/images/group.gif" border="0" alt="Owners" /></a>{else}<img src="{$tempdir}admin/images/group_grey.gif" border="0" alt="Not allowed to change owners" title="Not allowed to change owners" />{/if}</div></td><td class="text">{$events[album].summary}</td></tr>
                {sectionelse}
                <tr><td class="text" colspan="2">{if !$uname}No orphaned events{else}{$uname} does not own any events{/if}</td></tr>                    
                {/section}</tbody>
            </table>        
        </div>
        
        <div id="downloads" class="mootabs_panel">
                <table width="98%" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-15" id="sortTable3">
<thead>
                <tr><th class="smallhead" width="8%"></th><th class="smallhead">Name</th></tr></thead>
                <tbody>
                {section name=album loop=$numdownloads}
                    <tr class="text"><td class="text"><div align="center">{if $permissions.downloads}<a href="admin.php?page=downloads&amp;id={$downloads[album].id}&amp;did={$downloads[album].cat}&amp;action=editdown"><img src="{$tempdir}admin/images/edit.gif" border="0" alt="Edit {$downloads[album].name}" title="Edit {$downloads[album].name}" /></a>{else}<img src="{$tempdir}admin/images/edit_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;&nbsp;{if $editallowed}<a href="{$pagename}&amp;action=change&amp;itemType=downloads&amp;id={$downloads[album].id}{if $userid}&amp;uid={$userid}{/if}"><img src="{$tempdir}admin/images/group.gif" border="0" alt="Owners" /></a>{else}<img src="{$tempdir}admin/images/group_grey.gif" border="0" alt="Not allowed to change owners" title="Not allowed to change owners" />{/if}</div></td><td class="text">{$downloads[album].name}</td></tr>
                {sectionelse}
                <tr><td class="text" colspan="2">{if !$uname}No orphaned downloads{else}{$uname} does not own any downloads{/if}</td></tr>
                {/section}</tbody>
            </table>        
        </div>
        
        <div id="news" class="mootabs_panel">
                <table width="98%" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-15" id="sortTable4">
<thead>
                <tr><th class="smallhead" width="8%"></th><th class="smallhead">Name</th></tr></thead>
                <tbody>
                {section name=album loop=$numnews}
                    <tr class="text"><td class="text"><div align="center">{if $permissions.news}<a href="admin.php?page=news&amp;id={$news[album].id}&amp;action=edit"><img src="{$tempdir}admin/images/edit.gif" border="0" alt="Edit {$news[album].title}" title="Edit {$news[album].title}" /></a>{else}<img src="{$tempdir}admin/images/edit_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;&nbsp;{if $editallowed}<a href="{$pagename}&amp;action=change&amp;itemType=newsitem&amp;id={$news[album].id}{if $userid}&amp;uid={$userid}{/if}"><img src="{$tempdir}admin/images/group.gif" border="0" alt="Owners" /></a>{else}<img src="{$tempdir}admin/images/group_grey.gif" border="0" alt="Not allowed to change owners" title="Not allowed to change owners" />{/if}</div></td><td class="text">{$news[album].title}</td></tr>
                {sectionelse}
                <tr><td class="text" colspan="2">{if !$uname}No orphaned news items{else}{$uname} does not own any news items{/if}</td></tr>
                {/section}</tbody>
            </table>        
        </div>
        
        <div id="polls" class="mootabs_panel">
                <table width="98%" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-15" id="sortTable5">
<thead>
                <tr class="text"><th class="smallhead" width="8%"></th><th class="smallhead">Name</th></tr></thead>
                <tbody>
                {section name=album loop=$numpolls}
                    <tr><td class="text"><div align="center">{if $permissions.polls}<a href="admin.php?page=polls&amp;id={$polls[album].id}&amp;action=edit"><img src="{$tempdir}admin/images/edit.gif" border="0" alt="Edit {$polls[album].question}" title="Edit {$polls[album].question}" /></a>{else}<img src="{$tempdir}admin/images/edit_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;&nbsp;{if $editallowed}<a href="{$pagename}&amp;action=change&amp;itemType=pollitems&amp;id={$polls[album].id}{if $userid}&amp;uid={$userid}{/if}"><img src="{$tempdir}admin/images/group.gif" border="0" alt="Owners" /></a>{else}<img src="{$tempdir}admin/images/group_grey.gif" border="0" alt="Not allowed to change owners" title="Not allowed to change owners" />{/if}</div></td><td class="text">{$polls[album].question}</td></tr>
                {sectionelse}
                <tr><td class="text" colspan="2">{if !$uname}No orphaned polls{else}{$uname} does not own any polls{/if}</td></tr>
                {/section}</tbody>
            </table>        
        </div>
</div>
</div>
</div>
{elseif $action == "change"}
{literal}
<script type="text/javascript">
<!--
function confirmDelete(articleId) {
if (confirm("This will remove this owner. Continue?"))
{/literal}
document.location = "admin.php?page=owners&action=deleteowner&id=" + articleId + "&itemType={$itemType}&itemid={$itemid}{if $userid}&uid={$userid}{/if}";
}
//-->
</script>
<h2>Owners</h2>
<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="table sortable-onload-3 rowstyle-alt paginate-15" id="sortTable">
<thead>
<tr> 
   <th width="5%" class="smallhead"></th>
   <th width="15%" class="smallhead sortable-date">Expires</th>
  <th class="smallhead sortable" colspan="2">Owners</th>
</tr>
</thead><tbody>
{section name=owners loop=$numitemowners}
<tr class="text">
<td class="text"><div align="center">{if $numitemowners > 1}<a href="javascript:confirmDelete('{$itemowners[owners].id}')" title="Remove {$itemowners[owners].name}"><img src="{$tempdir}admin/images/delete.gif"  border="0" alt="Remove {$itemowners[owners].name}" /></a>{else}<img src="{$tempdir}admin/images/delete_grey.gif"  border="0" alt="Can't remove only owner" />{/if}</div></td>
<td class="text" {if $itemowners[owners].type == 0 && $itemowners[owners].expired == 0}style="background-color:#eeffff;"{elseif $itemowners[owners].expired == 1}style="background-color:#ff0000;color:#ffffff;"{/if}>{if $itemowners[owners].expire == 0}Forever{else}{$itemowners[owners].expire+$timeoffset|date_format:"%Y-%m-%d"}{/if}</td>
<td class="text" {if $itemowners[owners].type == 0 && $itemowners[owners].expired == 0}style="background-color:#eeffff;"{elseif $itemowners[owners].expired == 1}style="background-color:#ff0000;color:#ffffff;"{/if} colspan="2">{$itemowners[owners].name}</td>
</tr>
{/section}</tbody>
</table>
<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="table">
<tr>
<th class="smallhead" colspan="4">Colour Key</td>
</tr>
<tr class="text">
<td class="text" colspan="2">Edit Only</td>
<td class="text" width="43%" style="background-color:#eeffff;">Complete Ownership</td>
<td class="text" width="43%" style="background-color:#ff0000;color:#ffffff;">Expired</td>
</tr>
<tr>
<th class="smallhead" colspan="4">Add user/group</td>
</tr>
<tr class="text">
<td class="text" colspan="4">
<form method="post" action="">
<select name="owner" id="owner" class="inputbox">
<option value="0">Select user/group to add</option>
    <optgroup title="Users" label="Users">
    {section name="loop" loop=$numpeople}
        <option value="user_{$people[loop].id}">{$people[loop].uname}</option>
    {/section}
    </optgroup>
    <optgroup title="Groups" label="Groups">
    {section name="loop" loop=$numteams}
        <option value="group_{$groups[loop].id}">{$groups[loop].teamname}</option>
    {/section}
    </optgroup>
    
</select> <br />
Type of ownership: <select name="type_owner" id="type_owner" class="inputbox">
<option value="0">Complete (Allowed to edit, delete, modify owners)</option>
<option value="1">Edit only</option>
</select><br />
Length of time for ownership: <select name="expire" id="expire" class="inputbox">
<option value="0">Forever</option>
<option value="24">24 Hours</option>
<option value="48">48 Hours</option>
<option value="168">7 Days</option>
<option value="672">4 Weeks</option>
</select><br />
<input type="submit" class="button" value="Add" name="action" id="action" />&nbsp;<input type="button" class="button" value="Back" onclick="window.location='{$pagename}{if $userid}&amp;uid={$userid}{/if}'"/>
</form></td>
</tr>
</table>
{/if}