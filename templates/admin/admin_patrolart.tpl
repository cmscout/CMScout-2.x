<h2>Article Manager</h2>
{if $action == ""}
{literal}
<script type="text/javascript">
<!--
function confirmPublish(articleId)
{
    if (confirm("This will publish the article. Continue?")){/literal}
    document.location = "{$pagename}&action=publish&id=" + articleId;{literal}
}

function confirmunPublish(articleId)
{
    if (confirm("This will unpublish the article. Continue?")){/literal}
    document.location = "{$pagename}&action=unpublish&id=" + articleId;{literal}
}
function confirmDeleteTopic(articleId) {
if (confirm("This will delete this topic. Continue?"))
document.location = "admin.php?page=patrolart&action=deltopic&id=" + articleId;
}
//-->
</script>
{/literal}
<div align="center"><div style="width:100%;">
<div id="navcontainer" align="center">
<ul class="mootabs_title">
        <li title="articles">Articles</li>
        <li title="topics">Topics</li>
        </ul>
<div id="articles" class="mootabs_panel">
{if $addallowed}<a href="{$pagename}&amp;action=new" title="Add Article"><img src="{$tempdir}admin/images/add.png" alt="Add Article" border="0" /></a>{/if}
{if $numarticles > 0}
<table width="98%" cellpadding="0" cellspacing="0" border="0" align="center" class="table sortable-onload-3 rowstyle-alt paginate-15" id="sortTable">
<thead>
<tr> 
  <th width="8%" class="smallhead"></th>
  <th width="5%" class="smallhead">Publish</th>
  <th class="smallhead sortable">Title</th>
  <th width="15%" class="smallhead sortable">Author</th>
  <th width="10%" class="smallhead sortable-date">Date of Post</th>
  <th width="15%" class="smallhead sortable">Topics</th>
  <th width="10%" class="smallhead sortable">Group</th>
  </tr>
</thead>
<tbody>
{section name=articles loop=$numarticles}
<tr class="text">
  <td class="text"><div align="center">{if $editallowed}<a href="{$pagename}&amp;id={$row[articles].ID}&amp;action=edit"><img src="{$tempdir}admin/images/edit.gif" border="0" alt="Edit {$row[articles].title}" title="Edit {$row[articles].title}" /></a>{else}<img src="{$tempdir}admin/images/edit_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;&nbsp;{if $editallowed}<a href="{$pagename}&amp;action=moveitem&amp;id={$row[articles].ID}" title="Move {$row[articles].title} to a subsite, group site or the main page static content."><img src="{$tempdir}admin/images/move.gif" border="0" alt="Move {$row[articles].title}" /></a>{else}<img src="{$tempdir}admin/images/move_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;&nbsp;{if $deleteallowed}<a href="{$pagename}&action=delete&id={$row[articles].ID}"><img src="{$tempdir}admin/images/delete.gif" border="0" alt="Delete {$row[articles].title}" title="Delete {$row[articles].title}" /></a>{else}<img src="{$tempdir}admin/images/delete_grey.gif" border="0" alt="Deleting Disabled" title="Deleting Disabled" />{/if}</div></td>
  <td class="text"><div align="center">{if $publishallowed}{if $row[articles].allowed == 0}<a href="javascript:confirmPublish({$row[articles].ID})"><img src="{$tempdir}admin/images/publish.png" border="0" alt="Publish {$row[articles].title}" title="Publish {$row[articles].title}" /></a>{else}<a href="javascript:confirmunPublish({$row[articles].ID})"><img src="{$tempdir}admin/images/unpublish.png" border="0" alt="Unpublish {$row[articles].title}" title="Unpublish {$row[articles].title}" /></a>{/if}{else}{if $row[articles].allowed == 0}<img src="{$tempdir}admin/images/publish_grey.gif" border="0" alt="Not allowed to publish" title="Not allowed to publish" />{else}<img src="{$tempdir}admin/images/unpublish_grey.gif" border="0" alt="Not allowed to unpublish" title="Not allowed to unpublis" />{/if}{/if}</div></td>
  <td class="text">{$row[articles].title}</td>
  <td class="text">{$row[articles].author}</td>
  <td class="text">{$row[articles].date_post+$timeoffset|date_format:"%Y-%m-%d"}</td>
  <td class="text">{$row[articles].topics}</td>
  <td class="text">{if $row[articles].patrol != ""}{$row[articles].patrol}{else}General Articles{/if}</td>
