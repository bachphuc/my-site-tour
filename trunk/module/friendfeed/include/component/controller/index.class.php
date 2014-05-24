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
    * @package          Module_friendfeed
    * @version         $Id: index.class.php 3441 2011-11-02 15:53:59Z Miguel_Espinoza $
    */
    class FriendFeed_Component_Controller_Index extends Phpfox_Component
    {
        /**
        * Class process method wnich is used to execute this component.
        */
        public function process()
        {  
            $this->template()->setHeader(array(
                'jquery.min.js'=> 'module_friendfeed',
                'modernizr.custom.17475.js'=>'module_friendfeed',               
                'jquerypp.custom.js'=> 'module_friendfeed',
                'jquery.elastislide.js'=> 'module_friendfeed',
                'jslider.js'=>'module_friendfeed',
                'elastislide.css' =>'module_friendfeed',
                'toggle.css' =>'module_friendfeed'
            ));
        }
    }
?>
