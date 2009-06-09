{if $action==""}
{literal}
<script type="text/javascript">
<!--
function confirmDelete(articleId) {
if (confirm("This will delete this member. Continue?"))
document.location = "admin.php?page=troop&action=delete&id=" + articleId;
}
//-->
</script>
{/literal}
<h2>{$scoutlang.members}</h2>
{if $addallowed}<div class="toplinks"><a href="{$pagename}&amp;action=new" title="New {$scoutlang.member}"><img src="{$tempdir}admin/images/add.png" alt="New {$scoutlang.member}" border="0" /></a>
</div>{/if}
{if $nummembers > 0}
<div style="text-align:center">Parent in <span style="font-weight:bold;color:#ff0000;">red</span> is the primary guardian.</div>
<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="table sortable-onload-2 rowstyle-alt paginate-15" id="sortTable">
<thead>
<tr> 
  <th width="120px" class="smallhead"></th>
  <th class="smallhead sortable">Last Name</th>
  <th width="12%" class="smallhead sortable">First Name</th>
  <th width="10%" class="smallhead sortable">Username</th>
  <th width="15%" class="smallhead sortable">Relations</th>
  <th width="10%" class="smallhead sortable">Home</th>
  <th width="10%" class="smallhead sortable">Cellphone</th>
</tr>
</thead>
<tbody>
{section name=users loop=$nummembers}
<tr class="text">
  <td class="text"><div align="center">{if $editallowed && !$limitgroup}<a href="admin.php?page=troop&amp;action=edit&amp;id={$members[users].id}"><img src="{$tempdir}admin/images/edit.gif" alt="Edit {$members[users].firstName}'s details" title="Edit {$members[users].firstName}'s details" border="0" /></a>{else}<img src="{$tempdir}admin/images/edit_grey.gif" alt="Edit disabled" title="Edit disabled" border="0" />{/if}&nbsp;&nbsp;<a href="admin.php?page=troop&amp;action=view&amp;id={$members[users].id}" ><img src="{$tempdir}admin/images/page.gif" alt="View {$members[users].firstName}'s Details" title="View {$members[users].firstName}'s Details" border="0"/></a>&nbsp;&nbsp;{if $members[users].type == 0 && $members[users].awardScheme != 0}<a href="admin.php?page=troop&subpage=records&amp;id={$members[users].id}"><img src="{$tempdir}admin/images/record.png" alt="{$members[users].firstName}'s Record" title="{$members[users].firstName}'s Record" border="0" /></a>{else}<img src="{$tempdir}admin/images/record_grey.png" alt="Not a {$scoutlang.member}" title="Not a {$scoutlang.member}" border="0" />{/if}&nbsp;&nbsp;{if $deleteallowed}<a href="javascript:confirmDelete('{$members[users].id}')"><img src="{$tempdir}admin/images/delete.gif"  border="0" alt="Delete {$members[users].firstName}" title="Delete {$members[users].firstName}" /></a>{else}<img src="{$tempdir}admin/images/delete_grey.gif"  border="0" alt="Delete disabled" title="Delete disabled" />{/if}</a>
</div></td>
  <td class="text">{$members[users].lastName}</td>
  <td class="text">{$members[users].firstName}</td>
  <td class="text">{$members[users].uname}</td>
  <td class="text">{$members[users].relations}</td>
  <td class="text">{$members[users].home}</td>
  <td class="text">{$members[users].cell}</td>
</tr>
{/section}
</tbody>
</table>
{else}
<div align="center">No {$scoutlang.members}</div>
{/if}
{if $addallowed}<a href="{$pagename}&amp;action=import">Import CSV file</a>{/if}
{elseif $action == "import"}
<h2>Import from CSV</h2>
 {if $step == 1}
 <div align="center">
 <form action="" method="post" enctype="multipart/form-data" name="form1">
 <p>The CSV (Comma Seperated Values) file must have fields that are seperated by commas, fields should be deliminated with "" (double quotation marks).</p>
 <p>The minimum information required is:</p>
 <ul>
 <li>First Name</li>
 <li>Last Name</li>
 </ul>
 <p>You can added parent information at the same time. Parent information must be in the same row as the member information.</p>
  <fieldset class="formlist">
  <legend>Upload CSV file to import</legend>
  <div class="field">
  <label for="file" class="label">File<span class="hintanchor" title="Select a csv file on your computer that you wish to import."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper"><input name="file" id="file" type="file" size="50" class="inputbox" /></div><br />
  </div>
        <div class="submitWrapper">
    <input type="submit" name="Submit" value="Submit" class="button" />
		<input name="Cancel" type="button" id="Cancel" value="Cancel" onclick="history.go(-1);" class="button" />
   </div>
  </fieldset>
  </form>
  </div>
  {elseif $step == 2}
 <div align="center">
 <form action="{$pagename}&amp;action=import&amp;step=2" method="post" name="form1">
   <div class="field">
  <fieldset>
  <legend>Select which fields to import</legend>
      {literal}
<script type="text/javascript">
function memberSelect()
{
    var index = document.getElementById('type').selectedIndex;
    var type = document.getElementById('type').options[index].value;
    
    if (type == 0)
    {
        document.getElementById('memberDiv').style.display = 'block';
        document.getElementById('scoutinginfo').style.display = 'block';
    }
    else
    {
        document.getElementById('memberDiv').style.display = 'none';        
        document.getElementById('scoutinginfo').style.display = 'none';
    }
}
</script>
{/literal}
<div class="fieldItem"><label for="type" class="label">Record Type<span class="hintanchor" title="Type of records you are importing"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><select id="type" name="type" class="inputbox" onchange="memberSelect();">
<option value="0" {if $member.type == 0}selected="selected"{/if}>{$scoutlang.member}</option>
<option value="1" {if $member.type == 1}selected="selected"{/if}>Father</option>
<option value="2" {if $member.type == 2}selected="selected"{/if}>Mother</option>
</select></div></div><br />

    <div class="fieldItem"><label for="firstname" class="label">First Name<span class="hintanchor" title="Required"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper">    <select class="inputbox" name="firstname" id="firstname"><option value="-1">None</option><option value="-1">None</option>
    {section name=header loop=$headers}
  <option value="{$smarty.section.header.index}">{$headers[header]}</option>
  {/section}
    </select></div></div><br />

