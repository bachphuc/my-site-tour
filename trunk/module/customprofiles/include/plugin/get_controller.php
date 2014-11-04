<?php
    if(Phpfox::isModule('customprofiles'))
    {
        $ooRequest = Phpfox::getLib('request');
        $val = $ooRequest->get('val');
        if(isset($val['feed_expire_time']))
        {
            $_SESSION['expire_time'] = $val['expire_time'];
        }
    }
?>
