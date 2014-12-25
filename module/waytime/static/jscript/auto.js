$Behavior.initWayTime = function(){
    $Core.waytime.begin();
    if(!$('#waytime_watch').length){
        $('#header_menu_holder>ul').append('<li id="waytime_watch"><a onclick="$Core.waytime.begin();"></a></li>');
    }
}