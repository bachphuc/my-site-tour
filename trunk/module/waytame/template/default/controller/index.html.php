<?php
    defined('PHPFOX') or exit('NO DICE!');
?>

{if count($aQuestions)}
{foreach from=$aQuestions item=aQuestion}
<div id="waytame_item_question_{$aQuestion.question_id}" class="js_feed_view_more_entry_holder">
    <div id="js_item_feed_243" class="row_feed_loop js_parent_feed_entry row2 row_first js_user_feed">
        <div class="activity_feed_image">    
            <a title="{$aQuestion.full_name}" href="{url link=$aQuestion.user_name}">
                {img user=$aQuestion suffix='_50_square'}
            </a>    
        </div>

        <div class="activity_feed_content">                            
            <div class="activity_feed_content_text">
                <div class="activity_feed_content_info">
                    <span id="js_user_name_link_phuclb" class="user_profile_link_span"><a href="http://localhost/snowfox/snowfox.3.7.7/index.php?do=/phuclb/">phuclb</a></span>{phrase var='waytame.has_created_a_new_waytame_question'}</div>
                <div class="activity_feed_content_link">                
                    <div class="">
                        <a class="activity_feed_content_link_title" href="{$aQuestion.question_link}">{$aQuestion.question}</a>
                    </div>    
                </div>
            </div>

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
                        <li><a href="" onclick="$.ajaxCall('waytame.deteleQuestion','question_id={$aQuestion.question_id}');return false;" class="js_hover_title">{phrase var='waytame.delete'} <span class="js_hover_info">{phrase var='waytame.delete_this_question'}</span></a></li>
                        <li class="feed_entry_time_stamp">
                            <a class="feed_permalink" href="{$aQuestion.question_link}">{$aQuestion.time_stamp|convert_time}</a>
                        </li>

                    </ul>
                    <div class="clear"></div>        

                </div>

                <div class="comment_mini_content_holder" id="js_feed_like_holder_waytame_35">    
                    <div class="comment_mini_content_holder_icon"></div>
                    <div class="comment_mini_content_border">
                        <div id="js_feed_like_holder_243" class="js_comment_like_holder">
                            <div id="js_like_body_243">                        

                                <div id="display_actions_243" class="display_actions">
                                    <div class="comment_mini_content_holder">    
                                        <div class="comment_mini_content_holder_icon"></div>        
                                        <div class="comment_mini_content_border">                        
                                            <div id="" class="js_comment_like_holder">        

                                                <div class="activity_like_holder comment_mini">
                                                    <span class="waytame_total_like">{$aQuestion.total_like}</span> <img class="v_middle" alt="" src="http://localhost/snowfox/snowfox.3.7.7/theme/frontend/default/style/default/image/layout/like.png"> like this                                                    
                                                    <span class="waytame_total_dislike">{$aQuestion.total_dislike}</span> <img class="v_middle" alt="" src="http://localhost/snowfox/snowfox.3.7.7/theme/frontend/default/style/default/image/layout/dislike.png"> dislike this</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {if isset($aQuestion.answers)}
                        {foreach from=$aQuestion.answers item=aAnswer}
                        <div class="js_feed_comment_view_more_holder" id="waytame_answer_item_{$aAnswer.answer_id}">
                            <div style="background-color: transparent;">
                                <div class="js_mini_feed_comment comment_mini">
                                    <div class="comment_mini_image">
                                        <a title="{$aAnswer.full_name}" href="{url link=$aAnswer.user_name}">
                                            {img user=$aAnswer suffix='_50_square' max_width=32 max_height=32}
                                        </a>
                                    </div>
                                    <div class="comment_mini_content">
                                        <span id="js_user_name_link_phuclb" class="user_profile_link_span">
                                            <a href="{url link=$aAnswer.user_name}">{$aAnswer.full_name}</a></span><div class="comment_mini_text " id=""> has answered your question <br><br> <a class="activity_feed_content_link_title" href="">{$aAnswer.answer}</a></div>
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
                                                <li><a href="" onclick="$.ajaxCall('waytame.deteleAnswer','answer_id={$aAnswer.answer_id}');return false;">Delete</a></li>
                                                <li><span>·</span></li>
                                                <li><a href="" onclick="js_box_remove(this);tb_remove();$Core.box('report.add','400','type=wayanswer&tb=true&id={$aAnswer.answer_id}');return false;">Report</a></li>
                                            </ul>
                                            <div class="clear"></div></div></div><div class="js_comment_form_holder" id="js_comment_form_holder_269"></div><div class="comment_mini_child_holder">
                                        <div class="comment_mini_child_content"><div id="js_feed_like_holder_269"> </div></div>
                                    </div>
                                </div>
                            </div>
                            <div id="js_tmp_comment_e332e596124e265218ca5f624dec1422" style="background-color: transparent;"></div></div>  
                        {/foreach}
                        {/if}
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