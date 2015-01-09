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
    */
    class Waytame_Service_Process extends Phpfox_Service 
    {
        public function addQestion($aVals)
        {
            $iId = $this->database()->insert(Phpfox::getT('waytame_question'),$aVals);
            if(Phpfox::isModule('feed'))
            {
                $iFeedId = Phpfox::getService('feed.process')->add('waytame', $iId, 1, 0);
                Phpfox::getService('feed')->processAjax($iFeedId);  
            }
            return $iId;
        }

        public function addAnswer($aVals)
        {
            $aQuestion = $this->database()->select('*')
            ->from(Phpfox::getT('waytame_answer'))
            ->where('user_id='.$aVals['user_id'].' AND question_id='.(int)$aVals['question_id'])
            ->execute('getRow');
            if(isset($aQuestion['answer_id']))
            {
                return false;
            }
            $iId = $this->database()->insert(Phpfox::getT('waytame_answer'),$aVals);
            if($iId)
            {
                $aQuestion = Phpfox::getService('waytame')->getQuestion($aVals['question_id']);
                $this->database()->update(Phpfox::getT('waytame_question'),array(
                    'total_answer' => (int)$aQuestion['total_answer'] + 1
                    ),'question_id='.(int)$aQuestion['question_id']);
            }

            return $iId;
        }

        public function deleteQuestion($iQuestionId)
        {
            return $this->database()->delete(Phpfox::getT('waytame_question'),'question_id='.(int)$iQuestionId.' AND user_id='.(int)Phpfox::getUserId());
        }

        public function deleteAnswer($iAnswerId)
        {
            $aAnswer = Phpfox::getService('waytame')->getAnswer($iAnswerId);
            if(!isset($aAnswer['answer_id']))
            {
                return false;
            }
            if($aAnswer['owner_user_id'] != Phpfox::getUserId() && $aAnswer['user_id'] != Phpfox::getUserId())
            {
                return false;
            }
            
            $result = $this->database()->delete(Phpfox::getT('waytame_answer'),'answer_id='.(int)$iAnswerId);
            if($result)
            {
                $aQuestion = Phpfox::getService('waytame')->getQuestion($aAnswer['question_id']);
                $this->database()->update(Phpfox::getT('waytame_question'),array(
                    'total_answer' => (int)$aQuestion['total_answer'] - 1
                    ),'question_id='.(int)$aQuestion['question_id']);
            }
            return $result;
        }

        public function addNotificationQuestionExpire()
        {
            $aNotification = Phpfox::getService('waytame')->getWaytameNotificationOfCurrentUser();
            if(isset($aNotification['notification_id']))
            {
                return false;
            }
            $aUserHasExpireQuestions = Phpfox::getService('waytame')->getExpireFriendQuestionToday();
            if(!empty($aUserHasExpireQuestions) && count($aUserHasExpireQuestions))
            {
                $sMessage = "Any questions will expire today for ";
                $i = 0;
                foreach($aUserHasExpireQuestions as $key => $aUser)
                {
                    $sMessage.='<span class="drop_data_user">'.$aUser['full_name']."</span>, ";
                    $i++;
                    if($i >= 3)
                    {
                        break;
                    }
                }
                $sMessage = trim($sMessage,", ");
                if(count($aUserHasExpireQuestions) > 4)
                {
                    $sMessage.=" and other ".(count($aUserHasExpireQuestions) - 4)." friends.";
                }
                $iNotificationId = $this->database()->insert(Phpfox::getT('waytame_notification'),array(
                    'user_id' => Phpfox::getUserId(),
                    'message' => $sMessage,
                    'day' => date('j-m-Y',PHPFOX_TIME),
                    'time_stamp' => PHPFOX_TIME
                ));
                $aInsert = array(
                    'type_id' => 'waytame_expirequestion',
                    'item_id' => $iNotificationId,
                    'user_id' => Phpfox::getUserId(),
                    'owner_user_id' => Phpfox::getUserId(),
                    'is_seen' => 0,
                    'time_stamp' => PHPFOX_TIME
                );
                $this->database()->insert(Phpfox::getT('notification'),$aInsert);
            }
        }
    }
?>
