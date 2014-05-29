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
                //Subname
                $sName = $aFriends[$i]['full_name'];
                $iPos = strpos($sName, ' ');
                $sFirstName = substr($sName,0,$iPos);
                $sLastName  = substr($sName,$iPos,strlen($sName)-$iPos); 
                //Shorten
                if(strlen($sFirstName) > 8)
                    $sFirstName = substr($sFirstName,0,8).'...';
                if(strlen($sLastName)> 8 )
                    $sLastName = substr($sLastName,0, 8).'...';
                //Breakline
                if(empty($iPos)){
                    $aFriends[$i]['shorten_name']=$sLastName;
                }else  {
                    $aFriends[$i]['shorten_name']=$sFirstName.'<br>'.$sLastName;
                }
            }
            if( $iFriendNumber< 5){
                for($i = 0; $i < 5 - $iFriendNumber; $i++){
                    $array1 = array($iFriendNumber + $i => array(
                        'full_name'=> Phpfox::getPhrase('friendfeed.you_next_friend'),
                        'user_profile'=> '',
                        'user_image'=> Phpfox::getParam('core.path').'module/friendfeed/static/image/noavatar.jpg'
                    ));
                    $aFriends = array_merge($aFriends,$array1);
                }
            }
            $aSections =  array();
            for($i = 0; $i<count($aAlphabets);$i++){
                $aSections[$aAlphabets[$i]]= 0;
                for($j= 0; $j<count($aFriends);$j++){
                    $sName= strtoupper($aFriends[$j]['full_name'][0]);
                    if($aAlphabets[$i]== $sName) {
                        $aSections[$sName]= $j;
                        break;
                    }
                }

            } 
            for($i = 1; $i<26; $i++){ 
                if($aSections[$aAlphabets[$i]]== 0){
                    for($j = $i+1;$j <26; $j++){
                        if($aSections[$aAlphabets[$j]]!= 0){
                            $aSections[$aAlphabets[$i]] = (int)$aSections[$aAlphabets[$j]] -1; 
                            break;
                        }else  $aSections[$aAlphabets[$i]] = (int)$aSections[$aAlphabets[$i-1]]; 
                    }
                }
                if($i== 25){
                    $aSections[$aAlphabets[$i]] = (int)$aSections[$aAlphabets[$i-1]]; 
                }
            }
            $this->template()->assign(array(
                'aFriends'=> $aFriends,
                'aSections'=>$aSections
            ));       
        }
    }
?>

