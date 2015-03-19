<?php
require "lib/restAPI.php";

$API = new restAPI($_GET['request']);

if ( $API->response ) {
    echo $API->response;
} else {
    $API->processRequest();
}

?>
