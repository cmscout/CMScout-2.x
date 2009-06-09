<h2>Forum Manager</h2>
{if $action == ""}
{if $addallowed}<div class="toplinks"><a href="{$pagename}&amp;action=add" title="Add Category"><img src="{$tempdir}admin/images/add.png" alt="Add Category" border="0" /></a>
</div>{/if}
{if $numcats > 0}
<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-15" id="sortTable3">
<thead>
	  <tr>
		<th width="10%" class="smallhead"></th>
		<th class="smallhead">Category</th>
        <th class="smallhead" width="10%">Position</th>
	  </tr> 
      </thead>
      <tbody>
	 {section name=catloop loop=$numcats}
		 <tr class="text">
			<td class="text" style="text-align:center;"><a href="{$pagename}&amp;action=view&amp;cid={$cats[catloop].id}"><img src="{$tempdir}admin/images/mod.gif" border="0" alt="View {$cats[catloop].name} forums" title="View {$cats[catloop].name} forums" /></a>&nbsp;&nbsp;{if $editallowed}<a href="{$pagename}&amp;action=edit&amp;cid={$cats[catloop].id}"><img src="{$tempdir}admin/images/edit.gif" border="0" alt="Edit {$cats[catloop].name}" title="Edit {$cats[catloop].name}" /></a>{else}<img src="{$tempdir}admin/images/edit_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;&nbsp;{if $deleteallowed}<a href="{$pagename}&amp;action=delete&amp;cid={$cats[catloop].id}"><img src="{$tempdir}admin/images/delete.gif" border="0" alt="Delete {$cats[catloop].name}" title="Delete {$cats[catloop].name}" /></a>{else}<img src="{$tempdir}admin/images/delete_grey.gif" border="0" alt="Deleting Disabled" title="Deleting Disabled" />{/if}</td>
			<td class="text">{$cats[catloop].name}</td>
            <td class="text" style="text-align:center;">{if $smarty.section.catloop.iteration != 1}{if $editallowed}<a href="{$pagename}&amp;action=moveup&amp;cid={$cats[catloop].id}"><img src="{$tempdir}admin/images/up.gif" border="0" alt="Up" title="Move Up" /></a>{else}<img src="{$tempdir}admin/images/up_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}{/if}{if $smarty.section.catloop.iteration != $numcats}{if $editallowed}<a href="{$pagename}&amp;action=movedown&amp;cid={$cats[catloop].id}"><img src="{$tempdir}admin/images/down.gif" border="0" alt="Up" title="Move Down" /></a>{else}<img src="{$tempdir}admin/images/down_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}{/if}</td>
	  </tr>
	  {/section}
      </tbody>
	</table>
    {else}
<div align="center">No forum categories</div>
{/if}
  {elseif $action == "view"}
  <h3 style="text-align:center">Forums in {$catinfo.name}</h3>
  <div class="toplinks">{if $addallowed}<a href="{$pagename}&amp;action=addforum&amp;cid={$catinfo.id}" title="Add forum"><img src="{$tempdir}admin/images/add.png" alt="Add forum" border="0" /></a>
{/if}<a href="{$pagename}" title="Back"><img src="{$tempdir}admin/images/back.png" alt="Back" border="0" /></a></div>
  {if $numforums > 0}
{literal}
  <script type="text/javascript">
<!--
function showitems(id)
{
	var item = null;
    var item2 = null;

    item = document.getElementById('sub' + id);
    item2 = document.getElementById('subhide' + id);

    if (item.style.display == "none")
    {
        item.style.display = "";
       item2.style.display = "none";
        document.getElementById('close'+id).style.display = "block";
        document.getElementById('open'+id).style.display = "none";
    }
    else
    {
        item.style.display = "none";
       item2.style.display = "";
    document.getElementById('close'+id).style.display = "none";
    document.getElementById('open'+id).style.display = "block";
    }
}
//-->
  </script>
  {/literal}
  
