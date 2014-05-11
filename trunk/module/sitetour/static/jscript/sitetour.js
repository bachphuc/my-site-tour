$Core.selectDomTag = function(){
    $("*").unbind();
    $Core.siteTourMenu();
    if( typeof $Core.myDomOutline === 'undefined'){
        $Core.myDomOutline = null;
    }
    $Core.myDomOutline = DomOutline({ 
        onClick: function (element) {
            var sSector = $(element).data('sector');
            var ele = $(sSector);
            var newEle = $('<div/>').addClass('active_element');
            newEle.html('<span class="i_step">' + $Core.numberStep + '</span>');
            newEle.css({
                'left' : (ele.offset().left) + 'px',
                'top' : (ele.offset().top) + 'px',
                'width' : (ele.outerWidth() - 4) + 'px',
                'height' : (ele.outerHeight() - 4) + 'px',
            });
            $('body').append(newEle);
            
            $(sSector).popover({
                placement: 'auto',
                trigger: "manual",
                title: 'Add site tour step',
                content: '<p style="font-size:13px;line-height:24px;">Step Title</p><input class="tb_tour_title" type="text" style="width:365px"><p style="font-size:13px;line-height:24px;">Description</p><textarea class="tb_tour_content" style="width:365px;height:100px;"></textarea>',
                html: true,
                container : 'body',
                template: "<div sector='" + sSector + "' class='popover' style='max-width:400px;width:400px;'> <div class='arrow'></div> <h3 class='popover-title'></h3> <div class='popover-content'></div> <div class='popover-navigation'> <div class='btn-group'> <button class='btn btn-sm btn-default' data-role='prev'>&laquo; Prev</button> <button class='bt_next_step_setup btn btn-sm btn-default' data-role='next'>Next &raquo;</button><button class='btn btn-sm btn-default cancel_step_setup' data-role='can-step'>Cancel Step</button></div> <button class='btn btn-sm btn-default bt_save_tour' data-role='end' style='float:right;'>Save tour</button> </div> </div>",
            }).popover("show");
        }
    });
    $Core.myDomOutline.start();
}

$Core.startTour = function(){
    if( typeof $Core.Tour === 'undefined'){
        $Core.Tour = null;
    }
    if( typeof $Core.isRunningTour === 'undefined'){
        $Core.isRunningTour = false;
    }
    if(!$Core.isRunningTour){
        if($Core.Steps.length > 0){
            $Core.Tour = new Tour({
                steps: $Core.Steps,
                storage : false,
                onStart: function (tour) {
                    $Core.isRunningTour = true;
                },
                onEnd: function (tour) {
                    $Core.isRunningTour = false;
                    if($('.block_begin_tour').length > 0){
                        $('.block_begin_tour>div').removeClass('block_begin_tour_stop');
                    }
                },
                keyboard: true,
                backdrop : (typeof $Core.tourSeting === 'undefined' ? true : $Core.tourSeting.backdrop),
                duration : (typeof $Core.tourSeting === 'undefined' ? false : $Core.tourSeting.duration),
            });
            $Core.Tour.init();
            $Core.Tour.start();
        }
    }
}
$Core.siteTourMenu = function(){

    $('.cancel_step_setup').die('click').live('click',function(){
        var sector = $(this).closest('.popover').attr('sector');
        $(sector).popover('destroy');
        $(this).closest('.popover').remove();
        $Core.init();
    });

    $('.block_add_newtour').unbind('click').bind('click',function(e){
        $(this).find('.new_tour_menu').fadeToggle();
        e.stopPropagation();
        e.preventDefault();
        return false;
    });

    $('.bt_add_new_tour').unbind('click').bind('click',function(e){
        $(this).addClass('new_tour_menu_active');
        e.preventDefault();
        e.stopPropagation();
        $Core.selectDomTag();
    });

    $('.bt_next_step_setup').die('click').live('click',function(){
        var stepParent = $(this).closest('.popover');
        var step = {
            element : stepParent.attr('sector'),
            title : stepParent.find('.tb_tour_title').val(),
            content : stepParent.find('.tb_tour_content').val(),
            placement: 'auto',
            animation: true,
        };
        $Core.Steps.push(step);
        var sector = $(this).closest('.popover').attr('sector');
        $(sector).popover('destroy');
        $(this).closest('.popover').remove();
        $Core.selectDomTag();
        $Core.numberStep++;
    });

    $('.bt_preview_tour').unbind('click').bind('click',function(){
        $Core.startTour();
    });

    $('.bt_stop_setup_tour').unbind('click').bind('click',function(){
        $Core.myDomOutline.stop();
        $Core.init();
    });

    $('.bt_save_tour').unbind('click').bind('click',function(){
        if($(this).closest('.popover')){
            var sector = $(this).closest('.popover').attr('sector');
            $(sector).popover('destroy');
            $(this).closest('.popover').remove();
        }
        $Core.init();
        $Core.box('sitetour.showFormAddTour',300);  
    });

    $('#bt_save_tour').die('click').live('click',function(){
        if($Core.Steps.length == 0){
            alert('Please add some step!');
        }
        var sData = JSON.stringify($Core.Steps);
        $.ajaxCall('sitetour.addTour','data=' +sData+ '&title=' + $('#tb_tour_title').val() + '&url='+document.URL);
    });

    $('.block_begin_tour>div>div').unbind('click').bind('click',function(){
        if($('.block_begin_tour>div').hasClass('block_begin_tour_stop')){
            $('.block_begin_tour>div').removeClass('block_begin_tour_stop');
            $Core.Tour.end();
        }
        else{
            $('.block_begin_tour>div').addClass('block_begin_tour_stop');
            $Core.startTour();
        }
    });

    $('.cb_dont_show_tour').die('click').live('click',function(){

    });

    $('.bt_reset_tour').unbind('click').bind('click',function(){
        $.each($Core.Steps,function(index){
            $(this).popover('destroy');
            $Core.numberStep = 1;
        });
        
        $Core.myDomOutline.stop();
        $Core.Steps = [];
        $Core.init();
    });
}
$Behavior.siteTour = function(){
    if( typeof $Core.Steps === 'undefined'){
        $Core.Steps = [];
    }
    if( typeof $Core.numberStep === 'undefined'){
        $Core.numberStep = 1;
    }
    $Core.siteTourMenu();
}
