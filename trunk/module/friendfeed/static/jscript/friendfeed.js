

$Behavior.loadFriendFeed = function(){
    if(typeof $Core.isLoadingFriendFeed === 'undefined'){
        $Core.isLoadingFriendFeed = false;
    };

    $('#carousel li').unbind('click').bind('click',function(e){
        if(parseInt($(this).attr('val')) != 0){
            if(!$Core.isLoadingFriendFeed){
                $Core.isLoadingFriendFeed = true;
                var sFirst = $(this).text().trim().toLowerCase().substring(0,1);
                $('#section_alphabet a').removeClass('active_alpha');
                var sSector = '.section_' + sFirst;
                $(sSector).addClass('active_alpha');
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
    
	$('.mtextarea').keydown(function(){
		if($('#toggle').prop('checked')){
            $('#toggle').attr('checked',false);
            $('#toggle').change();
        } 
	});
	
	$('.activity_feed_form textarea').keydown(function(){
		if($('#toggle').prop('checked')){
            $('#toggle').attr('checked',false);
            $('#toggle').change();
        } 
	});
	
    $('.activity_feed_form textarea').unbind('click').bind('click',function(){
        if($('#toggle').prop('checked')){
            $('#toggle').attr('checked',false);
            $('#toggle').change();
        } 
    });
	
    $('.mtextarea').unbind('click').bind('click',function(){
        if($('#toggle').prop('checked')){
            $('#toggle').attr('checked',false);
            $('#toggle').change();
        } 
    });
	
	$('#btnSend.mbutton').unbind('click').bind('click',function(){
        if($('#toggle').prop('checked')){
            $('#toggle').attr('checked',false);
            $('#toggle').change();
        } 
    });
	
	$('#activity_feed_submit').unbind('click').bind('click',function(){
        if($('#toggle').prop('checked')){
            $('#toggle').attr('checked',false);
            $('#toggle').change();
        } 
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