<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-15" id="sortTable3">
<thead>
	  <tr>
		<th width="13%" class="smallhead"></th>
		<th class="smallhead">Name</th>
        <th width="10%" class="smallhead">Position</th>
	  </tr> 
      </thead><tbody>
	 {section name=forums loop=$numforums}
		 <tr class="text">
			<td class="text" style="text-align:center;">{if $editallowed}<a href="{$pagename}&amp;action=editforum&amp;fid={$forums[forums].id}&amp;cid={$catinfo.id}"><img src="{$tempdir}admin/images/edit.gif" border="0" alt="Edit {$forums[forums].name}" title="Edit {$forums[forums].name}" /></a>{else}<img src="{$tempdir}admin/images/edit_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;&nbsp;{if $editallowed}<a href="{$pagename}&amp;action=permissions&amp;fid={$forums[forums].id}&amp;cid={$catinfo.id}"><img src="{$tempdir}admin/images/key.gif" border="0" alt="Edit permissions for {$forums[forums].name}" title="Edit permissions for {$forums[forums].name}" /></a>{else}<img src="{$tempdir}admin/images/key_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;&nbsp;{if $editallowed}<a href="{$pagename}&amp;action=moderator&amp;fid={$forums[forums].id}&amp;cid={$catinfo.id}"><img src="{$tempdir}admin/images/moderator.png" border="0" alt="Edit moderators for {$forums[forums].name}" title="Edit moderators for {$forums[forums].name}" /></a>{else}<img src="{$tempdir}admin/images/moderator_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;&nbsp;{if $deleteallowed}<a href="{$pagename}&amp;action=deleteforum&amp;fid={$forums[forums].id}"><img src="{$tempdir}admin/images/delete.gif" border="0" alt="Delete {$forums[forums].name}" title="Delete {$forums[forums].name}" /></a>{else}<img src="{$tempdir}admin/images/delete_grey.gif" border="0" alt="Deleting Disabled" title="Deleting Disabled" />{/if}</td>
			<td class="text"><span class="hintanchor" title="Description :: {if $forums[forums].desc}{$forums[forums].desc}{else}No description{/if}"><img src="{$tempdir}admin/images/information.png" alt="[i]"/></span>{$forums[forums].name}</td>
            <td class="text" style="text-align:center;">{if $smarty.section.forums.iteration != 1}{if $editallowed}<a href="{$pagename}&amp;action=movefup&amp;fid={$forums[forums].id}&amp;cid={$catinfo.id}"><img src="{$tempdir}admin/images/up.gif" border="0" alt="Up" title="Move Up" /></a>{else}<img src="{$tempdir}admin/images/up_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;{/if}{if $smarty.section.forums.iteration != $numforums}{if $editallowed}<a href="{$pagename}&amp;action=movefdown&amp;fid={$forums[forums].id}&amp;cid={$catinfo.id}"><img src="{$tempdir}admin/images/down.gif" border="0" alt="Up" title="Move Down" /></a>{else}<img src="{$tempdir}admin/images/down_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}{/if}</td>
	  </tr>
      {if $forums[forums].numsubs > 0}
  <tr>
   <td class="text" valign="top">
   <div align="center"><div id="open{$item[items].id}"><a href="javascript:showitems('{$item[items].id}');" class="hintanchor2"><img src="{$tempdir}admin/images/close.png" title="[+]" border="0"/></a></div><div id="close{$item[items].id}" style="display:none;"><a href="javascript:showitems('{$item[items].id}');" class="hintanchor2"><img src="{$tempdir}admin/images/open.png" title="[-]" border="0"/></a></div></div></td>
   <td colspan="3" class="text">
   <div id="subhide{$item[items].id}" style="display:'';">
    <div align="left">&nbsp;{$forums[forums].name} has <b>{$forums[forums].numsubs}</b> sub forum(s).</div>
   </div>
   <div id="sub{$item[items].id}" style="display:none;">
    <strong><div align="left">Sub forum(s)</div></strong>
    <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-15" id="sortTable3">
