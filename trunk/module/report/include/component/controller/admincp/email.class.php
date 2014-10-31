<?php
    /**
    * [PHPFOX_HEADER]
    */

    defined('PHPFOX') or exit('NO DICE!');

    /**
    * 
    * 
    * @copyright        [PHPFOX_COPYRIGHT]
    * @author          phuclb
    * @package         Phpfox_Component
    */
    class Report_Component_Controller_Admincp_Email extends Phpfox_Component
    {
        /**
        * Class process method wnich is used to execute this component.
        */
        public function process()
        {
            if (($aIds = $this->request()->getArray('id')))
            {
                foreach ($aIds as $iId)
                {
                    if (!is_numeric($iId))
                    {
                        continue;
                    }

                    Phpfox::getService('report.email')->delete($iId);
                }

                $this->url()->send('admincp.report.email', null, 'Delete email template successful.');
            }

            $this->template()->setTitle('Manage Emails Template')
            ->setBreadcrumb('Manage Emails Template', $this->url()->makeUrl('admincp.report'))
            ->assign(array(
                'aEmails' => Phpfox::getService('report.email')->getEmailsTemplate()
                )
            );
        }
    }

?>