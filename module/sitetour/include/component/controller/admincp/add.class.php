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
class Sitetour_Component_Controller_Admincp_Add extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		$bIsEdit = false;
		$bIsStep = false;
		if (($iEditId = $this->request()->getInt('id')))
		{
			$aRow = Phpfox::getService('sitetour')->getTour($iEditId);
			$bIsEdit = true;
			$this->template()->assign(array(			
					'aForms' => $aRow,
					'iEditId' => $iEditId
				)
			);
		}
		
		if (($iSubtEditId = $this->request()->getInt('step')))
		{
			$aRow = Phpfox::getService('sitetour')->getStep($iSubtEditId);
			$iEditId = $iSubtEditId;
			$bIsEdit = true;
			$bIsStep = true;
                        $sLink = Phpfox::getLib('url')->makeUrl('admincp.language.phrase');
			$this->template()->assign(array(			
					'aForms' => $aRow,
					'iEditId' => $iEditId,
					'sLinkEdit' => $sLink,
				)
			);
		}		

		if (($aVals = $this->request()->getArray('val')))
		{
			if ($bIsEdit)
			{
                if ($bIsStep)
                {
                    if (Phpfox::getService('sitetour.process')->updateStep($iEditId, $aVals))
                    {
                        $this->url()->send('admincp.sitetour', array('tour' => $this->request()->get('tour')), Phpfox::getPhrase('sitetour.update_step_succesfully'));
                    }
                }
                else
                {
                    if (Phpfox::getService('sitetour.process')->updateSitetour($iEditId, $aVals))
                    {
                        $this->url()->send('admincp.sitetour', null, Phpfox::getPhrase('sitetour.update_sitetour_sucessfully'));
                    }
                }			
			}
		}
		
		$this->template()->setTitle(Phpfox::getPhrase('sitetour.edit_sitetour'))
			->setBreadcrumb(Phpfox::getPhrase('sitetour.edit_sitetour'))
			->assign(array(
				'bIsEdit' => $bIsEdit,
			)
		)		
			->setHeader(array(
				'add.js' => 'module_sitetour'
			));
            
        if(!$bIsEdit)
        {
            $this->url()->send('admincp.sitetour');
        }
	}
}

?>