<?php
class toolbox {

}
class zap
{
    // Vars
    private $zapLocation = "sec_zap:8090";
    private $personalZapLocation = "localhost:8090";
    public function __construct()
    {
        $this->json("create");
        $this->requests();
    }
    // items
    public function scanSite($url)
    {
        $jsonData = $this->json("getData");
        // start scan
        if(!$this->lock("create")){ return "error! LOCK"; };
        if(!$this->spider($url) === false){ return "error! SPIDER"; }
    }
    private function requests(){
        if(isset($_GET["updateReportStatus"])){ $this->updateScanData(); }
    }
    private function updateScanData(){
        if($this->lock("check")){ echo "no scan to update"; return;}
        $scans = $this->json("getData");
        if(!isset($scans["inProgress"]["zapScan"])){
            echo "none in progress to update";
            return;
        }
        $scan = $scans["inProgress"]["zapScan"];
        // get update
        $statusUrl = "http://" . $this->zapLocation . "/JSON/spider/view/status/?" . http_build_query([
                'scanId' => $scan["id"],
            ]);
        $waitResponse = json_decode($this->curl($statusUrl), true);
        if(!isset($waitResponse["status"])){
            return false;
        }

        $percentage = $waitResponse["status"];;
        if($percentage === "100"){

            $scan["status"] = "finished";
            $scan["percentage"] = "100";
            $scans["zapScans"]["historyScans"][] = $scan;

            $this->lock("destroy");

            unset($scans["inProgress"]["zapScan"]);
        } else {
            $scans["inProgress"]["zapScan"]["percentage"] = $percentage;
        }

        $this->json("put",$scans);

        echo "Site : ".$scan["baseurl"]."<br>";
        echo "Percentage : ".$scan["percentage"];
    }
    public function showScans()
    {
    }
        // TOOLS!
    private function spider($url){

        $sql = new nmap();
        $sql->scanSite($url);
        die();
        $spiderUrl = "http://" . $this->zapLocation . "/JSON/spider/action/scan/?" . http_build_query([
                'url' => $url,
            ]);

        $scanResponse = json_decode($this->curl($spiderUrl), true);
        if (!isset($scanResponse["scan"])) {
            echo "SOMETHING WENT WRONG STARTING SPIDER";
            return false;
        }

        $spiderScanId = $scanResponse["scan"];

        $jsonPutData = $this->json("getData");
        if (!is_array($jsonPutData)) {
            $jsonPutData = [];
        }

        $jsonPutData["inProgress"]["zapScan"] =  [
            "id" => $spiderScanId,
            "status"    => "started",
            "percentage" => "0",
            "type"      => "spider",
            "started"   => time(),
            "baseurl"   => $url,
        ];
        $this->json("put",$jsonPutData);
    }
    private function lock($option = false){
        $lockFileLocation = MODULE_FILEURL_autoSecurity."/scan.lock";
        if($option === "check"){
            if(file_exists($lockFileLocation)){
                return false;
            }
            return true;
        }

        if($option === "create"){
            if($this->lock("check") === false){
                echo "ERROR : Scan a scan is already in progress";
                return false;
            }
            file_put_contents($lockFileLocation,time());
            return true;
        }
        if($option === "destroy"){
            unlink($lockFileLocation);
            return true;
        }
    }
    private function json($option = false,$data = [])
    {
        $scansJsonFile = JSON_FILEURL . "/autoSecurity/scans.json";
        // init
        if ($option === "create") {
            if (!file_exists($scansJsonFile)) {
                file_put_contents($scansJsonFile, json_encode([]));
            }
            return;
        }

        if ($option === "getData"){
            $jsonData = json_decode(file_get_contents($scansJsonFile), true);
            return $jsonData;
        }

        if ($option === "put"){
            file_put_contents($scansJsonFile, json_encode($data));
        }
    }
    private function curl(string $url, array $headers = []): string|false {
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
class nmap {
    private $sqlmapLocation = "sec_sqlmap:8775";
    private $personalSqlmapLocation = "localhost:8775";

    public function scanSite($url){
        $headers = [
            CURLOPT_USERPWD => getenv('SQLMAP_API_USER') . ':' . getenv('SQLMAP_API_PASS'),
        ];
        $response = $this->curl($url,$headers);
        var_dump($response);
    }
    // tools
    private function curl(string $url, array $headers = []): string|false {
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
        print_r($raw);
        var_dump($raw);
        die();
        if ($raw === false) {
            echo curl_errno($ch) . ': ' . curl_error($ch);
        }

        unset($ch);
        return $raw;
    }
}
