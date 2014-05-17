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
    class PrivatePost_Service_Process extends Phpfox_Service 
    {
        public function getLastFeedId()
        {
            $iFeedId = $this->database()->select('MAX(feed_id) AS last_feed_id')
            ->from(Phpfox::getT('feed'))
            ->execute('getSlaveField');
            return $iFeedId;
        }
    }
?>
