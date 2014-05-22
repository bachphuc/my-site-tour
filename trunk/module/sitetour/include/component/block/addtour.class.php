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
        /*if (!Phpfox::getService('sitetour.check')->canAdd()){
            return FALSE;
        }*/
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
                    'duration' => false
                );
                $aSteps[] = $aConfirmStep;
            }
            $this->template()->assign(array(
                'aTour' => $aTour,
                'aSteps' => $aSteps,
                'bBackDrop' => Phpfox::getParam('sitetour.show_backdrop'),
                'bAutoTransitionStep' => (Phpfox::getParam('sitetour.auto_transition_step') ? Phpfox::getParam('sitetour.time_duration_step') : false),
                'bAutoPlayTour' => Phpfox::getParam('sitetour.auto_play_tour'),
                'bShowStep' => Phpfox::getParam('sitetour.show_step_number_when_play'),
            ));
        }
    }

}

?>
