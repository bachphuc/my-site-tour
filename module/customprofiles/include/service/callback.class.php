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
    class CustomProfiles_Service_CallBack extends Phpfox_Service 
    {
        public function getNotificationAnonymousConfirm($aNotification)
        {
            // $aNonymousFeed = Phpfox::getService('customprofiles')->getAnonymousFeed($aNotification['item_id']);
            $aNonymousFeed = Phpfox::getService('customprofiles')->getScheduleFeed($aNotification['item_id']);
            if(!isset($aNonymousFeed['feed_id']))
            {
                return false;
            }

            $sConfirm = '';
            if((int)$aNonymousFeed['status'] == 0 || (int)$aNonymousFeed['status'] == 2)
            {
                $sConfirm = '<span id="confirm_notification_'.$aNotification['notification_id'].'">. Click to accept or refuse this post &nbsp; <input type="button" value="Accept" class="button" onclick="$(this).attr(\'disabled\',\'disabled\');$.ajaxCall(\'customprofiles.acceptAnonymousPost\',\'notify_id='.$aNotification['notification_id'].'&feed_id='.$aNotification['item_id'].'\');return false;" style="margin-top:5px"> <input type="button" value="Refuse" class="button" onclick="$.ajaxCall(\'customprofiles.refuseAnonymousPost\',\'notify_id='.$aNotification['notification_id'].'&feed_id='.$aNotification['item_id'].'\');return false;" style="margin-top:5px"></span>';
            }

            $sHtml = 'A wayter posted about you'.$sConfirm.'</br> ';
            return array(
                'link' => '',
                'message' => $sHtml,
                'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog'),
                'no_profile_image' => true
            );    
        }

        public function getNotificationReplyInviteAnonymous($aNotification)
        {
            $aInvite = $this->database()->select('*')
            ->from(Phpfox::getT('custom_profiles_invite'))
            ->where("invite_id=".(int)$aNotification['item_id'])
            ->execute('getRow');
            if(!isset($aInvite['invite_id']))
            {
                return false;
            }

            $aUser = Phpfox::getService('customprofiles')->checkEmail($aInvite['email']);
            if(!isset($aUser['user_id']))
            {
                return false;
            }

            return array(
                'link' => Phpfox::getLib('url')->makeUrl($aUser['user_name']),  
                'message' => Phpfox::getPhrase('customprofiles.replay_accept_post',array('full_name' => $aInvite['full_name'],'message' => $aInvite['message'])),
                'no_profile_image' => true
            );  
        }

        public function getNotificationComment($aNotification)
        {
            $sMessage = Phpfox::getPhrase('customprofiles.a_wayter_commented_on_your_status_update');

            return array(
                'link' => Phpfox::getLib('url')->makeUrl(Phpfox::getUserBy('user_name').'/comment-id_'.$aNotification['item_id']), 
                'message' => $sMessage,
                'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog'),
                'no_profile_image' => true
            ); 
        }

        public function getNotificationCommentShowName($aNotification)
        {
            $aVals['type_id'] = 'feed_comment';
            $aVals['item_id'] = $aNotification['item_id'];
            $iActualFeedId = Phpfox::getService('customprofiles')->getActualFeedId($aVals);
            $aAnonymousFeed = Phpfox::getService('customprofiles')->getAnonymousFeed($iActualFeedId);
            
            if(!isset($aAnonymousFeed['feed_id']))
            {
                $aVals['type_id'] = 'feed_egift';
                $iActualFeedId = Phpfox::getService('customprofiles')->getActualFeedId($aVals);
                $aAnonymousFeed = Phpfox::getService('customprofiles')->getAnonymousFeed($iActualFeedId);
                if(!isset($aAnonymousFeed['feed_id']))
                {
                    return false;
                }
            }

            $aNotificationName = $this->database()->select('*')
            ->from(Phpfox::getT('custom_profiles_show_name'))
            ->where('notification_id='.$aNotification['notification_id'])
            ->execute('getRow');
            if(!isset($aNotificationName['notification_id']))
            {
                return false;
            }
            if(strlen($aAnonymousFeed['message']) > 60)
            {
                $aAnonymousFeed['message'] = substr($aAnonymousFeed['message'],0,60)."...";
            }
            if($aNotificationName['is_show'])
            {
                $sMessage = Phpfox::getPhrase('customprofiles.a_user_comment_on_your_status',array('full_name' => $aNotificationName['full_name'])).' "'.$aAnonymousFeed['message'].'"';
            }
            else
            {
                $sMessage = Phpfox::getPhrase('customprofiles.a_wayter_commented_on_your_status_update').' "'.$aAnonymousFeed['message'].'"';
            }

            return array(
                'link' => Phpfox::getLib('url')->makeUrl(Phpfox::getUserBy('user_name').'/comment-id_'.$aNotification['item_id']), 
                'message' => $sMessage,
                'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog'),
                'no_profile_image' => true
            ); 
        }

        // hau@gmail.com
        public function getNotificationInvitefirendsforfriend($aNotification)
        {
            if($aNotification["time_stamp"] > PHPFOX_TIME )
            {
                //HAU EDIT CODE
                $iFeedComment = $aNotification['item_id'];
                $aInsert = array(
                    'type_id' => 'customprofiles_invitefirendsforfriend',
                    'item_id' => $iFeedComment,
                    'user_id' => Phpfox::getUserId(),    
                    'owner_user_id' => $aNotification['user_id'],
                    'time_stamp' => $aNotification['time_stamp'],        
                );    

                $this->database()->insert(Phpfox::getT('notification'), $aInsert);
                // FINISH EDIT CODE
                return false ;
            }
            $receiver_id = $aNotification['user_id'];
            $receiver = Phpfox::getService('user')->getUser($receiver_id);

            return array(
                'no_profile_image' => true,
                'link' => Phpfox::getLib('url')->makeUrl(Phpfox::getUserBy('user_name').'/comment-id_'.$aNotification['item_id']),  
                'message' => Phpfox::getPhrase('customprofiles.a_site_member_has_posted_a_comment_about_you', array('sender'=>$receiver['full_name'], 'receiver'=>'you'))
            );        
        }
        // end hau@gmail.com
        public function getNotificationScheduleComplete($aNotification)
        {
            return array(
                'link' => '',
                'message' => Phpfox::getPhrase('customprofiles.your_post_has_been_correctly_scheduled_in_the_future')
            );
        }

        // callback get Activity anonymous feed
        public function getActivityFeed($aItem, $aCallback = null, $bIsChildItem = false)
        {
            if($aItem['user_id'] == Phpfox::getService('profile')->getProfileUserId())
            {
                return false;
            }

            if(Phpfox::isModule('like'))
            {
                $this->database()->select('l.like_id AS is_liked, ')
                ->leftJoin(Phpfox::getT('like'), 'l', 'l.type_id = \'feed_comment\' AND l.item_id = fc.feed_comment_id AND l.user_id = ' . Phpfox::getUserId());
            }

            $aRow = $this->database()->select('fc.*, ' . Phpfox::getUserField('u', 'parent_'))
            ->from(Phpfox::getT('feed_comment'), 'fc')            
            ->leftJoin(Phpfox::getT('user'), 'u', 'u.user_id = fc.parent_user_id')
            ->where('fc.feed_comment_id = ' . (int) $aItem['item_id'])
            ->execute('getSlaveRow');

            $sLink = Phpfox::getLib('url')->makeUrl($aRow['parent_user_name'], array('comment-id' => $aRow['feed_comment_id']));

            $aReturn = array(
                'no_share' => true,
                'feed_status' => $aRow['content'],
                'feed_link' => $sLink,
                'total_comment' => $aRow['total_comment'],
                'feed_total_like' => $aRow['total_like'],
                'feed_is_liked' => (isset($aRow['is_liked']) ? $aRow['is_liked'] : false),
                'feed_icon' => Phpfox::getLib('image.helper')->display(array('theme' => 'misc/comment.png', 'return_url' => true)),
                'time_stamp' => $aRow['time_stamp'],            
                'enable_like' => true,            
                'comment_type_id' => 'feed',
                'like_type_id' => 'feed_comment'            
            );

            if (!empty($aRow['parent_user_name']) && !defined('PHPFOX_IS_USER_PROFILE') && empty($_POST))
            {
                $aReturn['parent_user'] = Phpfox::getService('user')->getUserFields(true, $aRow, 'parent_');
            }        

            if (!PHPFOX_IS_AJAX && defined('PHPFOX_IS_USER_PROFILE') && !empty($aRow['parent_user_name']) && $aRow['parent_user_id'] != Phpfox::getService('profile')->getProfileUserId())
            {            
                $aReturn['feed_info'] = Phpfox::getPhrase('feed.posted_on_parent_full_names_wall', array('parent_user_name' => Phpfox::getLib('url')->makeUrl($aRow['parent_user_name']), 'parent_full_name' => $aRow['parent_full_name']));
                $aReturn['feed_status'] = $aRow['content'];
                // http://www.phpfox.com/tracker/view/15025/
                $aReturn['parent_user_id'] = $aRow['user_id'];
            }
            $aItem['full_name'] = 'anh rat la met moi roi day nhe';
            return $aReturn;
        }

        // notification when user confirm invite anonymous message NOT FRIEND, IS USER
        public function getNotificationReplyInvite($aNotification)
        {
            // $aNonymousMessage = Phpfox::getService('customprofiles')->getAnonymousFeed($aNotification['item_id']);
            $aNonymousMessage = Phpfox::getService('customprofiles')->getScheduleFeed($aNotification['item_id']);
            if(!isset($aNonymousMessage['object']))
            {
                return false;
            }
            $aVals = unserialize($aNonymousMessage['object']);
            $aUser = Phpfox::getService('user')->getUser($aNonymousMessage['receive_user_id']);
            if((int)$aNonymousMessage['status'] == 1)
            {
                $sMessage = Phpfox::getPhrase('customprofiles.replay_accept_post',array('full_name' => $aVals['full_name'],'message' => $aVals['message']));
            }
            else
            {
                $sMessage = Phpfox::getPhrase('customprofiles.reply_refuse_post',array('full_name' => $aVals['full_name'],'message' => $aVals['message']));
            }
            return array(
                'link' => Phpfox::getLib('url')->makeUrl($aUser['user_name']),  
                'message' => $sMessage,
                'no_profile_image' => true
            ); 
        }

        // notification when invite expire
        public function getNotificationExpireInvite($aNotification)
        {
            $aInvite = $this->database()->select('*')
            ->from(Phpfox::getT('custom_profiles_invite'))
            ->where('invite_id='.$aNotification['item_id'])
            ->execute('getRow');
            if(!isset($aInvite['invite_id']))
            {
                return false;
            }
            return array(
                'link' => '',  
                'message' => Phpfox::getPhrase('customprofiles.expire_invite_anonymous_message',array('full_name' => $aInvite['full_name'])),
                'no_profile_image' => true
            );
        }
    }
?>
