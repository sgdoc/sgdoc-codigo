<?php
include("./include.soap.php");

class Siorg extends Soap {

	function __construct() {
		$this->setConfiguracao(
				'http://estruturaorganizacional.dados.gov.br/ConsultarEstruturaWSImpl?wsdl' , 
				'http://estruturaorganizacional.dados.gov.br/'
		);
		$this->inicializarWebService();
	}
	
	function consultarEnderecoContato($unidade) {
		$metodo = 'consultarEnderecoContato';
		$parametros = array(
				'codigoUnidade' => $unidade
		);
		return $this->acessar($metodo, $parametros);
	}

}
