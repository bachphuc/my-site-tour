<?php
    /**
    * [PHPFOX_HEADER]
    */

    defined('PHPFOX') or exit('NO DICE!');

    /**
    * 
    * 
    * @copyright        [PHPFOX_COPYRIGHT]
    * @author          phuclb@npfox.com
    */
    class WayAnswer_Service_Callback extends Phpfox_Service 
    {
        public function getReportRedirect($iId)
        {
            return $this->getFeedRedirect($iId);
        }

        public function getFeedRedirect($iId, $iChild = 0)
        {

            $aAnswer = $this->database()->select('w.*')
            ->from(Phpfox::getT('waytame_answer'), 'w')
            ->join(Phpfox::getT('waytame_question'),'wt', 'wt.question_id=w.question_id')
            ->join(Phpfox::getT('user'), 'u', 'u.user_id = w.user_id')
            ->where('w.answer_id = ' . (int) $iId)
            ->execute('getSlaveRow');        

            if (!isset($aAnswer['answer_id']))
            {
                return false;
            }
            return Phpfox::getLib('url')->makeUrl('waytame.'.$aAnswer['question_id'],array('answer' => $aAnswer['answer_id']));
        }
    }
?>
