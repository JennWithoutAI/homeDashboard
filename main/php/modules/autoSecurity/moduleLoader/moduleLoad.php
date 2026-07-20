<?php
    // init
    if(!file_exists("init.lock")){
        // startup
        if(!is_dir(JSON_FILEURL."/autoSecurity")){mkdir(JSON_FILEURL."/autoSecurity");}
        $websiteListJsonUrl = JSON_FILEURL."/autoSecurity/websites.json";
        if(!file_exists($websiteListJsonUrl)){ file_put_contents($websiteListJsonUrl,json_encode([]));}
    } else { $websiteListJsonUrl = JSON_FILEURL."/autoSecurity/websites.json"; }


    // POSTS
    if(isset($_POST["addWebsites"])){
        $postData = $_POST;
        $requiredExts = [
            "protocol",
            "websiteName",
        ];
        foreach($requiredExts as $required){
            if(!isset($postData[$required])){
                die("ERROR");
            }
        }
            $url = $postData["protocol"]."://".$postData["websiteName"].$postData["domainExt"];
            if(!urlExists($url)){
                echo "URL NOT VALID!";
                return;
            }
            // add
            $addToJson[$url] = [
                "url" => $url,
                "modules" => "all",
                "results" => ""
            ];
            file_put_contents($websiteListJsonUrl,json_encode($addToJson));
    }
    if(isset($_POST["testSite"])){
        $siteName = $_POST["testSite"];
        $websiteJson = json_decode(file_get_contents($websiteListJsonUrl),true);
        if(isset($siteName)){
            if(!isset($websiteJson[$siteName])){
                die("CANT TEST SITE! ERROR");
            }
            $siteData = $websiteJson[$siteName];
            #   9001  testssl.sh HTTP wrapper
            #   9002  Nuclei HTTP wrapper
            #   9003  Trivy HTTP wrapper
            #   9004  sqlmap REST API
            #   9005  ZAP REST API
            $tests = [
                "9001" => "testssl",
                "9002" => "Nuclei HTTP",
                "9003" => "Trivy HTTP",
                "9004" => "sqlmap REST",
                "8090" => "ZAP REST"
            ];

            require_once(MODULE_FILEURL_autoSecurity."/moduleFunctions/zap.php");
            $zap = new zap();
            $zap->scanSite($siteName);
        }
    }







function urlExists($url) {
    $context = stream_context_create([
        'http' => [
            'method'        => 'HEAD',
            'follow_location' => 1,
            'max_redirects'   => 5,
            'timeout'         => 10,
            'header'          => "User-Agent: Mozilla/5.0 (compatible; URLChecker/1.0)\r\n"
        ],
        'ssl' => [
            'verify_peer'      => false,
            'verify_peer_name' => false,
        ],
    ]);

    $headers = @get_headers($url, 1, $context);

    if ($headers === false) {
        return false;
    }

    $statusLine = is_array($headers[0]) ? end($headers[0]) : $headers[0];
    preg_match('/HTTP\/\d\.\d\s+(\d+)/', $statusLine, $matches);
    $statusCode = isset($matches[1]) ? (int)$matches[1] : 0;

    return $statusCode >= 200 && $statusCode < 400;
}


    
?>