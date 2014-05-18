<?php
    defined('PHPFOX') or exit('NO DICE!');

    $bPrivate = Phpfox::getLib('request')->get('private');
    if($bPrivate || isset($_SESSION['is_private']))
    {   
        if($this->_bIsNewLoop || !$this->_bIsCallback)
        {
            $iLastFeedId = Phpfox::getService('privatepost.process')->getLastFeedId();
            Phpfox::getService('privatepost')->makePrivate($iLastFeedId);

            if(!Phpfox::getLib('request')->get('private_page') && !isset($_SESSION['private_page']))
            {
                $oAjax = Phpfox::getLib('ajax');
                echo '$Core.resetActivityFeedForm();$Core.loadInit();';
                $oAjax->alert(Phpfox::getPhrase('privatepost.submit_feed_successfuly_in_private_feed'));
                die();
            }

        }
    }
    else
    {
        if(Phpfox::getLib('request')->get('private_page') || isset($_SESSION['private_page']))
        {
            $oAjax = Phpfox::getLib('ajax');
            echo '$Core.resetActivityFeedForm();$Core.loadInit();';
            $oAjax->alert(Phpfox::getPhrase('privatepost.submit_feed_successfuly_in_public_feed'));
            die();
        }
    }
    if(isset($_SESSION['private_page']))
    {
        unset($_SESSION['private_page']);
    }
?>
