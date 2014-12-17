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
    class Waytime_Component_Controller_Admincp_Index extends Phpfox_Component
    {
        /**
        * Class process method wnich is used to execute this component.
        */
        public function process()
        {		
            $bSubCategory = false;
            if (($iId = $this->request()->getInt('sub')))
            {
                $this->template()->assign(array('iId' => $iId));
                $bSubCategory = true;
                if (($iDelete = $this->request()->getInt('delete')))
                {
                    if (Phpfox::getService('waytime.process')->deleteAnswer($iDelete, true))
                    {
                        $this->url()->send('admincp.waytime', array('sub' => $iId), Phpfox::getPhrase('waytime.delete_answer_successfully'));
                    }
                }
            }
            else
            {
                if (($iDelete = $this->request()->getInt('delete')))
                {
                    if (Phpfox::getService('waytime.process')->deleteQuestion($iDelete))
                    {
                        $this->url()->send('admincp.waytime', null, Phpfox::getPhrase('waytime.delete_question_successfully'));
                    }
                }			
            }

            $this->template()->setTitle(($bSubCategory ?  Phpfox::getPhrase('waytime.manage_answers') : Phpfox::getPhrase('waytime.manage_questions')))
            ->setBreadcrumb(($bSubCategory ?  Phpfox::getPhrase('waytime.manage_answers') : Phpfox::getPhrase('waytime.manage_questions')))
            ->setHeader(array(
                'drag.js' => 'static_script',
                '<script type="text/javascript">$Behavior.coreDragInit = function() { Core_drag.init({table: \'#js_drag_drop\', ajax: \'' . ($bSubCategory ? 'waytime.answerOrdering' : 'waytime.questionOrdering' ) . '\'}); }</script>'
                )
            )			
            ->assign(array(
                'bSubCategory' => $bSubCategory,
                'aCategories' => ($bSubCategory ? Phpfox::getService('waytime')->getAnswers($iId) : Phpfox::getService('waytime')->getQuestions())
                )
            );
        }
    }

?>