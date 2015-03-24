<?php
    
?>

<div>
    <p class="summary_title">Summary</p>
    <div>
        {foreach from=$aSummarys item=aQuestion name=index key=key}
        <div class="summary_item">
            <p>{$phpfox.iteration.index}. {$aQuestion.title}</p>
            <p>{$aQuestion.answer}</p>
            <p style="color:#444;">{$aQuestion.note}</p>
        </div>
        {/foreach}
    </div>
</div>

<div class="js_box_close" style="display: block;">
    <span class="box_controll_left">
        <a type="button" class="button no-icon" onclick="$Core.waytime.goPre({$iPre},this);return false;">PRE</a>
    </span>
    <span class="box_controll_right">
        <a type="button" class="button" onclick="return $Core.waytime.freeze(this);">FREEZE</a>
    </span>
</div>