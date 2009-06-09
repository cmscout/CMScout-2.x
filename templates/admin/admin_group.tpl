{literal}
  <script type="text/javascript">
<!--
function confirmDelete(articleId) {
if (confirm("This will delete this group. Continue?"))
document.location = "admin.php?page=group&action=delete&id=" + articleId;
}
//-->
  </script>
  {/literal}

<h2>Group Manager</h2>
{if ($action != "edit") && ($action!="Add") && ($action!="auth")}
  {if $addallowed}<div class="toplinks"><a href="{$pagename}&amp;action=Add" title="Add Group"><img src="{$tempdir}admin/images/add.png" alt="Add Group" border="0" /></a>
</div>{/if}
{if $numgroups > 0}
<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="table sortable-onload-2 rowstyle-alt paginate-15" id="sortTable">
<thead>
<tr> 
  <th width="12%" class="smallhead"></th>
  <th class="smallhead">Group Name</th>
</tr>
</thead>
<tbody>
{section name=groups loop=$numgroups}
<tr valign="top" class="text">
  <td class="text" style="text-align:center;">{if $editallowed}<a href="{$pagename}&amp;action=edit&amp;id={$groups[groups].id}" title="Edit {$groups[groups].teamname}" ><img src="{$tempdir}admin/images/edit.gif" border="0" alt="Edit {$groups[groups].teamname}"/></a>{else}<img src="{$tempdir}admin/images/edit_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;&nbsp;<a href="{$pagename}&amp;subpage=groupusers&amp;gid={$groups[groups].id}" title="View {$groups[groups].teamname} Users"><img src="{$tempdir}admin/images/group.gif" alt="{$groups[groups].teamname} Users" border="0"/></a>&nbsp;&nbsp;{if $publishallowed}<a href="{$pagename}&amp;action=auth&amp;id={$groups[groups].id}" title="{$groups[groups].teamname} access rights" ><img src="{$tempdir}admin/images/key.png" alt="{$groups[groups].teamname} access rights"border="0"/></a>{else}<img src="{$tempdir}admin/images/key_grey.gif" border="0" alt="Editing of access rights Disabled" title="Editing of access rights Disabled" />{/if}&nbsp;&nbsp;{if $deleteallowed}<a href="javascript:confirmDelete({$groups[groups].id})" title="Delete {$groups[groups].teamname}"><img src="{$tempdir}admin/images/delete.gif" border="0" alt="Delete {$groups[groups].teamname}" /></a>{else}<img src="{$tempdir}admin/images/delete_grey.gif" border="0" alt="Deleting Disabled" title="Deleting Disabled" />{/if}</td>
  <td class="text"><span class="hintanchor" title="Information :: &lt;b&gt;Group Site:&lt;/b&gt; {if ($groups[groups].ispublic  == 1)}Yes{else}No{/if}{if $groups[groups].getpoints != '0'}&lt;br /&gt;&lt;b&gt;Points:&lt;/b&gt; {$groups[groups].points}{/if}"><img src="{$tempdir}admin/images/information.png" alt="[?]"/></span>{$groups[groups].teamname}</td>
</tr>
{/section}
</tbody>
</table>
{else}
<div align="center">No groups</div>
{/if}
{elseif ($action=="edit" || $action=="Add")}
<div align="center">
<form name="form1" method="post"  onsubmit="return checkForm([['name','text',true,0,0,'']]);">
<fieldset class="formlist">
<legend>{if $action=="edit"}Edit{else}New{/if} Group</legend>
<div class="field">
<label for="name" class="label">Name<span class="hintanchor" title="Name of the group."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
<div class="inputboxwrapper"><input name="name" type="text" id="name" size="55" maxlength="50" value="{$group.teamname}" class="inputbox" onblur="checkElement('name', 'text', true, 0, 0, '');" /><br /><span class="fieldError" id="nameError">Required</span></div><br />

