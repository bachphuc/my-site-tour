<?php
    if(Phpfox::isModule('followedpost'))
    {
        $sView = $this->request()->get('view');
        $sUserName = Phpfox::getLib('request')->get('req1');
        $aProfileUser = Phpfox::getService('user')->get($sUserName,false);
        //d($aRows);
		
        if (isset($sView) && !empty($sView)){
            if ($sView == 'followed'){
				//echo "Check :".count($aRows);
                foreach ($aRows as $key => $value){
                    if(isset($value['followed_id']))
                    {
                        if($value['user_id'] == $aProfileUser['user_id'])
                        {
                            $sFindUserName =  $value['user_name'];
                            $user_id = $value['user_followed'];
                            $user_followed = $value['user_id'];
                            $aRows[$key]['user_id'] = $user_id;
                            $aRows[$key]['user_followed'] = $user_followed;

                            $iCnt = Phpfox::getService('followedpost')->getInforUser($user_id);
                            $aRows[$key]['user_name'] = $iCnt[0]['user_name'];
                            $aRows[$key]['user_image'] = $iCnt[0]['user_image'];
                            $aRows[$key]['full_name'] = $iCnt[0]['full_name']; 
                            $aRows[$key]['feed_link'] = str_replace($sFindUserName,$aRows[$key]['user_name'],$aRows[$key]['feed_link']);                     
                            $aMoreFeedRows = array();
                            if(isset($value['more_feed_rows']))
                            {
                                $aMoreFeedRows = $value['more_feed_rows'];
                            }
                            if(count($aMoreFeedRows)>0)
                            {
                                foreach ($aMoreFeedRows as $i => $item){
                                    if(isset($item['followed_id']))
                                    {
                                        if($item['user_id'] == $aProfileUser['user_id'])
                                        {
                                            $sFindUserNameMore =  $item['user_name'];
                                            $user_idMore = $item['user_followed'];
                                            $user_followedMore = $item['user_id'];
                                            $aRows[$key]['more_feed_rows'][$i]['user_id'] = $user_idMore;
                                            $aRows[$key]['more_feed_rows'][$i]['user_followed'] = $user_followedMore;

                                            $iCntMore = Phpfox::getService('followedpost')->getInforUser($user_id);
                                            $aRows[$key]['more_feed_rows'][$i]['user_name'] = $iCnt[0]['user_name'];
                                            $aRows[$key]['more_feed_rows'][$i]['user_image'] = $iCnt[0]['user_image'];
                                            $aRows[$key]['more_feed_rows'][$i]['full_name'] = $iCnt[0]['full_name']; 
                                            $aRows[$key]['more_feed_rows'][$i]['feed_link'] = str_replace($sFindUserNameMore,$aRows[$key]['user_name'],$aRows[$key]['feed_link']);   
                                            array_push($aRows,$aRows[$key]['more_feed_rows'][$i]);
                                            unset($aMoreFeedRows[$i]);
                                        }
                                        else
                                        {
                                            unset($aMoreFeedRows[$i]);
                                        }
                                    }
                                    else
                                    {
                                        unset($aMoreFeedRows[$i]);
                                    }
                                }
                                // d($aRows);//die();
                            }
                            $aRows[$key]['more_feed_rows'] = array();      
                        }
                        else
                        {
                            unset($aRows[$key]);
                        }
                    }
                    else
                    { 
                        unset($aRows[$key]);
                    }
                    $aRows[$key]['more_feed_rows'] = array();  
                }
            }
        }
    }
?>