<thead>
      <tr>
    <th width="12%" scope="col" class="smallhead"></th>
    <th scope="col" class="smallhead">Name</th>
    <th width="6%" scope="col" class="smallhead">Position</th>
  </tr></thead><tbody>
      	 {section name=subforums loop=$forums[forums].numsubs}
             <tr class="text">
                <td class="text" style="text-align:center;">{if $editallowed}<a href="{$pagename}&amp;action=editforum&amp;fid={$forums[forums].subforums[subforums].id}&amp;cid={$catinfo.id}"><img src="{$tempdir}admin/images/edit.gif" border="0" alt="Edit {$forums[forums].subforums[subforums].name}" title="Edit {$forums[forums].subforums[subforums].name}" /></a>{else}<img src="{$tempdir}admin/images/edit_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;&nbsp;{if $editallowed}<a href="{$pagename}&amp;action=permissions&amp;fid={$forums[forums].subforums[subforums].id}&amp;cid={$catinfo.id}"><img src="{$tempdir}admin/images/key.gif" border="0" alt="Edit permissions for {$forums[forums].subforums[subforums].name}" title="Edit permissions for {$forums[forums].subforums[subforums].name}" /></a>{else}<img src="{$tempdir}admin/images/key_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;&nbsp;{if $editallowed}<a href="{$pagename}&amp;action=moderator&amp;fid={$forums[forums].subforums[subforums].id}&amp;cid={$catinfo.id}"><img src="{$tempdir}admin/images/moderator.png" border="0" alt="Edit Moderators for {$forums[forums].subforums[subforums].name}" title="Edit Moderators for {$forums[forums].subforums[subforums].name}" /></a>{else}<img src="{$tempdir}admin/images/moderator_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}&nbsp;&nbsp;{if $deleteallowed}<a href="{$pagename}&amp;action=deleteforum&amp;fid={$forums[forums].subforums[subforums].id}"><img src="{$tempdir}admin/images/delete.gif" border="0" alt="Delete {$forums[forums].subforums[subforums].name}" title="Delete {$forums[forums].subforums[subforums].name}" /></a>{else}<img src="{$tempdir}admin/images/delete_grey.gif" border="0" alt="Deleting Disabled" title="Deleting Disabled" />{/if}</td>
                <td class="text"><span class="hintanchor" title="Description :: {if $forums[forums].subforums[subforums].desc}{$forums[forums].subforums[subforums].desc}{else}No description{/if}"><img src="{$tempdir}admin/images/information.png" alt="[i]"/></span>{$forums[forums].subforums[subforums].name}</td>
                <td class="text" style="text-align:center;">{if $smarty.section.subforums.iteration != 1}{if $editallowed}<a href="{$pagename}&amp;action=movefup&amp;fid={$forums[forums].subforums[subforums].id}&amp;cid={$catinfo.id}"><img src="{$tempdir}admin/images/up.gif" border="0" alt="Up" title="Move Up" /></a>{else}<img src="{$tempdir}admin/images/up_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}{/if}{if $smarty.section.subforums.iteration != $forums[forums].numsubs}{if $editallowed}<a href="{$pagename}&amp;action=movefdown&amp;fid={$forums[forums].subforums[subforums].id}&amp;cid={$catinfo.id}"><img src="{$tempdir}admin/images/down.gif" border="0" alt="Up" title="Move Down" /></a>{else}<img src="{$tempdir}admin/images/down_grey.gif" border="0" alt="Editing Disabled" title="Editing Disabled" />{/if}{/if}</td>
          </tr>
          {/section}</tbody>
   </table>
   </div>
   </td>
   </tr>      
      {/if}
	  {/section}
      </tbody>
	</table>
    {else}
