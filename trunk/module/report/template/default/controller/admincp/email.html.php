<?php 
    /**
    * [PHPFOX_HEADER]
    * 
    * @copyright        [PHPFOX_COPYRIGHT]
    * @author          Raymond Benc
    * @package         Phpfox
    * @version         $Id: category.html.php 1347 2009-12-22 18:10:30Z Raymond_Benc $
    */

    defined('PHPFOX') or exit('NO DICE!'); 

?>
<form method="post" action="{url link='admincp.report.email'}">
    {if count($aEmails)}
    <table>
        <tr>
            <th style="width:10px;"><input type="checkbox" name="val[id]" value="" id="js_check_box_all" class="main_checkbox" /></th>
            <th>Title</th>
            <th>Body Template</th>        
        </tr>    
        {foreach from=$aEmails key=iKey item=aEmail}
        <tr id="js_row{$aEmail.template_id}" class="checkRow{if is_int($iKey/2)} tr{else}{/if}">
            <td><input type="checkbox" name="id[]" class="checkbox" value="{$aEmail.template_id}" id="js_id_row{$aEmail.template_id}" /></td>
            <td><a href="{url link='admincp.report.addemail' id=$aEmail.template_id}">{$aEmail.title|convert|clean}</a></td>
            <td>{$aEmail.body|clean}</td>        
        </tr>
        {/foreach}
    </table>
    <div class="table_bottom">
        <input type="submit" name="delete" value="Delete Selected" class="sJsConfirm delete button sJsCheckBoxButton disabled" disabled="true" />        
    </div>
    {else}
    <div class="extra_info">No email template found.</div>
    {/if}
</form>