<div class="fieldItem"><label for="middlename" class="label">Middle Name<span class="hintanchor" title="Optional"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper">    <select class="inputbox" name="middlename" id="middlename"><option value="-1">None</option>
    {section name=header loop=$headers}
  <option value="{$smarty.section.header.index}">{$headers[header]}</option>
  {/section}
    </select></div></div><br />

<div class="fieldItem"><label for="lastname" class="label">Last Name<span class="hintanchor" title="Required"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper">    <select class="inputbox" name="lastname" id="lastname"><option value="-1">None</option>
    {section name=header loop=$headers}
  <option value="{$smarty.section.header.index}">{$headers[header]}</option>
  {/section}
    </select></div></div><br />

<div class="fieldItem"><label for="sex" class="label">Gender<span class="hintanchor" title="Required"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper">    <select class="inputbox" name="sex" id="sex"><option value="-1">None</option>
    {section name=header loop=$headers}
  <option value="{$smarty.section.header.index}">{$headers[header]}</option>
  {/section}
    </select></div></div><br />

<div class="fieldItem"><label for="address" class="label">Address<span class="hintanchor" title="Optional"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper">    <select class="inputbox" name="address" id="address"><option value="-1">None</option>
    {section name=header loop=$headers}
  <option value="{$smarty.section.header.index}">{$headers[header]}</option>
  {/section}
    </select><select class="inputbox" name="address1" id="address1"><option value="-1">None</option>
    {section name=header loop=$headers}
  <option value="{$smarty.section.header.index}">{$headers[header]}</option>
  {/section}
    </select><select class="inputbox" name="address2" id="address2"><option value="-1">None</option>
    {section name=header loop=$headers}
  <option value="{$smarty.section.header.index}">{$headers[header]}</option>
  {/section}
    </select><select class="inputbox" name="address3" id="address3"><option value="-1">None</option>
    {section name=header loop=$headers}
  <option value="{$smarty.section.header.index}">{$headers[header]}</option>
  {/section}
    </select></div></div><br />

<div class="fieldItem"><label for="homenumber" class="label">Home Number<span class="hintanchor" title="Optional"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper">    <select class="inputbox" name="homenumber" id="homenumber"><option value="-1">None</option>
    {section name=header loop=$headers}
  <option value="{$smarty.section.header.index}">{$headers[header]}</option>
  {/section}
    </select></div></div><br />

<div class="fieldItem"><label for="cellnumber" class="label">Cell Number<span class="hintanchor" title="Optional"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper">    <select class="inputbox" name="cellnumber" id="cellnumber"><option value="-1">None</option>
    {section name=header loop=$headers}
  <option value="{$smarty.section.header.index}">{$headers[header]}</option>
  {/section}
    </select></div></div><br />

<div class="fieldItem"><label for="worknumber" class="label">Work Number<span class="hintanchor" title="Optional"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper">    <select class="inputbox" name="worknumber" id="worknumber"><option value="-1">None</option>
    {section name=header loop=$headers}
  <option value="{$smarty.section.header.index}">{$headers[header]}</option>
  {/section}
    </select></div></div><br />

<div class="fieldItem"><label for="email" class="label">Email Address<span class="hintanchor" title="Optional"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper">    <select class="inputbox" name="email" id="email"><option value="-1">None</option>
    {section name=header loop=$headers}
  <option value="{$smarty.section.header.index}">{$headers[header]}</option>
  {/section}
    </select></div></div><br />

<div class="fieldItem"><label for="dob" class="label">Date of Birth<span class="hintanchor" title="Optional"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper">    <select class="inputbox" name="dob" id="dob"><option value="-1">None</option>
    {section name=header loop=$headers}
  <option value="{$smarty.section.header.index}">{$headers[header]}</option>
  {/section}
    </select></div></div><br />

<div class="fieldItem"><label for="medicalname" class="label">Medical Aid Name<span class="hintanchor" title="Optional"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper">    <select class="inputbox" name="medicalname" id="medicalname"><option value="-1">None</option>
    {section name=header loop=$headers}
  <option value="{$smarty.section.header.index}">{$headers[header]}</option>
  {/section}
    </select></div></div><br />

<div class="fieldItem"><label for="medicalnumber" class="label">Medical Aid Number<span class="hintanchor" title="Optional"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper">    <select class="inputbox" name="medicalnumber" id="medicalnumber"><option value="-1">None</option>
    {section name=header loop=$headers}
  <option value="{$smarty.section.header.index}">{$headers[header]}</option>
  {/section}
    </select></div></div><br />

<div class="fieldItem"><label for="docname" class="label">Doctor's Name<span class="hintanchor" title="Optional"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper">    <select class="inputbox" name="docname" id="docname"><option value="-1">None</option>
    {section name=header loop=$headers}
  <option value="{$smarty.section.header.index}">{$headers[header]}</option>
  {/section}
    </select></div></div><br />

<div class="fieldItem"><label for="docnum" class="label">Doctor Tel Number<span class="hintanchor" title="Optional"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper">    <select class="inputbox" name="docnum" id="docnum"><option value="-1">None</option>
    {section name=header loop=$headers}
  <option value="{$smarty.section.header.index}">{$headers[header]}</option>
  {/section}
    </select></div></div><br />

<div class="fieldItem"><label for="medical" class="label">Medical Conditions<span class="hintanchor" title="Optional"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper">    <select  class="inputbox"name="medical" id="medical"><option value="-1">None</option>
    {section name=header loop=$headers}
  <option value="{$smarty.section.header.index}">{$headers[header]}</option>
  {/section}
    </select></div></div><br />
    </fieldset>
<div id="memberDiv" {if $member.type != 0}style="display:none;"{else}style="display:block;"{/if}>    
    <fieldset>
    <legend>Parent Information</legend>
    
<div class="fieldItem"><label for="primaryGuard" class="label">Primary Guardian<span class="hintanchor" title="Optional :: Only enter information for minors."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><select id="primaryGuard" name="primaryGuard" class="inputbox" >
<option value="0" {if $member.primaryGuard == 0}selected="selected"{/if}>Father</option>
<option value="1" {if $member.primaryGuard == 1}selected="selected"{/if}>Mother</option>
</select></div></div><br />

