<?php

    /**
    * [PHPFOX_HEADER]
    */
    defined('PHPFOX') or exit('NO DICE!');

    /**
    *
    *
    */
    class CustomProfiles_Component_Block_UserData extends Phpfox_Component {

        /**
        * Class process method wnich is used to execute this component.
        */
        public function process()
        {
            Phpfox::isAdmin(true);
            $iUserId = $this->request()->get('user_id');
            if(!$iUserId)
            {
                return false;
            }
            $aUser = Phpfox::getService('user')->get($iUserId);
            if(!isset($aUser['user_id']))
            {
                return false;
            }
            if (!empty($aUser['birthday']))
            {
                $aUser = array_merge($aUser, Phpfox::getService('user')->getAgeArray($aUser['birthday']));
            }
            $this->template()->assign(array(
                'aUser' => $aUser,
                'aEmails' => Phpfox::getService('report.email')->getEmailsTemplate()
            ));
        }
    }
?>
