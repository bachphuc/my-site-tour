<?php
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright        [PHPFOX_COPYRIGHT]
 * @author          Miguel Espinoza
 * @package         Phpfox
 * @version         $Id: activity.html.php 602 2009-05-29 10:52:44Z Raymond_Benc $
 */
defined('PHPFOX') or exit('NO DICE!');
?>
<div class="egift_wrapper">
    {if count($aEgifts)}
    <link type="text/css" rel="stylesheet" href="{param var='core.path'}/module/egift/static/css/default/default/display.css">
    <script type="text/javascript">
        loadGift();
    </script>
    <div class="egift_selector{if Phpfox::getService('profile')->timeline()}_timeline{/if}">
        <select onchange="if (!empty(this.value)) {l} showGiftsByCategory(); {r}" id="selectCategory">
            {foreach from=$aCategories name=giftcategories item=aCat}
            <option value="{$aCat.category_id}">{phrase var=$aCat.phrase}</option>
            {/foreach}
        </select>        
    </div>
    <div class="extra_info" {if Phpfox::getService('profile')->timeline()} style="width:65%;"{/if}>
        {phrase var='egift.you_can_choose_an_egift_to_send'}
    </div>
    <div class="egift_selection">
        {foreach from=$aEgifts key=sName name=row item=aCategory}
        <div id="egift_item_cat_{$sName}" class="egift_category_holder">
            {foreach from=$aCategory key=iKey name=egift_item item=aGift}
                <div class="egift_item {if $aGift.price != '0.00'}egift_item_with_price{/if}" id="egift_item_{$aGift.egift_id}" onclick="setEgift({$aGift.egift_id},this);">
                    <div class='js_hover_title'>{img server_id=$aGift.server_id path='egift.url_egift' file=$aGift.file_path suffix='_75_square' max_width=75 max_height=75}<span class="js_hover_info">{$aGift.title}</span></div>
                    <div class="extra_info">
                    {if $aGift.price == '0.00'}
                        {phrase var='marketplace.free'}
                    {else}
                        {$aGift.currency_id|currency_symbol}{$aGift.price|number_format:2}
                    {/if}
                    </div>
                </div>
                {if (is_int($phpfox.iteration.egift_item/3))}
                    <div class="clear"></div>
                {/if}
            {/foreach}
        </div>
        {/foreach}
    </div>
    {/if}

</div>
