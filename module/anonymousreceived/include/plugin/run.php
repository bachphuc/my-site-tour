<?php
    if(Phpfox::isModule('anonymousreceived'))
    {
        $sController = Phpfox::getLib('module')->getFullControllerName();
        if($sController == "profile.index" && Phpfox::getLib('request')->get('view') == "anonyreceived")
        {
            Phpfox::getLib('module')->setController('anonymousreceived.index');
        }
    }
?>
