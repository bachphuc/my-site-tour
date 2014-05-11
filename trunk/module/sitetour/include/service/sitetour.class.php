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
            $aResult = $this->database()->select('st.*')
            ->from(Phpfox::getT('sitetour'),'st')
            ->where("is_active=1 AND url LIKE '%".$sUrl."'")
            ->execute('getRow');
            return $aResult;
        }
        
        public function getStepOfTour($iTourId,$bPlay = true)
        {
            $sCondition = '';
            if($bPlay)
            {
                $sCondition = 'is_active=1 AND ';
            }
            return $this->database()->select('*')
                ->from(Phpfox::getT('sitetour_step'))
                ->where($sCondition.'sitetour_id='.(int)$iTourId)
                ->order('ordering')
                ->execute('getRows');
        }
        
        public function getAllTours()
        {
            return $this->database()->select('sr.*,count(srs.step_id) AS total_step')
                ->from(Phpfox::getT('sitetour'),'sr')
                ->join(Phpfox::getT('sitetour_step'),'srs', 'srs.sitetour_id=sr.sitetour_id')
                ->group('sr.sitetour_id')
                ->execute('getRows');
        }
        
        public function getTour($iId)
        {
            return $this->database()->select('*')
            ->from(Phpfox::getT('sitetour'),'s')
            ->where('sitetour_id='.(int)$iId)
            ->execute('getRow');
        }
        
        public function getStep($iId)
        {
            return $this->database()->select('*')
            ->from(Phpfox::getT('sitetour_step'),'s')
            ->where('step_id='.(int)$iId)
            ->execute('getRow');
        }
        
        public function checkBlockTour($iSitetourId)
        {
            $aTours = $this->database()->select('*')
            ->from(Phpfox::getT('sitetour_user_block'))
            ->where('user_id='.Phpfox::getUserId().' AND sitetour_id='.(int)$iSitetourId)
            ->execute('getRows');
            if(count($aTours) > 0)
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
