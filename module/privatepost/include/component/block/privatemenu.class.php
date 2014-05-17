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
    class PrivatePost_Component_Block_PrivateMenu extends Phpfox_Component
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
        }
    }
?>
