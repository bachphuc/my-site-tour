<?php 
    defined('PHPFOX') or exit('NO DICE!'); 
?>

{literal}
<style type="text/css">
    .table_clear a{
        text-decoration: none !important;
    }
</style>
{/literal}

<div class="table_header">
    {if $bSubCategory}{phrase var='waytime.manage_answers_for_question_id' id=$iId}{else}{phrase var='waytime.questions'}{/if}
</div>
<table id="js_drag_drop" cellpadding="0" cellspacing="0">
    <tr>
        <th style="width:20px;"></th>
        <th style="width: 50px;">ID</th>
        <th>{if $bSubCategory}{phrase var='waytime.answer'}{else}{phrase var='waytime.question'}{/if}</th>
    </tr>
    {foreach from=$aCategories key=iKey item=aCategory}
    <tr class="checkRow{if is_int($iKey/2)} tr{else}{/if}">
        <td class="t_center">
            <a href="#" class="js_drop_down_link" title="Manage">{img theme='misc/bullet_arrow_down.png' alt=''}</a>
            <div class="link_menu">
                <ul>
                    {if !$bSubCategory}<li><a href="{url link='admincp.waytime.add-answer' question-id=$aCategory.question_id}">{phrase var='waytime.add_new_answer'}</a></li>{/if}
                    <li><a href="{if $bSubCategory}{url link='admincp.waytime.add-answer' id=$aCategory.answer_id question-id=$iId}{else}{url link='admincp.waytime.add' id=$aCategory.question_id}{/if}">Edit</a></li>		
                    {if !$bSubCategory && isset($aCategory.number_answer) && $aCategory.number_answer}
                    <li><a href="{url link='admincp.waytime' sub={$aCategory.question_id}">{phrase var='waytime.manage_answers_total' total=$aCategory.number_answer}</a></li>		
                    {/if}
                    <li><a href="{if $bSubCategory}{url link='admincp.waytime' sub=$iId delete=$aCategory.answer_id}{else}{url link='admincp.waytime' delete=$aCategory.question_id}{/if}" onclick="return confirm('{phrase var='waytime.are_you_sure'}');">{phrase var='pages.delete'}</a></li>		
                </ul>
            </div>		
        </td>	
        <td>{if $bSubCategory}{$aCategory.answer_id}{else}{$aCategory.question_id}{/if}</td>
        <td>{if $bSubCategory}{$aCategory.answer|convert}{else}{$aCategory.title|convert}{/if}</td>	
    </tr>
    {/foreach}
</table>
{if $bSubCategory}
<div class="table_clear">
    <a href="{url link='admincp.waytime.add-answer' question-id=$iId}"><input type="button" value="{phrase var='waytime.add_new_answer'}" class="button" /></a>
</div>
{else}
<div class="table_clear">
    <a href="{url link='admincp.waytime.add'}"><input type="button" value="{phrase var='waytime.add_new_question'}" class="button" /></a>
</div>
{/if}