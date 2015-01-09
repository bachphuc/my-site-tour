<?php
    defined('PHPFOX') or exit('NO DICE!');
?>
{if !isset($bLoadMore)}
<div class="waytame_description"><p>{phrase var='waytame.our_description_here'}</p></div>
<div class="waytame_question_panel">
{/if}

{if count($aQuestions)}
{foreach from=$aQuestions item=aQuestion key=iKey}
{php}$this->_aVars['aFeed'] = $this->_aVars['aQuestion'];{/php}
<div id="waytame_item_question_{$aQuestion.question_id}" class="waytame_question_item">
    <p><span>{php}echo $this->_aVars['iCurrentNumber'] + $this->_aVars['iKey'] + 1;{/php}.</span> {$aQuestion.question}</p>
    <p>Expire on {if $aQuestion.expire_time > PHPFOX_TIME}{$aQuestion.expire_time|convert_time:'waytame.format_expire_time'|trim:'ago'}{else}{$aQuestion.expire_time|convert_time:'waytame.format_expire_time'}{/if}</p>
    <p>{if $aQuestion.total_answer > 0}<a style="cursor: pointer" {if $aQuestion.total_answer > 0}onclick="tb_remove();$Core.box('waytame.showAnswer',500,'question_id={$aQuestion.question_id}&number_question={php}echo $this->_aVars['iKey'] + 1;{/php}');$('.js_box').addClass('waytame_box');return false;"{else}onclick="return false;"{/if}>{$aQuestion.total_answer} {if $aQuestion.total_answer > 1}answers{else}answer{/if}</a>{else}0 Answer{/if}</p>
    <div class="waytame_link">
        {if !isset($aQuestion.answer_id)}<a onclick="tb_remove();$Core.box('waytame.answerQuestion',500,'question_id={$aQuestion.question_id}&number_question={php}echo $this->_aVars['iCurrentNumber'] + $this->_aVars['iKey'] + 1;{/php}');$('.js_box').addClass('waytame_box');return false;">Waytame|</a>{else}<a onclick="return false;"><img style="position:relative;top:4px;left:-5px;" src="{param var='core.path'}module/waytame/static/image/ticked.png">|</a>{/if}
        <a class="waytame_like {if $aQuestion.feed_is_liked}waytame_hide{/if}" onclick="$.ajaxCall('waytame.likeQuestion','item_id={$aQuestion.question_id}&parent_id={$aQuestion.feed_id}&type_id=waytame');return false;" class="waytame_like">Like|</a>
        <a class="waytame_unlike {if !$aQuestion.feed_is_liked}waytame_hide{/if}" onclick="$.ajaxCall('waytame.unLikeQuestion','item_id={$aQuestion.question_id}&parent_id={$aQuestion.feed_id}&type_id=waytame');return false;">{phrase var='waytame.unlike'}|</a>
        <a class="waytame_dislike {if $aQuestion.feed_is_disliked}waytame_hide{/if}" onclick="$.ajaxCall('waytame.disLikeQuestion','item_id={$aQuestion.question_id}&parent_id={$aQuestion.feed_id}&item_type_id=waytame&module_name=waytame&action_type_id=2');return false;">Dislike|</a>
        <a class="waytame_remove_dislike {if !$aQuestion.feed_is_disliked}waytame_hide{/if}" onclick="$.ajaxCall('waytame.removeDisLlkeQuestion','item_id={$aQuestion.question_id}&parent_id={$aQuestion.feed_id}&item_type_id=waytame&module_name=waytame&action_type_id=2');return false;">Remove dislike|</a>
        <a class="waytame_report" onclick="tb_remove();$Core.box('report.add','400','type=waytame&tb=true&id={$aQuestion.question_id}');return false;">{phrase var='waytame.report'}</a>
    </div>
    <div style="clear: both;"></div>
</div>
{/foreach}
{/if}

{if !isset($bLoadMore)}
</div>
{if $iTotalQuestion > 4}
<div style="text-align: center;pading:5px;">
    <span val="0" style="display: none;" class="waytame_show_less_question" onclick="$.ajaxCall('waytame.showMoreQuestion','user_id={$aQuestions.0.user_id}&page='+$(this).attr('val'))"></span>
    <span val="1" class="waytame_show_more_question" onclick="$.ajaxCall('waytame.showMoreQuestion','user_id={$aQuestions.0.user_id}&page='+$(this).attr('val'))"></span>
</div>
{/if}

<div class="js_box_close">
    <a onclick="return js_box_remove(this);">CLOSE</a>
</div>
{/if}