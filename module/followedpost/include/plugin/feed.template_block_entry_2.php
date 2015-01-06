<?php
    /**
    * [PHPFOX_HEADER]
    * 
    * @copyright        [TTN]
    * @author          ttngon
    */
    defined('PHPFOX') or exit('NO DICE!');
    $sController = Phpfox::getLib('module')->getFullControllerName();
    $sView = Phpfox::getLib('request')->get('view');
    $aProfileUser ="";
    if(isset($this->_aVars['aUser']))
    {
     $aProfileUser = $this->_aVars['aUser'];
    }
     if (isset($sController) && $sController != 'profile.index' || isset($aProfileUser['user_id']) && $aProfileUser['user_id']!= Phpfox::getUserId()||(isset($sView) && $sView == 'followed'))
     {
    $bFollowed = Phpfox::getService('followedpost')->isFollowed($this->_aVars['aFeed']['feed_id']);
    $iFollow = Phpfox::getService('followedpost')->countFollow($this->_aVars['aFeed']['feed_id']);
    if($bFollowed){
        $iFollow = (int) $iFollow -0;
    }
?>
<?php if ($iFollow > 0) {?>
<li>
        <p style="color: #63799F">Followed by <label><?php echo $iFollow;?></label> wayters</p>
</li>
<li><span>|</span></li>
<?php }else{ ?>
<li style="display: none;">
        <p style="color: #63799F">Followed by <label></label> wayters</p>
</li>
<?php } ?>
<?php if (!$bFollowed) { ?>
    <li id='followedpost_makeFollowed_<?php echo $this->_aVars['aFeed']['feed_id']; ?>' style=" display: block;">
        <a href="javascript:void(0)" onclick="$.ajaxCall('followedpost.makeFollowed', 'id=<?php echo $this->_aVars['aFeed']['feed_id']; ?>', 'GET')">
            <?php echo Phpfox::getPhrase('followedpost.followed'); ?>
        </a>
    </li>
    <li id='followedpost_makePublic_<?php echo $this->_aVars['aFeed']['feed_id']; ?>' style=" display: none;">
        <a href="javascript:void(0)" onclick="$.ajaxCall('followedpost.makePublic', 'id=<?php echo $this->_aVars['aFeed']['feed_id']; ?>', 'GET')">
            <?php echo Phpfox::getPhrase('followedpost.public'); ?>
        </a>
    </li>
    <!--<li><span>|</span></li>-->
    <?php }else{ ?>
    <li id='followedpost_makePublic_<?php echo $this->_aVars['aFeed']['feed_id']; ?>' style="display: block;">
        <a href="javascript:void(0)" onclick="$.ajaxCall('followedpost.makePublic', 'id=<?php echo $this->_aVars['aFeed']['feed_id']; ?>', 'GET')">
            <?php echo Phpfox::getPhrase('followedpost.public'); ?>
        </a>
    </li>
    <li id='followedpost_makeFollowed_<?php echo $this->_aVars['aFeed']['feed_id']; ?>' style="display: none;">
        <a href="javascript:void(0)" onclick="$.ajaxCall('followedpost.makeFollowed', 'id=<?php echo $this->_aVars['aFeed']['feed_id']; ?>', 'GET')">
            <?php echo Phpfox::getPhrase('followedpost.followed'); ?>
        </a>
    </li>
    <!--<li><span>|</span></li>-->
<?php }} ?>

    
