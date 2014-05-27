<?php
    defined('PHPFOX') or exit('NO DICE!');
?>
<div id="friend_slider">
    <h2 style="color: #B8603B; font-size: 20px; padding: 10px 0px;float: left;">{phrase var='friendfeed.look_the_post'}</h2> 
    <input type="checkbox" name="toggle" id="toggle">
    <label for="toggle" style="float: right;margin-top: 20px;"></label>
    <div style="clear:both;"></div>

    <div id="main_slider">

        <ul id="carousel" class="elastislide-list">   
            {foreach from= $aFriends item=aFriend}    
            <li id="friend_item_{if isset($aFriend.friend_user_id)}{$aFriend.friend_user_id}{else}0{/if}" val="{if isset($aFriend.friend_user_id)}{$aFriend.friend_user_id}{else}0{/if}">  
                <a href="{$aFriend.user_profile}"><img src="{'_50_'|str_replace:'_200_':$aFriend.user_image}"> </a> 
                <div class="bottom_name">{$aFriend.full_name}</div> 
                <div class="friend_feed_loading"></div>
            </li> 
            {/foreach} 
        </ul>
        <div id="section_alphabet">
            <ul>
                {foreach from=$aSections key=iKey item=aSection}
                <li><a onclick="scrollToSection({$aSection})">{$iKey)}</a></li>
                {/foreach}
            </ul>
        </div>
    </div>

</div>

<textarea id="panel_home_feed" style="display: none;" val="0"></textarea>