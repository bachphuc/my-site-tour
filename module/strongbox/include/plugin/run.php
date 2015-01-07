<?php
    if(Phpfox::isModule('strongbox'))
    {
        $sController = Phpfox::getLib('module')->getFullControllerName();
        if($sController == "profile.index" && Phpfox::getLib('request')->get('view') == "strongbox")
        {
            Phpfox::getLib('module')->setController('strongbox.index');
        }
    }
?>