<fieldset class="formlist">
<legend>Father</legend>
<div class="fieldItem"><label for="fatherFirst" class="label">First Name<span class="hintanchor" title="Optional :: Only enter information for minors."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><select  class="inputbox"name="fatherFirst" id="fatherFirst"><option value="-1">None</option>
    {section name=header loop=$headers}
  <option value="{$smarty.section.header.index}">{$headers[header]}</option>
  {/section}
    </select></div></div><br />
<div class="fieldItem"><label for="fatherLast" class="label">Last Name<span class="hintanchor" title="Optional :: Only enter information for minors."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><select  class="inputbox"name="fatherLast" id="fatherLast"><option value="-1">None</option>
    {section name=header loop=$headers}
  <option value="{$smarty.section.header.index}">{$headers[header]}</option>
  {/section}
    </select></div></div><br />
<div class="fieldItem"><label for="fatherAddress" class="label">Address<span class="hintanchor" title="Optional :: Only enter information for minors."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><select  class="inputbox" name="fatherAddress" id="fatherAddress"><option value="-1">None</option>
    {section name=header loop=$headers}
  <option value="{$smarty.section.header.index}">{$headers[header]}</option>
  {/section}
    </select><select  class="inputbox" name="fatherAddress2" id="fatherAddress2"><option value="-1">None</option>
    {section name=header loop=$headers}
  <option value="{$smarty.section.header.index}">{$headers[header]}</option>
  {/section}
    </select><select  class="inputbox" name="fatherAddress3" id="fatherAddress3"><option value="-1">None</option>
    {section name=header loop=$headers}
  <option value="{$smarty.section.header.index}">{$headers[header]}</option>
  {/section}
    </select><select  class="inputbox" name="fatherAddress4" id="fatherAddress4"><option value="-1">None</option>
    {section name=header loop=$headers}
  <option value="{$smarty.section.header.index}">{$headers[header]}</option>
  {/section}
    </select></div></div><br />
<div class="fieldItem"><label for="fatherHome" class="label">Home Number<span class="hintanchor" title="Optional :: Only enter information for minors."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><select  class="inputbox"name="fatherHome" id="fatherHome"><option value="-1">None</option>
    {section name=header loop=$headers}
  <option value="{$smarty.section.header.index}">{$headers[header]}</option>
  {/section}
    </select></div></div><br />
<div class="fieldItem"><label for="fatherCell" class="label">Cell Number<span class="hintanchor" title="Optional :: Only enter information for minors."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><select  class="inputbox"name="fatherCell" id="fatherCell"><option value="-1">None</option>
    {section name=header loop=$headers}
  <option value="{$smarty.section.header.index}">{$headers[header]}</option>
  {/section}
    </select></div></div><br />
<div class="fieldItem"><label for="fatherWork" class="label">Work Number<span class="hintanchor" title="Optional :: Only enter information for minors."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><select  class="inputbox"name="fatherWork" id="fatherWork"><option value="-1">None</option>
    {section name=header loop=$headers}
  <option value="{$smarty.section.header.index}">{$headers[header]}</option>
  {/section}
    </select></div></div><br />
<div class="fieldItem"><label for="fatherEmail" class="label">Email Address<span class="hintanchor" title="Optional :: Only enter information for minors."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><select  class="inputbox"name="fatherEmail" id="fatherEmail"><option value="-1">None</option>
    {section name=header loop=$headers}
  <option value="{$smarty.section.header.index}">{$headers[header]}</option>
  {/section}
    </select></div></div><br />
</fieldset>

<fieldset class="formlist">
<legend>Mother</legend>
<div class="fieldItem"><label for="motherFirst" class="label">First Name<span class="hintanchor" title="Optional :: Only enter information for minors."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><select  class="inputbox"name="motherFirst" id="motherFirst"><option value="-1">None</option>
    {section name=header loop=$headers}
  <option value="{$smarty.section.header.index}">{$headers[header]}</option>
  {/section}
    </select></div></div><br />
<div class="fieldItem"><label for="motherLast" class="label">Last Name<span class="hintanchor" title="Optional :: Only enter information for minors."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><select  class="inputbox"name="motherLast" id="motherLast"><option value="-1">None</option>
    {section name=header loop=$headers}
  <option value="{$smarty.section.header.index}">{$headers[header]}</option>
  {/section}
    </select></div></div><br />
<div class="fieldItem"><label for="motherAddress" class="label">Address<span class="hintanchor" title="Optional :: Only enter information for minors."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><select  class="inputbox" name="motherAddress" id="motherAddress"><option value="-1">None</option>
    {section name=header loop=$headers}
  <option value="{$smarty.section.header.index}">{$headers[header]}</option>
  {/section}
    </select><select  class="inputbox" name="motherAddress2" id="motherAddress2"><option value="-1">None</option>
    {section name=header loop=$headers}
  <option value="{$smarty.section.header.index}">{$headers[header]}</option>
  {/section}
    </select><select  class="inputbox" name="motherAddress3" id="motherAddress3"><option value="-1">None</option>
    {section name=header loop=$headers}
  <option value="{$smarty.section.header.index}">{$headers[header]}</option>
  {/section}
    </select><select  class="inputbox" name="motherAddress4" id="motherAddress4"><option value="-1">None</option>
    {section name=header loop=$headers}
  <option value="{$smarty.section.header.index}">{$headers[header]}</option>
  {/section}
    </select></div></div><br />
<div class="fieldItem"><label for="motherHome" class="label">Home Number<span class="hintanchor" title="Optional :: Only enter information for minors."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><select  class="inputbox"name="motherHome" id="motherHome"><option value="-1">None</option>
    {section name=header loop=$headers}
  <option value="{$smarty.section.header.index}">{$headers[header]}</option>
  {/section}
    </select></div></div><br />
<div class="fieldItem"><label for="motherCell" class="label">Cell Number<span class="hintanchor" title="Optional :: Only enter information for minors."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><select  class="inputbox"name="motherCell" id="motherCell"><option value="-1">None</option>
    {section name=header loop=$headers}
  <option value="{$smarty.section.header.index}">{$headers[header]}</option>
  {/section}
    </select></div></div><br />
