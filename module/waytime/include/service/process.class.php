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
                return $this->database()->update(Phpfox::getT('waytime_profile_question'), array('answer_id' => $iAnswerId, 'note' => $sNote), 'profile_id = '.(int)$iProfileId. ' AND question_id = '.(int)$iQuestionId);
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

        public function updateProfile($iId)
        {
            $iTotalQuestion = Phpfox::getService('waytime')->getTotalQuestion();
            $iTotalAnswer = Phpfox::getService('waytime')->getTotalAnswer($iId);

            $aUpdate = array(
                'current' => $iTotalAnswer,
                'is_complete' => ($iTotalAnswer < $iTotalQuestion ? 0 : 1)
            );

            return $this->database()->update(Phpfox::getT('waytime_profile'), $aUpdate, 'profile_id = '.(int)$iId);
        }

        public function remember()
        {
            // Time remain can update in admincp
            $iNewTime = PHPFOX_TIME + (strtotime("+" . Phpfox::getParam('waytime.time_remain_complete_waytime')) - time());
            return $this->database()->update(Phpfox::getT('waytime_profile'), array('remind_time' => $iNewTime), 'user_id = '.Phpfox::getUserId());
        }

        public function addNotification($sType, $iItemId, $iOwnerUserId, $iSenderUserId = null)
        {
            $aInsert = array(
                'type_id' => $sType,
                'item_id' => $iItemId,
                'user_id' => $iOwnerUserId,    
                'owner_user_id' => ($iSenderUserId === null ? Phpfox::getUserId() : $iSenderUserId),
                'time_stamp' => PHPFOX_TIME        
            );    

            $this->database()->insert(Phpfox::getT('notification'), $aInsert);
        }

        public function processRun()
        {
            if(!Phpfox::isUser())
            {
                return true;
            }
            if(Phpfox::isAdminPanel())
            {
                return true;
            }
            Phpfox::getLib('template')->setHeader(array(
                'script.js' => 'module_waytime',
                'style.css' => 'module_waytime'
            ));

            $aProfile = Phpfox::getService('waytime')->getProfile();

            // Check if first register show popup
            if(!$aProfile['is_start'])
            {

                $this->database()->update(Phpfox::getT('waytime_profile'), array('is_start' => 1), 'profile_id = '.(int)$aProfile['profile_id']);

                Phpfox::getLib('template')->setHeader(array(
                    'auto.js' => 'module_waytime'
                ));
            }
            else
            {
                if($aProfile['remind_time'] < PHPFOX_TIME)
                {
                    // If is waiing show popup
                    if((int)$aProfile['is_waiting'] == 1)
                    {
                        $this->database()->update(Phpfox::getT('waytime_profile'), array('is_waiting' => 2), 'profile_id = '.(int)$aProfile['profile_id']);
                        Phpfox::getLib('template')->setHeader(array(
                            'auto.js' => 'module_waytime'
                        ));
                    }
                    else
                    {
                        if(Phpfox::isModule('notification'))
                        {
                            if(!$aProfile['is_complete'] || !$aProfile['is_waiting'])
                            {
                                $this->addNotification('waytime_completeWaytime',$aProfile['profile_id'], Phpfox::getUserId());
                            }
                            else if(!$aProfile['is_finish'])
                            {
                                $this->addNotification('waytime_unlockWaytime',$aProfile['profile_id'], Phpfox::getUserId());
                            }
                        }
                        $this->remember();
                    }
                }
            }

            $aProfile = Phpfox::getService('waytime')->getProfile();
            if((int)$aProfile['is_unlock'] == 1)
            {
                Phpfox::getLib('template')->setHeader('<script type="text/javascript">waytime_status = 9;waytime_tooltip = "";waytime_url = "'.Phpfox::getLib('url')->makeUrl(Phpfox::getUserBy('user_name').'.waytime').'";</script>');
                return true;
            }

            if(!$aProfile['is_complete'])
            {
                $iTotal = Phpfox::getService('waytime')->getRemainQuestion();
                $sTitle = ($iTotal ? Phpfox::getPhrase('waytime.would_you_like_to_complete_the_total_remaining_questions', array('total' => $iTotal)) : Phpfox::getPhrase('waytime.would_you_like_to_freeze_w_time_capsule'));
                Phpfox::getLib('template')->setHeader('<script type="text/javascript">waytime_status = 0;waytime_tooltip = "'.$sTitle.'";</script>');
            }
            else if($aProfile['is_complete'] && !$aProfile['is_waiting'])
            {
                $sTitle = Phpfox::getPhrase('waytime.would_you_like_to_freeze_w_time_capsule');
                Phpfox::getLib('template')->setHeader('<script type="text/javascript">waytime_status = 1;waytime_tooltip = "'.$sTitle.'";</script>');
            }
            else if((int)$aProfile['is_waiting'] == 1)
            {
                $iTotal = $aProfile['remind_time'] - PHPFOX_TIME;
                $iTotal = (int)($iTotal / (30 * 24 * 60 *60));
                Phpfox::getLib('template')->setHeader('<script type="text/javascript">waytime_status = 2;waytime_tooltip = "'.Phpfox::getPhrase('waytime.total_months_left_to_unfreeze_the_w_time_capsule', array('total' => $iTotal, 's' => ($iTotal > 1 ? 's' : ''))).'";</script>');

            }
            else if((int)$aProfile['is_waiting'] == 2 && !$aProfile['is_finish'])
            {
                Phpfox::getLib('template')->setHeader('<script type="text/javascript">waytime_status = 3;waytime_tooltip = "'.Phpfox::getPhrase('waytime.would_you_like_to_complete_your_unlocked_w_time_capsule').'";</script>');
            }
        }

        public function freeze()
        {
            $aProfile = Phpfox::getService('waytime')->getProfile();
            $iWaitTime = PHPFOX_TIME + (strtotime("+".Phpfox::getParam('waytime.time_waiting_to_unlock_waytime')) - time());
            $aUpdate = array(
                'is_waiting' => true,
                'remind_time' => $iWaitTime
            );
            return $this->database()->update(Phpfox::getT('waytime_profile'), $aUpdate,'profile_id = '.(int)$aProfile['profile_id']);
        }

        public function unlock($aVals)
        {
            $aProfile = Phpfox::getService('waytime')->getProfile();
            foreach($aVals as $iId => $value)   
            {
                $this->database()->update(Phpfox::getT('waytime_profile_question'), array('is_helpful' => $value), 'profile_id = '.(int)$aProfile['profile_id']. ' AND question_id = '.(int)$iId);
            }
            $aUpdate = array(
                'is_unlock' => 1,
                'is_finish' => 1
            );
            return $this->database()->update(Phpfox::getT('waytime_profile'), $aUpdate,'profile_id = '.(int)$aProfile['profile_id']);
        }

        public function processRunAjax()
        {
            if(!Phpfox::isUser())
            {
                return true;
            }
            if(Phpfox::isAdminPanel())
            {
                return true;
            }

            $aProfile = Phpfox::getService('waytime')->getProfile();

            // Check if first register show popup
            if(!$aProfile['is_start'])
            {

                $this->database()->update(Phpfox::getT('waytime_profile'), array('is_start' => 1), 'profile_id = '.(int)$aProfile['profile_id']);

                Phpfox::getLib('ajax')->call('$Core.waytime.begin();');
            }
            else
            {
                if($aProfile['remind_time'] < PHPFOX_TIME)
                {
                    // If is waiing show popup
                    if((int)$aProfile['is_waiting'] == 1)
                    {
                        $this->database()->update(Phpfox::getT('waytime_profile'), array('is_waiting' => 2), 'profile_id = '.(int)$aProfile['profile_id']);
                        Phpfox::getLib('ajax')->call('$Core.waytime.begin();');
                        $sTitle = Phpfox::getPhrase('waytime.would_you_like_to_complete_your_unlocked_w_time_capsule');
                        Phpfox::getLib('ajax')->call('$(".waytime_watch a span").html("'.$sTitle.'");');
                        Phpfox::getLib('ajax')->call('$(".waytime_watch a").attr("onclick","$Core.waytime.begin();return false;");');
                        Phpfox::getLib('ajax')->call('$(".waytime_watch").attr("id","waytime_watch");');
                    }
                    else
                    {
                        if(Phpfox::isModule('notification'))
                        {
                            if(!$aProfile['is_complete'] || !$aProfile['is_waiting'])
                            {
                                $this->addNotification('waytime_completeWaytime',$aProfile['profile_id'], Phpfox::getUserId());
                            }
                            else if(!$aProfile['is_finish'])
                            {
                                $this->addNotification('waytime_unlockWaytime',$aProfile['profile_id'], Phpfox::getUserId());
                            }
                        }
                        $this->remember();
                    }
                }
            }

            $aProfile = Phpfox::getService('waytime')->getProfile();
            if((int)$aProfile['is_unlock'] == 1)
            {
                $sToolTip = '';
                Phpfox::getLib('ajax')->call('$(".waytime_watch a span").html("'.$sToolTip.'");');
                Phpfox::getLib('ajax')->call('$(".waytime_watch a").attr("onclick","$Core.waytime.begin();return false;");');
                Phpfox::getLib('ajax')->call('$Core.waytime.bStopCheck = true;');
                return true;
            }

            if(!$aProfile['is_complete'])
            {
                $iTotal = Phpfox::getService('waytime')->getRemainQuestion();
                $sTitle = ($iTotal ? Phpfox::getPhrase('waytime.would_you_like_to_complete_the_total_remaining_questions', array('total' => $iTotal)) : Phpfox::getPhrase('waytime.would_you_like_to_freeze_w_time_capsule'));

                Phpfox::getLib('ajax')->call('$(".waytime_watch a span").html("'.$sTitle.'");');
                Phpfox::getLib('ajax')->call('$(".waytime_watch a").attr("onclick","$Core.waytime.begin();return false;");');
                return true;
            }
            else if($aProfile['is_complete'] && !$aProfile['is_waiting'])
            {
                $sTitle = Phpfox::getPhrase('waytime.would_you_like_to_freeze_w_time_capsule');

                Phpfox::getLib('ajax')->call('$(".waytime_watch a span").html("'.$sTitle.'");');
                Phpfox::getLib('ajax')->call('$(".waytime_watch a").attr("onclick","$Core.waytime.begin();return false;");');
            }
            else if((int)$aProfile['is_waiting'] == 1)
            {
                $iTotal = $aProfile['remind_time'] - PHPFOX_TIME;
                $iTotal = (int)($iTotal / (30 * 24 * 60 *60));

                $sTitle = Phpfox::getPhrase('waytime.total_months_left_to_unfreeze_the_w_time_capsule', array('total' => $iTotal, 's' => ($iTotal > 1 ? 's' : '')));
                Phpfox::getLib('ajax')->call('$(".waytime_watch a span").html("'.$sTitle.'");');
                Phpfox::getLib('ajax')->call('$(".waytime_watch a").attr("onclick","return false;");');
            }
            else if((int)$aProfile['is_waiting'] == 2 && !$aProfile['is_finish'])
            {
                $sTitle = Phpfox::getPhrase('waytime.would_you_like_to_complete_your_unlocked_w_time_capsule');
                Phpfox::getLib('ajax')->call('$(".waytime_watch a span").html("'.$sTitle.'");');
                Phpfox::getLib('ajax')->call('$(".waytime_watch a").attr("onclick","$Core.waytime.begin();return false;");');
            }
        }
    }
?>
