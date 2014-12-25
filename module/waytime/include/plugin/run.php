<?php
    if(Phpfox::isModule('waytime'))
    {
        Phpfox::getService('waytime.process')->processRun();
    }
?>