</tr>
{/section}
</tbody>
</table>
{else}
<div align="center" width="100%">No articles</div>
{/if}
</div>
<div id="topics" class="mootabs_panel">{if $addallowed && !$limitgroup}<div class="toplinks"><a href="{$pagename}&amp;action=newtopic" title="Add topic"><img src="{$tempdir}admin/images/add.png" alt="Add topic" border="0" /></a>
</div>{/if}
{if $numtopics > 0}
<table width="98%" cellpadding="0" cellspacing="0" border="0" align="center" class="table sortable-onload-3 rowstyle-alt paginate-15" id="sortTable1">
<thead>
<tr> 
  <th width="5%" class="smallhead"></th>
  <th class="smallhead">Topic Title</th>
  </tr>
</thead>
<tbody>
{section name=topic loop=$numtopics}
<tr class="text">
  <td class="text"><div align="center">{if $editallowed && !$limitgroup}<a href="{$pagename}&amp;id={$topics[topic].id}&amp;action=edittopic"><img src="{$tempdir}admin/images/edit.gif" border="0" alt="Edit {$topics[topic].title}" title="Edit {$topics[topic].title}" /></a>{else}<img src="{$tempdir}admin/images/edit_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;&nbsp;{if $deleteallowed && !$limitgroup}<a href="javascript:confirmDeleteTopic({$topics[topic].id})"><img src="{$tempdir}admin/images/delete.gif" border="0" alt="Delete {$topics[topic].title}" title="Delete {$topics[topic].title}" /></a>{else}<img src="{$tempdir}admin/images/delete_grey.gif" border="0" alt="Deleting Disabled" title="Deleting Disabled" />{/if}</div></td>
  <td class="text">{$topics[topic].title}</td>
</tr>
{/section}
</tbody>
</table>
{else}
<div align="center">No topics</div>
{/if}
</div>
</div></div></div>
{elseif $action=="edit" || $action=="new"}
<script type="text/javascript">
{include file="../scripts/validator.tpl"}
</script>
<div align="center">
<form action="{$editFormAction}" method="post" name="noteadd" id="noteadd" enctype="multipart/form-data" onsubmit="return checkForm([['title','text',true,0,0,''],['auth','text',true,0,0,'']]);">
<fieldset class="formlist">
<legend>{if $action=="edit"}Edit{else}New{/if} Article</legend>
<div class="field">

<label for="title" class="label"><b>Title</b><span class="hintanchor"
title="Required :: Title for the article."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><input name="title" type="text" id="title" size="50" value="{$row.title}" class="inputbox" onblur="checkElement('title', 'text', true, 0, 0, '');"/><br /><span class="fieldError" id="titleError">Required</span></div><br />

<label for="auth" class="label"><b>Author</b><span class="hintanchor" title="Required :: The author of this article"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><input name="auth" type="text" id="auth" size="50" value="{$row.author}" class="inputbox"  onblur="checkElement('auth', 'text', true, 0, 0, '');"/><br /><span class="fieldError" id="authError">Required</span></div><br />

    {if $numalbum > 0}
    {literal}
<script type="text/javascript">
function selectImage(id)
{
   oldid = document.getElementById('articlephoto').value;
   document.getElementById('articlephoto').value = id;
   
    if (document.getElementById(oldid))
    {    
        document.getElementById(oldid).style.borderWidth = "0px";
    }
    document.getElementById(id).style.borderWidth = "5px";
}

