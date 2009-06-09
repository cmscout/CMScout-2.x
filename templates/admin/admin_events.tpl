{literal}
<script type="text/javascript">
<!--
function confirmPublish(articleId)
{
    if (confirm("This will publish the event. Continue?")){/literal}
    document.location = "{$pagename}&action=publish&id=" + articleId;{literal}
}

function confirmunPublish(articleId)
{
    if (confirm("This will unpublish the event. Continue?")){/literal}
    document.location = "{$pagename}&action=unpublish&id=" + articleId;{literal}
}
//-->
</script>
{/literal}
<h2>Event Manager</h2>
{if $action == ''}
<div id="navcontainer">
<h4 title="events">Events</h4>
<div id="events">
{if $addallowed}<div class="toplinks"><a href="{$pagename}&amp;action=new" title="Add Event"><img src="{$tempdir}admin/images/add.png" alt="Add Event" border="0" /></a>
</div>{/if}
{if $numevents > 0}
<table width="98%" cellpadding="0" cellspacing="0" border="0" align="center" class="table sortable-onload-3 rowstyle-alt paginate-15" id="sortTable">
<thead>
	  <tr>
		<th width="80" class="smallhead"></th>
        <th  width="6%" class="smallhead">Publish</th>
		<th class="smallhead sortable">Summary</th>
		<th  width="30%" class="smallhead sortable">Dates</th>
	  </tr>
      </thead>
      <tbody>
	{section name=eventloop	loop=$numevents}
	  <tr class="text">
		<td class="text"><div align="center">{if $editallowed}<a href="{$pagename}&amp;action=edit&amp;id={$events[eventloop].id}"><img src="{$tempdir}admin/images/edit.gif" border="0" alt="Edit {$events[eventloop].summary}" title="Edit {$events[eventloop].summary}" /></a>{else}<img src="{$tempdir}admin/images/edit_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;&nbsp;{if $events[eventloop].signup && $editallowed}<a href="{$pagename}&amp;action=signups&amp;id={$events[eventloop].id}"><img src="{$tempdir}admin/images/checkbox.png" border="0" alt="Signup information for {$events[eventloop].summary}" title="Signup information for {$events[eventloop].summary}" /></a>{else}<img src="{$tempdir}admin/images/checkbox_grey.png" border="0" alt="Signup disabled for event" title="Signup disabled for event" />{/if}&nbsp;&nbsp;{if $deleteallowed}<a href="{$pagename}&amp;action=delete&amp;id={$events[eventloop].id}"><img src="{$tempdir}admin/images/delete.gif" border="0" alt="Delete {$events[eventloop].summary}" title="Delete {$events[eventloop].summary}" /></a>{else}<img src="{$tempdir}admin/images/delete_grey.gif" border="0" alt="Deleting Disabled" title="Deleting Disabled" />{/if}</div></td>
  <td class="text"><div align="center">{if $publishallowed}{if $events[eventloop].allowed == 0}<a href="javascript:confirmPublish({$events[eventloop].id})"><img src="{$tempdir}admin/images/publish.png" border="0" alt="Publish {$events[eventloop].summary}" title="Publish {$events[eventloop].summary}" /></a>{else}<a href="javascript:confirmunPublish({$events[eventloop].id})"><img src="{$tempdir}admin/images/unpublish.png" border="0" alt="Unpublish {$events[eventloop].summary}" title="Unpublish {$events[eventloop].summary}" /></a>{/if}{else}{if $events[eventloop].allowed == 0}<img src="{$tempdir}admin/images/publish_grey.gif" border="0" alt="Not allowed to publish" title="Not allowed to publish" />{else}<img src="{$tempdir}admin/images/unpublish_grey.gif" border="0" alt="Not allowed to unpublish" title="Not allowed to unpublis" />{/if}{/if}</div></td>
		<td class="text">{$events[eventloop].summary}</td>
		<td class="text">{$events[eventloop].startdate|date_format:"%B %e, %Y"} to {$events[eventloop].enddate|date_format:"%B %e, %Y"}</td>
	  </tr>
	{/section}
    </tbody>
	</table>
    {else}
<div align="center">No events on the calender</div>
{/if}
 </div>
 
 <h4 title="ical">iCalendar links</h4>
 <div id="ical">
  {if $addallowed}<div class="toplinks"><a href="{$pagename}&amp;action=newical" title="Add Link"><img src="{$tempdir}admin/images/add.png" alt="Add Link" border="0" /></a>
</div>{/if}
 {if $numical > 0}
<table width="98%" cellpadding="0" cellspacing="0" border="0" align="center" class="table sortable-onload-3 rowstyle-alt paginate-15" id="sortTable1">
<thead>
	  <tr>
		<th width="6%" class="smallhead"></th>
        <th  width="30%" class="smallhead">Name</th>
		<th class="smallhead">Link</th>
	  </tr>
      </thead>
      <tbody>
	{section name=eventloop	loop=$numical}
	  <tr class="text">
		<td class="text"><div align="center">{if $editallowed}<a href="{$pagename}&amp;action=editical&amp;id={$ical[eventloop].id}"><img src="{$tempdir}admin/images/edit.gif" border="0" alt="Edit {$ical[eventloop].name}" title="Edit {$ical[eventloop].name}" /></a>{else}<img src="{$tempdir}admin/images/edit_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;&nbsp;{if $deleteallowed}<a href="{$pagename}&action=deleteical&id={$ical[eventloop].id}"><img src="{$tempdir}admin/images/delete.gif" border="0" alt="Delete {$ical[eventloop].name}" title="Delete {$ical[eventloop].name}" /></a>{else}<img src="{$tempdir}admin/images/delete_grey.gif" border="0" alt="Deleting Disabled" title="Deleting Disabled" />{/if}</div></td>
		<td class="text">{$ical[eventloop].name}</td>
		<td class="text">{$ical[eventloop].link}</td>
	  </tr>
	{/section}
    </tbody>
	</table>
    {else}
