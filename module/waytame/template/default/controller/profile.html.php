<?php
    defined('PHPFOX') or exit('NO DICE!');
?>

{if count($aQuestions)}
{foreach from=$aQuestions item=aQuestion}
<div id="waytame_item_question_{$aQuestion.question_id}" class="js_feed_view_more_entry_holder" style="margin-top:10px;">
    <div class="row_feed_loop js_parent_feed_entry row2 row_first js_user_feed" style="padding: 0;">
        <div class="activity_feed_image">    
            <a title="{$aQuestion.full_name}" href="{url link=$aQuestion.user_name}">
                {img user=$aQuestion suffix='_50_square'}
            </a>    
        </div>

        <div class="activity_feed_content" style="margin-bottom: 0;">                            
            <div class="activity_feed_content_text">
                <div class="activity_feed_content_info">
                    <span id="js_user_name_link_{$aQuestion.user_name}" class="user_profile_link_span">
                        <a href="{url link=$aQuestion.user_name}">{$aQuestion.full_name}</a>
                    </span>{phrase var='waytame.has_created_a_new_waytame_question'}
                </div>
                <p style="color: #666;">{if $aQuestion.expire_time > PHPFOX_TIME}      {if $aQuestion.expire_time|convert_time:'waytame.format_expire_time'|strpos:'@' !== false} {phrase var='waytame.expires_on'} {else} {phrase var='waytame.expires_within'} {/if} {$aQuestion.expire_time|convert_time:'waytame.format_expire_time'|trim:'ago'}{else}Question expired {/if}</p>
                <div class="activity_feed_content_link">                
                    <div class="">
                        <a style="cursor: default;text-decoration: none !important;" class="activity_feed_content_link_title">{$aQuestion.question}</a>
                        <p style="margin-top:10px;">{$aQuestion.owner_answer}</p>
                        {if count($aQuestion.answers) > 0}<a style="cursor: pointer;display: inline-block;margin-top:8px;" class="activity_feed_content_link_title" href="{$aQuestion.question_link}">{$aQuestion.answers|count} {if count($aQuestion.answers) > 1}answers{else}answer{/if}</a>{/if}
                    </div>    
                </div>
            </div>

            <div class="js_feed_comment_border">
                <div class="comment_mini_link_like" style="margin-bottom:10px;">
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
                        <li><a href="" onclick="$.ajaxCall('waytame.deteleQuestion','question_id={$aQuestion.question_id}');return false;" class="js_hover_title">{phrase var='waytame.delete'} <span class="js_hover_info">{phrase var='waytame.delete_this_question'}</span></a></li>
                        <li class="feed_entry_time_stamp">
                            <a class="feed_permalink" href="{$aQuestion.question_link}">{$aQuestion.time_stamp|convert_time}</a>
                        </li>

                    </ul>
                    <div class="clear"></div>        

                </div>

                <div class="comment_mini_content_holder">    
                    <div>
                        <div class="js_comment_like_holder" {if $aQuestion.total_like < 1 && $aQuestion.total_dislike < 1}style="display:none;"{/if}>
                            <div>                        
                                <div class="display_actions">
                                    <div class="comment_mini_content_holder">         
                                        <div>                        
                                            <div id="" class="js_comment_like_holder">        

                                                <div class="activity_like_holder comment_mini">
                                                    <span class="like_info" {if $aQuestion.total_like < 1}style="display:none;"{/if}>
                                                    <img class="v_middle" alt="" src="{param var='core.path'}theme/frontend/default/style/default/image/layout/like.png"> Useful to <span class="waytame_total_like">{$aQuestion.total_like}</span>  wayter  </span>                         
                                                    <span {if $aQuestion.total_dislike < 1}style="display:none"{/if} class="dislike_info"> <span class="dot_net" {if $aQuestion.total_like < 1}style="display:none;"{/if}>·</span> <img class="v_middle" alt="" src="http://localhost/snowfox/snowfox.3.7.7/theme/frontend/default/style/default/image/layout/dislike.png"> Useless to <span class="waytame_total_dislike">{$aQuestion.total_dislike}</span>  wayter </span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{/foreach}
{else}
<p>You haven't created any question.</p>
{/if}