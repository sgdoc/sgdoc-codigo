<?php
require_once(dirname(__FILE__) . '/../../bibliotecas/nusoap/lib/nusoap.php');

class Soap {
	
	protected $configuracao;
	protected $certificado;
	protected $proxy;
	protected $client;
	
	function inicializarWebService() {
		$proxy = array();
		$proxy['server'] 	= (!is_null($this->proxy)) ? $this->proxy->server : false;
		$proxy['port'] 		= (!is_null($this->proxy)) ? $this->proxy->port : false;
		$proxy['username'] 	= (!is_null($this->proxy)) ? $this->proxy->username : false;
		$proxy['password'] 	= (!is_null($this->proxy)) ? $this->proxy->password : false;
		
		$client = new nusoap_client($this->configuracao->wsdl, false, $proxy['server'], $proxy['port'], $proxy['username'], $proxy['password'], 0, 3000 );		
		
		$client->setDebugLevel(9);
		$client->setUseCURL(true);
	
		if(!is_null($this->certificado)) {	
			if( strlen( $this->certificado->crt . $this->certificado->key . $this->certificado->pem ) > 0 ) {
				
				$client->setCurlOption(CURLOPT_SSL_VERIFYPEER, false);
				$client->setCurlOption(CURLOPT_SSL_VERIFYHOST, 2);
				
				$client->authtype = 'certificate';
				if( strlen($this->certificado->crt) > 0) {
					$client->certRequest['sslcertfile'] = $this->certificado->crt; # file containing the user's certificate
				}
				if( strlen($this->certificado->key) > 0 ) {
					$client->certRequest['sslkeyfile']  = $this->certificado->key; # file containing the private key
				}
				if( strlen($this->certificado->pem) > 0 ) {
					$client->certRequest['cainfofile']  = $this->certificado->pem;  # file containing the root certificate
				}
			}
		} 
		$this->client = $client;
	}
	
	function setCertificado($crt, $key, $pem) {
		$this->certificado = new ArrayObject();
		$this->certificado->crt = $crt;
		$this->certificado->key = $key;
		$this->certificado->pem = $pem;
	}
	
	function setConfiguracao($wsdl = null, $namespace = null) {
		$this->configuracao = new ArrayObject();
		$this->configuracao->wsdl = $wsdl;
		$this->configuracao->namespace = $namespace;
	}
	
	function setProxy($server = null, $port = null, $username = null, $password = null) {
		$this->proxy = new ArrayObject();
		$this->proxy->server = $server;
		$this->proxy->port = $port;
		$this->proxy->username = $username;
		$this->proxy->password = $password;
	}
	
	function acessar($metodo, $parametros = array(), $dto = null ) {
		$client = $this->client;
		
		$err = $client->getError();
		if ($err) {
			return array('sucesso' => false, 'mensagensErro' => $err);
		}
		$result = $client->call($metodo, $parametros, $this->configuracao->namespace);
		
		if ($client->fault) {
			return $result;
		} else {
			$err = $client->getError();
			if ($err) {
				return array('sucesso' => false, 'mensagensErro' => $err);
			}
		}
		$registros = array();
		if(is_null($dto)) {
			$registros = $result;
		} else {
			if(isset($result[$dto][0])) {
				$registros = $result[$dto];
			} else {
				$registros[0] = $result[$dto];
			}
		}
		return array( 'sucesso' => true, 'registros' => $registros );
	}

}
