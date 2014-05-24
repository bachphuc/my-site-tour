<?php
    defined('PHPFOX') or exit('NO DICE!');
?>
<div id="friend_slider">
    <h2 style="color: #B8603B; font-size: 20px; padding: 10px 0px;float: left;">Look the post of your friends</h2> 
    <input type="checkbox" name="toggle" id="toggle">
    <label for="toggle" style="float: right;margin-top: 20px;"></label>
    <div style="clear:both;"></div>

    <div id="main_slider">
        <ul id="carousel" class="elastislide-list">   
            {foreach from= $aFriends item=aFriend}    
            <li>
                <a href="{$aFriend.user_profile}"><img src="{$aFriend.user_image}"></a> 
                <div class="bottom_name">{$aFriend.full_name}</div>
            </li> 
            {/foreach} 
        </ul>
        <div id="section_alphabet">
            <ul>
                {foreach from=$aAlphabets item=aAlphabet}
                <li><a>{$aAlphabet}</a></li>
                {/foreach}
            </ul>
        </div>
    </div>

</div>

<!--<div class="block_listing_inline">
    <ul >
        {foreach from=$aFriends key=iKey name=friend item=aFriend}
        <li>{img user=$aFriend suffix='_50_square' max_width=50 max_height=50 class='js_hover_title'}</li> 
        {/foreach}
    </ul>
</div>  
<ul >
    <li><a href="#"><img src="{param var='core.path'}module/friendfeed/static/image/15.jpg" alt=""></a></li>
</ul>   -->
