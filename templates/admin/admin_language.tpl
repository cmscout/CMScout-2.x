<script type="text/javascript">
{include file="../scripts/validator.tpl"}
</script>
<form name="form1" method="post" action="" onsubmit="return checkForm([['patrol','text',true,0,0,''],['troop','text',true,0,0,''],['award_scheme','text',true,0,0,''],['advancement_badges','text',true,0,0,''],['badges','text',true,0,0,''],['member','text',true,0,0,'']]);">
<h2>Language</h2>
<div align="center"><div style="width:100%;">

<div class="field">
    <label for="patrol" class="label">Patrol<span class="hintanchor" title="Required :: What word do you use to describe groups in your organisation."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper">{if $editallowed}<input type="text" name="patrol" id="patrol" value="{$scoutlang.patrol}" size="40" class="inputbox" onblur="checkElement('patrol', 'text', true, 0, 0, '');" /><br /><span class="fieldError" id="patrolError">Required</span>{else}{$scoutlang.patrol}{/if}</div><br />

    <label for="troop" class="label">Troop<span class="hintanchor" title="Required :: What word do you use to describe your organisation."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper">{if $editallowed}<input type="text" name="troop" id="troop" value="{$scoutlang.troop}" size="40" class="inputbox" onblur="checkElement('troop', 'text', true, 0, 0, '');" /><br /><span class="fieldError" id="troopError">Required</span>{else}{$scoutlang.troop}{/if}</div><br />

    <label for="award_scheme" class="label">Award Scheme<span class="hintanchor" title="Required :: Word to describe an award scheme."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper">{if $editallowed}<input type="text" name="award_scheme" id="award_scheme" value="{$scoutlang.award_scheme}" size="40" class="inputbox" onblur="checkElement('award_scheme', 'text', true, 0, 0, '');" /><br /><span class="fieldError" id="award_schemeError">Required</span>{else}{$scoutlang.award_scheme}{/if}</div><br />

    <label for="advancement_badges" class="label">Advancement Badges<span class="hintanchor" title="Required :: What word do you use to describe advancement badges in your organisation."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper">{if $editallowed}<input type="text" name="advancement_badges" id="advancement_badges" value="{$scoutlang.advancement_badges}" size="40" class="inputbox" onblur="checkElement('advancement_badges', 'text', true, 0, 0, '');" /><br /><span class="fieldError" id="advancement_badgesError">Required</span>{else}{$scoutlang.advancement_badges}{/if}</div><br />

    <label for="badges" class="label">Badges<span class="hintanchor" title="Required :: What word do you use to describe badges in your organisation."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper">{if $editallowed}<input type="text" name="badges" id="badges" value="{$scoutlang.badges}" size="40" class="inputbox" onblur="checkElement('badges', 'text', true, 0, 0, '');" /><br /><span class="fieldError" id="badgesError">Required</span>{else}{$scoutlang.badges}{/if}</div><br />

    <label for="member" class="label">Member<span class="hintanchor" title="Required :: What word do you use to describe members in your organisation."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper">{if $editallowed}<input type="text" name="member" id="member" value="{$scoutlang.member}" size="40" class="inputbox" onblur="checkElement('member', 'text', true, 0, 0, '');" /><br /><span class="fieldError" id="memberError">Required</span>{else}{$scoutlang.member}{/if}</div><br />

    <label for="members" class="label">Members<span class="hintanchor" title="Required :: Plural for the above word."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper">{if $editallowed}<input type="text" name="members" id="members" value="{$scoutlang.members}" size="40" class="inputbox" onblur="checkElement('members', 'text', true, 0, 0, '');" /><br /><span class="fieldError" id="membersError">Required</span>{else}{$scoutlang.members}{/if}</div><br />

    </div>
    <div class="submitWrapper">
        <input type="submit" name="Submit" value="Update"  class="button" />&nbsp;
        <input type="reset" name="Submit2" value="Reset" class="button" />
    </div></div></div>
</form>