<div align="center">No forums in {$catinfo.name}</div>
{/if}
  {elseif $action=="addforum" || $action=="editforum"}
  <form method="post"  name="form1" onsubmit="return checkForm([['name','text',true,0,0,'']]);">
      <div align="center">
      <fieldset class="formlist" style="width:100%">
      <legend>{if $action=="addforum"}New{else}Edit{/if} Forum</legend>
      <div class="field">
      <label for="name" class="label">Name of forum<span class="hintanchor" title="Name of the forum."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
      <div class="inputboxwrapper"><input name="name" type="text" id="name" size="60" maxlength="100" value="{$forum.name}" class="inputbox" onblur="checkElement('name', 'text', true, 0, 0, '');" /><br /><span class="fieldError" id="nameError">Required</span></div><br />

      <label for="name" class="label">Description<span class="hintanchor" title="Short description of the forum. This can be forum rules, how to get access to this forum, etc."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
      <div class="inputboxwrapper"><input name="desc" type="text" id="desc" size="60" maxlength="255" value="{$forum.desc}" class="inputbox" /></div><br />

    {if $numparents > 0}
    <label for="name" class="label">Parent<span class="hintanchor" title="If this forum is a subforum, select the parent forum here."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper"><select name="parent" id="parent" class="inputbox">
    <option value="0" {if $forum.parent == '' || $forum.parent == 'top'}selected="selected"{/if}>Not a subforum</option>
    {section name=parents loop=$numparents}
     {if $forum.id != $parent[parents].id}<option value="{$parent[parents].id}" {if $forum.parent == $parent[parents].id}selected="selected"{/if}>{$parent[parents].name}</option>{/if}
    {/section}
    </select></div><br />
    {/if}
    
    {if $numforums > 0}
    <label for="name" class="label">Copy Permissions<span class="hintanchor" title="You can copy the permissions from another forum here. This will overwrite any permissions this forum may have."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper"><select name="permissions" id="permissions" class="inputbox">
    <option value="0" selected="selected">Don't copy permissions</option>
    {section name=forums loop=$numforums}
        {if $forum.id != $forums[forums].id}<option value="{$forums[forums].id}">{$forums[forums].name}</option>{/if}
    {/section}
    </select></div><br />
    {/if}
    
    {if $numcats > 0}    
    <label for="name" class="label">Move forum<span class="hintanchor" title="Move this forum to another category. If the forum is a subforum, this will remove it's subforum status."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper"><select name="move" id="move" class="inputbox">
    <option value="0" selected="selected">Don't move</option>
    {section name=cats loop=$numcats}
        {if $forum.cat != $cats[cats].id}<option value="{$cats[cats].id}">{$cats[cats].name}</option>{/if}
    {/section}
    </select></div><br />
    {/if}
    
    <label for="name" class="label">Minimum Post Count<span class="hintanchor" title="The number of posts that a user is required to have before they have access to this forum."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper"><input name="limit" type="text" id="limit" size="10" maxlength="10" value="{$forum.limit}" class="inputbox" /></div><br />

</div>
   <div class="submitWrapper"> <input type="submit" name="Submit" value="Submit" class="button" />
		<input name="Cancel" type="button" id="Cancel" value="Cancel" onclick="history.go(-1);" class="button" /></div>
        </fieldset>
