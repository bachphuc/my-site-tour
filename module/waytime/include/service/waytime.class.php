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
    * @package          Module_User
    * @version         $Id: process.class.php 7081 2014-01-29 18:36:08Z Fern $
    */
    class Waytime_Service_Waytime extends Phpfox_Service 
    {   
         public function getProfile($iUserId = null)
         {
             if(!$iUserId)
             {
                 $iUserId = Phpfox::getUserId();
             }
             $aRow = $this->database()->select('*')
             ->from(Phpfox::getT('waytime_profile'))
             ->where('user_id = '.(int)$iUserId)
             ->execute('getRow');
             if(!isset($aRow['profile_id']))
             {
                 $iProfileId = Phpfox::getService('waytime.process')->addProfile();
                 return array(
                    'profile_id' => $iProfileId,
                    'user_id' => Phpfox::getUserId(),
                    'current' => 0,
                    'is_complete' => 0,
                    'is_unlock' => 0,
                    'remind_time' => PHPFOX_TIME
                 );
             }
             return $aRow;
         }
         
         public function getQuestions()
         {
             $aQuestions = $this->database()->select('wq.*, count(wa.question_id) AS number_answer')
             ->from(Phpfox::getT('waytime_question'), 'wq')
             ->leftJoin(Phpfox::getT('waytime_answer'),'wa', 'wq.question_id = wa.question_id')
             ->group('wq.question_id')
             ->order('ordering ASC')
             ->execute('getRows');
             return $aQuestions;
         }
         
         public function getTotalQuestion()
         {
             return (int) $this->database()->select('count(*)')
             ->from(Phpfox::getT('waytime_question'), 'wq')
             ->execute('getSlaveField');
         }
         
         public function getQuestion($iQuestionId)
         {
             $aQuestion = $this->database()->select('*')
             ->from(Phpfox::getT('waytime_question'))
             ->where('question_id ='.(int)$iQuestionId)
             ->execute('getRow');
             if(!isset($aQuestion['question_id']))
             {
                 return false;
             }
             $aQuestion['answers'] = $this->getAnswers($iQuestionId);
             return $aQuestion;
         }
         
         public function getAnswers($iQuestionId)
         {
             $aRows = $this->database()->select('*')
             ->from(Phpfox::getT('waytime_answer'))
             ->where('question_id = '.(int)$iQuestionId)
             ->execute('getRows');
             return $aRows;
         }
         
         public function getAnswer($iId)
         {
             return $this->database()->select('*')
             ->from(Phpfox::getT('waytime_answer'))
             ->where('answer_id = '.(int)$iId)
             ->execute('getRow');
         }
         
         public function getTotalAnswer($iProfileId )
         {
             return (int)$this->database()->select('count(*)')
            ->from(Phpfox::getT('waytime_profile_question'))
            ->where('profile_id = '.(int)$iProfileId)
            ->execute('getSlaveField');
         }
         
         public function getAnswerQuestion($iProfileId, $iQuestionId)
         {
             $aRow = $this->database()->select('*')
            ->from(Phpfox::getT('waytime_profile_question'))
            ->where('profile_id = '.(int)$iProfileId . ' AND question_id = '.(int)$iQuestionId)
            ->execute('getRow'); 
            if(!isset($aRow['profile_id']))
            {
                return false;
            }
            return $aRow;
         }
         
    }
?>
