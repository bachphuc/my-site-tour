
<?php
    if(Phpfox::isModule('followedpost'))
    {
        $sView = Phpfox::getLib('request')->get('view');
        $sUserName = Phpfox::getLib('request')->get('req1');
        $aProfileUser = Phpfox::getService('user')->get($sUserName,false);
       
        if(isset($aProfileUser['user_id']))
        {
            if((isset($sView) && $sView == 'followed') || (Phpfox::getLib('request')->get('followed'))  || isset($_SESSION['is_followed']))
            {
               
                if(isset($_SESSION['is_followed']))
                {
                    unset($_SESSION['is_followed']);
                }
                $this->_sTable = Phpfox::getT('followed_feed');
            }
        }
    }

?>
