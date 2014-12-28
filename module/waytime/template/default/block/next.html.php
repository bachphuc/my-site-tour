<?php

?>

<h1 class="waytime_title">{phrase var='waytime.character'}</h1>

{if isset($aQuestion)}
<div>
    <p class="waytime_question_title">{$aQuestion.title}</p>
    <input type="hidden" id="hd_question_id" value="{$aQuestion.question_id}">
</div><br>
<div class="waytime_left">
    {if isset($aQuestion.answers) && count($aQuestion.answers)}
    {foreach from=$aQuestion.answers item=aAnswer}
    <div>
        <input {if isset($aAns.answer_id) && $aAns.answer_id == $aAnswer.answer_id}checked="checked"{/if} class="radio_answer" value="{$aAnswer.answer_id}" type="radio" name="answer" id="radio_{$aAnswer.answer_id}"> <label for="radio_{$aAnswer.answer_id}">{$aAnswer.answer}</label>
    </div>
    {/foreach}
    {/if}
</div>
<div class="waytime_right">
    <textarea class="waytime_note" placeholder="{phrase var='waytime.you_note_here_not_mandatory'}">{if isset($aAns.answer_id)}{$aAns.note}{/if}</textarea>
</div>
{/if}
<div class="clear"></div>
<div class="js_box_close" style="display: block;">
    <span class="box_controll_left">
        <a type="button" class="button" onclick="{if !$iPre}$Core.waytime.start(this);{else}$Core.waytime.goPre({$iPre},this);{/if}return false;">PRE</a>
        <a type="button" class="button" onclick="{if $iNext}$Core.waytime.goNext({$iNext},this);{else}$Core.waytime.last(this);{/if}return false;">NEXT</a>
    </span>
    <span class="box_controll_middle">{$iIndex}/{$iTotal}</span>
    <span class="box_controll_right">
        <a type="button" class="button" onclick="return $Core.waytime.close(this);">CLOSE & REMEMBER</a>
    </span>
</div>