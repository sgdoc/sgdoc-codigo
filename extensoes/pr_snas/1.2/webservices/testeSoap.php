<?php

include_once(dirname(__FILE__) . '/include.soap.php');

$ambiente = 'dsv';

define('CF_APP_BASE_PATH', realpath(__DIR__) . '/../../../..');
define('CF_APP_ENVIRONMENT', $ambiente);

include_once( dirname(__FILE__) . '/ConfigWs.php' );

ConfigWs::factory()
	->buildDBConfig()->buildAppConstants()
	->buildAppDefines()->buildEnvironment();

$exec = obterAcoesPorPrograma('0911', '2014');
print_r($exec);
	