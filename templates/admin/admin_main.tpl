<table cellspacing="1" cellpadding="4" border="0">
<tr valign="top">
<td width="100%">
    <div style="float:left;">
       {if $mainpageauth.config}<a class="hintanchor" href="admin.php?page=config" title="Website Configuration :: Configuration for the website."><img class="main-menu" src="{$tempdir}admin/images/mainconfig.png" alt="Website Configuration" border="0"/></a>{/if}
    </div>
    <div style="float:left;">
        <a class="hintanchor" href="admin.php?page=contentManager" title="Content Management :: Allows easy management of all website content."><img class="main-menu" src="{$tempdir}admin/images/content.png" alt="Content Management" border="0" /></a>
    </div>
    <div style="float:left;">
        {if $mainpageauth.menus}<a class="hintanchor" href="admin.php?page=menus" title="Menu Management :: Allows easy management of the sites menu system."><img class="main-menu" src="{$tempdir}admin/images/mainmenu.png" alt="Menu Management" border="0"/></a>{/if}
    </div>
    <div style="float:left;">
        {if $mainpageauth.users}<a class="hintanchor" href="admin.php?page=users" title="User Management :: Allows management of all users who are registered on the site."><img class="main-menu" src="{$tempdir}admin/images/mainuser.png" alt="User Management" border="0"/></a>{/if}
    </div>
    <div style="float:left;">
        {if $mainpageauth.group}<a class="hintanchor" href="admin.php?page=group" title="Group Management :: Allows management of website groups."><img class="main-menu" src="{$tempdir}admin/images/maingroups.png" alt="Group Management" border="0"/></a>{/if}
    </div>
    <div style="float:left;">
        {if $mainpageauth.patrol}<a class="hintanchor" href="admin.php?page=patrol" title="Group Site Management :: Allows easy management of group sites."><img class="main-menu" src="{$tempdir}admin/images/maingroupsite.png" alt="Group Site Management" border="0"/></a>{/if}
    </div>
    <div style="float:left;">
        {if $mainpageauth.subsite}<a class="hintanchor" href="admin.php?page=subsite" title="Sub Site Management :: Allows management of sub sites."><img class="main-menu" src="{$tempdir}admin/images/mainsubsites.png" alt="Sub Site Management" border="0"/></a>{/if}
    </div>
    <div style="float:left;">
        {if $mainpageauth.logfile}<a class="hintanchor" href="admin.php?page=logfile" title="Error Log :: Shows a list of all major errors that have happened to CMScout."><img class="main-menu" src="{$tempdir}admin/images/error.png" alt="Error Log" border="0"/></a>{/if}
    </div>
