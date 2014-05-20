<?php
    defined('PHPFOX') or exit('NO DICE!');
    
    if($iUserid == Phpfox::getUserId() && Phpfox::getLib('request')->get('view') == 'private')
    {
        $this->database()->select('feed.*')
        ->from(Phpfox::getT('mytable'),'mt')
        ->join(Phpfox::getT('private_feed'),'feed','feed.feed_id=mt.feed_id')
        ->where('mt.user_id = ' . (int) $iUserid . '')
        ->union();
    }
    
?>
