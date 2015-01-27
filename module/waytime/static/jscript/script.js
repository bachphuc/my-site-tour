
$Core.waytime = {
    begin : function(ele){
        if($('#waytime_watch_freeze').hasClass('freeze_glass')){
            return;
        }
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
        $.ajaxCall('waytime.saveAnswer','question_id=' + $('#hd_question_id').val() + '&answer_id=' + $('.radio_answer:checked').val() + '&note=' + $('.waytime_note').val());
        $Core.box('waytime.showLast',500);
        $('.js_box').addClass('waytame_box').addClass('waytame_box_green');
    },
    close : function(ele){
        if($('.waytime_bg').length){
            $('.waytime_bg').fadeOut();
        }
        $.ajaxCall('waytime.remember');
        return js_box_remove(ele);
    },
    freeze : function(ele){
        if($('.waytime_bg').length){
            $('.waytime_bg').fadeOut();
        }
        $.ajaxCall('waytime.freeze');
        return js_box_remove(ele);
    },
    exit : function(ele){
        if($('.waytime_bg').length){
            $('.waytime_bg').fadeOut();
        }
        return js_box_remove(ele);
    },
    unlock : function(ele){
        if($('.tb_unlock input:checked').length != $('.tb_unlock tbody tr').length){
            $('#waytime_error').fadeIn();
        }
        else{
            if($('.waytime_bg').length){
                $('.waytime_bg').fadeOut();
            }
            $(ele).closest('form').submit();
            js_box_remove(ele);
        }
    },
    bInit : false,
    init : function(){
        if($Core.waytime.bInit){
            return;
        }
        $Core.waytime.bInit = true;
        if(!$('.waytime_watch').length){
            if(typeof(waytime_tooltip) === 'undefined'){
                waytime_tooltip = '';
            }
            var sClass = '';
            var sAction = '$Core.waytime.begin();return false;';
            var sLink = '';
            var sId = 'waytime_watch';
            if(waytime_status == 9){
                sAction = '';
                sLink = waytime_url;
            }
            else if(waytime_status == 2){
                sAction = 'return false;';
                sClass = 'freeze_glass';
                sId = 'waytime_watch_freeze'
            }
            $('#header_menu_holder>ul').append('<li class="waytime_watch '+sClass+'" id="'+sId+'"><a href="'+sLink+'" title="'+waytime_tooltip+'" onclick="'+sAction+'"></a></li>');
        }
        if($('.waytime_bg').length == 0){
            $('body').append('<div class="waytime_bg"></div>');
        }
    },
    bStopCheck : false
}
$Behavior.initWayTime = function(){
    $Core.waytime.init();

    $('.holder_notify_drop_content').on('click', 'li a', function(e){
        if($(this).find('.waytime_noti').length > 0){
            $Core.waytime.begin();
            e.stopPropagation();
            e.preventDefault();
            return false;
        }
    });
    
    check = setInterval(function(){
        $.ajaxCall('waytime.processRunAjax');
        if($Core.waytime.bStopCheck){
            clearInterval(check);
        }
    }, 60000);
}