<span class="label">Group Site<span class="hintanchor" title="Activate sub site for this group."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></span>
<div class="inputboxwrapper"><input type="radio" name="publicgroup" id="publicgroup:yes" value="1" {if $group.ispublic == 1}checked{/if}/><label for="publicgroup:yes">Yes</label>
    <input type="radio" name="publicgroup" id="publicgroup:no" value="0" {if $group.ispublic == 0}checked{/if}/><label for="publicgroup:no">No</label></div><br />

<span class="label">{$scoutlang.patrol}<span class="hintanchor" title="Is this group a {$scoutlang.patrol}."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></span>
<div class="inputboxwrapper"><input type="radio" name="patrol" id="patrol:yes" value="1" {if $group.ispatrol == 1}checked{/if} /><label for="patrol:yes">Yes</label>
        <input type="radio" name="patrol" id="patrol:no" value="0" {if $group.ispatrol == 0}checked{/if} /><label for="patrol:no">No</label></div><br />


<span class="label">Points<span class="hintanchor" title="Can this group receive points."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></span>
<div class="inputboxwrapper"><input type="radio" name="points" id="points:yes" value="1" {if $group.getpoints == 1}checked{/if} /><label for="points:yes">Yes</label>
        <input type="radio" name="points" id="points:no" value="0" {if $group.getpoints == 0}checked{/if} /><label for="points:no">No</label></div><br />

    </div>
    <div class="submitWrapper"><input type="submit" name="Submit" value="Submit" class="button" />
    <input name="Cancel" type="button" id="Cancel" value="Cancel" onclick="history.go(-1);" class="button" />
    </div>
    </fieldset>
</form>
</div>
{elseif $action == "auth"}
  <script type="text/javascript">
   {literal}
   function all(utype, thingy)
   {
      if (document.getElementById(utype + thingy).checked == true)
      {
       {/literal} 
        {section name=module loop=$nummodules}
            if (document.getElementById(utype + '_{$modules[module].id}_' + thingy).disabled==false)
                document.getElementById(utype + '_{$modules[module].id}_' + thingy).checked=true;
        {/section}
        {literal}
      }
      else if (document.getElementById(utype + thingy).checked == false)
      {
       {/literal} 
        {section name=module loop=$nummodules}
            document.getElementById(utype + '_{$modules[module].id}_' + thingy).checked=false;
        {/section}
        {literal}
      }
   }
   {/literal}
   {literal}
   function disable(utype)
   {
      if (document.getElementById(utype + "_adminpanel").checked == true)
      {
       {/literal} 
        {section name=module loop=$nummodules}
            {if $modules[module].access != "notused"}
                document.getElementById(utype + '_{$modules[module].id}_access').disabled=false;
            {/if}
            {if $modules[module].add != "notused"}
                document.getElementById(utype + '_{$modules[module].id}_add').disabled=false;
            {/if}
            {if $modules[module].edit != "notused"}
                document.getElementById(utype + '_{$modules[module].id}_edit').disabled=false;
            {/if}
            {if $modules[module].delete != "notused"}
                document.getElementById(utype + '_{$modules[module].id}_delete').disabled=false;
            {/if}
            {if $modules[module].publish != "notused"}
                document.getElementById(utype + '_{$modules[module].id}_pub').disabled=false;
            {/if}
            {if $modules[module].limit != "notused"}
                document.getElementById(utype + '_{$modules[module].id}_limit').disabled=false;
            {/if}
            document.getElementById(utype + 'access').disabled=false;
            document.getElementById(utype + 'add').disabled=false;
            document.getElementById(utype + 'edit').disabled=false;
            document.getElementById(utype + 'delete').disabled=false;
            document.getElementById(utype + 'pub').disabled=false;
            document.getElementById(utype + 'limit').disabled=false;
        {/section}
        {literal}
      }
      else if (document.getElementById(utype + "_adminpanel").checked == false)
      {
       {/literal} 
        {section name=module loop=$nummodules}
            {if $modules[module].access != "notused"}
                document.getElementById(utype + '_{$modules[module].id}_access').disabled=true;
            {/if}
            {if $modules[module].add != "notused"}
                document.getElementById(utype + '_{$modules[module].id}_add').disabled=true;
            {/if}
            {if $modules[module].edit != "notused"}
                document.getElementById(utype + '_{$modules[module].id}_edit').disabled=true;
            {/if}
            {if $modules[module].delete != "notused"}
                document.getElementById(utype + '_{$modules[module].id}_delete').disabled=true;
            {/if}
            {if $modules[module].publish != "notused"}
                document.getElementById(utype + '_{$modules[module].id}_pub').disabled=true;
            {/if}
            {if $modules[module].limit != "notused"}
                document.getElementById(utype + '_{$modules[module].id}_limit').disabled=true;
            {/if}
            document.getElementById(utype + 'access').disabled=true;
            document.getElementById(utype + 'add').disabled=true;
            document.getElementById(utype + 'edit').disabled=true;
            document.getElementById(utype + 'delete').disabled=true;
            document.getElementById(utype + 'pub').disabled=true;
            document.getElementById(utype + 'limit').disabled=true;
        {/section}
        {literal}
      }
   }
   {/literal}
  </script>
