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
    * @package          Module_Feed
    * @version         $Id: birth.class.php 5973 2013-05-28 11:04:04Z Raymond_Benc $
    */
    class Waytime_Component_Block_Next extends Phpfox_Component 
    {
        /**
        * Class process method wnich is used to execute this component.
        */
        public function process()
        {
            $index = $this->request()->get('index');
            $aQuestions = Phpfox::getService('waytime')->getQuestions();
            $aProfile = Phpfox::getService('waytime')->getProfile();
            if(isset($aQuestions[$index - 1]))
            {
                $aQuestion = Phpfox::getService('waytime')->getQuestion($aQuestions[$index - 1]['question_id']);
                $aAnswer = Phpfox::getService('waytime')->getAnswerQuestion($aProfile['profile_id'], $aQuestion['question_id']);
                $this->template()->assign(array(
                    'aQuestion' => $aQuestion,
                    'aAns' => $aAnswer
                ));
            }
            $this->template()->assign(array(
                'iIndex' => $index,
                'iNext' => (isset($aQuestions[$index]) ? $index + 1 : false),
                'iPre' => $index - 1,
                'iTotal' => count($aQuestions)
            ));
        }
    }
?>
