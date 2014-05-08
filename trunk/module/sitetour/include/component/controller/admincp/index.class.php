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
		if (($iId = $this->request()->getInt('sub')))
		{
			$bStep = true;
			if (($iDelete = $this->request()->getInt('delete')))
			{
				if (Phpfox::getService('pages.process')->deleteCategory($iDelete, true))
				{
					$this->url()->send('admincp.pages', array('sub' => $iId), Phpfox::getPhrase('pages.successfully_deleted_the_category'));
				}
			}
		}
		else
		{
			if (($iDelete = $this->request()->getInt('delete')))
			{
				if (Phpfox::getService('pages.process')->deleteCategory($iDelete))
				{
					$this->url()->send('admincp.pages', null, Phpfox::getPhrase('pages.successfully_deleted_the_category'));
				}
			}			
		}
		
		$this->template()->setTitle(($bStep ?  Phpfox::getPhrase('pages.manage_sub_categories') : Phpfox::getPhrase('sitetour.manate_tours')))
			->setBreadcrumb(($bStep ?  Phpfox::getPhrase('pages.manage_sub_categories') : Phpfox::getPhrase('sitetour.manate_tours')))
			->setHeader(array(
					'drag.js' => 'static_script',
					'<script type="text/javascript">$Behavior.coreDragInit = function() { Core_drag.init({table: \'#js_drag_drop\', ajax: \'' . ($bStep ? 'pages.categorySubOrdering' : 'pages.categoryOrdering' ) . '\'}); }</script>'
				)
			)
            ->assign(array(
                'bStep' => $bStep,
            ));
            if($bStep)
            {
                $this->template()->assign(array(
                    'aSteps' => Phpfox::getService('sitetour')->getStepOfTour($iId),
                ));
            }
            else
            {
                $this->template()->assign(array(
                    'aTours' => Phpfox::getService('sitetour')->getAllTours(),
                ));
            }
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('pages.component_controller_admincp_index_clean')) ? eval($sPlugin) : false);
	}
}

?>