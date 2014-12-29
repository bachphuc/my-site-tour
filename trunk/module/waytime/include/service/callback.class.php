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
    * @package          Module_User
    * @version         $Id: process.class.php 7081 2014-01-29 18:36:08Z Fern $
    */
    class Waytime_Service_Callback extends Phpfox_Service 
    {   
        public function getProfileLink()
        {
            return 'profile.waytime';
        }

        public function getProfileMenu($aUser)
        {
            $aProfile = Phpfox::getService('waytime')->getProfile();
            if(!$aProfile['is_unlock'])
            {
                return false;
            }
            if($aUser['user_id'] != Phpfox::getUserId())
            {
                return false;
            }
            $aMenus[] = array(
                'phrase' => Phpfox::getPhrase('waytime.w_time'),
                'url' => 'profile.waytime',
                'icon' => 'feed/waytime.png'
            );    

            return $aMenus;
        }

        public function getNotificationUnlockWaytime($iTem)
        {
            $aProfile = Phpfox::getService('waytime')->getProfile();
            $sMessage = ($aProfile['is_unlock'] ? Phpfox::getPhrase('waytime.would_you_like_to_complete_your_unlocked_w_time_capsule') : Phpfox::getPhrase('waytime.notificaion_would_you_like_to_complete_your_unlocked_w_time_capsule'));
            return array(
                'link' => '',
                'message' => $sMessage,
                'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
            );    
        }

        public function getNotificationCompleteWaytime($iTem)
        {
            $aProfile = Phpfox::getService('waytime')->getProfile();
            $sMessage = ($aProfile['is_unlock'] ? Phpfox::getPhrase('waytime.would_you_like_to_complete_the_time_capsule_now') : Phpfox::getPhrase('waytime.notificaion_would_you_like_to_complete_the_time_capsule_now'));
            return array(
                'link' => '',
                'message' => $sMessage,
                'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
            );    
        }
    }
?>
