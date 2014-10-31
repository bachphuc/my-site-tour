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
                        define('IS_ADD_ANONYMOUS_FEED', true);
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

        public function showGift()
        {
            $this->setTitle('Egift');

            Phpfox::getBlock('customprofiles.egift');
        }

        public function viewMoreFeed()
        {        
            $aComments = Phpfox::getService('comment')->getCommentsForFeed($this->get('comment_type_id'), $this->get('item_id'), Phpfox::getParam('comment.comment_page_limit'), ($this->get('total') ? (int) $this->get('total') : null));        

            if (!count($aComments))
            {
                Phpfox_Error::set('No comments found.');

                return false;
            }

            if($this->get('added') == 1)
            {
                array_pop($aComments);
            }

            foreach ($aComments as $aComment)
            {
                $aComment['owner_user_id'] = $aComment['user_id'];
                $aComment['full_name'] = Phpfox::getPhrase('customprofiles.a_wayter_commented');
                $aComment['user_id'] = 0;
                $aComment['user_image'] = "";
                $aComment['user_name'] = "";
                $aComment['is_check'] = true;
                $aFeed = Phpfox::getService('customprofiles')->getFeed($this->get('feed_id'));
                $aFeed['feed_id'] = $this->get('item_id');
                $aFeed['owner_user_id'] = $aFeed['user_id'];
                $aNonymousFeed = Phpfox::getService('customprofiles')->getAnonymousFeed($this->get('feed_id'));
                if(isset($aNonymousFeed['anonymous_id']))
                {
                    $aFeed['is_anonymous'] = true;
                }
                $this->template()->assign(array('aComment' => $aComment, 'aFeed' => $aFeed))->getTemplate('comment.block.mini');
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

        public function showAnonymousFeed()
        {
            $iAnonymousId = (int)$this->get('anonymous_id');
            if(!$iAnonymousId)
            {
                return $this->alert(Phpfox::getPhrase('customprofiles.feed_is_not_avaiable_this_time'));
            }
            $aAnonymousFeed = Phpfox::getService('customprofiles')->getAnonymousFeedById($iAnonymousId);
            if(!isset($aAnonymousFeed['anonymous_id']))
            {
                return $this->alert(Phpfox::getPhrase('customprofiles.feed_is_not_avaiable_this_time'));
            }
            if(Phpfox::getService('customprofiles.process')->showAnonymousFeedToFriend($iAnonymousId))
            {
                $this->call('$("#anonymos_fee_'.$iAnonymousId.'").html(\'<a href="" onclick="$.ajaxCall(\\\'customprofiles.hideAnonymousFeed\\\',\\\'anonymous_id='.$iAnonymousId.'\\\');return false;">private</a>\');');
                $this->alert(Phpfox::getPhrase('customprofiles.make_this_post_public_with_friend_successful'));
            }
            else
            {
                return $this->alert(Phpfox::getPhrase('customprofiles.cann_t_public_this_anonymous_feed'));
            }
        }

        public function hideAnonymousFeed()
        {
            $iAnonymousId = (int)$this->get('anonymous_id');
            if(!$iAnonymousId)
            {
                return $this->alert(Phpfox::getPhrase('customprofiles.feed_is_not_avaiable_this_time'));
            }
            $aAnonymousFeed = Phpfox::getService('customprofiles')->getAnonymousFeedById($iAnonymousId);
            if(!isset($aAnonymousFeed['anonymous_id']))
            {
                return $this->alert(Phpfox::getPhrase('customprofiles.feed_is_not_avaiable_this_time'));
            }
            if(Phpfox::getService('customprofiles.process')->hideAnonymousFeedToFriend($iAnonymousId))
            {
                $this->call('$("#anonymos_fee_'.$iAnonymousId.'").html(\'<a href="" onclick="$.ajaxCall(\\\'customprofiles.showAnonymousFeed\\\',\\\'anonymous_id='.$iAnonymousId.'\\\');return false;">public</a>\');');
                $this->alert(Phpfox::getPhrase('customprofiles.make_this_post_hide_with_friend_successful'));
            }
            else
            {
                return $this->alert(Phpfox::getPhrase('customprofiles.can_t_hide_this_post_with_friends'));
            }
        }

        public function blockUser()
        {
            $iUserId = (int)$this->get('user_id');
            $iAnonymousId = (int)$this->get('anonymous_id');
            if(!$iUserId)
            {
                return $this->alert(Phpfox::getPhrase('customprofiles.this_user_is_not_avaiable_this_time'));
            }
            if(Phpfox::getService('customprofiles')->checkBlockUser($iUserId))
            {
                return $this->alert(Phpfox::getPhrase('customprofiles.you_have_already_blocked_this_user'));
            }
            if(Phpfox::getService('customprofiles.process')->blockUser($iUserId))
            {
                if($iAnonymousId)
                {
                    $sHtml = Phpfox::getPhrase('customprofiles.bloc_user_success',array('user_id' => $iUserId, 'anonymous_id' => $iAnonymousId));
                    $this->call('$("#anonymous_feed_block_'.$iAnonymousId.'").closest(".js_feed_view_more_entry_holder").append(\''.$sHtml.'\');');
                    $this->call('$("#anonymous_feed_block_'.$iAnonymousId.'").closest(".row_feed_loop").hide();');
                }
                else
                {
                    $this->alert(Phpfox::getPhrase('customprofiles.block_this_user_successful'));
                }
            }
            else
            {
                $this->alert('Can not block this User.');
            }
        }

        public function unlockUser()
        {
            Phpfox::isAdmin(true);
            $iUserId = (int)$this->get('user_id');
            $iBlockUserId = (int)$this->get('block_user_id');
            $iBlockId = $this->get('block_id');

            if(Phpfox::getService('customprofiles.process')->removeBlockUser($iUserId, $iBlockUserId))
            {
                $this->alert('Unlock this user successful.');
                $this->call('$("#block_user_'.$iBlockId.'").remove();');
            }
            else
            {
                $this->alert('Can not block this User.');
            }
        }

        public function addReport()
        {        
            Phpfox::isUser(true);
            $this->setTitle(Phpfox::getPhrase('customprofiles.report_and_block_this_wayter'));
            Phpfox::getBlock('customprofiles.add', array(
                'sType' => $this->get('type'),
                'iItemId' => $this->get('id'),
                'iUserId' => (int)$this->get('user_id'),
                'iAnonymousId' => (int)$this->get('anonymous_id'),
                )
            );
        }

        public function insertReport()
        {
            Phpfox::isUser(true);

            if ($iReportId = Phpfox::getService('report.data.process')->add($this->get('report'), $this->get('type'), $this->get('id'), $this->get('feedback')))
            {
                $iUserId = (int)$this->get('user_id');
                $iAnonymousId = (int)$this->get('anonymous_id');
                if(Phpfox::getService('customprofiles')->checkBlockUser($iUserId))
                {
                    return $this->alert(Phpfox::getPhrase('customprofiles.you_have_already_blocked_this_user'));
                }
                if(Phpfox::getService('customprofiles.process')->blockUser($iUserId,$iReportId))
                {
                    $this->alert(Phpfox::getPhrase('customprofiles.block_this_user_successful'));
                }
            }
        }

        public function getUserData()
        {
            Phpfox::isAdmin(true);
            $this->setTitle('User Data');
            Phpfox::getBlock('customprofiles.userdata');
        }
        
        public function sendEmail()
        {
            Phpfox::isAdmin(true);
            $aVals = $this->get('val');
            if(!isset($aVals['to']))
            {
                return $this->alert('Please choose a receiver.');
            }
            if(!isset($aVals['message']) || empty($aVals['message']))
            {
                return $this->alert('Message can not be null.');
            }
            if(Phpfox::getService('mail.process')->add($aVals))
            {
                return $this->call('$("#" + tb_get_active() + " .js_box_content").html("Send message to this successful.");');
            }
            $this->call('$("#" + tb_get_active() + " .js_box_content").html("Can not send message to this user.");');
        }
    }
?>
