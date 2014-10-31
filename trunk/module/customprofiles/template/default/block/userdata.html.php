<?php
    defined('PHPFOX') or exit('NO DICE!');
?>
{literal}
<style type="text/css">
    .table_user_data{
        color:#666;
        width:350px;
        float: left;
        background-color: transparent;
    } 
    .info_title{
        font-weight: bold;
        color:#555;
    }
    .user_data_image{
        float: right;
    }
</style>
<script type="text/javascript">
    $('#cb_list_email_template').change(function(){
        if($(this).val() != 0){
            $('#tb_email_content').val($(this).val());
        }
    });
</script>
{/literal}

<table class="table_user_data">
    <tr>
        <td class="info_title">Name and Surname:</td>
        <td>{$aUser.full_name}</td>
    </tr>
    <tr>
        <td class="info_title">Username:</td>
        <td>{$aUser.user_name}</td>
    </tr>
    <tr>
        <td class="info_title">User ID:</td>
        <td>{$aUser.user_id}</td>
    </tr>
    <tr>
        <td class="info_title">Email Address:</td>
        <td>{$aUser.email}</td>
    </tr>
    <tr>
        <td class="info_title">IP address:</td>
        <td>{$aUser.last_ip_address}</td>
    </tr>
    <tr>
        <td class="info_title">Last login:</td>
        <td>{$aUser.last_login|convert_time}</td>
    </tr>
    <tr>
        <td class="info_title">Last activity:</td>
        <td>{$aUser.last_activity|convert_time}</td>
    </tr>
    <tr>
        <td class="info_title">Registration Date:</td>
        <td>{$aUser.joined|convert_time}</td>
    </tr>
    <tr>
        <td class="info_title">Location:</td>
        <td>{$aUser.country_iso}</td>
    </tr>
    <tr>
        <td class="info_title">City:</td>
        <td>{$aUser.city_location}</td>
    </tr>
    <tr>
        <td class="info_title">Gender:</td>
        <td>{if $aUser.gender}Male{else}Female{/if}</td>
    </tr>
    <tr>
        <td class="info_title">Date of birth:</td>
        <td>{if !empty($aUser.birthday)}{$aUser.month}/{$aUser.day}/{$aUser.year}{/if}</td>
    </tr>
</table>
<div class="user_data_image">
    {img user=$aUser suffix='_100_square'}
</div>
<div class="clear"></div>
<div>
    <p style="color:#555;"><b>Send a message to this user</b></p>
</div>
<br />
<div>
    <select id="cb_list_email_template" style="width: 250px;padding:5px;">
        <option value="0">Select a Message</option>
        {foreach from=$aEmails item=aEmail}
        <option value="{$aEmail.body}">{$aEmail.title}</option>
        {/foreach}
    </select>
</div>
<br />
<form action="" method="POST" onsubmit="if($('#tb_email_content').val() == ''){l}alert('Please enter message. Message can not be null.');return false;{r};$(this).ajaxCall('customprofiles.sendEmail');return false;">
    <div>
        <input type="hidden" name="val[to]" value="{$aUser.user_id}">
        <textarea id="tb_email_content" name="val[message]" style="width: 250px;min-height: 100px;"></textarea>
    </div>
    <br />
    <div>
        <input type="submit" value="Send" class="button">
    </div>
</form>