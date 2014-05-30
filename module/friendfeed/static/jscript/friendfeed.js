

$Behavior.loadFriendFeed = function(){
    if(typeof $Core.isLoadingFriendFeed === 'undefined'){
        $Core.isLoadingFriendFeed = false;
    };

    $('#carousel li').unbind('click').bind('click',function(e){
        if(parseInt($(this).attr('val')) != 0){
            if(!$Core.isLoadingFriendFeed){
                $Core.isLoadingFriendFeed = true;
                $(this).find('.friend_feed_loading').fadeIn();
                $.ajaxCall('friendfeed.viewMore','profile_user_id=' + $(this).attr('val') + '&page=0');

            }
        }
        e.preventDefault();
        e.stopPropagation();
        return false;
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
            $Core.isLoadingFriendFeed = false;
            $(".active_friend_feed").removeClass("active_friend_feed");    
            $('.active_alpha').removeClass('active_alpha');
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