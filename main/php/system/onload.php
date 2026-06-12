<?php
    $jsonDataFile = "./jsonSheets/system/onloader.json";
    if(!file_exists($jsonDataFile)){
        die("fix this");
        // TODO:: Create logic for it
    }

    $config = json_decode(file_get_contents($jsonDataFile), true);
    usort($config['modules'], function ($a, $b) {
        return $a['rank'] <=> $b['rank'];
    });

    foreach ($config['modules'] as $module) {
        require_once "./modules/". $module["dirName"] ."/moduleLoader/moduleLoad.php";
    }
?>