
<?php
    if(Phpfox::isModule('followedpost'))
    {
        if(Phpfox::getLib('request')->get('followed'))
        {
            $_SESSION['is_followed'] = true;
        }
        if(Phpfox::getLib('request')->get('followed_page'))
        {
            $_SESSION['followed_page'] = true;
        }
    }
?>