<?php
    // Add dislike for page followpost.index
    if($sModule == 'followedpost' || $sModule == 'strongbox' || $sModule == 'anonymousdone' || $sModule == 'anonymousreceived' || $sModule == 'privatepost')
    {
        $sModule = $sItemTypeId = 'core';
        $this->request()->set('req1', '');
    }
?>
