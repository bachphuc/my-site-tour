<?php
    defined('PHPFOX') or exit('NO DICE!');
?>
<form action="" method="POST" onsubmit="$(this).ajaxCall('waytame.answerQuestionProcess');return false;">
    <div class="waytame_description">
        <p>{phrase var='waytame.our_description_here'}</p>
    </div>
    <div>
        <input type="hidden" name="val[question_id]" value="{$aQuestion.question_id}">
    </div>
    <div class="waytame_row">
        <p><span>{$iNumberQuestion}.</span> {$aQuestion.question}</p>
        <p>{phrase var='waytame.expires_on'}{$aQuestion.expire_time|convert_time:'waytame.format_expire_time'}</p>
    </div>
    <div class="waytame_row"><p>{phrase var='waytame.give_your_anonymous_answer'}</p></div>
    <div class="waytame_row">
        <input style="width: 80% !important;" type="text" name="val[answer]">
        <input type="submit" class="button" value="{phrase var='waytame.answer'}">
    </div>

    <div class="js_box_close">
        <a onclick="return js_box_remove(this);">CLOSE</a>
        <a style="float: left;" onclick="tb_remove();js_box_remove(this);$Core.box('waytame.showQuestion',500,'user_id={$aQuestion.user_id}');$('.js_box').addClass('waytame_box');return false;">BACK</a>
    </div>
</form>