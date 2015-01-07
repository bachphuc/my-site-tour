<?php
    if(Phpfox::isModule('strongbox'))
    {
        //$iMyPost = Phpfox::getService('strongbox')->isPost($aVals['is_via_feed']);
        //d($aVals);
        $path = Phpfox::getParam('core.path');

        $type_id = $aVals['type'];
        $item_id = $aVals['item_id'];
        $feed_id = Phpfox::getService('strongbox')->isCommentStrongBox($item_id,$type_id);
        
        $addBox = '
        setTimeout(function(){
        var isBox = $(".make_strongbox_'.$feed_id.'").length;
        if(isBox>0){
        var lenght = $(".make_strongbox_'.$feed_id.'").closest(".js_feed_comment_border").find(".comment_mini_action").length;
        var parent = $(".make_strongbox_'.$feed_id.'").closest(".js_feed_comment_border");
        if(lenght > 0){
        var sListComentId=$(".make_strongbox_'.$feed_id.'").attr(\'id\');
        var aIdComent =[];
        if(sListComentId !="")
        {
            var aIdComent = sListComentId.split("_");
        }
        for (i = 0; i < lenght; i++) { 
        var parentUL = parent.find(".comment_mini_action").eq(i).find("ul");
        var commentType = parentUL.closest(".js_mini_feed_comment");
        var idComment = commentType.attr(\'id\');
        var aIdComment = idComment.split("_");
        var idCommentCompare =aIdComment[2];
        if(aIdComent.indexOf(idCommentCompare) != -1)
        {
        if(parentUL.find(".show_strong_box").length > 0){
        parentUL.find(".show_strong_box").remove();}
        parentUL.append(\'<li id="icon_showmarkbox_\'+idComment+\'" style=" display: block;"><div style="cursor: pointer;" class="show_strong_box"><img src="'.$path.'module/strongbox/static/image/strongbox_yellow.png" alt="" id="button_image_strongbox" class="img_button" onclick="$.ajaxCall(\\\'strongbox.makePublicBoxIcon\\\',\\\'id=\'+idComment+\'&type=0\\\', \\\'GET\\\')"></div></li>\');
        parentUL.append(\'<li id="icon_markbox_\'+idComment+\'" style=" display: none;"><div style="cursor: pointer;" class="show_strong_box"><img src="'.$path.'module/strongbox/static/image/strongbox.png" alt="" id="button_image_strongbox" class="img_button" onclick="$.ajaxCall(\\\'strongbox.makeStrongBoxIcon\\\',\\\'feed='.$feed_id.'&id=\'+idComment+\'&type=0\\\', \\\'GET\\\')"></div></li>\');
        }
        else
        {
        if(parentUL.find(".show_strong_box").length > 0){
        parentUL.find(".show_strong_box").remove();}
        parentUL.append(\'<li id="icon_markbox_\'+idComment+\'" style=" display: block;"><div style="cursor: pointer;" class="show_strong_box"><img src="'.$path.'module/strongbox/static/image/strongbox.png" alt="" id="button_image_strongbox" class="img_button" onclick="$.ajaxCall(\\\'strongbox.makeStrongBoxIcon\\\',\\\'feed='.$feed_id.'&id=\'+idComment+\'&type=0\\\', \\\'GET\\\')"></div></li>\');
        parentUL.append(\'<li id="icon_showmarkbox_\'+idComment+\'" style=" display: none;"><div style="cursor: pointer;" class="show_strong_box"><img src="'.$path.'module/strongbox/static/image/strongbox_yellow.png" alt="" id="button_image_strongbox" class="img_button" onclick="$.ajaxCall(\\\'strongbox.makePublicBoxIcon\\\',\\\'id=\'+idComment+\'&type=0\\\', \\\'GET\\\')"></div></li>\');
        }
        }

        }
        }
        },100);';
        echo $addBox;
    }
?>
