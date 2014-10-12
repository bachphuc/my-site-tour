<?php
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright        [TTN]
 * @author          phuclb
 */
defined('PHPFOX') or exit('NO DICE!');
$iActualFeedId = Phpfox::getService('customprofiles')->getActualFeedId($this->_aVars['aFeed']);
$aNonymousFeed = Phpfox::getService('customprofiles')->getAnonymousFeed($iActualFeedId);
?>
<?php if(isset($aNonymousFeed['anonymous_id']) && $aNonymousFeed['receive_user_id'] == Phpfox::getUserId()): ?> 
        <li><span>&middot;</span></li>
        <li id="anonymos_fee_<?php echo $aNonymousFeed['anonymous_id'];?>">
            <a href="javascript:void(0)" onclick="$.ajaxCall('customprofiles.<?php if($aNonymousFeed['privacy']): echo 'hideAnonymousFeed'; else : echo 'showAnonymousFeed';endif; ?>', 'anonymous_id=<?php echo $aNonymousFeed['anonymous_id']; ?>')"><?php if($aNonymousFeed['privacy']): echo 'private'; else : echo 'public';endif; ?></a>
        </li>
        <li><span>&middot;</span></li>
        <li id="anonymous_feed_block_<?php echo $aNonymousFeed['anonymous_id'];?>">
            <a href="javascript:void(0)" onclick="$.ajaxCall('customprofiles.blockUser', 'anonymous_id=<?php echo $aNonymousFeed['anonymous_id'];?>&user_id=<?php echo $aNonymousFeed['user_id']; ?>','GET')">block this user</a>
        </li>
<?php endif; ?>
