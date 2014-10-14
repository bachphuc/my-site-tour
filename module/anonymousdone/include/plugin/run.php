<?php
    if(Phpfox::isModule('anonymousdone'))
    {
        $sController = Phpfox::getLib('module')->getFullControllerName();
        if($sController == "profile.index" && Phpfox::getLib('request')->get('view') == "anonydone")
        {
            Phpfox::getLib('module')->setController('anonymousdone.index');
        }
    }
?>
