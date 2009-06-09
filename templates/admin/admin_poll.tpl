<h2>Poll Manager</h2>
{if ($action!="edit" || $editallowed == 0) && ($action!="new" || $addallowed == 0)}
{if $addallowed}<div class="toplinks"><a href="{$pagename}&amp;action=new" title="Add poll"><img src="{$tempdir}admin/images/add.png" alt="Add poll" border="0" /></a>
</div>{/if}
{if $numpolls > 0}
{literal}
  <script type="text/javascript">
<!--
function confirmDelete(articleId) {
if (confirm("This will delete this poll. Continue?"))
document.location = "admin.php?page=poll&action=delete&id=" + articleId;
}

function confirmPublish(articleId)
{
    if (confirm("This will publish the poll. Continue?")){/literal}
    document.location = "{$pagename}&action=publish&id=" + articleId;{literal}
}

function confirmunPublish(articleId)
{
    if (confirm("This will unpublish the poll. Continue?")){/literal}
    document.location = "{$pagename}&action=unpublish&id=" + articleId;{literal}
}

//-->
  </script>
  {/literal}

<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="table sortable-onload-4 rowstyle-alt paginate-15" id="sortTable">
<thead>
  <tr valign="top"> 
    <th width="10%" class="smallhead"></th>
    <th  width="5%"class="smallhead" >Publish</th>
    <th class="smallhead sortable">Question</th>
    <th width="10%" class="smallhead sortable-date">Date End</th>
  </tr>
  </thead>
  <tbody>
 {section name=fields loop=$numpolls}
	  <tr valign="top" class="text"> 
		<td class="text" style="text-align:center;">{if $editallowed}<a href="{$pagename}&amp;id={$pollitems[fields].id}&amp;action=edit"><img src="{$tempdir}admin/images/edit.gif" border="0" alt="Edit" title="Edit" /></a>{else}<img src="{$tempdir}admin/images/edit_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;&nbsp;{if $editallowed}<a href="{$pagename}&amp;id={$pollitems[fields].id}&amp;action=sidebox"><img {if $pollitems[fields].sidebox}src="{$tempdir}admin/images/onfrontpage.gif"{else}src="{$tempdir}admin/images/frontpage_grey.gif"{/if} border="0" alt="Post to sidebox" title="Post to sidebox" /></a>{else}<img src="{$tempdir}admin/images/frontpage_grey.gif" border="0" alt="Not allowed to post to sidebox" title="Not allowed to post to sidebox" />{/if}&nbsp;&nbsp;{if $deleteallowed}<a href="javascript:confirmDelete({$pollitems[fields].id})"><img src="{$tempdir}admin/images/delete.gif" border="0" alt="Remove" title="Remove" /></a>{else}<img src="{$tempdir}admin/images/delete_grey.gif" border="0" alt="Deleting Disabled" title="Deleting Disabled" />{/if}</td>
  <td class="text" style="text-align:center;">{if $publishallowed}{if $pollitems[fields].allowed == 0}<a href="javascript:confirmPublish({$pollitems[fields].id})"><img src="{$tempdir}admin/images/publish.png" border="0" alt="Publish {$pollitems[fields].title}" title="Publish {$pollitems[fields].title}" /></a>{else}<a href="javascript:confirmunPublish({$pollitems[fields].id})"><img src="{$tempdir}admin/images/unpublish.png" border="0" alt="Unpublish {$pollitems[fields].title}" title="Unpublish {$pollitems[fields].title}" /></a>{/if}{else}{if $pollitems[fields].allowed == 0}<img src="{$tempdir}admin/images/publish_grey.gif" border="0" alt="Not allowed to publish" title="Not allowed to publish" />{else}<img src="{$tempdir}admin/images/unpublish_grey.gif" border="0" alt="Not allowed to unpublish" title="Not allowed to unpublis" />{/if}{/if}</td>
		<td class="text">{$pollitems[fields].question}</td>
        <td class="text">{if $pollitems[fields].date_stop == 0}Never Ends{else}{$pollitems[fields].date_stop|date_format:"%Y-%m-%d"}{/if}</td>
      </tr>  
    {/section}
    </tbody>
