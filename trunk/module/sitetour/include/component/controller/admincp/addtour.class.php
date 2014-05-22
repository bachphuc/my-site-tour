<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

class Sitetour_Component_Controller_Admincp_Addtour extends Phpfox_Component {

    /**
     * Class process method wnich is used to execute this component.
     */
    public function process() {
        $aValidation = array(
            'name' => array(
                'def' => 'required',
                'title' => Phpfox::getPhrase('sitetour.please_add_tour_name')
            ),
            'url' => array(
                'def' => 'required',
                'title' => Phpfox::getPhrase('sitetour.please_enter_url')
            ),
        );
        $oValid = Phpfox::getLib('validator')->set(array(
            'sFormName' => 'core_js_blog_form',
            'aParams' => $aValidation
                )
        );
        $aGroups = Phpfox::getService('user.group')->getForEdit();
        $aGroups = array_merge($aGroups['special'], $aGroups['custom']);
        if ($aVals = $this->request()->getArray('val')) {
            if ($oValid->isValid($aVals)) {
                $sTitle = $aVals['name'];
                $aData = array();
                $sUrl = $aVals['url'];
                $bIsAutorun = $aVals['is_auto'];
                $iUserGroupId = $aVals['user_group'];
                $iId = Phpfox::getService('sitetour.process')->addTour($sTitle, $aData, $sUrl, $bIsAutorun, $iUserGroupId);
                //Set session for display block add tag
                $_SESSION[base64_encode('npfox.com')] = TRUE;
                Phpfox::getLib('url')->send('admincp.sitetour.addstep.id_' . $iId, NULL, Phpfox::getPhrase('sitetour.please_add_new_step'));
            }
        }
        $this->template()->setBreadcrumb(Phpfox::getPhrase('sitetour.add_new_tour_backend'), $this->url()->makeUrl('current'))
                ->setTitle(Phpfox::getPhrase('sitetour.add_new_tour_backend'))
                ->assign(array(
                    'aGroups' => $aGroups,
                    'sCreateJs' => $oValid->createJS(),
                    'sGetJsForm' => $oValid->getJsForm(),
        ));
    }

}

?>
