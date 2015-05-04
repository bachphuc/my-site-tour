<?php
    /**
    * [PHPFOX_HEADER]
    */

    defined('PHPFOX') or exit('NO DICE!');

    /**
    * 
    * 
    * @copyright        [PHPFOX_COPYRIGHT]
    * @author          Raymond Benc
    * @package          Module_Event
    * @version         $Id: event.class.php 6139 2013-06-24 15:02:48Z Raymond_Benc $
    */
    class CustomProfiles_Service_Process extends Phpfox_Service 
    {
        public function addFeed($aVals)
        {
            $aUser = Phpfox::getService('user')->getUser(Phpfox::getUserId());
            if(!isset($aUser['user_id']))
            {
                return false;
            }
            $bIsFriend = false;
            if(isset($aVals['email']))
            {
                $aReceive = Phpfox::getService('customprofiles')->checkEmail($aVals['email']);
            }
            // if exist delay_time save feed schedule
            if(isset($aVals['time_delay']) && $aVals['time_delay'] > 0 && (!$aVals['is_not_friend'] || (isset($aReceive) && $aReceive)))
            {
                if(isset($aReceive) && isset($aReceive['user_id']) && $aVals['is_not_friend'])
                {
                    $aVals['friend_id'] = $aReceive['user_id'];
                }
                if(Phpfox::getService('friend')->isFriend(Phpfox::getUserId(),$aVals['friend_id']))
                {
                    $aVals['is_friend'] = true;
                }
                else
                {
                    $aVals['is_friend'] = false;
                }
                // Check if user block you
                if(Phpfox::getService('customprofiles')->checkBlockByUser($aVals['friend_id']))
                {
                    $aUser = Phpfox::getService('user')->getUser($aVals['friend_id']);
                    echo '$Core.resetAnonymousPost();'; 
                    Phpfox::getLib('ajax')->alert(Phpfox::getPhrase('customprofiles.has_blocked_you',array('full_name' => $aUser['full_name'])));
                    die();
                }
                $this->saveScheduleFeed($aVals);
                echo '$Core.resetActivityFeedForm();';
                echo '$Core.resetAnonymousPost();';  
                Phpfox::getLib('ajax')->alert(Phpfox::getPhrase('customprofiles.you_have_scheduled_to_post_a_anonymous_feed_to_other_user'));
                die();
            }
            $bIsGift = false;
            // check if type gift
            if (Phpfox::isModule('egift') && isset($aVals['egift_id']) && !empty($aVals['egift_id']))
            {
                /* is this gift a free one? */
                $aGift = Phpfox::getService('egift')->getEgift($aVals['egift_id']);
                if (!empty($aGift))
                {
                    $bIsGift = true;
                    $bIsFree = true;
                    foreach ($aGift['price'] as $sCurrency => $fVal)
                    {
                        if ($fVal > 0)
                        {
                            $bIsFree = false;
                        }
                    }    
                    $aVals['feed_type'] = 'feed_egift';
                    $aVals['parent_user_id'] = -1;
                    // Always make an invoice, so the feed can check on the state
                    if(!$aVals['is_not_friend'])
                    {
                        $aVals['parent_user_id'] = $aVals['friend_id'];
                    }
                    else
                    {
                        if($aReceive = Phpfox::getService('customprofiles')->checkEmail($aVals['email']))
                        {
                            $aVals['parent_user_id'] = $aReceive['user_id'];
                        }
                    }
                }
            }

            $iFeedId = 0;
            if(!$aVals['is_not_friend'])
            {
                $bIsFriend = true;
                // Check if user block you
                if(Phpfox::getService('customprofiles')->checkBlockByUser($aVals['friend_id']))
                {
                    $aUser = Phpfox::getService('user')->getUser($aVals['friend_id']);
                    echo '$Core.resetAnonymousPost();'; 
                    Phpfox::getLib('ajax')->alert(Phpfox::getPhrase('customprofiles.has_blocked_you',array('full_name' => $aUser['full_name'])));
                    die();
                }
                $iFeedId = $this->addFriendAnonymousMessage($aVals);
            }
            else
            {
                if($aReceive)
                {
                    if(Phpfox::getService('friend')->isFriend($aUser['user_id'],$aReceive['user_id']))
                    {
                        $bIsFriend = true;
                        $aVals['friend_id'] = $aReceive['user_id'];
                        // Check if user block you
                        if(Phpfox::getService('customprofiles')->checkBlockByUser($aVals['friend_id']))
                        {
                            $aUser = Phpfox::getService('user')->getUser($aVals['friend_id']);
                            echo '$Core.resetAnonymousPost();'; 
                            Phpfox::getLib('ajax')->alert(Phpfox::getPhrase('customprofiles.has_blocked_you',array('full_name' => $aUser['full_name'])));
                            die();
                        }
                        $iFeedId = $this->addFriendAnonymousMessage($aVals);
                    }
                    else
                    {
                        $aVals['friend_id'] = $aReceive['user_id'];
                        $aVals['is_friend'] = false;
                        // Check if user block you
                        if(Phpfox::getService('customprofiles')->checkBlockByUser($aVals['friend_id']))
                        {
                            $aUser = Phpfox::getService('user')->getUser($aVals['friend_id']);
                            echo '$Core.resetAnonymousPost();'; 
                            Phpfox::getLib('ajax')->alert(Phpfox::getPhrase('customprofiles.has_blocked_you',array('full_name' => $aUser['full_name'])));
                            die();
                        }
                        $this->saveScheduleFeed($aVals);
                    }
                }
                else
                {
                    return $this->inviteUserToAnonymousMessage($aVals);
                }
            }
            if($bIsGift && $iFeedId)
            {
                $iInvoice = Phpfox::getService('egift.process')->addInvoice($iFeedId, $aVals['parent_user_id'], $aGift);
            }

            if(!$bIsFriend)
            {
                echo '$Core.resetActivityFeedForm();';
                echo '$Core.resetAnonymousPost();';  
                Phpfox::getLib('ajax')->alert('Your anonymous post has been successfully sent');
                die();
            }
            return $iFeedId;
        }

        public function processFeed(&$aRows)
        {
            foreach($aRows as $key => $aRow)
            {
                if(isset($aRow['comments']))
                {
                    foreach($aRow['comments'] as $commentKey => $aComment)
                    {
                        $aRows[$key]['comments'][$commentKey]['full_name'] = Phpfox::getPhrase('customprofiles.a_wayter_commented');
                        $aRows[$key]['comments'][$commentKey]['owner_user_id'] = $aComment['user_id'];
                        $aRows[$key]['comments'][$commentKey]['user_id'] = 0;
                        $aRows[$key]['comments'][$commentKey]['user_image'] = "";
                        $aRows[$key]['comments'][$commentKey]['user_name'] = "";
                        $aRows[$key]['comments'][$commentKey]['is_check'] = true;
                    }
                }
                $aRows[$key]['owner_user_id'] = $aRow['user_id'];
                $aRows[$key]['is_check'] = true;
                if(!isset($aRow['feed_id']))
                {
                    continue;
                }

                $aNonymousFeed = Phpfox::getService('customprofiles')->getAnonymousFeed($aRow['feed_id']);
                if(isset($aNonymousFeed['anonymous_id']))
                {
                    $aRows[$key]['is_anonymous'] = true;
                    if(!isset($aRow['marks']))
                    {
                        $aRows[$key]['marks'] = Phpfox::getService('like')->getDislikes('feed-comment', $aRow['item_id']);
                    }
                    if(!isset($aRow['call_displayactions']))
                    {
                        $aActions = $this->getDislikes('feed-comment', $aRow['item_id']) ;
                        if (count($aActions) > 0)
                        {
                            $aRows[$key]['bShowEnterCommentBlock'] = true;
                            $aRows[$key]['call_displayactions'] = true;
                        }
                    }
                    if(isset($aRow['parent_user']))
                    {
                        $aRows[$key]['user_name'] = $aRow['parent_user']['parent_user_name'];
                        $aRows[$key]['full_name'] = '<span class="post_title">'.Phpfox::getPhrase('customprofiles.awayter_post_about').' </span>'.$aRow['parent_user']['parent_full_name'];
                        $aRows[$key]['user_id'] = $aRow['parent_user']['parent_user_id'];
                        $aRows[$key]['user_image'] = $aRow['parent_user']['parent_user_image'];
                        $aRows[$key]['feed_status'] = $aRows[$key]['feed_status'];
                    }

                    // process feed view more
                    if(isset($aRow['more_feed_rows']))
                    {
                        foreach($aRow['more_feed_rows'] as $mKey => $mRow)
                        {
                            $aMoreNonymousFeed = Phpfox::getService('customprofiles')->getAnonymousFeed($mRow['feed_id']);
                            if(isset($aMoreNonymousFeed['anonymous_id']))
                            {
                                if(!$aMoreNonymousFeed['is_active'])
                                {
                                    unset($aRows[$key]['more_feed_rows'][$mKey]);
                                    continue;
                                }
								if(isset($aRow['parent_user']) && $mRow['parent_user_id'] == $aRow['parent_user']['parent_user_id'])
								{
									$aRows[$key]['more_feed_rows'][$mKey]['user_name'] = $aRow['parent_user']['parent_user_name'];
									$aRows[$key]['more_feed_rows'][$mKey]['full_name'] =  '<span class="post_title">'.Phpfox::getPhrase('customprofiles.awayter_post_about').' </span>'.$aRow['parent_user']['parent_full_name'];
									$aRows[$key]['more_feed_rows'][$mKey]['user_id'] = $aRow['parent_user']['parent_user_id'];
									$aRows[$key]['more_feed_rows'][$mKey]['user_image'] = $aRow['parent_user']['parent_user_image'];
								}
								else
								{
									$aUser = Phpfox::getService('user')->getUser($mRow['parent_user_id']);
									$aRows[$key]['more_feed_rows'][$mKey]['user_name'] = $aUser['user_name'];
									$aRows[$key]['more_feed_rows'][$mKey]['full_name'] =  '<span class="post_title">'.Phpfox::getPhrase('customprofiles.awayter_post_about').' </span>'.$aUser['full_name'];
									$aRows[$key]['more_feed_rows'][$mKey]['user_id'] = $aUser['user_id'];
									$aRows[$key]['more_feed_rows'][$mKey]['user_image'] = $aUser['user_image'];
								}
                            }
                        }
                    }   
                    // end process feed view fore

                    unset($aRows[$key]['parent_user']);
                }
            }
        }

        public function addFriendAnonymousMessage($aVals)
        {
            $aUser = Phpfox::getService('user')->getUser(Phpfox::getUserId());
            // hau@gmail.com
            $iTimeSend = 0;
            $time = $aVals['time_delay'];

            $iTimeSend = PHPFOX_TIME + (int)$time;
            // end hau@gmail.com
            $sContent =  $aVals['message'] ;

            $iFeedComment = $this->database()->insert(Phpfox::getT('feed_comment'),array(
                'user_id' => Phpfox::getUserId(),
                'parent_user_id' => $aVals['friend_id'],
                'content' => $sContent,
                'time_stamp' => $iTimeSend
            ));

            $iExpireTime = 0;
            if(isset($_SESSION['expire_time']) && $_SESSION['expire_time'])
            {
                $iExpireTime = $iTimeSend + $_SESSION['expire_time'];
                unset($_SESSION['expire_time']);
            }
            $iFeedId = $this->database()->insert(Phpfox::getT('feed'),array(
                'type_id' => $aVals['feed_type'],
                'user_id' => Phpfox::getUserId(),
                'parent_user_id' => $aVals['friend_id'],
                'item_id' => $iFeedComment,
                'privacy' => 1,
                'feed_reference' => 0,
                'time_stamp' => $iTimeSend,
                'time_update' => $iTimeSend,
                'expire_time' => $iExpireTime
            ));

            $this->database()->insert(Phpfox::getT('custom_profiles_anonymous_feed'),array(
                'feed_id' => $iFeedId,
                'user_id' => Phpfox::getUserId(),
                'receive_user_id' => $aVals['friend_id'],
                'message' => $aVals['message'],
            ));
            if (Phpfox::isModule('notification'))
            {
                // create a nofitication for user about anonymous message will send in the future
                if($iTimeSend > PHPFOX_TIME)
                {
                    $this->addNotification('customprofiles_schedulecomplete',$iFeedComment,Phpfox::getUserId());
                }
                // hau@gmail.com
                $type_id = 'customprofiles_invitefirendsforfriend'.';'.$iTimeSend;
                Phpfox::getService('notification.process')->add($type_id, $iFeedComment, $aVals['friend_id']);              
            }
            return $iFeedId;
        }

        public function addUserAnonymousMessage($aVals)
        {
            $aUser = Phpfox::getService('user')->getUser(Phpfox::getUserId());
            // hau@gmail.com
            $iTimeSend = 0;
            $time = $aVals['time_delay'];
            $iTimeSend = PHPFOX_TIME + (int)$time;
            // end hau@gmail.com
            $sContent = $aVals['message'];

            $iFeedComment = $this->database()->insert(Phpfox::getT('feed_comment'),array(
                'user_id' => Phpfox::getUserId(),
                'parent_user_id' => $aVals['friend_id'],
                'content' => $sContent,
                'time_stamp' => $iTimeSend
            ));
            $iExpireTime = 0;
            if(isset($_SESSION['expire_time']) && $_SESSION['expire_time'])
            {
                $iExpireTime = $iTimeSend + $_SESSION['expire_time'];
                unset($_SESSION['expire_time']);
            }
            $iFeedId = $this->database()->insert(Phpfox::getT('feed'),array(
                'type_id' => $aVals['feed_type'],
                'user_id' => Phpfox::getUserId(),
                'parent_user_id' => $aVals['friend_id'],
                'item_id' => $iFeedComment,
                'privacy' => 1,
                'feed_reference' => 0,
                'time_stamp' => $iTimeSend,
                'time_update' => $iTimeSend,
                'expire_time' => $iExpireTime
            ));

            $this->database()->insert(Phpfox::getT('custom_profiles_anonymous_feed'),array(
                'feed_id' => $iFeedId,
                'user_id' => Phpfox::getUserId(),
                'receive_user_id' => $aVals['friend_id'],
                'message' => $aVals['message'],
                'is_active' => 0
            ));

            $iInvite = $this->database()->insert(Phpfox::getT('custom_profiles_invite_anonymous_message'),array(
                'user_id' => Phpfox::getUserId(),
                'invite_user_id' => $aVals['friend_id'],
                'feed_id' => $iFeedId,
                'time_stamp' => PHPFOX_TIME
            ));

            $sLink = Phpfox::getLib('url')->makeUrl('customprofiles.invite', array('id' => $iInvite));
            $bSent = Phpfox::getLib('mail')->to($aVals['email'])                      
            ->subject(Phpfox::getPhrase('customprofiles.you_received_an_anonymous_post_by_a_wayter'))
            ->message(array('customprofiles.message_invite_join_anonymous_message', array('link' => $sLink)))
            ->send();

            // Add notification confirm to receiver
            if (Phpfox::isModule('notification'))
            {
                if($iTimeSend > PHPFOX_TIME)
                {
                    $this->addNotification('customprofiles_schedulecomplete',$iFeedComment,Phpfox::getUserId());
                }
                $type_id = 'customprofiles_anonymousconfirm';
                Phpfox::getService('customprofiles.process')->addNotification($type_id, $iFeedId, $aVals['friend_id'],Phpfox::getUserId());             
            }
            // end add notification

            return $iFeedId;
        }

        public function inviteUserToAnonymousMessage($aVals)
        {
            try
            {
                $iInvite = Phpfox::getService('invite.process')->addInvite($aVals['email'], Phpfox::getUserId());
                $this->database()->clean();
            } 
            catch(Exception $e) 
            {
                echo 'Caught exception: ',  $e->getMessage(), "\n";
            }

            try
            {
                $sLink = Phpfox::getLib('url')->makeUrl('invite', array('id' => $iInvite));     
                $bSent = Phpfox::getLib('mail')->to($aVals['email'])                      
                ->subject(Phpfox::getPhrase('customprofiles.you_received_an_anonymous_post_by_a_wayter'))
                ->message(array('customprofiles.message_invite_join_anonymous_message', array('link' => $sLink)))
                ->send();

                $iFeedId = $this->saveFeed($aVals);
                $iAnonymousInvite = $this->database()->insert(Phpfox::getT('custom_profiles_invite'),array(
                    'user_id' => Phpfox::getUserId(),
                    'email' => $aVals['email'],
                    'full_name' => $aVals['full_name'],
                    'feed_id' => $iFeedId,
                    'message' => $aVals['message'],
                    'time_stamp' => PHPFOX_TIME
                ));
            }
            catch(Exception $e)
            {
                echo 'Caught exception: ',  $e->getMessage(), "\n";
            }

            echo '$Core.resetActivityFeedForm();';
            echo '$Core.resetAnonymousPost();';  
            Phpfox::getLib('ajax')->alert('Your anonymous post has been successfully sent');
            die();
        }

        public function acceptAnonymousPost($iFeedId)
        {
            $aNonymousMessage = Phpfox::getService('customprofiles')->getScheduleFeed($iFeedId);
            if(!isset($aNonymousMessage['feed_id']))
            {
                return false;
            }

            $aVals = unserialize($aNonymousMessage['object']);
            $aVals['force_create_feed'] = true;
            $iAnonymousFeedId = $this->createScheduleFeed($iFeedId,$aVals);
            if (Phpfox::isModule('notification'))
            {
                Phpfox::getService('notification.process')->add('customprofiles_replyinvite', $iFeedId,$aVals['sender_user_id']); 
            }
            return $iAnonymousFeedId;
        }

        public function recceptAnonymousPost($iFeedId)
        {
            $aNonymousMessage = Phpfox::getService('customprofiles')->getScheduleFeed($iFeedId);
            if(!isset($aNonymousMessage['feed_id']))
            {
                return false;
            }
            if (Phpfox::isModule('notification'))
            {
                Phpfox::getService('notification.process')->add('customprofiles_replyinvite', $iFeedId,$aNonymousMessage['user_id'] );              
            }
            return $this->database()->update(Phpfox::getT('custom_profiles_schedule_feed'),array(
                'status' => -1,
                ),'feed_id='.(int)$iFeedId);
        }

        public function addNotification($sType, $iItemId, $iOwnerUserId, $iSenderUserId = null, $iTime = null)
        {
            $aInsert = array(
                'type_id' => $sType,
                'item_id' => $iItemId,
                'user_id' => $iOwnerUserId,    
                'owner_user_id' => ($iSenderUserId === null ? Phpfox::getUserId() : $iSenderUserId),
                'time_stamp' => ($iTime ? $iTime : PHPFOX_TIME )  
            );    
            return $this->database()->insert(Phpfox::getT('notification'), $aInsert);
        }

        public function removeInviteAnonymousMessage($iInvite)
        {
            return $this->database()->delete(Phpfox::getT('custom_profiles_invite_anonymous_message'),'invite_id='.(int)$iInvite);
        }

        // save feed in temp table
        public function saveFeed($aVals)
        {
            $aVals['sender_user_id'] = Phpfox::getUserId();
            $sVals = serialize($aVals);
            $aInsert = array(
                'sender_user_id' => Phpfox::getUserId(),
                'email' => $aVals['email'],
                'object' => $sVals,
                'time_stamp' => PHPFOX_TIME
            );
            return $this->database()->insert(Phpfox::getT('custom_profiles_guest_anonymous_feed'),$aInsert);
        }

        public function createFeedByInvite($iFeedId,$iUserId)
        {
            $aGuestFeed = $this->database()->select('*')
            ->from(Phpfox::getT('custom_profiles_guest_anonymous_feed'))
            ->where("feed_id='".(int)$iFeedId."'")
            ->execute('getRow');
            if(!isset($aGuestFeed['feed_id']))
            {
                return false;
            }
            $aVals = unserialize($aGuestFeed['object']);
            $aVals['friend_id'] = $iUserId;
            if(!isset($aVals['email']))
            {
                return false;
            }

            $bGift = false;
            // check if contatin gift
            if (Phpfox::isModule('egift') && isset($aVals['egift_id']) && !empty($aVals['egift_id']))
            {
                $aGift = Phpfox::getService('egift')->getEgift($aVals['egift_id']);
                if (!empty($aGift))
                {
                    $bIsFree = true;
                    foreach ($aGift['price'] as $sCurrency => $fVal)
                    {
                        if ($fVal > 0)
                        {
                            $bIsFree = false;
                        }
                    }    
                    $aVals['feed_type'] = 'feed_egift';
                    $aVals['parent_user_id'] = -1;
                    $bGift = true;
                    // Always make an invoice, so the feed can check on the state
                    if(!$aVals['is_not_friend'])
                    {
                        $aVals['parent_user_id'] = $aVals['friend_id'];
                    }
                    else
                    {
                        if($aReceive = Phpfox::getService('customprofiles')->checkEmail($aVals['email']))
                        {
                            $aVals['parent_user_id'] = $aReceive['user_id'];
                        }
                        else
                        {
                            return false;
                        }
                    }
                }
            }
            else
            {
                $aVals['feed_type'] = 'feed_comment';  
            }
            // Add data into table feed
            $iFeedComment = $this->database()->insert(Phpfox::getT('feed_comment'),array(
                'user_id' => $aGuestFeed['sender_user_id'],
                'parent_user_id' => $aVals['friend_id'],
                'content' => $aVals['message'],
                'time_stamp' => PHPFOX_TIME
            ));
            $iExpireTime = 0;
            if(isset($_SESSION['expire_time']) && $_SESSION['expire_time'])
            {
                $iExpireTime = PHPFOX_TIME + $_SESSION['expire_time'];
                unset($_SESSION['expire_time']);
            }
            $iFeedId = $this->database()->insert(Phpfox::getT('feed'),array(
                'type_id' => $aVals['feed_type'],
                'user_id' => $aGuestFeed['sender_user_id'],
                'parent_user_id' => $aVals['friend_id'],
                'item_id' => $iFeedComment,
                'privacy' => 1,
                'feed_reference' => 0,
                'time_stamp' => PHPFOX_TIME,
                'time_update' => PHPFOX_TIME,
                'expire_time' => $iExpireTime
            ));

            $this->database()->insert(Phpfox::getT('custom_profiles_anonymous_feed'),array(
                'feed_id' => $iFeedId,
                'user_id' => $aGuestFeed['sender_user_id'],
                'receive_user_id' => $aVals['friend_id']
            ));

            if (Phpfox::isModule('notification'))
            {
                $type_id = 'customprofiles_invitefirendsforfriend';
                $this->addNotification($type_id, $iFeedComment, $aVals['friend_id'],$aGuestFeed['sender_user_id']);          
            }

            if($bGift)
            {
                $iInvoice = $this->addGiftInvoice($iFeedId,$aGuestFeed['sender_user_id'],$aVals['friend_id'],$aGift);
            }
        }

        // update status invite anonymous message NOT FRIEN, IS USER
        public function updateStatus($iFeedId,$iStatus)
        {
            return $this->database()->update(Phpfox::getT('custom_profiles_invite_anonymous_message'),array('status' => $iStatus),'feed_id = '.(int)$iFeedId);
        }

        // Remove friend
        public function removeFriend($iNewUserId,$iInviteUserId)
        {
            $this->database()->delete(Phpfox::getT('friend'),'user_id='.(int)$iNewUserId);
            $this->database()->delete(Phpfox::getT('friend'),'friend_user_id='.(int)$iNewUserId);
            Phpfox::getService('friend.process')->updateFriendCount($iNewUserId, $iInviteUserId);
            Phpfox::getService('friend.process')->updateFriendCount($iInviteUserId, $iNewUserId);
        }

        // update feed and notification
        public function updateCustomProfiles()
        {
            $iLimitTime = Phpfox::getParam('customprofiles.limit_time_for_invite_user_by_anonymous_module')*24*60*60;
            $iTimeExpire = PHPFOX_TIME - $iLimitTime;
            $aExpireInvite = $this->database()->select('*')
            ->from(Phpfox::getT('custom_profiles_invite'))
            ->where('is_expire = 0 AND time_stamp < '.$iTimeExpire)
            ->execute('getRows');
            if(count($aExpireInvite) > 0)
            {
                if (Phpfox::isModule('notification'))
                {
                    foreach($aExpireInvite as $key => $aInvite)
                    {
                        $this->addNotification('customprofiles_expireinvite', $aInvite['invite_id'], $aInvite['user_id'],$aInvite['user_id'],$aInvite['time_stamp']);
                    }
                }
                $this->database()->update(Phpfox::getT('custom_profiles_invite'),array('is_expire' => 1),'time_stamp < '.$iTimeExpire);
            }
        }

        public function removeInvite($iInviteId)
        {
            return $this->database()->update(Phpfox::getT('custom_profiles_invite'),array('is_expire' => 1),'invite_id='.(int)$iInviteId);
        }

        public function addGiftInvoice($iRefId, $iUserFrom, $iUserTo, $aEgift)
        {
            /* Create an invoice*/
            $iInvoice = $this->database()->insert(Phpfox::getT('egift_invoice'),array(
                'user_from' => $iUserFrom,
                'user_to' => $iUserTo,
                'egift_id' => $aEgift['egift_id'],
                'birthday_id' => $iRefId,
                'currency_id' => Phpfox::getService('user')->getCurrency(),
                'price' => $aEgift['price'][Phpfox::getService('user')->getCurrency()],
                'time_stamp_created' => PHPFOX_TIME,
                'status' => 'pending'
            ));

            return $iInvoice;
        }

        public function saveScheduleFeed($aVals)
        {
            $aVals['sender_user_id'] = Phpfox::getUserId();
            $aVals['time_stamp'] = PHPFOX_TIME + $aVals['time_delay'];
            $sVals = serialize($aVals);

            $iStatus = 0;
            if(!$aVals['time_delay'])
            {
                $iStatus = 2;
            }

            $aInsert = array(
                'user_id' => Phpfox::getUserId(),
                'object' => $sVals,
                'status' => $iStatus,
                'receive_user_id' => $aVals['friend_id'],
                'time_stamp' => PHPFOX_TIME + $aVals['time_delay']
            );
            $iFeedId = $this->database()->insert(Phpfox::getT('custom_profiles_schedule_feed'),$aInsert);
            if($iStatus != 0)
            {
                if(!$aVals['is_friend'])
                {
                    try
                    {
                        $sLink = Phpfox::getLib('url')->makeUrl('user.login');
                        Phpfox::getLib('mail')->to($aVals['email'])                    
                        ->subject(Phpfox::getPhrase('customprofiles.you_received_an_anonymous_post_by_a_wayter'))
                        ->message(array('customprofiles.message_invite_join_anonymous_message', array('link' => $sLink)))
                        ->send();
                    }
                    catch(Exception $e)
                    {
                        echo 'Caught exception: ',  $e->getMessage(), "\n";
                    }
                }

                if (Phpfox::isModule('notification'))
                {
                    $type_id = 'customprofiles_anonymousconfirm';
                    Phpfox::getService('customprofiles.process')->addNotification($type_id, $iFeedId, $aVals['friend_id'],Phpfox::getUserId(),$aVals['time_stamp']);             
                }
            }
            return $iFeedId;
        }

        public function createScheduleFeed($iScheduleFeedId,$aVals)
        {
            if($aVals['is_friend'] || isset($aVals['force_create_feed']))
            {
                $bGift = false;
                // check if contatin gift
                if (Phpfox::isModule('egift') && isset($aVals['egift_id']) && !empty($aVals['egift_id']))
                {
                    $aGift = Phpfox::getService('egift')->getEgift($aVals['egift_id']);
                    if (!empty($aGift))
                    {
                        $bIsFree = true;
                        foreach ($aGift['price'] as $sCurrency => $fVal)
                        {
                            if ($fVal > 0)
                            {
                                $bIsFree = false;
                            }
                        }    
                        $aVals['feed_type'] = 'feed_egift';
                        $bGift = true;
                    }
                }
                else
                {
                    $aVals['feed_type'] = 'feed_comment';  
                }
                // Add data into table feed
                $iFeedComment = $this->database()->insert(Phpfox::getT('feed_comment'),array(
                    'user_id' => $aVals['sender_user_id'],
                    'parent_user_id' => $aVals['friend_id'],
                    'content' => $aVals['message'],
                    'time_stamp' => $aVals['time_stamp']
                ));
                $iExpireTime = 0;
                if(isset($_SESSION['expire_time']) && $_SESSION['expire_time'])
                {
                    $iExpireTime = $aVals['time_stamp'] + $_SESSION['expire_time'];
                    unset($_SESSION['expire_time']);
                }
                else if(isset($aVals['expire_time']))
                {
                    $iExpireTime = $aVals['time_stamp'] + $aVals['expire_time'];
                }
                $iFeedId = $this->database()->insert(Phpfox::getT('feed'),array(
                    'type_id' => $aVals['feed_type'],
                    'user_id' => $aVals['sender_user_id'],
                    'parent_user_id' => $aVals['friend_id'],
                    'item_id' => $iFeedComment,
                    'privacy' => 1,
                    'feed_reference' => 0,
                    'time_stamp' => $aVals['time_stamp'],
                    'time_update' => $aVals['time_stamp'],
                    'expire_time' => $iExpireTime
                ));

                $iAnonymousFeedId = $this->database()->insert(Phpfox::getT('custom_profiles_anonymous_feed'),array(
                    'feed_id' => $iFeedId,
                    'user_id' => $aVals['sender_user_id'],
                    'receive_user_id' => $aVals['friend_id'],
                    'message' => $aVals['message'],
                ));

                if (Phpfox::isModule('notification'))
                {
                    $type_id = 'customprofiles_invitefirendsforfriend';
                    $aNoti = $this->database()->select('*')
                    ->from(Phpfox::getT('notification'))
                    ->where("type_id = 'customprofiles_anonymousconfirm' AND item_id = ".$iScheduleFeedId." AND user_id = ".$aVals['friend_id'].' AND owner_user_id = '.$aVals['sender_user_id'])
                    ->execute('getRow');
                    
                    if(!isset($aNoti['notification_id']))
                    {
                        $this->addNotification($type_id, $iFeedComment, $aVals['friend_id'],$aVals['sender_user_id'],$aVals['time_stamp']); 
                    } 
                }

                if($bGift)
                {
                    $iInvoice = $this->addGiftInvoice($iFeedId,$aVals['sender_user_id'],$aVals['friend_id'],$aGift);
                }
                if($iFeedId)
                {
                    $this->database()->update(Phpfox::getT('custom_profiles_schedule_feed'),array('status' => 1, 'new_feed_id' => $iFeedComment),'feed_id='.(int)$iScheduleFeedId);
                }
                return $iFeedId;
            }
            else
            {
                try
                {
                    $sLink = Phpfox::getLib('url')->makeUrl('user.login');
                    Phpfox::getLib('mail')->to($aVals['email'])                      
                    ->subject(Phpfox::getPhrase('customprofiles.you_received_an_anonymous_post_by_a_wayter'))
                    ->message(array('customprofiles.message_invite_join_anonymous_message', array('link' => $sLink)))
                    ->send();
                }
                catch(Exception $e)
                {
                    echo 'Caught exception: ',  $e->getMessage(), "\n";
                }

                if (Phpfox::isModule('notification'))
                {
                    $type_id = 'customprofiles_anonymousconfirm';
                    Phpfox::getService('customprofiles.process')->addNotification($type_id, $iScheduleFeedId, $aVals['friend_id'],Phpfox::getUserId(),$aVals['time_stamp']);             
                }
                return true;
            }
        }

        public function updateScheduleFeed()
        {
            $aScheduleFeeds = $this->database()->select('*')
            ->from(Phpfox::getT('custom_profiles_schedule_feed'))
            ->where('time_stamp <= '.PHPFOX_TIME.' AND status = 0 AND receive_user_id='.Phpfox::getUserId())
            ->execute('getRows');

            if(count($aScheduleFeeds) == 0)
            {
                return false;
            }

            foreach($aScheduleFeeds as $key => $aFeed)
            {
                $aVals = unserialize($aFeed['object']);
                $aVals['is_friend'] = Phpfox::getService('friend')->isFriend($aVals['friend_id'], $aVals['sender_user_id']);
                $this->createScheduleFeed($aFeed['feed_id'],$aVals);
            }
        }

        public function getDislikes($sType, $iItemId, $bGetCount = false)
        {
            if ($bGetCount == true)
            {
                $this->database()
                ->select('COUNT(*)')
                ->order('u.full_name ASC');
                $sGetHow = 'getSlaveField';
            }
            else
            {
                $this->database()
                ->select(Phpfox::getUserField() )
                ->group('u.user_id');
                $sGetHow = 'getSlaveRows';
            }
            $aDislikes = $this->database()
            ->from(Phpfox::getT('action'), 'a')
            ->join(Phpfox::getT('user'), 'u', 'u.user_id = a.user_id')            
            ->where('a.item_type_id = "' . $this->database()->escape($sType) . '" AND a.item_id = ' . (int)$iItemId)            
            ->execute($sGetHow);
            return $aDislikes;
        }

        // Confirm show anonymous feed for friend can see
        public function showAnonymousFeedToFriend($iAnonymousId)
        {
            return $this->database()->update(Phpfox::getT('custom_profiles_anonymous_feed'), array('privacy' => 1), 'anonymous_id = '.(int)$iAnonymousId);
        }

        // Confirm show anonymous feed for friend can see
        public function hideAnonymousFeedToFriend($iAnonymousId)
        {
            return $this->database()->update(Phpfox::getT('custom_profiles_anonymous_feed'), array('privacy' => 2), 'anonymous_id = '.(int)$iAnonymousId);
        }

        public function blockUser($iUserId , $iReportId = 0)
        {
            $this->database()->update(Phpfox::getT('custom_profiles_anonymous_feed'),array('is_block' => 1), 'user_id='.(int)$iUserId.' AND receive_user_id = '.Phpfox::getUserId());

            $iBlockId = $this->database()->insert(Phpfox::getT('custom_profiles_block'),
                array(
                    'user_id' => Phpfox::getUserId(), 
                    'block_user_id' => $iUserId , 
                    'time_stamp' => PHPFOX_TIME,
                    'report_id' => $iReportId
            ));
            if($iBlockId)
            {
                // Send a notifycation to sender, you block him
                if (Phpfox::isModule('notification'))
                {
                    Phpfox::getService('notification.process')->add('customprofiles_blockUser', $iBlockId, $iUserId);              
                }
            }
            return $iBlockId;
        }

        public function removeBlockUser($iUserId, $iBlockUserId)
        {
            Phpfox::isAdmin(true);
            $this->database()->update(Phpfox::getT('custom_profiles_anonymous_feed'),array('is_block' => 0), 'user_id='.(int)$iBlockUserId.' AND receive_user_id = '.$iUserId);
            return $this->database()->delete(Phpfox::getT('custom_profiles_block'),'user_id='.$iUserId.' AND block_user_id='.(int)$iBlockUserId);
        }
    }
?>
