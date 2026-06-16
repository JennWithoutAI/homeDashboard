<?php
    $html = "";
// get all jsons
    $moduleJsons = scandir(JSON_FILEURL);
    $currentPage = $_GET['page'];
    $html .= "<div class='autoHtml-control-container'>";
    $html .= "<h1 class='title'>Control Center</h1><hr>";
    $html .= "
    <form method='get' action=''>
        <input type='hidden' name='page' value='{$currentPage}'>
        <input class='unlock' type='submit' name='unlock' value='Manual update JSONS'>
    </form>";
    foreach($moduleJsons as $moduleJson){
        if($moduleJson === "." || $moduleJson === "..") { continue; }
        $childModuleJsonDir = JSON_FILEURL."/".$moduleJson;
        if(!is_dir($childModuleJsonDir)) { continue; }

        $html .= "<div class='module-group'>";
        $html .= "<div class='module-label'>{$moduleJson}</div>";
        $html .= "<div class='card-container'>";

        $childModuleJsons = scandir($childModuleJsonDir);
        foreach($childModuleJsons as $childModuleJson){
            if($childModuleJson === "." || $childModuleJson === "..") { continue; }
            $fileModuleJsonDir = $childModuleJsonDir."/".$childModuleJson;
            if(!is_file($fileModuleJsonDir)) { continue; }
            $fileData = file_get_contents($fileModuleJsonDir);
            $fileDataDecoded = json_decode($fileData, true);
            $prettyJson = json_encode($fileDataDecoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

            $html .= "
                <div class='control-card'>
                    <div class='control-header'>
                        <span class='port'><b><span class='dots'>:</span>{$childModuleJson}</b></span>
                        <form href='#' method='post'>
                            <textarea class='portname'>{$prettyJson}</textarea>
                            <br>
                            <input class='update' type='submit' value='updateChanges' >
                            <input class='reset' type='submit' value='fullR eset' >
                        </form>
                    </div>
                    <hr>
                </div>
            ";
        }
        $html .= "</div></div>"; // close card-container + module-group
    }
    $html .= "</div>";
    /*
$html .= "
                <div class='control-card'>
                    <div class='control-header'>
                        <span class='port'><b><span class='dots'>:</span>{$port}</span></b>
                        <span class='portname'>{$serviceName}</span>
                    </div>
                <hr>
                    
                    <div class='control-button'>
                        <a href='http://localhost:{$port}' target='_blank' class='servicePort'> Open Service Port </a>
                    </div>
                    
                </div>
                ";
    */
echo $html
?>


