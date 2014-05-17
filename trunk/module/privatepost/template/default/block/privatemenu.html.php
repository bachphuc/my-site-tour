<?php
    defined('PHPFOX') or exit('NO DICE!');
?>

<script type="text/javascript" src="{param var='core.path'}module/privatepost/static/jscript/script.js"></script>
<div class="sub_section_menu">
    <ul>
        <li><a href="{url link=$aUser.user_name view='my'}">{phrase var='privatepost.my_post'}</a></li>
        <li><a href="{url link=$aUser.user_name view='private'}">{phrase var='privatepost.private_post'}</a></li>
    </ul>
</div>