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
    class Waytame_Service_Like extends Phpfox_Service 
    {
        public function isLike($iItemId)
        {
            $aLike = $this->database()->select('*')
            ->from(Phpfox::getT('like'))
            ->where("type_id='waytame_answer' AND item_id = '$iItemId' AND user_id=".(int)Phpfox::getUserId())
            ->execute('getRow');
            if(isset($aLike['like_id']))
            {
                return true;
            }
            return false;
        }
        
        public function isDislike($iItemId)
        {
            $aDislike = $this->database()->select('*')
            ->from(Phpfox::getT('action'))
            ->where("action_type_id = 2 AND item_type_id='waytame_answer' AND item_id = '$iItemId' AND user_id=".(int)Phpfox::getUserId())
            ->execute('getRow');
            if(isset($aDislike['action_id']))
            {
                return true;
            }
            return false;
        }
        
        public function addLike($iItemId)
        {
            if($this->isLike($iItemId))
            {
                return false;
            }
            if($this->isDislike($iItemId))
            {
                $this->database()->delete(Phpfox::getT('action'),"item_type_id='waytame_answer' AND action_type_id = 2 AND item_id = '$iItemId' AND user_id=".Phpfox::getUserId());
            }
            $iId = $this->database()->insert(Phpfox::getT('like'),array(
                'type_id' => 'waytame_answer',
                'item_id' => $iItemId,
                'user_id' => Phpfox::getUserId(),
                'time_stamp' => PHPFOX_TIME
            ));
            if($iId)
            {
                $aAnswer = Phpfox::getService('waytame')->getAnswer($iItemId);
                Phpfox::getService('notification.process')->add('waytame_likeanswer', $iItemId, $aAnswer['user_id']);
                $this->database()->updateCount('like', 'type_id = \'waytame_answer\' AND item_id = ' . (int) $iItemId . '', 'total_like', 'waytame_answer', 'answer_id = ' . (int) $iItemId);    
            }            
            return $iId;
        }
        
        public function removeLike($iItemId)
        {
            if(!$this->isLike($iItemId))
            {
                return false;
            }
            
            $iId = $this->database()->delete(Phpfox::getT('like'),"type_id='waytame_answer' AND item_id='$iItemId' AND user_id=".Phpfox::getUserId());
            
            if($iId)
            {
                $this->database()->updateCount('like', 'type_id = \'waytame_answer\' AND item_id = ' . (int) $iItemId . '', 'total_like', 'waytame_answer', 'answer_id = ' . (int) $iItemId);    
            }   
            
            return $iId;
        }
        
        public function addDislike($iItemId)
        {
            if($this->isLike($iItemId))
            {
                $this->database()->delete(Phpfox::getT('like'),"type_id='waytame_answer' AND item_id='$iItemId' AND user_id=".Phpfox::getUserId());
            }
            
            $iId = $this->database()->insert(Phpfox::getT('action'),array(
                'action_type_id' => 2,
                'item_type_id' => 'waytame_answer',
                'item_id' => $iItemId,
                'user_id' => Phpfox::getUserId(),
                'time_stamp' => PHPFOX_TIME
            ));
            
            if($iId)
            {
                $aAnswer = Phpfox::getService('waytame')->getAnswer($iItemId);
                Phpfox::getService('notification.process')->add('waytame_dislikeanswer', $iItemId, $aAnswer['user_id']);
                $this->database()->updateCount('like', 'type_id = \'waytame_answer\' AND item_id = ' . (int) $iItemId . '', 'total_like', 'waytame_answer', 'answer_id = ' . (int) $iItemId);    
            }   
            
            return $iId;

        }
        
        public function removeDislike($iItemId)
        {
            if(!$this->isDislike($iItemId))
            {
                return false;
            }
            $iId = $this->database()->delete(Phpfox::getT('action'),"action_type_id = 2 AND item_type_id = 'waytame_answer' AND item_id = $iItemId AND user_id=".Phpfox::getUserId());
            
            if($iId)
            {
                $this->database()->updateCount('like', 'type_id = \'waytame_answer\' AND item_id = ' . (int) $iItemId . '', 'total_like', 'waytame_answer', 'answer_id = ' . (int) $iItemId);    
            }   
            
            return $iId;
        }
    }
?>
