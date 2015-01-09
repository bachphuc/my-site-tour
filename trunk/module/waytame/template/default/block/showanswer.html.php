<?php
    defined('PHPFOX') or exit('NO DICE!');
?>
{if !isset($bLoadMore)}
<div class="waytame_description">
    <p>{phrase var='waytame.our_description_here'}</p>
</div>

<div class="waytame_row" style="margin-bottom:20px !important;">
    <p>{$iNumberQuestion}. {$aQuestion.question}</p>
</div>
<div class="waytame_answer_panel">
{/if}

{if count($aAnswers)}
{foreach from=$aAnswers item=aAnswer}
    <div id="waytame_answer_item_{$aAnswer.answer_id}" class="waytame_answer_item">
        <p>{$aAnswer.answer}</p>
        <div class="waytame_link">
            <a class="waytame_like {if $aAnswer.is_liked}waytame_hide{/if}" onclick="$.ajaxCall('waytame.likeAnswerQuestion','item_id={$aAnswer.answer_id}');return false;" class="waytame_like">Like|</a>
            <a class="waytame_unlike {if !$aAnswer.is_liked}waytame_hide{/if}" onclick="$.ajaxCall('waytame.unLikeAnswerQuestion','item_id={$aAnswer.answer_id}');return false;">Unlike|</a>
            <a class="waytame_dislike {if $aAnswer.is_disliked}waytame_hide{/if}" onclick="$.ajaxCall('waytame.disLikeAnswerQuestion','item_id={$aAnswer.answer_id}');return false;">Dislike|</a>
            <a class="waytame_remove_dislike {if !$aAnswer.is_disliked}waytame_hide{/if}" onclick="$.ajaxCall('waytame.removeDisLlkeAnswerQuestion','item_id={$aAnswer.answer_id}');return false;">Remove dislike|</a>
            <a class="waytame_report" onclick="js_box_remove(this);tb_remove();$Core.box('report.add','400','type=wayanswer&tb=true&id={$aAnswer.answer_id}');return false;">Report</a>
        </div>
        <div style="clear: both;"></div>
    </div>
{/foreach}
{/if}

{if !isset($bLoadMore)}
</div>

{if $iTotalAnswer > 3}
<div style="text-align: center;pading:5px;">
    <span val="0" class="waytame_show_less_answer" style="display: none;" onclick="$.ajaxCall('waytame.showMoreAnswer','question_id={$aQuestion.question_id}&page='+$(this).attr('val'))"></span>
    <span val="1" class="waytame_show_more_answer" onclick="$.ajaxCall('waytame.showMoreAnswer','question_id={$aQuestion.question_id}&page='+$(this).attr('val'))"></span>
</div>
{/if}

<div class="js_box_close">
    <a onclick="return js_box_remove(this);">CLOSE</a>
    <a style="float: left;" onclick="tb_remove();js_box_remove(this);$Core.box('waytame.showQuestion',500,'user_id={$aQuestion.user_id}');$('.js_box').addClass('waytame_box');return false;">BACK</a>
</div>
{/if}