<div class="fieldItem"><label for="motherWork" class="label">Work Number<span class="hintanchor" title="Optional :: Only enter information for minors."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><select  class="inputbox"name="motherWork" id="motherWork"><option value="-1">None</option>
    {section name=header loop=$headers}
  <option value="{$smarty.section.header.index}">{$headers[header]}</option>
  {/section}
    </select></div></div><br />
<div class="fieldItem"><label for="motherEmail" class="label">Email Address<span class="hintanchor" title="Optional :: Only enter information for minors."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><select  class="inputbox"name="motherEmail" id="motherEmail"><option value="-1">None</option>
    {section name=header loop=$headers}
  <option value="{$smarty.section.header.index}">{$headers[header]}</option>
  {/section}
    </select></div></div><br />
</fieldset>
	</fieldset>
	</div>
	
<div id="scoutinginfo" {if $member.type != 0}style="display:none;"{else}style="display:block;"{/if}>
<fieldset>
<legend>Scouting Information</legend>
<div class="fieldItem"><label for="section" class="label">Section<span class="hintanchor" title="Required :: Which section do these {$scoutlang.member}(s) belong to"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><select id="section" name="section" class="inputbox">
<option value="0">None</option>
{section name=users loop=$numsections}
<option value="{$sections[users].id}">{$sections[users].name}</option>
{/section}
</select></div></div><br />

<div class="fieldItem"><label for="patrol" class="label">{$scoutlang.patrol}<span class="hintanchor" title="Required :: Field which contains the {$scoutlang.patrol} information?"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><select  class="inputbox"name="patrol" id="patrol"><option value="-1">None</option>
    {section name=header loop=$headers}
  <option value="{$smarty.section.header.index}">{$headers[header]}</option>
  {/section}
    </select></div></div><br />
    
<div class="fieldItem"><label for="awardScheme" class="label">{$scoutlang.award_scheme}<span class="hintanchor" title="Required :: Which {$scoutlang.award_scheme} do these{$scoutlang.member}(s) follow?"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><select id="awardScheme" name="awardScheme" class="inputbox">
<option value="0">None</option>
{section name=users loop=$numschemes}
<option value="{$schemes[users].id}">{$schemes[users].name}</option>
{/section}
</select></div></div><br />
</fieldset>
</div>
	
        <div class="submitWrapper">
    <input type="submit" name="Submit" value="Submit" class="button" />
		<input name="Cancel" type="button" id="Cancel" value="Cancel" onclick="history.go(-1);" class="button" />
   </div>  </div>
  </form>

  
  
    <h3>Imported Data</h3>
  <div style="width:98%;overflow:auto;padding:5px;height:700px;">
 <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="table sortable-onload-1 rowstyle-alt paginate-7" id="sortTable">
 <thead>
<tr> 
{section name=header loop=$headers}
  <th class="smallhead sortable">{$headers[header]}</th>
  {/section}
</tr>
</thead>
<tbody>
{section name=csvdata loop=$csvdata}
<tr class="text">
{section name=header loop=$csvdata[csvdata]}
  <td class="text">{$csvdata[csvdata][header]}</td>
  {/section}
</tr>
{/section}
</tbody>
 </table>
 </div>
 </div>
 {/if}
{elseif $action == "new" || $action == "edit"}
<h2>{if $action == "new"}Add{else}Edit{/if} {$scoutlang.member}</h2>
<div align="center"><div class="formlist">
<form name="form1" method="post" action="" onsubmit="return checkForm([['firstname','text',true,0,0,''],['lastname','text',true,0,0,''],['email','email',false,0,0,''],['dob', 'date', false,0,0,'']]);">
<div class="field">
<fieldset>
<legend>Details</legend>
<div class="fieldItem"><label for="firstname" class="label">First Name<span class="hintanchor" title="Required"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><input type="text" id="firstname" name="firstname" class="inputbox" value="{$member.firstName}" onblur="checkElement('firstname', 'text', true, 0, 0, '');"><br /><span class="fieldError" id="firstnameError">Required</span></div></div><br />

<div class="fieldItem"><label for="middlename" class="label">Middle Name<span class="hintanchor" title="Optional"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><input type="text" id="middlename" name="middlename" class="inputbox" value="{$member.middleName}" /></div></div><br />

<div class="fieldItem"><label for="lastname" class="label">Last Name<span class="hintanchor" title="Required"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><input type="text" id="lastname" name="lastname" class="inputbox" value="{$member.lastName}" onblur="checkElement('lastname', 'text', true, 0, 0, '');"><br /><span class="fieldError" id="lastnameError">Required</span></div></div><br />

<div class="fieldItem"><label for="sex" class="label">Gender<span class="hintanchor" title="Required"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><select id="sex" name="sex" class="inputbox">
<option value="0" {if $member.sex == 0}selected="selected"{/if}>Male</option>
<option value="1" {if $member.sex == 1}selected="selected"{/if}>Female</option>
</select></div></div><br />

<div class="fieldItem"><label for="address" class="label">Address<span class="hintanchor" title="Required"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><textarea id="address" name="address" rows="10" class="inputbox">{$member.address}</textarea></div></div><br />
<div class="fieldItem"><label for="homenumber" class="label">Home Number<span class="hintanchor" title="Optional"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><input type="text" id="homenumber" name="homenumber" class="inputbox" value="{$member.home}"/></div></div><br />

<div class="fieldItem"><label for="cellnumber" class="label">Cell Number<span class="hintanchor" title="Optional"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><input type="text" id="cellnumber" name="cellnumber" class="inputbox" value="{$member.cell}" /></div></div><br />

<div class="fieldItem"><label for="worknumber" class="label">Work Number<span class="hintanchor" title="Optional"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><input type="text" id="worknumber" name="worknumber" class="inputbox" value="{$member.work}" /></div></div><br />

<div class="fieldItem"><label for="email" class="label">Email Address<span class="hintanchor" title="Optional"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><input type="text" id="email" name="email" class="inputbox" value="{$member.email}" onblur="checkElement('email', 'email', false, 0, 0, '');"><br /><span class="fieldError" id="emailError">Must be a valid email address.</span></div></div><br />

