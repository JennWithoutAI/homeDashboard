<?php
    if(!defined("MODULE_FILEURL_automation")){
        return;
    }
    // add all the things you can imagine lik
    // config
    $enableAutomation = [];
    $enableAutomation["addModulesToSystemJson"] = true;
    // activate
    $auto = new automateSystem($enableAutomation);
    class automateSystem {
        public function __construct($list)
        {
            foreach($list as $functionName => $value){
                if($value){ $this->$functionName();}
            }
        }
        private function addModulesToSystemJson(){
            $file = JSON_FILEURL."/system/onloader.json";
            $addToJson = [];
            $highestRankForAutomation = 0;

            if(file_exists($file)){
                $fileData = json_decode(file_get_contents($file),true);
                $moduleDirs = scandir(MODULE_FILEURL);
                foreach($moduleDirs as $moduleDir){
                    if($moduleDir === "." || $moduleDir === ".."){ continue; }
                    if(!isset($fileData["modules"][$moduleDir])){
                        $addToJson[] = $moduleDir;
                        continue;
                    }
                    if($highestRankForAutomation < $fileData["modules"][$moduleDir]["rank"]){
                        $highestRankForAutomation = $fileData["modules"][$moduleDir]["rank"];
                    }
                }

                foreach($addToJson as $module){
                    $highestRankForAutomation++;
                    // just learned i can do it like this so gotta update it everywheere
                    $fileData["modules"][$module] = [
                        "rank"    => $highestRankForAutomation,
                        "enabled" => true // if prod, SET FALSE!!
                    ];
                }
                file_put_contents($file, json_encode($fileData, JSON_PRETTY_PRINT));
            }
        }
    }
?>