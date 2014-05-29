<?php
    /**
    * [PHPFOX_HEADER]
    */

    defined('PHPFOX') or exit('NO DICE!');

    /**
    * 
    * 
    * @copyright        [PHPFOX_COPYRIGHT]
    * @author          Raymond Benc
    * @package         Phpfox_Ajax
    * @version         $Id: ajax.class.php 7092 2014-02-05 21:42:42Z Fern $
    */
    class FriendFeed_Component_Ajax_Ajax extends Phpfox_Ajax
    {
        public function viewMore()
        {
            Phpfox::getBlock('feed.display');        
            $this->call('$Core.backUpHomeFeed();');
            $this->remove('#feed_view_more');
            $this->html('#js_feed_content', $this->getContent(false));
            $this->call('$Core.init();');
            $this->call('$("#friend_item_'.$this->get('profile_user_id').' .friend_feed_loading").fadeOut();');
            $this->call('$(".active_friend_feed").removeClass("active_friend_feed");');
            $this->call('$("#friend_item_'.$this->get('profile_user_id').' .bottom_name").addClass("active_friend_feed");');
            $this->call('$("#toggle").attr("checked",true);');
            $this->call('$Core.isLoadingFriendFeed = false;');
        }
    }
?>
