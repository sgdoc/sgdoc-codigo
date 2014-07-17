<?php

ini_set("soap.wsdl_cache_enabled", 0);
ini_set("soap.wsdl_cache_dir", "/tmp");
ini_set("soap.wsdl_cache_ttl", 86400);

if (isset($_GET['wsdl'])) {

    $autodiscover = new Zend_Soap_AutoDiscover();
    $autodiscover->setBindingStyle(array('style' => 'rpc'));
    $autodiscover->setOperationBodyStyle(array('use' => 'literal'));

    $autodiscover->setClass('ProviderWebServiceSyncUnits');

    $data = file_get_contents('php://input');
    $autodiscover->handle($data);
} else {

    $server = new Zend_Soap_Server(__URLSERVERAPP__ . '/webservices/unidades/server.php?wsdl', array('cache_wsdl' => false));
    $server->setClass('ProviderWebServiceSyncUnits');
    $server->setPersistence(SOAP_PERSISTENCE_REQUEST);

    $data = file_get_contents('php://input');
    $server->handle($data);
}