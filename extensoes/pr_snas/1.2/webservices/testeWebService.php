<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
echo "Início...\n";
try {
	
	include_once(dirname(__FILE__) . '/include.soap.php');
	
	// MDA - Ministério do Desenvolvimento Agrário - MDA
	$codigoSiorg = '17125';
	$exercicio = '2014';
	
	$configWs = array(
		'wsdl_url' 	=> 'https://testews.siop.gov.br/services/WSQualitativo?wsdl',
		'namespace' => 'http://servicoweb.siop.sof.planejamento.gov.br/',
		'usuario' 	=> 'WS-SGDOC',
		'senha' 	=> 'eebeca4ba6657c201d96cd06bec548a1',
		'perfil' 	=> '32'
	);
	
	$orgaosParametros = array(
		'credencial'	=> array('perfil' => $configWs['perfil'], 'senha' => $configWs['senha'], 'usuario' => $configWs['usuario']),
		'exercicio'		=> $exercicio,
		'codigoSiorg'	=> $codigoSiorg
	);
	
	$proxy = array(
		'server'	=> false,
		'port'		=> false,
		'username'	=> false,
		'password'	=> false
	);
	
	echo "Criando cliente para o WebService {$configWs['wsdl_url']}.....";
	$client = new nusoap_client($configWs['wsdl_url'], $proxy['server'], $proxy['port'], $proxy['username'], $proxy['password'], 0, 3000 );
	$client->setDebugLevel(9);
	$client->setUseCURL(true);
	
	$err = $client->getError();
	if ($err) {
		echo "[ ERRO ]\n";
		throw new Exception($err);
	}
	echo "[ OK ]\n";
	
	echo "Chamando o método 'obterOrgaoPorCodigoSiorg(codigoSiorg=$codigoSiorg, exercicio=$exercicio)'.....\n";
	$result = $client->call('obterOrgaoPorCodigoSiorg', $orgaosParametros, $configWs['namespace']);
	
	//print_r($result);exit;
	
	echo "Validando o retorno.....";
	if ($client->fault) {
		echo "[ ERRO ]\n";
		throw new Exception(print($result));
	} else {
		$err = $client->getError();
		if ($err) {
			echo "[ ERRO ]\n";
			throw new Exception($err);
		}
	}
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
		exit;
	} else {
		echo "[ ATENCAO - Não existem registros deste orgao ]\n";
	}
	
	echo "Debug (NuSoapClient): -----------------------------------------------------\n";
	echo $client->getDebug();
	echo "Fim Debug (NuSoapClient): -------------------------------------------------\n";
	
} catch (Exception $e) {
	echo "ERRO na linha {$e->getLine()}: " . $e->getMessage() . "\n";
} catch (SoapFault $ex) {
	echo "ERRO SOAP na linha {$ex->getLine()}: " . $ex->getMessage() . "\n";
}
echo "Fim\n";