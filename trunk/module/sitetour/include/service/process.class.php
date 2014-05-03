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
    class Sitetour_Service_Process extends Phpfox_Service 
    {
        public function addTour($sTitle,$aData,$sUrl)
        {
            $aInsert = array(
                'title' => $sTitle,
                'url' => $sUrl,
                'time_stamp' => PHPFOX_TIME
            );   
            $iId = $this->database()->insert(Phpfox::getT('sitetour'),$aInsert);
            if($iId)
            {
                $this->addSteps($iId,$aData);
                return $iId;
            }
            return false;
        }
        public function addStep($iTourId,$oStep)
        {
            $aInsert = array(
                'sitetour_id' => $iTourId,
                'title' => $oStep->title,
                'element' => $oStep->element,
                'content' => $oStep->content,
                'time_stamp' => PHPFOX_TIME
            );
            return $this->database()->insert(Phpfox::getT('sitetour_step'),$aInsert);
        }
        
        public function addSteps($iTourId, $aStep)
        {
            if(!is_array($aStep))
            {
                return false;
            }
            foreach($aStep as $key => $oStep)
            {
                $this->addStep($iTourId,$oStep);
            }
        }
    }
?>
