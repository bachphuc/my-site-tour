$Core.changeStatusPost = function(){
    if($('#private_post').length == 0){
        $('#js_activity_feed_form').append('<input id="private_post" type="hidden" name="private" value="1">');
        $('#bt_private_feed a').text('Public');
    }
    else{
        $('#private_post').remove();
        $('#bt_private_feed a').text('Private');
    }
}

$Behavior.privatePost = function(){
    
    if($('#bt_private_feed').length == 0){
        $('.activity_feed_form_share').append('<div onclick="$Core.changeStatusPost();" id="bt_private_feed" style="line-height:24px;height:24px;position:absolute;top:0px;cursor:pointer;right:20px;font-weight:bold;font-size:12px;"><a href="javascript:void(0);">Private</a></div>');
    }
}