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
        $this->alert($iFeedId);
    }
}
