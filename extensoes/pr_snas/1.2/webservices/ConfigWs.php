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
	
}
