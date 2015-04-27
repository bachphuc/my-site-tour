<?php
    /**
    * [PHPFOX_HEADER]
    */

    defined('PHPFOX') or exit('NO DICE!');

    /**
    * Waytame Callbacks
    * 
    * @copyright		[PHPFOX_COPYRIGHT]
    * @author  		phuclb
    * @package  		Module_Waytame
    * @version 		$Id: callback.class.php 7264 2014-04-09 21:00:49Z Fern $
    */
    class Waytame_Service_Callback extends Phpfox_Service 
    {
        /**
        * Class constructor
        *
        */	
        public function __construct()
        {	
            $this->_sTable = Phpfox::getT('waytame_question');
        }

        public function getFeedDetails($iItemId)
        {
            return array(
                'module' => 'waytame',
                'table_prefix' => 'waytame_question_',
                'item_id' => $iItemId
            );
        }		

        public function getActivityFeed($aRow, $aCallback = null, $bIsChildItem = false)
        {
            if (Phpfox::isUser())
            {
                $this->database()->select('l.like_id AS is_liked, ')
                ->leftJoin(Phpfox::getT('like'), 'l', 'l.type_id = \'waytame\' AND l.item_id = w.question_id AND l.user_id = ' . Phpfox::getUserId());
            }

            if ($bIsChildItem)
            {
                $this->database()->select(Phpfox::getUserField('u2') . ', ')->join(Phpfox::getT('user'), 'u2', 'u2.user_id = w.user_id');
            }				

            $aRow = $this->database()->select('w.question_id, w.question, w.time_stamp, w.total_like, w.total_comment, w.total_dislike, w.question AS text')
            ->from(Phpfox::getT('waytame_question'), 'w')
            ->where('w.question_id = ' . (int) $aRow['item_id'])
            ->execute('getSlaveRow');	

            if (!isset($aRow['question_id']))
            {
                return false;
            }		

            $aRow['text'] = '';

            return array_merge(array(
                'feed_title' => $aRow['question'],
                'feed_info' => Phpfox::getPhrase('waytame.ask_a_question'),
                'feed_link' => Phpfox::permalink('waytame', $aRow['question_id'], $aRow['question']),
                'feed_content' => $aRow['text'],
                'total_comment' => $aRow['total_comment'],
                'feed_total_like' => $aRow['total_like'],
                'feed_is_liked' => isset($aRow['is_liked']) ? $aRow['is_liked'] : false,
                'feed_icon' => Phpfox::getLib('image.helper')->display(array('theme' => 'module/blog.png', 'return_url' => true)),
                'time_stamp' => $aRow['time_stamp'],			
                'enable_like' => true,			
                'comment_type_id' => 'waytame',
                'like_type_id' => 'waytame',
                'custom_data_cache' => $aRow
                ), $aRow);
        }

        public function addLike($iItemId, $bDoNotSendEmail = false)
        {
            $aRow = $this->database()->select('question_id, question AS title, user_id')
            ->from(Phpfox::getT('waytame_question'))
            ->where('question_id = ' . (int) $iItemId)
            ->execute('getSlaveRow');

            if (!isset($aRow['question_id']))
            {
                return false;
            }

            $this->database()->updateCount('like', 'type_id = \'waytame\' AND item_id = ' . (int) $iItemId . '', 'total_like', 'waytame_question', 'question_id = ' . (int) $iItemId);	

            if (!$bDoNotSendEmail)
            {
                $sLink = Phpfox::permalink('waytame', $aRow['question_id'], $aRow['title']);

                Phpfox::getLib('mail')->to($aRow['user_id'])
                ->subject(array('waytame.user_like_your_question', array('full_name' => Phpfox::getUserBy('full_name'), 'title' => $aRow['title'])))
                ->message(array('waytame.full_user_like_question', array('full_name' => Phpfox::getUserBy('full_name'), 'link' => $sLink, 'title' => $aRow['title'])))
                ->notification('like.new_like')
                ->send();

                Phpfox::getService('notification.process')->add('waytame_like', $aRow['question_id'], $aRow['user_id']);
            }
        }

        public function getNotificationExpireQuestion($aNotification)
        {
            $aWaytameNotification = Phpfox::getService('waytame')->getWaytameNotification($aNotification['item_id']);
            if(!isset($aWaytameNotification['notification_id']))
            {
                return false;
            }
            return array(
                'link' => Phpfox::getLib('url')->makeUrl('friend',array('view_expire')),
                'message' => $aWaytameNotification['message'],
                'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
            );    
        }

        public function getNotificationLike($aNotification)
        {
            $aRow = $this->database()->select('w.question_id, w.question AS title, w.user_id, u.gender, u.full_name')	
            ->from(Phpfox::getT('waytame_question'), 'w')
            ->join(Phpfox::getT('user'), 'u', 'u.user_id = w.user_id')
            ->where('w.question_id = ' . (int) $aNotification['item_id'])
            ->execute('getSlaveRow');

            $sUsers = Phpfox::getService('notification')->getUsers($aNotification);
            $sTitle = Phpfox::getLib('parse.output')->shorten($aRow['title'], Phpfox::getParam('notification.total_notification_title_length'), '...');

            $sPhrase = '';
            if ($aNotification['user_id'] == $aRow['user_id'])
            {
                $sPhrase = Phpfox::getPhrase('waytame.user_gender_like_own_question', array('users' => $sUsers, 'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1), 'title' => $sTitle));	
            }
            elseif ($aRow['user_id'] == Phpfox::getUserId())		
            {
                $sPhrase = Phpfox::getPhrase('waytame.user_like_your_questions', array('users' => $aNotification['full_name'], 'title' => $sTitle));
            }
            else 
            {
                $sPhrase = Phpfox::getPhrase('waytame.liked_your_question_data', array('users' => $aRow['full_name'], 'row_full_name' => $aRow['full_name'], 'title' => $sTitle));
            }

            return array(
                'link' => Phpfox::getLib('url')->permalink('waytame', $aRow['question_id'], $aRow['title']),
                'message' => $sPhrase,
                'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
            );	
        }	

        public function getNotificationDisLike($aNotification)
        {
            $aQuestion = Phpfox::getService('waytame')->getQuestion($aNotification['item_id']); 
            return array(
                'link' => Phpfox::getLib('url')->permalink('waytame', $aQuestion['question_id'], $aQuestion['question']),
                'message' => Phpfox::getPhrase('waytame.waytame_dislike_notification', array('users' => $aNotification['full_name'],'title' => $aQuestion['question'])),
                'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
            );    
        }   

        public function getNotificationLikeAnswer($aNotification)
        {
            $aAnswer = Phpfox::getService('waytame')->getAnswer($aNotification['item_id']); 
            return array(
                'link' => Phpfox::getLib('url')->permalink('waytame', $aAnswer['question_id']),
                'message' => '<span class="drop_data_user">'.$aNotification['full_name'].'</span> liked your answer "'.$aAnswer['answer'].'" on question "'.$aAnswer['question'].'"',
                'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
            );    
        }  

        public function getNotificationDisLikeAnswer($aNotification)
        {
            $aAnswer = Phpfox::getService('waytame')->getAnswer($aNotification['item_id']); 
            return array(
                'link' => Phpfox::getLib('url')->permalink('waytame', $aAnswer['question_id']),
                'message' => '<span class="drop_data_user">'.$aNotification['full_name'].'</span> disliked your answer "'.$aAnswer['answer'].'" on question "'.$aAnswer['question'].'"',
                'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
            );    
        }  

        public function deleteLike($iItemId)
        {
            $this->database()->updateCount('like', 'type_id = \'waytame\' AND item_id = ' . (int) $iItemId . '', 'total_like', 'waytame_question', 'question_id = ' . (int) $iItemId);	
        }		

        public function getFeedRedirect($iId, $iChild = 0)
        {
            $aQuestion = $this->database()->select('w.question_id, w.question AS title')
            ->from($this->_sTable, 'w')
            ->join(Phpfox::getT('user'), 'u', 'u.user_id = w.user_id')
            ->where('w.question_id = ' . (int) $iId)
            ->execute('getSlaveRow');		

            if (!isset($aQuestion['question_id']))
            {
                return false;
            }					

            return Phpfox::permalink('waytame', $aQuestion['question_id'], $aQuestion['title']);
        }

        public function addComment($aVals, $iUserId = null, $sUserName = null)
        {
            $aQuestion = $this->database()->select('u.full_name, u.user_id, u.gender, u.user_name, w.question AS title, w.question_id, w.privacy, w.privacy_comment')
            ->from($this->_sTable, 'w')
            ->join(Phpfox::getT('user'), 'u', 'u.user_id = w.user_id')
            ->where('w.question_id = ' . (int) $aVals['item_id'])
            ->execute('getSlaveRow');

            if ($iUserId === null)
            {
                $iUserId = Phpfox::getUserId();
            }

            (Phpfox::isModule('feed') ? Phpfox::getService('feed.process')->add($aVals['type'] . '_comment', $aVals['comment_id'], 0, 0, 0, $iUserId) : null);

            // Update the post counter if its not a comment put under moderation or if the person posting the comment is the owner of the item.
            if (empty($aVals['parent_id']))
            {
                $this->database()->updateCounter('waytame_question', 'total_comment', 'question_id', $aVals['item_id']);
            }

            // Send the user an email
            $sLink = Phpfox::permalink('waytame', $aQuestion['question_id'], $aQuestion['title']);

            Phpfox::getService('comment.process')->notify(array(
                'user_id' => $aQuestion['user_id'],
                'item_id' => $aQuestion['question_id'],
                'owner_subject' => Phpfox::getPhrase('waytame.comment_your_question', array('full_name' => Phpfox::getUserBy('full_name'), 'title' => $aQuestion['title'])),
                'owner_message' => Phpfox::getPhrase('waytame.user_comment_your_question', array('full_name' => Phpfox::getUserBy('full_name'), 'link' => $sLink, 'title' => $aQuestion['title'])),
                'owner_notification' => 'comment.add_new_comment',
                'notify_id' => 'comment_waytame',
                'mass_id' => 'waytame',
                'mass_subject' => (Phpfox::getUserId() == $aQuestion['user_id'] ? Phpfox::getPhrase('waytame.gender_comment_on_your_question', array('full_name' => Phpfox::getUserBy('full_name'), 'gender' =>  Phpfox::getService('user')->gender($aQuestion['gender'], 1))) : Phpfox::getPhrase('waytame.user_comment_your_question_title', array('full_name' => Phpfox::getUserBy('full_name'), 'blog_full_name' => $aQuestion['full_name']))),
                'mass_message' => (Phpfox::getUserId() == $aQuestion['user_id'] ? Phpfox::getPhrase('waytame.user_comment_your_question_comment', array('full_name' => Phpfox::getUserBy('full_name'), 'gender' => Phpfox::getService('user')->gender($aQuestion['gender'], 1), 'link' => $sLink, 'title' => $aQuestion['title'])) : Phpfox::getPhrase('waytame.full_user_comment_your_question', array('full_name' => Phpfox::getUserBy('full_name'), 'blog_full_name' => $aQuestion['full_name'], 'link' => $sLink, 'title' => $aQuestion['title'])))
                )
            );
        }	

        public function updateCommentText($aVals, $sText)
        {

        }		

        public function getRedirectComment($iId)
        {
            return $this->getFeedRedirect($iId);
        }

        public function getReportRedirect($iId)
        {
            return $this->getFeedRedirect($iId);
        }

        public function deleteComment($iId)
        {
            $this->database()->update($this->_sTable, array('total_comment' => array('= total_comment -', 1)), 'question_id = ' . (int) $iId);
        }		

        public function getFeedRedirectFeedLike($iId, $iChildId = 0)
        {
            return $this->getFeedRedirect($iChildId);
        }

        public function getActions()
        {
            if(PHPFOX_IS_AJAX)
            {
                $oRequest = Phpfox::getLib('request');
                if($oRequest->getInt('item_id') && $oRequest->get('item_type_id') == 'waytame' && $oRequest->getInt('action_type_id') == 2)
                {
                    $aQuestion = Phpfox::getService('waytame')->getQuestion($oRequest->get('item_id'));
                    Phpfox::getService('notification.process')->add('waytame_dislike', $oRequest->getInt('item_id'), $aQuestion['user_id']);
                }
            }

            return array(
                'dislike' => array(
                    'enabled' => true,
                    'action_type_id' => 2, 
                    // sort of redundant given the key 
                    'phrase' => Phpfox::getPhrase('like.dislike'),
                    'phrase_in_past_tense' => 'disliked',
                    'item_type_id' => 'waytame', 
                    // used internally to differentiate between photo albums and photos for example.
                    'item_phrase' => Phpfox::getPhrase('waytame.item_phrase'), 
                    // used to display to the user what kind of item is this
                    'table' => 'waytame_question',
                    'column_update' => 'total_dislike',
                    'column_find' => 'question_id',
                    'where_to_show' => array('waytame', '')
                )
            );
        }

        public function getAjaxCommentVar()
        {
            return 'waytame.can_post_comment_on_waytame';
        }

        public function getCommentItem($iId)
        {
            $aRow = $this->database()->select('question_id AS comment_item_id, privacy_comment, user_id AS comment_user_id')
            ->from($this->_sTable)
            ->where('question_id = ' . (int) $iId)
            ->execute('getSlaveRow');        

            $aRow['comment_view_id'] = '0';

            return $aRow;
        }

        public function getCommentNotification($aNotification)
        {
            $aRow = $this->database()->select('w.question_id, w.question AS title, w.user_id, u.gender, u.full_name')    
            ->from(Phpfox::getT('waytame_question'), 'w')
            ->join(Phpfox::getT('user'), 'u', 'u.user_id = w.user_id')
            ->where('w.question_id = ' . (int) $aNotification['item_id'])
            ->execute('getSlaveRow');

            if (!isset($aRow['question_id']))
            {
                return false;
            }

            $sUsers = Phpfox::getService('notification')->getUsers($aNotification);
            $sTitle = Phpfox::getLib('parse.output')->shorten($aRow['title'], Phpfox::getParam('notification.total_notification_title_length'), '...');

            $sPhrase = '';
            if ($aNotification['user_id'] == $aRow['user_id'] && !isset($aNotification['extra_users']))
            {
                $sPhrase = Phpfox::getPhrase('waytame.users_commented_on_gender_question_title', array('users' => $sUsers, 'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1), 'title' => $sTitle));
            }
            elseif ($aRow['user_id'] == Phpfox::getUserId())        
            {
                $sPhrase = Phpfox::getPhrase('waytame.users_commented_on_your_question_title', array('users' => $sUsers, 'title' => $sTitle));
            }
            else 
            {
                $sPhrase = Phpfox::getPhrase('waytame.users_commented_on_span_class_drop_data_user_row_full_name', array('users' => $sUsers, 'row_full_name' => $aRow['full_name'], 'title' => $sTitle));
            }

            return array(
                'link' => Phpfox::getLib('url')->permalink('waytame', $aRow['question_id'], $aRow['title']),
                'message' => $sPhrase,
                'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
            );
        }
    }

?>
