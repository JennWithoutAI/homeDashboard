<?php
$jsonDataFile = JSON_URL."/system/onloader.json";

if (!file_exists($jsonDataFile)) {
    die("fix this");
}

$config = json_decode(file_get_contents($jsonDataFile), true);

usort($config['modules'], function ($a, $b) {
    return $a['rank'] <=> $b['rank'];
});

foreach ($config['modules'] as $module) {
    define("MODULE_FILEURL_{$module['dirName']}", MODULE_FILEURL . "/" . $module['dirName']);
    define("MODULE_URL_{$module['dirName']}", MODULE_URL . "/" . $module['dirName']);
}

foreach ($config['modules'] as $module) {
    $path = constant("MODULE_FILEURL_{$module['dirName']}") . "/moduleLoader/moduleLoad.php";
    require_once $path;
}
?>