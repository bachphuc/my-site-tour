<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

class CustomProfiles_Component_Block_Egift extends Phpfox_Component
{
    /**
     * Class process method wnich is used to execute this component.
     */
    public function process()
    {
        //$aUser = $this->getParam('aUser');
        $aUser =Phpfox::getUserBy();     
        $aCategories = Phpfox::getService('egift')->getCategories(true);
        // Check if there are categories to display
        $bShowCategories = false;
        foreach ($aCategories as $iKey => $aCategory)
        {
            if ($aCategory['time_start'] == null && $aCategory['time_end'] == null)
            {
                if (!isset($aUser['is_user_birthday']) || $aUser['is_user_birthday'] != true)
                {
                    unset($aCategories[$iKey]);
                }
                continue;
            }
            
            if ( PHPFOX_TIME < $aCategory['time_start'] || PHPFOX_TIME > $aCategory['time_end'])
            {
                unset($aCategories[$iKey]);
            }
        }
        
        // if the user has not set its birthday we just dont show it
        if (empty($aCategories))
        {
            return false;
        }
        
        $aEgifts = Phpfox::getService('egift')->getEgifts();

        foreach ($aEgifts as $sCat => $aCat)
        {
            foreach ($aCat as $iKey => $aGift)
            {
                if (is_array($aGift['price']) && !empty($aGift['price']) && isset($aEgifts[$sCat][$iKey]['price'][Phpfox::getService('user')->getCurrency()]))
                {
                    /*get the currency for this user*/
                    $aGift['price'] = $aEgifts[$sCat][$iKey]['price'][Phpfox::getService('user')->getCurrency()];
                    $aGift['currency_id'] = Phpfox::getService('user')->getCurrency();
                }
                else
                {
                    $aGift['price'] = '0.00';
                }
                $aEgifts[$aGift['category_id']][] = $aGift;
            }
            unset($aEgifts[$sCat]);
        }
        $this->template()->assign(array(
                'aCategories' => $aCategories,
                'aEgifts' => $aEgifts
            )
        );
    }
    
    /**
     * Garbage collector. Is executed after this class has completed
     * its job and the template has also been displayed.
     */
    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('core.component_block_activity_clean')) ? eval($sPlugin) : false);
    }
}

?>