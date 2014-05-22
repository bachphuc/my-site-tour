<?php

defined('PHPFOX') or exit('NO DICE!');

class Sitetour_Service_Check extends Phpfox_Service {

    public function canAdd() {
        if (!Phpfox::getParam('sitetour.enable_add_site_tour')) {
            $aTour = Phpfox::getService('sitetour')->getTourOnSite();
            if (isset($aTour) && isset($aTour['sitetour_id'])) {
            } else {
                return FALSE;
            }
        }
        if (Phpfox::isAdminPanel()) {
            return FALSE;
        }
        if (Phpfox::isAdmin()) {
            return TRUE;
        }
        return FALSE;
    }

    public function addPhrase($sText) {
        $sVarName = Phpfox::getService('language.phrase.process')->prepare($sText);
        $aLanguages = Phpfox::getService('language')->get();
        $aText = array();
        foreach ($aLanguages as $aLanguage) {
            $aText[$aLanguage['language_id']] = $sText;
        }
        $aVals = array(
            'product_id' => 'sitetour',
            'module' => 'sitetour|sitetour',
            'var_name' => $sVarName,
            'text' => $aText,
        );
        $iNumber = 1;
        do {
            $bVarNameOk = Phpfox::getService('language.phrase')->isPhrase($aVals);
            if ($bVarNameOk) {
                $aVals['var_name'] = $sVarName . '_' . $iNumber;
                $iNumber++;
            }
        } while ($bVarNameOk);
        $sPhrase = Phpfox::getService('language.phrase.process')->add($aVals);
        return $sPhrase;
    }

}