<form name="form1" method="post" action="{$editFormAction}">
<div align="center"><div class="formlist">
<div id="navcontainer">

<h4 title="normal">Normal User</h4>
<div id="normal" >
<div align="center">
    <input name="user_adminpanel" id="user_adminpanel" type="checkbox" value="1" onclick="disable('user');" {if $user.adminpanel == 1}checked="checked"{/if} />Allow access to administration panel
</div>
<table width="100%" border="0" cellspacing="1" cellpadding="4" align="center" class="table">
    <tr>
        <th class="smallhead">Module</th>
        <th width="10%" class="smallhead">Access Module</th>
        <th width="10%" class="smallhead">Add Items</th>
        <th width="10%" class="smallhead">Edit Items</th>
        <th width="10%" class="smallhead">Delete Items</th>
        <th width="10%" class="smallhead">Publish Items</th>
        <th width="10%" class="smallhead">Limitations</th>
    </tr>
<tr>
    <td class="text"></td>
    <td class="text" style="text-align:center;">
    <label for="useraccess"><input {if $user.adminpanel == 0}disabled="disabled"{/if} type="checkbox" value="1" id="useraccess" onclick="all('user', 'access');"/></label>
    </td>
    <td class="text" style="text-align:center;">
    <label for="useradd"><input {if $user.adminpanel == 0}disabled="disabled"{/if} type="checkbox" value="1" id="useradd" onclick="all('user', 'add');"/></label>
    </td>
    <td class="text" style="text-align:center;">
    <label for="useredit"><input {if $user.adminpanel == 0}disabled="disabled"{/if} type="checkbox" value="1" id="useredit" onclick="all('user', 'edit');"/></label>
    </td>
    <td class="text" style="text-align:center;">
    <label for="userdelete"><input {if $user.adminpanel == 0}disabled="disabled"{/if} type="checkbox" value="1" id="userdelete" onclick="all('user', 'delete');"/></label>
    </td>
    <td class="text" style="text-align:center;">
    <label for="userpub"><input {if $user.adminpanel == 0}disabled="disabled"{/if} type="checkbox" value="1" id="userpub" onclick="all('user', 'pub');"/></label>
    </td>
    <td class="text" style="text-align:center;">
    <label for="userlimit"><input {if $user.adminpanel == 0}disabled="disabled"{/if} type="checkbox" value="1" id="userlimit" onclick="all('user', 'limit');"/></label>
    </td>
