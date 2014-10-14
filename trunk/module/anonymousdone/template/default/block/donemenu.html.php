<?php
  defined('PHPFOX') or exit('NO DICE!');
?>
<div class="sub_section_menu" id='anonymous_post_received'>
    <ul>
    {if $aUser.user_id == $iUserId}
        <li {if $sView == 'anonydone'}class="active"{/if}><a style="background-image: url({param var='core.path'}module/anonymousdone/static/image/anonymousdone.png);" href="{url link=$aUser.user_name view='anonydone'}">{phrase var='anonymousdone.anonymous_posts_done'}</a></li>
    {/if}
    </ul>
</div>