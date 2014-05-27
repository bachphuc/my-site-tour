<?php
    defined('PHPFOX') or exit('NO DICE!');
?>

<script type="text/javascript">
    $Behavior.loadBlockTour = function(){l}
        $('.block_add_newtour').appendTo('body');
        $('.block_begin_tour').appendTo('body');
        $Core.tourSeting = {l}{r};
        {if $bBackDrop}$Core.tourSeting.backdrop = true;{/if}
        {if $bShowStep}
            $Core.tourSeting.showStepNumber = true;
        {else}
            $Core.tourSeting.showStepNumber = false;
        {/if}
    {r}
</script>


{if $bCanAdd}
<div class="block_add_newtour" {if isset($aAddTourPosition)}style="left:{$aAddTourPosition.left}px;top:{$aAddTourPosition.top}px;"{/if}>
    <ul class="new_tour_menu">
        <li class="bt_add_new_tour">{phrase var='sitetour.add_new_step'}</li>
        <li class="bt_stop_setup_tour">{phrase var='sitetour.cancel_setup_step'}</li>
        <li class="bt_preview_tour">{phrase var='sitetour.preview_tour'}</li>
        <li class="bt_save_tour">{phrase var='sitetour.save_tour'}</li>
        <li class="bt_reset_tour">{phrase var='sitetour.reset_tour'}</li>
    </ul>
</div>
{/if}
{if isset($aTour)}
<script type="text/javascript">
    $Behavior.initTour = function(){l}
        if( typeof $Core.initted === 'undefined'){l}
            $Core.initted = true;
            $Core.myDomOutline = null;
        
            $Core.TourInfo = {php}echo json_encode($this->_aVars['aTour']);{/php};
            $Core.Steps = {php}echo json_encode($this->_aVars['aSteps']);{/php};
            {if $aTour.is_autorun}$Core.startTour();{/if}
        {r}
    {r}
</script>
<div class="block_begin_tour" {if isset($aPlayTourPosition)}style="left:{$aPlayTourPosition.left}px;top:{$aPlayTourPosition.top}px;"{/if}>
    <div>
        <div class="bt_star_tour"></div>
        <div class="bt_end_tour"></div>
    </div>
</div>
{/if}