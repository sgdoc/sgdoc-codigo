<?php
/*  INICIALIZAÇÃO DAS CONFIGURAÇÕES DO SISTEMA   */

include_once(dirname(__FILE__) . '/../../../../classes/CFUtils.php');
include_once(dirname(__FILE__) . '/../../../../classes/CFConfig.php');
include_once(dirname(__FILE__) . '/../../../../classes/Config.php');

class ConfigWs extends Config {
	
	public static function factory() {
		return new self();
	}
	
	public function getConnection() {
		try {
		
			$config = self::factory()->buildDBConfig();
		
			$this->connection = new PDO("{$config->driver}:host={$config->host};dbname={$config->database}", $config->user, $config->password);
		
			$this->connection->setAttribute(PDO::ATTR_CASE, PDO::CASE_UPPER);
			$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->connection->query("set search_path to {$config->schema}");
			return $this->connection;
			
		} catch (PDOException $e) {
			$this->connection = null;
			//throw new Exception($e->getMessage());
			echo $e->getMessage();
		}
	}
	
	
	public function getSiopConfig($origemDados) {
		return array(
			'wsdl_url' 	=> $this->_params["ws.siop.{$origemDados}.wsdl_url"],
			'namespace' => $this->_params["ws.siop.{$origemDados}.namespace"],
			'usuario' 	=> $this->_params["ws.siop.{$origemDados}.usuario"],
			'senha' 	=> $this->_params["ws.siop.{$origemDados}.senha"],
			'perfil' 	=> $this->_params["ws.siop.{$origemDados}.perfil"]
		);
	}
	
	public function getSiopProxyConfig() {
		return array(
			'server'	=> ($this->_params['ws.siop.proxy.server'] === '') ? false : $this->_params['ws.siop.proxy.server'],
			'port'		=> ($this->_params['ws.siop.proxy.port'] === '') ? false : $this->_params['ws.siop.proxy.port'],
			'username'	=> ($this->_params['ws.siop.proxy.username'] === '') ? false : $this->_params['ws.siop.proxy.username'],
			'password'	=> ($this->_params['ws.siop.proxy.password'] === '') ? false : $this->_params['ws.siop.proxy.password']
		);
	}
	
	public function getSiopCertificateConfig() {
		return array(
			'crt'	=> trim( ($this->_params['ws.siop.crt.caminho'] === '') ? false : $this->_params['ws.siop.crt.caminho'] ),
			'key'	=> trim( ($this->_params['ws.siop.key.caminho'] === '') ? false : $this->_params['ws.siop.key.caminho'] ),
			'pem'	=> trim( ($this->_params['ws.siop.pem.caminho'] === '') ? false : $this->_params['ws.siop.pem.caminho'] )
		);		
	}
	
}
