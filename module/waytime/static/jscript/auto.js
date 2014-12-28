var bRun = false;
$Behavior.initWayTime = function(){
    if(!bRun){
        bRun = true;
        $Core.waytime.begin();
    }
}