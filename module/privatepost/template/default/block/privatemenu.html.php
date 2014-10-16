<?php
    defined('PHPFOX') or exit('NO DICE!');
?>

<div class="sub_section_menu" id="private_menu">
    <ul>
        <li {if $sView == 'private'}class="active"{/if}><a style="background-image: url({param var='core.path'}module/privatepost/static/image/private_post.png);" href="{url link=$aUser.user_name view='private'}">{phrase var='privatepost.private_post'}</a></li>
    </ul>
</div>