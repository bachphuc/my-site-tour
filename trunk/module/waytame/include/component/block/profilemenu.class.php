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
    class Waytame_Component_Block_ProfileMenu extends Phpfox_Component
    {
        public function process()
        {
            if(Phpfox::isModule('customprofiles'))
            {
                return false;
            }
            $aUser = $this->getParam('aUser',null);
            if(!$aUser)
            {
                return false;
            }
            if($aUser['user_id'] != Phpfox::getUserId())
            {
                return false;
            }
            $this->template()->assign(array(
                'sLink' => Phpfox::getLib('url')->makeUrl(Phpfox::getUserBy('user_name').'.waytame'),
                'sModule' => $this->request()->get('req2')
            ));
        }
    }
?>
