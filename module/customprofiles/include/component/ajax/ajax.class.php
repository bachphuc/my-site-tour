<?php

    /**
    * [PHPFOX_HEADER]
    */
    defined('PHPFOX') or exit('NO DICE!');

    /**
    *
    *
    */
    class CustomProfiles_Component_Ajax_Ajax extends Phpfox_Ajax {

        /**
        * Class process method wnich is used to execute this component.
        */
        public function getFriend()
        {
            $aFriends = Phpfox::getService('customprofiles')->getFromCache();
            $sData = json_encode($aFriends);
            $this->call('$Core.friendTagCache = jQuery.parseJSON(\''.$sData.'\');');
        }

        public function addFeed()
        {
            $aVals = (array) $this->get('val');
            if($aVals)
            {
                if (Phpfox::getLib('parse.format')->isEmpty($aVals['message']))
                {
                    $this->alert(Phpfox::getPhrase('user.add_some_text_to_share'));
                    $this->call('$Core.activityFeedProcess(false);');
                    return;
                }        

                $aVals['feed_type'] = 'feed_comment';         
                if (isset($aVals['message']) && ($iId = Phpfox::getService('customprofiles.process')->addFeed($aVals)))
                {          
                    $this->call('$Core.resetAnonymousPost();');     
                    if(isset($aVals['time_delay']) && $aVals['time_delay'] > 0)
                    {
                        $this->alert(Phpfox::getPhrase('customprofiles.you_have_scheduled_to_post_a_anonymous_feed_to_other_user'));
                    } 
                    else
                    {
                        Phpfox::getService('feed')->processAjax($iId);  
                    }
                }
                else 
                {
                    $this->call('$Core.activityFeedProcess(false);');
                }    

            }
        }

        public function acceptAnonymousPost()
        {
            // $aNonymousFeed = Phpfox::getService('customprofiles')->getAnonymousFeed($this->get('feed_id'));
            $aNonymousFeed = Phpfox::getService('customprofiles')->getScheduleFeed($this->get('feed_id'));
            if(!isset($aNonymousFeed['feed_id']))
            {
                $this->alert('Feed not avaiable!');return;
            }

            if($aNonymousFeed['receive_user_id'] != Phpfox::getUserId())
            {
                $this->alert("You don't have permission to do this!");return;
            }

            if($iFeedId = Phpfox::getService('customprofiles.process')->acceptAnonymousPost($this->get('feed_id')))
            {
                Phpfox::getService('customprofiles.feed')->processAjax($iFeedId); 
                $this->call('$("#confirm_notification_'.$this->get('notify_id').'").fadeOut();');
            }
        }

        public function refuseAnonymousPost()
        {
            // $aNonymousFeed = Phpfox::getService('customprofiles')->getAnonymousFeed($this->get('feed_id'));
            $aNonymousFeed = Phpfox::getService('customprofiles')->getScheduleFeed($this->get('feed_id'));
            if(!isset($aNonymousFeed['feed_id']))
            {
                $this->alert('Feed not avaiable!');return;
            }

            if($aNonymousFeed['receive_user_id'] != Phpfox::getUserId())
            {
                $this->alert("You don't have permission to do this!");return;
            }

            if(Phpfox::getService('customprofiles.process')->recceptAnonymousPost($this->get('feed_id')))
            {
                $this->call('$("#confirm_notification_'.$this->get('notify_id').'").fadeOut();');
            }
        }

        // loi@gmail.com
        public function showGift()
        {
            $this->setTitle('Egift');

            Phpfox::getBlock('customprofiles.egift');
        }
        // end loi@gmail.com

        public function viewMoreFeed()
        {        
            $aComments = Phpfox::getService('comment')->getCommentsForFeed($this->get('comment_type_id'), $this->get('item_id'), Phpfox::getParam('comment.comment_page_limit'), ($this->get('total') ? (int) $this->get('total') : null));        

            if (!count($aComments))
            {
                Phpfox_Error::set('No comments found.');

                return false;
            }

            // http://www.phpfox.com/tracker/view/15074/
            // if the added parameter is 1
            if($this->get('added') == 1)
            {
                // remove the last object, or it will be displayed as duplicate
                array_pop($aComments);
            }

            foreach ($aComments as $aComment)
            {
                // hide user_name and user_image
                $aComment['full_name'] = Phpfox::getPhrase('customprofiles.a_wayter_commented');
                $aComment['user_id'] = 0;
                $aComment['user_image'] = "";
                $aComment['user_name'] = "";
                // end hide

                $this->template()->assign(array('aComment' => $aComment, 'aFeed' => array('feed_id' => $this->get('item_id'))))->getTemplate('comment.block.mini');
            }

            if ($this->get('append'))
            {            
                $this->prepend('#js_feed_comment_view_more_' . ($this->get('feed_id') ? $this->get('feed_id') : $this->get('item_id')), $this->getContent(false));

                Phpfox::getLib('pager')->set(array(
                    'ajax' => 'comment.viewMoreFeed', 
                    'page' => Phpfox::getLib('request')->getInt('page'), 
                    'size' => $this->get('pagelimit'), 
                    'count' => $this->get('total'),
                    'phrase' => 'View previous comments',
                    'icon' => 'misc/comment.png',
                    'aParams' => array(
                        'comment_type_id' => $this->get('comment_type_id'),
                        'item_id' => $this->get('item_id'),
                        'append' => true,
                        'pagelimit' => $this->get('pagelimit'),
                        'total' => $this->get('total')
                    )
                    )
                );    

                $this->template()->getLayout('pager');        

                $this->html('#js_feed_comment_pager_' . ($this->get('feed_id') ? $this->get('feed_id') : $this->get('item_id')), $this->getContent(false));
            }
            else 
            {
                $this->hide('#js_feed_comment_view_more_link_' . ($this->get('feed_id') ? $this->get('feed_id') : $this->get('item_id')));
                $this->html('#js_feed_comment_view_more_' . ($this->get('feed_id') ? $this->get('feed_id') : $this->get('item_id')), $this->getContent(false));
            }

            $this->call('$Core.loadInit();');
        }    
    }
?>
