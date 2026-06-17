<?php
    // i really have to lock this down with security but thats later
    if(isset($_POST)){
        if(isset($_POST["CONTROL_saveChanges"])){
            if(isset($_POST["CONTROL_jsonField"])){
                if(!isset($_POST["CONTROL_moduleFullFile"])){require_once(MODULE_FILEURL_dashboardLogic."/pages/components/404.php");  }
                    $moduleFile = $_POST["CONTROL_moduleFullFile"];
                    $moduleJson = $_POST["CONTROL_jsonField"];
                    $data = json_decode($moduleJson, true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        echo "INVALID JSON, do it again";
                    } else {
                        file_put_contents($moduleFile,$moduleJson);
                        echo "updated ".$moduleFile;
                    }
            }
        }
        if(isset($_POST["CONTROL_resetForm"])){
            if(isset($_POST["CONTROL_resetForm"])){
                if(!isset($_POST["CONTROL_moduleFullFile"])){require_once(MODULE_FILEURL_dashboardLogic."/pages/components/404.php");  }
                $moduleFile = $_POST["CONTROL_moduleFullFile"];
                unlink($moduleFile);
                $page = "";
                if(isset($_GET["page"])){ $page = "?page=".$_GET["page"];}
                $full = $page."&unlock=true";
                header("Location: " .$full);
            }
        }

    }


    require_once(MODULE_FILEURL_dashboardLogic."/router.php");
?>