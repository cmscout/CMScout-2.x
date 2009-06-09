{literal}
  <script type="text/javascript">
<!--
function confirmDelete(articleId) {
if (confirm("This will Remove the authorisation setting. Continue?"))
  {/literal}
document.location = "{$pagename}&action=delete&id=" + articleId;
}
//-->
</script>
<h2>Authorization Manager</h2>
{if $action == ""}
{if $addallowed}<div class="toplinks"><a href="{$pagename}&amp;action=new" title="New authorization"><img src="{$tempdir}admin/images/add.png" alt="New authorization" border="0" /></a>
</div>{/if}
{if $numauths > 0}
<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="table sortable-onload-2 rowstyle-alt paginate-15" id="sortTable">
<thead>
  <tr valign="top"> 
    <th width="8%" class="smallhead"></th>
    <th class="smallhead sortable" width="30%">Name</th>
    <th class="smallhead sortable">Type</th>
  </tr>
  </thead>
  <tbody>
 {section name=front loop=$numauths}
	  <tr class="text" valign="middle"> 
		<td class="text" ><div align="center">{if $editallowed}<a href="{$pagename}&amp;id={$auths[front].id}&amp;action=edit"><img src="{$tempdir}admin/images/edit.gif" border="0" alt="Edit Authorizations for {$auths[front].authname}" title="Edit Authorizations for {$auths[front].authname}" /></a>{else}<img src="{$tempdir}admin/images/edit_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;&nbsp;{if $deleteallowed}<a href="javascript:confirmDelete({$auths[front].id})"><img src="{$tempdir}admin/images/delete.gif" border="0" alt="Remove Authorizations for {$auths[front].authname}" title="Remove Authorizations for {$auths[front].authname}" /></a>{else}<img src="{$tempdir}admin/images/delete_grey.gif" border="0" alt="Deleting Disabled" title="Deleting Disabled" />{/if}</div></td>
		<td class="text"><div align="left">{$auths[front].authname}</div></td>
        <td class="text">{if $auths[front].type == 1}User Based{elseif $auths[front].type == 2}Group Based{else}Unknown. Possibly pre-CMScout 2.0{/if}</td>
      </tr>  
    {/section}
    </tbody>
</table>
{else}
<div align="center">No allow authorizations</div>
{/if}
{elseif $action=="new" || $action=="edit"}
{literal}
  <script type="text/javascript">
<!--
function checkAll(type, action) 
{
    switch (type)
    {
        case 'dynamic':
            {/literal}itemList = [{section name=dynamic loop=$numdynamic}'{$dynamic[dynamic].id}'{if $smarty.section.dynamic.iteration <$numdynamic},{/if}{/section}];
            number = {$numdynamic};{literal}
            break;
        case 'permissions':
            {/literal}itemList = [{section name=permissions loop=$numperms}'{$permissions[permissions].id}'{if $smarty.section.permissions.iteration <$numperms},{/if}{/section}];
            number = {$numperms};{literal}
            break;
        case 'static':
            {/literal}itemList = [{section name=static loop=$numstatic}'{$static[static].id}'{if $smarty.section.static.iteration <$numstatic},{/if}{/section}];
            number = {$numstatic};{literal}
            break;
        case 'subsites':
            {/literal}itemList = [{section name=subsite loop=$numsites}'{$subsites[subsite].id}'{if $smarty.section.subsite.iteration <$numsites},{/if}{/section}];
            number = {$numsites};{literal}
            break;
    }
    for (i=0;i<number;i++)
    {
        document.getElementById(type + itemList[i] + action).checked = document.getElementById(type+action+'_all').checked;
    }
}
//-->
</script>
  {/literal}
    <form name="form2" method="post" action="">
    <table style="width:500px" class="formlist" align="center">
    <tr><td class="text" align="center" colspan="2"><div align="center">
    <div class="field">
        <label for="name" class="label">For<img src="{$tempdir}admin/images/help.png" class="hintanchor" alt="Hint" title="For which group does this authorisation apply?" /></label><div class="inputboxwrapper"><select name="name" id="name" class="inputbox">
        <optgroup label="Users">
            {section name=user loop=$numusers}
                <option value="{$users[user].id}.user" {if $item.authname == $users[user].id && $item.type == 1}selected="selected"{/if}>{$users[user].uname}</option>
            {/section}
            </optgroup>
            <optgroup label="Groups">
            {section name=group loop=$numgroups}
                <option value="{$groups[group].id}.group" {if $item.authname == $groups[group].id && $item.type == 2}selected="selected"{/if}>{$groups[group].teamname}</option>
            {/section}
            </optgroup>
        </select></div>
</div>
            </div>
            </td>
    </tr>
          <tr>
          <td colspan="2" class="text">
                <div align="center">
