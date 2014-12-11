<?php
    if(Phpfox::isModule('customprofiles'))
    {
        if(isset($aVals['show_your_name']))
        {
            $aVals['text'] = '['.Phpfox::getUserBy('full_name').'|'.Phpfox::getUserBy('user_name').'|'.Phpfox::getLib('url')->makeUrl(Phpfox::getUserBy('user_name')).'] '.$aVals['text'];
        }

        if ($aVals['type'] == 'profile' && !Phpfox::getService('user.privacy')->hasAccess($aVals['item_id'], 'comment.add_comment'))
        {
            $this->html('#js_comment_process', '');
            $this->call("$('#js_comment_submit').removeAttr('disabled');");                                
            $this->alert(Phpfox::getPhrase('bulletin.you_do_not_have_permission_to_add_a_comment_on_this_persons_profile'));

            return false;
        }        

        if ($aVals['type'] == 'group' && (!Phpfox::getService('group')->hasAccess($aVals['item_id'], 'can_use_comments', true)))
        {
            $this->html('#js_comment_process', '');
            $this->call("$('#js_comment_submit').removeAttr('disabled');");                    
            $this->alert(Phpfox::getPhrase('bulletin.only_members_of_this_group_can_leave_a_comment'));     

            return false;            
        }

        if (!Phpfox::getUserParam('comment.can_comment_on_own_profile') && $aVals['type'] == 'profile' && $aVals['item_id'] == Phpfox::getUserId() && empty($aVals['parent_id']))
        {
            $this->html('#js_comment_process', '');
            $this->call("$('#js_comment_submit').removeAttr('disabled');");                    
            $this->alert(Phpfox::getPhrase('comment.you_cannot_write_a_comment_on_your_own_profile'));

            return false;
        }

        if (($iFlood = Phpfox::getUserParam('comment.comment_post_flood_control')) !== 0)
        {
            $aFlood = array(
                'action' => 'last_post', // The SPAM action
                'params' => array(
                    'field' => 'time_stamp', // The time stamp field
                    'table' => Phpfox::getT('comment'), // Database table we plan to check
                    'condition' => 'type_id = \'' . Phpfox::getLib('database')->escape($aVals['type']) . '\' AND user_id = ' . Phpfox::getUserId(), // Database WHERE query
                    'time_stamp' => $iFlood * 60 // Seconds);    
                )
            );

            // actually check if flooding
            if (Phpfox::getLib('spam')->check($aFlood))
            {    
                if (isset($aVals['is_via_feed']))
                {        
                    $this->call('$(\'#js_feed_comment_form_' . $aVals['is_via_feed'] . '\').find(\'.js_feed_add_comment_button:first\').show();');
                    $this->call('$(\'#js_feed_comment_form_' . $aVals['is_via_feed'] . '\').find(\'.js_feed_comment_process_form:first\').hide();');
                }
                else 
                {                
                    $this->html('#js_comment_process', '');
                    $this->call("$('#js_comment_submit').removeAttr('disabled');");
                }

                $this->alert(Phpfox::getPhrase('comment.posting_a_comment_a_little_too_soon_total_time', array('total_time' => Phpfox::getLib('spam')->getWaitTime())));

                return false;
            }        
        }

        if (Phpfox::getLib('parse.format')->isEmpty($aVals['text'])
            || (isset($aVals['default_feed_value']) && $aVals['default_feed_value'] == $aVals['text']))
        {            
            if (isset($aVals['is_via_feed']))
            {        
                $this->call('$(\'#js_feed_comment_form_' . $aVals['is_via_feed'] . '\').find(\'.js_feed_add_comment_button:first\').show();');
                $this->call('$(\'#js_feed_comment_form_' . $aVals['is_via_feed'] . '\').find(\'.js_feed_comment_process_form:first\').hide();');
            }
            else 
            {                
                $this->html('#js_comment_process', '');
                $this->call("$('#js_comment_submit').removeAttr('disabled');");
            }            

            $this->alert(Phpfox::getPhrase('comment.add_some_text_to_your_comment'));
            $this->hide('.js_feed_comment_process_form');

            return false;
        }

        if (Phpfox::isModule('captcha') && !isset($bNoCaptcha) && Phpfox::getUserParam('captcha.captcha_on_comment') && !Phpfox::getService('captcha')->checkHash($aVals['image_verification']))
        {
            $bPassCaptcha = false;
            $this->call("$('#js_captcha_image').ajaxCall('captcha.reload', 'sId=js_captcha_image&sInput=image_verification');");            
            $this->alert(Phpfox::getPhrase('captcha.captcha_failed_please_try_again'), Phpfox::getPhrase('core.error'));
            if (Phpfox::getParam('core.wysiwyg') == 'tinymce' && Phpfox::getParam('core.allow_html'))
            {
                $this->call("tinyMCE.execCommand('mceSetContent',false, '" . str_replace("'", "\'", $aVals['text']) . "');");
            }
        }

        if ($bPassCaptcha)
        {
            if (($mId = Phpfox::getService('comment.process')->add($aVals)) === false)
            {                
                $this->html('#js_comment_process', '');
                $this->call("$('#js_comment_submit').removeAttr('disabled');");
                $this->hide('.js_feed_comment_process_form');
                $this->val('.js_comment_feed_textarea', '');
                // $this->alert(Phpfox::getPhrase('comment.cannot_comment_on_this_item_as_it_does_not_exist_any_longer'));        

                if (isset($aVals['is_via_feed']))
                {
                    $this->hide('#js_feed_comment_form_' . $aVals['item_id'])->show('#js_feed_comment_form_mini_' . $aVals['item_id']);
                }

                return false;
            }

            $this->hide('#js_captcha_load_for_check');

            // Comment requires moderation
            if ($mId == 'pending_moderation')
            {
                $this->call("$('#js_comment_form')[0].reset();");
                $this->alert(Phpfox::getPhrase('comment.your_comment_was_successfully_added_moderated'));
            }
            else 
            {    
                $this->call('if (typeof(document.getElementById("js_no_comments")) != "undefined") { $("#js_no_comments").hide(); }');

                $aRow = Phpfox::getService('comment')->getComment($mId);    
                
                $iNewTotalPoints = (int) Phpfox::getUserParam('comment.points_comment');
                $this->call('if ($Core.exists(\'#js_global_total_activity_points\')){ var iTotalActivityPoints = parseInt($(\'#js_global_total_activity_points\').html().replace(\'(\', \'\').replace(\')\', \'\')); $(\'#js_global_total_activity_points\').html(iTotalActivityPoints + ' . $iNewTotalPoints . '); }');

                if (isset($aVals['is_via_feed']))
                {
                    // hide user information
                    $aRow['owner_user_id'] = $aRow['user_id'];
                    $aRow['full_name'] = Phpfox::getPhrase('customprofiles.a_wayter_commented');
                    $aRow['user_id'] = 0;
                    $aRow['user_image'] = "";
                    $aRow['user_name'] = "";
                    $aRow['is_check'] = true;
                    // end hide user information
                    $aNonymousFeed = Phpfox::getService('customprofiles')->getAnonymousFeed($aVals['is_via_feed']);
                    $aPassData = array(
                        'aComment' => $aRow, 
                        'bForceNoReply' => true
                    );
                    if(isset($aNonymousFeed['anonymous_id']))
                    {
                        $aFeed['is_anonymous'] = true;
                        $aFeed['parent_user_id'] = $aNonymousFeed['receive_user_id'];
                    }
                    else
                    {
						$sType = (isset($aVals['type']) && ($aVals['type'] == 'event' || $aVals['type'] == 'pages') ? $aVals['type'] : null);
                        $aTempFeed = Phpfox::getService('customprofiles')->getFeed($aVals['is_via_feed'] , $sType);
                        $aFeed['is_anonymous'] = false;
                        $aFeed['owner_user_id'] = $aTempFeed['user_id'];
                    }
                    $aPassData['aFeed'] = $aFeed;
                    Phpfox::getLib('parse.output')->setImageParser(array('width' => 200, 'height' => 200));
                    Phpfox::getLib('template')->assign($aPassData)->getTemplate('comment.block.mini');
                    Phpfox::getLib('parse.output')->setImageParser(array('clear' => true));                    

                    $sId = 'js_tmp_comment_' . md5('comment_' . uniqid() . Phpfox::getUserId()) . '';

                    if (isset($aVals['parent_id']) && $aVals['parent_id'] > 0)
                    {
                        $this->html('#js_comment_form_holder_' . $aVals['parent_id'], '');
                        $this->append('#js_comment_children_holder_' . $aVals['parent_id'], '<div id="' . $sId . '">' . $this->getContent(false) . '</div>');

                        if (Phpfox::getParam('core.wysiwyg') == 'tiny_mce')
                        {
                            if (isset($aVals['is_in_view']))
                            {
                                $this->call('Editor.setContent(\'\');');
                            }
                            else
                            {
                                $this->call('$(\'#js_feed_comment_form_textarea_' . $aVals['is_via_feed'] .'\').val($(\'.js_comment_feed_value\').html()).addClass(\'js_comment_feed_textarea_focus\').removeAttr(\'style\');');                    
                            }    

                            $this->call('$(\'#js_feed_comment_form_textarea_' . $aVals['is_via_feed'] .'\').parent().find(\'.js_feed_comment_process_form:first\').hide();');                            
                        }
                    }                    
                    else
                    {
                        if (isset($aVals['is_in_view']))
                        {
                            $this->call('Editor.setContent(\'\');');
                        }
                        else
                        {
                            $this->call('$(\'#js_feed_comment_form_textarea_' . $aVals['is_via_feed'] .'\').val($(\'.js_comment_feed_value\').html()).addClass(\'js_comment_feed_textarea_focus\').removeAttr(\'style\');');                    
                        }

                        $this->call('$(\'#js_feed_comment_form_textarea_' . $aVals['is_via_feed'] .'\').parent().find(\'.js_feed_comment_process_form:first\').hide();');                        
                        $this->append('#js_feed_comment_post_' . $aVals['is_via_feed'], '<div id="' . $sId . '">' . $this->getContent(false) . '</div>');
                    }

                    // $this->call('$(\'#' . $sId . '\').highlightFade();');                    
                }
                else 
                {
                    Phpfox::getLib('parse.output')->setImageParser(array('width' => 500, 'height' => 500));
                    Phpfox::getLib('template')->assign(array('aRow' => $aRow, 'bCanPostOnItem' => false))->getTemplate('comment.block.entry');                
                    Phpfox::getLib('parse.output')->setImageParser(array('clear' => true));

                    if (isset($aVals['parent_id']) && $aVals['parent_id'] > 0)
                    {
                        $this->call("$('#js_comment_form_{$aVals['parent_id']}').slideUp(); $('#js_comment_form_form_{$aVals['parent_id']}').html(''); $('#js_comment_parent{$aVals['parent_id']}').html('<div style=\"margin-left:30px;\">" . $this->getContent() . "</div>' + $('#js_comment_parent{$aVals['parent_id']}').html()).slideDown(); $('#js_comment_form')[0].reset();");
                    }
                    else 
                    {
                        $this->call("$('#js_new_comment').html('" . $this->getContent() . "' + $('#js_new_comment').html()).slideDown(); $.scrollTo('#js_new_comment', 800); $('#js_comment_form')[0].reset();");
                    }

                    $this->call('$(\'#js_comment' . $aRow['comment_id'] . '\').find(\'.valid_message:first\').show().fadeOut(5000);');    
                }
            }

            if (!isset($aVals['is_via_feed']) && Phpfox::isModule('captcha') && Phpfox::getUserParam('captcha.captcha_on_comment') && !isset($bNoCaptcha))
            {
                $this->call("$('#js_captcha_image').ajaxCall('captcha.reload', 'sId=js_captcha_image&sInput=image_verification');");
            }            
            (($sPlugin = Phpfox_Plugin::get('comment.component_ajax_ajax_add_passed')) ? eval($sPlugin) : false);            
        }

        if (isset($aVals['is_via_feed']))
        {        

        }
        else 
        {    
            $this->html('#js_comment_process', '');
            $this->call("$('#js_comment_submit').removeAttr('disabled'); $('#js_reply_comment').val('0'); $('#js_reply_comment_info').html('');");
        }

        if (Phpfox::isModule('captcha') && !isset($bNoCaptcha) && Phpfox::getUserParam('captcha.captcha_on_comment'))
        {
            $this->call("$('#js_captcha_image').ajaxCall('captcha.reload', 'sId=js_captcha_image&sInput=image_verification');");
        }

        if ($aVals['type'] == 'photo')
        {
            $this->call("if (\$Core.exists('.js_feed_comment_view_more_holder')) { $('.js_feed_comment_view_more_holder')[0].scrollTop = $('.js_feed_comment_view_more_holder')[0].scrollHeight; }");
        }

        // http://www.phpfox.com/tracker/view/15074/
        // get the onclick atrribute
        $sCall = "sOnClick = $('#js_feed_comment_view_more_link_" . $aVals['is_via_feed'] . " .comment_mini_link .no_ajax_link').attr('onclick');";
        // if there is "view all comments" link
        $sCall .= "if (typeof sOnClick != 'undefined') {";
        // regex to get the params for the ajax call in this onlclick
        $sCall .= "sPattern = new RegExp('(comment_)?type_id=([a-z]+_?[a-z]*)&(amp;)?item_id=[0-9]+&(amp;)?feed_id=[0-9]+', 'i');";
        // save the current ajax params
        $sCall .= "sOnClickParam = sPattern.exec(sOnClick);";
        // replace the params, adding the new "added" variable
        $sCall .= "sNewOnClick = sOnClick.replace(sOnClickParam[0], sOnClickParam[0]+'&added=1');";
        // replace the onclick attribute
        $sCall .= "$('#js_feed_comment_view_more_link_" . $aVals['is_via_feed'] . " .comment_mini_link .no_ajax_link').attr('onclick', sNewOnClick);";
        // if there is "view all comments" link
        $sCall .= "}";
        // call this JS code
        $this->call($sCall);

        $this->call('$Core.loadInit();');
        echo $this->getData();
        die();
    }
?>
