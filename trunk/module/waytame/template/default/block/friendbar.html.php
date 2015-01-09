<?php
    defined('PHPFOX') or exit('NO DICE!');
?>

<div id="friend_bar_wapper">
    <div class="title-underline">{phrase var='waytame.waytame'}</div>
    <div id= "friend_list">
        <span class="outer_tootip">
            <img onclick="$Core.box('waytame.addQuestion',500);$('.js_box').addClass('waytame_box').addClass('waytame_box_green');" style="float: left;cursor: pointer;" src="{param var='core.path'}module/waytame/static/image/w_button.png" alt="" >
            <span class="waytame_question_tooltip">Create a new question</span>
        </span>
        <ul style="float: right;" class="waytame_slider">
            {foreach from= $aFriends item=aFriend}
            <li onclick="{if isset($aFriend.user_name)}$Core.box('waytame.showQuestion',500,'user_id={$aFriend.user_id}');$('.js_box').addClass('waytame_box');{/if}return false;"><a href="{if isset($aFriend.user_name)}{url link=$aFriend.user_name}{/if}"><img src="{'_50'|str_replace:'_120':$aFriend.user_image}"></a><span class="waytame_question_tooltip">{if isset($aFriend.user_name)}{$aFriend.full_name}<br>{$aFriend.total_question} {if $aFriend.total_question> 1}questions{else}question{/if}{else}Your next friend.{/if}</span></li>
            {/foreach}
        </ul>
        <div style="clear: both;"></div>
    </div>
</div>