<div class="fieldItem"><label for="dob" class="label">Date of Birth<span class="hintanchor" title="Optional"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><input type="text" id="dob" name="dob" class="inputbox format-y-m-d highlight-days-67" value="{$member.dob|date_format:"%Y-%m-%d"}"  onblur="checkElement('dob', 'date', false, 0, 0, '');"><br /><span class="fieldError" id="dobError">Must be a valid date in the format of YYYY-MM-DD.</span></div></div><br />
</fieldset>

<fieldset>
<legend>Medical Details</legend>
<div class="fieldItem"><label for="medicalname" class="label">Medical Aid Name<span class="hintanchor" title="Optional"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><input type="text" id="medicalname" name="medicalname" class="inputbox" value="{$member.aidName}" /></div></div><br />

<div class="fieldItem"><label for="medicalnumber" class="label">Medical Aid Number<span class="hintanchor" title="Optional"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><input type="text" id="medicalnumber" name="medicalnumber" class="inputbox" value="{$member.aidNumber}" /></div></div><br />

<div class="fieldItem"><label for="docname" class="label">Doctor's Name<span class="hintanchor" title="Optional"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><input type="text" id="docname" name="docname" class="inputbox" value="{$member.docName}" /></div></div><br />

<div class="fieldItem"><label for="docnum" class="label">Doctor Tel Number<span class="hintanchor" title="Optional"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><input type="text" id="docnum" name="docnum" class="inputbox" value="{$member.docNumber}" /></div></div><br />

<div class="fieldItem"><label for="medical" class="label">Medical Conditions<span class="hintanchor" title="Optional"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><textarea id="medical" name="medical" class="inputbox">{$member.medicalDetails}</textarea></div></div><br />
</fieldset>

<fieldset>
<legend>Other Information</legend>
{literal}
<script type="text/javascript">
function memberSelect()
{
    var index = document.getElementById('type').selectedIndex;
    var type = document.getElementById('type').options[index].value;
    
    if (type == 0)
    {
        document.getElementById('memberDiv').style.display = 'block';
        document.getElementById('scoutinginfo').style.display = 'block';
    }
    else
    {
        document.getElementById('memberDiv').style.display = 'none';        
        document.getElementById('scoutinginfo').style.display = 'none';
    }
}
</script>
{/literal}
<div class="fieldItem"><label for="type" class="label">Record Type<span class="hintanchor" title="Required"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><select id="type" name="type" class="inputbox" onchange="memberSelect();">
<option value="0" {if $member.type == 0}selected="selected"{/if}>{$scoutlang.member}</option>
<option value="1" {if $member.type == 1}selected="selected"{/if}>Father</option>
<option value="2" {if $member.type == 2}selected="selected"{/if}>Mother</option>
</select></div></div><br />

{literal}
<script type="text/javascript">
function selected()
{
    var users = [];
    var lastname = [];
    var firstname = [];
    {/literal}
    {section name=users loop=$numusers}
{if $user[users].selected}users[{$user[users].id}] = true;
    lastname[{$user[users].id}] = '{$user[users].selectedlast}';
    firstname[{$user[users].id}] = '{$user[users].selectedfirst}';
{/if}
    {/section}
    {literal}
    var index = document.getElementById('userId').selectedIndex;
    var id = document.getElementById('userId').options[index].value;
    if (users[id] == true)
    {
        document.getElementById('selected').style.display = 'block';
        document.getElementById('selected').innerHTML = '';
        document.getElementById('selected').innerHTML = 'User already in use by ' + lastname[id] + ', ' + firstname[id] + '. Selecting this user will remove the username link for ' + lastname[id] + ', ' + firstname[id] + '.';
    }
    else
    {
        document.getElementById('selected').style.display = 'none';
    }
}

function fathercopy()
{
    var index = document.getElementById('fatherId').selectedIndex;
    if (index != 0)
    {
        document.getElementById('copyfather').style.display = 'block';
    }
    else
    {
        document.getElementById('copyfather').style.display = 'none';
    }
}

function mothercopy()
{
    var index = document.getElementById('motherId').selectedIndex;
    if (index != 0)
    {
        document.getElementById('copymother').style.display = 'block';
    }
    else
    {
        document.getElementById('copymother').style.display = 'none';
    }
}

function toggle(id, fromid)
{
    if (document.getElementById(fromid).checked)
    {
        document.getElementById(id).disabled = true;
        document.getElementById(id).checked = false;
    }
    else
    {
        document.getElementById(id).disabled = false;
    }
}
</script>
{/literal}
<div class="fieldItem"><label for="userId" class="label">Website Username<span class="hintanchor" title="Optional"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><select id="userId" name="userId" class="inputbox" onchange="selected();">
<option value="0">Not a site user</option>
{section name=users loop=$numusers}
<option value="{$user[users].id}" {if $member.userId == $user[users].id}selected="selected"{/if}>{if $user[users].selected}In Use - {/if}{$user[users].lastname}, {$user[users].firstname} ({$user[users].uname})</option>
{/section}
</select><div id="selected" style="display:none;"></div></div></div><br />

<div id="memberDiv" {if $member.type != 0}style="display:none;"{else}style="display:block;"{/if}>
<div class="fieldItem"><label for="fatherId" class="label">Father<span class="hintanchor" title="Optional :: Only enter information for minors."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><select id="fatherId" name="fatherId" class="inputbox" onchange="fathercopy();">
<option value="0">Not in system</option>
{section name=users loop=$numfathers}
<option value="{$fathers[users].id}" {if $member.fatherId == $fathers[users].id}selected="selected"{/if}>{$fathers[users].lastName}, {$fathers[users].firstName}</option>
{/section}
</select></div></div><br />
<div id="copyfather" {if $member.fatherId}style="display:block;"{else}style="display:none;"{/if}>
<div class="fieldItem"><span class="label">Copy from father<span class="hintanchor" title="Optional :: Select information that should be copied from the fathers details. This will overwrite any existing information."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></span>
<div class="inputboxwrapper">
<input type="checkbox" name="copy[father][home]" id="copyfatherhome" value="1" onchange="toggle('copymotherhome', 'copyfatherhome');"><label for="copyfatherhome">Home Number</label>
<input type="checkbox" name="copy[father][cell]" id="copyfathercell" value="1" onchange="toggle('copymothercell', 'copyfathercell');"><label for="copyfathercell">Cellphone Number</label><br />
<input type="checkbox" name="copy[father][work]" id="copyfatherwork" value="1" onchange="toggle('copymotherwork', 'copyfatherwork');"><label for="copyfatherwork">Work Number</label>
<input type="checkbox" name="copy[father][email]" id="copyfatheremail" value="1" onchange="toggle('copymotheremail', 'copyfatheremail');"><label for="copyfatheremail">Email Address</label><br />
<input type="checkbox" name="copy[father][medical]" id="copyfathermedical" value="1" onchange="toggle('copymothermedical', 'copyfathermedical');"><label for="copyfathermedical">Medical Details</label>
</div></div><br />
</div>

