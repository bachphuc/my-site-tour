<?php
    /**
    * [PHPFOX_HEADER]
    */

    defined('PHPFOX') or exit('NO DICE!');

    /**
    * 
    * 
    * @copyright        [PHPFOX_COPYRIGHT]
    * @author          Raymond_Benc
    * @package         Phpfox_Component
    * @version         $Id: index.class.php 6113 2013-06-21 13:58:40Z Raymond_Benc $
    */
    class Sitetour_Component_Controller_Admincp_Setting extends Phpfox_Component
    {
        /**
        * Class process method wnich is used to execute this component.
        */
        public function process()
        {   
            $this->template()->assign(array(
                'aGroups' => Phpfox::getService('user.group')->getForEdit()
            ));
        }
    }
?>
