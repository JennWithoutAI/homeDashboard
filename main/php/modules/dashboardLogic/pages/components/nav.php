<?php
if(!file_exists(JSON_FILEURL."/dashboard/nav.json")){
    $page = "";
    if(isset($_GET["page"])){
        $page = "?page=".$_GET["page"];
        $full = $page."&unlock=true";
    } else {
        $full = "?unlock=true";
    }
    header("Location: " .$full);
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

