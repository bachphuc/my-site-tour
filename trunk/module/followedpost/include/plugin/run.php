<?php
    if(Phpfox::isModule('followedpost'))
    {
        $sController = Phpfox::getLib('module')->getFullControllerName();
        if($sController == "profile.index" && Phpfox::getLib('request')->get('view') == "followed")
        {
            Phpfox::getLib('module')->setController('followedpost.index');
        }
    }
?>
