{literal}
<script type="text/javascript">
<!--
function confirmPublish(articleId)
{
    if (confirm("This will publish the news item. Continue?")){/literal}
    document.location = "{$pagename}&action=publish&id=" + articleId;{literal}
}

function confirmunPublish(articleId)
{
    if (confirm("This will unpublish the news item. Continue?")){/literal}
    document.location = "{$pagename}&action=unpublish&id=" + articleId;{literal}
}
//-->
</script>
{/literal}
<h2>News Manager</h2>
{if $action != "edit" && $action != "new"}
{if $addallowed}<div class="toplinks"><a href="{$pagename}&amp;action=new" title="Add News Item"><img src="{$tempdir}admin/images/add.png" alt="Add News Item" border="0" /></a>
</div>{/if}
{if $numnews > 0}
<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="table sortable-onload-4-reverse rowstyle-alt paginate-15" id="sortTable">
<thead>
  <tr valign="top"> 
    <th width="5%" class="smallhead"></th>
    <th width="5%" class="smallhead">Publish</th>
    <th class="smallhead sortable">Title</th>
    <th width="20%" class="smallhead sortable-date">Date</th>
  </tr>
  </thead>
  <tbody>
 {section name=newsloop loop=$numnews}
	  <tr valign="middle" class="text"> 
		<td class="text"><div align="center">{if $editallowed}<a href="{$pagename}&amp;id={$news[newsloop].id}&amp;action=edit"><img src="{$tempdir}admin/images/edit.gif" border="0" alt="Edit {$news[newsloop].event}" title="Edit {$news[newsloop].event}" /></a>{else}<img src="{$tempdir}admin/images/edit_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;&nbsp;{if $deleteallowed}<a href="admin.php?page=news&amp;action=delete&amp;id={$news[newsloop].id}"><img src="{$tempdir}admin/images/delete.gif" border="0" alt="Delete {$news[newsloop].event}" title="Delete {$news[newsloop].event}" /></a>{else}<img src="{$tempdir}admin/images/delete_grey.gif" border="0" alt="Deleting Disabled" title="Deleting Disabled" />{/if}</div></td>
        <td class="text"><div align="center">{if $publishallowed}{if $news[newsloop].allowed == 0}<a href="javascript:confirmPublish({$news[newsloop].id})"><img src="{$tempdir}admin/images/publish.png" border="0" alt="Publish {$news[newsloop].event}" title="Publish {$news[newsloop].event}" /></a>{else}<a href="javascript:confirmunPublish({$news[newsloop].id})"><img src="{$tempdir}admin/images/unpublish.png" border="0" alt="Unpublish {$news[newsloop].event}" title="Unpublish {$news[newsloop].event}" /></a>{/if}{else}{if $news[newsloop].allowed == 0}<img src="{$tempdir}admin/images/publish_grey.gif" border="0" alt="Not allowed to publish" title="Not allowed to publish" />{else}<img src="{$tempdir}admin/images/unpublish_grey.gif" border="0" alt="Not allowed to unpublish" title="Not allowed to unpublish" />{/if}{/if}</div></td>
		<td class="text"> <span class="hintanchor" title="Preview :: {$news[newsloop].news|escape}"><img src="{$tempdir}admin/images/information.png" alt="[i]"/></span>{$news[newsloop].title}</td>
		<td class="text">{$news[newsloop].event+$timeoffset|date_format:"%Y-%m-%d"}</td>
	  </tr>  
	{/section}
    </tbody>
</table>
{else}
<div align="center">No news items</div>
{/if}
{else}
<script type="text/javascript">
{include file="../scripts/validator.tpl"}
</script>
<div align="center">
<form name="News" method="post" action="{$editFormAction}" onsubmit="return checkForm([['title','text',true,0,0,'']]);">
<fieldset class="formlist">
<legend>{if ($action!="edit")}Edit{else}Add{/if} News Item</legend>
<div class="field">
<label for="title" class="label">Title<span class="hintanchor" title="Title of the news item."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><input type="text" name="title" id="title" size="100" value="{$shownews.title}" class="inputbox" onblur="checkElement('title', 'text', true, 0, 0, '');"/><br /><span class="fieldError" id="titleError">Required</span></div><br />

<label for="attachment" class="label">Related Item<span class="hintanchor" title="Generaly this will be the item that the news is about, or related too."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><select name="attachment" id="attachment" class="inputbox">
  <option value="0" {if $shownews.attachment == 0}selected="selected"{/if}>None</option>
        <optgroup label="Articles">
            {section name=thing loop=$numarticles}
                <option value="{$article[thing].ID}.article" {if $shownews.attachment == $article[thing].idType}selected="selected"{/if}>{$article[thing].title}</option>
            {/section}
            </optgroup>
            <optgroup label="Photo Albums">
            {section name=thing loop=$numalbums}
                <option value="{$album[thing].ID}.album" {if $shownews.attachment == $album[thing].idType}selected="selected"{/if}>{$album[thing].album_name}</option>
            {/section}
            </optgroup>
            <optgroup label="Events">
            {section name=thing loop=$numevents}
                <option value="{$event[thing].id}.event" {if $shownews.attachment == $event[thing].idType}selected="selected"{/if}>{$event[thing].summary}</option>
            {/section}
            </optgroup>
            <optgroup label="Downloads">
            {section name=thing loop=$numdownloads}
                <option value="{$download[thing].id}.download" {if $shownews.attachment == $download[thing].idType}selected="selected"{/if}>{$download[thing].name}</option>
            {/section}
            </optgroup>
            <optgroup label="News">
            {section name=thing loop=$numnews}
                <option value="{$news[thing].id}.news" {if $shownews.attachment == $news[thing].idType}selected="selected"{/if}>{$news[thing].title}</option>
            {/section}
            </optgroup>
        </select>
        </div><br />
</div>

<textarea id="editor" name="editor" style="width:100%; height:50em" class="inputbox">{$shownews.news}</textarea>

<div class="submitWrapper">
{if ($action!="edit")}
<input type="submit" name="Submit" value="Add" class="button" />
{else}
<input type="submit" name="Submit" value="Modify" class="button" />
{/if}  
<input name="Cancel" type="button" id="Cancel" value="Cancel" onclick="window.location = '{$pagename}'" class="button" />
</div>
</fieldset>
</form>
</div>
{/if}
