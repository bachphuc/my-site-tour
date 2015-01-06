<?php
    /**
    * [PHPFOX_HEADER]
    */

    defined('PHPFOX') or exit('NO DICE!');

    /**
    * 
    * 
    * @copyright        [PHPFOX_COPYRIGHT]
    * @author          phuclb@npfox.com
    * @package          Module_Request
    */
    class FollowedPost_Component_Block_FollowedMenu extends Phpfox_Component
    {
        /**
        * Class process method wnich is used to execute this component.
        */
        public function process()
        {    
            $aUser = $this->getParam('aUser');
            $user_id = $aUser['user_id'];
            if($user_id == 0)
            {
                $user_id = $aUser['owner_user_id'];
            }
            if(Phpfox::getUserId() != $user_id)
            {
                return false;
            }
            $sView = $this->request()->get('view');
            if($sView && $sView == 'followed')
            {
                echo '<script type="text/javascript">$Core.bFollowedFeed = true;</script>';
            }
            $bMyPost = false;
            if(!$sView && !$this->request()->get('req2'))
            {
                $bMyPost = true;
            }
            
            $this->template()->assign(array(
                'aUser' => $aUser,
                'sView' => $sView,
                'bMyPost' => $bMyPost
            ));
        }
    }
?>
