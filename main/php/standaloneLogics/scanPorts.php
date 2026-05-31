<?php
/*
    * Made by Jenn
    * This is made for my home projects, this isnt made dynamically.
    * If you wish it dynamically just request it and ill make it.
    * <3 22 may 2026
 * Reffrence, but not exactly copied & writen myself from -
 * https://gist.github.com/akalongman/b50bc11a9303adb6f2db
 * https://codehill.com/2012/07/a-simple-port-scanner-in-php/
 * https://stackoverflow.com/questions/5211658/php-fsockopen-painfully-slow
 *
 * while working on this, it feels a bit much to just "fsockOpen", maybe i can do it with less resources?
 * anyway, im going to finish this and possibly use something else like nmap for this but for the purpose of making something ill make it for the people
*/
function scanPorts(){
    $host = "172.17.0.1"; // Base docker IP
    // i like to make a big range for myself
    $portStart = 81;
    $portEnd = 8001;
    $openPorts = [];
    for($portStart;$portStart <= $portEnd;$portStart++){
        // little trick i enjoy using, (shoutout to my old colleagues, (dutch) De Peertjes ;)
        if(!$openPort = @fsockopen($host,$portStart,$err, $err_string,1)){
            continue;
        }

        $openPorts[] = $portStart;
        fclose($openPort);
    }
    // old school html bundling? idk i like it
    $html = "";
    if (empty($openPorts)){
        echo "No open ports detected";
    }
    // load json for namescheme
   // $filename = "portname.json";
    //$fileData = file_get_contents(json_decode($filename),true);
    // potentially have to bugfix for future but not yet [im kinda sure but whatever for now]
    $html .= "
            <div class='autoHtml-ports-container'> 
            <h1 class='title'> Ports & Services </h1>
            <div class='card-container'>
    ";
    foreach($openPorts as $port) {
        if(isset($fileData[$port])){
            // ill make this later
        }
        $html .= "
                
                <div class='ports-card'>
                    <div class='ports-header'>
                        <span class='port'>{$port}</span>
                    </div>
                    <div class='ports-button'>
                        <a href='http://localhost:$port' target='_blank'> Open Service Port </a>
                    </div>
                </div>
                ";

    }
    $html .= "</div></div>";
    echo $html;

}



?>