<?php
    defined('PHPFOX') or exit('NO DICE!');
?>

<?php
    $sView = Phpfox::getLib('request')->get('view');
    $sUserName = Phpfox::getLib('request')->get('req1');
    $aProfileUser = Phpfox::getService('user')->get($sUserName,false);
    if(isset($aProfileUser['user_id']))
    {
        if(((isset($sView) && $sView == 'private') || (Phpfox::getLib('request')->get('private'))  || isset($_SESSION['is_private'])) && Phpfox::getUserId() == $aProfileUser['user_id'])
        {
            if(isset($_SESSION['is_private']))
            {
                unset($_SESSION['is_private']);
            }
            $this->_sTable = Phpfox::getT('private_feed');
        }
    }

?>
