<?php
    if(Phpfox::isModule('customprofiles'))
    {
        // Get anonymous feeds
        if(Phpfox::isModule('customprofiles'))
        {
            if($iFeedId == null && !(Phpfox::isModule('privacy') && Phpfox::getUserParam('privacy.can_view_all_items')))
            {
                $this->database()->select('feed.*')
                ->from($this->_sTable, 'feed')
                ->join(Phpfox::getT('custom_profiles_anonymous_feed'),'cf','cf.feed_id=feed.feed_id')
                ->where('cf.user_id='.Phpfox::getUserId().' OR (cf.receive_user_id='.Phpfox::getUserId().' AND cf.user_id NOT IN (SELECT cb.block_user_id FROM '.Phpfox::getT('custom_profiles_block').' AS cb WHERE cb.user_id = '.Phpfox::getUserId().'))')
                ->union();
            }
        }
    }
?>