</tr>
{section name=module loop=$nummodules}
    {assign var="modid" value=$modules[module].id}
    <tr>
        <td class="text"><div align="right">{$modules[module].name}<span class="hintanchor"title="{$modules[module].description}"><img src="{$tempdir}admin/images/information.png" alt="[i]"/></span></div></td>
        <td class="text" style="text-align:center;"><input {if $modules[module].access == "notused" || $modules[module].access == "" || $user.adminpanel == 0}disabled="disabled"{/if} name="user_{$modules[module].id}_access" id="user_{$modules[module].id}_access" type="checkbox" value="1" {if $user.access.$modid == 1}checked="checked"{/if}/></td>
        <td class="text" style="text-align:center;"><input {if $modules[module].add == "notused" || $modules[module].add == "" || $user.adminpanel == 0}disabled="disabled"{/if} name="user_{$modules[module].id}_add" id="user_{$modules[module].id}_add" type="checkbox" value="1"  {if $user.add.$modid == 1}checked="checked"{/if}/></td>
        <td class="text" style="text-align:center;"><input {if $modules[module].edit == "notused" || $modules[module].edit == "" || $user.adminpanel == 0}disabled="disabled"{/if} name="user_{$modules[module].id}_edit" id="user_{$modules[module].id}_edit" type="checkbox" value="1"  {if $user.edit.$modid == 1}checked="checked"{/if}/></td>
        <td class="text" style="text-align:center;"><input {if $modules[module].delete == "notused" || $modules[module].delete == "" || $user.adminpanel == 0}disabled="disabled"{/if} name="user_{$modules[module].id}_delete" id="user_{$modules[module].id}_delete" type="checkbox" value="1"  {if $user.delete.$modid == 1}checked="checked"{/if}/></td>
        <td class="text" style="text-align:center;"><input {if $modules[module].publish == "notused" || $modules[module].publish == "" || $user.adminpanel == 0}disabled="disabled"{/if} name="user_{$modules[module].id}_pub" id="user_{$modules[module].id}_pub" type="checkbox" value="1"  {if $user.publish.$modid == 1}checked="checked"{/if}/></td>
        <td class="text" style="text-align:center;"><input {if $modules[module].limit == "notused" || $modules[module].limit == "" || $user.adminpanel == 0}disabled="disabled"{/if} name="user_{$modules[module].id}_limit" id="user_{$modules[module].id}_limit" type="checkbox" value="1"  {if $user.limit.$modid == 1}checked="checked"{/if}/></td>
    </tr>
{/section}
</table>
</div>

<h4 title="assistant">Assistant Group Leader</h4>
<div id="assistant" >
<div align="center">
<input name="ass_adminpanel" id="ass_adminpanel" type="checkbox" value="1" onclick="disable('ass');" {if $ass.adminpanel == 1}checked="checked"{/if}/>Allow access to administration panel
</div>
<table width="100%" border="0" cellspacing="1" cellpadding="4" align="center" class="table">
<tr>
<th class="smallhead">Module</th>
<th width="10%" class="smallhead">Access Module</th>
<th width="10%" class="smallhead">Add Items</th>
<th width="10%" class="smallhead">Edit Items</th>
<th width="10%" class="smallhead">Delete Items</th>
<th width="10%" class="smallhead">Publish Items</th>
<th width="10%" class="smallhead">Limitations</th>
</tr>
<tr>
    <td class="text"></td>
    <td class="text" style="text-align:center;">
    <label for="assaccess"><input {if $ass.adminpanel == 0}disabled="disabled"{/if} type="checkbox" value="1" id="assaccess" onclick="all('ass', 'access');"/></label>
    </td>
    <td class="text" style="text-align:center;">
    <label for="assadd"><input {if $ass.adminpanel == 0}disabled="disabled"{/if} type="checkbox" value="1" id="assadd" onclick="all('ass', 'add');"/></label>
    </td>
    <td class="text" style="text-align:center;">
    <label for="assedit"><input {if $ass.adminpanel == 0}disabled="disabled"{/if} type="checkbox" value="1" id="assedit" onclick="all('ass', 'edit');"/></label>
    </td>
    <td class="text" style="text-align:center;">
    <label for="assdelete"><input {if $ass.adminpanel == 0}disabled="disabled"{/if} type="checkbox" value="1" id="assdelete" onclick="all('ass', 'delete');"/></label>
    </td>
    <td class="text" style="text-align:center;">
    <label for="asspub"><input {if $ass.adminpanel == 0}disabled="disabled"{/if} type="checkbox" value="1" id="asspub" onclick="all('ass', 'pub');"/></label>
    </td>
    <td class="text" style="text-align:center;">
    <label for="asslimit"><input {if $ass.adminpanel == 0}disabled="disabled"{/if} type="checkbox" value="1" id="asslimit" onclick="all('ass', 'limit');"/></label>
    </td>
