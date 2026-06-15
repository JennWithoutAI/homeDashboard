<?php
$jsonDataFile = JSON_FILEURL."/system/onloader.json";

// MAKE SURE SYSTEM IS BEING ADDED
if (!file_exists($jsonDataFile)) {
    $starter["modules"]["automation"] = [
        "rank" => 0,
        "enabled" => true
    ];
    file_put_contents($jsonDataFile,json_encode($starter));
}
$config = json_decode(file_get_contents($jsonDataFile), true);
if (!isset($config["modules"]["automation"])) {
    $config["modules"]["automation"] = [
        "rank" => 0,
        "enabled" => true
    ];
    file_put_contents($jsonDataFile,json_encode($config));
}

uasort($config['modules'], function ($a, $b) {
    return $a['rank'] <=> $b['rank'];
});

foreach ($config['modules'] as $moduleName => $module) {
    if(!$module["enabled"]){ continue; };
    define("MODULE_FILEURL_{$moduleName}", MODULE_FILEURL . "/" . $moduleName);
    define("MODULE_URL_{$moduleName}", MODULE_URL . "/" . $moduleName);
}

foreach ($config['modules'] as $moduleName => $module) {
    if(!$module["enabled"]){ continue; };
    $path = constant("MODULE_FILEURL_{$moduleName}") . "/moduleLoader/moduleLoad.php";
    require_once $path;
}
?>