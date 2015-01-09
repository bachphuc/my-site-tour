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
    * @package         Phpfox_Ajax
    * @version         $Id: ajax.class.php 7092 2014-02-05 21:42:42Z Fern $
    */
    class Waytame_Component_Ajax_Ajax extends Phpfox_Ajax
    {
        public function addQuestion()
        {
            $this->setTitle(Phpfox::getPhrase('waytame.waytame_title'));
            Phpfox::getLib('template')
            ->assign(array(
                'iExpireTime' => PHPFOX_TIME + Phpfox::getParam('waytame.limit_time_question_expire')*24 * 60 * 60
            ))
            ->getTemplate('waytame.block.addquestion');
        }

        public function showQuestion()
        {
            $this->setTitle(Phpfox::getPhrase('waytame.waytame_title'));
            $aQuestions = Phpfox::getService('waytame')->getQuestions($this->get('user_id'),0,4,true);
            Phpfox::getLib('template')->assign(array(
                'aQuestions' => $aQuestions,
                'iCurrentNumber' => 0,
                'iTotalQuestion' => Phpfox::getService('waytame')->getTotalQuestion($this->get('user_id'))
            ))->getTemplate('waytame.block.showquestion');
        }

        public function showMoreQuestion()
        {
            $iPage = (int)$this->get('page');
            $iLimit = 4;
            $aQuestions = Phpfox::getService('waytame')->getQuestions($this->get('user_id'),$iPage,$iLimit,true);   
            if(count($aQuestions))
            {
                Phpfox::getLib('template')->assign(array(
                    'aQuestions' => $aQuestions,
                    'bLoadMore' => true,
                    'iPage' => $iPage + 1,
                    'iCurrentNumber' => $iPage * $iLimit
                ))->getTemplate('waytame.block.showquestion');

                $this->html('.waytame_question_panel',$this->getContent(false));
                $this->call('$(".waytame_show_more_question").attr("val",'.($iPage + 1).');');
                if($iPage == 0)
                {
                    $this->call('$(".waytame_show_less_question").hide();');
                }
                else
                {
                    $this->call('$(".waytame_show_less_question").show();');
                    $this->call('$(".waytame_show_less_question").attr("val",'.($iPage - 1).');');
                }
                
                if(count($aQuestions) < $iLimit)
                {
                    $this->call('$(".waytame_show_more_question").hide();');
                }
                else
                {
                    if(Phpfox::getService('waytame')->getTotalQuestion($this->get('user_id')) > $iPage * $iLimit + count($aQuestions))
                    {
                        $this->call('$(".waytame_show_more_question").show();');
                    }
                    else
                    {
                        $this->call('$(".waytame_show_more_question").hide();');
                    }
                }
            }
            else
            {
                $this->call('$(".waytame_show_more_question").hide();');
            }
        }

        public function answerQuestion()
        {
            $this->setTitle(Phpfox::getPhrase('waytame.waytame_title'));
            $iNumberQuestion = $this->get('number_question');
            if(Phpfox::getService('waytame')->checkIsAnswer($this->get('question_id')))
            {
                echo Phpfox::getPhrase('waytame.you_ve_actually_answered_this_question');
                echo '
                <div class="js_box_close">
                <a onclick="return js_box_remove(this);">CLOSE</a>
                </div>';
                echo '<script type="text/javascript">setTimeout(function(){tb_remove();},2000);</script>';
                return;
            }
            $aQuestion = Phpfox::getService('waytame')->getQuestion($this->get('question_id'));
            if($aQuestion['user_id'] == Phpfox::getUserId())
            {
                echo Phpfox::getPhrase('waytame.you_are_the_owner_of_this_question_so_that_you_do_not_answer_it');
                echo '
                <div class="js_box_close">
                <a onclick="return js_box_remove(this);">CLOSE</a>
                </div>';
                echo '<script type="text/javascript">setTimeout(function(){tb_remove();},2000);</script>';
                return;
            }
            Phpfox::getLib('template')->assign(array(
                'iNumberQuestion' => $iNumberQuestion,
                'aQuestion' => $aQuestion
            ))->getTemplate('waytame.block.answerquestion');
        }

        public function showAnswer()
        {
            $this->setTitle('WAYTAME');
            $aQuestion = Phpfox::getService('waytame')->getQuestion($this->get('question_id'));
            $aAnswers = Phpfox::getService('waytame')->getAnswers($this->get('question_id'));
            Phpfox::getLib('template')->assign(array(
                'aAnswers' => $aAnswers,
                'aQuestion' => $aQuestion,
                'iNumberQuestion' => $this->get('number_question'),
                'iTotalAnswer' => Phpfox::getService('waytame')->getTotalAnswer($this->get('question_id'))
            ))->getTemplate('waytame.block.showanswer');
        }

        public function showMoreAnswer()
        {
            $iPage = (int)$this->get('page');
            $iLimit = 3;
            $aAnswers = Phpfox::getService('waytame')->getAnswers($this->get('question_id'),$iPage,$iLimit,true);   
            if(count($aAnswers))
            {
                Phpfox::getLib('template')->assign(array(
                    'aAnswers' => $aAnswers,
                    'bLoadMore' => true,
                    'iPage' => $iPage + 1,
                ))->getTemplate('waytame.block.showanswer');

                $this->html('.waytame_answer_panel',$this->getContent(false));
                $this->call('$(".waytame_show_more_answer").attr("val",'.($iPage + 1).');');
                
                if($iPage == 0)
                {
                    $this->call('$(".waytame_show_less_answer").hide();');
                }
                else
                {
                    $this->call('$(".waytame_show_less_answer").show();');
                    $this->call('$(".waytame_show_less_answer").attr("val",'.($iPage - 1).');');
                }
                
                if(count($aAnswers) < $iLimit)
                {
                    $this->call('$(".waytame_show_more_answer").hide();');
                }
                else
                {
                    if(Phpfox::getService('waytame')->getTotalAnswer($this->get('question_id')) > $iPage * $iLimit + count($aAnswers))
                    {
                        $this->call('$(".waytame_show_more_answer").show();');
                    }
                    else
                    {
                        $this->call('$(".waytame_show_more_answer").hide();');
                    }
                }
            }
            else
            {
                $this->call('$(".waytame_show_more_answer").hide();');
            }
        }

        public function addQestionProcess()
        {
            Phpfox::isUser(true);
            $aVal = $this->get('val');
            if(!$aVal || !is_array($aVal) || empty($aVal))
            {
                return $this->alert(Phpfox::getPhrase('waytame.please_fill_in_form'));
            }
            if(empty($aVal['question']) || empty($aVal['owner_answer']))
            {
                return $this->alert(Phpfox::getPhrase('waytame.some_field_is_empty_please_fill_in_form'));
            }
            $aVal['user_id'] = Phpfox::getUserId();
            $aVal['time_stamp'] = PHPFOX_TIME;
            $iId = Phpfox::getService('waytame.process')->addQestion($aVal);
            if($iId)
            {
                return $this->call('$("#"+tb_get_active()+" .js_box_content").html("'.Phpfox::getPhrase('waytame.add_question_successful').'");setTimeout(function(){tb_remove();},2000);');
            }
            return $this->alert(Phpfox::getPhrase('waytame.something_was_wrong'));
        }

        public function answerQuestionProcess()
        {
            Phpfox::isUser(true);
            $aVal = $this->get('val');
            if(!$aVal || !is_array($aVal) || empty($aVal))
            {
                return $this->alert(Phpfox::getPhrase('waytame.some_fields_are_missed'));
            }
            if(empty($aVal['answer']))
            {
                return $this->alert(Phpfox::getPhrase('waytame.answer_must_be_not_empty'));
            }
            $aVal['user_id'] = Phpfox::getUserId();
            $aVal['time_stamp'] = PHPFOX_TIME;
            $aQuestion = Phpfox::getService('waytame')->getQuestion($aVal['question_id']);
            if($aQuestion['user_id'] == Phpfox::getUserId())
            {
                return $this->alert(Phpfox::getPhrase('waytame.you_are_the_owner_of_this_question_so_that_you_do_not_answer_it'));
            }
            $iId = Phpfox::getService('waytame.process')->addAnswer($aVal);
            if($iId)
            {
                if($this->get('is_view'))
                {
                    Phpfox::getLib('template')->assign(array(
                        'aAnswer' => Phpfox::getService('waytame')->getAnswer($iId),
                    ))->getTemplate('waytame.block.answer-entity');
                    $this->append('#answer_panel',$this->getContent(false));
                    $this->call('$(".js_comment_feed_textarea").val("");');
                    $this->call('$Core.loadInit();');
                    return;
                }
                else
                {
                    return $this->call('$("#"+tb_get_active()+" .js_box_content").html("'.Phpfox::getPhrase('waytame.answer_successful').'");setTimeout(function(){tb_remove();},2000);');
                }
            }
            if($this->get('is_view'))
            {
                $this->alert(Phpfox::getPhrase('waytame.you_ve_actually_answered_this_question'));
            }
            else
            {
                return $this->call('$("#"+tb_get_active()+" .js_box_content").html("'.Phpfox::getPhrase('waytame.you_ve_actually_answered_this_question').'");setTimeout(function(){tb_remove();},2000);');
            }
        }

        public function likeQuestion()
        {
            Phpfox::isUser(true);
            if (Phpfox::getService('like')->hasBeenMarked(2, $this->get('type_id'), $this->get('item_id')))
            {
                $sTypeId = $this->get('type_id');
                $sModuleId = $this->get('module_name');

                if (empty($sTypeId))
                {
                    $sTypeId = $this->get('like_type_id');
                }

                if (empty($sModuleId) && !empty($sTypeId))
                {
                    $this->set('module_name', $sTypeId);
                    $sModuleId = $sTypeId;
                }
                if (empty($sTypeId) && $this->get('item_type_id') != '')
                {
                    $this->set('type_id', $this->get('item_type_id'));
                    $sTypeId = $this->get('item_type_id');
                }

                Phpfox::getService('like.process')->removeAction( 2, $sTypeId, $this->get('item_id'), $sModuleId );
                $this->call('$("#waytame_item_question_'.$this->get('item_id').' .waytame_total_dislike").text(parseInt($("#waytame_item_question_'.$this->get('item_id').' .waytame_total_dislike").text()) - 1);');
            }
            if (Phpfox::getService('like.process')->add($this->get('type_id'), $this->get('item_id')))
            {
                $this->call('$("#waytame_item_question_'.$this->get('item_id').' .waytame_like").eq(0).hide();');
                $this->call('$("#waytame_item_question_'.$this->get('item_id').' .waytame_unlike").eq(0).show();');
                $this->call('$("#waytame_item_question_'.$this->get('item_id').' .waytame_dislike").eq(0).show();');
                $this->call('$("#waytame_item_question_'.$this->get('item_id').' .waytame_remove_dislike").eq(0).hide();');
                $this->call('$("#waytame_item_question_'.$this->get('item_id').' .waytame_total_like").text(parseInt($("#waytame_item_question_'.$this->get('item_id').' .waytame_total_like").text()) + 1);');
            }
            $this->call('checkQuestion("#waytame_item_question_'.$this->get('item_id').'");');
        }

        public function unLikeQuestion()
        {
            if (Phpfox::getService('like.process')->delete($this->get('type_id'), $this->get('item_id'), (int) $this->get('force_user_id')))
            {
                $this->call('$("#waytame_item_question_'.$this->get('item_id').' .waytame_like").eq(0).show();');
                $this->call('$("#waytame_item_question_'.$this->get('item_id').' .waytame_unlike").eq(0).hide();');
                $this->call('$("#waytame_item_question_'.$this->get('item_id').' .waytame_dislike").eq(0).show();');
                $this->call('$("#waytame_item_question_'.$this->get('item_id').' .waytame_remove_dislike").eq(0).hide();');
                $this->call('$("#waytame_item_question_'.$this->get('item_id').' .waytame_total_like").text(parseInt($("#waytame_item_question_'.$this->get('item_id').' .waytame_total_like").text()) - 1);');
            }
            $this->call('checkQuestion("#waytame_item_question_'.$this->get('item_id').'");');
        }

        public function disLikeQuestion()
        {
            $sTypeId = str_replace('-', '_', $this->get('item_type_id'));
            $this->set(array('type_id' => $sTypeId));        
            if(Phpfox::getService('like')->didILike('waytame', $this->get('item_id')))
            {
                $this->call('$("#waytame_item_question_'.$this->get('item_id').' .waytame_total_like").text(parseInt($("#waytame_item_question_'.$this->get('item_id').' .waytame_total_like").text()) - 1);');
            }
            if (Phpfox::getService('like.process')->doAction($this->get('action_type_id'), $this->get('item_type_id'), $this->get('item_id'), $this->get('module_name') ))
            {
                $this->call('$("#waytame_item_question_'.$this->get('item_id').' .waytame_like").eq(0).show();');
                $this->call('$("#waytame_item_question_'.$this->get('item_id').' .waytame_unlike").eq(0).hide();');
                $this->call('$("#waytame_item_question_'.$this->get('item_id').' .waytame_dislike").eq(0).hide();');
                $this->call('$("#waytame_item_question_'.$this->get('item_id').' .waytame_remove_dislike").eq(0).show();');
                $this->call('$("#waytame_item_question_'.$this->get('item_id').' .waytame_total_dislike").text(parseInt($("#waytame_item_question_'.$this->get('item_id').' .waytame_total_dislike").text()) + 1);');
            }
            $this->call('checkQuestion("#waytame_item_question_'.$this->get('item_id').'");');
        }

        public function removeDisLlkeQuestion()
        {
            $sTypeId = $this->get('type_id');
            $sModuleId = $this->get('module_name');

            if (empty($sTypeId))
            {
                $sTypeId = $this->get('like_type_id');
            }

            if (empty($sModuleId) && !empty($sTypeId))
            {
                $this->set('module_name', $sTypeId);
                $sModuleId = $sTypeId;
            }
            if (empty($sTypeId) && $this->get('item_type_id') != '')
            {
                $this->set('type_id', $this->get('item_type_id'));
                $sTypeId = $this->get('item_type_id');
            }
            if (Phpfox::getService('like.process')->removeAction( 2, $sTypeId, $this->get('item_id'), $sModuleId ))
            {
                $this->call('$("#waytame_item_question_'.$this->get('item_id').' .waytame_like").eq(0).show();');
                $this->call('$("#waytame_item_question_'.$this->get('item_id').' .waytame_unlike").eq(0).hide();');
                $this->call('$("#waytame_item_question_'.$this->get('item_id').' .waytame_dislike").eq(0).show();');
                $this->call('$("#waytame_item_question_'.$this->get('item_id').' .waytame_remove_dislike").eq(0).hide();');
                $this->call('$("#waytame_item_question_'.$this->get('item_id').' .waytame_total_dislike").text(parseInt($("#waytame_item_question_'.$this->get('item_id').' .waytame_total_dislike").text()) - 1);');
            }
            $this->call('checkQuestion("#waytame_item_question_'.$this->get('item_id').'");');
        }

        public function likeAnswerQuestion()
        {
            Phpfox::isUser(true);

            if (Phpfox::getService('waytame.like')->addLike($this->get('item_id')))
            {
                $this->call('$("#waytame_answer_item_'.$this->get('item_id').' .waytame_like").hide();');
                $this->call('$("#waytame_answer_item_'.$this->get('item_id').' .waytame_unlike").show();');
                $this->call('$("#waytame_answer_item_'.$this->get('item_id').' .waytame_dislike").show();');
                $this->call('$("#waytame_answer_item_'.$this->get('item_id').' .waytame_remove_dislike").hide();');
            }
        }

        public function unLikeAnswerQuestion()
        {
            Phpfox::isUser(true);
            if (Phpfox::getService('waytame.like')->removeLike($this->get('item_id')))
            {
                $this->call('$("#waytame_answer_item_'.$this->get('item_id').' .waytame_like").show();');
                $this->call('$("#waytame_answer_item_'.$this->get('item_id').' .waytame_unlike").hide();');
                $this->call('$("#waytame_answer_item_'.$this->get('item_id').' .waytame_dislike").show();');
                $this->call('$("#waytame_answer_item_'.$this->get('item_id').' .waytame_remove_dislike").hide();');
            }
        }

        public function disLikeAnswerQuestion()
        {
            Phpfox::isUser(true);
            if (Phpfox::getService('waytame.like')->addDislike($this->get('item_id')) )
            {
                $this->call('$("#waytame_answer_item_'.$this->get('item_id').' .waytame_like").show();');
                $this->call('$("#waytame_answer_item_'.$this->get('item_id').' .waytame_unlike").hide();');
                $this->call('$("#waytame_answer_item_'.$this->get('item_id').' .waytame_dislike").hide();');
                $this->call('$("#waytame_answer_item_'.$this->get('item_id').' .waytame_remove_dislike").show();');
            }
        }

        public function removeDisLlkeAnswerQuestion()
        {
            Phpfox::isUser(true);

            if (Phpfox::getService('waytame.like')->removeDislike( $this->get('item_id')))
            {
                $this->call('$("#waytame_answer_item_'.$this->get('item_id').' .waytame_like").show();');
                $this->call('$("#waytame_answer_item_'.$this->get('item_id').' .waytame_unlike").hide();');
                $this->call('$("#waytame_answer_item_'.$this->get('item_id').' .waytame_dislike").show();');
                $this->call('$("#waytame_answer_item_'.$this->get('item_id').' .waytame_remove_dislike").hide();');
            }
        }

        public function deteleQuestion()
        {
            Phpfox::isUser(true);
            $iQuestionId = (int)$this->get('question_id');
            if(Phpfox::getService('waytame.process')->deleteQuestion($iQuestionId))
            {
                $this->call('$("#waytame_item_question_'.$this->get('question_id').'").remove();');
            }
        }

        public function deteleAnswer()
        {
            Phpfox::isUser(true);
            $iAnswerId = (int)$this->get('answer_id');
            if(Phpfox::getService('waytame.process')->deleteAnswer($iAnswerId))
            {
                $this->call('$("#waytame_answer_item_'.$this->get('answer_id').'").remove();');
            }
        }
    }
?>
