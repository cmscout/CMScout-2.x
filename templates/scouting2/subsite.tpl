<table width="100%" border="0" cellpadding="1" cellspacing="0">
    <tr>
        <th colspan = "2" class="normalhead">
            {$sitetitle}
        </th>
    </tr>
    <tr valign="top">
        {if $sitemenu != ""}
        <td nowrap width="15%" class="sitemenu">
            <div align="center">{eval var=$sitemenu}</div>
        </td>
        {/if}
        <td {if $sitemenu == ""}colspan="2"{/if} class="sitecontent">
            {if $sitecontent != ""}{eval var=$sitecontent}{/if}&nbsp;
        </td>
    </tr>
</table>
