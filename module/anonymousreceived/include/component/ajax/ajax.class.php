<?php

    /**
    * [PHPFOX_HEADER]
    * 
    * @copyright        [TTN]
    * @author          ttngon
    */
    defined('PHPFOX') or exit('NO DICE!');
    class AnonymousReceived_Component_Ajax_Ajax extends Phpfox_Ajax{
        public function viewMore()
        {
            if ($this->get('callback_module_id') == 'pages' && Phpfox::getService('pages')->isTimelinePage($this->get('callback_item_id')))
            {
                define('PAGE_TIME_LINE', true);
            }
  
            Phpfox::getComponent('anonymousreceived.index', null, 'controller');   

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
