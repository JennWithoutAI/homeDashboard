<?php
    if(!defined('MODULE_FILEURL_dashboardLogic')){
        return;
    }

    // add all the things you can imagine lik
    // config
    $enableAutomation = [];
    $enableAutomation["navbar"] = true;

    // activate
    new automateDashboard($enableAutomation);


    class automateDashboard {
        public function __construct($list)
        {

        }
    }
?>