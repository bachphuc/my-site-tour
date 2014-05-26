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
            <li>  
                <a href="{$aFriend.user_profile}"><img src="{'_50_'|str_replace:'_200_':$aFriend.user_image}"> </a> 
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
