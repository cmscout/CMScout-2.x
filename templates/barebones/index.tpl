<!--Include the header file-->
{include file="overall_header.tpl"}

<!--User message section, you may change the method that is used to display it to suit your website-->
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
    <div {if $usererror != true}id="infobar"{else}id="errorbar"{/if}>{if !$nohide}<a href="#" onclick="hide('infobar');" title="Click to hide">{/if}{$infomessage}{if !$nohide}</a>{/if}</div>
{elseif $newpm} 
    <div id="infobar"><a href="index.php?page=pmmain">You have a new private message, click here to read it now</a></div>
{elseif $nummessagepm > 0}
    <div id="infobar"><a href="index.php?page=pmmain">You have {$nummessagepm} unread private messages, click here to read them now</a</div>
{/if}

<!--The "Top" menu section-->
{if $menu.top != ""}
    {section name=cats loop=$nummenucats.top}
        {section name=itemloop loop=$menu.top[cats].numitems}
            {if $menu.top[cats].items[itemloop].type == 1}
                <a href="index.php?page={$menu.top[cats].items[itemloop].link}&amp;menuid={$menu.top[cats].items[itemloop].id}">{$menu.top[cats].items[itemloop].name}</a>
            {elseif $menu.top[cats].items[itemloop].type == 2 || $menu.top[cats].items[itemloop].type == 3}
                <a href="{$menu.top[cats].items[itemloop].link}">{$menu.top[cats].items[itemloop].name}</a>
            {elseif $menu.top[cats].items[itemloop].type == 4}
                {eval var=$menu.top[cats].items[itemloop].link}
            {/if}
        {/section}
    {/section}
{/if}

<!--This shows the "Welcome ..." part on the top left of most templates (You may remove it, or change the location as you see fit)-->
{eval var=$userdisp}


<!--The "Left" menu section-->
{section name=cats loop=$nummenucats.left}
    {if $menu.left[cats].showhead}{$menu.left[cats].name}{/if}
    {section name=itemloop loop=$menu.left[cats].numitems}
        {if $menu.left[cats].items[itemloop].type == 1}
            <a href="index.php?page={$menu.left[cats].items[itemloop].link}&amp;menuid={$menu.left[cats].items[itemloop].id}">{$menu.left[cats].items[itemloop].name}</a>
        {elseif $menu.left[cats].items[itemloop].type == 2 || $menu.left[cats].items[itemloop].type == 3}
            <a href="{$menu.left[cats].items[itemloop].link}">{$menu.left[cats].items[itemloop].name}</a>
        {elseif $menu.left[cats].items[itemloop].type == 4}
            {eval var=$menu.left[cats].items[itemloop].link}
        {/if}

        {if $menu.left[cats].items[itemloop].subitems != 0}
            {section name=subitemloop loop=$menu.left[cats].items[itemloop].subitems}
                {if $menu.left[cats].items[itemloop].subitem[subitemloop].type == 1}
                    <a href="index.php?page={$menu.left[cats].items[itemloop].subitem[subitemloop].link}&amp;menuid={$menu.left[cats].items[itemloop].subitem[subitemloop].parent}">{$menu.left[cats].items[itemloop].subitem[subitemloop].name}</a>
                {elseif $menu.left[cats].items[itemloop].subitem[subitemloop].type == 2 || $menu.left[cats].items[itemloop].subitem[subitemloop].type == 3}
                    <a href="{$menu.left[cats].items[itemloop].subitem[subitemloop].link}">{$menu.left[cats].items[itemloop].subitem[subitemloop].name}</a>
                {/if}
            {/section}
        {/if}
    {/section}
{/section}

<!--Shows the "edit this page" and "add item" links-->
{if $editable || $addable}
    <div style="float:right;">
    {if $editable == true}
        <a href="{$editlink}"><img border="0" src="{$templateinfo.imagedir}edit.gif" alt="Edit this item" /></a>
    {/if}		
    {if $addable == true}
        <a href="{$addlink}"><img border="0" src="{$templateinfo.imagedir}add.png" alt="Add a new item" /></a>
    {/if}
    </div><br style="clear:both;" />
{/if}

<!--Displays the actual content of the page-->
{if $dataC == false}
    {include file="$content"}
{else}
    {eval var=$content}
{/if}

<!--The "Right" menu section-->
{section name=cats loop=$nummenucats.right}
    {if $menu.right[cats].showhead}{$menu.right[cats].name}{/if}

    {section name=itemloop loop=$menu.right[cats].numitems}
        {if $menu.right[cats].items[itemloop].type == 1}
            <a href="index.php?page={$menu.right[cats].items[itemloop].link}&amp;menuid={$menu.right[cats].items[itemloop].id}">{$menu.right[cats].items[itemloop].name}</a>
        {elseif $menu.right[cats].items[itemloop].type == 2 || $menu.right[cats].items[itemloop].type == 3}
            <a href="{$menu.right[cats].items[itemloop].link}">{$menu.right[cats].items[itemloop].name}</a>
        {elseif $menu.right[cats].items[itemloop].type == 4}
            {eval var=$menu.right[cats].items[itemloop].link}
        {/if}
        
        {if $menu.right[cats].items[itemloop].subitems != 0}
            {section name=subitemloop loop=$menu.left[cats].items[itemloop].subitems}
                {if $menu.right[cats].items[itemloop].subitem[subitemloop].type == 1}
                    <a href="index.php?page={$menu.right[cats].items[itemloop].subitem[subitemloop].link}&amp;menuid={$menu.right[cats].items[itemloop].subitem[subitemloop].parent}">{$menu.right[cats].items[itemloop].subitem[subitemloop].name}</a>
                {elseif $menu.right[cats].items[itemloop].subitem[subitemloop].type == 2 || $menu.right[cats].items[itemloop].subitem[subitemloop].type == 3}
                    <a href="{$menu.right[cats].items[itemloop].subitem[subitemloop].link}">{$menu.right[cats].items[itemloop].subitem[subitemloop].name}</a>
                {/if}
            {/section}
        {/if}
    {/section}
{/section}

<!--Includes the footer of the site-->
{include file="overall_footer.tpl}