</tr>
{section name=module loop=$nummodules}
{assign var="modid" value=$modules[module].id}
<tr>
<td class="text"><div align="right">{$modules[module].name}<span class="hintanchor"title="{$modules[module].description}"><img src="{$tempdir}admin/images/information.png" alt="[i]"/></span></div></td>
<td class="text" style="text-align:center;"><input {if $modules[module].access == "notused" || $modules[module].access == "" || $ass.adminpanel == 0}disabled="disabled"{/if} name="ass_{$modules[module].id}_access" id="ass_{$modules[module].id}_access" type="checkbox" value="1" {if $ass.access.$modid == 1}checked="checked"{/if}/></td>
<td class="text" style="text-align:center;"><input {if $modules[module].add == "notused" || $modules[module].add == "" || $ass.adminpanel == 0}disabled="disabled"{/if} name="ass_{$modules[module].id}_add" id="ass_{$modules[module].id}_add" type="checkbox" value="1"  {if $ass.add.$modid == 1}checked="checked"{/if}/></td>
<td class="text" style="text-align:center;"><input {if $modules[module].edit == "notused" || $modules[module].edit == "" || $ass.adminpanel == 0}disabled="disabled"{/if} name="ass_{$modules[module].id}_edit" id="ass_{$modules[module].id}_edit" type="checkbox" value="1"  {if $ass.edit.$modid == 1}checked="checked"{/if}/></td>
<td class="text" style="text-align:center;"><input {if $modules[module].delete == "notused" || $modules[module].delete == "" || $ass.adminpanel == 0}disabled="disabled"{/if} name="ass_{$modules[module].id}_delete" id="ass_{$modules[module].id}_delete" type="checkbox" value="1"  {if $ass.delete.$modid == 1}checked="checked"{/if}/></td>
<td class="text" style="text-align:center;"><input {if $modules[module].publish == "notused" || $modules[module].publish == "" || $ass.adminpanel == 0}disabled="disabled"{/if} name="ass_{$modules[module].id}_pub" id="ass_{$modules[module].id}_pub" type="checkbox" value="1"  {if $ass.publish.$modid == 1}checked="checked"{/if}/></td>
<td class="text" style="text-align:center;"><input {if $modules[module].limit == "notused" || $modules[module].limit == "" || $ass.adminpanel == 0}disabled="disabled"{/if} name="ass_{$modules[module].id}_limit" id="ass_{$modules[module].id}_limit" type="checkbox" value="1"  {if $ass.limit.$modid == 1}checked="checked"{/if}/></td>
</tr>
{/section}
</table>
</div>

<h4 title="leader">Group Leader</h4>
<div id="leader" >
<div align="center">
<input name="gpl_adminpanel" id="gpl_adminpanel" type="checkbox" value="1" onclick="disable('gpl');" {if $gpl.adminpanel == 1}checked="checked"{/if}/>Allow access to administration panel
</div>
<table width="100%" border="0" cellspacing="1" cellpadding="4" align="center" class="table">
<tr>
<th class="smallhead">Module</th>
<th width="10%" class="smallhead">Access Module</th>
<th width="10%" class="smallhead">Add Items</th>
<th width="10%" class="smallhead">Edit Items</th>
<th width="10%" class="smallhead">Delete Items</th>
<th width="10%" class="smallhead">Publish Items</th>
<th width="10%" class="smallhead">Limitations</th>
</tr>
<tr>
    <td class="text"></td>
    <td class="text" style="text-align:center;">
    <label for="gplaccess"><input {if $gpl.adminpanel == 0}disabled="disabled"{/if} type="checkbox" value="1" id="gplaccess" onclick="all('gpl', 'access');"/></label>
    </td>
    <td class="text" style="text-align:center;">
    <label for="gpladd"><input {if $gpl.adminpanel == 0}disabled="disabled"{/if} type="checkbox" value="1" id="gpladd" onclick="all('gpl', 'add');"/></label>
    </td>
    <td class="text" style="text-align:center;">
    <label for="gpledit"><input {if $gpl.adminpanel == 0}disabled="disabled"{/if} type="checkbox" value="1" id="gpledit" onclick="all('gpl', 'edit');"/></label>
    </td>
    <td class="text" style="text-align:center;">
    <label for="gpldelete"><input {if $gpl.adminpanel == 0}disabled="disabled"{/if} type="checkbox" value="1" id="gpldelete" onclick="all('gpl', 'delete');"/></label>
    </td>
    <td class="text" style="text-align:center;">
    <label for="gplpub"><input {if $gpl.adminpanel == 0}disabled="disabled"{/if} type="checkbox" value="1" id="gplpub" onclick="all('gpl', 'pub');"/></label>
    </td>
    <td class="text" style="text-align:center;">
    <label for="gpllimit"><input {if $gpl.adminpanel == 0}disabled="disabled"{/if} type="checkbox" value="1" id="gpllimit" onclick="all('gpl', 'limit');"/></label>
    </td>
