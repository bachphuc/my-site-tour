<?php
    defined('PHPFOX') or exit('NO DICE!');
?>
{literal}
<style type="text/css">
    .waytame_action_link a{
        font-weight:bold;
    }
    .form_answer .show_name{
        display:none;
    }
    .comment_mini{
        color:#3b5998;
    }
    .activity_feed_content_link_title{
        text-decoration: none !important;
    }
    div.comment_mini_content_border{
        background-color: transparent !important;
    }
    .row_feed_loop{
        border-bottom: none !important;
    }
</style>
{/literal}

<div id="waytame_item_question_{$aQuestion.question_id}" class="js_feed_view_more_entry_holder">
    <div  class="row_feed_loop js_parent_feed_entry row2 row_first js_user_feed">
        <div class="item_info">
            {phrase var='waytame.by_user' full_name=$aQuestion|user:'':'':50:'':'author'}
            <p style="color: #666;margin-top:10px;">{if $aQuestion.expire_time > PHPFOX_TIME}      {if $aQuestion.expire_time|convert_time:'waytame.format_expire_time'|strpos:'@' !== false} {phrase var='waytame.expires_on'} {else} {phrase var='waytame.expires_within'} {/if} {$aQuestion.expire_time|convert_time:'waytame.format_expire_time'|trim:'ago'}{else}Question expired {/if}</p>
            <a style="display: inline-block;margin-top:8px;" class="activity_feed_content_link_title" >{$aQuestion.answers|count} {if count($aQuestion.answers) > 1}answers{else}answer{/if}</a>
        </div>

        <div>                            
            <div class="js_feed_comment_border">
                <div class="comment_mini_link_like">
                    <ul class="waytame_action_link">
                        <li class="li_action">
                            <a href="" class="waytame_like {if $aQuestion.feed_is_liked}waytame_hide{/if}" onclick="$.ajaxCall('waytame.likeQuestion','item_id={$aQuestion.question_id}&parent_id={$aQuestion.feed_id}&type_id=waytame');return false;" class="waytame_like">Like</a>
                            <a href="" class="waytame_unlike {if !$aQuestion.feed_is_liked}waytame_hide{/if}" onclick="$.ajaxCall('waytame.unLikeQuestion','item_id={$aQuestion.question_id}&parent_id={$aQuestion.feed_id}&type_id=waytame');return false;">Unlike</a>  
                        </li>
                        <li><span>·</span></li>
                        <li class="li_action">
                            <a href="" class="waytame_dislike {if $aQuestion.feed_is_disliked}waytame_hide{/if}" onclick="$.ajaxCall('waytame.disLikeQuestion','item_id={$aQuestion.question_id}&parent_id={$aQuestion.feed_id}&item_type_id=waytame&module_name=waytame&action_type_id=2');return false;">Dislike</a>
                            <a href="" class="waytame_remove_dislike {if !$aQuestion.feed_is_disliked}waytame_hide{/if}" onclick="$.ajaxCall('waytame.removeDisLlkeQuestion','item_id={$aQuestion.question_id}&parent_id={$aQuestion.feed_id}&item_type_id=waytame&module_name=waytame&action_type_id=2');return false;">Remove dislike</a>
                        </li>          
                        <li><span>·</span></li>    
                        <li><a class="js_hover_title" href="" onclick="tb_remove();$Core.box('report.add','400','type=waytame&tb=true&id={$aQuestion.question_id}');return false;">{phrase var='waytame.report'} <span class="js_hover_info">{phrase var='waytame.report_this_question'}</span></a></li>                
                        <li><span>·</span></li>
                        <!--<li><a href="" onclick="$.ajaxCall('waytame.deteleQuestion','question_id={$aQuestion.question_id}');return false;" class="js_hover_title">{phrase var='waytame.delete'} <span class="js_hover_info">{phrase var='waytame.delete_this_question'}</span></a></li>-->
                    </ul>
                    <a class="feed_permalink" href="{$aQuestion.question_link}">{$aQuestion.time_stamp|convert_time}</a>
                    <div class="clear"></div>        
                </div>

                <div class="comment_mini_content_holder">    
                    <!--<div class="comment_mini_content_holder_icon"></div>-->
                    <div id="answer_panel" class="comment_mini_content_border">
                        <div class="js_comment_like_holder_bip">
                            <div>                        
                                <div class="display_actions">
                                    <div class="comment_mini_content_holder">  <br>  
                                        <!--<div class="comment_mini_content_holder_icon"></div>  -->      
                                        <div class="comment_mini_content_border">                      
                                            <div class="js_comment_like_holder" {if !$aQuestion.total_like && !$aQuestion.total_dislike}style="display:none;"{/if}>        

                                                <div class="activity_like_holder comment_mini">
                                                    <span {if !$aQuestion.total_like}style="display:none;"{/if} class="like_info"><img class="v_middle" alt="" src="{param var='core.path'}theme/frontend/default/style/default/image/layout/like.png"> Useful to <span class="waytame_total_like">{$aQuestion.total_like}</span> wayter{if $aQuestion.total_like > 1}s{/if} </span>  <span class="dot_net" {if $aQuestion.total_like && $aQuestion.total_dislike}{else}style="display:none;"{/if}>· </span>                                               
                                                    <span {if !$aQuestion.total_dislike}style="display:none;"{/if} class="dislike_info"><img class="v_middle" alt="" src="http://localhost/snowfox/snowfox.3.7.7/theme/frontend/default/style/default/image/layout/dislike.png"> Useless to <span class="waytame_total_dislike">{$aQuestion.total_dislike}</span>  wayter{if $aQuestion.total_dislike > 1}s{/if}</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {if isset($aAnswer)}
                        <div>
                            <h1 style="font-size:16px;">Answer #{$aAnswer.answer_id}</h1>
                            <div style="background-color:#DDD;" class="js_feed_comment_view_more_holder" id="waytame_answer_item_{$aAnswer.answer_id}">
                                <div style="background-color: transparent;">
                                    <div class="js_mini_feed_comment comment_mini">
                                        <div class="comment_mini_image">
                                            <a title="{$aAnswer.full_name}" href="{url link=$aAnswer.user_name}">
                                                {img user=$aAnswer suffix='_50_square' max_width=32 max_height=32}
                                            </a>
                                        </div>
                                        <div class="comment_mini_content">
                                            <span id="js_user_name_link_phuclb" class="user_profile_link_span">
                                                <a href="{url link=$aAnswer.user_name}">{$aAnswer.full_name}</a></span><div class="comment_mini_text " id=""> has answered your question <br><br> <a class="activity_feed_content_link_title">{$aAnswer.answer}</a></div>
                                            <div class="comment_mini_action">
                                                <ul>
                                                    <li class="comment_mini_entry_time_stamp">{$aAnswer.time_stamp|convert_time}</li>
                                                    <li><span>·</span></li>
                                                    <li><span>·</span></li>
                                                    <li class="li_action">
                                                    <a href="" class="waytame_like {if $aAnswer.is_liked}waytame_hide{/if}" onclick="$.ajaxCall('waytame.likeAnswerQuestion','item_id={$aAnswer.answer_id}');return false;" class="waytame_like">Like</a>
                                                    <a href="" class="waytame_unlike {if !$aAnswer.is_liked}waytame_hide{/if}" onclick="$.ajaxCall('waytame.unLikeAnswerQuestion','item_id={$aAnswer.answer_id}');return false;">Unlike</a>
                                                    <li><span>·</span></li>
                                                    <li class="li_action">
                                                    <a href="" class="waytame_dislike {if $aAnswer.is_disliked}waytame_hide{/if}" onclick="$.ajaxCall('waytame.disLikeAnswerQuestion','item_id={$aAnswer.answer_id}');return false;">Dislike</a>
                                                    <a href="" class="waytame_remove_dislike {if !$aAnswer.is_disliked}waytame_hide{/if}" onclick="$.ajaxCall('waytame.removeDisLlkeAnswerQuestion','item_id={$aAnswer.answer_id}');return false;">Remove dislike</a>
                                                    <li style="display:none;" class="js_like_link_holder"><span>
                                                        </span>
                                                    </li>
                                                    <li style="display:none;" class="js_like_link_holder">
                                                        <a onclick="return $Core.box('like.browse', 400, 'type_id=feed_mini&amp;item_id=269');" href="#"><span class="js_like_link_holder_info">0 people</span>
                                                        </a>
                                                    </li>
                                                    <li style="display:none;" class="js_dislike_link_holder"><span>·</span></li><li style="display:none;" class="js_dislike_link_holder">
                                                        <a onclick="return  $Core.box('like.browse', 400, 'type_id=feed_mini&amp;item_id=269&amp;dislike=1');" href="#">0 people</a>
                                                    </li>
                                                    <li><span>·</span></li>
                                                    <!--<li><a href="" onclick="$.ajaxCall('waytame.deteleAnswer','answer_id={$aAnswer.answer_id}');return false;">Delete</a></li>-->
                                                    <li><span>·</span></li>
                                                    <li><a href="" onclick="js_box_remove(this);tb_remove();$Core.box('report.add','400','type=wayanswer&tb=true&id={$aAnswer.answer_id}');return false;">Report</a></li>
                                                </ul>
                                                <div class="clear"></div></div></div><div class="js_comment_form_holder" id="js_comment_form_holder_269"></div><div class="comment_mini_child_holder">
                                            <div class="comment_mini_child_content"><div id="js_feed_like_holder_269"> </div></div>
                                        </div>
                                    </div>
                                </div>
                                <div id="js_tmp_comment_e332e596124e265218ca5f624dec1422" style="background-color: transparent;"></div></div>
                            <div style="height: 20px;"></div>
                        </div>
                        {/if}
                        {if isset($aQuestion.answers)}
                        {foreach from=$aQuestion.answers item=aAnswer}
                        <div class="js_feed_comment_view_more_holder" id="waytame_answer_item_{$aAnswer.answer_id}">
                            <div style="background-color: transparent;">
                                <div class="js_mini_feed_comment comment_mini">
                                    <div class="comment_mini_image">
                                        {*}<a title="{$aAnswer.full_name}" href="{url link=$aAnswer.user_name}">
                                            {img user=$aAnswer suffix='_50_square' max_width=32 max_height=32}
                                        </a>{*}
                                    </div>

                                    <div class="comment_mini_content">
                                        <span class="user_profile_link_span">
                                            <a href="{url link=$aAnswer.user_name}">{*}{$aAnswer.full_name}</a></span><div class="comment_mini_text " id=""> has answered your question <br><br> {*}<a class="activity_feed_content_link_title" >{$aAnswer.answer}</a></div>
                                        <div class="comment_mini_action">
                                            <ul style="margin-left: 40px;">
                                                {*}<li class="comment_mini_entry_time_stamp">{$aAnswer.time_stamp|convert_time}</li><li><span>·</span></li>{*}
                                                <li class="li_action">
                                                <a href="" class="waytame_like {if $aAnswer.is_liked}waytame_hide{/if}" onclick="$.ajaxCall('waytame.likeAnswerQuestion','item_id={$aAnswer.answer_id}');return false;" class="waytame_like">Like</a>
                                                <a href="" class="waytame_unlike {if !$aAnswer.is_liked}waytame_hide{/if}" onclick="$.ajaxCall('waytame.unLikeAnswerQuestion','item_id={$aAnswer.answer_id}');return false;">Unlike</a>
                                                <li><span>·</span></li>
                                                <li class="li_action">
                                                <a href="" class="waytame_dislike {if $aAnswer.is_disliked}waytame_hide{/if}" onclick="$.ajaxCall('waytame.disLikeAnswerQuestion','item_id={$aAnswer.answer_id}');return false;">Dislike</a>
                                                <a href="" class="waytame_remove_dislike {if !$aAnswer.is_disliked}waytame_hide{/if}" onclick="$.ajaxCall('waytame.removeDisLlkeAnswerQuestion','item_id={$aAnswer.answer_id}');return false;">Remove dislike</a>
                                                <li style="display:none;" class="js_like_link_holder"><span>
                                                    </span>
                                                </li>
                                                <li style="display:none;" class="js_like_link_holder">
                                                    <a onclick="return $Core.box('like.browse', 400, 'type_id=feed_mini&amp;item_id=269');" href="#"><span class="js_like_link_holder_info">0 people</span>
                                                    </a>
                                                </li>
                                                <li style="display:none;" class="js_dislike_link_holder"><span>·</span></li><li style="display:none;" class="js_dislike_link_holder">
                                                    <a onclick="return  $Core.box('like.browse', 400, 'type_id=feed_mini&amp;item_id=269&amp;dislike=1');" href="#">0 people</a>
                                                </li>
                                                <li><span>·</span></li>
                                                <!--<li><a href="" onclick="$.ajaxCall('waytame.deteleAnswer','answer_id={$aAnswer.answer_id}');return false;">Delete</a></li>-->
                                                <li><span>·</span></li>
                                                <li><a href="" onclick="js_box_remove(this);tb_remove();$Core.box('report.add','400','type=wayanswer&tb=true&id={$aAnswer.answer_id}');return false;">Report</a></li>
                                            </ul>
                                            <div class="clear"></div></div></div><div class="js_comment_form_holder" id="js_comment_form_holder_269"></div><div class="comment_mini_child_holder">
                                        <div class="comment_mini_child_content"><div id="js_feed_like_holder_269"> </div></div>
                                    </div>
                                </div>
                            </div>
                        </div>  
                        {/foreach}
                        {/if}

                    </div>
                    {if $aQuestion.expire_time > PHPFOX_TIME}
                    <!---- Form answer waytime question ---->
                    {if Phpfox::getUserId() != $aQuestion.user_id}
                    <div class="comment_mini_content_holder">    
                        <div class="comment_mini_content_border">
                            <div class="js_feed_comment_form">
                                <div class=" comment_mini comment_mini_end">
                                    <form class="form_answer" action="" method="post" onsubmit="$(this).ajaxCall('waytame.answerQuestionProcess');return false;">
                                        <input type="hidden" name="is_view" value="1">
                                        <input type="hidden" name="val[question_id]" value="{$aQuestion.question_id}">
                                        <div style="" class="comment_mini_image">
                                            <a title="{$aUser.full_name}" href="{url link=$aUser.user_name}">{img user=$aUser suffix='_50_square' width='32' height='32'}</a></div>                
                                        <div class="comment_mini_textarea_holder comment_mini_content">     
                                            <textarea onclick="event.stopPropagation();return false;" onfocus="event.stopPropagation();return false;" class="js_comment_feed_textarea " placeholder="Write a comment..." name="val[answer]" rows="4" cols="60"></textarea>
                                            <div class="js_feed_comment_process_form">Adding your Answer<img src="{param var='core.path'}theme/frontend/default/style/default/image/ajax/add.gif"></div>
                                        </div>
                                        <div style="display: block;" class="feed_comment_buttons_wrap">
                                            <div class="js_feed_add_comment_button t_right">
                                                <input type="submit" class="button button_set_off" value="Post Answer">                                    
                                            </div>                                
                                        </div>               
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    {/if}
                    <!--- End form answer waytime question ---->
                    {/if}
                </div>
            </div>
        </div>
    </div>
</div>