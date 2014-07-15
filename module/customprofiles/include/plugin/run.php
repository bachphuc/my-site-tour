<?php
    if(Phpfox::isModule('customprofiles'))
	{
        Phpfox::getService('customprofiles')->setHeaders();
		Phpfox::getService('customprofiles.process')->updateScheduleFeed();
	}
?>
