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
    $iExpireTime = $this->_aVars['aFeed']['expire_time'];
    $iTotalSeconds = $iExpireTime - PHPFOX_TIME;
    $iHour = (int)($iTotalSeconds / 3600);
    $iMinute = (int)(($iTotalSeconds - $iHour * 3600) / 60);
    $iSecond = $iTotalSeconds - $iHour * 3600 - $iMinute * 60;
    $sExpireTime = $iHour.':'.$iMinute.':'.$iSecond;
?>
<?php if($this->_aVars['aFeed']['expire_time']) : ?>
    <li><span>&middot;</span></li>
    <li><a>Self-destruction in <?php echo $sExpireTime;?></a></li>
    <?php endif;?>
<?php if(isset($aNonymousFeed['anonymous_id']) && $aNonymousFeed['receive_user_id'] == Phpfox::getUserId()): ?> 
    <li><span>&middot;</span></li>
    <li id="anonymos_fee_<?php echo $aNonymousFeed['anonymous_id'];?>">
        <a href="javascript:void(0)" onclick="$.ajaxCall('customprofiles.<?php if($aNonymousFeed['privacy']): echo 'hideAnonymousFeed'; else : echo 'showAnonymousFeed';endif; ?>', 'anonymous_id=<?php echo $aNonymousFeed['anonymous_id']; ?>')"><?php if($aNonymousFeed['privacy']): echo 'private'; else : echo 'public';endif; ?></a>
    </li>
    <?php if(!Phpfox::getService('friend')->isFriend($aNonymousFeed['receive_user_id'], $aNonymousFeed['user_id'])) : ?>
        <li><span>&middot;</span></li>
        <li id="anonymous_feed_block_<?php echo $aNonymousFeed['anonymous_id'];?>">
            <a class="js_hover_title inlinePopup activity_feed_report" title="<?php echo $this->_aVars['aFeed']['report_phrase'];?>" href="#?call=customprofiles.addReport&amp;height=100&amp;width=400&amp;type=<?php echo $this->_aVars['aFeed']['report_module']; ?>&amp;id=<?php echo $this->_aVars['aFeed']['item_id'];?>&amp;anonymous_id=<?php echo $aNonymousFeed['anonymous_id']; ?>&amp;user_id=<?php echo $aNonymousFeed['user_id']; ?>" onclick="return true;$.ajaxCall('customprofiles.blockUser', 'anonymous_id=<?php echo $aNonymousFeed['anonymous_id'];?>&user_id=<?php echo $aNonymousFeed['user_id']; ?>','GET')"><?php echo Phpfox::getPhrase('customprofiles.block_this_user');?><span class="js_hover_info"><?php echo Phpfox::getPhrase('customprofiles.if_you_click_this_link_you_will_not_receive_further_anonymous_messages_by_this_wayter');?></span></a>
        </li>
        <?php endif ?>
    <?php endif; ?>
