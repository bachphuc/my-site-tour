<?php
    if(Phpfox::isModule('customprofiles'))
    {
        $aControllers = array(
            'privatepost.index',
            'followedpost.index',
            'strongbox.index',
            'anonymousdone.index',
            'anonymousreceived.index',
            'waytime.profile',
            'waytame.profile',
            'customprofiles.expire'
        );
        $sFullController = Phpfox::getLib('module')->getFullControllerName();
        if(in_array($sFullController, $aControllers))
        {
            if($iId == 1)
            {
                $aBlocks[$iId][] = 'profile.pic';
                $aBlocks[$iId][] = 'customprofiles.menu';
            }
        }
    }
?>
