<?php
$dir = JSON_FILEURL."/personalSiteAuto";
$file = $dir."/timer.json";
if(!file_exists($file)){
    return;
    // if this gets here with a file exists, someone is fucking around or something is NOT loading the way it should be.
}
$fdata = json_decode(file_get_contents($file),true);

$time = getdate(time());
$today = $time["weekday"];

if(isset($fdata["weekday"])){
    if($fdata["weekday"] === $today){
        return;
    }
}

// client side checker
file_put_contents($file, json_encode(["weekday" => $today]));

$colorTable = [
    "blue"  => "#5BCEFA",
    "pink"  => "#F5A9B8",
    "white" => "#FFFFFF",
];
switch ($today) {
    case "Monday":
        $colorKey = "blue";
        break;
    case "Tuesday":
        $colorKey = "pink";
        break;
    case "Wednesday":
        $colorKey = "white";
        break;
    case "Thursday":
        $colorKey = "blue";
        break;
    case "Friday":
        $colorKey = "pink";
        break;
    case "Saturday":
        $colorKey = "white";
        break;
    case "Sunday":
        $colorKey = "blue";
        break;
    default:
        die("for real how did we get here?");
}

// later import this from the json
$colorScheme = [
    "blue"  => "#5BCEFA",
    "pink"  => "#F5A9B8",
    "white" => "#FFFFFF",
];

$pushRotate = MODULE_FILEURL_personalSiteAutomation."/rotator.php";

$f = fopen($pushRotate,"r+");
fwrite($f,$colorScheme[$colorKey]);
require_once(MODULE_FILEURL_personalSiteAutomation."/moduleFunctions/git.php");





