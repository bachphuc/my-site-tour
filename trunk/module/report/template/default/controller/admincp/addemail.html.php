<?php 
    /**
    * [PHPFOX_HEADER]
    * 
    * @copyright        [PHPFOX_COPYRIGHT]
    * @author          Raymond Benc
    * @package         Phpfox
    * @version         $Id: add.html.php 6474 2013-08-20 06:58:29Z Raymond_Benc $
    */

    defined('PHPFOX') or exit('NO DICE!'); 

?>
<form method="post" action="{url link='admincp.report.addemail'}">
    {if $bIsEdit}
    <div><input type="hidden" name="id" value="{$aForms.template_id}" /></div>
    {/if}
    <div class="table_header">Email template detail</div>
    <div class="table">
        <div class="table_left">Title</div>
        <div class="table_right">
            <input type="text" name="val[title]" value="{value type='input' id='title'}" size="40" maxlength="100" />
        </div>
        <div class="clear"></div>
    </div>
    
    <div class="table">
        <div class="table_left">Body Email Template</div>
        <div class="table_right">
            <textarea name="val[body]" style="width: 255px;min-height: 150px;" maxlength="500">{value type='input' id='body'}</textarea>
        </div>
        <div class="clear"></div>
    </div>
    
    <div class="table_clear">
        <input type="submit" value="{if $bIsEdit}{phrase var='report.update'}{else}{phrase var='report.add'}{/if}" class="button" />
    </div>
</form>