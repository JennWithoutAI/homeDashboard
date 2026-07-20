<?php
class zapOld {
    private $zapLocation = "sec_zap:8090";
    private $personalZapLocation = "localhost:8090";
    private $scansJsonFile = JSON_FILEURL."/autoSecurity/scans.json";
    public function __construct(){
        $this->catchRequests();
    }

    public function scanSite($url){

        $spiderUrl = "http://" . $this->zapLocation . "/JSON/spider/action/scan/?" . http_build_query([
                'url' => $url,
            ]);
        $scanResponse = json_decode($this->curl($spiderUrl), true);
        if (!isset($scanResponse["scan"])) {
            die("SOMETHING WENT WRONG STARTING SPIDER");
        }
        $spiderScanId = $scanResponse["scan"];

        $jsonPutData = [
            $spiderScanId => [
                "status"    => "started",
                "percentage" => 0,
                "type"      => "spider",
                "started"   => time(),
                "baseurl"   => $url,
            ]
        ];

        file_put_contents($this->scansJsonFile, json_encode($jsonPutData));
    }
    public function scanSite($url, ?array $auth = null){
        // ...existing "clear previous scan" logic stays the same...

        $userId = null;
        if ($auth !== null) {
            $userId = $this->setupAuthentication(
                $url,
                $auth['loginUrl'],
                $auth['userField'],
                $auth['passField'],
                $auth['username'],
                $auth['password'],
                $auth['loggedInRegex']
            );
        }

        if ($userId !== null) {
            $spiderUrl = "http://{$this->zapLocation}/JSON/spider/action/scanAsUser/?" . http_build_query([
                    'url' => $url,
                    'contextId' => $auth['contextId'], // returned from setupAuthentication too, capture it
                    'userId' => $userId,
                ]);
        } else {
            $spiderUrl = "http://{$this->zapLocation}/JSON/spider/action/scan/?" . http_build_query([
                    'url' => $url,
                ]);
        }

        // ...rest unchanged (parse scan id, write scans.json)...
    }
    private function setupAuthentication(string $baseUrl, string $loginUrl, string $userField, string $passField, string $username, string $password, string $loggedInRegex): int {
        // 1. Create context
        $ctxResp = json_decode($this->curl("http://{$this->zapLocation}/JSON/context/action/newContext/?" .
            http_build_query(['contextName' => 'authCtx'])), true);
        $contextId = $ctxResp['contextId'];

        // 2. Include the site in the context
        $this->curl("http://{$this->zapLocation}/JSON/context/action/includeInContext/?" .
            http_build_query([
                'contextName' => 'authCtx',
                'regex' => preg_quote($baseUrl, '/') . '.*',
            ]));

        // 3. Set form-based auth method
        $loginRequestData = "{$userField}={%username%}&{$passField}={%password%}";
        $this->curl("http://{$this->zapLocation}/JSON/authentication/action/setAuthenticationMethod/?" .
            http_build_query([
                'contextId' => $contextId,
                'authMethodName' => 'formBasedAuthentication',
                'authMethodConfigParams' =>
                    "loginUrl=" . urlencode($loginUrl) .
                    "&loginRequestData=" . urlencode($loginRequestData),
            ]));

        // 4. Logged-in indicator (regex matched against response body/headers when session is valid)
        $this->curl("http://{$this->zapLocation}/JSON/authentication/action/setLoggedInIndicator/?" .
            http_build_query([
                'contextId' => $contextId,
                'loggedInIndicatorRegex' => $loggedInRegex,
            ]));

        // 5. Create user
        $userResp = json_decode($this->curl("http://{$this->zapLocation}/JSON/users/action/newUser/?" .
            http_build_query([
                'contextId' => $contextId,
                'name' => 'scanUser',
            ])), true);
        $userId = $userResp['userId'];

        $this->curl("http://{$this->zapLocation}/JSON/users/action/setAuthenticationCredentials/?" .
            http_build_query([
                'contextId' => $contextId,
                'userId' => $userId,
                'authCredentialsConfigParams' =>
                    "username=" . urlencode($username) . "&password=" . urlencode($password),
            ]));

        $this->curl("http://{$this->zapLocation}/JSON/users/action/setUserEnabled/?" .
            http_build_query([
                'contextId' => $contextId,
                'userId' => $userId,
                'enabled' => 'true',
            ]));

        return $userId;
    }
    private function clearSite(string $baseUrl): void {
        $deleteUrl = "http://" . $this->zapLocation . "/JSON/core/action/deleteSiteNode/?" . http_build_query([
                'url' => $baseUrl,
            ]);
        $this->curl($deleteUrl);
    }
    private function updateScanData(){
        if(!file_exists($this->scansJsonFile)){return;}
        $scans = json_decode(file_get_contents($this->scansJsonFile),true);
        if($scans === null){
            return;
        }
        foreach($scans as $id => $scan){
            if($scan["status"] === "finished"){continue;}
            $percentage = $this->askScanStatus($id,$scan["type"]);
            if($percentage === "100"){
                $scans[$id]["status"] = "finished";
            }
            $scans[$id]["percentage"] = $percentage;
        }
        file_put_contents($this->scansJsonFile,json_encode($scans));
    }
    public function appSimpelPay(){
        // user
        // email floydweij@gmail.com
        // pass cvATb7S3Jygq2g4Rg5rg5SK8e5f1
        // admin
    }

