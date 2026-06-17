<?php
    if(!defined('MODULE_FILEURL_dashboardLogic')){
        return;
    }

    // add all the things you can imagine lik
    // config
    $enableAutomation = [];
    $enableAutomation["navbar"] = true;
    $enableAutomation["scanDirs"] = true;

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
        private function scanDirs(){
            if(!file_exists(JSON_FILEURL."/dashboard/names.json")){
                require_once(MODULE_FILEURL_dashboardLogic."/scanPorts.php");
                scanPorts(true);
            }

        }
        private function navbar(){
            if(!file_exists(MODULE_FILEURL_dashboardLogic."/pages/components/nav.php")){ $this->createStarterFrontend(); }
            $navbarJsonFileDir = JSON_FILEURL."/dashboard/nav.json";
            if(!file_exists($navbarJsonFileDir)){
                // the extra key is for multiple navBars If ever required
                $defaultData["navItems"]["home"]["name"] = "home";
                $defaultData["navItems"]["home"]["href"] = "/?page=home";
                $defaultData["navItems"]["home"]["rank"] = 0;
                $defaultData["navItems"]["home"]["enabled"] = true;
                $defaultData["navItems"]["home"]["type"] = "public";
                $defaultData["navItems"]["home"]["fullDir"] = MODULE_FILEURL_dashboardLogic."/pages/public/home.php";
                file_put_contents($navbarJsonFileDir,json_encode($defaultData,true));
            }
            $pageDirs = MODULE_FILEURL_dashboardLogic."/pages";
            $scannableDirs = ["public","private"];

            $currentNavs = json_decode(file_get_contents($navbarJsonFileDir),true);
            $starterIndex = 0;
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
                    $currentNavs["navItems"][$withoutExt]["name"]    = $withoutExt;
                    $currentNavs["navItems"][$withoutExt]["href"]    = "/?page=".$withoutExt;
                    $currentNavs["navItems"][$withoutExt]["rank"]    = ++$starterIndex;
                    $currentNavs["navItems"][$withoutExt]["enabled"] = true; // keep it true until prod
                    $currentNavs["navItems"][$withoutExt]["type"]    = $scanDir;
                    $currentNavs["navItems"][$withoutExt]["fullDir"] = $fullUrlFile;
                }
            }
            if($update){
                file_put_contents($navbarJsonFileDir,json_encode($currentNavs,true));
            }
        }
    }
?>