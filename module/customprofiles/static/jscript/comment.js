

$Behavior.addTagLink = function(){
    $(".comment_mini_text").each(function(index){
        if(!$(this).hasClass("user_profile_link_span")){
            var text = $(this).text();
            var reg = /\[[^\]]+\]/g;
            var match = text.match(reg);
            if(match && match.length > 0){
                var param = match[0].substring(1,match[0].length - 1);
                var aSplit = param.split("|");
                var full_name = aSplit[0];
                var user_name = aSplit[1];
                var url = aSplit[2];
                var new_text = text.replace(reg,'<span class="user_profile_link_span" id="js_user_name_link_'+user_name+'"><a href="'+url+'">'+full_name+'</a></span>');
                $(this).html(new_text);
            }
        }
    });
    
    $(".activity_feed_content_info").each(function(index){
        if($(this).find(".post_title").length > 0){
            var text = $(this).find(".post_title").text();
            $(this).find(".post_title").remove();
            $(this).prepend(text);
            if($(this).find(".user_profile_link_span").length > 0){
                var fullname = $(this).find(".user_profile_link_span").text();
                var par = $(this).closest('.row_feed_loop');
                par.find('.activity_feed_image a').attr('title', fullname);
                par.find('img').attr('alt', fullname);
            }
        }
    });
    
    $(".js_feed_add_comment_button .show_name").remove();
    $(".js_feed_add_comment_button").prepend('<div class="show_name" style="position: absolute;"><input style="position:relative;top:3px;" type="checkbox" name="val[show_your_name]" value="1"/> <label> Show your name.</label></div>');
}

$Behavior.resetTime = function(){
    var defaultValue = $('.activity_feed_form_button_position .expire_time_menu .expire_time_holder ul li:nth-child(1) a').attr('rel');
    $('.activity_feed_form_button_position .expire_time_menu .expire_time').val(defaultValue);
    $('.activity_feed_form_button_position .expire_time_menu .expire_time_holder ul a').removeClass('active_expire');
    $('.activity_feed_form_button_position .expire_time_menu .expire_time_holder ul li:nth-child(1) a').addClass('active_expire');
    
    var defaultText = $('.activity_feed_form_button_position .expire_time_menu .expire_time_holder ul li:nth-child(1) a').text();
    $('.activity_feed_form_button_position .expire_time_menu .js_hover_title .js_hover_info').text(defaultText);
}