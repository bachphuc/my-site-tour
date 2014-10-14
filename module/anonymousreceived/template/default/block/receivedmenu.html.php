<?php
  defined('PHPFOX') or exit('NO DICE!');
?>
<div class="sub_section_menu" id='anonymous_post_received'>
    <ul>
    {if $isFriend}
        <li {if $sView == 'anonyreceived'}class="active"{/if}><a style="background-image: url({param var='core.path'}module/anonymousdone/static/image/anonymousdone.png);" href="{url link=$aUser.user_name view='anonyreceived'}">{phrase var='anonymousreceived.anonymous_posts_received'}</a></li>
    {/if}
    </ul>
</div>