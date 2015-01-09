<?php
    defined('PHPFOX') or exit('NO DICE!');
?>

{if $sModule == 'waytame'}
{literal}
<script type="text/javascript">
    $Behavior.initProfileMenu = function(){
        $('.sub_section_menu li').removeClass('active');
        $('.bt_waytame_menu').addClass('active');
    }
</script>
{/literal}
{/if}
<div class="sub_section_menu">
    <ul>        
        <li class="bt_waytame_menu" class="active">
            <a style="background-image: url('{param var='core.path'}module/waytame/static/image/question.png');" href="{$sLink}">Waytame</a>
        </li>
    </ul>
</div>