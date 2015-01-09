

<ul>        
    {if Phpfox::isUser() && Phpfox::isModule('like') && isset($aFeed.like_type_id)}
        {if isset($aFeed.like_item_id)}
            {module name='like.link' like_type_id=$aFeed.like_type_id like_item_id=$aFeed.like_item_id like_is_liked=$aFeed.feed_is_liked}
        {else}
            {module name='like.link' like_type_id=$aFeed.like_type_id like_item_id=$aFeed.item_id like_is_liked=$aFeed.feed_is_liked}
        {/if}    
        {if Phpfox::isUser() 
            && Phpfox::isModule('comment') 
            && Phpfox::getUserParam('feed.can_post_comment_on_feed')
            && (isset($aFeed.comment_type_id) && $aFeed.can_post_comment) 
            || (!isset($aFeed.comment_type_id) && isset($aFeed.total_comment))
            }                
        <li><span>&middot;</span></li>
        {/if}
    {/if}
    
    {if Phpfox::isModule('report') && isset($aFeed.report_module) && isset($aFeed.force_report)}
        <li><span>&middot;</span></li>    
        <li><a href="#?call=report.add&amp;height=100&amp;width=400&amp;type={$aFeed.report_module}&amp;id={$aFeed.item_id}" class="inlinePopup activity_feed_report" title="{$aFeed.report_phrase}">{phrase var='feed.report'}</a></li>                
    {/if}              
</ul>
<div class="clear"></div>        
