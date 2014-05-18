<?php
    defined('PHPFOX') or exit('NO DICE!');
?>

<?php
    $sView = Phpfox::getLib('request')->get('view');
    if((isset($sView) && $sView == 'private') || (Phpfox::getLib('request')->get('private'))  || isset($_SESSION['is_private']))
    {
        if(isset($_SESSION['is_private']))
        {
            unset($_SESSION['is_private']);
        }
        $this->_sTable = Phpfox::getT('private_feed');
    }
?>
