$Core.changeStatusPost = function(){
    if($('#followed_post').length == 0){
        $('#js_activity_feed_form').append('<input id="followed_post" type="hidden" name="followed" value="1">');
        $('#bt_followed_feed').val('Followed');
    }
    else{
        $('#followed_post').remove();
        $('#bt_followed_feed').val('Public');
    }
}

$Behavior.followedPost = function(){

    if($('#bt_followed_feed').length == 0){
        $('.activity_feed_form_button_position>.clear').before('<input type="button" class="button" onclick="$Core.changeStatusPost();" id="bt_followed_feed" value="Public" style="float:right;" />');
    }

    if(typeof $Core.bFollowedFeed !== 'undefined'){
        if(!$Core.bFollowedFeed){
            return;
        }
        
        if($('#followed_page').length == 0){
            $('#js_activity_feed_form').append('<input id="followed_page" type="hidden" name="followed_page" value="1">');
        }

        $sViewMoreOnClick = $('#feed_view_more .global_view_more').attr('onclick');

        $Core.forceLoadOnFeed = function(){
            if ($iReloadIteration >= 2){
                return;
            }

            if (!$Core.exists('#js_feed_pass_info')){
                return;
            }

            $iReloadIteration++;
            $('#feed_view_more_loader').show();
            $('.global_view_more').hide();

            setTimeout("$.ajaxCall('feed.viewMore', $('#js_feed_pass_info').html().replace(/&amp;/g, '&') + '&iteration=" + $iReloadIteration + "&view=followed', 'GET');", 1000);
        }
    }
}

