<?php
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[TTN]
 * @author  		ttngon
 */
defined('PHPFOX') or exit('NO DICE!');
$bPrivate = Phpfox::getService('privatepost')->isPrivate($this->_aVars['aFeed']['feed_id']);
?>
<?php if ($this->_aVars['aFeed']['user_id'] == Phpfox::getUserId()): ?> 
    <?php if (!$bPrivate) : ?>
        <li><span>&middot;</span></li>
        <li>
            <a href="javascript:void(0)" onclick="$.ajaxCall('privatepost.makePrivate', 'id=<?php echo $this->_aVars['aFeed']['feed_id']; ?>', 'GET')">
                <?php echo Phpfox::getPhrase('privatepost.private'); ?>
            </a>
        </li>
    <?php else: ?>
        <li><span>&middot;</span></li>
        <li>
            <a href="javascript:void(0)" onclick="$.ajaxCall('privatepost.makePublic', 'id=<?php echo $this->_aVars['aFeed']['feed_id']; ?>', 'GET')">
                <?php echo Phpfox::getPhrase('privatepost.public'); ?>
            </a>
        </li>
    <?php endif; ?>

<?php endif; ?>