<div align="center">No iCalendar links</div>
{/if}
 </div></div>
 {elseif $action == 'signups'}
 <script type="text/javascript">
{include file="../scripts/validator.tpl"}
</script>
<div id="navcontainer">       
<h4 title="events">Attendies</h4>
<div id="events">
<div class="toplinks"><a href="{$pagename}" title="Back"><img src="{$tempdir}admin/images/back.png" alt="Back" border="0" /></a></div>
{if $nummembers > 0}
{literal}
  <script type="text/javascript">
<!--
function markall() 
{
{/literal}itemList = [{section name=dynamic loop=$nummembers}'{$members[dynamic].id}'{if $smarty.section.dynamic.iteration <$nummembers},{/if}{/section}];
    number = {$nummembers};{literal}
    for (i=0;i<number;i++)
    {
        document.getElementById('attend' + itemList[i]).checked = document.getElementById('allattend').checked;
        showdiv(itemList[i]);
    }
}

function showdiv(id)
{
    if (document.getElementById('attend' + id).checked)
    {
        document.getElementById('details' + id).style.display = 'block';
    }
    else
    {
        document.getElementById('details' + id).style.display = 'none';
    }
}
function confirmFieldDelete(articleId)
{
    if (confirm("This will remove this field. Continue?")){/literal}
    document.location = "{$pagename}&action=deletefield&event={$eventid}&id=" + articleId;{literal}
}
function confirmDownloadDelete(articleId)
{
    if (confirm("This will remove this download. Continue?")){/literal}
    document.location = "{$pagename}&action=deletedownload&event={$eventid}&id=" + articleId;{literal}
}
//-->
</script>
  {/literal}
<form method="post" name="form1" id="form1">
<div class="field">
<table width="98%" cellpadding="0" cellspacing="0" border="0" align="center" class="table sortable-onload-3 rowstyle-alt paginate-15" id="sortTable">
<thead>
	  <tr>
		<th width="20px" class="smallhead"><input type="checkbox" id="allattend" value="1" onclick="markall();"/></th>
		<th class="smallhead sortable">Name</th>
	  </tr>
      </thead>
      <tbody>
	{section name=attendloop loop=$nummembers}
	  <tr class="text" valign="top">
		<td class="text"><input type="checkbox" name="attend[{$members[attendloop].id}]" id="attend{$members[attendloop].id}" value="1" onclick="showdiv('{$members[attendloop].id}')" {if $members[attendloop].attend}checked="checked"{/if}/></td>
		<td class="text"><label for="attend{$members[attendloop].id}">{$members[attendloop].lastName}, {$members[attendloop].firstName}</label>
{if $numfields > 0}
        <fieldset id="details{$members[attendloop].id}" style="margin:3px; padding:2px;border:1px solid #000;width:100%;display:{if $members[attendloop].attend}block{else}none{/if};">
