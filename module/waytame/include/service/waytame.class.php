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
    * @package          Module_Friend
    * @version         $Id: friend.class.php 5913 2013-05-13 08:36:48Z Raymond_Benc $
    */
    class Waytame_Service_Waytame extends Phpfox_Service
    {
        public function getFriend()
        {
            $aRows = $this->database()->select('f.*,count(u.user_id) AS total_question, ' . Phpfox::getUserField())
            ->from(Phpfox::getT('friend'), 'f')
            ->join(Phpfox::getT('user'), 'u', 'u.user_id = f.friend_user_id')
            ->join(Phpfox::getT('waytame_question'),'w','w.user_id=u.user_id AND w.expire_time > '.PHPFOX_TIME)
            ->where('f.is_page = 0 AND f.user_id = ' . Phpfox::getUserId())
            ->group('u.user_id')
            ->order('u.full_name ASC')
            ->execute('getSlaveRows');

            foreach ($aRows as $iKey => $aRow)
            {        
                if (Phpfox::getUserId() == $aRow['user_id'])
                {
                    unset($aRows[$iKey]);

                    continue;
                }

                $aRows[$iKey]['full_name'] = html_entity_decode(Phpfox::getLib('parse.output')->split($aRow['full_name'], 20), null, 'UTF-8');                        
                $aRows[$iKey]['user_profile'] = ($aRow['profile_page_id'] ? Phpfox::getService('pages')->getUrl($aRow['profile_page_id'], '', $aRow['user_name']) : Phpfox::getLib('url')->makeUrl($aRow['user_name']));
                $aRows[$iKey]['is_page'] = ($aRow['profile_page_id'] ? true : false);
                $aRows[$iKey]['user_image'] = Phpfox::getLib('image.helper')->display(array(
                    'user' => $aRow,
                    'suffix' => '_50_square',
                    'max_height' => 50,
                    'max_width' => 50,
                    'return_url' => true
                    )
                );
            }        

            return $aRows;
        }

        public function getQuestions($iUserId, $iPage = 0, $iLimit = 4,$bCheck = false)
        {
            $sSelect = '';
            if($bCheck)
            {
                $sSelect = ',wa.answer_id';
            }
            $this->database()->select('f.feed_id, f.type_id, wq.question AS feed_title, f.item_id, wq.*'.$sSelect)
            ->from(Phpfox::getT('waytame_question'),'wq');
            if($bCheck)
            {
                $this->database()->leftJoin(Phpfox::getT('waytame_answer'),'wa','wa.question_id=wq.question_id AND wa.user_id='.(int)Phpfox::getUserId());
            }
            $aRows = $this->database()->where('wq.user_id='.(int)$iUserId.' AND is_expire = 0 AND wq.expire_time > '.PHPFOX_TIME)
            ->join(Phpfox::getT('feed'),'f',"f.item_id=wq.question_id AND f.type_id='waytame'")
            ->limit($iPage * $iLimit,$iLimit)
            ->order('wq.time_stamp DESC')
            ->execute('getRows');

            foreach($aRows as $key => $aRow)
            {
                $aRows[$key]['feed_link'] = Phpfox::getLib('url')->permalink('waytame',$aRow['question_id'],$aRow['question']);
                $aRows[$key]['like_type_id'] = 'waytame';
                $aRows[$key]['feed_is_liked'] = Phpfox::getService('like')->didILike($aRow['type_id'], $aRow['item_id']);
                $aRows[$key]['feed_is_disliked'] = Phpfox::getService('like')->hasBeenMarked(2,$aRow['type_id'], $aRow['item_id']);
            }
            return $aRows;
        }

        public function getQuestion($iQuestionId)
        {
            $aRow = $this->database()->select('wq.*,f.feed_id,f.type_id,'.Phpfox::getUserField())
            ->from(Phpfox::getT('waytame_question'),'wq')
            ->join(Phpfox::getT('feed'),'f','f.item_id = wq.question_id')
            ->join(Phpfox::getT('user'),'u','u.user_id=wq.user_id')
            ->where('question_id='.(int)$iQuestionId)
            ->execute('getRow');

            if(!isset($aRow['question_id']))
            {
                return false;
            }
            $aRow['question_link'] = Phpfox::getLib('url')->permalink('waytame',$aRow['question_id'],$aRow['question']);
            $aRow['answers'] = $this->getAnswers($aRow['question_id']);
            $aRow['feed_is_liked'] = Phpfox::getService('like')->didILike('waytame', $aRow['question_id']);
            $aRow['feed_is_disliked'] = Phpfox::getService('like')->hasBeenMarked(2, 'waytame', $aRow['question_id']);

            return $aRow;
        }

        public function getAnswers($iQuestionId,$iPage = 0,$iLimit = 3)
        {
            $aRows = $this->database()->select('w.*,'.Phpfox::getUserField())
            ->from(Phpfox::getT('waytame_answer'),'w')
            ->join(Phpfox::getT('user'),'u','u.user_id=w.user_id')
            ->where('question_id='.(int)$iQuestionId)
            ->limit($iPage * $iLimit,$iLimit)
            ->order('w.time_stamp DESC')
            ->execute('getRows');

            $aResults = array();
            foreach($aRows as $key => $aRow)
            {
                $aRows[$key]['like_type_id'] = 'waytame_answer';
                $aRows[$key]['is_liked'] = Phpfox::getService('waytame.like')->isLike($aRow['answer_id']);
                $aRows[$key]['is_disliked'] = Phpfox::getService('waytame.like')->isDislike($aRow['answer_id']);
                $aResults[$aRow['answer_id']] = $aRows[$key];
            }

            return $aResults;
        }
        
        public function getTotalAnswer($iQuestionId)
        {
            return (int)$this->database()->select('count(*)')
            ->from(Phpfox::getT('waytame_answer'),'w')
            ->where('question_id='.(int)$iQuestionId)
            ->execute('getSlaveField');
        }

        public function getAnswer($iAnswerId)
        {
            $aRow = $this->database()->select('wa.*,wq.question,wq.user_id AS owner_user_id,'.Phpfox::getUserField())
            ->from(Phpfox::getT('waytame_answer'),'wa')
            ->join(Phpfox::getT('waytame_question'),'wq','wq.question_id=wa.question_id')
            ->join(Phpfox::getT('user'), 'u', 'u.user_id = wa.user_id')
            ->where('answer_id='.(int)$iAnswerId)
            ->execute('getRow');

            if(!isset($aRow['answer_id']))
            {
                return false;
            }
            $aRow['like_type_id'] = 'waytame_answer';
            $aRow['is_liked'] = Phpfox::getService('like')->didILike('waytame_answer', $aRow['answer_id']);
            $aRow['is_disliked'] = Phpfox::getService('like')->hasBeenMarked(2,'waytame_answer', $aRow['answer_id']);

            return $aRow;
        }

        public function getInfoForAction($aItem)
        {
            if (is_numeric($aItem))
            {
                $aItem = array('item_id' => $aItem);
            }
            $aRow = $this->database()->select('w.question_id, w.question AS title, w.user_id, u.gender, u.full_name')    
            ->from(Phpfox::getT('waytame_question'), 'w')
            ->join(Phpfox::getT('user'), 'u', 'u.user_id = w.user_id')
            ->where('w.question_id = ' . (int) $aItem['item_id'])
            ->execute('getSlaveRow');

            $aRow['link'] = Phpfox::getLib('url')->permalink('waytame', $aRow['question_id'], $aRow['title']);
            return $aRow;
        }

        public function get($iUserId = null, $iPage = 0, $iLimit = 10)
        {
            if(!$iUserId)
            {
                $iUserId = Phpfox::getUserId();
            }
            $aRows = $this->database()->select('wq.*,f.feed_id,'.Phpfox::getUserField())
            ->from(Phpfox::getT('waytame_question'),'wq')
            ->join(Phpfox::getT('user'),'u','u.user_id=wq.user_id')
            ->join(Phpfox::getT('feed'),'f',"f.item_id=wq.question_id AND type_id='waytame'")
            ->where('wq.user_id='.(int)$iUserId.' AND is_expire = 0 AND wq.expire_time > '.PHPFOX_TIME)
            ->limit($iPage * $iLimit,$iLimit)
            ->order('wq.time_stamp DESC')
            ->execute('getRows');

            foreach($aRows as $key => $aRow)
            {
                $aRows[$key]['question_link'] = Phpfox::getLib('url')->permalink('waytame',$aRow['question_id'],$aRow['question']);
                $aRows[$key]['answers'] = $this->getAnswers($aRow['question_id']);
                $aRows[$key]['feed_is_liked'] = Phpfox::getService('like')->didILike('waytame', $aRow['question_id']);
                $aRows[$key]['feed_is_disliked'] = Phpfox::getService('like')->hasBeenMarked(2, 'waytame', $aRow['question_id']);
            }

            return $aRows;
        }

        public function checkIsAnswer($iQuestionId,$iUserId = null)
        {
            if(!$iUserId)
            {
                $iUserId = Phpfox::getUserId();
            }
            $aAnswer = $this->database()->select('*')
            ->from(Phpfox::getT('waytame_answer'))
            ->where('question_id='.(int)$iQuestionId.' AND user_id='.(int)$iUserId)
            ->execute('getRow');
            if(!isset($aAnswer['answer_id']))
            {
                return false;
            }
            return true;
        }

        public function getTotalQuestion($iUserId)
        {
            $iTotal = (int)$this->database()->select('count(*)')
            ->from(Phpfox::getT('waytame_question'))
            ->where('user_id='.(int)$iUserId.' AND is_expire = 0 AND expire_time > '.PHPFOX_TIME)
            ->execute('getSlaveField');
            return $iTotal;
        }

        public function processFriends(&$aFriends)
        {
            foreach($aFriends as $key => $aFriend)
            {
                $aQuestions = $this->getQuestions($aFriend['user_id']);
                if($aQuestions && count($aQuestions))
                {
                    $aFriends[$key]['aQuestions'] = $aQuestions;
                    $aFriends[$key]['count_question'] = $this->getTotalQuestion($aFriend['user_id']);
                }
            }
        }

        public function getExpireFriendQuestionToday()
        {
            $iBeginTime = Phpfox::getLib('date')->mktime(0,0,1,date('m',PHPFOX_TIME),date('j',PHPFOX_TIME),date('Y',PHPFOX_TIME));
            $iEndTime = Phpfox::getLib('date')->mktime(23,59,59,date('m',PHPFOX_TIME),date('j',PHPFOX_TIME),date('Y',PHPFOX_TIME));
            $aQuestions = $this->database()->select('u.*,f.user_id AS friend_id,f.friend_user_id')
            ->from(Phpfox::getT('waytame_question'),'wq')
            ->join(Phpfox::getT('friend'),'f','f.friend_user_id=wq.user_id AND f.user_id='.Phpfox::getUserId())
            ->join(Phpfox::getT('user'),'u','u.user_id=f.friend_user_id')
            ->where("is_expire = 1 OR (wq.expire_time > $iBeginTime AND wq.expire_time < $iEndTime)")
            ->group('u.user_id')
            ->order('wq.time_stamp DESC')
            ->execute('getRows');
            foreach($aQuestions as $key => $aQuestion)
            {
                $aQuestions[$key]['lists'] = array();
            }
            return $aQuestions;
        }
        
        public function getWaytameNotificationOfCurrentUser()
        {
            $sDay = date('j-m-Y',PHPFOX_TIME);
            return $this->database()->select('*')
            ->from(Phpfox::getT('waytame_notification'))
            ->where('user_id='.(int)Phpfox::getUserId()." AND day='$sDay'")
            ->execute('getRow');
        }
        
        public function getWaytameNotification($iNotificationId)
        {
            return $this->database()->select('*')
            ->from(Phpfox::getT('waytame_notification'))
            ->where('notification_id='.(int)$iNotificationId)
            ->execute('getRow');
        }
        
        public function getAll($iUserId = null, $iPage = 0, $iLimit = 10)
        {
            if(!$iUserId)
            {
                $iUserId = Phpfox::getUserId();
            }
            $aRows = $this->database()->select('wq.*,f.feed_id,'.Phpfox::getUserField())
            ->from(Phpfox::getT('waytame_question'),'wq')
            ->join(Phpfox::getT('user'),'u','u.user_id=wq.user_id')
            ->join(Phpfox::getT('feed'),'f',"f.item_id=wq.question_id AND type_id='waytame'")
            ->where('wq.user_id='.(int)$iUserId)
            ->limit($iPage * $iLimit,$iLimit)
            ->order('wq.time_stamp DESC')
            ->execute('getRows');

            foreach($aRows as $key => $aRow)
            {
                $aRows[$key]['question_link'] = Phpfox::getLib('url')->permalink('waytame',$aRow['question_id'],$aRow['question']);
                $aRows[$key]['answers'] = $this->getAnswers($aRow['question_id']);
                $aRows[$key]['feed_is_liked'] = Phpfox::getService('like')->didILike('waytame', $aRow['question_id']);
                $aRows[$key]['feed_is_disliked'] = Phpfox::getService('like')->hasBeenMarked(2, 'waytame', $aRow['question_id']);
            }

            return $aRows;
        }
    }
?>
