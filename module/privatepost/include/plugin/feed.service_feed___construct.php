<?php
    defined('PHPFOX') or exit('NO DICE!');
?>

<?php
    $sView = Phpfox::getLib('request')->get('view');
    if($sView && $sView == 'private')
    {
        $this->_sTable = Phpfox::getT('private_feed');
    }
?>
