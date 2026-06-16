<?php
if(!file_exists(JSON_FILEURL."/dashboard/nav.json")){
    ?>
    <nav>
        <div class="button-container">
            <a href="?home">HOME</a>
            <a href="?page=cheatSheet">CheatSheet Coding</a>
            <a href="?page=twitch">Twitch Board</a>
            <a href="https://github.com/JennWithoutAI/homeDashboard" target="_blank">Github Link To Project</a>
        </div>
    </nav>
    <?php
    return;
}
// auto load
$navBarItems = json_decode(file_get_contents(JSON_FILEURL."/dashboard/nav.json"),true);
$generatedNav = "";
$generatedNav .= "<nav><div class='button-container'>";
foreach($navBarItems["navItems"] as $items){
    if(!$items["enabled"]){ continue; }
    $generatedNav .= "<a href='{$items['href']}'>{$items['name']}</a>";
}
$generatedNav .= "</div></nav>";
echo $generatedNav
?>

