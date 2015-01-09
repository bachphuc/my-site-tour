


$Behavior.waytame = function(){
    $('.activity_feed_content_link_title').click(function(e){
        if($(this).closest('.js_feed_view_more_entry_holder').find('.feed_waytame').length > 0){
            var question_id = $(this).closest('.js_feed_view_more_entry_holder').find('.feed_waytame').attr('val');
            tb_remove();
            $Core.box('waytame.answerQuestion',500,'number_question=1&question_id=' + question_id);
            $('.js_box').addClass('waytame_box');
            e.preventDefault();
            e.stopPropagation();
            return false;
        } 
    });
}

function checkQuestion(element){
    var ele = $(element);
    var iLike = parseInt(ele.find('.waytame_total_like').text());
    var iDisLike = parseInt(ele.find('.waytame_total_dislike').text());
    if(iLike < 1 && iDisLike < 1){
        ele.find('.js_comment_like_holder').hide();
    }
    else{
        ele.find('.js_comment_like_holder').show();
    }
    if(iLike > 0){
        ele.find('.like_info').show();
    }
    else{
        ele.find('.like_info').hide();
    }
    
    if(iDisLike > 0){
        ele.find('.dislike_info').show();
        if(iLike > 0){
            ele.find('.dot_net').show();
        }
        else{
            ele.find('.dot_net').hide();
        }
    }
    else{
        ele.find('.dislike_info').hide();
    }
}