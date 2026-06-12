<?php
    /* twitch api */
    $twitchApiUrl = "./twitchApi/v1/";
    if(file_exists($twitchApiUrl."twitch.lock")){
        require_once($twitchApiUrl."index.php"); // load dont execute

        // twitchlock is made automatically [will be] and acts as a does it exist
        // TODO:: Make TwitchLock

        // session checks
        if(isset($_SESSION["access_token"]) && isset($_SESSION["refresh_token"])){
            // do some logic here, man im tired haha
            // TODO:: make Logic here
        }
    }
?>