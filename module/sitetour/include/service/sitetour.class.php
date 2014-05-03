<?php
    /**
    * [PHPFOX_HEADER]
    */

    defined('PHPFOX') or exit('NO DICE!');

    /**
    * 
    * 
    * @copyright        [PHPFOX_COPYRIGHT]
    * @author          phuclb@ceofox.com
    * @package         Phpfox_Service
    * @version         $Id: sitetour.class.php 6889 2013-11-14 09:35:03Z Miguel_Espinoza $
    */
    class Sitetour_Service_Sitetour extends Phpfox_Service 
    {
        public function getTourOnSite($sUrl = '')
        {
            if($sUrl == '')
            {
                $sUrl = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
            }
            return $this->database()->select('*')
            ->from(Phpfox::getT('sitetour'),'st')
            ->where("is_active=1 AND url LIKE '%".$sUrl."%'")
            ->execute('getRow');
        }
        
        public function getStepOfTour($iTourId)
        {
            return $this->database()->select('*')
                ->from(Phpfox::getT('sitetour_step'))
                ->where('sitetour_id='.(int)$iTourId)
                ->execute('getRows');
        }
    }
?>
