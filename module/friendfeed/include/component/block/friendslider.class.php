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

            $iFriendNumber = count($aFriends);
            for($i = 0; $i< $iFriendNumber;$i++){
                $sName = $aFriends[$i]['full_name'];
                $iPos = 0;
                $iPos = strpos($sName, ' ');
                $aFriends[$i]['first_name']=substr($sName,0,$iPos);
                $aFriends[$i]['last_name'] =substr($sName,$iPos,strlen($sName)-$iPos); 

            }
            if( $iFriendNumber< 10){
                for($i = 0; $i < 10 - $iFriendNumber; $i++){
                    $array1 = array($iFriendNumber + $i => array(
                        'full_name'=> 'you next friend',
                        'user_profile'=> '',
                        'user_image'=> Phpfox::getParam('core.path').'module/friendfeed/static/image/noavatar.jpg'
                    ));
                    $aFriends = array_merge($aFriends,$array1);
                }
            }
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
                if($aSections[$aAlphabets[$i]]== -1 && $i!= 0){
                    $aSections[$aAlphabets[$i]] = $aSections[$aAlphabets[$i -1]];   
                }
            }   
            $this->template()->assign(array(
                'aFriends'=> $aFriends,
                'aSections'=>$aSections
            ));       
        }
    }
?>