<div id="navcontainer">
<h4 title="dtab">CMScout Pages</h4>
<div id="dtab" >
<table width="98%" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-21" id="sortTable">
<thead>
<tr>
    <th class="smallhead">Page</th>
    <th class="smallhead" width="50">Allow</th>
    <th class="smallhead" width="50">Ignore</th>
    <th class="smallhead" width="50">Disallow</th>
</tr>
</thead>
<tbody>
<tr class="text">
    <th class="smallhead">Select All</th>
    <th class="smallhead" style="text-align:center"><label for="dynamic_allow_all"><input type="radio" value="1" name="dynamic_all" id="dynamic_allow_all" onclick="checkAll('dynamic', '_allow')" /></label></th>
    <th class="smallhead" style="text-align:center"><label for="dynamic_ignore_all"><input type="radio" value="0" name="dynamic_all" id="dynamic_ignore_all" onclick="checkAll('dynamic', '_ignore')"/></label></th>
    <th class="smallhead" style="text-align:center"><label for="dynamic_deny_all"><input type="radio" value="-1" name="dynamic_all" id="dynamic_deny_all" onclick="checkAll('dynamic', '_deny')"/></label></th>
</tr>
{section name=dynamic loop=$numdynamic}
            {assign var="id" value=$dynamic[dynamic].id}
            <tr class="text">
                <td class="text">{$dynamic[dynamic].name}</td>
                <td class="text" style="text-align:center"><label for="dynamic{$dynamic[dynamic].id}_allow"><input type="radio" value="1" name="dynamic[{$dynamic[dynamic].id}]" id="dynamic{$dynamic[dynamic].id}_allow" {if $item.dynamic.$id == 1}checked="checked"{/if} /></label></td>
                <td class="text" style="text-align:center"><label for="dynamic{$dynamic[dynamic].id}_ignore"><input type="radio" value="0" name="dynamic[{$dynamic[dynamic].id}]" id="dynamic{$dynamic[dynamic].id}_ignore" {if $item.dynamic.$id == 0}checked="checked"{/if} /></label></td>
                <td class="text" style="text-align:center"><label for="dynamic{$dynamic[dynamic].id}_deny"><input type="radio" value="-1" name="dynamic[{$dynamic[dynamic].id}]" id="dynamic{$dynamic[dynamic].id}_deny" {if $item.dynamic.$id ==-1}checked="checked"{/if} /></label></td>
            </tr>
{/section} </tbody>
</table> 
</div>         
    
<h4 title="permi">User Contributions</h4>    
<div id="permi" >
<table width="98%" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-21" id="sortTable2">
<thead>
<tr>
    <th class="smallhead">Page</th>
    <th class="smallhead" width="50">Allow</th>
    <th class="smallhead" width="50">Ignore</th>
    <th class="smallhead" width="50">Disallow</th>
</tr>
</thead>
<tbody>
<tr class="text">
    <th class="smallhead">Select All</th>
    <th class="smallhead" style="text-align:center"><label for="permissions_allow_all"><input type="radio" value="1" name="permissions_all" id="permissions_allow_all" onclick="checkAll('permissions', '_allow')" /></label></th>
    <th class="smallhead" style="text-align:center"><label for="permissions_ignore_all"><input type="radio" value="0" name="permissions_all" id="permissions_ignore_all" onclick="checkAll('permissions', '_ignore')"/></label></th>
    <th class="smallhead" style="text-align:center"><label for="permissions_deny_all"><input type="radio" value="-1" name="permissions_all" id="permissions_deny_all" onclick="checkAll('permissions', '_deny')"/></label></th>
</tr>
{section name=permissions loop=$numperms}
            {assign var="id" value=$permissions[permissions].id}
            <tr class="text">
                <td class="text">{$permissions[permissions].name}</td>
                <td class="text" style="text-align:center"><label for="permissions{$permissions[permissions].id}_allow"><input type="radio" value="1" name="permissions[{$permissions[permissions].id}]" id="permissions{$permissions[permissions].id}_allow" {if $item.permission.$id == 1}checked="checked"{/if} /></label></td>
                <td class="text" style="text-align:center"><label for="permissions{$permissions[permissions].id}_ignore"><input type="radio" value="0" name="permissions[{$permissions[permissions].id}]" id="permissions{$permissions[permissions].id}_ignore" {if $item.permission.$id == 0}checked="checked"{/if} /></label></td>
                <td class="text" style="text-align:center"><label for="permissions{$permissions[permissions].id}_deny"><input type="radio" value="-1" name="permissions[{$permissions[permissions].id}]" id="permissions{$permissions[permissions].id}_deny" {if $item.permission.$id ==-1}checked="checked"{/if} /></label></td>
            </tr>
{/section} </tbody>
</table>      
</div>
    
