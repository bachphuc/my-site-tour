<?php
    if(Phpfox::isModule('customprofiles'))
    {
        // hau@gmail.com
        /*$iTotal = Phpfox::getService('customprofiles')->getUnseenTotal();
        if ($iTotal > 0)
        {        
        Phpfox::getLib('ajax')->call('$(\'#js_total_new_notifications\').html(\'' . (int) $iTotal . '\').css({display: \'block\'}).show();');
        }
        else
        {
        Phpfox::getLib('ajax')->call('$(\'#js_total_new_notifications\').html(\'' . 0 . '\').css({display: \'none\'}).hide();');
        }*/
        // end hau@gmail.com

        // phuclb@npfox.com
        if(Phpfox::isModule('customprofiles'))
        {
            Phpfox::getService('customprofiles.process')->updateCustomProfiles();
            Phpfox::getService('customprofiles.process')->updateScheduleFeed();
        }
        // end phuclb@npfox.com
    }
?>
