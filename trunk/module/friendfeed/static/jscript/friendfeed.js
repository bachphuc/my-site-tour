

$Behavior.loadFriendFeed = function(){
    $('#carousel li').unbind('click').bind('click',function(e){
        $(this).find('.friend_feed_loading').fadeIn();
        $.ajaxCall('friendfeed.viewMore','profile_user_id=' + $(this).attr('val') + '&page=0');
        e.preventDefault();
        e.stopPropagation();
    });
    $('#toggle').unbind('click').bind('click',function(e){
        if($(this).prop('checked')){
            e.stopPropagation();
            e.preventDefault();
            return false;
        }
    });
    $('#toggle').unbind('change').bind('change',function(){
        if(!$(this).prop('checked')){
            scrollToSection(0);
            $('#toggle').attr('checked',false);
            $(".active_friend_feed").removeClass("active_friend_feed");    
            $Core.restoreHomeFeed();
        }
    });
    
    $('#section_alphabet a').unbind('click').bind('click',function(){
        $('#section_alphabet a').removeClass('active_alpha');
        $(this).addClass('active_alpha'); 
    });
}

$Core.backUpHomeFeed = function(){
    if($("#panel_home_feed").attr('val') == '0'){
        $("#panel_home_feed").val($("#js_feed_content").html());
        $("#panel_home_feed").attr('val','1');
    }
}

$Core.restoreHomeFeed = function(){
    $("#js_feed_content").html($("#panel_home_feed").val());
    $("#panel_home_feed").attr('val','0');
    $Core.loadInit();
}
