<?php

/**
 * [PHPFOX_HEADER]
 * 
 * @copyright        [TTN]
 * @author          ttngon
 */
defined('PHPFOX') or exit('NO DICE!');
class Strongbox_Component_Ajax_Ajax extends Phpfox_Ajax{
    public function makeStrongBox()
    {
        $iFeedId = $this->get('id');
        $iFeedType = $this->get('type');
        if (Phpfox::getService('strongbox')->makeStrongBox($iFeedId,$iFeedType))
        {
            $this->call("$('#strongbox_makeFollowed_$iFeedId').css(\"display\",\"none\");");
            $this->call("$('#strongbox_makePublic_$iFeedId').css(\"display\", \"block\");");
        }
    }
    public function makePublicStrongBox()
    {
        $iFeedId = $this->get('id');
        $iFeedType = $this->get('type');
        if (Phpfox::getService('strongbox')->makePublicStrongBox($iFeedId,$iFeedType))
        {
            $this->call("$('#strongbox_makePublic_$iFeedId').css(\"display\", \"none\");");
            $this->call("$('#strongbox_makeFollowed_$iFeedId').css(\"display\", \"block\");");
        }
    }
    
     public function makeStrongBoxIcon(){
        $iFeed = $this->get('feed');
        $iComment = $this->get('id');
        $iFeedType = $this->get('type');
        if (Phpfox::getService('strongbox')->makeStrongBoxIcon($iFeed,$iComment))
        {  
            $this->call("$('#icon_markbox_$iComment').css(\"display\",\"none\");");
            $this->call("$('#icon_showmarkbox_$iComment').css(\"display\", \"block\");");
        }
    }
    
     public function makePublicBoxIcon()
     {
        $iComment = $this->get('id');
        $iFeedType = $this->get('type');
        if (Phpfox::getService('strongbox')->makePublicStrongBoxIcon($iComment,$iFeedType))
        {
            $this->call("$('#icon_markbox_$iComment').css(\"display\",\"block\");");
            $this->call("$('#icon_showmarkbox_$iComment').css(\"display\", \"none\");");
        }
    }
}