function getAlbumData()
{
    var index = document.getElementById('albumSelect').selectedIndex;
    var id = document.getElementById('albumSelect').options[index].value;
    var albums = new Array();
    var photoId = new Array();
    {/literal}
    {section name=album loop=$numalbum}
       {if $albums[album].numphotos}
       albums[{$albums[album].ID}] = "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"2\" align=\"center\"><tr style=\"height:135px\">{section name=photos loop=$albums[album].numphotos}<td width=\"135px\" style=\"vertical-align:middle;\"><div align=\"center\" ><a href=\"javascript:selectImage('{$albums[album].photos[photos].ID}');\" title=\"Insert Photo\"><img border=\"0\" src=\"thumbnail.php?pic={$albums[album].photos[photos].ID}\" alt=\"Insert Photo\" class=\"selectImage\" id=\"{$albums[album].photos[photos].ID}\" /></a></div></td>{if ($smarty.section.photos.iteration % 3 == 0)}</tr><tr style=\"height:135px\">{/if}{/section}</tr></table>";{/if}
    {/section}
    {literal}
    if (id != 0)
    {
        description_div = document.getElementById('showAlbum');
        description_div.innerHTML = '';
        description_div.innerHTML = albums[id];
        description_div.style.height = "280px";
        selectedID = document.getElementById('articlephoto').value; 
        if (document.getElementById(selectedID))
        {
            document.getElementById(selectedID).style.borderWidth = "5px";
        }
    }
    else
    {
        document.getElementById('articlephoto').value = 0;
        description_div = document.getElementById('showAlbum');        
        description_div.innerHTML = '';
        description_div.innerHTML = 'No album selected';
        description_div.style.height = "";
    }
}
</script>
{/literal}
     <label for="photo" class="label">Photo Album<span class="hintanchor"title="Optional :: Attach a photo album to this article."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
        <div class="inputboxwrapper"><select name="photo" id="photo" class="inputbox">
            <option value="0" selected>None</option>
          {section name=albums loop=$numalbum}
            {if $albums[albums].numphotos > 0}
            <option value="{$albums[albums].ID}" {if $row.album_id == $albums[albums].ID}selected="selected"{/if}>{$albums[albums].album_name}</option>
            {/if}
          {/section}
        </select></div><br />
        
     <label for="albumSelect" class="label">Article Photo<span class="hintanchor"title="Optional :: This picture will be shown at the top of the article when an user views it."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
        <div class="inputboxwrapper"><select id="albumSelect" class="inputbox" onchange="getAlbumData();">
            <option value="0" selected>None</option>
          {section name=albums loop=$numalbum}
            {if $albums[albums].numphotos > 0}
            <option value="{$albums[albums].ID}" {if $selectedAlbum == $albums[albums].ID}selected="selected"{/if}>{$albums[albums].album_name}</option>
            {/if}
          {/section}
        </select><br /><div id="showAlbum" style="overflow:auto;width:480px;{if $selectedAlbum}height:280px{/if}">{if !$selectedAlbum}No album selected{else}<table width="100%" cellspacing="0" cellpadding="2" align="center"><tr style="height:135px">{section name=photos loop=$selectedAlbumInfo.numphotos}<td width="135px" style="vertical-align:middle;"><div align="center" ><a href="javascript:selectImage('{$selectedAlbumInfo.photos[photos].ID}');" title="Insert Photo"><img border="0" src="thumbnail.php?pic={$selectedAlbumInfo.photos[photos].ID}" alt="Insert Photo" class="selectImage" id="{$selectedAlbumInfo.photos[photos].ID}" {if $row.pic == $selectedAlbumInfo.photos[photos].ID}style="border-width:5px;"{/if}/></a></div></td>{if ($smarty.section.photos.iteration % 3 == 0)}</tr><tr style="height:135px">{/if}{/section}</tr></table>{/if}</div><input type="hidden" name="articlephoto" id="articlephoto" value="{$row.pic}" /></div><br />
   {/if}
   

{if $numevents > 0}
    <label for="event" class="label"><b>Event</b><span class="hintanchor" title="Optional :: Attach a event to this article."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper"><select name="event" id="event" class="inputbox">
        <option value="0" selected>None</option>
        {section name=events loop=$numevents}
            <option value="{$event[events].id}"{if $event[events].id == $row.event_id}selected{/if}>{$event[events].summary}   ({$event[events].startdate|date_format:"%Y-%m-%d"} to {$event[events].enddate|date_format:"%Y-%m-%d"})</option>
        {/section}
    </select></div><br />
{/if}                   

