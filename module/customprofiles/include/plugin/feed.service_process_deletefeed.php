<?php
    $aNonymousFeed = Phpfox::getService('customprofiles')->getAnonymousFeed($iId);
    if(isset($aNonymousFeed['anonymous_id']))
    {
        $this->database()->delete(Phpfox::getT('feed'),'feed_id='.(int)$iId);
        $this->database()->delete(Phpfox::getT('feed_comment'),'feed_comment_id='.(int)$aFeed['item_id']);
        $this->database()->delete(Phpfox::getT('custom_profiles_anonymous_feed'),'feed_id='.(int)$iId);
    }
?>
