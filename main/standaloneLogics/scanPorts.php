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
    $portStart = 80; // 80 sice when hitting for it just skips 80 ;)
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
    foreach($openPorts as $port){
        $html .= "<div><button>{$port}</button></div>";
    }
    echo $html;
    // ill revisit this later, dont have the focus due the heat
}



?>