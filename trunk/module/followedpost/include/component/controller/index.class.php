<?php

    /**
    * [PHPFOX_HEADER]
    */
    defined('PHPFOX') or exit('No direct script access allowed.');

    /**
    *
    *
    */
    class FollowedPost_Component_Controller_Index extends Phpfox_Component 
    {

        public function process()
        {
            Phpfox::isUser(true);
            $aFollowed = Phpfox::getService('followedpost.process')->getFollowFeed();
            
            if(Phpfox::isModule('customprofiles'))
            {
                Phpfox::getService('customprofiles.process')->processFeed($aFollowed);
            }
            
            $bForceReloadOnPage = false;
            $iFeedPage = 0; 
            $sCustomViewType='';
            $aFeedCallback = array();
            $bIsCustomFeedView = false;
            $bLoadCheckIn = false;
            $bForceFormOnly = false;
            $bIsHashTagPop = false;
            $this->template()->assign(array(
                'sFeedType' => 'mini',
                'bForceReloadOnPage' => $bForceReloadOnPage,                
                'bHideEnterComment' => true,
                'aFeeds' => $aFollowed,
                'iFeedNextPage' => ($bForceReloadOnPage ? 0 : ($iFeedPage + 1)),
                'iFeedCurrentPage' => $iFeedPage,
                'iTotalFeedPages' => 1,
                'aFeedVals' => $this->request()->getArray('val'),
                'sCustomViewType' => $sCustomViewType,
                'aFeedStatusLinks' => Phpfox::getService('feed')->getShareLinks(),
                'aFeedCallback' => $aFeedCallback,
                'bIsCustomFeedView' => $bIsCustomFeedView,
                'sTimelineYear' => $this->request()->get('year'),
                'sTimelineMonth' => $this->request()->get('month'),
                'iFeedUserSortOrder' => Phpfox::getUserBy('feed_sort'),
                'bLoadCheckIn' => $bLoadCheckIn,
                'bForceFormOnly' => $bForceFormOnly,
                'sIsHashTagSearch' => urlencode(strip_tags((($this->request()->get('hashtagsearch') ? $this->request()->get('hashtagsearch') : ($this->request()->get('req1') == 'hashtag' ? $this->request()->get('req2') : ''))))),
                'sIsHashTagSearchValue' => urldecode(strip_tags((($this->request()->get('hashtagsearch') ? $this->request()->get('hashtagsearch') : ($this->request()->get('req1') == 'hashtag' ? $this->request()->get('req2') : ''))))),
                'bIsHashTagPop' => $bIsHashTagPop
                )
            );    

        }

    }

?>