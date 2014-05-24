<?php
    /**
    * [PHPFOX_HEADER]
    */

    defined('PHPFOX') or exit('NO DICE!');

    /**
    * Shows the congratulate ajax box
    *
    * @copyright        [PHPFOX_COPYRIGHT]
    * @author          Miguel Espinoza
    * @package          Module_Friend
    * @version         $Id: detail.class.php 254 2009-02-23 12:36:20Z Miguel_Espinoza $
    */
    class FriendFeed_Component_Block_FriendSlider extends Phpfox_Component
    {
        public function process()
        {
            $aFriends  =  Phpfox::getService('friend')->getFromCache();
            $aAlphabets = array('A', 'B', 'C','D','E','F','G','H','I','J','K',
            'L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
            $this->template()->assign(array(
                'aFriends'=> $aFriends,
                'aAlphabets'=>$aAlphabets
            ));       
        }
    }
?>

