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
    * @package          Module_Report
    * @version         $Id: add.class.php 1179 2009-10-12 13:56:40Z Raymond_Benc $
    */
    class Customprofiles_Component_Block_Menu extends Phpfox_Component 
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
                'iUserId' =>Phpfox::getUserId(),
                'sController' => Phpfox::getLib('module')->getFullControllerName(),
            ));
        }
    }
?>
