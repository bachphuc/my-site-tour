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
    class FollowedPost_Service_FollowedPost extends Phpfox_Service {

        public function makeFollowed($iFeedId) {
            $aFeed = $this->database()->select('*')
            ->from(Phpfox::getT('feed'))
            ->where('feed_id=' . (int) $iFeedId)
            ->execute('getRow');    
            $aInsert = array(
                'feed_id' => $aFeed['feed_id'],
                'user_followed' => $aFeed['user_id'],
                'app_id' => $aFeed['app_id'],
                'privacy' => $aFeed['privacy'],
                'privacy_comment' => $aFeed['privacy_comment'],
                'type_id' => $aFeed['type_id'],
                'user_id' => Phpfox::getUserId(),
                'parent_user_id' => $aFeed['parent_user_id'],
                'item_id' => $aFeed['item_id'],
                'feed_reference' => $aFeed['feed_reference'],
                'parent_feed_id' => $aFeed['parent_feed_id'],
                'parent_module_id' => $aFeed['parent_module_id'],
                'time_update' => $aFeed['time_update'],        
            ); 

            $this->database()->insert(Phpfox::getT('followed_feed'), $aInsert);
            // $this->database()->delete(Phpfox::getT('feed'), 'feed_id=' . (int) $iFeedId);
            return TRUE;
        }

        public function checkCommentVisible($idComent)
        {
            $iCnt = $this->database()->select('*')
            ->from(Phpfox::getT('strongbox_feed'))
            ->where('comment_id='.$idComent.' AND user_id_sb='.Phpfox::getUserId())
            ->execute('getSlaveField');
            if (isset($iCnt) && $iCnt) 
            {
                return true;
            }
            else
            {
                return false;
            }
        }

        public function isPost($item_id,$comment_type_id) {   
            $iCnt = $this->database()->select('*')
            ->from(Phpfox::getT('feed'))
            ->where('item_id='.$item_id.' AND type_id="'.$comment_type_id.'"')
            ->execute('getSlaveField');
            if (isset($iCnt) && $iCnt) {
                return $iCnt;
            }
        }

        public function makePublic($iFeedId) {
            $aFeed = $this->database()->select('*')
            ->from(Phpfox::getT('followed_feed'))
            ->where('feed_id=' . (int) $iFeedId)
            ->execute('getRow');
            //$this->database()->insert(Phpfox::getT('feed'), $aFeed);
            $iCnt = $this->database()->select('*')
            ->from(Phpfox::getT('followed_feed'))
            ->where('feed_id=' . (int) $iFeedId.' AND user_id='.Phpfox::getUserId())
            ->execute('getSlaveField');
            $this->database()->delete(Phpfox::getT('followed_feed'), 'followed_id=' . (int) $iCnt);
            return TRUE;
        }

        /* $iCnt = $this->database()->select('COUNT(*)')
        ->from(Phpfox::getT('followed_feed'))
        ->where('feed_id=' . (int) $iFeedId.' AND user_followed='.Phpfox::getUserId())
        ->execute('getSlaveField');*/
        public function isFollowed($iFeedId) {
            $iCnt = $this->database()->select('*')
            ->from(Phpfox::getT('followed_feed'))
            ->where('feed_id=' . (int) $iFeedId.' AND user_id='.Phpfox::getUserId())
            ->execute('getSlaveField');
            if (isset($iCnt) && $iCnt) {
                return $iCnt;
            } else {
                return FALSE;
            }
        }

        public function countFollow($iFeedId) {
            $iCnt = $this->database()->select('COUNT(*)')
            ->from(Phpfox::getT('followed_feed'))
            ->where('feed_id=' . (int) $iFeedId)
            ->execute('getSlaveField');
            return $iCnt;

        }

        public function getInforUser($id) {
            $iCnt = $this->database()->select('*')
            ->from(Phpfox::getT('user'))
            ->where('user_id=' . (int)$id)
            ->execute('getRows');
            return $iCnt;

        }

    }

?>
