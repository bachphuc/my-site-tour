<?php
    if(Phpfox::isModule('customprofiles'))
    {
        if ( Phpfox::isModule('invite') && (Phpfox::getCookie('invited_by_email') || Phpfox::getCookie('invited_by_user')))
        {
            if (($iInviteId = Phpfox::getCookie('invited_by_user')))
            {
                $aAnonymousInvite = $this->database()->select('*')
                ->from(Phpfox::getT('custom_profiles_invite'))
                ->where('user_id = '.(int)$iInviteId." AND email='".$aVals['email']."'")
                ->execute('getRow');
            }
            else if (($iInviteId = Phpfox::getCookie('invited_by_email')))
            {
                $aInvite = $this->database()->select('*')
                ->from(Phpfox::getT('invite'))
                ->where('invite_id = ' . (int) $iInviteId)
                ->execute('getSlaveRow');            

                $aAnonymousInvite = $this->database()->select('*')
                ->from(Phpfox::getT('custom_profiles_invite'))
                ->where('user_id = '.(int)$aInvite['user_id']." AND email='".$aInvite['email']."'")
                ->execute('getRow');
            } 
        }
        else
        {
            $aAnonymousInvite = $this->database()->select('*')
            ->from(Phpfox::getT('custom_profiles_invite'))
            ->where("email='".$aVals['email']."'")
            ->execute('getRow');
        }

        if(isset($aAnonymousInvite['invite_id']))
        {
            Phpfox::getLib('setting')->setParam('user.on_signup_new_friend',0);
            Phpfox::getService('customprofiles.process')->createFeedByInvite($aAnonymousInvite['feed_id'],$aExtras['user_id']);
            $bRemoveFriend = true;
            $iInviteUserId = $aAnonymousInvite['user_id'];
            if (Phpfox::isModule('notification'))
            {
                Phpfox::getService('customprofiles.process')->addNotification('customprofiles_replyinviteanonymous',$aAnonymousInvite['invite_id'], $aAnonymousInvite['user_id'], $iId); 
            }
            Phpfox::getService('customprofiles.process')->removeInvite($aAnonymousInvite['invite_id']);
        }
    }
?>
