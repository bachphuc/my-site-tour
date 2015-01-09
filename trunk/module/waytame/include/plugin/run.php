<?php
    if(Phpfox::isModule('waytame'))
    {
        Phpfox::getService('waytame.process')->addNotificationQuestionExpire();
        phpfox::getLib('template')->setHeader(array(
            'script.js'=>'module_waytame',
            'style.css' =>'module_waytame'
        ));
    }
?>
