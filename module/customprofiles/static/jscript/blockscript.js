$Behavior.blockmessage = function(){
    var option = {
        width: 350,
        title : 'A specific time',
        dialogClass : 'custom_dialog',
        autoOpen: false,
        draggable: true,
        modal: true,
        resizable: false,
        close: function() {
            isEditSchedule();
        },
        draggable : false,
        hide: { effect: "fade", duration: 2000 }
    };
    var bSetPosition = false;
    if(!$("#dialog_calender").data('init-dialog')){
        $("#dialog_calender").data('init-dialog',true)
    }
    else{
        if($("#dialog_calender").dialog( "isOpen" )){
            option.autoOpen = true;
        }
    }
    $("#dialog_calender").dialog('destroy').dialog(option);

    var option = {
        title : 'Edit schedule',
        autoOpen: false,
        dialogClass : 'custom_dialog',
        draggable: true,
        modal: true,
        resizable: false,
        close: function() {
            isEditSchedule();
        },
        draggable : false
    };
    var bSetPosition = false;
    if(!$("#dialog_edit").data('init-dialog')){
        $("#dialog_edit").data('init-dialog',true)
    }
    else{
        if($("#dialog_edit").dialog( "isOpen" )){
            option.autoOpen = true;
        }
    }
    $("#dialog_edit").dialog('destroy').dialog(option);


    var option = {
        autoOpen: false,
        title : 'Delete schedule',
        dialogClass : 'custom_dialog',
        draggable: true,
        modal: true,
        resizable: false,
        close: function() {
            isEditSchedule();
        },
        draggable : false
    };
    var bSetPosition = false;
    if(!$("#dialog_delete").data('init-dialog')){
        $("#dialog_delete").data('init-dialog',true)
    }
    else{
        if($("#dialog_delete").dialog( "isOpen" )){
            option.autoOpen = true;
        }
    }
    $("#dialog_delete").dialog('destroy').dialog(option);

    $('#button_clock').click(function(){
        var title = $(this).val();
        if(title != 'Edit'){
            showPickerTime();
        } else {
            editColorSchedule();
            $("#dialog_edit").dialog("open");
        }
    });

    $('#edit_schedule').click(function(){
        $("#dialog_edit").dialog("close");
        $('#error_time').hide();
        $("#dialog_calender").dialog("open");
        editColorSchedule();
    });
    $('#delete_buttonschedule').click(function(){

        $("#dialog_edit").dialog("close");
        $("#dialog_delete").dialog('open');
        editColorSchedule();
    });
    $('#delete_time').click(function(){
        setTextButtonClock('Time');
        $('#schedule_time_value').val('0');
        $('#button_clock').attr('title', 'Schedule your post in the future!');
        $("#dialog_delete").dialog('close');
        $('#label_time_future').hide(); 
        editColorSchedule();
    });

    $('#show_calender').click(function(){
        var max_time = $('#limit_time_schedule').val();
        var d = new Date();
        var year = d.getFullYear();
        d.setFullYear(year);
        var sMaxday = '+'+max_time+'D';
        $( "#dialog" ).dialog( "close" );
        $("#dialog_calender").dialog("open");
        $( "#datepicker" ).datepicker({
            changeMonth: true,
            changeYear: true,
            minDate: '0',
            maxDate: sMaxday, 
            dateFormat: 'dd-mm-yy',

        });
        $('#datepicker').val(d.getDate()+"-"+(d.getMonth()+1)+'-'+year);
        $('#hour_input').focus();
        $('#datepicker').blur();
    });

    $("#time_hour").change(function(){ 
        $('#error_time').hide();
    });
    $("#time_minute").change(function(){ 
        $('#error_time').hide();
    });
    $('#datepicker').click(function(){
        $('#error_time').hide();
    });

    $('#button_randomies').click(function(){
        var hour  = Math.floor((Math.random() * 24));
        var minute = Math.floor((Math.random() * 60));

        var d = new Date();

        var month = d.getMonth()+1;
        var day = d.getDate()+1;
        var year = d.getFullYear();
        var maxday = 0;
        if(month == 1||month == 3||month == 5 || month == 7|| month == 8|| month == 10|| month ==12){
            maxday = 31;
        } else {
            if(month == 2){
                maxday = 28;
            }else {
                maxday = 30;
            }
        }
        var monthDay =[];
        if(day >= maxday){
            day = maxday;
        }
        for( var i= day ; i< maxday; i++)
        {
            var item = i+'-'+month;
            monthDay.push(item);
        }
        month+=1;
        if(month == 13){
            month = 1;
        }
        for(var i= 1 ; i<=day; i++){
            var item = i+'-'+month;
            monthDay.push(item);
        }
        var iTimeRand = Math.floor((Math.random() * monthDay.length));
        var aTime = monthDay[iTimeRand].split("-");

        var sFullTime = hour + '-' + minute +'-0-'+aTime[0] + '-'+aTime[1] +'-'+year;
        var deltaTime = getTimeSchedule(year,aTime[1],aTime[0],hour,minute,0);

        if(deltaTime > 0){
            var sHour = hour;
            if(hour < 10){
                sHour = '0'+sHour;
            }
            var sMinute = minute;
            if(minute < 10){
                sMinute = '0' +sMinute;
            }    
            $('#schedule_time_value').val(deltaTime);
            var message = 'Message will be send on a random date in the future.';
            $('#label_time_future').text(message);
            $('#label_time_future').show(); 
            $("#dialog_calender").dialog("close");
            changeColorSchedule();
        }
        else{
            $('#error_time').show();
        }

    });

    $('#button_schedule').click(function(){
        var day = $("#datepicker").val()? $("#datepicker").datepicker('getDate').getDate() : null ;  
        if(day != null){
            $('#label_time_future').show(); 
            var month = $("#datepicker").datepicker('getDate').getMonth()+1;  
            var year = $("#datepicker").datepicker('getDate').getFullYear();
            var hour = $('#time_hour').val();
            var minute = $('#time_minute').val();
            var sFullTime = hour+ '-'+ minute +'-0-'+month+'-'+day+'-'+year; 
            var deltaTime = getTimeSchedule(year,month,day,hour,minute,0);

            if(deltaTime > 0){
                var sHour = hour;
                if(hour < 10){
                    sHour = '0'+sHour;
                }
                var sMinute = minute;
                if(minute < 10){
                    sMinute = '0' +sMinute;
                }    
                $('#schedule_time_value').val(deltaTime);
                var message = 'Message will be send on '+day+'/'+month+'/'+year+ ' at '+sHour+':'+sMinute+'.';
                $('#label_time_future').text(message);
                $("#dialog_calender").dialog("close");
                changeColorSchedule();
            }
            else{
                $('#error_time').show();
            }
        }
        else{
            $('#error_time').show();
        }
    });
}