</form>
{elseif $action=="permissions"}
  
  <script type="text/javascript">
   {literal}
   function all(what)
   {
      if (document.getElementById(what).checked == true)
      {
       {/literal} 
        {section name=groups loop=$numgroups}
            document.getElementById(what + '{$groups[groups].id}').checked=true;
        {/section}
            document.getElementById(what + 'Guest').checked=true;
        {literal}
      }
      else if (document.getElementById(what).checked == false)
      {
       {/literal} 
        {section name=groups loop=$numgroups}
            document.getElementById(what + '{$groups[groups].id}').checked=false;
        {/section}
            document.getElementById(what + 'Guest').checked=false;
        {literal}
      }

   }
   {/literal}
  </script>
  <form action="{$editFormAction}" method="post"  name="form1">
      <div align="center">     
      <fieldset class="formlist"> 
      <legend>Permisions for <em>{$forum.name}</em></legend>
    <table width="100%" cellspacing="1" cellpadding="2" class="table" align="center">
        <tr valign="top">
         <th class="smallhead">
         </th>
          <th class="smallhead" width="11%">
           New Topic<br /><span class="hintanchor" title="Can group members post new topics"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span>
          </th>
          <th class="smallhead" width="11%">
           Reply to topic<br /><span class="hintanchor" title="Can group members reply to topics"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span>
          </th>          
          <th class="smallhead" width="11%">
           Edit Own Posts<br /><span class="hintanchor" title="Can group members edit their own posts"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span>
          </th>         
          <th class="smallhead" width="11%">
           Delete Own Posts<br /><span class="hintanchor" title="Can group members delete their own posts"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span>
          </th>                   
          <th class="smallhead" width="11%">
           View Forum<br /><span class="hintanchor" title="Can group members view topics"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span>
          </th>          
          <th class="smallhead" width="11%">
           Read posts in forum<br /><span class="hintanchor" title="Can group members read posts"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span>
          </th>  
          <th class="smallhead" width="11%">
           Sticky Topics<br /><span class="hintanchor" title="Can group members mark topics as 'sticky'"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span>
          </th> 
          <th class="smallhead" width="11%">
           Announcement<br /><span class="hintanchor" title="Can group members mark topics as a announcement"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span>
          </th> 
          <th class="smallhead" width="11%">
           Add Poll<br /><span class="hintanchor" title="Can group members add a poll to a topic"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span>
          </th>           
        </tr>
        <tr>
            <th class="smallhead"></td>
            <th class="smallhead" style="text-align:center;">
                <input type="checkbox" onclick="all('newtopic');" id="newtopic" />
            </td>
            <th class="smallhead" style="text-align:center;">
                <input type="checkbox" onclick="all('reply');" id="reply"/>
            </td>
            <th class="smallhead" style="text-align:center;">
                <input type="checkbox" onclick="all('edit');" id="edit"/>
            </td>
            <th class="smallhead" style="text-align:center;">
                <input type="checkbox" onclick="all('delete');" id="delete"/>
            </td>
            <th class="smallhead" style="text-align:center;">
                <input type="checkbox" onclick="all('view');" id="view"/>
            </td>
            <th class="smallhead" style="text-align:center;">
                <input type="checkbox" onclick="all('read');" id="read"/>
            </td>
            <th class="smallhead" style="text-align:center;">
                <input type="checkbox" onclick="all('sticky');" id="sticky"/>
            </td>
            <th class="smallhead" style="text-align:center;">
                <input type="checkbox" onclick="all('announce');" id="announce"/>
            </td>        
            <th class="smallhead" style="text-align:center;">
                <input type="checkbox" onclick="all('poll');" id="poll"/>
            </td>
        </tr>
        <tr>
         <td class="text">
          Guest
          {assign var="guestid" value=-1}
         </td>
          <td class="text" style="text-align:center;">
           <input type="checkbox" name="newtopic[-1]" id="newtopicGuest" value="1" {if $auths.new.$guestid == 1}checked="checked"{/if} class="inputbox" />
          </td>
          <td class="text" style="text-align:center;">
           <input type="checkbox" name="reply[-1]" id="replyGuest" value="1" {if $auths.reply.$guestid == 1}checked="checked"{/if} class="inputbox" />
          </td>
          <td class="text" style="text-align:center;">
           <input type="checkbox" name="edit[-1]" id="editGuest" value="1" {if $auths.edit.$guestid == 1}checked="checked"{/if} class="inputbox" />
          </td>
          <td class="text" style="text-align:center;">
           <input type="checkbox" name="delete[-1]" id="deleteGuest" value="1" {if $auths.delete.$guestid == 1}checked="checked"{/if} class="inputbox" />
          </td>
          <td class="text" style="text-align:center;">
           <input type="checkbox" name="view[-1]" id="viewGuest" value="1" {if $auths.view.$guestid == 1}checked="checked"{/if} class="inputbox" />
          </td>
          <td class="text" style="text-align:center;">
           <input type="checkbox" name="read[-1]" id="readGuest" value="1" {if $auths.read.$guestid == 1}checked="checked"{/if} class="inputbox" /> 
          </td>
          <td class="text" style="text-align:center;">
           <input type="checkbox" name="sticky[-1]" id="stickyGuest" value="1" {if $auths.sticky.$guestid == 1}checked="checked"{/if} class="inputbox" /> 
          </td>
          <td class="text" style="text-align:center;">
           <input type="checkbox" name="announce[-1]" id="announce_Guest" value="1" {if $auths.announce.$guestid == 1}checked="checked"{/if} class="inputbox" /> 
          </td>
          <td class="text" style="text-align:center;">
           <input type="checkbox" name="poll[-1]" id="poll_Guest" value="1" {if $auths.poll.$guestid == 1}checked="checked"{/if} class="inputbox" /> 
          </td>
        </tr>
        {section name=groups loop=$numgroups}
        {assign var="id" value=$groups[groups].id}
        <tr>
         <td class="text">
          {$groups[groups].teamname}
         </td>
          <td class="text" style="text-align:center;">
           <input type="checkbox" name="newtopic[{$groups[groups].id}]" id="newtopic{$groups[groups].id}" value="1" {if $auths.new.$id == 1}checked="checked"{/if} class="inputbox" />
          </td>
          <td class="text" style="text-align:center;">
           <input type="checkbox" name="reply[{$groups[groups].id}]" id="reply{$groups[groups].id}" value="1" {if $auths.reply.$id == 1}checked="checked"{/if} class="inputbox" />
          </td>
          <td class="text" style="text-align:center;">
           <input type="checkbox" name="edit[{$groups[groups].id}]" id="edit{$groups[groups].id}" value="1" {if $auths.edit.$id == 1}checked="checked"{/if} class="inputbox" />
          </td>
          <td class="text">
           <input type="checkbox" name="delete[{$groups[groups].id}]" id="delete{$groups[groups].id}" value="1" {if $auths.delete.$id == 1}checked="checked"{/if} class="inputbox" />
          </td>
          <td class="text" style="text-align:center;">
           <input type="checkbox" name="view[{$groups[groups].id}]" id="view{$groups[groups].id}" value="1" {if $auths.view.$id == 1}checked="checked"{/if} class="inputbox" />
          </td>
          <td class="text" style="text-align:center;">
           <input type="checkbox" name="read[{$groups[groups].id}]" id="read{$groups[groups].id}" value="1" {if $auths.read.$id == 1}checked="checked"{/if} class="inputbox" />
          </td>
          <td class="text" style="text-align:center;">
           <input type="checkbox" name="sticky[{$groups[groups].id}]" id="sticky{$groups[groups].id}" value="1" {if $auths.sticky.$id == 1}checked="checked"{/if} class="inputbox" />
          </td>
           <td class="text">
           <input type="checkbox" name="announce[{$groups[groups].id}]" id="announce{$groups[groups].id}" value="1" {if $auths.announce.$id == 1}checked="checked"{/if} class="inputbox" />
          </td>
          <td class="text" style="text-align:center;">
           <input type="checkbox" name="poll[{$groups[groups].id}]" id="poll{$groups[groups].id}" value="1" {if $auths.poll.$id == 1}checked="checked"{/if} class="inputbox" />
          </td>
        </tr>
        {/section}
    </table>    
   <div class="submitWrapper"> <input type="submit" name="Submit" value="Submit" class="button" />
		<input name="Cancel" type="button" id="Cancel" value="Cancel" onclick="history.go(-1);" class="button" /></div>
        </fieldset></div>
