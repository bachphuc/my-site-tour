<?php
    defined('PHPFOX') or exit('NO DICE!');
?>
<form action="" method="POST" onsubmit="$(this).ajaxCall('waytame.addQestionProcess');return false;">
    <input type="hidden" value="{$iExpireTime}" name="val[expire_time]">
    <div class="waytame_description"><label>{phrase var='waytame.our_description_here'}</label></div>
    <div class="waytame_row"><label>{phrase var='waytame.make_your_question'}</label></div>
    <div class="waytame_row"><input type="text" name="val[question]"></div>
    <div class="waytame_row"><label>{phrase var='waytame.your_answer'}</label></div>
    <div class="waytame_row"><input type="text" name="val[owner_answer]"></div>
    <div class="waytame_row"><label>{phrase var='waytame.expires_on'} {$iExpireTime|convert_time:'waytame.format_expire_time'}</label></div>

    <div class="js_box_close">
        <a onclick="$(this).closest('form').submit();return false;">{phrase var='waytame.create'}</a>
        <a onclick="return js_box_remove(this);">{phrase var='waytame.close'}</a>
    </div>
</form>