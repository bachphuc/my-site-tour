<?php
    if(Phpfox::isModule('customprofiles'))
    {
        $aVa = Phpfox::getLib('request')->getArray('val');
        if(Phpfox::getCookie('invited_by_email_form') && Phpfox::getCookie('invited_by_email_form') != '' && count($aVa) == 0 && isset($this->_aVars['aForms']['email']))
        {
            $sScript = '
            <script type="text/javascript">
            $Behavior.lockEmail = function(){
            $("#email").prop("readonly", true);
            }
            </script>';
            echo $sScript;
        }
    }
?>