<strong>Additional Information</strong>
    {section name=fields loop=$numfields}
        <div class="fieldItem"><label for="{$fields[fields].name}{$members[attendloop].id}" class="label">{$fields[fields].query}<span class="hintanchor" title="{if $fields[fields].required}Required{else}Optional{/if}::{$fields[fields].hint}"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
        {assign var="name" value=$fields[fields].name}
        <div class="inputboxwrapper">
        {if $fields[fields].type == 1}
            <input name="options[{$members[attendloop].id}][{$fields[fields].name}]" id="{$fields[fields].name}{$members[attendloop].id}" type="text" size="{math equation="x + y" x=$fields[fields].options y=5}" {if $fields[fields].options > 0}maxlength="{$fields[fields].options}"{/if} value="{$members[attendloop].attendoptions.$name}" class="inputbox" {if $fields[fields].required}onblur="checkElement('{$fields[fields].name}{$members[attendloop].id}', 'text', true, 0, 0, '');"{/if} />{if $fields[fields].required}<br /><span class="fieldError" id="{$fields[fields].name}{$members[attendloop].id}Error">Required</span>{/if}
        {elseif $fields[fields].type == 2}
            <textarea name="options[{$members[attendloop].id}][{$fields[fields].name}]" id="{$fields[fields].name}{$members[attendloop].id}" rows="5"  class="inputbox" {if $fields[fields].required}onblur="checkElement('{$fields[fields].name}{$members[attendloop].id}', 'text', true, 0, 0, '');"{/if}>{$members[attendloop].attendoptions.$name}</textarea>{if $fields[fields].required}<br /><span class="fieldError" id="{$fields[fields].name}{$members[attendloop].id}Error">Required</span>{/if}
        {elseif $fields[fields].type == 3}
            {section name=options loop=$fields[fields].options[0]+1 start=1}
            <input type="radio" name="options[{$members[attendloop].id}][{$fields[fields].name}]" id="{$fields[fields].name}{$members[attendloop].id}:{$smarty.section.options.iteration}" value="{$smarty.section.options.iteration}" {if $members[attendloop].attendoptions.$name == $smarty.section.options.iteration}checked="checked"{/if} /><label for="{$fields[fields].name}{$members[attendloop].id}:{$smarty.section.options.iteration}">{$fields[fields].options[options]|deurl}</label>
            {/section}
        {elseif $fields[fields].type == 4}
            {assign var="temp" value=$members[attendloop].attendoptions.$name}
            {section name=options loop=$fields[fields].options[0]+1 start=1}
            <input type="checkbox" name="options[{$members[attendloop].id}][{$fields[fields].name}][{$smarty.section.options.iteration}]" id="{$fields[fields].name}{$members[attendloop].id}:{$smarty.section.options.iteration}" value="1" {if $temp[options] == 1}checked="checked"{/if} /><label for="{$fields[fields].name}{$members[attendloop].id}:{$smarty.section.options.iteration}">{$fields[fields].options[options]|deurl}</label>&nbsp;
            {/section}
        {elseif $fields[fields].type == 5}
            <select name="options[{$members[attendloop].id}][{$fields[fields].name}]" id="{$fields[fields].name}{$members[attendloop].id}" class="inputbox">
            {section name=options loop=$fields[fields].options[0]+1 start=1}
            <option value="{$smarty.section.options.iteration}" {if $members[attendloop].attendoptions.$name == $smarty.section.options.iteration}selected="selected"{/if}>{$fields[fields].options[options]|deurl}</option>
            {/section}
            </select>  
        {elseif $fields[fields].type == 6}
            <input name="options[{$members[attendloop].id}][{$fields[fields].name}]" id="{$fields[fields].name}{$members[attendloop].id}" type="text" value="{$members[attendloop].attendoptions.$name}" class="inputbox format-y-m-d highlight-days-67" onblur="checkElement('{$fields[fields].name}{$members[attendloop].id}', 'date', {if $fields[fields].required}true{else}false{/if}, 0, 0, '');"/><br /><span class="fieldError" id="{$fields[fields].name}{$members[attendloop].id}Error">{if $fields[fields].required}Required: {/if}Must be a valid date in the format YYYY-MM-DD</span>         
        {/if}   
        </div></div><br />
    {/section}          
</fieldset>
{/if}
        </td>
	  </tr>
	{/section}
    </tbody>
	</table>
    </div>
    <div class="submitWrapper"><input type="submit" name="Submit" value="Update" class="button" /></div>
    </form>
    {else}
<div align="center">No members in database.</div>
{/if}
 </div>
 
 <h4 title="ical">Extra information fields</h4>
 <div id="ical">
  <div class="toplinks">{if $addallowed}<a href="{$pagename}&amp;action=newfield&amp;event={$eventid}" title="Add Field"><img src="{$tempdir}admin/images/add.png" alt="Add Link" border="0" /></a>{/if}<a href="{$pagename}" title="Back"><img src="{$tempdir}admin/images/back.png" alt="Back" border="0" /></a></div>
 {if $numfields > 0}
<table width="98%" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-15" id="sortTable">
<thead>
  <tr valign="top"> 
    <th width="8%" class="smallhead"></th>
    <th class="smallhead" >Name</th>
    <th class="smallhead" width="20%">Type</th>
  </tr>
  </thead>
<tbody>
 {section name=fields loop=$numfields}
	  <tr class="text" valign="top"> 
		<td class="text" style="text-align:center;">{if $editallowed}<a href="{$pagename}&amp;id={$fields[fields].id}&amp;action=editfield&amp;event={$eventid}"><img src="{$tempdir}admin/images/edit.gif" border="0" alt="Edit {$fields[fields].name}" title="Edit {$fields[fields].name}" /></a>{else}<img src="{$tempdir}admin/images/edit_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;&nbsp;{if $deleteallowed}<a href="javascript:confirmFieldDelete({$fields[fields].id})"><img src="{$tempdir}admin/images/delete.gif" border="0" alt="Remove {$fields[fields].name}" title="Remove {$fields[fields].name}" /></a>{else}<img src="{$tempdir}admin/images/delete_grey.gif" border="0" alt="Deleting Disabled" title="Deleting Disabled" />{/if}</td>
		<td class="text">
        <span class="hintanchor" title="Information :: <b>Name:</b> {$fields[fields].name}<br /><b>Required:</b> {if $fields[fields].required}Yes{else}No{/if}<br /><b>Filled in by:</b> {if $fields[fields].register}Attendie{else}Event Master{/if}"><img src="{$tempdir}admin/images/information.png" alt="[i]"/></span>{$fields[fields].query}</td>
        <td class="text">{if $fields[fields].type == 1}Text Input{elseif $fields[fields].type == 2}Text Area{elseif $fields[fields].type == 3}Radio buttons{elseif $fields[fields].type == 4}Checkboxes{elseif $fields[fields].type == 5}Select Box{elseif $fields[fields].type == 6}Date Input{/if}</td>
      </tr>  
    {/section}
    </tbody>
</table>
    {else}
<div align="center">No extra fields</div>
{/if}
 </div>
 
 <h4 title="downloads">Attached Downloads</h4>
  <div id="downloads">
  <div class="toplinks"><a href="{$pagename}" title="Back"><img src="{$tempdir}admin/images/back.png" alt="Back" border="0" /></a></div>
 {if $numeventdownloads > 0}
