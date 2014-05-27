
$Behavior.friendSlider = function(){
    if(!$('#carousel').data('init')){
        carousel = $('#carousel').elastislide();
        $('#carousel').data('init',true);
    }
    else{
        carousel.refresh();
    }
}
function scrollToSection(index){

    if(carousel != null && index != -1){
        carousel.scrollToIndex(index);  
    }  
}