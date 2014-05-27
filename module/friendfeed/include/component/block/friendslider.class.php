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
            $aFriends  =  Phpfox::getService('friendfeed')->getFriend();
            $aAlphabets = array('A', 'B', 'C','D','E','F','G','H','I','J','K',
                'L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');

            $aSections =  array();
            for($i = 0; $i<count($aAlphabets);$i++){
                $aSections[$aAlphabets[$i]]= -1;
                for($j= 0; $j<count($aFriends);$j++){
                    $sName= strtoupper($aFriends[$j]['full_name'][0]);
                    if($aAlphabets[$i]== $sName) {
                        $aSections[$sName]= $j;
                        break;
                    }
                }
            }
            $this->template()->assign(array(
                'aFriends'=> $aFriends,
                'aSections'=>$aSections
            ));       
        }
    }
?>

