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
 * @package          Module_Sitetour
 * @version         $Id: index.class.php 1321 2009-12-15 18:19:30Z Raymond_Benc $
 */
class Sitetour_Component_Block_AddTour extends Phpfox_Component {

    public function process() {
        $sAddTourPosition  =  Phpfox::getParam('sitetour.add_new_tour_block_position');
        if(!empty($sAddTourPosition))
        {
            $aAddTourPosition =  json_decode($sAddTourPosition,true);
            if($aAddTourPosition)
            {
                $this->template()->assign(array(
                    'aAddTourPosition' => $aAddTourPosition
                ));
            }
        }
        $sPlayTourPosition  =  Phpfox::getParam('sitetour.play_tour_button_play_position');
        if(!empty($sPlayTourPosition))
        {
            $aPlayTourPosition =  json_decode($sPlayTourPosition,true);
            if($aPlayTourPosition)
            {
                $this->template()->assign(array(
                    'aPlayTourPosition' => $aPlayTourPosition
                ));
            }
        }
        $this->template()->assign(array(
            'bCanAdd' => Phpfox::getService('sitetour.check')->canAdd()
        ));
        $aTour = Phpfox::getService('sitetour')->getTourOnSite();
        if (isset($aTour) && isset($aTour['sitetour_id'])) {
            $bCheckBlockTour = Phpfox::getService('sitetour')->checkBlockTour($aTour['sitetour_id']);
            if ($bCheckBlockTour) {
                $this->template()->assign(array(
                    'bCheckBlockTour' => true
                ));
                return false;
            }
            $aUser = Phpfox::getService('user')->get(Phpfox::getUserId());
            if ($aTour['user_group_id'] != $aUser['user_group_id'] && $aTour['user_group_id'] != 0) {
                return false;
            }
            $aSteps = Phpfox::getService('sitetour')->getStepOfTour($aTour['sitetour_id']);
            if (Phpfox::getParam('sitetour.show_step_dont_show_again')) {
                $aLastStep = end($aSteps);
                $aConfirmStep = array(
                    'title' => "Sitetour",
                    'content' => '<input onclick="$.ajaxCall(\'sitetour.blockTour\',\'id=' . $aTour['sitetour_id'] . '\');" class="cb_dont_show_tour" type="checkbox"> Don\'t show tour in next time.',
                    'element' => $aLastStep['element'],
                    'placement' => 'auto',
                    'animate' => true,
                    'duration' => false,
                    'confirm_step' => true
                );
                $aSteps[] = $aConfirmStep;
            }
            $this->template()->assign(array(
                'aTour' => $aTour,
                'aSteps' => $aSteps,
            ));
        }
        $this->template()->assign(array(
            'bBackDrop' => Phpfox::getParam('sitetour.show_backdrop'),
            'bShowStep' => Phpfox::getParam('sitetour.show_step_number_when_play'),
        ));
    }

}

?>