<div class="fieldItem"><label for="motherId" class="label">Mother<span class="hintanchor" title="Optional :: Only enter information for minors."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><select id="motherId" name="motherId" class="inputbox"  onchange="mothercopy();">
<option value="0">Not in system</option>
{section name=users loop=$nummothers}
<option value="{$mother[users].id}" {if $member.motherId == $mother[users].id}selected="selected"{/if}>{$mother[users].lastName}, {$mother[users].firstName}</option>
{/section}
</select></div></div><br />
<div id="copymother" {if $member.motherId}style="display:block;"{else}style="display:none;"{/if}>
<div class="fieldItem"><span class="label">Copy from mother<span class="hintanchor" title="Optional :: Select information that should be copied from the mother details. This will overwrite any existing information."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></span>
<div class="inputboxwrapper">
<input type="checkbox" name="copy[mother][home]" id="copymotherhome" value="1" onchange="toggle('copyfatherhome', 'copymotherhome');"><label for="copymotherhome">Home Number</label>
<input type="checkbox" name="copy[mother][cell]" id="copymothercell" value="1" onchange="toggle('copyfathercell', 'copymothercell');"><label for="copymothercell">Cellphone Number</label><br />
<input type="checkbox" name="copy[mother][work]" id="copymotherwork" value="1" onchange="toggle('copyfatherwork', 'copymotherwork');"><label for="copymotherwork">Work Number</label>
<input type="checkbox" name="copy[mother][email]" id="copymotheremail" value="1" onchange="toggle('copyfatheremail', 'copymotheremail');"><label for="copymotheremail">Email Address</label><br />
<input type="checkbox" name="copy[mother][medical]" id="copymothermedical" value="1" onchange="toggle('copyfathermedical', 'copymothermedical');"><label for="copymothermedical">Medical Details</label>
</div></div><br />
</div>

<div class="fieldItem"><label for="primaryGuard" class="label">Primary Guardian<span class="hintanchor" title="Optional :: Only enter information for minors."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><select id="primaryGuard" name="primaryGuard" class="inputbox">
<option value="0" {if $member.primaryGuard == 0}selected="selected"{/if}>Father</option>
<option value="1" {if $member.primaryGuard == 1}selected="selected"{/if}>Mother</option>
</select></div></div><br /></div>
</fieldset>

{if $numfields > 0}
<fieldset>
<legend>Additional Information</legend>
    {section name=fields loop=$numfields}
        <div class="fieldItem"><{if $fields[fields].type != 3 && $fields[fields].type != 4}label for="{$fields[fields].name}"{else}span{/if} class="label">{$fields[fields].query}<span class="hintanchor" title="{if $fields[fields].required}Required{else}Optional{/if}::{$fields[fields].hint}"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></{if $fields[fields].type != 3 && $fields[fields].type != 4}label{else}span{/if}>
        {assign var="name" value=$fields[fields].name}
        <div class="inputboxwrapper">
        {if $fields[fields].type == 1}
            <input name="{$fields[fields].name}" type="text" size="{math equation="x + y" x=$fields[fields].options y=5}" maxlength="{$fields[fields].options}" value="{$member.custom.$name}" class="inputbox" />
        {elseif $fields[fields].type == 2}
            <textarea name="{$fields[fields].name}" cols="50" rows="5"  class="inputbox">{$details.custom.$name}</textarea>
        {elseif $fields[fields].type == 3}
            {section name=options loop=$fields[fields].options[0]+1 start=1}
            <input type="radio" name="{$fields[fields].name}" id="{$fields[fields].name}{$smarty.section.options.iteration}" value="{$smarty.section.options.iteration}" {if $member.custom.$name == $smarty.section.options.iteration}checked="checked"{/if} /><label for="{$fields[fields].name}{$smarty.section.options.iteration}">{$fields[fields].options[options]}</label>
            {/section}
        {elseif $fields[fields].type == 4}
            {assign var="temp" value=$details.custom.$name}
            {section name=options loop=$fields[fields].options[0]+1 start=1}
            <input type="checkbox" name="{$fields[fields].name}{$smarty.section.options.iteration}" id="{$fields[fields].name}{$smarty.section.options.iteration}" value="1" {if $temp[options] == 1}checked="checked"{/if} /><label for="{$fields[fields].name}{$smarty.section.options.iteration}">{$fields[fields].options[options]}</label>
            {/section}
        {elseif $fields[fields].type == 5}
            <select name="{$fields[fields].name}" class="inputbox">
            {section name=options loop=$fields[fields].options[0]+1 start=1}
            <option value="{$smarty.section.options.iteration}" {if $member.custom.$name == $smarty.section.options.iteration}selected="selected"{/if}>{$fields[fields].options[options]}</option>
            {/section}
            </select>  
        {elseif $fields[fields].type == 6}
            <input name="{$fields[fields].name}" type="text" value="{$member.custom.$name}" class="inputbox format-y-m-d highlight-days-67" />           
        {/if}   
        </div></div><br />
    {/section}         
</fieldset>
{/if}

<div id="scoutinginfo" {if $member.type != 0}style="display:none;"{else}style="display:block;"{/if}>
<fieldset>
<legend>Scouting Information</legend>
<div class="fieldItem"><label for="section" class="label">Section<span class="hintanchor" title="Required :: Which section does this {$scoutlang.member} belong to"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><select id="section" name="section" class="inputbox" />
<option value="0">None</option>
{section name=users loop=$numsections}
<option value="{$sections[users].id}" {if $member.section == $sections[users].id}selected="selected"{/if}>{$sections[users].name}</option>
{/section}
</select></div></div><br />