</td>
<td style="width:400px;">
    <p class="accTitle2">Online Users</p>
    <div class="accContent2"><table width="390" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-10" id="sortTable3">
    <thead>
      <tr>
        <th scope="col" class="smallhead sortable">Username</th>
        <th scope="col" class="smallhead sortable">Logged on</th>
        <th scope="col" class="smallhead sortable">Last update</th>
        <th scope="col" class="smallhead sortable">Current Page</th>
      </tr>
      </thead><tbody>
      {section name=users loop=$numusers}
      <tr class="text">
        <td class="text">{if $onlineusers[users].isguest == 0}<a href="admin.php?page=users&amp;subpage=users_view&amp;id={$onlineusers[users].id}">{$onlineusers[users].uname}</a>{else}{$onlineusers[users].uname}{/if}&nbsp;<a href="admin.php?page=users&amp;action=logout&amp;id={$onlineusers[users].id}">{if $onlineusers[users].isactive == 1}<img src="{$tempdir}admin/images/active.png" border="0" alt="Active" title="Active" />{else}<img src="{$tempdir}admin/images/inactive.png" border="0" alt="Inactive" title="Inactive" />{/if}</a></td>
        <td class="text">{$onlineusers[users].logon+$timeoffset|date_format:"%Y-%m-%d %H:%M"}</td>
        <td class="text">{$onlineusers[users].lastupdate+$timeoffset|date_format:"%Y-%m-%d %H:%M"}</td>
        <td class="text">{$onlineusers[users].location}</td>
      </tr>
      {/section}</tbody>
    </table></div>
    <p class="accTitle2">Statistics</p>
    <div class="accContent2"><div class="field">
    <div class="fieldItem"><span class="label">Registered Users</span>
    <div class="inputboxwrapper" style="width:10px;">{$stats.numusers}</div></div><br />
    <div class="fieldItem"><span class="label">Groups</span>
    <div class="inputboxwrapper" style="width:10px;">{$numgroups}</div></div><br />
    <div class="fieldItem"><span class="label">Content Pages</span>
    <div class="inputboxwrapper" style="width:10px;">{$stats.pages}</div></div><br />
    <div class="fieldItem"><span class="label">Photo Albums</span>
    <div class="inputboxwrapper" style="width:10px;">{$stats.albums}</div></div><br />
    <div class="fieldItem"><span class="label">Photos</span>
    <div class="inputboxwrapper" style="width:10px;">{$stats.photos}</div></div><br />
    <div class="fieldItem"><span class="label">Articles</span>
    <div class="inputboxwrapper" style="width:10px;">{$stats.articles}</div></div><br />
    <div class="fieldItem"><span class="label">Forum Topics</span>
    <div class="inputboxwrapper" style="width:10px;">{$stats.topics}</div></div><br />
    <div class="fieldItem"><span class="label">Forum Posts</span>
    <div class="inputboxwrapper" style="width:10px;">{$stats.posts}</div></div><br />
    </div></div>
    <p class="accTitle2">Groups</p>
    <div class="accContent2"><table width="390" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-15" id="sortTable2">
    <thead>
      <tr>
        <th scope="col" class="smallhead sortable">Name</th>
        <th scope="col" class="smallhead sortable-numeric">Number of Users</th>
      </tr></thead>
      <tbody>
      {section name=group loop=$numgroups}
      <tr class="text">
        <td class="text"><a href="admin.php?page=users&amp;groupid={$groups[group].id}">{$groups[group].name}</a></td>
        <td class="text" style="text-align:center;">{$groups[group].numusers}</td>
      </tr>
      {/section}</tbody>
    </table></div>
    <p class="accTitle2">Members</p>
    <div class="accContent2">{if $nummembers > 0}
    <table width="390" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-8" id="sortTable">
    <thead>
    <tr> 
      <th class="smallhead sortable">Name</th>
      <th width="20%" class="smallhead sortable">Relations</th>
      <th width="10%" class="smallhead sortable">Home</th>
      <th width="10%" class="smallhead sortable">Cellphone</th>
    </tr></thead>
      <tbody>
    {section name=users loop=$nummembers}
    <tr class="text">
      <td class="text"><a href="admin.php?page=troop&amp;action=view&amp;id={$members[users].id}">{$members[users].lastName}, {$members[users].firstName}</a></td>
      <td class="text">{$members[users].relations}</td>
      <td class="text">{$members[users].home}</td>
      <td class="text">{$members[users].cell}</td>
    </tr>
    {/section}
    </tbody>
    </table>
    {else}
    <div align="center">No members</div>
    {/if}</div>
</td>
</tr>
<tr>
<td colspan="2">
{if $newversion != false}
  {if $latest}
    <div align="left" style="color:#009900;">You are using the latest version of CMScout.</div>
  {elseif $beyond}
    <div align="left" style="color:#009900;">You are using a un-released/modified version of CMScout.</div>
  {else}
    <div align="left" style="color:#990000;">Your CMScout installation is out of date. The latest version of CMScout is {$newversion} and you have version {$config.version}, please goto <a href="http://www.cmscout.za.net">http://www.cmscout.za.net</a> and download the latest version.</div>
  {/if}
  <div align="left"><b>CMScout Message: </b>{$cmscoutmessage}</div>
{else}
  <div align="left" style="color:#990000;">Unable to communicate with CMScout server.</div>
{/if}
</td>
</tr>
</table>