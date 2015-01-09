<?php
    
?>

{literal}
<style type="text/css">
    .desoft_menu{
        list-style: none;
    }
    .desoft_menu li{
        float: left;
        display: block;
        padding: 5px;
        cursor: pointer;
    }
    .desoft_menu .clear{
        float: none;
        height: 1px;
        padding: 0;
        border-bottom: 2px solid red;
        cursor: default;
    }
    .desoft_menu li:hover{
        background-color: #CCC;
    }
</style>
{/literal}

<ul class="desoft_menu">
    <li><a href="{url link=$aUser.user_name}" class="js_hover_title"><img src="{param var='core.path'}module/customprofiles/static/image/icons/mypost.png"><span class="js_hover_info">My Post</span></a></li>
    
    <li><a href="{url link=$aUser.user_name view='followed'}" class="js_hover_title"><img src="{param var='core.path'}module/customprofiles/static/image/icons/followpost.png"><span class="js_hover_info">Follow Post</span></a></li>
    
    <li><a href="{url link=$aUser.user_name view='private'}" class="js_hover_title"><img src="{param var='core.path'}module/customprofiles/static/image/icons/privatepost.png"><span class="js_hover_info">Private Post</span></a></li>
    
    <li><a href="{url link=$aUser.user_name view='strongbox'}" class="js_hover_title"><img src="{param var='core.path'}module/customprofiles/static/image/icons/strongbox_yellow.png"><span class="js_hover_info">My Strong Box</span></a></li>
    
    <li><a href="{url link=$aUser.user_name view='anonydone'}" class="js_hover_title"><img src="{param var='core.path'}module/customprofiles/static/image/icons/anonymousdone.png"><span class="js_hover_info">Anonymous Post Done</span></a></li>
    
    <li><a href="{url link=$aUser.user_name view='anonyreceived'}" class="js_hover_title"><img src="{param var='core.path'}module/customprofiles/static/image/icons/anonymousreceive.png"><span class="js_hover_info">Anonymous Post Receive</span></a></li>
    
    <li class="clear"></li>
</ul>