<?php
    defined('PHPFOX') or exit('NO DICE!');
?>

<link rel="stylesheet" href="{param var='core.path'}module/customprofiles/static/css/default/default/style.css" type="text/css">
<script type="text/javascript" src="{param var='core.path'}module/customprofiles/static/jscript/script.js"></script>

<link rel="stylesheet" href="{param var='core.path'}module/customprofiles/static/css/default/default/blockstyle.css" type="text/css">
<script type="text/javascript" src="{param var='core.path'}module/customprofiles/static/jscript/blockscript.js"></script>

<div style="height: 25px; font-size: 16px; font-weight: bold; color: #c85727; margin-left: 15px;">Anonymous message</div>
<div class="sendbox">
    <form action="" method="POST" onsubmit="if($Core.checkData()){l}$('.anonymous_loading').fadeIn();$(this).ajaxCall('customprofiles.addFeed');{r}return false;">
        <div class="row_box" style="border-top: 3px #f78d1e solid;">
            <label id="lb_error_invalid_name" class="invalid_email">{phrase var='customprofiles.this_field_cannot_be_empty'}</label>
            <input name="val[full_name]" id="tb_friend" type="text" placeholder="{phrase var='customprofiles.search_wayter'}" class="mtextbox" autocomplete="off">
        </div>
        <div class="tag_panel">
            <div class="tag_panel_clear"></div>
        </div>
        <div class="email_panel" style="display: none;">
            <input id="tb_email" name="val[email]" autocomplete="off" type="text" placeholder="{phrase var='customprofiles.enter_your_friend_email_to_invite'}">
            <input id="cb_is_friend" type="hidden" value="0" name="val[is_not_friend]">
            <label id="lb_error_invalid_email" class="invalid_email">{phrase var='customprofiles.your_email_is_invalid'}</label>
            <label id="lb_error_email_empty" class="invalid_email">{phrase var='customprofiles.this_field_cannot_be_empty'}</label>
        </div>
        <div>
            <input id="egift_id" type="hidden" value="" name="val[egift_id]">
        </div>

        <div class="row_box">
            <label id="tb_error_invalid_message" class="invalid_email">{phrase var='customprofiles.this_field_cannot_be_empty'}</label>
            <textarea onchange="$Core.checkAnonymousStatus();" name="val[message]" placeholder="Write your message..." id="message" class="mtextarea"></textarea>
        </div>

        <div class="controll_panel" style="display: none;">
            <div id="egift_selected"></div>
            <div style="float: left;margin-left: 10px;" class="panel_anonymous_option">
                <input type="hidden" id="schedule_time_value" name="val[time_delay]" value="0" >

                <img src="{param var='core.path'}module/customprofiles/static/image/time.png" alt="" title="{phrase var='customprofiles.schedule_your_post_future'}" value="{phrase var='customprofiles.title_time'}" id="button_clock" class="img_button">
                <img src="{param var='core.path'}module/customprofiles/static/image/smile.png" alt=""  onclick="$Core.box('emoticon.preview', 'height=400&width=400&editor_id=message');return false" id="btn_emoticon" class="img_button">   
                <img src="{param var='core.path'}module/customprofiles/static/image/gift.png" alt=""  onclick="$Core.box('customprofiles.showGift', 'height=400&width=400');return false;" id="btn_gift" class="img_button">

            </div>
            
            <div style="float: right; margin-right: 10px;">
                <img class="anonymous_loading" src="{param var='core.path'}module/customprofiles/static/image/load.gif">
                <input type="submit" value="Send" id= "btnSend" class="mbutton">
            </div>
            
            {template file='customprofiles.block.expire'}
            
            <div style="clear:both;padding-bottom:5px;"></div>
            <label id="label_time_future" hidden="true" style="line-height: 30px; font-weight: bold;margin-left: 10px;"></label>
        </div>
    </form>
</div>

<div id="dialog_edit" title="Basic dialog" style="text-align: center;display: none;">

    <div>{phrase var='customprofiles.edit_delete_scheduling'}</div></br>
    <div>
        <input id="edit_schedule" type="button" class="button" value="{phrase var='customprofiles.button_edit'}">
        <input id="delete_buttonschedule" type="button" class="button" value="{phrase var='customprofiles.button_delete'}">
    </div>

</div>

<div id="dialog_delete" title="Basic dialog" style="text-align: center;display: none;">

    <div>{phrase var='customprofiles.are_you_sure_you_want_to_delete_this_scheduling'}</div></br>
    <div>
        <input id="delete_time" type="button" class="button" value="{phrase var='customprofiles.button_delete'}">
    </div>

</div>

<div id="dialog_calender" style="display: none;">
    <div>{phrase var='customprofiles.choose_date_time'}</div></br>
    <div><label id="error_time" style="color: red; font-weight: bold;" hidden="true">{phrase var='customprofiles.invalid_time'}</label></div>
    <div> 
        <div class="div_time">
            <p>&nbsp;Hour:</p>
            <select id="time_hour">
                {for $i =0 ; $i < 24 ; $i++}
                {if $i<10}
                <option value="{$i}">0{$i}</option>
                {else}
                <option value="{$i}">{$i}</option>
                {/if}
                {/for}
            </select>
        </div>
        <div class="div_time">
            <p>&nbsp;Minute:</p>
            <select id="time_minute">
                {for $i =0 ; $i < 60 ; $i++}
                {if $i<10}
                <option value="{$i}">0{$i}</option>
                {else}
                <option value="{$i}">{$i}</option>
                {/if}  
                {/for}
            </select>
        </div>
        <div class="div_time">
            <p>&nbsp;Date:</p>
            <input  type="text" id="datepicker" tabindex="-1" readonly="readonly" value='10/6/2014'>
            <input type="hidden" value="{param var='customprofiles.max_time_schedule'}" id="limit_time_schedule"> 
        </div>
    </div></br>
    <div style="clear: both;"></div></br>
    <div class="div_calendar_button">
        <input id="button_schedule"  type="button" class="button" value="{phrase var='customprofiles.button_schedule'}">
        <input id ="button_randomies" type="button" class="button" value="{phrase var='customprofiles.button_randomize'}">
    </div></br>
</div>