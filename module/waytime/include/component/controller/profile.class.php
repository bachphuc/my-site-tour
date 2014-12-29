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
    * @package         Phpfox_Component
    * @version         $Id: index.class.php 5850 2013-05-10 06:07:19Z Miguel_Espinoza $
    */
    class Waytime_Component_Controller_Profile extends Phpfox_Component
    {
        /**
        * Class process method wnich is used to execute this component.
        */
        public function process()
        {
            $aProfile = Phpfox::getService('waytime')->getProfile();
            if(!$aProfile['is_unlock'])
            {
                return $this->url()->send('',null, Phpfox::getPhrase('waytime.you_have_not_completed_w_time_capsule_yet'));
            }
            $aSummarys = Phpfox::getService('waytime')->getSummarys();
            $this->template()->assign(array(
                'aSummarys' => $aSummarys,
            ));
        }
    }
?>
