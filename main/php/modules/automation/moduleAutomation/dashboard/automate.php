<?php
    if(!defined('MODULE_FILEURL_dashboardLogic')){
        return;
    }

    // add all the things you can imagine lik
    // config
    $enableAutomation = [];
    $enableAutomation["navbar"] = true;

    // activate
    new automateDashboard($enableAutomation);

    class automateDashboard {
        public function __construct($list)
        {
            foreach($list as $functionName => $value){
                if($value){ $this->$functionName();}

            }
        }
        private function createStarterFrontend(){
            $pagesMainDir = MODULE_FILEURL_dashboardLogic."/pages";
            // i can make a loop but for once i wont
            if(!is_dir($pagesMainDir."/components")){
                mkdir($pagesMainDir);
            }
            if(!is_dir($pagesMainDir."/private")){
                mkdir($pagesMainDir);
            }
            if(!is_dir($pagesMainDir."/public")){
                mkdir($pagesMainDir);
            }
            // usually you want to make your own nav but for failsafe purposes ill make it like this
            if(!file_exists($pagesMainDir."/components/nav.php")){
                file_put_contents($pagesMainDir."/components/nav.php","");
            }
            if(!file_exists($pagesMainDir."/public/home.php")){
                file_put_contents($pagesMainDir."/public/home.php","");
            }
        }
        private function navbar(){
            if(!file_exists(MODULE_FILEURL_dashboardLogic."/pages/components/nav.php")){ $this->createStarterFrontend(); }
            $navbarJsonFileDir = JSON_FILEURL."/dashboard/nav.json";
            if(!file_exists($navbarJsonFileDir)){
                // the extra key is for multiple navBars If ever required
                $defaultData["navItems"][0]["name"] = "home";
                $defaultData["navItems"][0]["href"] = "/?page=home";
                $defaultData["navItems"][0]["rank"] = 0;
                $defaultData["navItems"][0]["enabled"] = true;
                $defaultData["navItems"][0]["type"] = "public";
                $defaultData["navItems"][0]["fullDir"] = MODULE_FILEURL_dashboardLogic."/pages/public/home.php";
                file_put_contents($navbarJsonFileDir,json_encode($defaultData,true));
            }
            $pageDirs = MODULE_FILEURL_dashboardLogic."/pages";
            $scannableDirs = ["public","private"];

            $currentNavs = json_decode(file_get_contents($navbarJsonFileDir),true);
            $starterIndex = 0;
            $initKey = count($currentNavs["navItems"]);
            $update = false;
            foreach($scannableDirs as $scanDir){
                $publicDirs = scandir($pageDirs."/".$scanDir);
                foreach($publicDirs as $file){
                    $fullUrlFile = $pageDirs."/".$scanDir."/".$file;
                    if($file === "." || $file === ".." || !is_file($fullUrlFile)){ continue; }

                    $alreadyExists = false;
                    foreach($currentNavs["navItems"] as $checkForFullDir){
                        if($checkForFullDir["rank"] > $starterIndex){ $starterIndex = $checkForFullDir["rank"]; }
                        if($fullUrlFile === $checkForFullDir["fullDir"]){ $alreadyExists = true; break; }
                    }
                    if($alreadyExists){ continue; }

                    $withoutExt = preg_replace('/\.\w+$/', '', $file);

                    $update = true;
                    $currentNavs["navItems"][$initKey]["name"]    = $withoutExt;
                    $currentNavs["navItems"][$initKey]["href"]    = "/?page=".$withoutExt;
                    $currentNavs["navItems"][$initKey]["rank"]    = ++$starterIndex;
                    $currentNavs["navItems"][$initKey]["enabled"] = true;
                    $currentNavs["navItems"][$initKey]["type"]    = $scanDir;
                    $currentNavs["navItems"][$initKey]["fullDir"] = $fullUrlFile;
                    $initKey++;
                }
            }
            if($update){
                file_put_contents($navbarJsonFileDir,json_encode($currentNavs,true));
            }
        }
    }
?>