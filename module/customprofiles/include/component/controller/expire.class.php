<?php

    /**
    * [PHPFOX_HEADER]
    */
    defined('PHPFOX') or exit('NO DICE!');

    /**
    *
    *
    */
    class CustomProfiles_Component_Controller_Expire extends Phpfox_Component {

        /**
        * Class process method wnich is used to execute this component.
        */
        public function process()
        {
            Phpfox::isUser(true);
            Phpfox::isUser(true);
            $aFeeds = Phpfox::getService('customprofiles.expire')->getExpirePosts();
            
            if(Phpfox::isModule('customprofiles'))
            {
                Phpfox::getService('customprofiles.process')->processFeed($aFeeds);
            }

            $bForceReloadOnPage = false;
            $iFeedPage = $this->request()->get('page', 0);
            $sCustomViewType = null;
            $aFeedCallback = array();
            $bIsCustomFeedView = false;
            $bLoadCheckIn = false;
            $bForceFormOnly = false;
            $bIsHashTagPop = false;
            $this->template()->assign(array(
                'sFeedType' => 'mini',
                'bForceReloadOnPage' => $bForceReloadOnPage,                
                'bHideEnterComment' => true,
                'aFeeds' => $aFeeds,
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
