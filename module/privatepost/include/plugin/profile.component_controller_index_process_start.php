<?php
    if(Phpfox::isModule('customprofiles') && Phpfox::isModule('privatepost'))
    {
        $sController = Phpfox::getLib('module')->getFullControllerName();
        if($sController == "profile.index" && Phpfox::getLib('request')->get('view') == "private")
        {
            Phpfox::getComponent('privatepost.index', array('bNoTemplate' => true), 'controller');
        }
    }
?>

