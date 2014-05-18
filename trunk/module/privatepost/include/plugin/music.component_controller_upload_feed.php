<?php
    defined('PHPFOX') or exit('NO DICE!');
?>

<?php
    if(Phpfox::getLib('request')->get('private'))
    {
        $_SESSION['is_private'] = true;
    }
    if(Phpfox::getLib('request')->get('private_page'))
    {
        $_SESSION['private_page'] = true;
    }
?>