<div class="fieldItem"><label for="patrol" class="label">{$scoutlang.patrol}<span class="hintanchor" title="Required :: Which {$scoutlang.patrol} does this {$scoutlang.member} belong too?"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><select id="patrol" name="patrol" class="inputbox" />
<option value="0">None</option>
{section name=users loop=$numpatrols}
<option value="{$patrol[users].id}" {if $member.patrol == $patrol[users].id}selected="selected"{/if}>{$patrol[users].teamname}</option>
{/section}
</select></div></div><br />
<div class="fieldItem"><label for="awardScheme" class="label">{$scoutlang.award_scheme}<span class="hintanchor" title="Required :: Which {$scoutlang.award_scheme} does this {$scoutlang.member} follow?"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><select id="awardScheme" name="awardScheme" class="inputbox" />
<option value="0">None</option>
{section name=users loop=$numschemes}
<option value="{$schemes[users].id}" {if $member.awardScheme == $schemes[users].id}selected="selected"{/if}>{$schemes[users].name}</option>
{/section}
</select></div></div><br />
{if $numscoutfields > 0}
<fieldset>
<legend>Additional Scouting Information</legend>
    {section name=scoutfields loop=$numscoutfields}
        <div class="fieldItem"><{if $scoutfields[scoutfields].type != 3 && $scoutfields[scoutfields].type != 4}label for="{$scoutfields[scoutfields].name}"{else}span{/if} class="label">{$scoutfields[scoutfields].query}<span class="hintanchor" title="{if $scoutfields[scoutfields].required}Required{else}Optional{/if}::{$scoutfields[scoutfields].hint}"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></{if $scoutfields[scoutfields].type != 3 && $scoutfields[scoutfields].type != 4}label{else}span{/if}>
        {assign var="name" value=$scoutfields[scoutfields].name}
        <div class="inputboxwrapper">
        {if $scoutfields[scoutfields].type == 1}
            <input name="{$scoutfields[scoutfields].name}" type="text" size="{math equation="x + y" x=$scoutfields[scoutfields].options y=5}" maxlength="{$scoutfields[scoutfields].options}" value="{$member.custom.$name}" class="inputbox" />
        {elseif $scoutfields[scoutfields].type == 2}
            <textarea name="{$scoutfields[scoutfields].name}" cols="50" rows="5"  class="inputbox">{$details.custom.$name}</textarea>
        {elseif $scoutfields[scoutfields].type == 3}
            {section name=options loop=$scoutfields[scoutfields].options[0]+1 start=1}
            <input type="radio" name="{$scoutfields[scoutfields].name}" id="{$scoutfields[scoutfields].name}{$smarty.section.options.iteration}" value="{$smarty.section.options.iteration}" {if $member.custom.$name == $smarty.section.options.iteration}checked="checked"{/if} /><label for="{$scoutfields[scoutfields].name}{$smarty.section.options.iteration}">{$scoutfields[scoutfields].options[options]}</label>
            {/section}
        {elseif $scoutfields[scoutfields].type == 4}
            {assign var="temp" value=$member.custom.$name}
            {section name=options loop=$scoutfields[scoutfields].options[0]+1 start=1}
            <input type="checkbox" name="{$scoutfields[scoutfields].name}{$smarty.section.options.iteration}" id="{$scoutfields[scoutfields].name}{$smarty.section.options.iteration}" value="1" {if $temp[options] == 1}checked="checked"{/if} /><label for="{$scoutfields[scoutfields].name}{$smarty.section.options.iteration}">{$scoutfields[scoutfields].options[options]}</label>&nbsp;
            {/section}
        {elseif $scoutfields[scoutfields].type == 5}
            <select name="{$scoutfields[scoutfields].name}" class="inputbox">
            {section name=options loop=$scoutfields[scoutfields].options[0]+1 start=1}
            <option value="{$smarty.section.options.iteration}" {if $member.custom.$name == $smarty.section.options.iteration}selected="selected"{/if}>{$scoutfields[scoutfields].options[options]}</option>
            {/section}
            </select>  
        {elseif $scoutfields[scoutfields].type == 6}
            <input name="{$scoutfields[scoutfields].name}" type="text" value="{$member.custom.$name}" class="inputbox format-y-m-d highlight-days-67" />           
        {/if}   
        </div></div><br />
    {/section}          
</fieldset>
{/if}
</fieldset>
</div>
</div>
<div class="submitWrapper"<input type="submit" name="submit" value="Submit" class="button" />&nbsp;<input name="Cancel" type="button" id="Cancel" value="Cancel" onclick="window.location='admin.php?page=troop';" class="button" /></div>

</form>
</div></div>
{elseif $action == "view"}
<h2>View {$scoutlang.member}</h2>
<div align="center"><div class="formlist">
<div class="field">
<fieldset>
<legend>Details</legend>
<div class="fieldItem"><label for="firstname" class="label">First Name</label>
<div class="inputboxwrapper">{$member.firstName}</div></div><br />

<div class="fieldItem"><label for="middlename" class="label">Middle Name</label>
<div class="inputboxwrapper">{$member.middleName}</div></div><br />

<div class="fieldItem"><label for="lastname" class="label">Last Name</label>
<div class="inputboxwrapper">{$member.lastName}</div></div><br />

<div class="fieldItem"><label for="sex" class="label">Gender</label>
<div class="inputboxwrapper">{if $member.sex == 0}Male{else}Female{/if}</div></div><br />

<div class="fieldItem"><label for="address" class="label">Address</label>
<div class="inputboxwrapper">{$member.address}</div></div><br />

<div class="fieldItem"><label for="homenumber" class="label">Home Number</label>
<div class="inputboxwrapper">{$member.home}</div></div><br />

<div class="fieldItem"><label for="cellnumber" class="label">Cell Number</label>
<div class="inputboxwrapper">{$member.cell}</div></div><br />

