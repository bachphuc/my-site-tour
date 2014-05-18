$Core.changeStatusPost = function(){
    if($('#private_post').length == 0){
        $('#js_activity_feed_form').append('<input id="private_post" type="hidden" name="private" value="1">');
        $('#bt_private_feed').val('Private');
    }
    else{
        $('#private_post').remove();
        $('#bt_private_feed').val('Public');
    }
}

$Behavior.privatePost = function(){

    if($('#bt_private_feed').length == 0){
        $('.activity_feed_form_button_position>.clear').before('<input type="button" class="button" onclick="$Core.changeStatusPost();" id="bt_private_feed" value="Public" style="float:right;" />');
    }

    if(typeof $Core.bPrivateFeed !== 'undefined'){
        if(!$Core.bPrivateFeed){
            return;
        }
        
        if($('#private_page').length == 0){
            $('#js_activity_feed_form').append('<input id="private_page" type="hidden" name="private_page" value="1">');
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

            setTimeout("$.ajaxCall('feed.viewMore', $('#js_feed_pass_info').html().replace(/&amp;/g, '&') + '&iteration=" + $iReloadIteration + "&view=private', 'GET');", 1000);
        }
    }
}

