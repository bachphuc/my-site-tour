<?php
    if(isset($aRow['user_id']))
    {
        $aComment = $this->database()->select('*')
        ->from(Phpfox::getT('comment'))
        ->where('comment_id='.(int)$iId)
        ->execute('getRow');
        if(isset($aComment['comment_id']))
        {
            if($aComment['type_id'] == 'feed')
            {
                $sType = 'feed_comment';
                $aFeed = Phpfox::getService('customprofiles')->getFeedItem($aComment['item_id'],$sType);
                if(isset($aFeed['feed_id']))
                {
                    $aNonymousFeed = Phpfox::getService('customprofiles')->getAnonymousFeed($aFeed['feed_id']);
                    if(isset($aNonymousFeed['anonymous_id']))
                    {
                        if(Phpfox::getUserId() == $aNonymousFeed['receive_user_id'])
                        {
                            $aRow['user_id'] = Phpfox::getUserId();
                        }
                    }
                    else
                    {
                        if(Phpfox::getUserId() == $aFeed['user_id'])
                        {
                            $aRow['user_id'] = Phpfox::getUserId();
                        }
                    }
                }
            }
            else
            {
                $aFeed = Phpfox::getService('customprofiles')->getFeedItem($aComment['item_id'],$aComment['type_id']);
                if(Phpfox::getUserId() == $aFeed['user_id'])
                {
                    $aRow['user_id'] = Phpfox::getUserId();
                }
            }
        }
    }
?>
