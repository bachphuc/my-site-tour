<?php
    /**
    * [PHPFOX_HEADER]
    * 
    * @copyright        [TTN]
    * @author          phuclb
    */
    defined('PHPFOX') or exit('NO DICE!');
    if(Phpfox::isModule('customprofiles')): ?>
    <li class=""><span>&middot;</span></li>
    <li><a href="" onclick="$.ajaxCall('customprofiles.blockUser','user_id=<?php echo $this->_aVars['aComment']['owner_user_id']; ?>','GET');return false;">block this user</a></li>
<?php endif;?>