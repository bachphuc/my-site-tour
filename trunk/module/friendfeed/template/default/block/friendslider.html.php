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
            <li {if isset($aFriend.friend_user_id)}id="friend_item_{$aFriend.friend_user_id}"{/if} val="{if isset($aFriend.friend_user_id)}{$aFriend.friend_user_id}{else}0{/if}">  
                <a href="{$aFriend.user_profile}"><img src="{'_50_'|str_replace:'_200_':$aFriend.user_image}"> </a> 
                {if $aFriend.first_name != ''}
                    {if strlen($aFriend.last_name) > 10}
                        <div class="bottom_name">{$aFriend.first_name}<br>{$aFriend.last_name|shorten:10'...'}</div> 
                        {else}
                        <div class="bottom_name">{$aFriend.first_name}<br>{$aFriend.last_name}</div> 
                    {/if}
                    {else}
                        {if strlen($aFriend.last_name) > 10}
                        <div class="bottom_name">{$aFriend.last_name|shorten:10'...'}</div> 
                        {else}
                        <div class="bottom_name">{$aFriend.last_name'}</div> 
                        {/if}
                {/if}

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