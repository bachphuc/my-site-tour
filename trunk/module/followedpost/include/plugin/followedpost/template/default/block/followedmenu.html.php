<?php
    defined('PHPFOX') or exit('NO DICE!');
?>

<script type="text/javascript" src="{param var='core.path'}module/followedpost/static/jscript/script.js"></script>
<!--<script type="text/javascript">
    {if $sView == 'followed' || $sView == 'my' || $bMyPost}
    $Behavior.initFollowedMenu = function(){l}
        $('.sub_section_menu:not(#followed_menu) li').removeClass('active');
    {r}
    {/if}
</script>-->
<div class="sub_section_menu" id="followed_menu">
    <ul>
        <li {if $sView == 'my' || $bMyPost}class="active"{/if}><a style="background-image: url({param var='core.path'}module/followedpost/static/image/my_post.png);" href="{url link=$aUser.user_name view='my'}">{phrase var='followedpost.my_post'}</a></li>
        <li {if $sView == 'followed'}class="active"{/if}><a style="background-image: url({param var='core.path'}module/followedpost/static/image/followed_post.png);" href="{url link=$aUser.user_name view='followed'}">{phrase var='followedpost.followed_post'}</a></li>
    </ul>
</div>