<?php
    /* twitch api */
        $twitchApiUrl = "./modules/twitchApi/v1/";
    //if(file_exists($twitchApiUrl."twitch.lock")){
        require_once($twitchApiUrl."index.php"); // load dont execute
        $twitchClass = new twitch();
        // twitchlock is made automatically [will be] and acts as a does it exist
        // TODO:: Make TwitchLock
    echo "<pre>";
        $channel = "gismogy";
        if(isset($_GET["twitchAPI"])){
            $twitchGET = $_GET["twitchAPI"];
            if($twitchGET === "getViewers" || $twitchGET === "all"){
                $data = $twitchClass->getChannelData($channel);
                echo "viewer amount : " . $data ."<br><br>";
            }/*
            if($twitch === "getChat"){
                $data = $twitchClass->getChatData($channel);
                echo "Chat : ".$data;
            } */
            if($twitchGET === "getChatters" || $twitchGET === "all"){
                $users = [];
                $data = $twitchClass->getChatters($channel);
                echo "People Watching : <br><br>";
                foreach($data as $user){
                    $username = $user["user_name"];
                    if($username === "Gismogy" || $username === "StreamElements"){ echo "<span>".$username."</span><br>"; continue;}
                    $users[] = $username;
                    echo $username;
                    echo "<span class='lime'>".$username."</span><br>";
                }
            }
        }
echo "</pre>";
    //}
?>