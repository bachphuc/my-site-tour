<?php
    /**
    * [PHPFOX_HEADER]
    */

    defined('PHPFOX') or exit('NO DICE!');

    class Report_Component_Controller_Admincp_AddEmail extends Phpfox_Component
    {
        public function process()
        {
            $bIsEdit = false;
            if (($iId = $this->request()->getInt('id')))
            {
                if ($aEmail = Phpfox::getService('report.email')->getForEdit($iId))
                {
                    $bIsEdit = true;

                    $this->template()->assign('aForms', $aEmail);
                }
            }

            if (($aVals = $this->request()->getArray('val')))
            {
                if ($bIsEdit)
                {
                    if (Phpfox::getService('report.email')->update($aEmail['template_id'], $aVals))
                    {
                        $this->url()->send('admincp.report.addemail', array('id' => $aEmail['template_id']), 'Update email template successful.');
                    }                
                }
                else 
                {
                    if (Phpfox::getService('report.email')->add($aVals))
                    {
                        $this->url()->send('admincp.report.addemail', null, 'Add email template successful.');
                    }
                }
            }

            $this->template()->setTitle(($bIsEdit === true ? 'Edit email template' : 'Add new email template'))
            ->setBreadcrumb(($bIsEdit === true ? 'Edit email template' : 'Add new email template'), $this->url()->makeUrl('admincp.report'))
            ->assign(array(
                'bIsEdit' => $bIsEdit
                )
            );
        }
    }

?>