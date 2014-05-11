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
 * @version 		$Id: index.class.php 6113 2013-06-21 13:58:40Z Raymond_Benc $
 */
class Sitetour_Component_Controller_Admincp_Index extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{		
		$bStep = false;
		if (($iId = $this->request()->getInt('tour')))
		{
			$bStep = true;
			if (($iDelete = $this->request()->getInt('delete')))
			{
                $aTour = Phpfox::getService('sitetour')->getStep($iDelete);
				if (Phpfox::getService('sitetour.process')->deleteTourOrStep($iDelete, true))
				{
					$this->url()->send('admincp.sitetour', array('tour' => $aTour['sitetour_id']), Phpfox::getPhrase('sitetour.delete_step_succesfully'));
				}
			}
		}
		else
		{
			if (($iDelete = $this->request()->getInt('delete')))
			{
                $aTour = Phpfox::getService('sitetour')->getTour($iDelete);
				if (Phpfox::getService('sitetour.process')->deleteTourOrStep($iDelete))
				{
					$this->url()->send('admincp.sitetour', null, Phpfox::getPhrase('sitetour.delete_site_successfully'));
				}
			}			
		}
		
		$this->template()->setTitle(($bStep ?  Phpfox::getPhrase('sitetour.manage_sitetour_step') : Phpfox::getPhrase('sitetour.manate_tours')))
			->setBreadcrumb(($bStep ?  Phpfox::getPhrase('sitetour.manage_sitetour_step') : Phpfox::getPhrase('sitetour.manate_tours')))
			->setHeader(array(
					'drag.js' => 'static_script',
					'<script type="text/javascript">$Behavior.coreDragInit = function() { Core_drag.init({table: \'#js_drag_drop\', ajax: \'' . ($bStep ? 'sitetour.categorySubOrdering' : 'sitetour.categoryOrdering' ) . '\'}); }</script>'
				)
			)
            ->assign(array(
                'bStep' => $bStep,
            ));
            if($bStep)
            {
                $this->template()->assign(array(
                    'aSteps' => Phpfox::getService('sitetour')->getStepOfTour($iId,false),
                ));
            }
            else
            {
                $this->template()->assign(array(
                    'aTours' => Phpfox::getService('sitetour')->getAllTours(),
                ));
            }
	}
}

?>