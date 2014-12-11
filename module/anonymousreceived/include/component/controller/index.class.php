<?php

    /**
    * [PHPFOX_HEADER]
    */
    defined('PHPFOX') or exit('No direct script access allowed.');

    /**
    *
    *
    */
    class AnonymousReceived_Component_Controller_Index extends Phpfox_Component {

        public function process()
        {
            Phpfox::isUser(true);
            $iId = $this->request()->get('id');
            $aReceivedFeed = Phpfox::getService('anonymousreceived.process')->getReceivedFeed($iId);
            Phpfox::getService('customprofiles.process')->processFeed($aReceivedFeed);
            
            if(!count($aReceivedFeed) && $iId)
            {
                return $this->url()->send('error.404');
            }
            $bForceReloadOnPage = false;
            $iFeedPage = 0; 
            
            $sCustomViewType = ($iId ? 'Wall Feed: #'.$iId : '');
            
            $aFeedCallback = array();
            $bIsCustomFeedView = false;
            $bLoadCheckIn = false;
            $bForceFormOnly = false;
            $bIsHashTagPop = false;
            $this->template()->assign(array(
                'bForceReloadOnPage' => $bForceReloadOnPage,                
                'bHideEnterComment' => true,
                'aFeeds' => $aReceivedFeed,
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
                'sIsHashTagSearch' => false,
                'sIsHashTagSearchValue' => false,
                'bIsHashTagPop' => $bIsHashTagPop
                )
            );    

        }

    }

?>