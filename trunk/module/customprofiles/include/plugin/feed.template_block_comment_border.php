<?php
    if(Phpfox::isModule('customprofiles'))
    {
        if(isset($this->_aVars['aFeed']['comments']))
        {
            foreach($this->_aVars['aFeed']['comments'] as $commentKey => $aComment)
            {
                if(!isset($aComment['is_check']))
                {
                    $this->_aVars['aFeed']['comments'][$commentKey]['full_name'] = Phpfox::getPhrase('customprofiles.a_wayter_commented');
                    $this->_aVars['aFeed']['comments'][$commentKey]['owner_user_id'] = $aComment['user_id'];
                    $this->_aVars['aFeed']['comments'][$commentKey]['user_id'] = 0;
                    $this->_aVars['aFeed']['comments'][$commentKey]['user_image'] = "";
                    $this->_aVars['aFeed']['comments'][$commentKey]['user_name'] = "";
                }
            }
        }

        $sHtml = '<script type="text/javascript">
        $Behavior.addExtra'.$this->_aVars['aFeed']['feed_id'].' = function(){
        var ele = $("#js_feed_comment_post_'.$this->_aVars['aFeed']['feed_id'].' .comment_mini_link_block:not(.comment_mini_link_block_hidden)");
        if(ele.length > 0){
        var onclickEvent = ele.attr("onclick");
        if(typeof onclickEvent !== "undefined"){
        onclickEvent = onclickEvent.replace("comment.viewMoreFeed","customprofiles.viewMoreFeed");
        ele.attr("onclick",onclickEvent);
        }
        }
        var precomment = $("#js_feed_comment_post_'.$this->_aVars['aFeed']['feed_id'].' .pager_view_more");
        if(precomment.length > 0){
        var onclickEvent = precomment.attr("onclick");
        if(typeof onclickEvent !== "undefined"){
        onclickEvent = onclickEvent.replace("comment.viewMoreFeed","customprofiles.viewMoreFeed");
        precomment.attr("onclick",onclickEvent);
        }
        }
        }
        </script>';
        echo $sHtml;
    }
?>
