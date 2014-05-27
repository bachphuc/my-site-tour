var carousel;
$Behavior.friendSlider = function(){
    carousel = $('#carousel').elastislide();  
}
function scrollToSection(index){

    if(carousel != null && index != -1){
        carousel.scrollToIndex(index);  
    }  
}