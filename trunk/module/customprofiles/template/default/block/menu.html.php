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
    <li><a title="My Post"><img src="{param var='core.path'}module/customprofiles/static/image/icons/mypost.png"></a></li>
    <li><a title="Follow Post"><img src="{param var='core.path'}module/customprofiles/static/image/icons/followpost.png"></a></li>
    <li><a title="Private Post"><img src="{param var='core.path'}module/customprofiles/static/image/icons/privatepost.png"></a></li>
    <li><a title="My Strong Box"><img src="{param var='core.path'}module/customprofiles/static/image/icons/strongbox_yellow.png"></a></li>
    <li><a title="Anonymous Post Done"><img src="{param var='core.path'}module/customprofiles/static/image/icons/anonymousdone.png"></a></li>
    <li><a title="Anonymous Post Receive"><img src="{param var='core.path'}module/customprofiles/static/image/icons/anonymousreceive.png"></a></li>
    <li class="clear"></li>
</ul>