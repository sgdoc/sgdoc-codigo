<?php

define("TIPO_INTEIRO", 0);
define("TIPO_NUMERAL", 1);
define("TIPO_TEXTUAL", 2);
define("TIPO_BOOLEAN", 3);

class DB {

	protected $conexao;
	
	function __construct() {
		
		$this->conexao = $this->obterConexao();
	}
	
	function obterConexao() {
	try {	
			$config = $this->obterConfiguracao();

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
	
	
	function atualizarDados($tabela, $condicao, $valores) {		
		$out = array();
		try {
			$operacao = "UPDATE {$tabela} SET {$valores} WHERE {$condicao}\n";
			//echo $operacao; die;
			/*** INSERT data ***/
			$out['count'] = $this->conexao->exec($operacao);
			return $out;
		} catch (Exception $e) {
			$this->agora("Erro ao obter dados SIOP:");
			var_dump($e->getMessage());
			echo "Parâmetros passados:\n";
			var_dump($destino, $condicao, $ordem, $limite);
		}
		return $out;
	}
	
	
	function obterDados($tabela, $campo = null, $condicao = null, $ordem = null, $limite = null ) {
		$out = array();
		try {
	
			$where = '';
			
			$campos = '*';
			if(!is_null($campo)) {
				$campos = $campo;
				if(is_array($campo)) {
					$campos = implode(" , ", $campo);
				} 
			}
			
			if(!is_null($condicao)) {
				$condicoes = $condicao;
				if(is_array($condicao)) {
					$condicoes = implode(' and ', $condicao);
				}
				$where = " where {$condicoes} ";
			}
	
			$order = '';
			if(!is_null($ordem)) {
				$ordenadores = $ordem;
				if(is_array($ordem)) {
					$ordenadores = implode(' , ', $ordem);
				}
				$order = " order by {$ordenadores} ";
			}
	
			$limit = '';
			if(!is_null($limite)) {
				$limit = " limit $limite ";
			}
	
			$operacao = "select {$campos} from {$tabela} {$where} {$order} {$limit}";
			$stmt = $this->conexao->prepare($operacao);
			$stmt->execute();
			$out = array();
			while($tuple = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$out[] = $tuple;
			}
		} catch (Exception $e) {
			$this->agora("Erro ao obter dados SIOP:");
			var_dump($e->getMessage());
			echo "Parâmetros passados:\n";
			var_dump($destino, $condicao, $ordem, $limite);
		}
		return $out;
	}

	function obterConfiguracao() {
		$config = new ArrayObject();
		$config->driver 	= 'pgsql';
		$config->host 		= '10.31.80.111';
		$config->database	= 'db_sgdoc4';
		$config->user 		= 'usr_pr_sgdoc4';
		$config->password	= 'usr_pr_sgdoc4';
		$config->schema		= 'sgdoc';	
		
		return $config;
	}
	

	function agora($mensagem = "", $quebraDeLinha = true) {
		echo date("H:i:s");
		if($mensagem !== "")
			echo " ==> {$mensagem}";
		if($quebraDeLinha)
			echo "\n";
	}

}