<table width="98%" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-15" id="sortTable">
<thead>
  <tr valign="top"> 
    <th width="8%" class="smallhead"></th>
    <th class="smallhead" >Name</th>
  </tr>
  </thead>
<tbody>
 {section name=fields loop=$numeventdownloads}
	  <tr class="text" valign="top"> 
		<td class="text" style="text-align:center;">{if $download_editallowed}<a href="admin.php?page=downloads&amp;action=editdown&amp;did={$event_downloads[fields].did}&amp;event={$eventid}"><img src="{$tempdir}admin/images/edit.gif" border="0" alt="Edit {$field[fields].name}" title="Edit {$event_downloads[fields].name}" /></a>{else}<img src="{$tempdir}admin/images/edit_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;&nbsp;{if $deleteallowed}<a href="javascript:confirmDownloadDelete({$event_downloads[fields].id})"><img src="{$tempdir}admin/images/delete.gif" border="0" alt="Remove {$event_downloads[fields].name}" title="Remove {$event_downloads[fields].name}" /></a>{else}<img src="{$tempdir}admin/images/delete_grey.gif" border="0" alt="Deleting Disabled" title="Deleting Disabled" />{/if}</td>
		<td class="text">
        {$event_downloads[fields].name}</td>
      </tr>  
    {/section}
    </tbody>
</table>
    {else}
<div align="center">No attached downloads</div>
{/if}
{if $editallowed && !$limitgroup}
<table width="98%" cellpadding="0" cellspacing="0" border="0" class="table" align="center">
<tr>
<th class="smallhead" colspan="3">Attach a download</th>
<tr class="text">
<td class="text" colspan="3">
<form method="post" action="{$pagename}&amp;action=adddownload&amp;id={$eventid}">
<div class="field">
<div class="fieldItem"><label for="gid" class="label">Download<span class="hintanchor" title="Required :: Choose a download to attach to this event, you may choose unlimited downloads."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><select name="download" id="download" class="inputbox">
<option value="0">Select download</option>
{section name=cats loop=$numcategories}
{if $downloads[cats].number > 0}
<optgroup label="{$downloads[cats].name}">
{section name=downloads loop=$downloads[cats].number}
<option value="{$downloads[cats].downloads[downloads].id}">{$downloads[cats].downloads[downloads].name}</option>
{/section}
</optgroup>
{/if}
{/section}
</select></div></div><br />
<div class="fieldItem"><label for="gid" class="label">Permissions<span class="hintanchor" title="Required :: Choose who may download this item.<br /><strong>Note:</strong> Selecting 'only attendies' will bypass standard download category permissions, whilst selecting 'all users' will use standard download category permissions."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><select name="permissions" id="permissions" class="inputbox">
<option value="0">All users can download</option>
<option value="1">Only attendies can download</option>
</select></div></div><br />
</div>
<div class="submitWrapper">
<input type="submit" class="button" value="Add" name="action" id="action" /></div>
</form></td>
</tr>
</table>
{/if}
 </div>
 {elseif $action == "newfield" || $action=="editfield"}
 {literal}
  <script type="text/javascript">
<!--
 function changetype()
 {
    var type = document.getElementById('type').value;
    var optiondiv = document.getElementById('optiondiv');
    optiondiv.innerHTML = '';
    switch (type)
    {
        case '1': case '2':
                optiondiv.innerHTML = '<div class="fieldItem"><label for="options" class="label">Maximum size</label><div class="inputboxwrapper"><input type="text" size="10" name="options" id="options" class="inputbox"/></div></div><br />';
                break;
        case '3': case '4': case '5':
        {/literal}
                optiondiv.innerHTML = '<div class="fieldItem"><label for="numoptions" class="label">Number of options</label><div class="inputboxwrapper"><input type="text" size="10" class="inputbox" name="numoptions" id="numoptions"  onchange="changeoptions(' + type + ')" value="1" style="width:70%"/><a href="#" onclick="takeone(' + type + ');"><img src="{$tempdir}admin/images/small_arrow_delete.png" title="[-]" border="0"/></a><a href="#" onclick="addone(' + type + ');"><img src="{$tempdir}admin/images/small_arrow_add.png" title="[+]" border="0"/></a></div></div><br /><div id="optiondiv2"><div class="fieldItem"><label for="option1" class="label">Option 1</label><div class="inputboxwrapper"><input type="text" name="option1" id="option1" size="50"  class="inputbox" style="width:70%"/></div></div><br /></div>';
                {literal}
                break;
        case '6':
                break;
    }
 }
 
 function changeoptions(type)
 {
    var numoptions = document.getElementById('numoptions').value;
    var optiondiv = document.getElementById('optiondiv2');
    var temp = '';
    var html = '';
    for(var i=1;i<=numoptions;i++)
    {
        temp = ''; 
        if (document.getElementById('option' + i)) 
        {
            temp = document.getElementById('option' + i).value; 
        }
        html = html + '<div class="fieldItem"><label for="option' + i + '" class="label">Option ' + i + '</label><div class="inputboxwrapper"><input type="text" name="option' + i + '" id="option' + i + '" size="50"  class="inputbox" value="' + temp + '" style="width:70%"/>';
        if (i>1 && i < numoptions)
        {
           {/literal} 
            html = html + '<a href="#" onclick="moveup('+i+');" title="Move up"><img src="{$tempdir}admin/images/small_arrow_up.png" title="[^]" border="0"/></a<a href="#" onclick="movedown('+i+');" title="Move down"><img src="{$tempdir}admin/images/small_arrow.png" title="[v]" border="0"/></a>';
            {literal}
        }
        else if (i==1 && numoptions>1)
        {
                    {/literal} html = html + '<a href="#" onclick="movedown('+i+');" title="Move down"><img src="{$tempdir}admin/images/small_arrow.png" title="[v]" border="0"/></a>';{literal}
        }
        else if (i==numoptions && numoptions>1)
        {
                    {/literal} html = html + '<a href="#" onclick="moveup('+i+');" title="Move up"><img src="{$tempdir}admin/images/small_arrow_up.png" title="[^]" border="0"/></a>';{literal}
        }
        html = html + '</div></div><br />';
    }
    optiondiv.innerHTML = '';
    optiondiv.innerHTML = html;
 }
 
 function addone(type)
 {
    document.getElementById('numoptions').value++;
    changeoptions(type);
 }
 
 function takeone(type)
 {
    var value = document.getElementById('numoptions').value;
    
    if (--value == 0)
    {
        document.getElementById('numoptions').value = 1;
    }
    else
    {
        document.getElementById('numoptions').value--;
    }
    changeoptions(type);
 }
 
