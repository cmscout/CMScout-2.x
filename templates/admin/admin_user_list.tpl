<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="table sortable-onload-1 rowstyle-alt paginate-15" id="sortTable">
<thead>
<tr> 
  <th class="smallhead sortable">Name</th>
  <th class="smallhead sortable">Email</th>
  <th width="200" class="smallhead sortable">Groups</th>
  <th width="100" class="smallhead sortable-date">Last Login</th>
  <th width="90" class="smallhead sortable-numeric">Login Count</th>
    {section name=fields loop=$fields}
        <th class="smallhead sortable">{$fields[fields].query}</th>
    {/section}   
</tr>
  </thead>
  <tbody>
{section name=users loop=$numusers}
<tr class="text" style="vertical-align:middle">
  <td class="text">{$row[users].uname} ({$row[users].firstname} {$row[users].lastname})</td>
  <td class="text"><a href="mailto:{$row[users].email}">{$row[users].email}</a></text>
  <td class="text">{$row[users].team}</td>
  <td class="text">{if $row[users].lastlogin > 0}{$row[users].lastlogin+$timeoffset|date_format:"%Y-%m-%d"}{else}Never logged in{/if}</td>
  <td style="text-align:center" class="text">{$row[users].logincount}</td>
    {section name=fields loop=$fields}
    <td class="text">
        {assign var="name" value=$fields[fields].name}
        <div class="inputboxwrapper">
        {if $fields[fields].type == 1}
            {$uinfo.custom.$name}
        {elseif $fields[fields].type == 2}
            {$uinfo.custom.$name}
        {elseif $fields[fields].type == 3}
            {section name=options loop=$fields[fields].options[0]+1 start=1}
                {if $uinfo.custom.$name == $smarty.section.options.iteration}{$fields[fields].options[options]}{/if}
            {/section}
        {elseif $fields[fields].type == 4}
            {assign var="temp" value=$uinfo.custom.$name}
            {if $temp}
            {section name=options loop=$fields[fields].options[0]+1 start=1}
                {if $temp[options] == 1}{$fields[fields].options[options]}{/if}{if $smarty.section.options.iteration < ($fields[fields].options[0]+1)}, {/if}
            {/section}
            {/if}
        {elseif $fields[fields].type == 5}
            {section name=options loop=$fields[fields].options[0]+1 start=1}
                {if $uinfo.custom.$name == $smarty.section.options.iteration}{$fields[fields].options[options]}{/if}
            {/section}
        {elseif $fields[fields].type == 6}
            {$uinfo.custom.$name}           
        {/if}   
       </td>
    {/section}   
</tr>
{/section}
</tbody>
</table>