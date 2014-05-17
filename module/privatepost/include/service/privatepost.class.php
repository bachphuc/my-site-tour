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
class PrivatePost_Service_PrivatePost extends Phpfox_Service {

    public function makePrivate($iFeedId) {
        $aFeed = $this->database()->select('*')
                ->from(Phpfox::getT('feed'))
                ->where('feed_id=' . (int) $iFeedId)
                ->execute('getRow');
        $this->database()->insert(Phpfox::getT('private_feed'), $aFeed);
        $this->database()->delete(Phpfox::getT('feed'), 'feed_id=' . (int) $iFeedId);
        return TRUE;
        
    }

    public function makePublic($iFeedId) {
        $aFeed = $this->database()->select('*')
                ->from(Phpfox::getT('private_feed'))
                ->where('feed_id=' . (int) $iFeedId)
                ->execute('getRow');
        $this->database()->insert(Phpfox::getT('feed'), $aFeed);
        $this->database()->delete(Phpfox::getT('private_feed'), 'feed_id=' . (int) $iFeedId);
        return TRUE;
    }
    public function isPrivate($iFeedId){
        $iCnt = $this->database()->select('COUNT(*)')
                ->from(Phpfox::getT('private_feed'))
                ->where('feed_id=' . (int) $iFeedId)
                ->execute('getSlaveField');
        if (isset($iCnt) && $iCnt){
            return TRUE;
        } else {
            return FALSE;
        }
    }

}

?>
