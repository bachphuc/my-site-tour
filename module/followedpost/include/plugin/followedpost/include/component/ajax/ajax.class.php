<?php

/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[TTN]
 * @author  		ttngon
 */
defined('PHPFOX') or exit('NO DICE!');
class Followedpost_Component_Ajax_Ajax extends Phpfox_Ajax{
    public function makeFollowed(){
        $iFeedId = $this->get('id');
        if (Phpfox::getService('followedpost')->makeFollowed($iFeedId)){
            $this->call("$('#followedpost_makeFollowed_$iFeedId').css(\"display\",\"none\");");
            $this->call("$('#followedpost_makePublic_$iFeedId').css(\"display\", \"block\");");
        }
    }
    public function makePublic(){
        $iFeedId = $this->get('id');
        if (Phpfox::getService('followedpost')->makePublic($iFeedId)){
            $this->call("$('#followedpost_makePublic_$iFeedId').css(\"display\", \"none\");");
            $this->call("$('#followedpost_makeFollowed_$iFeedId').css(\"display\", \"block\");");
        }
    }
}
