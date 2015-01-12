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
        cursor: pointer;
        width:32px;
        height: 32px;
        margin-right: 4px;
        margin-top:10px;
    }
    .desoft_menu li a{
        display: block;
    }
    .desoft_menu .clear{
        float: none;
        height: 1px;
        padding: 0;
        border-bottom: 2px solid red;
        cursor: default;
        width:100%;
    }
    .desoft_menu li:hover{
        background-color: #CCC;
    }
    .desoft_menu li.active{
        background-color:#666;
    }
</style>
{/literal}

<ul class="desoft_menu">
    <li {if empty($sView) && $sController == 'profile.index'}class="active"{/if}><a href="{url link=$aUser.user_name}" class="js_hover_title"><img src="{param var='core.path'}module/customprofiles/static/image/icons/wall.png"><span class="js_hover_info">My Post</span></a></li>
    
    {if Phpfox::isModule('followedpost')}
    <li {if $sView=='followed'}class="active"{/if}><a href="{url link=$aUser.user_name view='followed'}" class="js_hover_title"><img src="{param var='core.path'}module/customprofiles/static/image/icons/follow.png"><span class="js_hover_info">Follow Post</span></a></li>
    {/if}
    
    {if Phpfox::isModule('privatepost')}
    <li {if $sView=='private'}class="active"{/if}><a href="{url link=$aUser.user_name view='private'}" class="js_hover_title"><img src="{param var='core.path'}module/customprofiles/static/image/icons/private.png"><span class="js_hover_info">Private Post</span></a></li>
    {/if}
    
    {if Phpfox::isModule('strongbox')}
    <li {if $sView=='strongbox'}class="active"{/if}><a href="{url link=$aUser.user_name view='strongbox'}" class="js_hover_title"><img src="{param var='core.path'}module/customprofiles/static/image/icons/strongbox.png"><span class="js_hover_info">My Strong Box</span></a></li>
    {/if}
    
    {if Phpfox::isModule('customprofiles')}
    <li {if $sView=='expire'}class="active"{/if}><a href="{url link=$aUser.user_name view='expire'}" class="js_hover_title"><img src="{param var='core.path'}module/customprofiles/static/image/icons/expire.png"><span class="js_hover_info">Expiring posts</span></a></li>
    {/if}
    
    {if Phpfox::isModule('anonymousdone')}
    <li {if $sView=='anonydone'}class="active"{/if}><a href="{url link=$aUser.user_name view='anonydone'}" class="js_hover_title"><img src="{param var='core.path'}module/customprofiles/static/image/icons/anonymousdone.png"><span class="js_hover_info">Anonymous Post Done</span></a></li>
    {/if}
    
    {if Phpfox::isModule('anonymousreceived')}
    <li {if $sView=='anonyreceived'}class="active"{/if}><a href="{url link=$aUser.user_name view='anonyreceived'}" class="js_hover_title"><img src="{param var='core.path'}module/customprofiles/static/image/icons/anonymousreceive.png"><span class="js_hover_info">Anonymous Post Receive</span></a></li>
    {/if}
    
    {if Phpfox::isModule('waytime')}
    <li {if $sController == 'waytime.profile'}class="active"{/if}><a href="{url link=$aUser.user_name.'.waytime'}" class="js_hover_title"><img src="{param var='core.path'}module/customprofiles/static/image/icons/waytime.png"><span class="js_hover_info">W-Time Capsule</span></a></li>
    {/if}
    
    {if Phpfox::isModule('waytame')}
    <li {if $sController=='waytame.profile'}class="active"{/if}><a href="{url link=$aUser.user_name.'.waytame'}" class="js_hover_title"><img src="{param var='core.path'}module/customprofiles/static/image/icons/waytame.png"><span class="js_hover_info">Waytame</span></a></li>
    {/if}
    <li class="clear"></li>
</ul>