<?php
    if(Phpfox::isModule('customprofiles') && Phpfox::isModule('privatepost') && false)
    {
        $sController = Phpfox::getLib('module')->getFullControllerName();
        if($sController == "profile.index" && Phpfox::getLib('request')->get('view') == "private")
        {
            Phpfox::getLib('module')->setController('privatepost.index');
        }
    }
?>