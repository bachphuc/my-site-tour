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
    class Waytame_Component_Controller_Profile extends Phpfox_Component
    {
        /**
        * Class process method wnich is used to execute this component.
        */
        public function process()
        {  
            if($iId = $this->request()->getInt('req2'))
            {
                $aQuestion = Phpfox::getService('waytame')->getQuestion($iId);
                if(isset($aQuestion['question_id']))
                {
                    return Phpfox::getLib('module')->getComponent('waytame.view',array('aQuestion' => $aQuestion, 'bNoTemplate' => true),'controller',false);
                }
            }
            $aQuestions = Phpfox::getService('waytame')->getAll();
            $this->template()->assign(array(
                'aQuestions' => $aQuestions
            ));
        }
    }
?>
