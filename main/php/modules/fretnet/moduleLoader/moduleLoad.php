<?php


//  note dashboard V1 has a page dashboard flaw so always use ?page & fretPage
fretrouter();
function fretrouter(){
    if(isset($_GET["fretpage"])){
        $fretPage = $_GET["fretpage"];
        if($fretPage === "carousel"){
            require_once(MODULE_FILEURL_fretnet."/fretnetStaticHtml/ferretCarousel.php");
            die();
        }
        require_once(MODULE_FILEURL_fretnet."/fretnetStaticHtml/index.php");
        die();
    }
}




?>