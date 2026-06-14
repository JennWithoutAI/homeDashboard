<?php
    $pageFileUrl = MODULE_FILEURL_dashboardLogic."/pages";
    require_once $pageFileUrl."/components/header.php";
    $addDefault = true;
    if(isset($_GET["page"])){
        $page = $_GET["page"];
        $page = basename($_GET["page"]); // strips directory separators
        if(!preg_match('/^[a-zA-Z0-9_]+$/', $page)) {
            die("Fix this in the sanitation section of logic");
        }
        $gotoPage = $pageFileUrl."/public/".$page.".php";
        if(!file_exists($gotoPage)){
            die("404 not found");
        }

        $addDefault = false;
        require_once($gotoPage);
        unset($gotoPage);
    }

    if($addDefault){
        require_once($pageFileUrl."/public/home.php");
    }