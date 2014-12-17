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
    class Waytime_Component_Controller_Admincp_Add_Answer extends Phpfox_Component
    {
        /**
        * Class process method wnich is used to execute this component.
        */
        public function process()
        {
            $bIsEdit = false;
            $iQuestionId = $this->request()->get('question-id');
            if(!$iQuestionId)
            {
                return $this->url()->send('admincp.waytime', null, Phpfox::getPhrase('waytime.question_not_found'));
            }
            if($iEditId = $this->request()->get('id'))
            {
                $bIsEdit = true;
                $aRow = Phpfox::getService('waytime')->getAnswer($iEditId);
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
                    if(Phpfox::getService('waytime.process')->updateAnswer($aVals, $iEditId))
                    {
                        return $this->url()->send('admincp.waytime', array('sub' => $iQuestionId), Phpfox::getPhrase('waytime.update_answer_successfully'));
                    }
                }
                else
                {
                    if(Phpfox::getService('waytime.process')->addAnswer($aVals))
                    {
                        return $this->url()->send('admincp.waytime',  array('sub' => $iQuestionId), Phpfox::getPhrase('waytime.add_answer_successfully'));
                    }
                }
            }
            $this->template()->setTitle(($bIsEdit ? Phpfox::getPhrase('waytime.edit_answer') : Phpfox::getPhrase('waytime.add_new_answer')))
            ->setBreadcrumb(($bIsEdit ? Phpfox::getPhrase('waytime.edit_answer') : Phpfox::getPhrase('waytime.add_new_answer')))
            ->assign(array(
                'iQuestionId' => $iQuestionId,
                'aQuestion' => Phpfox::getService('waytime')->getQuestion($iQuestionId)
            ));
        }
    }

?>