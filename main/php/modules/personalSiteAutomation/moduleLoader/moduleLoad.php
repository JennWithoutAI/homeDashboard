<?php

    $dir = JSON_FILEURL."/personalSiteAuto";
    $file = $dir."/timer.json";
    if(!is_dir($dir)){
        mkdir($dir);
    }
    if(!file_exists($file)){
        $t = fopen($file,"w+");
        fclose($t);
    }
    $fileData = file_get_contents($file);
    if(!json_validate($fileData)){
        $t = fopen($file,"r+");
        fwrite($t,"[]");
        fclose($t);
    }

    // init (joinked from stackoverflow)
    $file = 'https://github.com/JennWithoutAI/rotatingRepo';
    $file_headers = @get_headers($file);
    if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
        return;
    }
    $fileRotator = MODULE_FILEURL_personalSiteAutomation."/moduleFunctions/rotater.php";
    if(!file_exists($fileRotator)){
        $f = fopen($fileRotator, "w+");
        fclose($f);
    }
    require_once($fileRotator);


?>