</tr>
{section name=module loop=$nummodules}
{assign var="modid" value=$modules[module].id}
<tr>
<td class="text"><div align="right">{$modules[module].name}<span class="hintanchor" title="{$modules[module].description}"><img src="{$tempdir}admin/images/information.png" alt="[i]"/></span></div></td>
<td class="text" style="text-align:center;"><input {if $modules[module].access == "notused" || $modules[module].access == "" || $gpl.adminpanel == 0}disabled="disabled"{/if} name="gpl_{$modules[module].id}_access" id="gpl_{$modules[module].id}_access" type="checkbox" value="1" {if $gpl.access.$modid == 1}checked="checked"{/if}/></td>
<td class="text" style="text-align:center;"><input {if $modules[module].add == "notused" || $modules[module].add == "" || $gpl.adminpanel == 0}disabled="disabled"{/if} name="gpl_{$modules[module].id}_add" id="gpl_{$modules[module].id}_add" type="checkbox" value="1"  {if $gpl.add.$modid == 1}checked="checked"{/if}/></td>
<td class="text" style="text-align:center;"><input {if $modules[module].edit == "notused" || $modules[module].edit == "" || $gpl.adminpanel == 0}disabled="disabled"{/if} name="gpl_{$modules[module].id}_edit" id="gpl_{$modules[module].id}_edit" type="checkbox" value="1"  {if $gpl.edit.$modid == 1}checked="checked"{/if}/></td>
<td class="text" style="text-align:center;"><input {if $modules[module].delete == "notused" || $modules[module].delete == "" || $gpl.adminpanel == 0}disabled="disabled"{/if} name="gpl_{$modules[module].id}_delete" id="gpl_{$modules[module].id}_delete" type="checkbox" value="1"  {if $gpl.delete.$modid == 1}checked="checked"{/if}/></td>
<td class="text" style="text-align:center;"><input {if $modules[module].publish == "notused" || $modules[module].publish == "" || $gpl.adminpanel == 0}disabled="disabled"{/if} name="gpl_{$modules[module].id}_pub" id="gpl_{$modules[module].id}_pub" type="checkbox" value="1"  {if $gpl.publish.$modid == 1}checked="checked"{/if}/></td>
<td class="text" style="text-align:center;"><input {if $modules[module].limit == "notused" || $modules[module].limit == "" || $gpl.adminpanel == 0}disabled="disabled"{/if} name="gpl_{$modules[module].id}_limit" id="gpl_{$modules[module].id}_limit" type="checkbox" value="1"  {if $gpl.limit.$modid == 1}checked="checked"{/if}/></td>
</tr>
{/section}
</table>
</div></div>
    <div align="center"><input type="submit" name="Submit" value="Submit" class="button" />
    <input name="Cancel" type="button" id="Cancel" value="Cancel" onclick="window.location='{$pagename}'" class="button" />
    </div>

</div></div></form>
{/if}
