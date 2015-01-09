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
</div>