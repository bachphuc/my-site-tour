var bRun = false;
$Behavior.initAutoWayTime = function(){
    if(!bRun){
        bRun = true;
        $Core.waytime.begin();
    }
}