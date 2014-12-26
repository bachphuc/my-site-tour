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
    * @package          Module_Feed
    * @version         $Id: birth.class.php 5973 2013-05-28 11:04:04Z Raymond_Benc $
    */
    class Waytime_Component_Block_Last extends Phpfox_Component 
    {
        /**
        * Class process method wnich is used to execute this component.
        */
        public function process()
        {
            $aSummarys = Phpfox::getService('waytime')->getSummarys();
            $iTotalQuestion = Phpfox::getService('waytime')->getTotalQuestion();
            $this->template()->assign(array(
                'aSummarys' => $aSummarys,
                'iPre' => $iTotalQuestion
            ));
        }
    }
?>
