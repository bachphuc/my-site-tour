<?php
    if(isset($this->_aVars['aFeed']['comments']))
    {
       // d($this->_aVars['aFeed']['comments']);// die(); 
    }
    if(Phpfox::isModule('strongbox'))
    {
        defined('PHPFOX') or exit('NO DICE!');
        $type_id =0;
        if(isset($this->_aVars['aFeed']['type_id']))
        {
            $type_id = $this->_aVars['aFeed']['type_id'];
        }
        else
        {
            $type_id = $this->_aVars['aFeed']['comment_type_id'];
        }
        $item_id = $this->_aVars['aFeed']['item_id'];
        $feed_id = Phpfox::getService('strongbox')->isPost($item_id,$type_id); 
        $sListId = Phpfox::getService('strongbox')->isStrongBoxIcon($item_id,$type_id);
        /*$iMyPost = true;
        if($feed_id){
        $iMyPost = false;
        }*/
    ?>
    <?php if ($feed_id){ //echo $sListId;?> 
    
        <li style=" display: none;"><label id="<?php echo $sListId ?>" class='make_strongbox_<?php echo $feed_id ?>'><?php echo $feed_id; ?></label></li>
        <?php    $iStrongbox = Phpfox::getService('strongbox')->isStrongBox($feed_id);
            if(!$iStrongbox) {?>
            <li><span>|</span></li>
            <li id='strongbox_makeFollowed_<?php echo $feed_id; ?>' style=" display: block;">
                <a href="javascript:void(0)" onclick="$.ajaxCall('strongbox.makeStrongBox', 'id=<?php echo $feed_id; ?>&type=1', 'GET')">
                    <?php echo Phpfox::getPhrase('strongbox.strongbox'); ?>
                </a>
            </li>
            <li id='strongbox_makePublic_<?php echo $feed_id; ?>' style=" display: none;">
                <a href="javascript:void(0)" onclick="$.ajaxCall('strongbox.makePublicStrongBox', 'id=<?php echo $feed_id; ?>&type=1', 'GET')">
                    <?php echo Phpfox::getPhrase('strongbox.remove_strongbox'); ?>
                </a>
            </li>
            <!--<li><span>|</span></li>-->
            <?php } else { ?>
            <li><span>|</span></li>
            <li id='strongbox_makePublic_<?php echo $feed_id; ?>' style="display: block;">
                <a href="javascript:void(0)" onclick="$.ajaxCall('strongbox.makePublicStrongBox', 'id=<?php echo $feed_id; ?>&type=1', 'GET')">
                    <?php echo Phpfox::getPhrase('strongbox.remove_strongbox'); ?>
                </a>
            </li>
            <li id='strongbox_makeFollowed_<?php echo $feed_id; ?>' style="display: none;">
                <a href="javascript:void(0)" onclick="$.ajaxCall('strongbox.makeStrongBox','id=<?php echo $feed_id; ?>&type=1', 'GET')">
                    <?php echo Phpfox::getPhrase('strongbox.strongbox'); ?>
                </a>
            </li>
            <!--<li><img src="<?php echo Phpfox::getParam('core.path'); ?>module/strongbox/static/image/box.png" alt="" id="button_image_strongbox" class="img_button"></li>-->
            <?php }}} ?>




    
