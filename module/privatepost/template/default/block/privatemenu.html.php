<?php
    defined('PHPFOX') or exit('NO DICE!');
?>

<script type="text/javascript" src="{param var='core.path'}module/privatepost/static/jscript/script.js"></script>
<script type="text/javascript">
    {if $sView == 'private' || $sView == 'my'}
    $Behavior.initPrivateMenu = function(){l}
        $('.sub_section_menu:not(#private_menu) li').removeClass('active');
    {r}
    {/if}
</script>
<div class="sub_section_menu" id="private_menu">
    <ul>
        <li {if $sView == 'my'}class="active"{/if}><a style="background-image: url({param var='core.path'}module/privatepost/static/image/my_post.png);" href="{url link=$aUser.user_name view='my'}">{phrase var='privatepost.my_post'}</a></li>
        <li {if $sView == 'private'}class="active"{/if}><a style="background-image: url({param var='core.path'}module/privatepost/static/image/private_post.png);" href="{url link=$aUser.user_name view='private'}">{phrase var='privatepost.private_post'}</a></li>
    </ul>
</div>