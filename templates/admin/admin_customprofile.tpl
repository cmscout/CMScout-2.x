<h2>Custom Profile Manager</h2>
{if ($action!="edit" || $editallowed == 0) && ($action!="new" || $addallowed == 0)}
  {if $addallowed}<div class="toplinks"><a href="{$pagename}&amp;action=new" title="Add custom field"><img src="{$tempdir}admin/images/add.png" alt="Add custom field" border="0" /></a>
</div>{/if}
{if $numfields > 0}
{literal}
  <script type="text/javascript">
<!--
function confirmDelete(articleId) {
if (confirm("This will delete this field. Continue?"))
document.location = "admin.php?page=customprofile&action=delete&id=" + articleId;
}
//-->
  </script>
  {/literal}
<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-15" id="sortTable">
<thead>
  <tr valign="top"> 
    <th width="8%" class="smallhead"></th>
    <th class="smallhead" >Name</th>
    <th class="smallhead" width="20%">Type</th>
    <th class="smallhead" width="10%">Position</th>
  </tr>
  </thead>
<tbody>
 {section name=fields loop=$numfields}
	  <tr class="text" valign="top"> 
		<td class="text" style="text-align:center;">{if $editallowed}<a href="{$pagename}&amp;id={$field[fields].id}&amp;action=edit"><img src="{$tempdir}admin/images/edit.gif" border="0" alt="Edit Authorizations for {$field[fields].name}" title="Edit Authorizations for {$field[fields].name}" /></a>{else}<img src="{$tempdir}admin/images/edit_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;&nbsp;{if $deleteallowed}<a href="javascript:confirmDelete({$field[fields].id})"><img src="{$tempdir}admin/images/delete.gif" border="0" alt="Remove Authorizations for {$field[fields].name}" title="Remove Authorizations for {$field[fields].name}" /></a>{else}<img src="{$tempdir}admin/images/delete_grey.gif" border="0" alt="Deleting Disabled" title="Deleting Disabled" />{/if}</td>
		<td class="text">
        <span class="hintanchor" title="Information :: <b>Name:</b> {$field[fields].name}<br /><b>Required:</b> {if $field[fields].required}Yes{else}No{/if}<br /><b>Register Field:</b> {if $field[fields].register}Yes{else}No{/if}<br /><b>Public Field:</b> {if $field[fields].profileview}Yes{else}No{/if}"><img src="{$tempdir}admin/images/information.png" alt="[i]"/></span>{$field[fields].query}</td>
        <td class="text">{if $field[fields].type == 1}Text Input{elseif $field[fields].type == 2}Text Area{elseif $field[fields].type == 3}Radio buttons{elseif $field[fields].type == 4}Checkboxes{elseif $field[fields].type == 5}Select Box{elseif $field[fields].type == 6}Date Input{/if}</td>
        <td class="text" style="text-align:center;">{if $smarty.section.fields.iteration != 1}{if $editallowed}<a href="{$pagename}&amp;action=moveup&amp;id={$field[fields].id}"><img src="{$tempdir}admin/images/up.gif" border="0" alt="Up" title="Move Up" /></a>{else}<img src="{$tempdir}admin/images/up_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}{/if}{if $smarty.section.fields.iteration != $numfields}{if $editallowed}<a href="{$pagename}&amp;action=movedown&amp;id={$field[fields].id}"><img src="{$tempdir}admin/images/down.gif" border="0" alt="Up" title="Move Down" /></a>{else}<img src="{$tempdir}admin/images/down_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}{/if}</td>
      </tr>  
    {/section}
    </tbody>
</table>
{else}
<div align="center">No custom profile fields</div>
{/if}
{elseif ($action == "new" && $addallowed == 1) || ($action == "edit" && $editallowed == 1)}
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
                optiondiv.innerHTML = '<div class="fieldItem"><label for="numoptions" class="label">Number of options</label><div class="inputboxwrapper"><input type="text" size="10" class="inputbox" name="numoptions" id="numoptions"  onchange="changeoptions(' + type + ')" value="1" style="width:70%"/><a href="#" onclick="takeone(' + type + ');"><img src="{$tempdir}admin/images/small_arrow_delete.png" title="[-]" border="0"/></a><a href="#" onclick="addone(' + type + ');"><img src="{$tempdir}admin/images/small_arrow_add.png" title="[+]" border="0"/></a></div></div><br /><div id="optiondiv2"><div class="fieldItem"><label for="option1" class="label">Option 1</label><div class="inputboxwrapper"><input type="text" name="option1" id="option1" size="50"  class="inputbox" style="width:70%"/></div></div>';
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
<form name="forms" id="forms" method="post" action="">
<fieldset class="formlist">
<legend>{if $action=="edit"}Edit{else}Add{/if} Field</legend>
<div class="field">
<div class="fieldItem"><label for="name" class="label">Name of field<span class="hintanchor" title="Required :: This is the name that CMScout uses internally to refer to the field. It can only contain alphanumeric characters and the underscore, and can not contain any spaces. It can not be changed once it has been set."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper">{if $action == "new"}<input type="text" name="name" id="name" size="50"  class="inputbox" {if $action=="edit"}value="{$item.name}"{/if} onblur="checkElement('name', 'custom', true, 0, 0, /^[a-zA-Z0-9_]*$/);"/><br /><span class="fieldError" id="nameError">Required: May only contain alphanumeric characters and the underscore.</span>{else}{$item.name}{/if}</div></div><br />

