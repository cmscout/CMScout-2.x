{include file="overall_header.tpl"}
{if $ismessage == true}
<script>
    {literal}
    function hide(id)
    {
        document.getElementById(id).style.display = "none";
        return;
    }
    {/literal}
</script>

<table width="100%" border="0" cellspacing="0" cellpadding="0"> <tr> <td width="100%"> 
<div {if $usererror != true}id="infobar"{else}id="errorbar"{/if}>{if !$nohide}<a href="#" onclick="hide('infobar');" title="Click to hide">{/if}{$infomessage}{if !$nohide}</a>{/if}</div>
</td> </tr> </table>
{elseif $newpm}
<table width="100%" border="0" cellspacing="0" cellpadding="0"> <tr> <td width="100%"> 
<div id="infobar"><a href="index.php?page=pmmain">You have a new private message, click here to read it now</a></div>
</td> </tr> </table>
{elseif $nummessagepm > 0}
<table width="100%" border="0" cellspacing="0" cellpadding="0"> <tr> <td width="100%"> 
<div id="infobar"><a href="index.php?page=pmmain">You have {$nummessagepm} unread private messages, click here to read them now</a</div>
</td> </tr> </table>
{/if}

<center style="margin:20px;">
 {if $extra != "nomenu"}   <div class="outside-box" style="height:135px;"><span style="text-align:left;"><img align="left" src="{$templateinfo.imagedir}logo.gif" alt="CMScout" /></span>
        <div style="text-align:right;">
        {if $menu.top != ""}
          {section name=cats loop=$nummenucats.top}
          | {section name=itemloop loop=$menu.top[cats].numitems}
           {if $menu.top[cats].items[itemloop].type == 1}
                <a href="index.php?page={$menu.top[cats].items[itemloop].link}">{$menu.top[cats].items[itemloop].name|upper}</a>
           {elseif $menu.top[cats].items[itemloop].type == 2 || $menu.top[cats].items[itemloop].type == 3}
                <a href="{$menu.top[cats].items[itemloop].link}">{$menu.top[cats].items[itemloop].name|upper}</a>
           {elseif $menu.top[cats].items[itemloop].type == 4}
                {eval var=$menu.top[cats].items[itemloop].link}
           {/if} |
          {/section}
        {/section}
        {/if}
        </div>
	</div>
	<div class="outside-box" style="border-top: none;">
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
        <td align="left" width="40%">{eval var=$userdisp}
        </td>
        <td align="left">
        ..:.:.:: {$config.troopname} ::.:.:..
        </td>
        </tr>
        </table>
	</div>{/if}
	<div class="outside-box" style="border-top: none;">
		<table cellspacing="0" cellpadding="0" border="0">
			<tr>
				 {if $extra != "nomenu"}<td valign="top">
                {section name=cats loop=$nummenucats.left}
                <div style="padding-bottom: 3px;">
             {if $menu.left[cats].showhead}<div class="nav-title">{$menu.left[cats].name|upper} ::.:.:..</div>{else}<div class="nav-title">::.:.:..</div>{/if}

            {section name=itemloop loop=$menu.left[cats].numitems}
               {if $menu.left[cats].items[itemloop].type == 1}
                    <a href="index.php?page={$menu.left[cats].items[itemloop].link}&amp;menuid={$menu.left[cats].items[itemloop].id}" class="nav-link">{$menu.left[cats].items[itemloop].name|upper}</a>
               {elseif $menu.left[cats].items[itemloop].type == 2 || $menu.left[cats].items[itemloop].type == 3}
                    <a href="{$menu.left[cats].items[itemloop].link}" class="nav-link">{$menu.left[cats].items[itemloop].name|upper}</a>
               {elseif $menu.left[cats].items[itemloop].type == 4}
               {if $menu.left[cats].numitems > 1}<br />{/if}
                    <div class="inside-box">{eval var=$menu.left[cats].items[itemloop].link}</div>
                    {if $menu.left[cats].numitems > 1}<br />{/if}
               {/if}
               {if $menu.left[cats].items[itemloop].subitems != 0}
                   {section name=subitemloop loop=$menu.left[cats].items[itemloop].subitems}
                       {if $menu.left[cats].items[itemloop].subitem[subitemloop].type == 1}
                           <a href="index.php?page={$menu.left[cats].items[itemloop].subitem[subitemloop].link}&amp;menuid={$menu.left[cats].items[itemloop].subitem[subitemloop].parent}" class="nav-link">&nbsp;&nbsp;{$menu.left[cats].items[itemloop].subitem[subitemloop].name|upper}</a>
                       {elseif $menu.left[cats].items[itemloop].subitem[subitemloop].type == 2 || $menu.left[cats].items[itemloop].subitem[subitemloop].type == 3}
                            <a href="{$menu.left[cats].items[itemloop].subitem[subitemloop].link}" class="nav-link">&nbsp;&nbsp;{$menu.left[cats].items[itemloop].subitem[subitemloop].name|upper}</a>
                       {/if}
                   {/section}
               {/if}
              {/section}
              </div>
            {/section}
  				</td>{/if}
				<td valign="top" width="100%" style="padding-left: 3px; padding-right: 3px;">
					<!-- NEWS-ITEM -->
                       <div style="padding-bottom: 3px;">
						<div class="inside-box" align="left">
                           <div style="float:left">{$location} ::.::.:..</div>{if $editable || $addable}<div style="float:right;">
                        {if $editable == true}<a href="{$editlink}"><img border="0" src="{$templateinfo.imagedir}edit.gif" alt="Edit this item" /></a>{/if}		
                        {if $addable == true}<a href="{$addlink}"><img border="0" src="{$templateinfo.imagedir}add.png" alt="Add a new item" /></a>{/if}</div>
                    {/if}<br style="clear:both;" />
						</div>
                        <div class="inside-box" style="border-top: none;" align="left">
                        {if $dataC == false}
                            {include file="$content"}
                        {else}
                            {eval var=$content}
                        {/if}
                        </div>
                    {if $extra == "nomenu"}
                     <a href="javascript:close()">Close Window</a>
                    {/if}
                    </div>
				</td>{if $extra != "nomenu"}
				<td valign="top">
                {section name=cats loop=$nummenucats.right}
                <div style="padding-bottom: 3px;">
             {if $menu.right[cats].showhead}<div class="nav-title">{$menu.right[cats].name|upper} ::.:.:..</div>{else}<div class="nav-title">::.:.:..</div>{/if}

            {section name=itemloop loop=$menu.right[cats].numitems}
               {if $menu.right[cats].items[itemloop].type == 1}
                    <a href="index.php?page={$menu.right[cats].items[itemloop].link}&amp;menuid={$menu.right[cats].items[itemloop].id}" class="nav-link">{$menu.right[cats].items[itemloop].name|upper}</a>
               {elseif $menu.right[cats].items[itemloop].type == 2 || $menu.right[cats].items[itemloop].type == 3}
                    <a href="{$menu.right[cats].items[itemloop].link}" class="nav-link">{$menu.right[cats].items[itemloop].name|upper}</a>
               {elseif $menu.right[cats].items[itemloop].type == 4}
	        {assign var="sideitemid" value=$menu.right[cats].items[itemloop].id}
               {if $menu.right[cats].numitems > 1}<br />{/if}
                    <div class="inside-box">{eval var=$menu.right[cats].items[itemloop].link}</div>
                    {if $menu.right[cats].numitems > 1}<br />{/if}
               {/if}
               {if $menu.right[cats].items[itemloop].subitems != 0}
                   {section name=subitemloop loop=$menu.left[cats].items[itemloop].subitems}
                       {if $menu.right[cats].items[itemloop].subitem[subitemloop].type == 1}
                           <a href="index.php?page={$menu.right[cats].items[itemloop].subitem[subitemloop].link}&amp;menuid={$menu.right[cats].items[itemloop].subitem[subitemloop].parent}" class="nav-link">&nbsp;&nbsp;{$menu.right[cats].items[itemloop].subitem[subitemloop].name|upper}</a>
                       {elseif $menu.right[cats].items[itemloop].subitem[subitemloop].type == 2 || $menu.right[cats].items[itemloop].subitem[subitemloop].type == 3}
                            <a href="{$menu.right[cats].items[itemloop].subitem[subitemloop].link}" class="nav-link">&nbsp;&nbsp;{$menu.right[cats].items[itemloop].subitem[subitemloop].name|upper}</a>
                       {/if}
                   {/section}
               {/if}
              {/section}
              </div>
            {/section}
				</td>
                {/if}
			</tr>
		</table>
	</div>
{include file="overall_footer.tpl}



