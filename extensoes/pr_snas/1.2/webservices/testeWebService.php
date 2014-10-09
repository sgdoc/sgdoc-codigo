<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

$debugNuSoap = 'Nada a declarar';

function imprimir($debug, $orgao, $utf) {
	echo "\n\nDebug (NuSoapClient) para  o orgao: $orgao, ".($utf ? 'com' : 'sem')." UTF8\n\n";
	echo $debug;
	echo "\nFim Debug (NuSoapClient): -------------------------------------------------\n";
}

function testarErroNuSoap($client) {
	if ($client->fault) {
		echo "[ ERRO ]\n";
		$debugNuSoap = $client->getDebug();
		throw new Exception(print($result));
	}
	$err = $client->getError();
	if ($err) {
		echo "[ ERRO ]\n";
		$debugNuSoap = $client->getDebug();
		throw new Exception($err);
	}
}

echo "Início...\n";
try {
	
	include_once(dirname(__FILE__) . '/include.soap.php');
	
	// MDA - Ministério do Desenvolvimento Agrário - MDA
	//$codigoSiorg = '17125';
	//29260
	//$arrCodSiorg = array('17125', '17125', '29260', '29260');
	$arrCodSiorg = array('17125');
	$exercicio = '2014';
	
	$configWs = array(
		'wsdl_url' 	=> 'https://testews.siop.gov.br/services/WSQualitativo?wsdl',
		'namespace' => 'http://servicoweb.siop.sof.planejamento.gov.br/',
		'usuario' 	=> 'WS-SGDOC',
		'senha' 	=> 'eebeca4ba6657c201d96cd06bec548a1',
		'perfil' 	=> '32'
	);
	
	$proxy = array(
		'server'	=> false, //'10.32.80.192', //PROXY ELIO
		'port'		=> false, //'9090',
		'username'	=> false,
		'password'	=> false
	);
	
	$bolUtf = false;
	
	foreach ($arrCodSiorg as $codigoSiorg) {
		
		$orgaosParametros = array(
			'credencial'	=> array('perfil' => $configWs['perfil'], 'senha' => $configWs['senha'], 'usuario' => $configWs['usuario']),
			'exercicio'		=> $exercicio,
			'codigoSiorg'	=> $codigoSiorg
		);
		
		echo "\n\nBuscando o orgao: $codigoSiorg, ".($bolUtf ? 'com' : 'sem')." UTF8\n\n";	
		echo "Criando cliente para o WebService {$configWs['wsdl_url']}.....";
		$client = new nusoap_client($configWs['wsdl_url'], false, $proxy['server'], $proxy['port'], $proxy['username'], $proxy['password'], 0, 3000 );
		//$client = new nusoap_client($configWs['wsdl_url'], 0, 3000 );
		//$client->setHTTPProxy($proxy['server'], $proxy['port']);
		$client->setDebugLevel(9);
		$client->setUseCURL(true);
		$client->decode_utf8 = $bolUtf;
		
		$bolUtf = !$bolUtf;
		
		testarErroNuSoap($client);
		echo "[ OK ]\n";
		
		echo "Chamando o método 'obterOrgaoPorCodigoSiorg(codigoSiorg=$codigoSiorg, exercicio=$exercicio)'.....\n";
		$result = $client->call('obterOrgaoPorCodigoSiorg', $orgaosParametros, $configWs['namespace']);
		
		echo "Validando o retorno.....";
		testarErroNuSoap($client);
		echo "[ OK ]\n";
		
		echo "Obtendo registros.....";
		$registros = array();
		if (array_key_exists('registros', $result)) {
			if(isset($result['registros'][0])) {
				$registros = $result['registros'];
			} else {
				$registros[0] = $result['registros'];
			}
			echo "[ OK ]\nRegistros:\n";
			foreach ($registros as $reg) {
				echo "     * {$reg['codigoOrgao']} - {$reg['descricao']}\n";
			}
			echo "Fim\n";
			//exit;
		} else {
			echo "[ ATENCAO - Não existem registros deste orgao ]\n";
		}
		//imprimir($client->getDebug(), $codigoSiorg, $bolUtf);
	}
} catch (Exception $e) {
	echo "ERRO na linha {$e->getLine()}: " . $e->getMessage() . "\n";
	imprimir($debugNuSoap, $codigoSiorg, $bolUtf);
} catch (SoapFault $ex) {
	echo "ERRO SOAP na linha {$ex->getLine()}: " . $ex->getMessage() . "\n";
	imprimir($debugNuSoap, $codigoSiorg, $bolUtf);
}

echo "Fim\n";