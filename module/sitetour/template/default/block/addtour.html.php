<?php
    defined('PHPFOX') or exit('NO DICE!');
?>

{if !isset($aTour)}
<div class="block_add_newtour">
    <ul class="new_tour_menu">
        <li class="bt_add_new_tour">Add New Tour</li>
        <li class="bt_preview_tour">Preview Tour</li>
        <li class="bt_stop_setup_tour">Stop Setup Tour</li>
        <li class="bt_save_tour">Save Tour</li>
        <li>Cancel Tour</li>
    </ul>
</div>
{else}
<script type="text/javascript">
    $Behavior.initTour = function(){l}
        $Core.TourInfo = {php}echo json_encode($this->_aVars['aTour']);{/php};
        $Core.Steps = {php}echo json_encode($this->_aVars['aSteps']);{/php};
    {r}
</script>
<div class="block_begin_tour">
    <div>
        <div class="bt_star_tour"></div>
        <div class="bt_end_tour"></div>
    </div>
</div>
{/if}