
$Core.checkAnonymousStatus = function(){
    if($('.mtextarea').val() != ''){
        $bButtonSubmitActive = true;
    }
    else{
        $bButtonSubmitActive = false;
    }
}
$Core.searcbFriends = {
    targetSector : '',
    aResult : [],

    friendClickCallBack : function(aParam){return false},
    loadFriend : function(){return false},

    init : function(aParam){
        var dis = this;
        if(typeof aParam.targetSector !== 'undefined'){
            dis.targetSector=aParam.targetSector;
            targetSector = dis.targetSector;
        }   
        else{
            return false;
        }

        if(typeof aParam.loadFriend !== 'undefined'){
            dis.loadFriend=aParam.loadFriend;
            dis.loadFriend();
        }   
        else{
            return false;
        }

        if(typeof aParam.friendClickCallBack !== 'undefined'){
            dis.friendClickCallBack = aParam.friendClickCallBack;
        }

        $(targetSector).unbind('focus').bind('focus',function(){
            if($('.result_tag_friend_panel').length>0){
                return;
            }

            var sHtml = '<div class="result_tag_friend_panel"></div>';
            $(targetSector).parent().append(sHtml);
            if(typeof $(targetSector).parent().css('position') === 'undefined'){
                $(targetSector).parent().css('position','relative');
            }
            else{
                if($(targetSector).parent().css('position') != 'relative' && $(targetSector).parent().css('position') != 'absolute'){
                    $(targetSector).parent().css('position','relative');
                }
            }
            $('.result_tag_friend_panel').css({
                'top' : ($(targetSector).position().top + $(targetSector).outerHeight() + 1) + 'px',
                'left' : ($(targetSector).position().left) + 'px',
                'width' : ($(targetSector).outerWidth() - 2) + 'px'
            });
            $(targetSector).unbind('keyup').bind('keyup', function() {
                dis.search($(this).val());
            });
            $(this).unbind('focus');
        });

        $(targetSector).unbind('keyup').bind('keyup', function() {
            dis.search($(this).val());
        });

        $('.sendbox').on('click','.friend_tag_item',function(){
            dis.friendClickCallBack($(this)); 
            dis.hide();
        });  

        $(document).click(function(){
            $('.result_tag_friend_panel').hide();
        });    
    },
    search : function(keyword){
        if(typeof $Core.friendTagCache !== 'undefined'){
            var dis = this;
            dis.aResult = [];
            if(keyword == ''){
                this.hide();
                return;
            }
            $.each($Core.friendTagCache,function(key,value){
                if(value.full_name.toLowerCase().indexOf(keyword.toLowerCase()) != -1){
                    dis.aResult.push(value);
                }
            });
        }
        if(this.aResult.length>0){
            this.build();
            this.show();
        }
        else{
            if(keyword != ''){
                this.build();
                this.show();
            }
            else{
                this.hide();
            }
        }
    },
    build : function(){
        var sHtml = '';
        if(this.aResult.length > 0){
            var dis = this;
            $.each(this.aResult,function(key,value){
                sHtml+='<div class="friend_tag_item" val="'+value.user_id+'">';
                sHtml+='<div class="friend_tag_item_avatar"><img src="' +value.user_image+ '" ></div>';
                sHtml+='<div class="friend_tag_item_info"><p>'+value.full_name+'</p></div>';
                sHtml+='<div style="clear:both;"></div>';
                sHtml+='</div>';
            });  
        }
        $('.result_tag_friend_panel').html(sHtml);
        $('.result_tag_friend_panel').append('<div class="friend_tag_item find_more"><div class="friend_tag_item_info"><p>Write to another wayter</p></div></div>');
    },
    show : function(){
        $('.result_tag_friend_panel').show();
    },
    hide : function(){
        $('.result_tag_friend_panel').hide();
    }
}

