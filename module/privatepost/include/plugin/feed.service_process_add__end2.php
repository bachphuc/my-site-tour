<?php
    defined('PHPFOX') or exit('NO DICE!');

    $bPrivate = Phpfox::getLib('request')->get('private');
    if($bPrivate || isset($_SESSION['is_private']))
    {
        if($this->_bIsNewLoop || !$this->_bIsCallback)
        {
            if(isset($_SESSION['is_private']))
            {
                unset($_SESSION['is_private']);
            }
            $iLastFeedId = Phpfox::getService('privatepost.process')->getLastFeedId();
            Phpfox::getService('privatepost')->makePrivate($iLastFeedId);
        }
    }
?>
