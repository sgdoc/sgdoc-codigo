<?php

$session = new Zend_Session_Namespace('notifications');

$session->last = (integer) substr(microtime(), 11, 10);
$session->next = ($session->last + (integer) $_REQUEST['time']);

print(json_encode(array('status' => 'success')));