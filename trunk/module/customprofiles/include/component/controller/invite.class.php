<?php

    /**
    * [PHPFOX_HEADER]
    */
    defined('PHPFOX') or exit('NO DICE!');

    /**
    *
    *
    */
    class CustomProfiles_Component_Controller_Invite extends Phpfox_Component {

        /**
        * Class process method wnich is used to execute this component.
        */
        public function process()
        {
            Phpfox::isUser(true);
            /**
            *   LOCK this feature
            *
            if($iId = $this->request()->get('id'))
            {
                $aInvite = Phpfox::getService('customprofiles')->getInviteAnonymousMessage($iId);
                if(isset($aInvite['invite_id']) && Phpfox::getUserId() == $aInvite['invite_user_id'])
                {
                    if (Phpfox::isModule('notification'))
                    {
                        $type_id = 'customprofiles_anonymousconfirm';
                        Phpfox::getService('customprofiles.process')->addNotification($type_id, $aInvite['feed_id'], $aInvite['invite_user_id'],$aInvite['user_id']);    
                        Phpfox::getService('customprofiles.process')->removeInviteAnonymousMessage($iId);      
                    }
                }
            }   
            **/
            
            $this->url()->send('');
        }
    }
?>
