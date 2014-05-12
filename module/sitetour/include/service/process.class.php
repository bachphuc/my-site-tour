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
        public function addTour($sTitle,$aData,$sUrl,$bIsAutorun)
        {
            $aInsert = array(
                'title' => $sTitle,
                'url' => $sUrl,
                'time_stamp' => PHPFOX_TIME,
                'is_autorun' => $bIsAutorun
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
                'time_stamp' => PHPFOX_TIME,
                'duration' => $oStep->duration,
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

        public function blockTour($iTourId)
        {
            return $this->database()->insert(Phpfox::getT('sitetour_user_block'),array(
                'user_id' => Phpfox::getUserId(),
                'sitetour_id' => $iTourId,
                'time_stamp' => PHPFOX_TIME
            ));
        }

        public function updateActivity($iId, $iType, $iSub)
        {
            Phpfox::isUser(true);
            Phpfox::getUserParam('admincp.has_admin_access', true);        

            $this->database()->update(Phpfox::getT('sitetour_step'), array('is_active' => (int) ($iType == '1' ? 1 : 0)), 'step_id = ' . (int) $iId);

        }

        public function deleteTourOrStep($iId, $bIsStep = false)
        {
            if ($bIsStep)
            {
                $this->database()->delete(Phpfox::getT('sitetour_step'), 'step_id = ' . (int) $iId);
            }
            else
            {
                $this->database()->delete(Phpfox::getT('sitetour'), 'sitetour_id = ' . (int) $iId);
                $this->database()->delete(Phpfox::getT('sitetour_step'), 'sitetour_id = ' . (int) $iId);
            }
            return true;
        }
        
        public function updateStep($iId, $aVals)
        {
            return $this->database()->update(Phpfox::getT('sitetour_step'),$aVals,'step_id='.(int)$iId);
        }
        
        public function updateSitetour($iId, $aVals)
        {
            return $this->database()->update(Phpfox::getT('sitetour'),$aVals,'sitetour_id='.(int)$iId);
        }
    }
?>
