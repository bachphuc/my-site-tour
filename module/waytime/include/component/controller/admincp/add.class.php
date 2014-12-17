<?php
    /**
    * [PHPFOX_HEADER]
    */

    defined('PHPFOX') or exit('NO DICE!');

    /**
    * 
    * 
    * @copyright		[PHPFOX_COPYRIGHT]
    * @author  		Raymond_Benc
    * @package 		Phpfox_Component
    * @version 		$Id: add.class.php 3402 2011-11-01 09:07:31Z Miguel_Espinoza $
    */
    class Waytime_Component_Controller_Admincp_Add extends Phpfox_Component
    {
        /**
        * Class process method wnich is used to execute this component.
        */
        public function process()
        {
            $bIsEdit = false;
            if($iEditId = $this->request()->get('id'))
            {
                $bIsEdit = true;
                $aRow = Phpfox::getService('waytime')->getQuestion($iEditId);
                $this->template()->assign(array(
                    'bIsEdit' => true,
                    'iEditId' => $iEditId,
                    'aForms' => $aRow
                ));
            }
            if($aVals = $this->request()->get('val'))
            {
                if($bIsEdit)
                {
                    if(Phpfox::getService('waytime.process')->updateQuestion($aVals, $iEditId))
                    {
                        return $this->url()->send('admincp.waytime', null, 'Update question successfully.');
                    }
                }
                else
                {
                    if(Phpfox::getService('waytime.process')->addQuestion($aVals))
                    {
                        return $this->url()->send('admincp.waytime.add', null, 'Add question successfully.');
                    }
                }
            }
            $this->template()->setTitle(($bIsEdit ? 'Edit Question' : 'Add New Question'))
            ->setBreadcrumb(($bIsEdit ? 'Edit Question' : 'Add New Question'));
        }
    }

?>