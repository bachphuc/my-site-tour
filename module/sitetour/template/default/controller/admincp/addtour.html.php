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
<div class="main_beark">
    <div class="table_header">
        {phrase var='sitetour.add_new_tour_backend'}
    </div>
    {$sCreateJs}
    <form method="post" action="{url link='admincp.sitetour.addtour'}" id="core_js_tour_form" onsubmit="{$sGetJsForm}" enctype="multipart/form-data">
        <div class="table">
            <div class="table_left">
                <label for="name">{required}{phrase var='sitetour.tour_name'}:</label>
            </div>
            <div class="table_right">
                <input type="text" name="val[name]" value="{value type='input' id='name'}" id="name" size="40" />
            </div>			
        </div>
        <div class="table">
            <div class="table_left">
                <label for="name">{required}{phrase var='sitetour.site_tour_link'}:</label>
            </div>
            <div class="table_right">
                <input type="text" name="val[url]" value="{value type='input' id='url'}" id="url" size="50" />
            </div>			
        </div>
        <div class="table">
            <div class="table_left">
                <label for="text">{required}{phrase var='sitetour.visiable_with_user_group'}:</label>
            </div>
            <div class="table_right">
                {foreach from=$aGroups key=iKey item=aGroup}
                 <label><input value="{$aGroup.user_group_id}" {if $aGroup.user_group_id=1}checked='yes'{/if} type="radio" name="val[user_group]" id="user_group" class="checkbox" {value type='checkbox' id='user_group' default='1'}/> {$aGroup.title}</label>
                {/foreach}
            </div>			
        </div>
        <div class="table">
            <div class="table_left">
                <label for="text">{required}{phrase var='sitetour.is_auto_run'}:</label>
            </div>
            <div class="table_right">
                <label><input value="1" checked="yes" type="radio" name="val[is_auto]" id="is_auto" class="checkbox" {value type='checkbox' id='is_auto' default='1'}/> {phrase var='core.yes'}</label>
                 <label><input value="0" type="radio" name="val[is_auto]" id="is_auto" class="checkbox" {value type='checkbox' id='is_auto' default='0'}/> {phrase var='core.no'}</label>
            </div>
        </div>
        <div class='table'>
            <input type="submit" name="val[submit]" value="{phrase var='sitetour.add'}" class="button" />
        </div>
    </form>
</div>