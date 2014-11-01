<?php
    if(Phpfox::isModule('customprofiles'))
    {
        $aConds[] = 'AND is_delete = 0';
    }
?>
