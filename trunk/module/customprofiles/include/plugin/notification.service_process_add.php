<?php
    if(Phpfox::isModule('customprofiles'))
    {
        // hau@gmail.com
        if(strpos($sType,";") !== false)
        {
            $aTime = explode(";", $sType);
            $aInsert = array(
                'type_id' => $aTime[0],
                'item_id' => $iItemId,
                'user_id' => $iOwnerUserId,    
                'owner_user_id' => ($iSenderUserId === null ? Phpfox::getUserId() : $iSenderUserId),
                'time_stamp' => (int)$aTime[1]        
            );    

            $this->database()->insert($this->_sTable, $aInsert);  
            $bDoNotInsert = true;
        }
        // hau@gmail.com
        // phuclb@npfox.com
        if($sType == 'comment_feed')
        {
            $aFeedComment = $this->database()->select('*')
            ->from(Phpfox::getT('feed'),'feed')
            ->join(Phpfox::getT('custom_profiles_anonymous_feed'),'cf','feed.feed_id=cf.feed_id')
            ->where('feed.item_id='.(int)$iItemId)
            ->execute('getRow');
            if(isset($aFeedComment['feed_id']))
            {
                $aVals = Phpfox::getLib('request')->get('val');

                $sType = 'customprofiles_commentshowname';
                $bInserted = false;
                // Send notification for sender
                if(Phpfox::getUserId() !=  $iOwnerUserId && ($iOwnerUserId == $aFeedComment['user_id'] || ($iOwnerUserId != $aFeedComment['user_id'] && $iOwnerUserId != $aFeedComment['receive_user_id'] && (int)$aFeedComment['privacy'] == 1)))
                {
                    $aInsert = array(
                        'type_id' => $sType,
                        'item_id' => $iItemId,
                        'user_id' => $iOwnerUserId,    
                        'owner_user_id' => ($iSenderUserId === null ? Phpfox::getUserId() : $iSenderUserId),
                        'time_stamp' => PHPFOX_TIME      
                    );   
                    $bInserted = true;
                    $iNotificationId = $this->database()->insert($this->_sTable, $aInsert);  

                    $aInsertNotification = array(
                        'notification_id' => $iNotificationId,
                        'full_name' => Phpfox::getUserBy('full_name'),
                        'message' => $aVals['text'],
                        'is_show' => (isset($aVals['show_your_name']) ? 1 : 0)
                    );

                    $this->database()->insert(Phpfox::getT('custom_profiles_show_name'),$aInsertNotification);
                }
                // End send for sender
                
                // Send notificaton for receiver
                if(Phpfox::getUserId() !=  $aFeedComment['receive_user_id'] && !defined('ADD_NOTIFICATION_SENDER') )
                {
                    define('ADD_NOTIFICATION_SENDER', true);
                    $aInsert = array(
                        'type_id' => $sType,
                        'item_id' => $iItemId,
                        'user_id' => $aFeedComment['receive_user_id'],    
                        'owner_user_id' => ($iSenderUserId === null ? Phpfox::getUserId() : $iSenderUserId) ,
                        'time_stamp' => PHPFOX_TIME      
                    );    

                    $iNotificationId = $this->database()->insert($this->_sTable, $aInsert);  

                    $aInsertNotification = array(
                        'notification_id' => $iNotificationId,
                        'full_name' => Phpfox::getUserBy('full_name'),
                        'message' => $aVals['text'],
                        'is_show' => (isset($aVals['show_your_name']) ? 1 : 0)
                    );

                    $this->database()->insert(Phpfox::getT('custom_profiles_show_name'),$aInsertNotification);
                }
                // End send for receiver
                
                $bDoNotInsert = true;
            }
        }
        define('PROCESS_ADD_COMMENT_ANONYMOUS_COMPLETE', true);
        // end phuclb@npfox.com
    }
?>