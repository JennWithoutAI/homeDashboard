<?php
    function loadAutomationModule(){
       // ob_start();
        $lockDir = MODULE_FILEURL_automation."/auto.lock";

        if(file_exists($lockDir) && isset($_GET["unlock"])){
                $moduleAutomationDirLocation = MODULE_FILEURL_automation."/moduleAutomation";
                $moduleAutomationDirs = scandir($moduleAutomationDirLocation);

                array_unshift($moduleAutomationDirs, "system");
                foreach($moduleAutomationDirs as $childModuleDir) {

                    if($childModuleDir === "." || $childModuleDir === ".."){ continue; }

                    $moduleChildFullDir = $moduleAutomationDirLocation."/".$childModuleDir;
                    if(!is_dir($moduleChildFullDir)){ continue; }

                    $automateDir = $moduleChildFullDir."/automate.php";
                    if(!file_exists($automateDir)){ continue; }
                    require_once($automateDir);
                }
              //  ob_end_clean();
                file_put_contents($lockDir,time());
            }
        }

    loadAutomationModule();

?>