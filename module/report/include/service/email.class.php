<?php
    /**
    * [PHPFOX_HEADER]
    */

    defined('PHPFOX') or exit('NO DICE!');

    class Report_Service_Email extends Phpfox_Service 
    {    
        /**
        * Class constructor
        */    
        public function __construct()
        {    
            $this->_sTable = Phpfox::getT('report_template');
        }

        public function getEmailsTemplate()
        {
            return $this->database()->select('*')
            ->from($this->_sTable)
            ->order('template_id DESC')
            ->execute('getRows');
        }

        public function getForEdit($iId)
        {
            $aEmail = $this->database()->select('*')
            ->from($this->_sTable)
            ->where('template_id = ' . (int) $iId)
            ->execute('getRow');

            if (!isset($aEmail['template_id']))
            {
                return Phpfox_Error::set('Email template is not avaiable.');
            }

            return $aEmail;
        }

        public function update($iId, $aVals)
        {
            if (!isset($aVals['title']))
            {
                return Phpfox_Error::set('Provide a title.');
            }

            if (!isset($aVals['body']))
            {
                return Phpfox_Error::set('Provide a body template.');
            }

            $this->database()->update($this->_sTable, $aVals , 'template_id = ' . (int) $iId
            );        

            return true;
        }

        public function add($aVals)
        {
            if (!isset($aVals['title']))
            {
                return Phpfox_Error::set('Provide a title.');
            }

            if (!isset($aVals['body']))
            {
                return Phpfox_Error::set('Provide a body template.');
            }
            $aVals['time_stamp'] = PHPFOX_TIME;

            $iId = $this->database()->insert($this->_sTable, $aVals);

            return $iId;
        }

        public function delete($iId)
        {
            $this->database()->delete($this->_sTable, 'template_id = ' . (int) $iId);

            return true;
        }
    }
?>