    public function getHtmlReport(string $baseUrl): string|false {
        $reportUrl = "http://" . $this->personalZapLocation . "/OTHER/core/other/htmlreport/?" . http_build_query([
                'baseurl' => $baseUrl,
            ]);
        return $reportUrl;
    }

    private function askScanStatus(string $scanId, string $type = "spider") {
        $endpoint = $type === "ascan"
            ? "/JSON/ascan/view/status/"
            : "/JSON/spider/view/status/";

            $statusUrl = "http://" . $this->zapLocation . $endpoint . "?" . http_build_query([
                    'scanId' => $scanId,
                ]);

            $waitResponse = json_decode($this->curl($statusUrl), true);

            if(!isset($waitResponse["status"])){
                return false;
            }
            return $waitResponse["status"];
    }

    public function downloadHtmlReport(string $baseUrl):void {
        ob_clean();
        $reportUrl = "http://" . $this->zapLocation . "/OTHER/core/other/htmlreport/?" . http_build_query([
                'baseurl' => $baseUrl,
            ]);
        $content = $this->curl($reportUrl);
        if ($content === false) {
            http_response_code(500);
            die("Failed to fetch report");
        }

        $filename = "zap_report_" . preg_replace('/[^a-zA-Z0-9]/', '_', $baseUrl) . ".html";

        header('Content-Type: text/html');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . strlen($content));
        echo $content;
        exit;
    }

    public function showScans(){
        if(!file_exists($this->scansJsonFile)){return;}
        $this->updateScanData();
        $scans = json_decode(file_get_contents($this->scansJsonFile),true);
        if($scans === null || empty($scans)){
            return;
        }

        // Only one scan is ever stored (ZAP doesn't support concurrent scans)
        $id   = array_key_first($scans);
        $attr = $scans[$id];

        $html = "
     <div class='autoHtml-ports-container'> 
        <h1 class='title'> Sites </h1>
        <div class='card-container'>
            <div class='ports-card'>
                <div class='ports-header'>
                    <span class='portname'>Scan : {$id}</span>
                </div>
                <hr>
                <div class=''>
                    {$attr['status']}
                </div>
    ";

        if($attr['status'] === 'finished'){
            $viewUrl = $this->getHtmlReport($attr["baseurl"]);
            $html .= "
            <a href='$viewUrl' class='port-button' target='_blank'> click to see Results</a><br>
            <a href='?page=autoSecurity&downloadReport={$attr["baseurl"]}' class='port-button' target='_blank'> click to Download results</a>
        ";
        } else {
            $html .= "
            <div class=''> Percentage -> 
                {$attr['percentage']}
            </div>
        ";
        }

        $html .= "</div></div></div>";
        echo $html;
    }

    public function curl(string $url, array $headers = []): string|false {
        $ch = curl_init();
        $options = [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 10,
        ];
        if (!empty($headers)) {
            $options[CURLOPT_HTTPHEADER] = $headers;
        }
        curl_setopt_array($ch, $options);
        $raw = curl_exec($ch);
        if ($raw === false) {
            echo curl_errno($ch) . ': ' . curl_error($ch);
        }
        unset($ch);
        return $raw;
    }
}