</table>
{else}
<div align="center">No polls</div>
{/if}
{elseif ($action == "new" && $addallowed == 1) || ($action == "edit" && $editallowed == 1)}
{literal}
  <script type="text/javascript">
<!-- 
 function changeoptions(type)
 {
    var numoptions = document.getElementById('numoptions').value;
    var optiondiv = document.getElementById('optiondiv');
    var temp = '';
    var html = '';
    for(var i=1;i<=numoptions;i++)
    {
        temp = ''; 
        if (document.getElementById('option' + i)) 
        {
            temp = document.getElementById('option' + i).value; 
        }
        html = html + '<label for="option' + i + '" class="label">Option ' + i + '</label><div class="inputboxwrapper"><input type="text" name="option[]" id="option' + i + '" size="50"  class="inputbox" value="' + temp + '"/>';
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
        html = html + '</div><br />';
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
<div align="center">
<form name="forms" id="forms" method="post" action="" onsubmit="return checkForm([['question','text',true,0,0,''],['date_stop','date',false,0,0,'']]);">
<div class="field">
<fieldset class="formlist">
<legend>{if $action == "new"}Add{else}Edit{/if} Poll</legend>
<label for="question" class="label">Question<span class="hintanchor" title="The poll question."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><input type="text" name="question" id="question"  class="inputbox" {if $action=="edit"}value="{$item.question}"{/if} onblur="checkElement('question', 'text', true, 0, 0, '');"/><br /><span class="fieldError" id="questionError">Required</span></div><br />

<label for="date_stop" class="label">Date End<span class="hintanchor" title="Date that the poll must end. Leave blank to run the poll forever."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><input type="text" name="date_stop" id="date_stop"  class="inputbox format-y-m-d highlight-days-67 range-low-today" {if $action=="edit" && $item.date_stop != 0}value="{$item.date_stop|date_format:"%Y-%m-%d"}"{/if} onblur="checkElement('date_stop', 'date', false, 0, 0, '');"/><br /><span class="fieldError" id="date_stopError">Must a valid date in the format: YYYY-MM-DD</span></div><br />

<label for="numoptions" class="label">Number of options<span class="hintanchor" title="Number of options in the poll."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><input type="text" size="10" class="inputbox" name="numoptions" id="numoptions"  onchange="changeoptions({$item.type})" value="{if $numoptions}{$numoptions}{else}1{/if}" /><a href="#" onclick="takeone({$item.type});"><img src="{$tempdir}admin/images/small_arrow_delete.png" title="[-]" border="0"/></a><a href="#" onclick="addone({$item.type});"><img src="{$tempdir}admin/images/small_arrow_add.png" title="[+]" border="0"/></a></div><br />
<div id="optiondiv">
{section name=options loop=$numoptions}
<label for="option{$smarty.section.options.iteration}" class="label">Option {$smarty.section.options.iteration}</label>
<div class="inputboxwrapper"><input type="text" name="option[]" id="option{$smarty.section.options.iteration}" size="50"  class="inputbox" value="{$item.options[options]}"/>{if $smarty.section.options.iteration !=1}<a href="#" onclick="moveup({$smarty.section.options.iteration});" title="Move up"><img src="{$tempdir}admin/images/small_arrow_up.png" title="[^]" border="0"/></a>{/if}{if $smarty.section.options.iteration < ($numoptions)}<a href="#" onclick="movedown({$smarty.section.options.iteration});" title="Move down"><img src="{$tempdir}admin/images/small_arrow.png" title="[v]" border="0"/></a>{/if}</div><br />
{sectionelse}
<label for="option1" class="label">Option 1</label>
<div class="inputboxwrapper"><input type="text" name="option[]" id="option1" size="50"  class="inputbox" value=""/></div><br />
{/section}
</div>
<div class="submitWrapper">
<input type="submit" name="Submit" value="Submit" class="button" />
<input name="Cancel" type="button" id="Cancel" value="Cancel" onclick="window.location='{$pagename}'" class="button" />
</div>
</fieldset>
</div>
</form>
</div>
{/if}