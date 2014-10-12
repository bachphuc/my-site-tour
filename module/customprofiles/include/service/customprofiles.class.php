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
    * @package          Module_Event
    * @version         $Id: event.class.php 6139 2013-06-24 15:02:48Z Raymond_Benc $
    */
    class CustomProfiles_Service_CustomProfiles extends Phpfox_Service 
    {
        public function getAnonymousFeed($iFeedId)
        {
            return $this->database()->select('*')
            ->from(Phpfox::getT('custom_profiles_anonymous_feed'),'af')
            ->where('af.feed_id='.(int)$iFeedId)
            ->execute('getRow');
        }
        
        public function getAnonymousFeedById($iAnonymousId)
        {
            return $this->database()->select('*')
            ->from(Phpfox::getT('custom_profiles_anonymous_feed'),'af')
            ->where('af.anonymous_id='.(int)$iAnonymousId)
            ->execute('getRow');
        }

        public function checkEmail($sEmail)
        {
            $aUser = $this->database()->select('*')
            ->from(Phpfox::getT('user'),'u')
            ->where("u.email='".$sEmail."'")
            ->execute('getRow');
            if(isset($aUser['user_id']))
            {
                return $aUser;
            }
            return false;
        }

        public function getFromCache()
        {
            $aRows = $this->database()->select('f.*, ' . Phpfox::getUserField())
            ->from(Phpfox::getT('friend'), 'f')
            ->join(Phpfox::getT('user'), 'u', 'u.user_id = f.friend_user_id')
            ->where('f.is_page = 0 AND f.user_id = ' . Phpfox::getUserId().' AND f.friend_user_id NOT IN (SELECT cb.user_id FROM '.Phpfox::getT('custom_profiles_block').' AS cb WHERE cb.block_user_id = '.Phpfox::getUserId().')')
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

        public function getInviteAnonymousMessage($iId)
        {
            return $this->database()->select('*')
            ->from(Phpfox::getT('custom_profiles_invite_anonymous_message'))
            ->where('invite_id='.(int)$iId)
            ->execute('getRow');
        }

        public function getInviteAnonymousMessageByFeedId($iFeedId)
        {
            return $this->database()->select('*')
            ->from(Phpfox::getT('custom_profiles_invite_anonymous_message'))
            ->where('feed_id='.(int)$iFeedId)
            ->execute('getRow');
        }
        // hau@gmail.com
        public function getUnseenTotal()
        { 
            $iCnt = $this->database()->select('COUNT(*)')
            ->from(Phpfox::getT('notification'), 'n')            
            ->where('n.user_id = ' . (int) Phpfox::getUserId() . ' AND n.is_seen = 0 AND time_stamp <='.PHPFOX_TIME)            
            ->execute('getSlaveField');          
            return $iCnt;
        }
        // end hau@gmail.com

        public function getActualFeedId($aFeed)
        {
            $sTypeId = '';
            if(isset($aFeed['type_id']))
            {
                $sTypeId = $aFeed['type_id'];
            }
            else
            {
                $sTypeId = $aFeed['comment_type_id'];
            }
            $iItemId = $aFeed['item_id'];
            $aParentFeed = $this->database()->select('*')
            ->from(Phpfox::getT('feed'))
            ->where("item_id=".(int)$iItemId." AND type_id='".$sTypeId."'")
            ->execute('getRow');
            if(!isset($aParentFeed['feed_id']))
            {
                return false;
            }
            return $aParentFeed['feed_id'];
        }
        
        public function getScheduleFeed($iFeedId)
        {
            return $this->database()->select('*')
            ->from(Phpfox::getT('custom_profiles_schedule_feed'))
            ->where('feed_id='.(int)$iFeedId)
            ->execute('getRow');
        }
        
        public function setHeaders()
        {
            Phpfox::getLib('template')->setHeader(array(
                'comment.js' => 'module_customprofiles'
            ));
        }
        
        public function getFeed($iFeedId)
        {
            return $this->database()->select('*')
            ->from(Phpfox::getT('feed'))
            ->where('feed_id='.(int)$iFeedId)
            ->execute('getRow');
        }
        
        public function getFeedItem($iItemId, $sType)
        {
            return $this->database()->select('*')
            ->from(Phpfox::getT('feed'))
            ->where('item_id='.(int)$iItemId." AND type_id='".$sType."'")
            ->execute('getRow');
        }
        
        public function checkBlockUser($iUserId)
        {
            $aRow = $this->database()->select('*')
            ->from(Phpfox::getT('custom_profiles_block'))
            ->where('user_id = '.Phpfox::getUserId().' AND block_user_id = '.(int)$iUserId)
            ->execute('getRow');
            if(isset($aRow['block_id']))
            {
                return true;
            }
            return false;
        }
    }
?>