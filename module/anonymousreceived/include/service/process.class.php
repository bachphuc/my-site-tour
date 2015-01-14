<?php
    /**
    * [PHPFOX_HEADER]
    */

    defined('PHPFOX') or exit('NO DICE!');

    /**
    * 
    * 
    * @copyright        [PHPFOX_COPYRIGHT]
    * @author          phuclb@npfox.com
    */
    class AnonymousReceived_Service_Process extends Phpfox_Service 
    {
        public function getLastFeedId()
        {
            $iFeedId = $this->database()->select('MAX(feed_id) AS last_feed_id')
            ->from(Phpfox::getT('feed'))
            ->execute('getSlaveField');
            return $iFeedId;
        }

        public function getReceivedFeed($iId = null)
        {
            $iTotalFeeds = 8;
            $iPage = Phpfox::getLib('request')->get('page', 0);
            $iOffset = ($iPage * $iTotalFeeds);
            
            $sSelect = 'feed.*,' . Phpfox::getUserField();
            $sOrder = 'feed.time_update DESC';
            
            $aConds = array('AND sb.receive_user_id='.Phpfox::getService('profile')->getProfileUserId());
            if($iId)
            {
                $aConds[] = "AND feed.type_id IN ('feed_comment','feed_egift')";
                $aConds[] = "AND feed.item_id = ".(int)$iId;
                $aConds[] = "AND feed.parent_user_id = ".Phpfox::getUserId();
            }
            
            $aRows = $this->database()->select($sSelect)
            ->from(Phpfox::getT('feed'), 'feed')            
            ->join(Phpfox::getT('user'), 'u', 'u.user_id = feed.user_id')
            ->join(Phpfox::getT('custom_profiles_anonymous_feed'), 'sb', 'sb.feed_id = feed.feed_id')
            ->where($aConds)
            ->order($sOrder)
            ->group('feed.feed_id')    
            ->limit($iOffset, $iTotalFeeds)      
            ->execute('getSlaveRows');   
            $bFirstCheckOnComments = false;
            if (Phpfox::getParam('feed.allow_comments_on_feeds') && Phpfox::isUser() && Phpfox::isModule('comment'))
            {
                $bFirstCheckOnComments = true;    
            }
            $aFeedLoop = array();
            $aLoopHistory = array();
            $aFeedLoop = $aRows;
            $aFeeds = array();
            $aCacheData = array();
            $sLastFriendId = '';
            $sLastPhotoId = 0;
            if (Phpfox::isModule('like'))
            {
                $oLike = Phpfox::getService('like');
            }

            $aParentFeeds = array();
            foreach ($aFeedLoop as $sKey => $aRow)
            { 
                $aRow['feed_time_stamp'] = $aRow['time_stamp'];
                if (($aReturn = $this->_processFeed($aRow, $sKey, null, $bFirstCheckOnComments)))
                {
                    if (isset($aReturn['force_user']))
                    {
                        $aReturn['user_name'] = $aReturn['force_user']['user_name'];
                        $aReturn['full_name'] = $aReturn['force_user']['full_name'];
                        $aReturn['user_image'] = $aReturn['force_user']['user_image'];
                        $aReturn['server_id'] = $aReturn['force_user']['server_id'];
                    }

                    $aReturn['feed_month_year'] = date('m_Y', $aRow['feed_time_stamp']);
                    $aReturn['feed_time_stamp'] = $aRow['feed_time_stamp'];
                    if (isset($aReturn['like_type_id']) && isset($oLike) && Phpfox::getParam('like.allow_dislike'))
                    {
                        $aReturn['marks'] = $oLike->getActionsFor($aReturn['like_type_id'], (isset($aReturn['like_item_id']) ? $aReturn['like_item_id'] : $aReturn['item_id']));
                    }

                    /* Lets figure out the phrases for like.display right here */
                    //if (Phpfox::getParam('like.allow_dislike'))                
                    if(Phpfox::isModule('like'))
                    {
                        $this->getPhraseForLikes($aReturn);
                    }

                    if (Phpfox::getParam('feed.cache_each_feed_entry') && !empty($aReturn['like_type_id']) && Phpfox::isUser() && isset($aReturn['likes']) && count($aReturn['likes']))
                    {                    
                        $iUserLiked = (isset($aReturn['likes_history'][Phpfox::getUserId()]) ? true : false);
                        $aReturn['feed_is_liked'] = $iUserLiked;
                        $aReturn['is_liked'] = $iUserLiked;
                    }

                    if (Phpfox::getParam('feed.cache_each_feed_entry') && isset($aReturn['comments']) && count($aReturn['comments']))
                    {
                        foreach ($aReturn['comments'] as $iCommentKey => $aCommentValue)
                        {                    
                            $aReturn['comments'][$iCommentKey]['is_liked'] = (isset($aCommentValue['liked_history'][Phpfox::getUserId()]) ? true : false);
                        }
                    }                                

                    $aFeeds[] = $aReturn;
                }

                // Show the feed properly. If user A posted on page 1, then feed will say "user A > page 1 posted ..."
                if (isset($this->_aCallback['module']) && $this->_aCallback['module'] == 'pages')
                {
                    // If defined parent user, and the parent user is not the same page (logged in as a page)
                    if (isset($aRow['page_user_id']) && $aReturn['page_user_id'] != $aReturn['user_id'])
                    {
                        $aParentFeeds[$aReturn['feed_id']] = $aRow['page_user_id'];
                    }
                }
                elseif (isset($aRow['parent_user_id']) && !isset($aRow['parent_user']))
                {
                    $aParentFeeds[$aRow['feed_id']] = $aRow['parent_user_id'];
                }

            }

            // Get the parents for the feeds so it displays arrow.png 
            if (!empty($aParentFeeds))
            {
                $aParentUsers = $this->database()->select(Phpfox::getUserField())
                ->from(Phpfox::getT('user'), 'u')
                ->where('user_id IN (' . implode(',',array_values($aParentFeeds)) . ')')
                ->execute('getSlaveRows');

                $aFeedsWithParents = array_keys($aParentFeeds);
                foreach ($aFeeds as $sKey => $aRow)
                {
                    if (in_array($aRow['feed_id'], $aFeedsWithParents))
                    {
                        foreach ($aParentUsers as $aUser)
                        {
                            if ($aUser['user_id'] == $aRow['parent_user_id'])
                            {
                                $aTempUser = array();
                                foreach ($aUser as $sField => $sVal)
                                {
                                    $aTempUser['parent_' . $sField] = $sVal;
                                }
                                $aFeeds[$sKey]['parent_user'] = $aTempUser;
                            }
                        }                    
                    }
                }
            }

            $oReq = Phpfox::getLib('request');
            if (($oReq->getInt('status-id')
                || $oReq->getInt('comment-id')
                || $oReq->getInt('link-id')
                || $oReq->getInt('poke-id')
                )
                && isset($aFeeds[0]))
            {
                $aFeeds[0]['feed_view_comment'] = true;
                // $this->setParam('aFeed', array_merge(array('feed_display' => 'view', 'total_like' => $aRows[0]['feed_total_like']), $aRows[0]));
            }

            return $aFeeds;

        }

        private function _processFeed($aRow, $sKey, $iUserid, $bFirstCheckOnComments)
        {            
            switch ($aRow['type_id'])
            {
                case 'comment_profile':
                case 'comment_profile_my':
                    $aRow['type_id'] = 'profile_comment'; break;
                case 'profile_info':
                    $aRow['type_id'] = 'custom'; break;
                case 'comment_photo':
                    $aRow['type_id'] = 'photo_comment'; break;
                case 'comment_blog':
                    $aRow['type_id'] = 'blog_comment'; break;
                case 'comment_video':
                    $aRow['type_id'] = 'video_comment'; break;
                case 'comment_group':
                    $aRow['type_id'] = 'pages_comment'; break;                
            }

            if (preg_match('/(.*)_feedlike/i', $aRow['type_id'])
                || $aRow['type_id'] == 'profile_design'
            )
            {
                $this->database()->delete(Phpfox::getT('feed'), 'feed_id = ' . (int) $aRow['feed_id']);

                return false;
            }


            if (!Phpfox::hasCallback($aRow['type_id'], 'getActivityFeed'))
            {
                return false;
            }

            $bCacheFeed = false;
            if (Phpfox::getParam('feed.cache_each_feed_entry'))
            {
                $bCacheFeed = true;
            }

            $sFeedCacheId = $this->cache()->set(array('feeds', $aRow['type_id'] . '_' . $aRow['item_id']));
            if ($bCacheFeed && ($aFeed = $this->cache()->get($sFeedCacheId)))
            {
                if (Phpfox::hasCallback($aRow['type_id'], 'getActivityFeedCustomChecks'))
                {
                    $aFeed = Phpfox::callback($aRow['type_id'] . '.getActivityFeedCustomChecks', $aFeed, $aRow);
                    if ($aFeed === false)
                    {
                        return false;
                    }
                }
            }
            else
            {
                $aFeed = Phpfox::callback($aRow['type_id'] . '.getActivityFeed', $aRow, (isset($this->_aCallback['module']) ? $this->_aCallback : null));

                if ($aFeed === false)
                {
                    return false;
                }
                /*
                if (!empty($aRow['feed_reference']))
                {
                $aRow['item_id'] = $aRow['feed_reference'];
                }
                */

                if (isset($this->_aViewMoreFeeds[$sKey]))
                {
                    foreach ($this->_aViewMoreFeeds[$sKey] as $iSubKey => $aSubRow)
                    {
                        $mReturnViewMore = $this->_processFeed($aSubRow, $iSubKey, $iUserid, $bFirstCheckOnComments);

                        if ($mReturnViewMore === false)
                        {
                            continue;
                        }

                        $aFeed['more_feed_rows'][] = $mReturnViewMore;
                    }
                }

                if (Phpfox::isModule('like') && (isset($aFeed['like_type_id']) || isset($aRow['item_id'])) && ( (isset($aFeed['enable_like']) && $aFeed['enable_like'])) || (!isset($aFeed['enable_like'])) &&  (isset($aFeed['feed_total_like']) && (int) $aFeed['feed_total_like'] > 0))
                {
                    $aFeed['likes'] = Phpfox::getService('like')->getLikesForFeed($aFeed['like_type_id'], (isset($aFeed['like_item_id']) ? $aFeed['like_item_id'] : $aRow['item_id']), ((int) $aFeed['feed_is_liked'] > 0 ? true : false), Phpfox::getParam('feed.total_likes_to_display'), true);                
                    $aFeed['feed_total_like'] = Phpfox::getService('like')->getTotalLikeCount();


                    if (Phpfox::getParam('feed.cache_each_feed_entry'))
                    {
                        $aAllLikesRows = $this->database()->select('user_id')
                        ->from(Phpfox::getT('like'))
                        ->where('type_id = \'' . $aFeed['like_type_id'] . '\' AND item_id = ' . (isset($aFeed['like_item_id']) ? $aFeed['like_item_id'] : $aRow['item_id']))
                        ->execute('getSlaveRows');
                        foreach ($aAllLikesRows as $aAllLikesRow)
                        {
                            $aFeed['likes_history'][$aAllLikesRow['user_id']] = true;
                        }
                    }                  
                }
                
                $bDetailFeed = (Phpfox::getLib('request')->getInt('id') ? true : false);
                $numCoutComment = (isset($aFeed['total_comment']) ? $aFeed['total_comment'] : 0);
                if (isset($aFeed['comment_type_id']) && (int) $aFeed['total_comment'] > 0 && Phpfox::isModule('comment'))
                {    
                    $aFeed['comments'] = Phpfox::getService('comment')->getCommentsForFeed($aFeed['comment_type_id'], $aRow['item_id'], ($bDetailFeed ? $numCoutComment : Phpfox::getParam('comment.total_comments_in_activity_feed')));
                    if (Phpfox::getParam('feed.cache_each_feed_entry'))
                    {
                        foreach ($aFeed['comments'] as $iCommentRowCnt => $aCommentRow)
                        {
                            $aCommentLikesRows = $this->database()->select('user_id')
                            ->from(Phpfox::getT('like'))
                            ->where('type_id = \'feed_mini\' AND item_id = ' . $aCommentRow['comment_id'])
                            ->execute('getSlaveRows');
                            foreach ($aCommentLikesRows as $aCommentLikesRow)
                            {
                                $aFeed['comments'][$iCommentRowCnt]['liked_history'][$aCommentLikesRow['user_id']] = true;
                            }    
                        }    
                    }
                }
                if($bDetailFeed)
                {
                    $aFeed['total_comment'] = 0;
                }
                
                if ($bCacheFeed)
                {
                    $this->cache()->save($sFeedCacheId, $aFeed);
                }
            }        

            if (isset($aRow['app_title']) && $aRow['app_id'])
            {
                $sLink = '<a href="' . Phpfox::permalink('apps', $aRow['app_id'], $aRow['app_title']) . '">' . $aRow['app_title'] . '</a>';
                $aFeed['app_link'] = $sLink;            
            }

            // Check if user can post comments on this feed/item
            $bCanPostComment = false;
            if ($bFirstCheckOnComments)
            {
                $bCanPostComment = true;    
            }

            if ($iUserid !== null && $iUserid != Phpfox::getUserId())
            {
                switch ($aRow['privacy_comment'])
                {
                    case '1':
                        // http://www.phpfox.com/tracker/view/14418/ instead of "if(!Phpfox::getService('user')->getUserObject($iUserid)->is_friend)"
                        if (Phpfox::getService('friend')->isFriend(Phpfox::getUserId(), $aRow['user_id']))
                        {
                            $bCanPostComment = false;
                        }
                        break;
                    case '2':
                        // http://www.phpfox.com/tracker/view/14418/ instead of "if (!Phpfox::getService('user')->getUserObject($iUserid)->is_friend && !Phpfox::getService('user')->getUserObject($iUserid)->is_friend_of_friend)"
                        if (Phpfox::getService('friend')->isFriend(Phpfox::getUserId(), $aRow['user_id']) && Phpfox::getService('friend')->isFriendOfFriend($aRow['user_id']))
                        {
                            $bCanPostComment = false;
                        }
                        break;
                    case '3':
                        $bCanPostComment = false;
                        break;
                }
            }

            if ($iUserid === null)
            {
                if ($aRow['user_id'] != Phpfox::getUserId())
                {
                    switch ($aRow['privacy_comment'])
                    {    
                        case '1':
                        case '2':
                            if (!isset($aRow['is_friend']) || !$aRow['is_friend'])
                            {
                                $bCanPostComment = false;
                            }
                            break;
                        case '3':
                            $bCanPostComment = false;
                            break;
                    }
                }
            }

            $aRow['can_post_comment'] = $bCanPostComment;


            if (!isset($aFeed['marks']))
            {
                if(Phpfox::isModule('like'))
                {
                    $aFeed['marks'] = Phpfox::getService('like')->getDislikes($aRow['type_id'], $aRow['item_id']);
                }
            }        

            $aFeed['bShowEnterCommentBlock'] = false;
            if (
                ( isset($aFeed['feed_total_like']) && $aFeed['feed_total_like'] > 0) ||
                ( isset($aFeed['marks']) && is_array($aFeed['marks']) && count($aFeed['marks'])) ||
                ( isset($aFeed['comments']) && is_array($aFeed['comments']) && count($aFeed['comments']))
            )
            {
                $aFeed['bShowEnterCommentBlock'] = true;
            }
            $aOut = array_merge($aRow, $aFeed);


            return $aOut;        
        }

        public function getPhraseForLikes(&$aFeed, $bForce = false)
        {
            $sOriginalIsLiked = ((isset($aFeed['feed_is_liked']) && $aFeed['feed_is_liked']) ? $aFeed['feed_is_liked'] : '');

            if(!isset($aFeed['feed_total_like']))
            {
                $aFeed['feed_total_like'] = isset($aFeed['likes']) ? count($aFeed['likes']) : 0;
            }

            if(!isset($aFeed['like_type_id']))
            {
                $aFeed['like_type_id'] = isset($aFeed['type_id']) ? $aFeed['type_id'] : null;
            }

            $sPhrase = '';
            $oParse = Phpfox::getLib('phpfox.parse.output');
            if (Phpfox::isModule('like'))
            {
                $oLike = Phpfox::getService('like');
            }
            $oUrl = Phpfox::getLib('url');

            if ((!isset($aFeed['likes']) && isset($oLike)) || count($aFeed['likes']) > Phpfox::getParam('feed.total_likes_to_display'))
            {
                $aFeed['likes'] = $oLike->getLikesForFeed($aFeed['type_id'], $aFeed['item_id'], false, Phpfox::getParam('feed.total_likes_to_display'));
                $aFeed['total_likes'] = count($aFeed['likes']);
            }

            $bDidILikeIt = false;
            /* Check to see if I liked this */
            if (Phpfox::getParam('feed.cache_each_feed_entry'))
            {
                $aFeed['feed_is_liked'] = false;
            }
            else
            {
                if (!isset($aFeed['feed_is_liked']))
                {
                    if(Phpfox::isModule('like'))
                    {
                        $aFeed['feed_is_liked'] = Phpfox::getService('like')->didILike($aFeed['type_id'], $aFeed['item_id']);
                    }
                }
            }

            $iCountLikes = (isset($aFeed['likes']) && !empty($aFeed['likes'])) ? count($aFeed['likes']) : 0;

            if (isset($aFeed['feed_is_liked']) && $aFeed['feed_is_liked'])        
            {
                $iPhraseLimiter = (!empty($iCountLikes) ? $iCountLikes : 0);
                if ($iPhraseLimiter == 1 || $iPhraseLimiter == 2)
                {
                    //$sPhrase = Phpfox::getPhrase('like.you');
                    //$sPhrase = Phpfox::getPhrase('like.you');
                }
                else if ($iPhraseLimiter > 2)
                {
                    //$sPhrase = Phpfox::getPhrase('like.you_comma').  '&nbsp;';
                }
                $bDidILikeIt = true;
            }
            else
            {
                if(Phpfox::isModule('like'))
                {
                    $sPhrase = Phpfox::getPhrase('like.article_to_upper');
                }
            }

            if (isset($aFeed['likes']) && is_array($aFeed['likes']) && $iCountLikes > 0)
            {
                $iIteration = 0;
                $aLikes = array();
                foreach ($aFeed['likes'] as $aLike)
                {
                    if($iIteration >= $iCountLikes)
                    {
                        break;
                    }
                    else
                    {
                        if ($aLike['user_id'] == Phpfox::getUserId() && !Phpfox::getParam('feed.cache_each_feed_entry'))
                        {
                            continue;
                        }
                        //$sUserLink = '<span class="user_profile_link_span" id="js_user_name_link_'. $aLike['user_name'] . '"><a href="' . $oUrl->makeUrl($aLike['user_name']) . '">'.$oParse->shorten($aLike['full_name'], 30) .'</a></span>';
                        $sUserLink = '<span class="user_profile_link_span" id="js_user_name_link_"></span>';
                        $aLikes[] = $sUserLink;
                        $iIteration++;
                    }            
                }

                $sTempUser = array_pop($aLikes);
                //$sImplode = implode(', ', $aLikes);
                $sImplode = implode($aLikes);
                $sPhrase .=  $sImplode . ' ';

                if (isset($aFeed['feed_is_liked']) && $aFeed['feed_is_liked'] && $iPhraseLimiter >= 2 && $aFeed['feed_total_like'] > $iPhraseLimiter)
                {
                    //$sPhrase = trim($sPhrase) . ', ' /*. Phpfox::getPhrase('like.and') . ' '*/;
                    $sPhrase = trim($sPhrase);
                }
                else if ( isset($aFeed['feed_total_like']) && ($aFeed['feed_total_like'] > Phpfox::getParam('feed.total_likes_to_display')) && Phpfox::getParam('feed.total_likes_to_display') != 1)
                {
                    //$sPhrase = trim($sPhrase) . ', ';
                    $sPhrase = trim($sPhrase);
                }
                else if (count($aLikes) > 0) 
                {
                    //$sPhrase .= Phpfox::getPhrase('like.and') . ' ';
                    $sPhrase .= ' ';
                }
                else
                {
                    $sPhrase = trim($sPhrase);
                }
                $sPhrase .= $sTempUser;

            }

            if (isset($aFeed['feed_total_like']) && $aFeed['feed_total_like'] > Phpfox::getParam('feed.total_likes_to_display') && Phpfox::getParam('feed.total_likes_to_display') != 0)
            {
                $sPhrase .= '<a href="#" onclick="return $Core.box(\'like.browse\', 400, \'type_id='. $aFeed['like_type_id'] . '&amp;item_id='. $aFeed['item_id'] . '\');">';
                //$iTotalLeftShow = ($aFeed['feed_total_like'] - Phpfox::getParam('feed.total_likes_to_display'));
                $iTotalLeftShow = ($aFeed['feed_total_like']);

                if ($iTotalLeftShow == 1)
                {
                    //$sPhrase .= '&nbsp;'. Phpfox::getPhrase('like.and') . '&nbsp;' . Phpfox::getPhrase('like.1_other_person') . '&nbsp;';
                    //$sPhrase .= '&nbsp;'. Phpfox::getPhrase('like.and') . '&nbsp;' . Phpfox::getPhrase('like.1_other_person') . '&nbsp;';
                    $sPhrase .= '</a>' . 'Useful to ' . number_format($aFeed['feed_total_like']) . '&nbsp;' . 'wayter';    // ------> aggiunta da me
                }
                else
                {
                    //$sPhrase .= '&nbsp;'. Phpfox::getPhrase('like.and') . '&nbsp;'. number_format($iTotalLeftShow) . '&nbsp;' . Phpfox::getPhrase('like.others') . '&nbsp;';
                    $sPhrase .= '&nbsp;'. 'Useful to ' . number_format($aFeed['feed_total_like']) . '&nbsp;';
                }
                //$sPhrase .= '</a>' . Phpfox::getPhrase('like.likes_this');
                $sPhrase .= '</a>' . 'wayters';
            }
            else
            {
                if (isset($aFeed['likes']) && count($aFeed['likes']) > 1)
                {
                    //$sPhrase .= '&nbsp;'. Phpfox::getPhrase('like.like_this');
                    $sPhrase .= '&nbsp;'. 'Useful to ' . number_format($aFeed['feed_total_like']) . '&nbsp;' . 'wayters';
                }
                else
                {
                    if (isset($aFeed['feed_is_liked']) && $aFeed['feed_is_liked'])
                    {
                        if (count($aFeed['likes']) == 1)
                        {
                            //$sPhrase .= '&nbsp;' . Phpfox::getPhrase('like.like_this');
                            $sPhrase .= '&nbsp;' . 'Useful to ' . number_format($aFeed['feed_total_like']) . '&nbsp;' . 'wayter'; 
                        }
                        else
                        {
                            if (count($aFeed['likes']) == 0)
                            {
                                $sPhrase .= '<a href="#" onclick="return $Core.box(\'like.browse\', 400, \'type_id='. $aFeed['like_type_id'] . '&amp;item_id='. $aFeed['item_id'] . '\');">';
                                //$sPhrase .= number_format($aFeed['feed_total_like']) . '&nbsp;' . Phpfox::getPhrase('like.others') . '&nbsp;';
                                $sPhrase .=  'Useful to ' . number_format($aFeed['feed_total_like']) . '&nbsp;';
                                //$sPhrase .= '</a>' . Phpfox::getPhrase('like.likes_this');
                                $sPhrase .= '</a>' . 'wayter';

                            }
                            else
                            {
                                // $sPhrase .= Phpfox::getPhrase('like.likes_this');
                                $sPhrase .= 'wayter trovano utile il tuo post';
                            }
                        }
                    }
                    else
                    {
                        if (isset($aFeed['likes']) && count($aFeed['likes']) == 1)
                        {
                            //$sPhrase .= '&nbsp;' . Phpfox::getPhrase('like.likes_this');
                            $sPhrase .= '&nbsp;' . 'Useful to ' . number_format($aFeed['feed_total_like']) . '&nbsp;' . 'wayter'; 
                        }
                        else if (strlen($sPhrase) > 1)
                        {
                            //$sPhrase .= Phpfox::getPhrase('like.like_this');    
                            $sPhrase .= '&nbsp;' . 'Useful to ' . number_format($aFeed['feed_total_like']) . '&nbsp;' . 'wayters'; 
                        }            
                    }
                }
            }

            // $aActions = Phpfox::getService('like')->getActionsFor($aFeed['type_id'], $aFeed['item_id']);        
            $aActions = array();
            if(Phpfox::isModule('like'))
            {
                $aActions = Phpfox::getService('like')->getDislikes($aFeed['type_id'], $aFeed['item_id']) ;
            }

            if (count($aActions) > 0)
            {
                $aFeed['bShowEnterCommentBlock'] = true;
                $aFeed['call_displayactions'] = true;
            }
            if (strlen($sPhrase) > 1 || count($aActions) > 0)
            {
                $aFeed['bShowEnterCommentBlock'] = true;
            }
            $sPhrase = str_replace(array("&nbsp;&nbsp;", '  ', "\n"), array('&nbsp;',' ',''), $sPhrase);
            $sPhrase = str_replace(array('  '," &nbsp;", "&nbsp; "), ' ', $sPhrase);
            //',&nbsp;,'
            $sPhrase = str_replace(array("\r\n", "\r"), "\n", $sPhrase);
            $aFeed['feed_like_phrase'] = $sPhrase;

            if (!empty($sOriginalIsLiked) && !$bForce)
            {
                $aFeed['feed_is_liked'] = $sOriginalIsLiked;
            }

            if (empty($sPhrase))
            {
                $aFeed['feed_is_liked'] = false;
                $aFeed['feed_total_like'] = 0;
            }

            return $sPhrase;
        }
    }
?>