{if $numteams > 0}
    <label for="patrol" class="label"><b>Group</b><span class="hintanchor" title="Optional :: Group site to place this article"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper"><select name="patrol" id="patrol" class="inputbox">
        <option value="0" {if $row.patrol == 0} selected{/if}>None</option>
        {section name=team loop=$numteams}
            <option value="{$teams[team].id}" {if $row.patrol == $teams[team].id} selected{/if} >{$teams[team].teamname}</option>
        {/section}
    </select></div><br />
{/if}

<span class="label"><b>Topics</b><span class="hintanchor" title="Optional :: Select any topics you want this article to fall under"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></span>
<div class="inputboxwrapper"><ul class="checklist" style="height:10em;">
    {section name=topic loop=$numtopics}
        {assign var="id" value=$topics[topic].id}
        <li><label for="topics{$topics[topic].id}"><input type="checkbox" value="1" name="topics[{$topics[topic].id}]" id="topics{$topics[topic].id}" {if $row.topics.$id == 1}checked="checked"{/if} {if $topics[topic].disabled}onchange="document.getElementById('topics{$topics[topic].id}').checked = {if $row.topics.$id == 1}true{else}false{/if};"{/if} />{$topics[topic].title}</label></li>
    {sectionelse}
        <li>No available topics</li>
    {/section} 
</ul></div><br />

<span class="label"><b>Related Articles</b><span class="hintanchor" title="Optional :: Select articles that are related to this one."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></span>
<div class="inputboxwrapper"><ul class="checklist" style="height:10em;">
    {section name=articles loop=$numarticles}
        {assign var="id" value=$article[articles].ID}
        <li><label for="articles{$article[articles].ID}"><input type="checkbox" value="1" name="articles[{$article[articles].ID}]" id="articles{$article[articles].ID}"  {if $row.related.$id == 1}checked="checked"{/if} />{$article[articles].title}</label></li>
    {sectionelse}
        <li>No available articles</li>
    {/section} 
</ul></div><br />

<label for="order" class="label"><b>Order</b><span class="hintanchor" title="Optional :: Order of article. Only applies to topics with a custom ordering."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><input name="order" type="text" id="order" size="5" value="{$row.order}" class="inputbox" onblur="checkElement('order', 'number', true, 0, 0, '');" /><br /><span class="fieldError" id="orderError">Required: Must be a number</span></div><br />

<label for="summary" class="label"><b>Short summary</b><span class="hintanchor" title="Optional :: A short summary of the article."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><textarea id="summary" name="summary" rows="20" class="inputbox">{$row.summary}</textarea></div><br />
</div>
<textarea id="editor" name="editor" style="width:100%; height:50em" class="inputbox">{$row.detail}</textarea>	
<div class="submitWrapper">
<input type="submit" name="Submit" value="Submit" class="button" />
<input name="Cancel" type="button" id="Cancel" value="Cancel" onclick="window.location = '{$pagename}';" class="button" />
</div></fieldset>
</form>
{elseif $action=="edittopic" || $action == "newtopic"}
<script type="text/javascript">
{include file="../scripts/validator.tpl"}
</script>
<div align="center">
 <form method="post" action="{$editFormAction}" name="form" onsubmit="return checkForm([['title','text',true,0,0,''],['description','text',true,0,0,''],['perpage', 'number', true, 0, 0, '']]);">
 <fieldset class="formlist">
 <legend>Edit Topic</legend>
 <div class="field">
 <label for="summary" class="label"><b>Topic Name</b><span class="hintanchor" title="Required :: Name of topic."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><input name="title" type="text" id="title" value="{$topic.title}" class="inputbox" onblur="checkElement('title', 'text', true, 0, 0, '');" /><br /><span class="fieldError" id="titleError">Required</span></div><br />
 
<label for="summary" class="label"><b>Description</b><span class="hintanchor" title="Required :: Description for the topic."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><textarea name="description" id="description" class="inputbox"  onblur="checkElement('description', 'text', true, 0, 0, '');">{$topic.description}</textarea><br /><span class="fieldError" id="descriptionError">Required</span></div><br />

