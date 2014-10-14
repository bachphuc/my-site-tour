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
    class AnonymousDone_Service_Process extends Phpfox_Service 
    {
        public function getLastFeedId()
        {
            $iFeedId = $this->database()->select('MAX(feed_id) AS last_feed_id')
            ->from(Phpfox::getT('feed'))
            ->execute('getSlaveField');
            return $iFeedId;
        }

        public function getDoneFeed()
        {
            $sSelect = 'feed.*,' . Phpfox::getUserField();
            $sOrder = 'feed.time_update DESC';
            $aRows = $this->database()->select($sSelect)
            ->from(Phpfox::getT('feed'), 'feed')            
            ->join(Phpfox::getT('user'), 'u', 'u.user_id = feed.user_id')
            ->join(Phpfox::getT('custom_profiles_anonymous_feed'), 'sb', 'sb.feed_id = feed.feed_id')
            ->where('sb.user_id='.Phpfox::getUserId())
            ->order($sOrder)
            ->group('feed.feed_id')         
            ->execute('getSlaveRows');   

            if ($bForceReturn === true)
            {
                return $aRows;
            }


            $bFirstCheckOnComments = false;
            if (Phpfox::getParam('feed.allow_comments_on_feeds') && Phpfox::isUser() && Phpfox::isModule('comment'))
            {
                $bFirstCheckOnComments = true;    
            }

            $iLoopMaxCount = Phpfox::getParam('feed.group_duplicate_feeds');    
            if (Phpfox::getService('profile')->timeline() || Phpfox::getParam('feed.cache_each_feed_entry'))
            {
                $iLoopMaxCount = 0;
            }

            if (defined('PHPFOX_SKIP_LOOP_MAX_COUNT'))
            {
                $iLoopMaxCount = 0;
            }

            $aFeedLoop = array();
            $aLoopHistory = array();
            if (Phpfox::getLib('request')->get('hashtagsearch'))
            {
                $aFeedLoop = $aRows;
            }
            else
            {
                if ($iLoopMaxCount > 0)
                {
                    foreach ($aRows as $iKey => $aRow)
                    {
                        $sFeedKey = $aRow['user_id'] . $aRow['type_id'] . date('dmyH', $aRow['time_stamp']);
                        if (isset($aRow['type_id']))
                        {
                            $aModule = explode('_', $aRow['type_id']);
                            if (isset($aModule[0]) && Phpfox::isModule($aModule[0]) && Phpfox::hasCallback($aModule[0] . (isset($aModule[1]) ? '_' . $aModule[1] : ''), 'getReportRedirect'))
                            {
                                $aRow['report_module'] = $aRows[$iKey]['report_module'] = $aModule[0] . (isset($aModule[1]) ? '_' . $aModule[1] : '');
                                $aRow['report_phrase'] = $aRows[$iKey]['report_phrase'] = Phpfox::getPhrase('feed.report_this_entry');
                                $aRow['force_report'] = $aRows[$iKey]['force_report'] = true;
                            }
                        }

                        if (isset($aFeedLoop[$sFeedKey]))
                        {
                            if (!isset($aLoopHistory[$sFeedKey]))
                            {
                                $aLoopHistory[$sFeedKey] = 0;
                            }

                            $aLoopHistory[$sFeedKey]++;

                            if ($aLoopHistory[$sFeedKey] >= ($iLoopMaxCount - 1))
                            {
                                $bIsLoop = true;

                                $this->_aViewMoreFeeds[$sFeedKey][] = $aRow;
                            }
                            else
                            {

                                $aFeedLoop[$sFeedKey . $aLoopHistory[$sFeedKey]] = $aRow;

                                continue;
                            }
                        }
                        else
                        {
                            $aFeedLoop[$sFeedKey] = $aRow;
                        }

                        if (isset($bIsLoop))
                        {
                            unset($bIsLoop);
                        }
                    }
                }
                else
                {
                    $aFeedLoop = $aRows;
                }
            }

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
                if (($aReturn = $this->_processFeed($aRow, $sKey, $iUserid, $bFirstCheckOnComments)))
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
                elseif (isset($aRow['parent_user_id']) && !isset($aRow['parent_user']) && $aRow['type_id'] != 'friend')
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
                    if (in_array($aRow['feed_id'], $aFeedsWithParents) && $aRow['type_id'] != 'photo_tag')
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

            if (Phpfox::getService('profile')->timeline())
            {        
                $iSubCnt = 0;
                foreach ($aFeeds as $iKey => $aFeed)
                {
                    if (is_int($iKey/2))
                    {
                        $this->_aFeedTimeline['left'][] = $aFeed;
                    }
                    else
                    {
                        $this->_aFeedTimeline['right'][] = $aFeed;
                    }

                    $iSubCnt++;
                    if ($iSubCnt === 1)
                    {
                        $sMonth = date('m', $aFeed['feed_time_stamp']);
                        $sYear = date('Y', $aFeed['feed_time_stamp']);
                        if ($sMonth == date('m', PHPFOX_TIME) && $sYear == date('Y', PHPFOX_TIME))
                        {
                            $this->_sLastDayInfo = '';
                        }
                        elseif ($sYear == date('Y', PHPFOX_TIME))
                        {
                            $this->_sLastDayInfo = Phpfox::getTime('F', $aFeed['feed_time_stamp'], false);
                        }
                        else
                        {
                            $this->_sLastDayInfo = Phpfox::getTime('F Y', $aFeed['feed_time_stamp'], false);
                        }
                    }
                }
            }
            if ($oReq->getInt('page') == 0 && Phpfox::isModule('ad') && Phpfox::getParam('ad.multi_ad') && $iFeedId == null && ( ($iAd = Phpfox::getService('ad')->getSponsoredFeed()) != false))
            {
                $aFeeds = array_splice($aFeeds, 0, count($aFeeds) - 1);
                $aSponsored = $this->get(null, $iAd);
                if (isset($aSponsored[0]))
                {
                    $aSponsored[0]['sponsored_feed'] = true;            
                    $aFeeds = array_merge($aSponsored, $aFeeds);
                }
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
                if($aRow['type_id'] == 'feed_comment')
                    $aFeed = $this->getActivityFeedComment($aRow);
                elseif ($aRow['type_id'] == 'feed_egift')
                    $aFeed = $this->getActivityFeedEgift($aRow);

                if ($aFeed === false)
                {             
                    return false; 
                }

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

                $numCoutComment = $aFeed['total_comment'];
                if (isset($aFeed['comment_type_id']) && (int) $aFeed['total_comment'] > 0 && Phpfox::isModule('comment'))
                {    
                    $aFeed['comments'] = Phpfox::getService('comment')->getCommentsForFeed($aFeed['comment_type_id'], $aRow['item_id'], $numCoutComment);
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
                $aFeed['total_comment'] = 0;    
                //               
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

                        if (Phpfox::getService('friend')->isFriend(Phpfox::getUserId(), $aRow['user_id']))
                        {
                            $bCanPostComment = false;
                        }
                        break;
                    case '2':

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

                }
                else if ($iPhraseLimiter > 2)
                {

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

                        $sUserLink = '<span class="user_profile_link_span" id="js_user_name_link_"></span>';
                        $aLikes[] = $sUserLink;
                        $iIteration++;
                    }            
                }

                $sTempUser = array_pop($aLikes);

                $sImplode = implode($aLikes);
                $sPhrase .=  $sImplode . ' ';

                if (isset($aFeed['feed_is_liked']) && $aFeed['feed_is_liked'] && $iPhraseLimiter >= 2 && $aFeed['feed_total_like'] > $iPhraseLimiter)
                {

                    $sPhrase = trim($sPhrase);
                }
                else if ( isset($aFeed['feed_total_like']) && ($aFeed['feed_total_like'] > Phpfox::getParam('feed.total_likes_to_display')) && Phpfox::getParam('feed.total_likes_to_display') != 1)
                {

                    $sPhrase = trim($sPhrase);
                }
                else if (count($aLikes) > 0) 
                {

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

                $iTotalLeftShow = ($aFeed['feed_total_like']);

                if ($iTotalLeftShow == 1)
                {

                    $sPhrase .= '</a>' . 'Useful to ' . number_format($aFeed['feed_total_like']) . '&nbsp;' . 'wayter';    
                }
                else
                {

                    $sPhrase .= '&nbsp;'. 'Useful to ' . number_format($aFeed['feed_total_like']) . '&nbsp;';
                }

                $sPhrase .= '</a>' . 'wayters';
            }
            else
            {
                if (isset($aFeed['likes']) && count($aFeed['likes']) > 1)
                {

                    $sPhrase .= '&nbsp;'. 'Useful to ' . number_format($aFeed['feed_total_like']) . '&nbsp;' . 'wayters';
                }
                else
                {
                    if (isset($aFeed['feed_is_liked']) && $aFeed['feed_is_liked'])
                    {
                        if (count($aFeed['likes']) == 1)
                        {

                            $sPhrase .= '&nbsp;' . 'Useful to ' . number_format($aFeed['feed_total_like']) . '&nbsp;' . 'wayter'; 
                        }
                        else
                        {
                            if (count($aFeed['likes']) == 0)
                            {
                                $sPhrase .= '<a href="#" onclick="return $Core.box(\'like.browse\', 400, \'type_id='. $aFeed['like_type_id'] . '&amp;item_id='. $aFeed['item_id'] . '\');">';

                                $sPhrase .=  'Useful to ' . number_format($aFeed['feed_total_like']) . '&nbsp;';

                                $sPhrase .= '</a>' . 'wayter';

                            }
                            else
                            {

                                $sPhrase .= 'wayter trovano utile il tuo post';
                            }
                        }
                    }
                    else
                    {
                        if (isset($aFeed['likes']) && count($aFeed['likes']) == 1)
                        {

                            $sPhrase .= '&nbsp;' . 'Useful to ' . number_format($aFeed['feed_total_like']) . '&nbsp;' . 'wayter'; 
                        }
                        else if (strlen($sPhrase) > 1)
                        {

                            $sPhrase .= '&nbsp;' . 'Useful to ' . number_format($aFeed['feed_total_like']) . '&nbsp;' . 'wayters'; 
                        }            
                    }
                }
            }


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

        public function getActivityFeedComment($aItem)
        {

            if(Phpfox::isModule('like'))
            {
                $this->database()->select('l.like_id AS is_liked, ')
                ->leftJoin(Phpfox::getT('like'), 'l', 'l.type_id = \'feed_comment\' AND l.item_id = fc.feed_comment_id AND l.user_id = ' . Phpfox::getUserId());
            }

            $aRow = $this->database()->select('fc.*, ' . Phpfox::getUserField('u', 'parent_'))
            ->from(Phpfox::getT('feed_comment'), 'fc')            
            ->leftJoin(Phpfox::getT('user'), 'u', 'u.user_id = fc.parent_user_id')
            ->where('fc.feed_comment_id = ' . (int) $aItem['item_id'])
            ->execute('getSlaveRow');

            $sLink = Phpfox::getLib('url')->makeUrl($aRow['parent_user_name'], array('comment-id' => $aRow['feed_comment_id']));

            $aReturn = array(
                'no_share' => true,
                'feed_status' => $aRow['content'],
                'feed_link' => $sLink,
                'total_comment' => $aRow['total_comment'],
                'feed_total_like' => $aRow['total_like'],
                'feed_is_liked' => (isset($aRow['is_liked']) ? $aRow['is_liked'] : false),
                'feed_icon' => Phpfox::getLib('image.helper')->display(array('theme' => 'misc/comment.png', 'return_url' => true)),
                'time_stamp' => $aRow['time_stamp'],            
                'enable_like' => true,            
                'comment_type_id' => 'feed',
                'like_type_id' => 'feed_comment'            
            );

            if (!empty($aRow['parent_user_name']) && !defined('PHPFOX_IS_USER_PROFILE') && empty($_POST))
            {
                $aReturn['parent_user'] = Phpfox::getService('user')->getUserFields(true, $aRow, 'parent_');
            }        

            if (!PHPFOX_IS_AJAX && defined('PHPFOX_IS_USER_PROFILE') && !empty($aRow['parent_user_name']) && $aRow['parent_user_id'] != Phpfox::getService('profile')->getProfileUserId())
            {            
                $aReturn['feed_info'] = Phpfox::getPhrase('feed.posted_on_parent_full_names_wall', array('parent_user_name' => Phpfox::getLib('url')->makeUrl($aRow['parent_user_name']), 'parent_full_name' => $aRow['parent_full_name']));
                $aReturn['feed_status'] = $aRow['content'];
                $aReturn['parent_user_id'] = $aRow['user_id'];
            }

            return $aReturn;        
        }
        public function getActivityFeedEgift($aItem)
        {    
            $this->database()->select('e.file_path, g.price, g.status, fc.content, fc.feed_comment_id, fc.total_comment, f.time_stamp, fc.total_like, ' . Phpfox::getUserField('u', 'parent_'))
            ->from(Phpfox::getT('egift_invoice'), 'g')
            ->join(Phpfox::getT('feed'), 'f', 'f.feed_id = g.birthday_id')
            ->join(Phpfox::getT('egift'), 'e', 'e.egift_id = g.egift_id')
            ->leftjoin(Phpfox::getT('feed_comment'), 'fc', 'fc.feed_comment_id = ' . $aItem['item_id'])
            ->leftJoin(Phpfox::getT('user'), 'u', 'u.user_id = fc.parent_user_id')

            ->where('g.birthday_id = ' . (int)$aItem['feed_id']);

            if(Phpfox::isModule('like'))
            {
                $this->database()->select(', l.like_id as is_liked')
                ->leftJoin(Phpfox::getT('like'), 'l', 'l.type_id = \'feed_egift\' AND l.item_id = fc.feed_comment_id AND l.user_id = ' . Phpfox::getUserId());
            }

            $aInvoice = $this->database()->execute('getSlaveRow');

            if ($aInvoice['price'] > 0 && $aInvoice['status'] != 'completed')
            {
                return false;
            }

            $aReturn = array(
                'no_share' => true,
                'feed_status' => $aInvoice['content'],
                'feed_link' => '',
                'total_comment' => $aInvoice['total_comment'],
                'feed_total_like' => $aInvoice['total_like'],
                'feed_is_liked' => (isset($aInvoice['is_liked']) ? $aInvoice['is_liked'] : false),
                'feed_icon' => Phpfox::getLib('image.helper')->display(array('theme' => 'misc/comment.png', 'return_url' => true)),
                'time_stamp' => $aInvoice['time_stamp'],            
                'enable_like' => true,            
                'comment_type_id' => 'feed',
                'like_type_id' => 'feed_egift'    
            );

            if (!empty($aInvoice['file_path']))
            {
                $aReturn['feed_image'] = Phpfox::getLib('image.helper')->display(array(
                    'server_id' => 0,
                    'path' => 'egift.url_egift',
                    'file' => $aInvoice['file_path'],
                    'suffix' => '_120',
                    'max_width' => 120,
                    'max_height' => 120,
                    'thickbox' => true
                    )
                );            
            }        

            if (!empty($aInvoice['parent_user_name']) && !defined('PHPFOX_IS_USER_PROFILE') && empty($_POST))
            {
                $aReturn['parent_user'] = Phpfox::getService('user')->getUserFields(true, $aInvoice, 'parent_');
            }        

            if (!PHPFOX_IS_AJAX && defined('PHPFOX_IS_USER_PROFILE') && !empty($aInvoice['parent_user_name']) && $aInvoice['parent_user_id'] != Phpfox::getService('profile')->getProfileUserId())
            {
                if (empty($_POST))
                {
                    $aReturn['parent_user'] = Phpfox::getService('user')->getUserFields(true, $aInvoice, 'parent_');
                }

            }        

            return $aReturn;
        }
    }
?>
