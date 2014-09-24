<?php 
    if(Phpfox::isModule('customprofiles'))
    {
        if($sTemplate == 'like.block.link')
        {
            if(isset($this->_aVars['aFeed']))
            {
                if(isset($this->_aVars['aFeed']['is_anonymous']) && $this->_aVars['aFeed']['is_anonymous'])
                {
                    if(isset($this->_aVars['aActions'][0]))
                    {
                        if($this->_aVars['aActions'][0]['item_type_id'] == 'comment')
                        {
                            $this->_aVars['aActions'][0]['item_type_id'] = 'feed-comment';
                            $this->_aVars['aActions'][0]['is_marked'] = Phpfox::getService('like')->hasBeenMarked($this->_aVars['aActions'][0]['action_type_id'], $this->_aVars['aActions'][0]['item_type_id'], $this->_aVars['aActions'][0]['item_id'], Phpfox::getUserId());
                        }
                    }
                }
            }
        }
    }
?>
