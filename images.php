<?php

require_once("../../config.php");

list($a, $req)  =  explode('images.php', $_SERVER['REQUEST_URI']);

    $fp = fopen($CFG->dataroot.$req, "r");
    if ($fp) {
        header("Content-type: image/jpg");
        fpassthru($fp);
        fclose($fp);
    } else {
        //header("HTTP/1.1 500 Internal Server Error");
        header("Content-type: application/octet-stream");
        echo "File no found";
    }

