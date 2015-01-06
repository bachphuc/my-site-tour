<?php
    if(Phpfox::isModule('followedpost'))
    {

        $bFollowed = Phpfox::getLib('request')->get('followed');
        if($bFollowed || isset($_SESSION['is_followed']))
        {   
            if($this->_bIsNewLoop || !$this->_bIsCallback)
            {
                $iLastFeedId = Phpfox::getService('followedpost.process')->getLastFeedId();
                Phpfox::getService('followedpost')->makeFollowed($iLastFeedId);

                if(!Phpfox::getLib('request')->get('followed_page') && !isset($_SESSION['followed_page']))
                {
                    $oAjax = Phpfox::getLib('ajax');
                    echo '$Core.resetActivityFeedForm();$Core.loadInit();';
                    $oAjax->alert(Phpfox::getPhrase('followedpost.submit_feed_successfuly_in_followed_feed'));
                    die();
                }

            }
        }
        else
        {
            if(Phpfox::getLib('request')->get('followed_page') || isset($_SESSION['followed_page']))
            {
                $oAjax = Phpfox::getLib('ajax');
                echo '$Core.resetActivityFeedForm();$Core.loadInit();';
                $oAjax->alert(Phpfox::getPhrase('followedpost.submit_feed_successfuly_in_public_feed'));
                die();
            }
        }
        if(isset($_SESSION['followed_page']))
        {
            unset($_SESSION['followed_page']);
        }
    }
?>