function movedown(number)
 {
    tobemoved = document.getElementById('option'+number).value;
    temp = document.getElementById('option'+(number+1)).value;
    document.getElementById('option'+(number+1)).value = tobemoved;
    document.getElementById('option'+number).value = temp;
 }
 
 function moveup(number)
 {
    tobemoved = document.getElementById('option'+number).value;
    temp = document.getElementById('option'+(number-1)).value;
    document.getElementById('option'+(number-1)).value = tobemoved;
    document.getElementById('option'+number).value = temp;
 }
//-->
</script>
{/literal}
<script type="text/javascript">
{include file="../scripts/validator.tpl"}
</script>
<div align="center">
<form name="form" method="post" action="">
<fieldset class="formlist">
<legend>{if $action=="editfield"}Edit{else}Add{/if} Field</legend>
<div class="field">
<div class="fieldItem"><label for="name" class="label">Name of field<span class="hintanchor" title="Required :: This is the name that CMScout uses internally to refer to the field. It can only contain alphanumeric characters and the underscore, and can not contain any spaces. It can not be changed once it has been set."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper">{if $action == "newfield"}<input type="text" name="name" id="name" size="50"  class="inputbox" onblur="checkElement('name', 'custom', true, 0, 0, /^[a-zA-Z0-9_]*$/);"/><br /><span class="fieldError" id="nameError">Required: May only contain alphanumeric characters and the underscore.</span>{else}{$item.name}{/if}</div></div><br />

<div class="fieldItem"><label for="query" class="label">Query<span class="hintanchor" title="Required :: This is the question that the user will see."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label><div class="inputboxwrapper"><input type="text" name="query" id="query" size="50"  class="inputbox" {if $action=="editfield"}value="{$item.query}"{/if} onblur="checkElement('query', 'text', true, 0, 0, '');"/><br /><span class="fieldError" id="queryError">Required</span></div></div><br />

<div class="fieldItem"><label for="hint" class="label">Hint<span class="hintanchor" title="Required :: This is a explaination that will be shown to the user."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label><div class="inputboxwrapper"><input type="text" name="hint" id="hint" size="50"  class="inputbox" {if $action=="editfield"}value="{$item.hint}"{/if} onblur="checkElement('hint', 'text', true, 0, 0, '');"/><br /><span class="fieldError" id="hintError">Required</span></div></div><br />

<div class="fieldItem"><span class="label">Required<span class="hintanchor" title="Required :: Is the user required to enter a value for this field."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></span><span class="radiobox"><input type="radio" name="required" id="requiredyes" value="1" {if $item.required}checked="checked"{/if}/><label for="requiredyes">Yes</label>&nbsp;<input type="radio" name="required" id="requiredno" value="0" {if $action == "editfield"}{if !$item.required}checked="checked"{/if}{else}checked="checked"{/if}/><label for="requiredno">No</label></span></div><br />

<div class="fieldItem"><span class="label">Filled in by<span class="hintanchor" title="Required :: Who fills in this field."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></span><span class="radiobox"><input type="radio" name="register" id="registeryes" value="1" {if $item.register}checked="checked"{/if}/><label for="registeryes">Attendie</label>&nbsp;<input type="radio" name="register" id="registerno" value="0" {if $action == "editfield"}{if !$item.register}checked="checked"{/if}{else}checked="checked"{/if}/><label for="registerno">Event Master</label></span></div><br />

<div class="fieldItem"><label for="type" class="label">Type<span class="hintanchor" title="Required :: What type of field is this."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label><div class="inputboxwrapper">
  <select name="type" id="type" class="inputbox" onchange="changetype();">
      <option value="1" {if $item.type == 1}selected="selected"{/if}>Text input box</option>
      <option value="2" {if $item.type == 2}selected="selected"{/if}>Text Area</option>
      <option value="3" {if $item.type == 3}selected="selected"{/if}>Radio buttons</option>
      <option value="4" {if $item.type == 4}selected="selected"{/if}>Checkboxes</option>
      <option value="5" {if $item.type == 5}selected="selected"{/if}>Select Box</option>
      <option value="6" {if $item.type == 6}selected="selected"{/if}>Date input box</option>
     </select></div></div>
