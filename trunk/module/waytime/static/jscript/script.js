
$Core.waytime = {
    begin : function(ele){
        if($('.waytime_bg').length == 0){
            $('body').append('<div class="waytime_bg"></div>');
        }
        
        if(ele){
            js_box_remove(ele);
        }
        $('.waytime_bg').fadeIn();
        $Core.box('waytime.start',500);
        $('.js_box').addClass('waytame_box');
    },
    start : function(ele){
        if($('.waytime_bg').length == 0){
            $('body').append('<div class="waytime_bg"></div>');
        }
        if(ele){
            js_box_remove(ele);
        }
        $Core.box('waytime.showFirst',500);
        $('.js_box').addClass('waytame_box').addClass('waytame_box_green');
    },
    goNext : function(index, ele){
        if(!$('.radio_answer:checked').length && index > 1){
            return false;
        }
        if(ele){
            js_box_remove(ele);
        }
        if(index > 1){
            $.ajaxCall('waytime.saveAnswer','question_id=' + $('#hd_question_id').val() + '&answer_id=' + $('.radio_answer:checked').val() + '&note=' + $('.waytime_note').val());
        }
        
        $Core.box('waytime.showNext',500, 'index=' + index);
        $('.js_box').addClass('waytame_box');
    },
    goPre : function(index, ele){
        if(ele){
            js_box_remove(ele);
        }
        $Core.box('waytime.showNext',500, 'index=' + index);
        $('.js_box').addClass('waytame_box');
    },
    last : function(ele){
        if(ele){
            js_box_remove(ele);
        }
        $Core.box('waytime.showLast',500);
        $('.js_box').addClass('waytame_box').addClass('waytame_box_green');
    },
    close : function(ele){
        if($('.waytime_bg').length){
            $('.waytime_bg').fadeOut();
        }
        $.ajaxCall('waytime.remember');
        return js_box_remove(ele);
    }
}
$Behavior.initWayTime = function(){
    if(!$('#waytime_watch').length){
        $('#header_menu_holder>ul').append('<li id="waytime_watch"><a onclick="$Core.waytime.begin();"></a></li>');
    }
}