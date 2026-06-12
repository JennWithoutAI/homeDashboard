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
$jsonFile = "./jsonSheets/dashboard/names.json";
if(isset($_POST["portReNaming"])){
    if(!file_exists($jsonFile)){file_put_contents($jsonFile, json_encode(new stdClass(), JSON_PRETTY_PRINT));}
    $oldData = json_decode(file_get_contents($jsonFile),true);
    if(!isset($_POST["port"]) && !isset($_POST["serviceName"])){ die("something went wrong"); }
    $port = $_POST["port"];
    $name = $_POST["serviceName"];
    $oldData[$port] = $name;
    file_put_contents($jsonFile, json_encode($oldData,JSON_PRETTY_PRINT));
}
// lazy copy paste i know but im tired, let me be
if(isset($_POST["portNaming"])){
    if(!file_exists($jsonFile)){file_put_contents($jsonFile, json_encode(new stdClass(), JSON_PRETTY_PRINT));}
    $oldData = json_decode(file_get_contents($jsonFile),true);
    $newData = $_POST["portNaming"];
    foreach($_POST as $portname => $portData){
        if($portname === "portNaming"){ continue;}
        if(!is_numeric($portname)){continue;}
        if(isset($oldData[$portname])){ continue;}
        $oldData[$portname] = $portData;
    }
    file_put_contents($jsonFile, json_encode($oldData,JSON_PRETTY_PRINT));
}
function scanPorts(){
    $jsonFile = "./jsonSheets/dashboard/names.json";
    $host = "172.17.0.1"; // Base docker IP
    // i like to make a big range for myself
    $portStart = 80;
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
    // Name Json
    if(!file_exists($jsonFile)){file_put_contents($jsonFile, json_encode(new stdClass(), JSON_PRETTY_PRINT));}
    $names = json_decode(file_get_contents($jsonFile),true);
    $unusedList = [];
    // ports
    foreach($openPorts as $port) {
        // just some security
        if(!is_numeric($port)){ die( "Something went wrong ");}

        if(isset($names[$port])){               $serviceName = $names[$port];
            if ($serviceName === "Unused" || $serviceName === "unused"){ $unusedList[] = $port; continue; }
        } else {
            $serviceName = "
            <form method='post'>
            <input type='text' name='portNaming' value='true' hidden> 
            Name : <input type='text' name='{$port}'/>  
            <input type='submit'> 
            <input type='submit' value='Unused' name='{$port}'>
            </form> ";
        }

        $html .= "
                <div class='ports-card'>
                    <div class='ports-header'>
                        <span class='port'><b><span class='dots'>:</span>{$port}</span></b>
                        <span class='portname'>{$serviceName}</span>
                    </div>
                <hr>
                    
                    <div class='ports-button'>
                        <a href='http://localhost:{$port}' target='_blank' class='servicePort'> Open Service Port </a>
                    </div>
                    
                </div>
                ";
    }
    $html .= "<div></div></div>";
    if(!empty($unusedList)){
    $html .= "
            <hr>
            <div class='autoHtml-ports-container'> 
            <h1 class='title'>Marked as unused ports </h1>
            <div class='card-container'>
    ";
    foreach ($unusedList as $port){
        $html .= "
                <div class='ports-card'>
                    <div class='ports-header'>
                        <span class='port'><b><span class='dots'>:</span>{$port}</b></span>
                    </div>
                </div>
                ";
    }
        $html .= "<div></div></div>";
    }
    $html .= "
            <hr>
            <div class='autoHtml-ports-container'> 
            <h1 class='title'>Change Port Names </h1>
            <div class='card-container'>
                <div class='ports-card'>
                    <form method='post'><input type='text' name='portReNaming' hidden>
                    <label for='port'>&nbspPort Number : </label>
                    <input type='number' name='port' required/>
                    <br>
                    <label for='serviceName'>Service Name :</label>
                    <input type='text' name='serviceName' required> 
                    <br>
                    &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input type='submit' value='Rename'/></form>
                </div>
            </div>
    ";
    echo $html;

}
function page_title($url) {

    $page = file_get_contents($url);

    if (!$page) return null;

    $matches = array();

    if (preg_match('/<title>(.*?)<\/title>/', $page, $matches)) {
        return $matches[1];
    } else {
        return null;
    }
}




?>