<?php
  defined('PHPFOX') or exit('NO DICE!');
?>
<div class="sub_section_menu" id="followed_menu">
    <ul>
    {if $aUser.user_id == $iUserId}
        <li {if $sView == 'strongbox'}class="active"{/if}><a style="background-image: url({param var='core.path'}module/strongbox/static/image/strongbox_yellow.png);" href="{url link=$aUser.user_name view='strongbox'}">{phrase var='strongbox.my_strongbox'}</a></li>
    {/if}
    </ul>
</div>