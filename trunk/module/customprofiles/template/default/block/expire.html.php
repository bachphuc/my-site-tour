<?php
    defined('PHPFOX') or exit('NO DICE!'); 
?>

{literal}
<style type="text/css">
    .expire_time_menu{
        float: right;
        position: relative;
        width: 45px;
    }
    .expire_time_menu>a{
        color: #333;
        display: block;
        font-weight: bold;
        line-height: 25px;
        overflow: hidden;
        padding: 0 0 0 25px;
        text-decoration: none;
        text-indent: -1000px;
        z-index: 1;
        background: url("{/literal}{param var='core.path'}{literal}module/customprofiles/static/image/expire_icon.png") no-repeat scroll 0 0 rgba(0, 0, 0, 0);
    }
    .expire_time_menu>a:hover{
        background: url('{/literal}{param var='core.path'}{literal}module/customprofiles/static/image/expire_icon.png') no-repeat scroll 0px -50px transparent;
    }
    .expire_active{
        background: url('{/literal}{param var='core.path'}{literal}module/customprofiles/static/image/expire_icon.png') no-repeat scroll 0px -25px transparent !important;
    }
    .expire_time_holder{
        border-top: 1px solid #333;
        background: none repeat scroll 0 0 #fff;
        border-bottom: 2px solid #333;
        border-left: 1px solid #333;
        border-right: 1px solid #333;
        display: none;
        position: absolute;
        width: 200px;
        z-index: 2000;
        right: 0;
    }
    .expire_time_holder ul{
        list-style-type: none;
        margin: 0;
        padding: 0;
    }
    .expire_time_holder ul a{
        display: block;
        line-height: 25px;
        padding: 0 0 0 25px;
        text-decoration: none;
    }
    .expire_time_holder ul a:hover{
        color: #FFF;
        background-color:#333;  
        
    }
    .active_expire{
        background-image: url("{/literal}{param var='core.path'}{literal}theme/frontend/default/style/default/image/misc/is_active_image.png");
        background-position: 5px 0;
        background-repeat: no-repeat;
    }
    .active_expire:hover{
        background-position: 5px -25px;
    }
    .controll_panel .expire_time_menu{
        top:4px;
    }
</style>
<script type="text/javascript">
    $Behavior.initExpireMenu = function(){
        $('.expire_time_menu>a').unbind('click').bind('click',function(e){
            if($(this).hasClass('expire_active')){
                $(this).removeClass('expire_active');
            }
            else{
                $(this).addClass('expire_active');
            }
            $(this).next('.expire_time_holder').toggle();
            e.stopPropagation();
            e.preventDefault();
            return false;
        });
        $('.expire_time_holder a').unbind('click').bind('click',function(e){
            $(this).closest('.expire_time_holder').find('.active_expire').removeClass('active_expire');
            $(this).addClass('active_expire');
            $(this).closest('.expire_time_menu').find('.expire_time').val($(this).attr('rel'));
            $(this).closest('.expire_time_holder').toggle();
            $('.expire_time_menu>a').removeClass('expire_active');
            e.stopPropagation();
            e.preventDefault();
            return false;
        });
        $(document).click(function(){
            $('.expire_time_holder').hide();
            $('.expire_time_menu>a').removeClass('expire_active');
        });
    }
</script>
{/literal}
{php}
    $iExpireTime = Phpfox::getParam('customprofiles.expire_post_time');
    $iYear = (int)($iExpireTime / 365);
    $iMonth = (int)(($iExpireTime - $iYear * 65) / 30);
    $iWeek = (int)(($iExpireTime - $iYear * 365 - $iMonth * 30) / 7);
    $iDay = $iExpireTime - $iYear * 365 - $iMonth * 30 - $iWeek * 7 ;
    $sTime = '';
    $sTime.= ($iYear ? $iYear.($iYear > 1 ? ' Years' : ' Year') : '').' ';
    $sTime.= ($iMonth ? $iMonth.($iMonth > 1 ? ' Months' : ' Month') : '').' ';
    $sTime.= ($iWeek ? $iWeek.($iWeek > 1 ? ' Weeks' : ' Week') : '').' ';
    $sTime.= ($iDay ? $iDay.($iDay > 1 ? ' Days' : ' Day') : '').' ';
    $iTotalSecond = $iExpireTime * 24 * 60 *60;
    $this->_aVars['iTotalSecond'] = $iTotalSecond;
    $this->_aVars['sTime'] = $sTime;
{/php}
<div class="expire_time_menu">
    <div><input type="hidden" value="{$iTotalSecond}" name="val[expire_time]" class="expire_time"></div>
    <div><input type="hidden" value="1" name="val[feed_expire_time]"></div>
    <a class="js_hover_title" href=""><span>1 Week</span><span class="js_hover_info">1 Week</span></a>
    <div class="expire_time_holder" style="display: none;">
        <ul>
            <li><a class="active_expire" rel="{$iTotalSecond}" href="">{$sTime}</a></li>
            <li><a rel="900" href="">15 Minutes</a></li>
            <li><a rel="1800" href="">30 Minutes</a></li>
            <li><a rel="3600" href="">1 Hour</a></li>
            <li><a rel="86400" href="">1 Day</a></li>
        </ul>
    </div>
</div>