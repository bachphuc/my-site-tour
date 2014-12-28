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
    }
?>
