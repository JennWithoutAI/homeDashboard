<?php
    /* twitch api */
        $twitchApiUrl = "./modules/twitchApi/v1/";
    //if(file_exists($twitchApiUrl."twitch.lock")){
        require_once($twitchApiUrl."index.php"); // load dont execute
        $twitchClass = new twitch();
        // twitchlock is made automatically [will be] and acts as a does it exist
        // TODO:: Make TwitchLock
        if(isset($_GET["twitchAPI"])){
            $data = $twitchClass->getChannelData("gismogy");
            echo "viewer amount : ".$data;
        }
    //}
?>