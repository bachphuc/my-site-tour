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

    class StrongBox_Service_StrongBox extends Phpfox_Service {

        public function getInforUser($id) {
            $iCnt = $this->database()->select('*')
            ->from(Phpfox::getT('user'))
            ->where('user_id=' . (int)$id)
            ->execute('getRows');
            return $iCnt;

        }

        public function isPost($item_id,$comment_type_id) {   
            $iCnt = $this->database()->select('*')
            ->from(Phpfox::getT('feed'))
            ->where('((user_id=' .Phpfox::getUserId().' AND type_id<>"feed_comment" AND type_id<>"feed_egift") OR parent_user_id='.Phpfox::getUserId().') AND time_update<='.PHPFOX_TIME.' AND item_id='.$item_id.' AND type_id="'.$comment_type_id.'"')
            ->execute('getSlaveField');
            if (isset($iCnt) && $iCnt) {
                return $iCnt;
            } else {
                return FALSE;
            }
        }

        public function isCommentStrongBox($item_id,$comment_type_id) {
            $iCnt = $this->database()->select('*')
            ->from(Phpfox::getT('feed'))
            ->where('item_id='.$item_id.' AND type_id="'.$comment_type_id.'"')
            ->execute('getSlaveField');
            if (isset($iCnt) && $iCnt) {
                return $iCnt;
            } else {
                return FALSE;
            }
        }

        public function isStrongBox($iFeedId) {
            $iCnt = $this->database()->select('*')
            ->from(Phpfox::getT('strongbox_feed'))
            ->where('feed_id=' . (int) $iFeedId.' AND user_id_sb='.Phpfox::getUserId().' AND type_id_sb=1')
            ->execute('getSlaveField');
            if (isset($iCnt) && $iCnt) {
                return TRUE;
            } else {
                return FALSE;
            }
        }

        public function isStrongBoxIcon($item_id,$type_id) {
            if($type_id =='feed_comment')
            {
                $type_id = 'feed';
            }
            $aFeed = $this->database()->select('*')
            ->from(Phpfox::getT('comment'))
            ->where('item_id=' . (int) $item_id.' AND type_id="'.$type_id.'"')
            ->execute('getRows');
            //d($aFeed);
            $sListId='';
            if(count($aFeed) > 0)
            {
                for($i = 0; $i<count($aFeed);$i++)
                {
                    $idComent = $aFeed[$i]['comment_id'];
                    $iCnt = $this->database()->select('*')
                    ->from(Phpfox::getT('strongbox_feed'))
                    ->where('comment_id='.$idComent.' AND type_id_sb=0 AND user_id_sb="'.Phpfox::getUserId().'"')
                    ->execute('getSlaveField');
                    if (isset($iCnt) && $iCnt) {
                        if($sListId != '')
                        {
                            $sListId = $sListId.'_';
                        }
                        $sListId = $sListId.$idComent;
                    }
                }
            }
            return $sListId;
        }

        public function makeStrongBox($iFeedId,$type) {
            /*$aFeed = $this->database()->select('*')
            ->from(Phpfox::getT('feed'))
            ->where('feed_id=' . (int) $iFeedId)
            ->execute('getRow');*/   
            $typeFeed = $type;
            $feedID = $iFeedId; 
            if($typeFeed == 0){
                $typeFeed = 0;
            }
            $aFeed = $this->database()->select('*')
            ->from(Phpfox::getT('feed'))
            ->where('feed_id=' . (int) $iFeedId)
            ->execute('getRow');   
            $aInsert = array(
                'user_id_sb' => Phpfox::getUserId(),
                'feed_id' => $feedID,
                'comment_id' => 0,
                'type_id_sb' => $type,
                'app_id' => $aFeed['app_id'],
                'privacy' => $aFeed['privacy'],
                'privacy_comment' => $aFeed['privacy_comment'],
                'type_id' => $aFeed['type_id'],
                'user_id' => $aFeed['user_id'],
                'parent_user_id' => $aFeed['parent_user_id'],
                'item_id' => $aFeed['item_id'],
                'feed_reference' => $aFeed['feed_reference'],
                'parent_feed_id' => $aFeed['parent_feed_id'],
                'parent_module_id' => $aFeed['parent_module_id'],
                'time_update' => $aFeed['time_update'],        
            ); 

            $this->database()->insert(Phpfox::getT('strongbox_feed'), $aInsert);
            return TRUE;
        }

        public function makeStrongBoxIcon($feedId,$iComment) {
            /*$aFeed = $this->database()->select('*')
            ->from(Phpfox::getT('feed'))
            ->where('feed_id=' . (int) $iFeedId)
            ->execute('getRow');*/   
            $pieces = explode("_", $iComment);
            $Comment = $pieces[2]; 
/*            $aComment = $this->database()->select('*')
            ->from(Phpfox::getT('comment'))
            ->where('comment_id=' . (int) $Comment)
            ->execute('getRow');
            d($aComment) ;*/
            $aFeed = $this->database()->select('*')
            ->from(Phpfox::getT('feed'))
            ->where('feed_id='.$feedId)
            ->execute('getRow');   
            $aInsert = array(
                'user_id_sb' => Phpfox::getUserId(),
                'feed_id' => $aFeed['feed_id'],
                'comment_id' => $Comment,
                'type_id_sb' => 0,
                'app_id' => $aFeed['app_id'],
                'privacy' => $aFeed['privacy'],
                'privacy_comment' => $aFeed['privacy_comment'],
                'type_id' => $aFeed['type_id'],
                'user_id' => $aFeed['user_id'],
                'parent_user_id' => $aFeed['parent_user_id'],
                'item_id' => $aFeed['item_id'],
                'feed_reference' => $aFeed['feed_reference'],
                'parent_feed_id' => $aFeed['parent_feed_id'],
                'parent_module_id' => $aFeed['parent_module_id'],
                'time_update' => $aFeed['time_update'],        
            ); 

            $this->database()->insert(Phpfox::getT('strongbox_feed'), $aInsert);
            //save feed when click icon strongbox
            /*$iCnt = $this->database()->select('*')
            ->from(Phpfox::getT('strongbox_feed'))
            ->where('feed_id='. $aFeed['feed_id'].' AND type_id_sb=1 AND user_id_sb='.Phpfox::getUserId())
            ->execute('getSlaveField');
            if(!$iCnt)
            {
            $aInsertFeed = array(
            'user_id_sb' => Phpfox::getUserId(),
            'feed_id' => $aFeed['feed_id'],
            'type_id_sb' => 1,
            'app_id' => $aFeed['app_id'],
            'privacy' => $aFeed['privacy'],
            'privacy_comment' => $aFeed['privacy_comment'],
            'type_id' => $aFeed['type_id'],
            'user_id' => $aFeed['user_id'],
            'parent_user_id' => $aFeed['parent_user_id'],
            'item_id' => $aFeed['item_id'],
            'feed_reference' => $aFeed['feed_reference'],
            'parent_feed_id' => $aFeed['parent_feed_id'],
            'parent_module_id' => $aFeed['parent_module_id'],
            'time_update' => $aFeed['time_update'],        
            ); 
            $this->database()->insert(Phpfox::getT('strongbox_feed'), $aInsertFeed);
            }*/
            return TRUE;
        }

        public function makePublicStrongBox($id,$type) {
            /* $aFeed = $this->database()->select('*')
            ->from(Phpfox::getT('strongbox_feed'))
            ->where('feed_id=' . (int) $iFeedId)
            ->execute('getRow');*/
            //$this->database()->insert(Phpfox::getT('feed'), $aFeed);
            $iCnt = $this->database()->select('*')
            ->from(Phpfox::getT('strongbox_feed'))
            ->where('feed_id=' . (int) $id.' AND user_id_sb='.Phpfox::getUserId().' AND type_id_sb='.(int)$type)
            ->execute('getSlaveField');
            $this->database()->delete(Phpfox::getT('strongbox_feed'), 'strongbox_id=' . (int) $iCnt);
            return TRUE;
        }

        public function makePublicStrongBoxIcon($id,$type) {
            $pieces = explode("_", $id);
            $idComment = $pieces[2];
            $iCnt = $this->database()->select('*')
            ->from(Phpfox::getT('strongbox_feed'))
            ->where('comment_id=' . (int) $idComment.' AND user_id_sb='.Phpfox::getUserId().' AND type_id_sb='.(int)$type)
            ->execute('getSlaveField');
            $this->database()->delete(Phpfox::getT('strongbox_feed'), 'strongbox_id=' . (int) $iCnt);
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

    }

?>