<br />

<div id="optiondiv">{if $action != "editfield"}<div class="fieldItem"><label for="options" class="label">Maximum size<span class="hintanchor" title="Required :: Maximum size the user may enter. Enter zero for no size limit."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label><div class="inputboxwrapper"><input type="text" size="10" name="options" id="options"  class="inputbox"/></div></div><br />{else}
{if $item.type == 1 || $item.type == 2}
<div class="fieldItem"><label for="options" class="label">Maximum size<span class="hintanchor" title="Required :: Maximum size the user may enter. Enter zero for no size limit."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label><div class="inputboxwrapper"><input type="text" size="10" id="options" name="options"  class="inputbox" value="{$item.options}"/></div></div><br />
{elseif $item.type==3 || $item.type == 4 || $item.type==5}
<div class="fieldItem"><label for="numoptions" class="label">Number of options</label><div class="inputboxwrapper"><input type="text" size="10" class="inputbox" name="numoptions" id="numoptions"  onchange="changeoptions({$item.type})" value="{$item.options[0]}" style="width:70%"/><a href="#" onclick="takeone({$item.type});"><img src="{$tempdir}admin/images/small_arrow_delete.png" title="[-]" border="0"/></a><a href="#" onclick="addone({$item.type});"><img src="{$tempdir}admin/images/small_arrow_add.png" title="[+]" border="0"/></a></div></div><br />
<div id="optiondiv2">
{section name=options loop=$item.options[0]+1 start=1}
<div class="fieldItem"><label for="option{$smarty.section.options.iteration}" class="label">Option {$smarty.section.options.iteration}</label><div class="inputboxwrapper"><input type="text" name="option{$smarty.section.options.iteration}" id="option{$smarty.section.options.iteration}" size="50"  class="inputbox" value="{$item.options[options]|deurl}" style="width:70%"/>{if $smarty.section.options.iteration !=1}&nbsp;<a href="#" onclick="moveup({$smarty.section.options.iteration});" title="Move up"><img src="{$tempdir}admin/images/small_arrow_up.png" title="[^]" border="0"/></a>{/if}{if $smarty.section.options.iteration < ($item.options[0])}<a href="#" onclick="movedown({$smarty.section.options.iteration});" title="Move down"><img src="{$tempdir}admin/images/small_arrow.png" title="[v]" border="0"/></a>{/if}</div></div><br />
{/section}
</div>
{/if}
{/if}
</div>
<div class="submitWrapper">
<input type="submit" name="Submit" value="Submit" class="button" />
<input name="Cancel" type="button" id="Cancel" value="Cancel" onclick="history.go(-1);" class="button" />
</div>
</fieldset>
</form>
</div>
	{elseif $action == 'edit' || $action=="new"}
    {literal}
<script type="text/javascript">
<!--
    function checkAll(type) 
{
    switch (type)
    {
        case 'group':
            {/literal}itemList = ['0'{section name=team loop=$numteams},'{$teams[team].id}'{/section}];
            number = {$numteams+1};{literal}
            break;
        {/literal}{if $numpatrols}
        case 'patrol':
            itemList = [{section name=patrols loop=$numpatrols}'{$patrols[patrols].id}'{if $smarty.section.patrols.iteration <$numpatrols},{/if}{/section}];
            number = {$numpatrols};
            break;
        {/if}{literal}
    }
    for (i=0;i<number;i++)
    {
        document.getElementById(type + itemList[i]).checked = document.getElementById(type+'_all').checked;
    }
}
//-->
</script>
{/literal}
<div align="center">
	<form method="post" name="form1" id="form1" onsubmit="return checkForm([['summary', 'text', true, 0, 0, ''],['sdate', 'date', true, 0, 0, ''],['edate', 'date', true, 0, 0, ''],['colour', 'custom', true, 0, 0, /^#[a-fA-F0-9]*$/]]);">
    <fieldset class="formlist" style="width:100%">
    <legend>{if $action == 'edit'}Edit{else}New{/if} Event</legend>
    <div class="field">
    <div class="fieldItem"><label for="summary" class="label"><b>Title</b><span class="hintanchor" title="Title for the event."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper"><input type="text" name="summary" id="summary" size="32" class="inputbox" value="{$events.summary}" onblur="checkElement('summary', 'text', true, 0, 0, '');" /><br /><span class="fieldError" id="summaryError">Required</span></div></div><br />
    
    <div class="fieldItem"><label for="sdate" class="label"><b>Starting Date</b><span class="hintanchor" title="Date that the event starts"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper"><input name="sdate" id="sdate" type="text"  size="20" maxlength="20" class="inputbox format-y-m-d highlight-days-67{if $action=="new"} range-low-today{/if} first-week-day-{math equation="x - 1" x=$config.startday}" onblur="checkElement('sdate', 'date', true, 0, 0, '');" /><br /><span class="fieldError" id="sdateError">Required: Date in the format: YYYY-MM-DD</span></div></div><br />
     
    <div class="fieldItem"><label for="stime" class="label"><b>Starting Time</b><span class="hintanchor" title="Time that the event starts"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper">{html_select_time field_array=stime class=inputbox style=width:40px}</div></div><br />
    
    <div class="fieldItem"><label for="edate" class="label"><b>Ending Date</b><span class="hintanchor" title="Date that the event ends"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper"><input name="edate" id="edate" type="text"  size="20" maxlength="20" class="inputbox format-y-m-d highlight-days-67 range-low-{$events.startdate|date_format:"%Y-%m-%d"} first-week-day-{math equation="x - 1" x=$config.startday}" onblur="checkElement('edate', 'date', true, 0, 0, '');"/><br /><span class="fieldError" id="edateError">Required: Date in the format: YYYY-MM-DD</span></div></div><br />
   
    <div class="fieldItem"><label for="etime" class="label"><b>Ending Time</b><span class="hintanchor" title="Tome that the event ends"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper">{html_select_time field_array=etime class=inputbox style=width:40px}</div></div><br />
   
   <div class="fieldItem"><label for="colour" class="label"><b>Colour</b><span class="hintanchor" title="Background colour for the event."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
   <div class="inputboxwrapper"><input id="colour" name="colour" type="text" size="13" class="inputbox" value="{$events.colour}"  style="background-color:{$events.colour};" onblur="checkElement('colour', 'custom', false, 0, 0, /^#[a-fA-F0-9]*$/);"/><img src="{$tempdir}admin/images/rainbow.png" alt="[r]" width="16" height="16" class="rain" id="colourSelector" /><br /><span class="fieldError" id="colourError">Optional: Must be a valid HTML colour code.</span></div></div><br />
              
