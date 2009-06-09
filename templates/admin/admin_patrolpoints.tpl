<h2>Patrol Points</h2>
{if $numpoints > 0}
<div align="center"><div class="formlist">
<form name="form1" method="post" action="{$editFormAction}">
<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="table sortable-onload-1 rowstyle-alt paginate-15" id="sortTable">
<thead>
        <tr>
		  <th class="smallhead sortable">Group</th>
		  <th width="7%" class="smallhead sortable-numeric">Points</th>
          {if $edits == true}<th width="15%" class="smallhead">Action</th>
          <th width="20%" class="smallhead">Value</th>{/if}
		</tr>
        </thead>
        <tbody>
    {section name=pointloop loop=$numpoints}
		<tr valign="top" class="text">
		  <td class="text"><div align="right">{$points[pointloop].teamname}</div></td>
		  <td class="text" style="text-align:center;">{$points[pointloop].points}</td>
          {if $edits == true}
          <td class="text" align="left" ><div align="left">
          <select id="how_{$points[pointloop].id}" name="how_{$points[pointloop].id}" class="inputbox">
          <option value="0">Add</option>
          <option value="1">Subtract</option>
          <option value="2">Set to</option>
          <option value="3" selected="selected">Ignore</option>
          </select></div>
          </td>
          <td class="text" align="left" >
          <input name="{$points[pointloop].id}" type="text" value="0" class="inputbox" /></td>
          {/if}
		</tr>
	{/section}
    </tbody>
  </table>
      {if $edits == true}<div align="center"><input type="submit" name="Submit" value="Submit" class="button" />
      &nbsp;<input type="reset" name="Submit2" value="Reset" class="button" /></div>{/if}
</form>
</div></div>
{else}
<div align="center">There are no groups currently activated for points</div>
{/if}
