<?php

require_once MODULE_FILEURL_dashboardLogic."/pages/components/nav.php";

?>

    <hr>
<?php
$constants = get_defined_constants(true);
$allConstants = $constants['user'] ?? [];

$moduleConstants = array_filter(
        $allConstants,
        fn($key) => str_starts_with($key, 'MODULE_'),
        ARRAY_FILTER_USE_KEY
);

foreach ($moduleConstants as $constName => $constValue){
    echo "[".$constName. "] => ". $constValue."<br>";
}