$Behavior.sendBox = function(){
    $Core.searcbFriends.init({
        targetSector : '#tb_friend',
        loadFriend : function(){
            if(typeof $Core.friendTagCache === 'undefined'){
                $.ajaxCall('customprofiles.getFriend'); 
            } 
        },
        friendClickCallBack : function(element){
            if(!element.hasClass('find_more')){
                var tagFriend = $('<div class="receive_friend_item"><input type="hidden" value="'+element.attr('val')+'" name="val[friend_id]"><img src="'+element.find('img').attr('src')+'"><span>'+element.text()+'<span><span class="remove_receive_friend_item">X</span></div>').insertBefore('.tag_panel_clear');
                if($('.tag_panel .receive_friend_item').length > 0){
                    $('#tb_friend').hide();
                }
                else{
                    $('#tb_friend').show();
                }
                $('.email_panel').hide();
                $('#cb_is_friend').val(0);
            }
            else{
                $('.email_panel').show();
                $('#cb_is_friend').val(1);
            }
        },
    });

    $('.sendbox').on('click','.remove_receive_friend_item',function(){
        $(this).closest('.receive_friend_item').remove(); 
        if($('.tag_panel .receive_friend_item').length > 0){
            $('#tb_friend').hide();
        }
        else{
            $('#tb_friend').show();
        }
    });

    $('.sendbox').on('focus','#tb_email',function(e){
        $('#lb_error_invalid_email').fadeOut();
        $('#lb_error_email_empty').fadeOut();
        $('#lb_error_empty_user').fadeOut();
    });
    $('.sendbox').on('focus','#message',function(e){
        $('#tb_error_invalid_message').fadeOut();
        $('#lb_error_empty_user').fadeOut();
        $('.controll_panel').fadeIn();
    });
    $('.sendbox').on('focus','#tb_friend',function(e){
        $('#lb_error_invalid_name').fadeOut();
        $('#lb_error_empty_user').fadeOut();
    });
    $('.sendbox').on('blur','#tb_email',function(e){
        if($(this).val() == ''){
            $('#lb_error_invalid_email').fadeOut();
            return;
        }
        if(!$Core.checkEmailValid($(this).val())){
            $('#lb_error_invalid_email').fadeIn();
        }
        else{
            $('#lb_error_invalid_email').fadeOut();
        }
    });

    $('.activity_feed_form_attach li a').click(function(){
        if($(this).attr('rel') == 'global_attachment_customprofiles'){
            $('.activity_feed_form_button').addClass('hide_default_button_submit');
        } 
        else{
            $('.activity_feed_form_button').removeClass('hide_default_button_submit'); 
        }
    });

    // ducloi.bm@gmail.com
    $(".sendbox").on('click','.remove_button',function() {
        $('#egift_id').val('');
        $(this).closest('.gift_item').remove();
    });
}

$Core.resetAnonymousPost = function(){
    $('#message').val('');
    $('#tb_friend').val('');
    $('#egift_selected').html('');
    $('#tb_friend').show();
    $('.email_panel').hide();
    $('.anonymous_loading').fadeOut();
    $('.receive_friend_item').remove();
    $('#button_clock').removeClass('active_option');
    $('#button_clock').attr('value','Time');
    $('#schedule_time_value').val('0');
    $('#button_clock').attr('title', 'Schedule your post in the future!');
    $("#dialog_delete").dialog('close');
    $('#label_time_future').hide(); 
    $('#egift_id').val('');
    $('.invalid_email').fadeOut();
    $('.controll_panel').fadeOut();
}

$Core.checkEmailValid = function(email){
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test(email);
}

// Send gift
function showGiftsByCategory(){
    var $sName = $('#selectCategory option:selected').val().toLowerCase();
    $('.egift_category_holder').hide();
    $('#egift_item_cat_'+$sName).show();
}

// ducloi.bm@gmail.com
function setEgift(eGiftId, element)
{ 
    $('.egift_item').each(function(){
        $(this).removeClass('eGiftHighlight');
    });
    if ($('#egift_id').val() == eGiftId){      
        $('#egift_id').val('');
    }
    else{
        $('#egift_item_'+eGiftId).addClass('eGiftHighlight');
        $('#egift_id').val(eGiftId);  

        var htmlTag = '<div class="gift_item"><img src="'+ $(element).find('img').attr('src')+'"><span class="remove_button">X</span><div>';

        $("#egift_selected").html(htmlTag);
    }
}

function loadGift(){
    showGiftsByCategory();
    if ($('#egift_id').val() != ""){
        $('#egift_item_'+$('#egift_id').val()).addClass('eGiftHighlight');
    }
}

$Core.checkData = function(){
    var bPass = true;
    if($('#message').val() == '' || $('#message').val() == ' '){
        bPass = false;
        $('#tb_error_invalid_message').fadeIn();

    }
    if(($('#tb_email').val() == '' || $('#tb_email').val() == ' ') && $('#tb_email').is(':visible')){
        bPass = false;
        $('#lb_error_invalid_email').hide();
        $('#lb_error_email_empty').fadeIn();
    }

    if(($('#tb_friend').val() == '' || $('#tb_friend').val() == ' ') && $('#tb_friend').is(':visible')){
        bPass = false;
        $('#lb_error_invalid_name').fadeIn();
    }
    if(($('#tb_email').val() != '' && $('#tb_email').val() != ' ') && $('#tb_email').is(':visible')){
        if(!$Core.checkEmailValid($('#tb_email').val())){
            bPass = false;
            $('#lb_error_invalid_email').fadeIn();
        }
        else{
            $('#lb_error_invalid_email').fadeOut();
        }
    }
    if(bPass){
        if(!$Core.checkEmailValid($('#tb_email').val()) && $('.receive_friend_item').length == 0){
            $('#lb_error_empty_user').fadeIn();
            bPass = false;
        }
    }
    
    return bPass;
}
    
    