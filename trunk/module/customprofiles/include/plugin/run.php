<?php 
    if(Phpfox::isModule('customprofiles'))
    {
        Phpfox::getService('customprofiles')->setHeaders();
        Phpfox::getService('customprofiles.process')->updateScheduleFeed();
        
        $sController = Phpfox::getLib('module')->getFullControllerName();
        if($sController == "profile.index" && Phpfox::getLib('request')->get('view') == "expire")
        {
            Phpfox::getLib('module')->setController('customprofiles.expire');
        }
    }
?>