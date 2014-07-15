<?php
    if(Phpfox::isModule('customprofiles'))
    {
        if(isset($bRemoveFriend) && $bRemoveFriend)
        {
            Phpfox::getService('customprofiles.process')->removeFriend($iId,$iInviteUserId);
        }
    }
?>