<div class="fieldItem"><span class="label"><b>Groups</b><span class="hintanchor" title="Groups that may see this event."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></span>
<div class="inputboxwrapper"><ul class="checklist">
<li><label for="group_all"><input type="checkbox" value="1" id="group_all" onchange="checkAll('group')">Select/Unselect All</label></li>
<li><label for="group0"><input type="checkbox" value="1" name="groups[0]" id="group0" {if $events.groups.0 == 1}checked="checked"{/if}>Guests</label></li>
{section name=team loop=$numteams}
    {assign var="id" value=$teams[team].id}
    <li><label for="group{$teams[team].id}"><input type="checkbox" value="1" name="groups[{$teams[team].id}]" id="group{$teams[team].id}" {if $events.groups.$id == 1}checked="checked"{/if}>{$teams[team].teamname}</label></li>
{/section}
</ul></div></div><br />

{literal}
<script type="text/javascript">
<!--
function showSignup(show)
{
    if (show)
    {
        document.getElementById('signupDiv').style.display = "block";
    }
    else
    {
        document.getElementById('signupDiv').style.display = "none";
    }
}
function changeGroups()
{
    var index = document.getElementById('signupusers').selectedIndex;
    var type = document.getElementById('signupusers').options[index].value;
    
    if (type == 0 || type == 1)
    {
        document.getElementById('patrolList').style.display = 'block';
        document.getElementById('memberlist').style.display = 'none';        
    }
    else if (type == 2)
    {
        document.getElementById('patrolList').style.display = 'none';        
        document.getElementById('memberlist').style.display = 'none';        
    }
    else if (type == 3)
    {
        document.getElementById('memberlist').style.display = 'block';        
        document.getElementById('patrolList').style.display = 'none';        
    }
}
//-->
</script>
{/literal}

<div class="fieldItem"><span class="label">Enable signups<span class="hintanchor" title="Required :: Can members signup for this event. Once you have added this event you will be have access to more options."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></span><div class="inputboxwrapper"><input type="radio" name="signup" id="signup:yes" value="1" {if $events.signup}checked="checked"{/if} onchange="showSignup(true);"/><label for="signup:yes">Yes</label>&nbsp;<input type="radio" name="signup" id="signup:no" value="0" {if $action == "edit"}{if !$events.signup}checked="checked"{/if}{else}checked="checked"{/if} onchange="showSignup(false);"/><label for="signup:no">No</label></div></div><br />

<div id="signupDiv" {if $action == "new" || $events.signup == 0}style="display:none";{/if}>
<div class="fieldItem"><label for="signupusers" class="label"><b>Signups</b><span class="hintanchor" title="Who may signup for this event?"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper">  
<select name="signupusers" id="signupusers" class="inputbox" {if $numpatrols > 0 || $nummembers > 0}onchange="changeGroups();"{/if}>
      <option value="0" {if $events.signupusers == 0}selected="selected"{/if}>Members and parents</option>
      <option value="1" {if $events.signupusers == 1}selected="selected"{/if}>Only members</option>
      <option value="2" {if $events.signupusers == 2}selected="selected"{/if}>Only parents</option>
      <option value="3" {if $events.signupusers == 3}selected="selected"{/if}>Invite only</option>
     </select></div></div><br />
     
{if $numpatrols > 0}<div id="patrolList" class="fieldItem" {if $action == "edit" && ($events.signupusers == 2 || $events.signupusers == 3)}style="display:none";{/if}><span class="label"><b>{$scoutlang.patrol}</b><span class="hintanchor" title="{$scoutlang.members} from which {$scoutlang.patrol} may signup for this event."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></span>
<div class="inputboxwrapper"><ul class="checklist">
<li><label for="patrol_all"><input type="checkbox" value="1" id="patrol_all" onchange="checkAll('patrol')">Select/Unselect All</label></li>
{section name=patrol loop=$numpatrols}
    {assign var="id" value=$patrols[patrol].id}
    <li><label for="patrol{$patrols[patrol].id}"><input type="checkbox" value="1" name="patrols[{$patrols[patrol].id}]" id="patrol{$patrols[patrol].id}" {if $events.patrols.$id == 1 && $events.signupusers != 3}checked="checked"{/if}>{$patrols[patrol].teamname}</label></li>
{/section}
</ul></div><br /></div>
{/if}

