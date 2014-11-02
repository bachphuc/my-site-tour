<?php
    if(Phpfox::isModule('customprofiles'))
    {
        if(!Phpfox::isAdmin() || !Phpfox::getLib('request')->get('comment'))
        {
            $aConds[] = 'AND is_delete = 0';
        }
    }
?>
