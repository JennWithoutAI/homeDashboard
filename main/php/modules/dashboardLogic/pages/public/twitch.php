<?php
if(!defined('MODULE_FILEURL_twitchApi')){
    echo "Twitch API MODULE IS NOT ENABLED";
    return;
}

if(!file_exists(MODULE_FILEURL_twitchApi)){
    die("Twitch API MODULE NOT ENABLED, please Fix that before using this");
}
// made in module loader


$currentpage = $_GET["page"];


// tiredness is shooting in so im going to bed for now <3
?>

<br><br><br><br><br>
    <!-- use for now nav for the button styling, fix it later TODO:: FIX STYLING AND HTML -->
<nav>
    <a href="?page=<?php echo $currentpage?>&twitchAPI=checkViewers">check Viewers</a>

</nav>
