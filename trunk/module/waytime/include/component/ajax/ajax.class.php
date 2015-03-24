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
            echo '<script type="text/javascript">';
            echo '$(".js_box").addClass("waytame_box_green");';
            echo '</script>';
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
            echo '<script type="text/javascript">';
            echo '$(".js_box").addClass("waytame_box_green");';
            echo '</script>';
        }

        public function showUnlock()
        {
            $this->setTitle(Phpfox::getPhrase('waytime.w_time_capsule'));   
            Phpfox::getComponent('waytime.unlock', null , 'block');
            echo '<script type="text/javascript">';
            echo '$(".js_box").addClass("waytame_box_green");';
            echo '</script>';
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
            Phpfox::isUser(true);
            $aProfile = Phpfox::getService('waytime')->getProfile();
            if(!isset($aProfile['profile_id']))
            {
                return $this->alert('Can not load your question.');
            }
            if($aProfile['is_unlock'])
            {
                $this->showReview();
            }
            else if($aProfile['is_complete'] && !$aProfile['is_waiting'])
            {
                // Show popup to freeze
                $this->showLast();
            }
            else if($aProfile['is_waiting'])
            {
                // Show popup to unlock
                $this->showUnlock();
            }
            else if(!$aProfile['current'])
            {
                // Show first popup
                $this->showFirst();
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
            Phpfox::isUser(true);
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
                Phpfox::getService('waytime.process')->updateProfile($aProfile['profile_id']);

                $iTotal = Phpfox::getService('waytime')->getRemainQuestion();
                $sTitle = ($iTotal ? Phpfox::getPhrase('waytime.would_you_like_to_complete_the_total_remaining_questions', array('total' => $iTotal)) : Phpfox::getPhrase('waytime.would_you_like_to_freeze_w_time_capsule'));

                $this->call('$(".waytime_watch a span").html("'.$sTitle.'");');
                return;
            }
        }

        public function remember()
        {            
            Phpfox::isUser(true);
            Phpfox::getService('waytime.process')->remember();
        }

        public function freeze()
        {
            Phpfox::isUser(true);
            Phpfox::getService('waytime.process')->freeze();

            $aProfile = Phpfox::getService('waytime')->getProfile();
            $iTotal = $aProfile['remind_time'] - PHPFOX_TIME;
            $iTotal = (int)($iTotal / (30 * 24 * 60 *60));

            $sTitle = Phpfox::getPhrase('waytime.total_months_left_to_unfreeze_the_w_time_capsule', array('total' => $iTotal, 's' => ($iTotal > 1 ? 's' : '')));
            $this->call('$(".waytime_watch a span").html("'.$sTitle.'");');
            $this->call('$(".waytime_watch a").attr("onclick","return false;");');
            $this->call('$(".waytime_watch").attr("id","waytime_watch_freeze");');
        }

        public function unlock()
        {
            Phpfox::isUser(true);
            $aVals = $this->get('question');
            Phpfox::getService('waytime.process')->unlock($aVals);
            $this->call('$(".waytime_watch a span").html("");');
            $this->call('$(".waytime_watch a").attr("onclick","$Core.waytime.begin();return false;");');
        }
        
        public function processRunAjax()
        {
            Phpfox::getService('waytime.process')->processRunAjax();
        }
        
        public function showReview()
        {
            $this->setTitle(Phpfox::getPhrase('waytime.w_time_capsule'));   
            Phpfox::getComponent('waytime.review', null , 'block');
        }
    }
?>