function changeColorSchedule(){
    $('#button_clock').attr('title', 'Your post has been scheduled in the future!');
    setTextButtonClock('Edit');
    $('#button_clock').addClass('active_option');
}

function editColorSchedule(){
    $('#button_clock').removeClass('active_option');
}

function setTextButtonClock($str){
    $('#button_clock').attr('value',$str);
}

function isEditSchedule(){
    var title = $('#button_clock').val();
    if(title == 'Edit'){
        changeColorSchedule();
    }
}

function getTimeSchedule(year,month,day,hour,minutes,seconds){
    var schedule = new Date(year,month-1,day,hour,minutes,seconds).getTime();
    var time = new Date().getTime();
    var timeSchedule = Math.round((schedule - time)/1000);
    return timeSchedule;
}

function showPickerTime(){
    $('#error_time').hide();
    var max_time = $('#limit_time_schedule').val();
    var d = new Date();
    var year = d.getFullYear();
    d.setFullYear(year);
    var sMaxday = '+'+max_time+'D';
    $("#dialog_calender").dialog("open");
    $( "#datepicker" ).datepicker({
        changeMonth: true,
        changeYear: true,
        minDate: '0',
        maxDate: sMaxday, 
        dateFormat: 'dd-mm-yy',

    });
    $('#datepicker').val(d.getDate()+"-"+(d.getMonth()+1)+'-'+year);
    $('#hour_input').focus();
    $("#time_hour").prop("selectedIndex", 0);
    $("#time_minute").prop("selectedIndex", 0);
    $('#datepicker').blur();
}