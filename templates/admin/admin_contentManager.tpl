<h2>Content Items</h2>
<table cellspacing="1" cellpadding="4" border="0" width="100%">
<tr valign="top">
<td>
    <div style="float:left;">
        {if $mainpageaddauth.content && $mainpageauth.content}<a class="hintanchor" href="admin.php?page=content&action=new" title="Add Content Page :: Add a content page."><img class="main-menu" src="{$tempdir}admin/images/mainaddcontent.png" alt="Add Content Page" border="0"/></a>{/if}
    </div>
    <div style="float:left;">
        {if $mainpageauth.content}<a class="hintanchor" href="admin.php?page=content" title="Content Pages :: List of all content pages."><img class="main-menu" src="{$tempdir}admin/images/maincontent.png" alt="Content Pages" border="0"/></a>{/if}
    </div>
    <div style="float:left;">
        {if $mainpageaddauth.articles && $mainpageauth.articles}<a class="hintanchor" href="admin.php?page=patrolart&action=new" title="Add Article :: Add an article."><img class="main-menu" src="{$tempdir}admin/images/mainaddarticle.png" alt="Add Article" border="0"/></a>{/if}
    </div>
    <div style="float:left;">
        {if $mainpageauth.articles}<a class="hintanchor" href="admin.php?page=patrolart" title="Articles :: List of all articles."><img class="main-menu" src="{$tempdir}admin/images/mainarticles.png" alt="Articles" border="0"/></a>{/if}
    </div>

    <div style="float:left;">
        {if $mainpageaddauth.events && $mainpageauth.events}<a class="hintanchor" href="admin.php?page=events&action=new" title="Add Event :: Add an event."><img class="main-menu" src="{$tempdir}admin/images/mainaddevent.png" alt="Add Event" border="0"/></a>{/if}
    </div>
    <div style="float:left;">
        {if $mainpageauth.events}<a class="hintanchor" href="admin.php?page=events" title="Events :: List of all events."><img class="main-menu" src="{$tempdir}admin/images/mainevents.png" alt="Events" border="0"/></a>{/if}
    </div>
    <div style="float:left;">
        {if $mainpageaddauth.news && $mainpageauth.news}<a class="hintanchor" href="admin.php?page=news&action=new" title="Add News :: Add a news item."><img class="main-menu" src="{$tempdir}admin/images/mainaddnews.png" alt="Add News" border="0"/></a>{/if}
    </div>
    <div style="float:left;">
        {if $mainpageauth.news}<a class="hintanchor" href="admin.php?page=news" title="News :: List of all news items."><img class="main-menu" src="{$tempdir}admin/images/mainnews.png" alt="News" border="0"/></a>{/if}
    </div>

    <div style="float:left;">
        {if $mainpageaddauth.photo && $mainpageauth.photo}<a class="hintanchor" href="admin.php?page=photo&action=new" title="Add Album :: Add a photo album."><img class="main-menu" src="{$tempdir}admin/images/mainaddalbum.png" alt="Add Album" border="0"/></a>{/if}
    </div>
    <div style="float:left;">
        {if $mainpageauth.photo}<a class="hintanchor" href="admin.php?page=photo" title="Photo Albums :: List of all photo albums."><img class="main-menu" src="{$tempdir}admin/images/mainphotos.png" alt="Photo Albums" border="0"/></a>{/if}
    </div>
    <div style="float:left;">
        {if $mainpageaddauth.poll && $mainpageauth.poll}<a class="hintanchor" href="admin.php?page=poll&action=new" title="Add Poll :: Add a poll."><img class="main-menu" src="{$tempdir}admin/images/mainaddpoll.png" alt="Add Poll<" border="0"/></a>{/if}
    </div>
    <div style="float:left;">
        {if $mainpageauth.poll}<a class="hintanchor" href="admin.php?page=poll" title="Polls :: List of all polls."><img class="main-menu" src="{$tempdir}admin/images/mainpolls.png" alt="Polls" border="0"/></a>{/if}
    </div>
    </td>
    
    <td style="width:400px;">
    {if $mainpageauth.content}<p class="accTitle2">Content Pages</p>
    <div class="accContent2">{if $numcontent}<table width="390px" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-15" id="sortTable">
    <thead>
      <tr>
        <th scope="col" class="smallhead" width="20px"></th>
        <th scope="col" class="smallhead sortable">Name</th>
        <th scope="col" class="smallhead sortable">Length</th>
      </tr>
      </thead><tbody>
      {section name=contentloop loop=$numcontent}
      <tr class="text">
        <td class="text">{if $mainpageeditauth.content}<a href="admin.php?page=content&amp;id={$content[contentloop].id}&amp;action=edit"><img src="{$tempdir}admin/images/edit.gif" border="0" alt="Edit {$content[contentloop].friendly}" title="Edit {$content[contentloop].friendly}" /></a>{else}<a href="{$pagename}&amp;id={$content[contentloop].id}&amp;action=edit" title="View {$content[contentloop].friendly}"><img src="{$tempdir}admin/images/page.png" border="0" alt="View {$content[contentloop].friendly}" /></a>{/if}</td>
        <td class="text">{$content[contentloop].friendly}</td>
        <td class="text">{$content[contentloop].size}</td>
      </tr>
      {/section}</tbody>
    </table>{else}No content pages available{/if}</div>{/if}


    {if $mainpageauth.articles}<p class="accTitle2">Articles</p>
    <div class="accContent2">{if $numarticles}<table width="390px" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-15" id="sortTable">
    <thead>
      <tr>
        <th scope="col" class="smallhead" width="20px"></th>
        <th scope="col" class="smallhead sortable">Name</th>
        <th scope="col" class="smallhead sortable">Length</th>
      </tr>
      </thead><tbody>
      {section name=contentloop loop=$numarticles}
      <tr class="text">
        <td class="text">{if $mainpageeditauth.articles}<a href="admin.php?page=patrolart&amp;id={$articles[contentloop].ID}&amp;action=edit"><img src="{$tempdir}admin/images/edit.gif" border="0" alt="Edit {$articles[contentloop].title}" title="Edit {$articles[contentloop].title}" /></a>{else}<img src="{$tempdir}admin/images/edit_grey.gif" border="0" alt="No editing permisions" />{/if}</td>
        <td class="text">{$articles[contentloop].title}</td>
        <td class="text">{$articles[contentloop].size}</td>
      </tr>
      {/section}</tbody>
    </table>{else}No articles available{/if}</div>{/if}


    {if $mainpageauth.events}<p class="accTitle2">Events</p>
    <div class="accContent2">{if $numevents}<table width="390px" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-15" id="sortTable">
    <thead>
      <tr>
        <th scope="col" class="smallhead" width="20px"></th>
        <th scope="col" class="smallhead sortable">Name</th>
      </tr>
      </thead><tbody>
      {section name=contentloop loop=$numevents}
      <tr class="text">
        <td class="text">{if $mainpageeditauth.events}<a href="admin.php?page=events&amp;id={$events[contentloop].id}&amp;action=edit"><img src="{$tempdir}admin/images/edit.gif" border="0" alt="Edit {$events[contentloop].summary}" title="Edit {$events[contentloop].summary}" /></a>{else}<img src="{$tempdir}admin/images/edit_grey.gif" border="0" alt="No editing permisions" />{/if}</td>
        <td class="text">{$events[contentloop].summary}</td>
      </tr>
      {/section}</tbody>
    </table>{else}No events available{/if}</div>{/if}


    {if $mainpageauth.news}<p class="accTitle2">News</p>
    <div class="accContent2">{if $numnews}<table width="390px" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-15" id="sortTable">
    <thead>
      <tr>
        <th scope="col" class="smallhead" width="20px"></th>
        <th scope="col" class="smallhead sortable">Name</th>
        <th scope="col" class="smallhead sortable">Length</th>
      </tr>
      </thead><tbody>
      {section name=contentloop loop=$numnews}
      <tr class="text">
        <td class="text">{if $mainpageeditauth.news}<a href="admin.php?page=news&amp;id={$news[contentloop].id}&amp;action=edit"><img src="{$tempdir}admin/images/edit.gif" border="0" alt="Edit {$news[contentloop].title}" title="Edit {$news[contentloop].title}" /></a>{else}<img src="{$tempdir}admin/images/edit_grey.gif" border="0" alt="No editing permisions" />{/if}</td>
        <td class="text">{$news[contentloop].title}</td>
        <td class="text">{$news[contentloop].size}</td>
      </tr>
      {/section}</tbody>
    </table>{else}No news items available{/if}</div>{/if}


    {if $mainpageauth.photo}<p class="accTitle2">Photo Albums</p>
    <div class="accContent2">{if $numalbums}<table width="390px" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-15" id="sortTable">
    <thead>
      <tr>
        <th scope="col" class="smallhead" width="20px"></th>
        <th scope="col" class="smallhead sortable">Name</th>
      </tr>
      </thead><tbody>
      {section name=contentloop loop=$numalbums}
      <tr class="text">
        <td class="text"><a href="admin.php?page=photo&amp;id={$albums[contentloop].ID}&amp;action=view"><img src="{$tempdir}admin/images/mod.gif" border="0" alt="View {$albums[contentloop].album_name}" title="View {$content[albums].album_name}" /></a></td>
        <td class="text">{$albums[contentloop].album_name}</td>
      </tr>
      {/section}</tbody>
    </table>{else}No photo albums available{/if}</div>{/if}


    {if $mainpageauth.poll}<p class="accTitle2">Polls</p>
    <div class="accContent2">{if $numpolls}<table width="390px" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-15" id="sortTable">
    <thead>
      <tr>
        <th scope="col" class="smallhead" width="20px"></th>
        <th scope="col" class="smallhead sortable">Name</th>
      </tr>
      </thead><tbody>
      {section name=contentloop loop=$numpolls}
      <tr class="text">
        <td class="text">{if $mainpageeditauth.poll}<a href="admin.php?page=poll&amp;id={$polls[contentloop].id}&amp;action=edit"><img src="{$tempdir}admin/images/edit.gif" border="0" alt="Edit {$polls[contentloop].question}" title="Edit {$polls[contentloop].question}" /></a>{else}<img src="{$tempdir}admin/images/edit_grey.gif" border="0" alt="No editing permisions" />{/if}</td>
        <td class="text">{$polls[contentloop].question}</td>
      </tr>
      {/section}</tbody>
    </table>{else}No polls available{/if}</div>{/if}
</td>
</tr>
</table>