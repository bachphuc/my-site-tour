<?php
    
?>

<div>
    <p class="summary_title">Summary</p>
    <div>
        {foreach from=$aSummarys item=aQuestion name=index key=key}
        <div>
            <p>{index}</p>
        </div>
        {/foreach}
    </div>
</div>

<div class="js_box_close" style="display: block;">
    <span class="box_controll_left">
        <a type="button" class="button" onclick="">PRE</a>
    </span>
    <span class="box_controll_right">
        <a type="button" class="button" onclick="return $Core.waytime.close(this);">FREEZE</a>
    </span>
</div>