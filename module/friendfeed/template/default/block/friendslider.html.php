<?php
    defined('PHPFOX') or exit('NO DICE!');
?>
<div id="friend_slider" style="width: 557px; padding-top: 25px; padding-bottom: 50px;">
    <h2 style="color: #B8603B; font-size: 20px; padding: 10px 0px;float: left;">{phrase var='friendfeed.look_the_post'}</h2> 
    <input type="checkbox" name="toggle" id="toggle">
    <label for="toggle" style="float: right;margin-top: 20px;"></label>
    <div style="clear:both;"></div>

    <div id="main_slider">

        <ul id="carousel" class="elastislide-list">   
            {foreach from= $aFriends item=aFriend}    
            <li {if isset($aFriend.friend_user_id)}id="friend_item_{$aFriend.friend_user_id}"{/if} val="{if isset($aFriend.friend_user_id)}{$aFriend.friend_user_id}{else}0{/if}">  
                <a href="{$aFriend.user_profile}"><img src="{'_50'|str_replace:'_120':$aFriend.user_image}"> </a> 
                <div class="bottom_name">{$aFriend.shorten_name'}</div> 
                <div class="friend_feed_loading"></div>
            </li> 
            {/foreach} 
        </ul>
        <div id="section_alphabet">
            <ul>
                {foreach from=$aSections key=iKey item=aSection}
                <li><a class="section_{$iKey|strtolower}" onclick="scrollToSection({$aSection})">{$iKey}</a></li>
                {/foreach}
            </ul>
        </div>
    </div>

</div>

<textarea id="panel_home_feed" style="display: none;" val="0"></textarea>