{if $nummembers > 0}<div id="memberlist" class="fieldItem" {if $action == "new" || $events.signupusers != 3}style="display:none";{/if}><span class="label"><b>Send invites too</b><span class="hintanchor" title="Select members who you wish to invite."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></span>
<div class="inputboxwrapper"><ul class="checklist">
<li><label for="invite_all"><input type="checkbox" value="1" id="invite_all" onchange="checkAll('invite')">Select/Unselect All</label></li>
{section name=members loop=$nummembers}
    {assign var="id" value=$members[members].id}
    <li><label for="invite{$members[members].id}"><input type="checkbox" value="1" name="invites[{$members[members].id}]" id="invite{$members[members].id}" {if $events.patrols.$id == 1 && $events.signupusers == 3}checked="checked"{/if}>{$members[members].lastName}, {$members[members].firstName}</label></li>
{/section}
</ul></div><br /></div>
{/if}

</div>
        </div>
	    <h3 style="text-align:center">Details (Optional)<span class="hintanchor" title="Optional extra details and help for this event."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></h3>
        <textarea id="editor" name="editor" style="width: 100%; height: 25em;">{$events.detail}</textarea><br />
        
        <div class="submitWrapper">
        <input name="Submit" type="submit" id="Submit" value="Submit" class="button" /><input type="button" name="Submit2" value="Cancel" onClick="window.location='admin.php?page=events'" class="button" />
        </div>
    </fieldset>
	</form>   
</div>
{elseif $action == 'editical' || $action=="newical"}
<div align="center">
	<form method="post" name="form1" id="form1" onsubmit="return checkForm([['name', 'text', true, 0, 0, ''],['link', 'text', true, 0, 0, ''],['colour', 'custom', true, 0, 0, /^#[a-fA-F0-9]*$/]]);">
    <fieldset class="formlist" style="width:100%">
    <legend>{if $action == 'edit'}Edit{else}New{/if} Event</legend>
    <div class="field">
    <div class="fieldItem"><label for="name" class="label"><b>Name</b><span class="hintanchor" title="Name for the iCalendar file."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper"><input type="text" name="name" id="name" size="32" class="inputbox" value="{$events.name}" onblur="checkElement('name', 'text', true, 0, 0, '');" /><br /><span class="fieldError" id="nameError">Required</span></div></div><br />

    <div class="fieldItem"><label for="link" class="label"><b>Link</b><span class="hintanchor" title="Link to the iCalendar file."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper">http://<input type="text" name="link" id="link" size="32" class="inputbox" value="{$events.link}" onblur="checkElement('link', 'text', true, 0, 0, '');" /><br /><span class="fieldError" id="linkError">Required</span></div></div><br />

<div class="fieldItem"><label for="colour" class="label"><b>Colour</b><span class="hintanchor" title="Background colour for events in this iCalendar file."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
   <div class="inputboxwrapper"><input id="colour" name="colour" type="text" size="13" class="inputbox" value="{$events.colour}"  style="background-color:{$ical.colour};" onblur="checkElement('colour', 'custom', false, 0, 0, /^#[a-fA-F0-9]*$/);"/><img src="{$tempdir}admin/images/rainbow.png" alt="[r]" width="16" height="16" class="rain" id="colourSelector" /><br /><span class="fieldError" id="colourError">Optional: Must be a valid HTML colour code.</span></div></div><br />
                  {literal}
<script type="text/javascript">
<!--
    function checkAll(type) 
{
    switch (type)
    {
        case 'group':
            {/literal}itemList = ['0'{section name=team loop=$numteams},'{$teams[team].id}'{/section}];
            number = {$numteams+1};{literal}
            break;
    }
    for (i=0;i<number;i++)
    {
        document.getElementById(type + itemList[i]).checked = document.getElementById(type+'_all').checked;
    }
}
//-->
</script>
{/literal}
           <div class="fieldItem"><span class="label"><b>Groups</b><span class="hintanchor" title="Groups that may see events in this iCalendar file."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></span>
           <div class="inputboxwrapper"><ul class="checklist">
	   <li><label for="group_all"><input type="checkbox" value="1" id="group_all" onchange="checkAll('group')">Select/Unselect All</label></li>
           <li><label for="group0"><input type="checkbox" value="1" name="groups[0]" id="group0" {if $events.groups.0 == 1}checked="checked"{/if}>Guests</label></li>
            {section name=team loop=$numteams}
                {assign var="id" value=$teams[team].id}
                <li><label for="group{$teams[team].id}"><input type="checkbox" value="1" name="groups[{$teams[team].id}]" id="group{$teams[team].id}" {if $events.groups.$id == 1}checked="checked"{/if}>{$teams[team].teamname}</label></li>
            {/section}
            </ul></div></div><br />
        </div>
        
        <div class="submitWrapper">
        <input name="Submit" type="submit" id="Submit" value="Submit" class="button" /><input type="button" name="Submit2" value="Cancel" onClick="window.location='admin.php?page=events'" class="button" />
        </div>
    </fieldset>
	</form>   
</div>
    {/if}
