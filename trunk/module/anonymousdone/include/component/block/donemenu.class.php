<?php
    /**
    * [PHPFOX_HEADER]
    */

    defined('PHPFOX') or exit('NO DICE!');

    /**
    * 
    * 
    * @copyright        [PHPFOX_COPYRIGHT]
    * @author          phuclb@npfox.com
    * @package          Module_Request
    */
    class AnonymousDone_Component_Block_DoneMenu extends Phpfox_Component
    {
        /**
        * Class process method wnich is used to execute this component.
        */
        public function process()
        { 
            
            $aUser = $this->getParam('aUser');
            if(Phpfox::getUserId() != $aUser['user_id'])
            {
                return false;
            }
            $sView = $this->request()->get('view');
            
            $this->template()->assign(array(
                'aUser' => $aUser,
                'sView' => $sView,
                'iUserId' =>Phpfox::getUserId()
            ));
        }
    }
?>
