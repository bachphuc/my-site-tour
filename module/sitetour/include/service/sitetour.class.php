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
class Sitetour_Service_Sitetour extends Phpfox_Service {

    public function getTourOnSite($sUrl = '') {
        if ($sUrl == '') {
            $sUrl = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        }
        $aUser = Phpfox::getService('user')->get(Phpfox::getUserId());
        $aResult = $this->database()->select('st.*')
                ->from(Phpfox::getT('sitetour'), 'st')
                ->where("is_active=1 AND url LIKE '%" . $sUrl . "' AND (user_group_id = ".$aUser['user_group_id']." OR user_group_id = 0)")
                ->order('sitetour_id DESC')
                ->limit(1)
                ->execute('getRow');
        if (isset($aResult) && isset($aResult['title'])) {
            $aResult['title'] = Phpfox::getPhrase($aResult['title']); 
        }
        return $aResult;
    }

    public function getStepOfTour($iTourId, $bPlay = true) {
        $sCondition = '';
        if ($bPlay) {
            $sCondition = 'is_active=1 AND ';
        }
        $aReturns = $this->database()->select('*')
                ->from(Phpfox::getT('sitetour_step'))
                ->where($sCondition . 'sitetour_id=' . (int) $iTourId)
                ->order('ordering')
                ->execute('getRows');
        foreach ($aReturns as $iKey => $aReturn) {
            $aReturns[$iKey]['title'] = Phpfox::getPhrase($aReturn['title']);
            $aReturns[$iKey]['content'] = Phpfox::getPhrase($aReturn['content']);
        }
        return $aReturns;
    }

    public function getAllTours() {
        $aReturns = $this->database()->select('sr.*,count(srs.step_id) AS total_step')
                ->from(Phpfox::getT('sitetour'), 'sr')
                ->join(Phpfox::getT('sitetour_step'), 'srs', 'srs.sitetour_id=sr.sitetour_id')
                ->group('sr.sitetour_id')
                ->order('url DESC')
                ->execute('getRows');
        foreach ($aReturns as $iKey => $aReturn) {
            $aReturns[$iKey]['title'] = Phpfox::getPhrase($aReturn['title']);
        }
        return $aReturns;
    }

    public function getTour($iId) {
        $aReturn = $this->database()->select('*')
                ->from(Phpfox::getT('sitetour'), 's')
                ->where('sitetour_id=' . (int) $iId)
                ->execute('getRow');
        $aReturn['title'] = Phpfox::getPhrase($aReturn['title']);
        return $aReturn;
    }

    public function getStep($iId) {
        $aStep = $this->database()->select('*')
                ->from(Phpfox::getT('sitetour_step'), 's')
                ->where('step_id=' . (int) $iId)
                ->execute('getRow');
        $aStep['title'] = str_replace('sitetour.', '', $aStep['title']);
        $aStep['content'] = str_replace('sitetour.', '', $aStep['content']);
        return $aStep;
    }

    public function checkBlockTour($iSitetourId) {
        $aTours = $this->database()->select('*')
                ->from(Phpfox::getT('sitetour_user_block'))
                ->where('user_id=' . Phpfox::getUserId() . ' AND sitetour_id=' . (int) $iSitetourId)
                ->execute('getRows');
        if (count($aTours) > 0) {
            return true;
        } else {
            return false;
        }
    }

}

?>
