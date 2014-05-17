<?php
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[TTN]
 * @author  		ttngon
 */
defined('PHPFOX') or exit('NO DICE!');
//d($this->_aVars['aFeed']);
?>
<?php if ($this->_aVars['aFeed']['user_id'] == Phpfox::getUserId()): ?> 
    <li><span>&middot;</span></li>
    <li>
        <a href="#" onclick="$.ajaxCall('privatepost.makePrivate','id=<?php echo $this->_aVars['aFeed']['feed_id'];?>','GET')">
            <?php echo Phpfox::getPhrase('privatepost.private'); ?>
        </a>
    </li>
<?php endif; ?>
