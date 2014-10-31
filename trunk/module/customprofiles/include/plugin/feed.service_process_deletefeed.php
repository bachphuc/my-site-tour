<?php
    $aNonymousFeed = Phpfox::getService('customprofiles')->getAnonymousFeed($iId);
    if(isset($aNonymousFeed['anonymous_id']))
    {
        $this->database()->update(Phpfox::getT('feed'), array('is_delete' => 1),'feed_id='.(int)$iId);
    }
?>
