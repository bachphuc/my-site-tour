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
    class Waytime_Service_Process extends Phpfox_Service 
    {   
        public function addQuestion($aVals)
        {
            if(empty($aVals['title']))
            {
                Phpfox_Error::set('Question can not be null.');
                return false;
            }
            return $this->database()->insert(Phpfox::getT('waytime_question'), $aVals);
        }

        public function updateQuestion($aVals, $iId)
        {
            return $this->database()->update(Phpfox::getT('waytime_question'), $aVals , 'question_id = '.(int)$iId);
        }

        public function deleteQuestion($iId)
        {
            $this->database()->delete(Phpfox::getT('waytime_question'), 'question_id = '.(int)$iId);
            $this->database()->delete(Phpfox::getT('waytime_answer'), 'question_id = '.(int)$iId);
            return true;
        }

        public function addAnswer($aVals)
        {
            if(empty($aVals['answer']))
            {
                Phpfox_Error::set('Answer can not be null.');
                return false;
            }
            return $this->database()->insert(Phpfox::getT('waytime_answer'), $aVals);
        }

        public function updateAnswer($aVals, $iId)
        {
            return $this->database()->update(Phpfox::getT('waytime_answer'), $aVals , 'answer_id = '.(int)$iId);
        }

        public function deleteAnswer($iId)
        {
            return $this->database()->delete(Phpfox::getT('waytime_answer'), 'answer_id = '.(int)$iId);
        }

        public function addProfile($iUserId = null)
        {
            if(!$iUserId)
            {
                $iUserId = Phpfox::getUserId();
            }
            return $this->database()->insert(Phpfox::getT('waytime_profile'), array('user_id' => $iUserId, 'remind_time' => PHPFOX_TIME));
        }

        public function getAnswerQuestion($iProfileId, $iQuestionId)
        {
            return $this->database()->select('*')
            ->from(Phpfox::getT('waytime_profile_question'))
            ->where('profile_id = '.(int)$iProfileId. ' AND question_id = '.(int)$iQuestionId)
            ->execute('getRow');
        }

        public function answerQuestion($iProfileId, $iQuestionId, $iAnswerId, $sNote)
        {
            $aRow = $this->getAnswerQuestion($iProfileId, $iQuestionId);
            if(isset($aRow['profile_id']))
            {
                return $this->database()->update(Phpfox::getT('waytime_profile_question'), array('answer_id' => $iAnswerId), 'profile_id = '.(int)$iProfileId. ' AND question_id = '.(int)$iQuestionId);
            }
            else
            {
                $this->database()->insert(Phpfox::getT('waytime_profile_question'),array(
                    'profile_id' => $iProfileId,
                    'question_id' => $iQuestionId, 
                    'answer_id' => $iAnswerId,
                    'note' => $sNote,
                    'time_stamp' => PHPFOX_TIME
                ));
                return true;
            }
        }

        public function updateProfile($aVals, $iId)
        {
            return $this->database()->update(Phpfox::getT('waytime_profile'), $aVals, 'profile_id = '.(int)$iId);
        }

        public function remember()
        {
            $iNewTime = PHPFOX_TIME + (strtotime("+2 minutes") - time());
            return $this->database()->update(Phpfox::getT('waytime_profile'), array('remind_time' => $iNewTime), 'user_id = '.Phpfox::getUserId());
        }

        public function processRun()
        {
            Phpfox::getLib('template')->setHeader(array(
                'script.js' => 'module_waytime',
                'style.css' => 'module_waytime'
            ));
            $aProfile = Phpfox::getService('waytime')->getProfile();
            if($aProfile['remind_time'] < PHPFOX_TIME)
            {
                Phpfox::getLib('template')->setHeader(array(
                    'auto.js' => 'module_waytime'
                ));
            }
        }
    }
?>