</form>    
{elseif $action=="add" || $action == "edit"}
<div align="center">
<form name="form2" method="post"  onsubmit="return checkForm([['catname','text',true,0,0,'']]);">
<fieldset class="formlist">
<legend>{if $action=="add"}New{else}Edit{/if} Category</legend>
<div class="field">
    <label for="catname" class="label">Category Name<span class="hintanchor" title="The name of the category"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper"><input name="catname" id="catname" size="50" value="{$cat.name}" class="inputbox" onblur="checkElement('catname', 'text', true, 0, 0, '');" /><br /><span class="fieldError" id="catnameError">Required</span></div><br />
</div>
<div class="submitWrapper">
    <input type="submit" name="Submit" value="Submit" class="button" id="Submit" />
    <input name="Cancel" type="button" id="Cancel" value="Cancel" onclick="history.go(-1);" class="button" /></div>
</fieldset>
</form></div>
{elseif $action=="deleteforum"}
{if $numtopics > 0}
<div align="center">
 <form method="post" action="{$editFormAction}" name="form">
 <fieldset class="formlist">
 <legend>There are still topics attached to {$forum.name}</legend>
 <div class="field">
 <label for="forum" class="label">Action for topics</label>
 <div class="inputboxwrapper"><select name="forum" id="forum" class="inputbox">
  <option value="del">Delete Them</option>
  {section name=loop loop=$numforums}
   {if $forums[loop].id != $forum.id}<option value="{$forums[loop].id}">Move to {$forums[loop].name}</option>{/if}
  {/section}
 </select></div><br /></div>
 <div class="submitWrapper"><input type="Submit" name="submit" id="submit" value="Delete" class="button" />&nbsp;<input type="button" value="Cancel" id="cancel" name="cancel" onclick="window.location = '{$pagename}&amp;action=view&amp;cid={$forum.cat}'" class="button" /></div>
 </fieldset>
 </form></div>
{else}
<div align="center">
 <form method="post" action="{$editFormAction}" name="form">
 <fieldset class="formlist">
 <legend>Delete Forum</legend>
 <div class="field" style="text-align:center">
    <b>Are you sure you want to delete {$forum.name}?</b></div>
 <div class="submitWrapper"><input type="Submit" name="submit" id="submit" value="Delete" class="button" />&nbsp;<input type="button" value="Cancel" id="cancel" name="cancel" onclick="window.location = '{$pagename}&amp;action=view&amp;cid={$forum.cat}'" class="button" /></div>
 </fieldset>
 </form></div>
 {/if}
{elseif $action=="delete"}
{if $numforums > 0}
<div align="center">
 <form method="post" action="{$editFormAction}" name="form">
 <fieldset class="formlist">
 <legend>There are still forums attached to {$cat.name}</legend>
 <div class="field">
 <label for="forum" class="label">Action for forums</label>
 <div class="inputboxwrapper"><select name="forum" id="forum" class="inputbox">
  <option value="del">Delete them and their topics</option>
  {section name=loop loop=$numcats}
   {if $cats[loop].id != $cat.id}<option value="{$cats[loop].id}">Move to {$cats[loop].name}</option>{/if}
  {/section}
 </select></div><br /></div>
 <div class="submitWrapper"><input type="Submit" name="submit" id="submit" value="Delete" class="button" />&nbsp;<input type="button" value="Cancel" id="cancel" name="cancel" onclick="window.location = '{$pagename}'" class="button" /></div>
 </fieldset>
 </form></div>
{else}
<div align="center">
 <form method="post" action="{$editFormAction}" name="form">
 <fieldset class="formlist">
 <legend>Delete Forum</legend>
 <div class="field" style="text-align:center">
    <b>Are you sure you want to delete {$cat.name}?</b></div>
 <div class="submitWrapper"><input type="Submit" name="submit" id="submit" value="Delete" class="button" />&nbsp;<input type="button" value="Cancel" id="cancel" name="cancel" onclick="window.location = '{$pagename}'" class="button" /></div>
 </fieldset>
 </form></div>
 {/if}
   {elseif $action == "moderator"}
   <div align="center"><div class="formlist">
   <h3 style="text-align:center;">Moderators for {$forum.name}</h3>
