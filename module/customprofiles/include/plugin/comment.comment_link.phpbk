<?php
    /**
    * [PHPFOX_HEADER]
    * 
    * @copyright        [TTN]
    * @author          phuclb
    */
    defined('PHPFOX') or exit('NO DICE!');
    if(Phpfox::isModule('customprofiles')): 
        if($this->_aVars['aComment']['owner_user_id'] != Phpfox::getUserId()) : ?>
        <li class=""><span>&middot;</span></li>
        <li><a title="<?php echo Phpfox::getPhrase('customprofiles.block_this_user_and_you_will_not_receive_further_anonymous_messages_by_him_her');?>" href="" onclick="$.ajaxCall('customprofiles.blockUser','user_id=<?php echo $this->_aVars['aComment']['owner_user_id']; ?>','GET');return false;">block this user</a></li>
<?php endif;endif;?>