<?php
    if(Phpfox::isModule('customprofiles'))
    {
        $ooRequest = Phpfox::getLib('request');
        $val = $ooRequest->get('val');
        if(isset($val['feed_expire_time']))
        {
            $_SESSION['expire_time'] = $val['expire_time'];
			Phpfox::setCookie('invited_by_email', 0, '-1');	
        }
		else if(Phpfox::getCookie('feed_expire_time'))
		{
			$_SESSION['expire_time'] = Phpfox::getCookie('feed_expire_time');
			Phpfox::setCookie('invited_by_email', 0, '-1');	
		}
    }
?>
