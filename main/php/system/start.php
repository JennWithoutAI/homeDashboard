<?php
    session_start();
    // CONSTS
    define("BASEURL", $_SERVER["DOCUMENT_ROOT"]);
    const MODULE_FILEURL = BASEURL."/modules";
    const MODULE_URL = "/modules";
    const JSON_URL = "/jsonSheets";
    const JSON_FILEURL = BASEURL."/jsonSheets";
    // start Onload process & init system
    require_once("./system/onload.php");

    die();
?>