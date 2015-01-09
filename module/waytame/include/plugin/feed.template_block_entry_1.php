<?php
    if(Phpfox::isModule('waytame'))
    {
        if($this->_aVars['aFeed']['type_id'] == 'waytame')
        {
            echo '<span val="'.$this->_aVars['aFeed']['item_id'].'" class="feed_waytame"></span>';
        }
    }
?>
