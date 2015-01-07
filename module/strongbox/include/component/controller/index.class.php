<?php

    /**
    * [PHPFOX_HEADER]
    */
    defined('PHPFOX') or exit('No direct script access allowed.');

    /**
    *
    *
    */
    class StrongBox_Component_Controller_Index extends Phpfox_Component {

        public function process()
        {
            $aStrongFeed = Phpfox::getService('strongbox.process')->getStrongFeed();
            //            d($aStrongFeed[0]);die();
            for($i =0; $i <count($aStrongFeed);$i++)
            {
                if(isset($aStrongFeed[$i]['comments']))
                {
                    $lenght = count($aStrongFeed[$i]['comments']);
                    for($j =0; $j <$lenght;$j++)
                    {
                        if(isset($aStrongFeed[$i]['comments'][$j]['comment_id']))
                        {
                            $idComment = $aStrongFeed[$i]['comments'][$j]['comment_id'];
                            $isCommentSB = Phpfox::getService('strongbox')->checkCommentVisible($idComment);
                            if(!$isCommentSB)
                            {
                                unset($aStrongFeed[$i]['comments'][$j]);
                                $lenght++;
                            }
                        }

                    }
                }
            }
            //d($aStrongFeed);die();
            $bForceReloadOnPage = false;
            $iFeedPage = 0; 
            $sCustomViewType='';
            $aFeedCallback = array();
            $bIsCustomFeedView = false;
            $bLoadCheckIn = false;
            $bForceFormOnly = false;
            $bIsHashTagPop = false;
            $this->template()->assign(array(
                'bForceReloadOnPage' => $bForceReloadOnPage,                
                'bHideEnterComment' => true,
                'aFeeds' => $aStrongFeed,
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