

$Behavior.siteTour = function(){
    if( typeof $Core.Steps === 'undefined'){
        $Core.Steps = [];
    }
    var myDomOutline = null;
    function selectDomTag(){
        myDomOutline = DomOutline({ 
            onClick: function (element) { 
                var sSector = $(element).data('sector');
                console.log(sSector);
                $(sSector).css('background-color','red');
                $(sSector).popover({
                    placement: 'auto',
                    trigger: "manual",
                    title: 'Add site tour step',
                    content: '<p style="font-size:13px;line-height:24px;">Step Title</p><input class="tb_tour_title" type="text" style="width:365px"><p style="font-size:13px;line-height:24px;">Description</p><textarea class="tb_tour_content" style="width:365px;height:100px;"></textarea>',
                    html: true,
                    container : 'body',
                    template: "<div sector='" + sSector + "' class='popover' style='max-width:400px;width:400px;'> <div class='arrow'></div> <h3 class='popover-title'></h3> <div class='popover-content'></div> <div class='popover-navigation'> <div class='btn-group'> <button class='btn btn-sm btn-default' data-role='prev'>&laquo; Prev</button> <button class='bt_next_step_setup btn btn-sm btn-default' data-role='next'>Next &raquo;</button></div><button class='btn btn-sm btn-default cancel_step_setup' data-role='can-step'>Cancel Step</button> <button class='btn btn-sm btn-default' data-role='end'>Save tour</button> </div> </div>",
                }).popover("show");
            }
        });
        myDomOutline.start();
    }

    if(!$('.cancel_step_setup').data('click')){
        $('.cancel_step_setup').live('click',function(){
            var sector = $(this).closest('.popover').attr('sector');
            $(sector).popover('destroy');
            $(this).closest('.popover').remove();
        });
        $(this).data('click',true);
    }

    $('.block_add_newtour').unbind('click').bind('click',function(){
        $(this).find('.new_tour_menu').toggle();
    });

    $('.bt_add_new_tour').unbind('click').bind('click',function(e){
        $(this).addClass('new_tour_menu_active');
        e.preventDefault();
        e.stopPropagation();
        selectDomTag();
    });

    if(!$('.bt_next_step_setup').data('click')){
        $('.bt_next_step_setup').live('click',function(){
            var stepParent = $(this).closest('.popover');
            var step = {
                element : stepParent.attr('sector'),
                title : stepParent.find('.tb_tour_title').val(),
                content : stepParent.find('.tb_tour_content').val()
            };
            $Core.Steps.push(step);
            var sector = $(this).closest('.popover').attr('sector');
            $(sector).popover('destroy');
            $(this).closest('.popover').remove();
            selectDomTag();
        });
        $(this).data('click',true);
    }

    $('.bt_preview_tour').unbind('click').bind('click',function(){
        if($Core.Steps.length > 0){
            var tour = new Tour({
                steps: $Core.Steps,
                storage : false
            });
            tour.init();
            tour.start();
        }
    });

    $('.bt_stop_setup_tour').unbind('click').bind('click',function(){
        myDomOutline.stop();
    });
}
