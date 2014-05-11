<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond_Benc
 * @package 		Phpfox
 * @version 		$Id: add.html.php 5387 2013-02-19 12:19:37Z Miguel_Espinoza $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
<div class="table_header">
	{phrase var='sitetour.sitetour_detail'}
</div>

{if $bIsEdit}
<form method="post" action="{url link='admincp.sitetour.add'}">
    <div><input type="hidden" name="tour" value="{$aForms.sitetour_id}" /></div>
    {if isset($aForms.step_id)}
    <div><input type="hidden" name="step" value="{$iEditId}" /></div>
    {else}
    <div><input type="hidden" name="id" value="{$iEditId}" /></div>
    {/if}
    
    <div class="table">
        <div class="table_left">
            {phrase var='sitetour.title'}:
        </div>
        <div class="table_right">
            <input type="text" name="val[title]" value="{value id='title' type='input'}" size="30" />
        </div>
        <div class="clear"></div>        
    </div>
    {if isset($aForms.step_id)}
    <div class="table">
        <div class="table_left">
            {phrase var='sitetour.content'}:
        </div>
        <div class="table_right">
            <textarea style="width: 400px;" name="val[content]" >{$aForms.content}</textarea>
        </div>
        <div class="clear"></div>        
    </div>
    {/if}
    <div class="table_clear">
        <input type="submit" value="{phrase var='sitetour.submit'}" class="button" />
    </div>
</form>
{/if}