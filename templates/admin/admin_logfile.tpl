<div style="margin:5px; padding:5px;" align="right"><a href="admin.php?page=logfile&amp;action=clear">Clear Logfile</a></div>
<div align="center"><div style="width:100%">
{section name=log loop=$logfile}
<div style="margin:5px; padding:5px; border:1px solid #000;" align="center"><b>Date: </b>{$logfile[log].date}<br />
<span class="comments">{$logfile[log].error}</span></div>
{sectionelse}
<div style="margin:5px; padding:5px; border:1px solid #000;" align="center">The logfile is empty.</div>
{/section}
</div></div>