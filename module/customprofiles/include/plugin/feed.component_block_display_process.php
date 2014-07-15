<?php
    if(Phpfox::isModule('customprofiles'))
    {
        Phpfox::getService('customprofiles.process')->processFeed($aRows);
    }
?>
