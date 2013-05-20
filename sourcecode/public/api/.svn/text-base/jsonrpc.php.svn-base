<?php
require_once 'Zend/Application.php';
require_once 'Zend/Json/Server.php';


class Webservice {
    public function takeTicket($ticket_cat_id) {
        return "ok";
    }
}

$server = new Zend_Json_Server();

// Indicate what functionality is available:
$server->setClass('Webservice');

if ('GET' == $_SERVER['REQUEST_METHOD']) {
    $server->setEnvelope(Zend_Json_Server_Smd::ENV_JSONRPC_2);

    // Grab the SMD
    $smd = $server->getServiceMap();

    // Return the SMD to the client
    //header('Content-Type: application/json');
    echo $smd;
    return;
}

// Handle the request:
$server->handle();
?>