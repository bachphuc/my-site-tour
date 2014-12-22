<?php
    if(Phpfox::isModule('customprofiles'))
    {
        if(defined('PROCESS_ADD_COMMENT_ON_ANONYMOUS_FEED') && PROCESS_ADD_COMMENT_ON_ANONYMOUS_FEED && !defined('PROCESS_ADD_COMMENT_ANONYMOUS_COMPLETE'))
        {
            $iItemId = $aParams['item_id'];
            $aFeedComment = $this->database()->select('*')
            ->from(Phpfox::getT('feed'),'feed')
            ->join(Phpfox::getT('custom_profiles_anonymous_feed'),'cf','feed.feed_id=cf.feed_id')
            ->where('feed.item_id='.(int)$iItemId)
            ->execute('getRow');
            if(isset($aFeedComment['feed_id']))
            {
                $aVals = Phpfox::getLib('request')->get('val');

                $sType = 'customprofiles_commentshowname';

                // Send notificaton for receiver when no body comment
                if(Phpfox::getUserId() !=  $aFeedComment['receive_user_id'])
                {
                    $aInsert = array(
                        'type_id' => $sType,
                        'item_id' => $iItemId,
                        'user_id' => $aFeedComment['receive_user_id'],    
                        'owner_user_id' => Phpfox::getUserId(),
                        'time_stamp' => PHPFOX_TIME      
                    );    

                    $iNotificationId = $this->database()->insert(Phpfox::getT('notification'), $aInsert);  

                    $aInsertNotification = array(
                        'notification_id' => $iNotificationId,
                        'full_name' => Phpfox::getUserBy('full_name'),
                        'message' => $aVals['text'],
                        'is_show' => (isset($aVals['show_your_name']) ? 1 : 0)
                    );

                    $this->database()->insert(Phpfox::getT('custom_profiles_show_name'),$aInsertNotification);
                }
            }
        }
    }
?>