<label for="summary" class="label"><b>Sort method</b><span class="hintanchor" title="How should the articles in this topic be sorted. Custom Order uses the value of the Order field of the article."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><select name="sort" id="sort" class="inputbox">
                    <option value="title" {if $topic.sort == "title"}selected="selected"{/if}>Title</option>
                    <option value="date_post" {if $topic.sort == "date_post"}selected="selected"{/if}>Date Posted</option>
                    <option value="author" {if $topic.sort == "author"}selected="selected"{/if}>Author</option>
                    <option value="order" {if $topic.sort == "order"}selected="selected"{/if}>Custom Order</option>
                </select>
                <select name="order" id="order" class="inputbox">
                    <option value="ASC" {if $topic.order == "ASC"}selected="selected"{/if}>Ascending</option>
                    <option value="DESC" {if $topic.order == "DESC"}selected="selected"{/if}>Descending</option>
                </select></div><br />

<label for="summary" class="label"><b>Groups</b><span class="hintanchor" title="Which groups are allowed to add articles into this topic."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper">
    <ul class="checklist">
            {section name=team loop=$numteams}
                {assign var="id" value=$teams[team].id}
               <li><label for="group{$teams[team].id}"><input type="checkbox" value="1" name="groups[{$teams[team].id}]" id="group{$teams[team].id}" {if $topic.groups.$id == 1}checked="checked"{/if}>{$teams[team].teamname}</label></li>
            {/section} 
    </ul></div><br />

<label for="summary" class="label"><b>Amount/page</b><span class="hintanchor" title="Required :: Number of articles to show per page."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper">
    <input name="perpage" type="text" id="perpage" value="{$topic.perpage}" class="inputbox" onblur="checkElement('perpage', 'number', true, 0, 0, '');" /><br /><span class="fieldError" id="perpageError">Required. Must be a number.</span></div><br />

<label for="summary" class="label"><b>Display Method</b><span class="hintanchor" title="How should this topic be displayed."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><select name="display" id="display" class="inputbox">
    <option value="1" {if $topic.display == "1"}selected="selected"{/if}>Table style</option>
    <option value="2" {if $topic.display == "2"}selected="selected"{/if}>Title list</option>
    <option value="3" {if $topic.display == "3"}selected="selected"{/if}>Photo thumbnail grid</option>
    <option value="4" {if $topic.display == "4"}selected="selected"{/if}>List with summaries</option>
</select></div><br />
</div>
<div class="submitWrapper">
  <input type="Submit" name="Submit" id="Submit" value="Submit" class="button" />&nbsp;<input type="button" value="Cancel" id="cancel" name="cancel" onclick="window.location = '{$pagename}&amp;activetab=topics'" class="button" /></div>
 </fieldset>
 </form>
 </div>
{elseif $action=="moveitem"}
<div align="center">
 <form method="post" action="{$editFormAction}" name="form">
 <fieldset class="formlist">
 <legend>Move Article</legend>
 <div class="field">
 <label for="place" class="label">Location<span class="hintanchor" title="Where do you wish to move this article too?"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
 <div class="inputboxwrapper">
 <select name="place" id="place" class="inputbox">
  {if !$limitgroup}<option value="0" selected="selected">Main content</option>{/if}
  <optgroup label="Group Sites">
  {section name=loop loop=$numpatrols}
   <option value="group_{$patrols[loop].id}">{$patrols[loop].teamname}</option>
  {/section}
  </optgroup>
  <optgroup label="Sub Sites">
  {section name=loop loop=$numsubsites}
   <option value="site_{$subsites[loop].id}">{$subsites[loop].name}</option>
  {/section}
  </optgroup>
 </select></div><br />
 <span class="label">Preserve<span class="hintanchor" title="Unless you choose to preserve the article all article specific content (Date of event, date article was added, article photo, author, etc.) will be lost. If the article is preserved it will be marked as unpublished. There is also no way of moving an content item back to an article."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></span><div class="inputboxwrapper"><input type="radio" name="preserve" id="preserve" value="1" />Yes&nbsp;<input type="radio" id="preserve" name="preserve" value="0" checked="checked" />No</div>
 <br />
 </div>
 <div class="submitWrapper">
 <input type="Submit" name="Submit" id="Submit" value="Move" class="button" />&nbsp;<input type="button" value="Cancel" id="cancel" name="cancel" onclick="window.location = '{$pagename}&amp;pid={$patrolid}'" class="button" />
 </div>
 </fieldset>
 </form>
</div>
{/if}