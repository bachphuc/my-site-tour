<?php
    defined('PHPFOX') or exit('NO DICE!');
?>

<div class="sub_section_menu">
    <ul>
        <li><a href="{url link=$aUser.user_name view='my'}">{phrase var='privatepost.my_post'}</a></li>
        <li><a href="{url link=$aUser.user_name view='private'}">{phrase var='privatepost.private_post'}</a></li>
    </ul>
</div>