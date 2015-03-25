<?php

    /**
    * [PHPFOX_HEADER]
    * 
    * @copyright		[TTN]
    * @author  		ttngon
    */
    defined('PHPFOX') or exit('NO DICE!');
    class Followedpost_Component_Ajax_Ajax extends Phpfox_Ajax
    {
        public function makeFollowed()
        {
            $iFeedId = $this->get('id');
            if (Phpfox::getService('followedpost')->makeFollowed($iFeedId)){
                $this->call("$('#followedpost_makeFollowed_$iFeedId').css(\"display\",\"none\");");
                $this->call("$('#followedpost_makePublic_$iFeedId').css(\"display\", \"block\");");
            }
        }
        
        public function makePublic()
        {
            $iFeedId = $this->get('id');
            if (Phpfox::getService('followedpost')->makePublic($iFeedId)){
                $this->call("$('#followedpost_makePublic_$iFeedId').css(\"display\", \"none\");");
                $this->call("$('#followedpost_makeFollowed_$iFeedId').css(\"display\", \"block\");");
                $this->call('if(oParams.sController == "followedpost.index"){$("#followedpost_makeFollowed_'. $iFeedId .'").closest(".js_feed_view_more_entry_holder").hide();}');
            }
        }

        public function viewMore()
        {
            if ($this->get('callback_module_id') == 'pages' && Phpfox::getService('pages')->isTimelinePage($this->get('callback_item_id')))
            {
                define('PAGE_TIME_LINE', true);
            }
  
            Phpfox::getComponent('followedpost.index', null, 'controller');   

            $sYear = $this->get('year');

            $this->remove('#feed_view_more');
            if (!$this->get('forceview') && !$this->get('resettimeline'))
            {
                $this->append('#js_feed_content', $this->getContent(false));
            }
            else
            {
                // $this->html('#js_timeline_year_holder_' . $sYear . '', $this->getContent(false));
                $this->call('$.scrollTo(\'.timeline_left\', 800);');
                $this->html('#js_feed_content', $this->getContent(false));
            }
            $this->call('$Core.loadInit();');
        }
    }