<div class="fieldItem"><label for="worknumber" class="label">Work Number</label>
<div class="inputboxwrapper">{$member.work}</div></div><br />

<div class="fieldItem"><label for="email" class="label">Email Address</label>
<div class="inputboxwrapper">{$member.email}</div></div><br />
</fieldset>

<fieldset>
<legend>Medical Details</legend>
<div class="fieldItem"><label for="medicalname" class="label">Medical Aid Name</label>
<div class="inputboxwrapper">{$member.aidName}</div></div><br />

<div class="fieldItem"><label for="medicalnumber" class="label">Medical Aid Number</label>
<div class="inputboxwrapper">{$member.aidNumber}</div></div><br />

<div class="fieldItem"><label for="docname" class="label">Doctor's Name</label>
<div class="inputboxwrapper">{$member.docName}</div></div><br />

<div class="fieldItem"><label for="docnum" class="label">Doctor Tel Number</label>
<div class="inputboxwrapper">{$member.docNumber}</div></div><br />

<div class="fieldItem"><label for="medical" class="label">Medical Conditions</label>
<div class="inputboxwrapper">{$member.medicalDetails}</div></div><br />
</fieldset>

<fieldset>
<legend>Other Information</legend>
<div class="fieldItem"><label for="type" class="label">Record Type</label>
<div class="inputboxwrapper">{if $member.type == 0}{$scoutlang.member}{elseif $member.type == 1}Father{elseif $member.type == 2}Mother{elseif $member.type == 3}Legal Guardian{/if}
</div></div><br />

<div class="fieldItem"><label for="userId" class="label">Website Username</label>
<div class="inputboxwrapper">{if $member.userId}{$member.user.lastname}, {$member.user.firstname} ({$member.user.uname}){else}{$scoutlang.member} not registered on site.{/if}</div></div><br />
{if $member.type == 0}
<div class="fieldItem"><label for="fatherId" class="label">Father</label>
<div class="inputboxwrapper">{if $member.fatherId}{$member.father.lastname}, {$member.father.firstname}{else}Not in system.{/if}</div></div><br />
{if $member.fatherId}<div class="fieldItem">
<div class="inputboxwrapper" style="width:100%;"><table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="table">
<thead>
<tr> 
  <th width="25%" class="smallhead">Home</th>
  <th width="25%" class="smallhead">Cell</th>
  <th width="25%" class="smallhead">Work</th>
  <th width="25%" class="smallhead">Email</th>
</tr>
</thead>
<tbody>
<tr class="text">
  <td class="text">{$member.father.home}</td>
  <td class="text">{$member.father.cell}</td>
  <td class="text">{$member.father.work}</td>
  <td class="text"><a href="mailto:{$member.father.email}">{$member.father.email}</a></td>
</tr>
</tbody>
</table></div></div><br />{/if}

<div class="fieldItem"><label for="motherId" class="label">Mother</label>
<div class="inputboxwrapper">{if $member.motherId}{$member.mother.lastname}, {$member.mother.firstname}{else}Not in system.{/if}</div></div><br />
{if $member.motherId}<div class="fieldItem">
<div class="inputboxwrapper" style="width:100%;"><table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="table">
<thead>
<tr> 
  <th width="25%" class="smallhead">Home</th>
  <th width="25%" class="smallhead">Cell</th>
  <th width="25%" class="smallhead">Work</th>
  <th width="25%" class="smallhead">Email</th>
</tr>
</thead>
<tbody>
<tr class="text">
  <td class="text">{$member.mother.home}</td>
  <td class="text">{$member.mother.cell}</td>
  <td class="text">{$member.mother.work}</td>
  <td class="text"><a href="mailto:{$member.mother.email}">{$member.mother.email}</a></td>
</tr>
</tbody>
</table></div></div><br />{/if}

<div class="fieldItem"><label for="primaryGuard" class="label">Primary Guardian</label>
<div class="inputboxwrapper">{if $member.primaryGuard == 0}Father{else}Mother{/if}</div></div><br />
{/if}
</fieldset>

{if $member.type == 0}
<fieldset>
<legend>Scouting</legend>
<div class="fieldItem"><label for="section" class="label">Section</label>
<div class="inputboxwrapper">{if $member.section}{$member.section.name}{else}None{/if}</div></div><br />

<div class="fieldItem"><label for="patrol" class="label">{$scoutlang.patrol}</label>
<div class="inputboxwrapper">{if $member.patrol}{$member.patrolname.teamname}{else}None{/if}</div></div><br />

<div class="fieldItem"><label for="awardScheme" class="label">{$scoutlang.award_scheme}</label>
<div class="inputboxwrapper">{if $member.awardScheme}{$member.awardScheme.name}{else}None{/if}</div></div><br />
</fieldset>
{/if}

{if $numfields > 0}
<fieldset>
<legend>Additional Information</legend>
    {section name=fields loop=$numfields}
        <div class="fieldItem"><label for="$fields[fields].name" class="label">{$fields[fields].query}</label>
        {assign var="name" value=$fields[fields].name}
        <div class="inputboxwrapper">
        {if $fields[fields].type == 1}
            {$member.custom.$name}
        {elseif $fields[fields].type == 2}
            {$member.custom.$name}
        {elseif $fields[fields].type == 3}
            {section name=options loop=$fields[fields].options[0]+1 start=1}
                {if $member.custom.$name == $smarty.section.options.iteration}{$fields[fields].options[options]}{/if}
            {/section}
        {elseif $fields[fields].type == 4}
            {assign var="temp" value=$member.custom.$name}
            {section name=options loop=$fields[fields].options[0]+1 start=1}
                {if $temp[options] == 1}{$fields[fields].options[options]}{/if}{if $smarty.section.options.iteration < ($fields[fields].options[0])}, {/if}
            {/section}
        {elseif $fields[fields].type == 5}
            {section name=options loop=$fields[fields].options[0]+1 start=1}
                {if $member.custom.$name == $smarty.section.options.iteration}{$fields[fields].options[options]}{/if}
            {/section}
        {elseif $fields[fields].type == 6}
            {$member.custom.$name}           
        {/if}   
        </div></div><br />
    {/section}          
</fieldset>
{/if}
</div>
<a href="{$pagename}">Back</a>
</div></div>
{/if}