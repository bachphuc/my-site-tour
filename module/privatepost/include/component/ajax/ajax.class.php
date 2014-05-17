<?php

/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[TTN]
 * @author  		ttngon
 */
defined('PHPFOX') or exit('NO DICE!');
class Privatepost_Component_Ajax_Ajax extends Phpfox_Ajax{
    public function makePrivate(){
        $iFeedId = $this->get('id');
        if (Phpfox::getService('privatepost')->makePrivate($iFeedId)){
            $this->call("$('#js_item_feed_$iFeedId').remove();");
        }
    }
    public function makePublic(){
        $iFeedId = $this->get('id');
        if (Phpfox::getService('privatepost')->makePublic($iFeedId)){
            $this->call("$('#js_item_feed_$iFeedId').remove();");
        }
    }
}
