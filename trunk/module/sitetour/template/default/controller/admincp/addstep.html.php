<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright        [PHPFOX_COPYRIGHT]
 * @author          Raymond_Benc
 * @package         Phpfox
 * @version         $Id: add.html.php 5387 2013-02-19 12:19:37Z Miguel_Espinoza $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
<div class="table_header">
    Add a new step for tour in site {$aTour.title}
</div>


<form method="post" action="">
    
    <div class="table">
        <div class="table_left">
            URL:
        </div>
        <div class="table_right">
            <a href="{$aTour.url}">{$aTour.url}</a>
        </div>
        <div class="clear"></div>        
    </div>
    
    <div class="table">
        <div class="table_left">
            Element:
        </div>
        <div class="table_right">
            <input type="text" name="val[element]" value="{value id='title' type='input'}" size="30" />
        </div>
        <div class="clear"></div>        
    </div>
    
    <div class="table">
        <div class="table_left">
            {phrase var='sitetour.title'}:
        </div>
        <div class="table_right">
            <input type="text" name="val[title]" value="{value id='title' type='input'}" size="30" />
        </div>
        <div class="clear"></div>        
    </div>
    
    <div class="table">
        <div class="table_left">
            Content:
        </div>
        <div class="table_right">
            <input type="text" name="val[content]" value="{value id='title' type='input'}" size="30" />
        </div>
        <div class="clear"></div>        
    </div>
    
    <div class="table">
        <div class="table_left">
            Auto transition:
        </div>
        <div class="table_right">
            <input type="checkbox" name="val[is_auto]" value="1">
        </div>
        <div class="clear"></div>        
    </div>
    
    <div class="table">
        <div class="table_left">
            Time duration:
        </div>
        <div class="table_right">
            <input type="text" name="val[duration]" value="{value id='title' type='input'}" size="30" />
        </div>
        <div class="clear"></div>        
    </div>
    
    <div class="table_clear">
        <input name="addmore" type="submit" value="Add More" class="button" />
        <input name="submit" type="submit" value="Finished" class="button" />
    </div>
</form>