<h4 title="content">Content</h4>    
<div id="content" >
{if $numstatic > 0}
<table width="98%" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-21" id="sortTable3">
<thead>
<tr>
    <th class="smallhead">Page</th>
    <th class="smallhead" width="50">Allow</th>
    <th class="smallhead" width="50">Ignore</th>
    <th class="smallhead" width="50">Disallow</th>
</tr>
</thead>
<tbody>
<tr class="text">
    <th class="smallhead">Select All</th>
    <th class="smallhead" style="text-align:center"><label for="static_allow_all"><input type="radio" value="1" name="static_all" id="static_allow_all" onclick="checkAll('static', '_allow')" /></label></th>
    <th class="smallhead" style="text-align:center"><label for="static_ignore_all"><input type="radio" value="0" name="static_all" id="static_ignore_all" onclick="checkAll('static', '_ignore')"/></label></th>
    <th class="smallhead" style="text-align:center"><label for="static_deny_all"><input type="radio" value="-1" name="static_all" id="static_deny_all" onclick="checkAll('static', '_deny')"/></label></th>
</tr>
{section name=static loop=$numstatic}
            {assign var="id" value=$static[static].id}
            <tr class="text">
                <td class="text">{if $static[static].friendly == ""}{$static[static].name}{else}{$static[static].friendly}{/if}</td>
                <td class="text" style="text-align:center"><label for="static{$static[static].id}_allow"><input type="radio" value="1" name="static[{$static[static].id}]" id="static{$static[static].id}_allow" {if $item.static.$id == 1}checked="checked"{/if} /></label></td>
                <td class="text" style="text-align:center"><label for="static{$static[static].id}_ignore"><input type="radio" value="0" name="static[{$static[static].id}]" id="static{$static[static].id}_ignore" {if $item.static.$id == 0}checked="checked"{/if} /></label></td>
                <td class="text" style="text-align:center"><label for="static{$static[static].id}_deny"><input type="radio" value="-1" name="static[{$static[static].id}]" id="static{$static[static].id}_deny" {if $item.static.$id ==-1}checked="checked"{/if} /></label></td>
            </tr>
{/section} </tbody>
</table>  
{else}
No content pages available
{/if}       
</div> 

<h4 title="subsite">Subsites</h4>
<div id="subsite" >
{if $numsites > 0}
<table width="98%" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-21" id="sortTable4">
<thead>
<tr>
    <th class="smallhead">Page</th>
    <th class="smallhead" width="50">Allow</th>
    <th class="smallhead" width="50">Ignore</th>
    <th class="smallhead" width="50">Disallow</th>
</tr>
</thead>
<tbody>
<tr class="text">
    <th class="smallhead">Select All</th>
    <th class="smallhead" style="text-align:center"><label for="subsites_allow_all"><input type="radio" value="1" name="subsites_all" id="subsites_allow_all" onclick="checkAll('subsites', '_allow')" /></label></th>
    <th class="smallhead" style="text-align:center"><label for="subsites_ignore_all"><input type="radio" value="0" name="subsites_all" id="subsites_ignore_all" onclick="checkAll('subsites', '_ignore')"/></label></th>
    <th class="smallhead" style="text-align:center"><label for="subsites_deny_all"><input type="radio" value="-1" name="subsites_all" id="subsites_deny_all" onclick="checkAll('subsites', '_deny')"/></label></th>
</tr>
{section name=subsites loop=$numsites}
            {assign var="id" value=$subsites[subsites].id}
            <tr class="text">
                <td class="text">{$subsites[subsites].name}</td>
                <td class="text" style="text-align:center"><label for="subsites{$subsites[subsites].id}_allow"><input type="radio" value="1" name="subsites[{$subsites[subsites].id}]" id="subsites{$subsites[subsites].id}_allow" {if $item.subsites.$id == 1}checked="checked"{/if} /></label></td>
                <td class="text" style="text-align:center"><label for="subsites{$subsites[subsites].id}_ignore"><input type="radio" value="0" name="subsites[{$subsites[subsites].id}]" id="subsites{$subsites[subsites].id}_ignore" {if $item.subsites.$id == 0}checked="checked"{/if} /></label></td>
                <td class="text" style="text-align:center"><label for="subsites{$subsites[subsites].id}_deny"><input type="radio" value="-1" name="subsites[{$subsites[subsites].id}]" id="subsites{$subsites[subsites].id}_deny" {if $item.subsites.$id ==-1}checked="checked"{/if} /></label></td>
            </tr>
{/section} </tbody>
</table>  
{else}
No sub sites available
{/if}         
</div> 
</div></div>
         </td>
         </tr>
        <tr>
        <td colspan="2" align="center">
        <input type="submit" name="Submit" value="Submit" class="button" />
         <input name="Cancel" type="button" id="Cancel" value="Cancel" onclick="window.history.go(-1);" class="button" />
       </td></tr>
      </table>

    </form>
{/if}
