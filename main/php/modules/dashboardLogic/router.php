<?php
    $pageFileUrl = MODULE_FILEURL_dashboardLogic."/pages";
    require_once $pageFileUrl."/components/header.php";
    router($pageFileUrl);

    function router($pageFileUrl){
            $addDefault = true;
            if(isset($_GET["page"])){
                $page = $_GET["page"];
                $page = basename($_GET["page"]); // strips directory separators
                if(!preg_match('/^[a-zA-Z0-9_]+$/', $page)) {
                    // gotta fix htis tomroww // note still havent fixed it TODO:: FIX THIS IMG THING
                    require_once $pageFileUrl."/components/404.php";
                    die("A big error happend");
                }

                $jsonUrlPathDir = JSON_FILEURL."/dashboard/nav.json";
                if(!file_exists($jsonUrlPathDir)) {
                    require_once $pageFileUrl."/components/404.php";
                    die("make sure Nav is generated!! ERROR");
                }

                $urlPaths = json_decode(file_get_contents($jsonUrlPathDir),true);
                $selectedPage = $urlPaths["navItems"][$page];
                if(!isset($selectedPage)){
                    require_once $pageFileUrl."/components/404.php";
                    die();
                }

                $gotoPage = $selectedPage["fullDir"];
                if(!file_exists($gotoPage)){
                    require_once $pageFileUrl."/components/404.php";
                    die();
                }

                $addDefault = false;
                require_once($gotoPage);
            }
            if($addDefault){
                require_once($pageFileUrl."/public/home.php");
            }
    }