<div class="fieldItem"><label for="query" class="label">Query<span class="hintanchor" title="Required :: This is the question that the user will see."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label><div class="inputboxwrapper"><input type="text" name="query" id="query" size="50"  class="inputbox" {if $action=="edit"}value="{$item.query}"{/if} onblur="checkElement('query', 'text', true, 0, 0, '');"/><br /><span class="fieldError" id="queryError">Required</span></div></div><br />

<div class="fieldItem"><label for="hint" class="label">Hint<span class="hintanchor" title="Required :: This is a explaination that will be shown to the user."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label><div class="inputboxwrapper"><input type="text" name="hint" id="hint" size="50"  class="inputbox" {if $action=="edit"}value="{$item.hint}"{/if} onblur="checkElement('hint', 'text', true, 0, 0, '');"/><br /><span class="fieldError" id="hintError">Required</span></div></div><br />

<div class="fieldItem"><span class="label">Required<span class="hintanchor" title="Required :: Is the user required to enter a value for this field."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></span><div class="inputboxwrapper"><input type="radio" name="required" id="requiredyes" value="1" {if $item.required}checked="checked"{/if}/><label for="requiredyes">Yes</label>&nbsp;<input type="radio" name="required" id="requiredno" value="0" {if $action == "edit"}{if !$item.required}checked="checked"{/if}{else}checked="checked"{/if}/><label for="requiredno">No</label></div></div><br />

<div class="fieldItem"><span class="label">Registration Field<span class="hintanchor" title="Required :: Should this field be shown on the registration screen."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></span><div class="inputboxwrapper"><input type="radio" name="register" id="registeryes" value="1" {if $item.register}checked="checked"{/if}/><label for="registeryes">Yes</label>&nbsp;<input type="radio" name="register" id="registerno" value="0" {if $action == "edit"}{if !$item.register}checked="checked"{/if}{else}checked="checked"{/if}/><label for="registerno">No</label></div></div><br />

<div class="fieldItem"><span class="label">Public Field<span class="hintanchor" title="Required :: Should the value of this field be shown on the users public profile."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></span><div class="inputboxwrapper"><input type="radio" name="profileview" id="profileviewyes" value="1" {if $item.profileview}checked="checked"{/if}/><label for="profileviewyes">Yes</label>&nbsp;<input type="radio" name="profileview" id="profileviewno" value="0" {if $action == "edit"}{if !$item.profileview}checked="checked"{/if}{else}checked="checked"{/if}/><label for="profileviewno">No</label></div></div><br />

<div class="fieldItem"><label for="type" class="label">Type<span class="hintanchor" title="Required :: What type of field is this."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label><div class="inputboxwrapper">
  <select name="type" id="type" class="inputbox" onchange="changetype();">
      <option value="1" {if $item.type == 1}selected="selected"{/if}>Text input box</option>
      <option value="2" {if $item.type == 2}selected="selected"{/if}>Text Area</option>
      <option value="3" {if $item.type == 3}selected="selected"{/if}>Radio buttons</option>
      <option value="4" {if $item.type == 4}selected="selected"{/if}>Checkboxes</option>
      <option value="6" {if $item.type == 6}selected="selected"{/if}>Date input box</option>
     </select></div></div>
<br />

<div id="optiondiv">{if $action != "edit"}<div class="fieldItem"><label for="options" class="label">Maximum size<span class="hintanchor" title="Required :: Maximum size the user may enter. Enter zero for no size limit."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label><div class="inputboxwrapper"><input type="text" size="10" name="options" id="options"  class="inputbox"/></div></div><br />{else}
{if $item.type == 1 || $item.type == 2}
<div class="fieldItem"><label for="options" class="label">Maximum size<span class="hintanchor" title="Required :: Maximum size the user may enter. Enter zero for no size limit."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label><div class="inputboxwrapper"><input type="text" size="10" id="options" name="options"  class="inputbox" value="{$item.options}"/></div></div><br />
{elseif $item.type==3 || $item.type == 4 || $item.type==5}
<div class="fieldItem"><label for="numoptions" class="label">Number of options</label><div class="inputboxwrapper"><input type="text" size="10" class="inputbox" name="numoptions" id="numoptions"  onchange="changeoptions({$item.type})" value="{$item.options[0]}" style="width:70%"/><a href="#" onclick="takeone({$item.type});"><img src="{$tempdir}admin/images/small_arrow_delete.png" title="[-]" border="0"/></a><a href="#" onclick="addone({$item.type});"><img src="{$tempdir}admin/images/small_arrow_add.png" title="[+]" border="0"/></a></div></div><br />
<div id="optiondiv2">
{section name=options loop=$item.options[0]+1 start=1}
<div class="fieldItem"><label for="option{$smarty.section.options.iteration}" class="label">Option {$smarty.section.options.iteration}</label><div class="inputboxwrapper"><input type="text" name="option{$smarty.section.options.iteration}" id="option{$smarty.section.options.iteration}" size="50"  class="inputbox" value="{$item.options[options]}" style="width:70%"/>{if $smarty.section.options.iteration !=1}<a href="#" onclick="moveup({$smarty.section.options.iteration});" title="Move up"><img src="{$tempdir}admin/images/small_arrow_up.png" title="[^]" border="0"/></a>{/if}{if $smarty.section.options.iteration < ($item.options[0])}<a href="#" onclick="movedown({$smarty.section.options.iteration});" title="Move down"><img src="{$tempdir}admin/images/small_arrow.png" title="[v]" border="0"/></a>{/if}</div></div><br />
{/section}
</div>
{/if}
{/if}
</div>
</div>
<div class="submitWrapper">
<input type="submit" name="Submit" value="Submit" class="button" />
<input name="Cancel" type="button" id="Cancel" value="Cancel" onclick="history.go(-1);" class="button" />
</div>
</form>
</div>
{/if}