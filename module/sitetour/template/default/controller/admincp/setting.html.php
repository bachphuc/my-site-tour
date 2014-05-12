<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright        [PHPFOX_COPYRIGHT]
 * @author          Raymond Benc
 * @package         Phpfox
 * @version         $Id: add.html.php 1121 2009-10-01 12:59:13Z Raymond_Benc $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>

<div class="table_header">
    Setting user group for tour
</div>

<table id="js_drag_drop" cellpadding="0" cellspacing="0">
    <tr>
        <th>{phrase var='sitetour.title'}</th>
        <th class="t_center" style="width:60px;">{phrase var='sitetour.active'}</th>    
    </tr>
    {foreach from=$aGroups.special key=iKey item=aGroup}
    <tr class="checkRow{if is_int($iKey/2)} tr{else}{/if}">   
        <td>{$aGroup.title|convert}</td>
        <td class="t_center">
            <div class="js_item_is_active">
                <a href="#?call=sitetour.updateActivity&amp;id={$aGroup.user_group_id}&amp;active=0&amp;step=1" class="js_item_active_link" title="{phrase var='sitetour.deactivate'}">{img theme='misc/bullet_green.png' alt=''}</a>
            </div>
            <div class="js_item_is_not_active">
                <a href="#?call=sitetour.updateActivity&amp;id={$aGroup.user_group_id}&amp;active=1&amp;step=1" class="js_item_active_link" title="{phrase var='sitetour.active'}">{img theme='misc/bullet_red.png' alt=''}</a>
            </div>        
        </td>        
    </tr>
    {/foreach}
</table>