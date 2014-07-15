<?php
    if(Phpfox::isModule('customprofiles'))
    {
        if(!isset($this->_aVars['aFeed']['is_check']))
        {
            $iActualFeedId = Phpfox::getService('customprofiles')->getActualFeedId($this->_aVars['aFeed']);
            $aNonymousFeed = Phpfox::getService('customprofiles')->getAnonymousFeed($iActualFeedId);
            if(isset($aNonymousFeed['anonymous_id']) && isset($this->_aVars['aFeed']['parent_user']))
            {
                $this->_aVars['aFeed']['user_name'] = $this->_aVars['aFeed']['parent_user']['parent_user_name'];
                $this->_aVars['aFeed']['full_name'] = '<span class="post_title">'.Phpfox::getPhrase('customprofiles.awayter_post_about').' </span>'.$this->_aVars['aFeed']['parent_user']['parent_full_name'];
                $this->_aVars['aFeed']['user_id'] = $this->_aVars['aFeed']['parent_user']['parent_user_id'];
                $this->_aVars['aFeed']['user_image'] = $this->_aVars['aFeed']['parent_user']['parent_user_image'];
                $this->_aVars['aFeed']['feed_status'] = Phpfox::getPhrase('customprofiles.anonymous_post').$this->_aVars['aFeed']['feed_status'];
                unset($this->_aVars['aFeed']['parent_user']);
            }
        }
    }
?>
