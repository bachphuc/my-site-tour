<?php
    /**
    * [PHPFOX_HEADER]
    */

    defined('PHPFOX') or exit('NO DICE!');

    /**
    *
    *
    * @copyright        [PHPFOX_COPYRIGHT]
    * @author          Raymond Benc
    * @package          Module_User
    * @version         $Id: ajax.class.php 6792 2013-10-16 10:19:49Z Fern $
    */
    class Waytime_Component_Ajax_Ajax extends Phpfox_Ajax
    {
        public function showFirst()
        {
            $this->setTitle(Phpfox::getPhrase('waytime.w_time_capsule'));   
            Phpfox::getComponent('waytime.first',null,'block');
        }

        public function showNext()
        {
            $this->setTitle(Phpfox::getPhrase('waytime.w_time_capsule'));    
            Phpfox::getComponent('waytime.next', null , 'block');
        }

        public function showLast()
        {
            $this->setTitle(Phpfox::getPhrase('waytime.w_time_capsule'));   
            Phpfox::getComponent('waytime.last', null , 'block');
        }

        public function questionOrdering()
        {
            $aVals = $this->get('val');
            Phpfox::getService('core.process')->updateOrdering(array(
                'table' => 'waytime_question',
                'key' => 'question_id',
                'values' => $aVals['ordering']
                )
            );    
        }
        
        public function start()
        {
            $aProfile = Phpfox::getService('waytime')->getProfile();
            if(!isset($aProfile['profile_id']))
            {
                return $this->alert('Can not load your question.');
            }
            if($aProfile['is_unlock'])
            {
                return $this->alert('You have completed all question.');
            }
            else if($aProfile['is_complete'])
            {
                 // Show popup to unlock
                 $this->showLast();
            }
            else if(!$aProfile['current'])
            {
                 // Show first popup
                 $this->showFirst();
                 echo '<script type="text/javascript">';
                 echo '$(".js_box").addClass("waytame_box_green");';
                 echo '</script>';
            }
            else
            {
                // Show current question
                $this->set('index',$aProfile['current'] + 1);
                Phpfox::getLib('request')->set('index' , $aProfile['current'] +1);
                $this->showNext();
            }
        }
        
        public function saveAnswer()
        {
            $aProfile = Phpfox::getService('waytime')->getProfile();
            if(!isset($aProfile['profile_id']))
            {
                return $this->alert('Can not load your question.');
            }
            $iQuestionId = $this->get('question_id');
            if(!is_numeric($iQuestionId))
            {
                return false;
            }
            $iAnswerId = $this->get('answer_id');
            if(!is_numeric($iAnswerId))
            {
                return false;
            }
            
            $sNote = $this->get('note');
            if(Phpfox::getService('waytime.process')->answerQuestion($aProfile['profile_id'], $iQuestionId, $iAnswerId, $sNote))
            {
                $iTotalQuestion = Phpfox::getService('waytime')->getTotalQuestion();
                $iTotalAnswer = Phpfox::getService('waytime')->getTotalAnswer($aProfile['profile_id']);
                
                $aUpdate = array(
                    'current' => $iTotalAnswer,
                    'is_complete' => ($iTotalAnswer < $iTotalQuestion ? 0 : 1)
                );
                Phpfox::getService('waytime.process')->updateProfile($aUpdate, $aProfile['profile_id']);
                return;
            }
        }
        
        public function remember()
        {
            $aProfile = Phpfox::getService('waytime')->getProfile();
        }
    }
?>
