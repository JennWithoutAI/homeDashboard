<?php
if(!defined('MODULE_FILEURL_autoSecurity')){
    echo "Security Module not enabled!";
    return;
}
$url = $_GET["page"];
?>

<hr>
<div class="page-container">
    <h3>Add websites to scan</h3>
    <form method="post" action="?page=<?php echo $url; ?>">
        <input type="text" name="addWebsites" hidden valy>
        <label for="protocol">Protocol</label>
        <select id="protocol" name="protocol">
            <option value="http" >HTTP</option>
            <option value="https" selected>HTTPS</option>
        </select>

        <label for="websiteName">Name of website</label>
        <input type="text" id="websiteName" name="websiteName" required>

        <label for="domainExt">Domain</label>
        <select id="domainExt" name="domainExt">
            <option value=".nl" selected>.nl</option>
            <option value=".com">.com</option>
            <option value=".net">.net</option>
        </select>

        <input type="submit" value="Add website">
    </form>
</div>

<?php


$allWebsites = json_decode(file_get_contents(JSON_FILEURL."/autoSecurity/websites.json"),true);
$html = "";
$html .= "        
         <div class='autoHtml-ports-container'> 
            <h1 class='title'> Sites </h1>
            <div class='card-container'>
          
            ";
    foreach($allWebsites as $name => $siteData){
        $html .= "
             <form method='post' action='?page=".$url."'>
                <div class='ports-card'>
                    <div class='ports-header'>
                        <span class='port'><b><span class='dots'></span>{$name}</span></b>
                    </div>
                    <input hidden name='testSite' value='$name'>
                    <input class='port-button' type='submit' value='test the site'>
                    <a class='port-button' href='?page=autoSecurity&updateReportStatus=true'><div> update scan </div></a>
                </div>
            </form>
        ";
    }
    $html .= "</div></div></div>";
    echo $html;
    require_once(MODULE_FILEURL_autoSecurity."/moduleFunctions/zap.php");
    $zapClass = new zap;
    $zapClass->showScans();

?>
