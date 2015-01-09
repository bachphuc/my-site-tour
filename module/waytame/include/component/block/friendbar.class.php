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
    class Waytame_Component_Block_FriendBar extends Phpfox_Component
    {
        public function process()
        {
            $aFriends = Phpfox::getService("waytame")->getFriend();
            $iTotalFriends = count($aFriends);
            $aSixRandFriends = array();

            if($iTotalFriends <= 6)
            {
                for($i =0; $i < 6 - $iTotalFriends; $i++ )
                {
                    $aTemp = array(
                        'user_image'=> Phpfox::getParam('core.path').'module/waytame/static/image/no_image.png'
                    );
                    $aFriends[] = $aTemp;
                }
                $aSixRandFriends = $aFriends;
            }
            else
            {
                $aSequence = range(0,$iTotalFriends -1); 
                shuffle($aSequence);
                for($i = 0; $i<6;$i++)
                {
                    $aSixRandFriends[$i] = $aFriends[$aSequence[$i]];
                }
            }
            
            $this->template()->assign(array(
                'aFriends'=>$aSixRandFriends
                )
            );
        }
    }
?>

