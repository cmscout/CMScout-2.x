{include file = "admin/admin_header.tpl"}
{if $infomessage}
<script type="text/javascript">
    {literal}
    function hide(id)
    {
        document.getElementById(id).style.display = "none";
        return;
    }
    {/literal}
</script>
<table width="100%" border="0" cellspacing="0" cellpadding="0"> <tr> <td width="100%"> 
<div {if $usererror != true}id="infobar"{else}id="errorbar"{/if}>{if !$nohide}<a href="#" onclick="hide('infobar');" title="Click to hide">{$infomessage}</a>{else}{$infomessage}{/if}</div>
</td> </tr> </table>
{/if}
<table width="100%" border="0" cellspacing="1" cellpadding="4">
<tr>
<td colspan="2">
<img src="templates/admin/images/banner.gif" alt="CMScout Administration" />
</td>
</tr>
<tr>
<td style="width:200px;vertical-align:top;">
{if $ex != "nomenu"}{$mainmenu}{/if}
</td>
<td style="vertical-align:top;" width="100%">
    {include file = "admin/$file"}
</td>
</tr>
</table>
{include file = "admin/admin_footer.tpl"}
