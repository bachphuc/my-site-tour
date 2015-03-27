<?php
    if(Phpfox::isModule('strongbox')) : 
        $type_id =0;
        if(isset($this->_aVars['aFeed']['type_id'])) :
            $type_id = $this->_aVars['aFeed']['type_id'];
            else:
            $type_id = $this->_aVars['aFeed']['comment_type_id'];
            endif;
        $item_id = $this->_aVars['aFeed']['item_id'];
        $feed_id = Phpfox::getService('strongbox')->isPost($item_id,$type_id);
        $path = Phpfox::getParam('core.path');

    ?>
    <script type="text/javascript">
        <?php if(!PHPFOX_IS_AJAX) : ?>
            $Behavior.addBox_<?php echo $feed_id; ?> = function(){
                <?php endif; ?>
            var isBox = $(".make_strongbox_<?php echo $feed_id; ?>").length;
            if(isBox > 0){
                var lenght = $(".make_strongbox_<?php echo $feed_id; ?>").closest(".js_feed_comment_border").find(".comment_mini_action").not('.check_strong_box').length;
                var parent = $(".make_strongbox_<?php echo $feed_id; ?>").closest(".js_feed_comment_border");
                if(lenght > 0){
                    var sListComentId = $(".make_strongbox_<?php echo $feed_id; ?>").attr('id');
                    var aIdComent = [];
                    if(sListComentId != ""){
                        var aIdComent = sListComentId.split("_");
                    }
                    parent.find(".comment_mini_action").each(function(){
                        if($(this).hasClass('check_strong_box')){
                            return true;
                        }
                        $(this).addClass('check_strong_box');
                        var parentUL = $(this).find("ul");
                        var commentType = parentUL.closest(".js_mini_feed_comment");
                        var idComment = commentType.attr('id');
                        var aIdComment = idComment.split("_");
                        var idCommentCompare = aIdComment[2];
                        if(aIdComent.indexOf(idCommentCompare) != -1){
                            if(parentUL.find(".show_strong_box").length > 0){
                                parentUL.find(".show_strong_box").remove();}
                            parentUL.append('<li id="icon_showmarkbox_'+idComment+'" style=" display: block;"><div style="cursor: pointer;" class="show_strong_box js_hover_title"><span class="js_hover_info"><?php echo Phpfox::getPhrase('strongbox.remove_strong_box'); ?></span><img src="<?php echo $path; ?>module/strongbox/static/image/strongbox_yellow.png" alt="" id="button_image_strongbox" class="img_button" onclick="$.ajaxCall(\'strongbox.makePublicBoxIcon\',\'id='+idComment+'&type=0\', \'GET\')"></div></li>');
                            parentUL.append('<li id="icon_markbox_'+idComment+'" style=" display: none;"><div style="cursor: pointer;" class="show_strong_box js_hover_title"><span class="js_hover_info"><?php echo Phpfox::getPhrase('strongbox.strong_box'); ?></span><img src="<?php echo $path; ?>module/strongbox/static/image/strongbox.png" alt="" id="button_image_strongbox" class="img_button" onclick="$.ajaxCall(\'strongbox.makeStrongBoxIcon\',\'feed=<?php echo $feed_id; ?>&id='+idComment+'&type=0\', \'GET\')"></div></li>');
                        }
                        else{
                            if(parentUL.find(".show_strong_box").length > 0){
                                parentUL.find(".show_strong_box").remove();}
                            parentUL.append('<li id="icon_markbox_'+idComment+'" style=" display: block;"><div style="cursor: pointer;" class="show_strong_box js_hover_title"><span class="js_hover_info"><?php echo Phpfox::getPhrase('strongbox.strong_box'); ?></span><img src="<?php echo $path; ?>module/strongbox/static/image/strongbox.png" alt="" id="button_image_strongbox" class="img_button" onclick="$.ajaxCall(\'strongbox.makeStrongBoxIcon\',\'feed=<?php echo $feed_id; ?>&id='+idComment+'&type=0\', \'GET\')"></div></li>');
                            parentUL.append('<li id="icon_showmarkbox_'+idComment+'" style=" display: none;"><div style="cursor: pointer;" class="show_strong_box js_hover_title"><span class="js_hover_info"><?php echo Phpfox::getPhrase('strongbox.remove_strong_box'); ?></span><img src="<?php echo $path; ?>module/strongbox/static/image/strongbox_yellow.png" alt="" id="button_image_strongbox" class="img_button" onclick="$.ajaxCall(\'strongbox.makePublicBoxIcon\',\'id='+idComment+'&type=0\', \'GET\')"></div></li>');
                        }
                    });
                }
            }
        <?php if(!PHPFOX_IS_AJAX) : ?>}<?php endif; ?>
    </script>
    <?php endif; ?>