<table width="100%" border="0" cellspacing="1" cellpadding="4" align="center" class="table">
	  {if $nummods > 0}
      <tr>
		<th width="5%" class="smallhead"></th>
		<th class="smallhead">Moderator</th>
	  </tr> 
	 {section name=mod loop=$nummods}
		 <tr>
			<td class="text" style="text-align:center;"><a href="{$pagename}&amp;action=deletemod&amp;id={$mods[mod].id}&amp;fid={$forum.id}&amp;cid={$forum.cat}"><img src="{$tempdir}admin/images/delete.gif" border="0" alt="Delete moderator" title="Delete moderator" /></a></td>
			<td class="text"><div align="left">{$mods[mod].name}</div></td>
	  </tr>
	  {/section}
      {/if}
      <tr>
		<th class="smallhead" colspan="2">Add user/group</th>
	  </tr> 
      <tr>
		<td colspan="2" class="text"> 
        <form method="post" action="{$editFormAction}" name="form">
        <div class="field">
        <label for="user" class="label">Who to add</label>
        <div class="inputboxwrapper">
        <select name="user" id="user" class="inputbox">
        <optgroup label="Users">
            {section name=thing loop=$numusers}
                <option value="u_{$users[thing].id}">{$users[thing].uname}</option>
            {/section}
            </optgroup>
            <optgroup label="Groups">
            {section name=thing loop=$numgroups}
                <option value="g_{$groups[thing].id}">{$groups[thing].teamname}</option>
            {/section}
            </optgroup>
        </select></div><br />
        </div>
        <div class="submitWrapper">
		<input type="Submit" name="submit" id="submit" value="Add" class="button" /></div></form></td>
	  </tr> 
	</table><br />
    <div align="center"><input type="button" class="button" onclick="window.location = '{$pagename}&action=view&cid={$forum.cat}';" value="Back to forum view" /></div>
</div></div>
{/if}
