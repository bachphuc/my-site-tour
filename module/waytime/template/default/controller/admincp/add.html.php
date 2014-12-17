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
	{if isset($bIsEdit) && $bIsEdit}{phrase var='waytime.edit_question'}{else}{phrase var='waytime.add_new_question'}{/if}
</div>
<form method="post" action="{url link='admincp.waytime.add'}">
	{if isset($bIsEdit) && $bIsEdit}
	<div><input type="hidden" name="id" value="{$iEditId}" /></div>
	{/if}
	
	<div class="table">
		<div class="table_left">
			{phrase var='waytime.question'}:
		</div>
		<div class="table_right">
            <textarea name="val[title]">{value id='title' type='input'}</textarea>
		</div>
		<div class="clear"></div>		
	</div>
	
	<div class="table_clear">
		<input type="submit" value="Submit" class="button" />
	</div>
</form>