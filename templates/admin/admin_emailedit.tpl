<h2>Emails</h2>
{if $action!="edit"}
{if $numemails > 0}
<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="table sortable-onload-2 rowstyle-alt paginate-15" id="sortTable">
<thead>
  <tr valign="top"> 
    <th width="5%" class="smallhead"></th>
    <th class="smallhead sortable">Name</th>
    <th width="33%" class="smallhead sortable">Subject</th>
  </tr>
  </thead>
  <tbody>
 {section name=contentloop loop=$numemails}
	  <tr valign="middle" class="text"> 
		<td class="text"><div align="center">{if $editallowed}<a href="{$pagename}&amp;id={$emails[contentloop].id}&amp;action=edit" title="Edit {$emails[contentloop].name}"><img src="{$tempdir}admin/images/edit.gif" border="0" alt="Edit {$emails[contentloop].name}"  /></a>{else}<img src="{$tempdir}admin/images/edit_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}</div></td>
		<td class="text">{$emails[contentloop].name}</td>
        <td class="text">{$emails[contentloop].subject}</td>
	  </tr>  
	{/section}
    </tbody>
</table>
{else}
<div align="center">No Emails</div>
{/if}
{else}
{literal}
<script type="text/javascript">
<!--
function add(addWhat) 
{
    document.getElementById('email').value = document.getElementById('email').value + addWhat;
}
//-->
</script>
{/literal}
<div align="center">
<form name="Content" method="post" action="">
<fieldset class="formlist">
<legend>Edit {$email.name}</legend>
<div class="field">
<label for="subject" class="label">Subject<span class="hintanchor" title="Subject for the email."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><input name="subject" type="text" id="subject" class="inputbox" value="{$email.subject}" onblur="checkElement('subject', 'text', true, 0, 0, '');" /><br /><span class="fieldError" id="subjectError">Required</span></div><br />
<label for="subject" class="label">Subject<span class="hintanchor" title="The body of the email, you may not use any HTML, but you can use the supplied CMScout Tags."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><textarea id="email" name="email" rows="30" cols="50" class="inputbox" onblur="checkElement('email', 'text', true, 0, 0, '');">{$email.email}</textarea></div>
<br /><span class="fieldError" id="emailError">Required</span></div><br />
<div style="width:100%"><div style="font-weight:bold;text-align:center;font-size:big;">CMScout Tags</div>
<div class="field" style="border:1px dashed #000">
<span class="label">Username</span><div class="inputboxwrapper"><a href="javascript:add('!#uname#!');">!#uname#!</a></div><br />
<span class="label">Posting User</span><div class="inputboxwrapper"><a href="javascript:add('!#postuname#!');">!#postuname#!</a></div><br />
<span class="label">Item Title</span><div class="inputboxwrapper"><a href="javascript:add('!#title#!');">!#title#!</a></div><br />
<span class="label">Item Type</span><div class="inputboxwrapper"><a href="javascript:add('!#type#!');">!#type#!</a></div><br />
<span class="label">Item Link</span><div class="inputboxwrapper"><a href="javascript:add('!#link#!');">!#link#!</a></div><br />
<span class="label">Item Extract</span><div class="inputboxwrapper"><a href="javascript:add('!#extract#!');">!#extract#!</a></div><br />
<span class="label">Website Name</span><div class="inputboxwrapper"><a href="javascript:add('!#website#!');">!#website#!</a></div><br />
</div>

        
</div>
<div class="submitWrapper">
		  <input type="submit" name="Submit" value="Update" class="button" />
        <input name="Cancel" type="button" id="Cancel" value="Cancel" onclick="window.location = '{$pagename}'" class="button" />
        </div>
</fieldset>
</form>
</div>
{/if}
