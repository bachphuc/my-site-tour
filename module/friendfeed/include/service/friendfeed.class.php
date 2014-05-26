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
    class FriendFeed_Service_FriendFeed extends Phpfox_Service
    {
        public function getFriend()
        {
            $aRows = $this->database()->select('f.*, ' . Phpfox::getUserField())
            ->from(Phpfox::getT('friend'), 'f')
            ->join(Phpfox::getT('user'), 'u', 'u.user_id = f.friend_user_id')
            ->where('f.is_page = 0 AND f.user_id = ' . Phpfox::getUserId())
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
    }
?>
