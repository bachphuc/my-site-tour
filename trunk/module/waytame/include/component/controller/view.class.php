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
    * @package          Module_friendfeed
    * @version         $Id: index.class.php 3441 2011-11-02 15:53:59Z Miguel_Espinoza $
    */
    class Waytame_Component_Controller_View extends Phpfox_Component
    {
        /**
        * Class process method wnich is used to execute this component.
        */
        public function process()
        {  
            $aQuestion = $this->getParam('aQuestion',null);
            if(!isset($aQuestion['question_id']))
            {
                $this->url()->send('waytame');
            }
            if(!Phpfox::isUser())
            {
                $this->url()->send('user.register');
            }
            if(!Phpfox::getService('friend')->isFriend(Phpfox::getUserId(),$aQuestion['user_id']) && !Phpfox::isAdmin() && $aQuestion['user_id'] != Phpfox::getUserId())
            {
                return $this->url()->send('404',array(),'Page not found!');
            }
            if($iAnswerId = $this->request()->get('answer'))
            {
                $aAnswer = Phpfox::getService('waytame')->getAnswer($iAnswerId);
                if($aAnswer['question_id'] == $aQuestion['question_id'])
                {
                    if(isset($aQuestion['answers']) && isset($aQuestion['answers'][$aAnswer['answer_id']]))
                    {
                        unset($aQuestion['answers'][$aAnswer['answer_id']]);
                    }
                    $this->template()->assign(array('aAnswer' => $aAnswer));
                }
            }
            $this->template()->setBreadCrumb(Phpfox::getPhrase('waytame.waytame'),Phpfox::getLib('url')->makeUrl('waytame'))
            ->setBreadCrumb($aQuestion['question'],$this->url()->permalink('waytame', $aQuestion['question_id'], $aQuestion['question']), true)
            ->assign(array(
                'aQuestion' => $aQuestion,
                'aUser' => Phpfox::getService('user')->get(Phpfox::getUserId())
            ));
        }
    }
?>
