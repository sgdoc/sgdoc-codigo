<?php
require_once(dirname(__FILE__) . '/../bibliotecas/nusoap/lib/nusoap.php');

define("TIPO_INTEIRO", 0);
define("TIPO_NUMERAL", 1);
define("TIPO_TEXTUAL", 2);
define("TIPO_BOOLEAN", 3);


function inicializarWebServiceSOF($configuracao) {
	$proxy = ConfigWs::factory()->getSiopProxyConfig();
	$client = new nusoap_client($configuracao['wsdl_url'], false, $proxy['server'], $proxy['port'], $proxy['username'], $proxy['password'], 0, 3000 );
	$client->setDebugLevel(9);
	$client->setUseCURL(true);

	// Verifica se há algum dado de configuração para certificado. 
	// Caso houver define o tipo de autenticação para certificate.
	$certificado = ConfigWs::factory()->getSiopCertificateConfig();
	if( strlen( $certificado['crt'] . $certificado['key'] . $certificado['pem'] ) > 0 ) {
		
		$client->setCurlOption(CURLOPT_SSL_VERIFYPEER, false);
		$client->setCurlOption(CURLOPT_SSL_VERIFYHOST, 2);
		
		$client->authtype = 'certificate';
		if( strlen($certificado['crt']) > 0) {
			$client->certRequest['sslcertfile'] = $certificado['crt']; # file containing the user's certificate
		}
		if( strlen($certificado['key']) > 0 ) {
			$client->certRequest['sslkeyfile']  = $certificado['key']; # file containing the private key
		}
		if( strlen($certificado['pem']) > 0 ) {
			$client->certRequest['cainfofile']  = $certificado['pem'];  # file containing the root certificate
		}
		
	} 
	return $client;
}

function acessarWebServiceSOF($metodo, $parametros = array(), $configuracao = array(), $dto = 'registros' ) {

	$client = inicializarWebServiceSOF($configuracao);
	
	$err = $client->getError();
	if ($err) {
		return array('sucesso' => false, 'mensagensErro' => $err);
	}
	$result = $client->call($metodo, $parametros, $configuracao['namespace']);

	if ($client->fault) {
		return $result;
	} else {
		$err = $client->getError();
		if ($err) {
			return array('sucesso' => false, 'mensagensErro' => $err);
		}
	}
	$registros = array();
	if(isset($result[$dto][0])) {
		$registros = $result[$dto];
	} else {
		$registros[0] = $result[$dto];
	}
	return array( 'sucesso' => true, 'registros' => $registros );
}

function retornaCredenciais($configuracao){
	$credencial = array(
			'perfil'	=> $configuracao['perfil'],
			'senha'		=> $configuracao['senha'],
			'usuario'	=> $configuracao['usuario']
	);
	return $credencial;
}

function obterTabelaSiop($destino) {
	return "snas.tb_siop_{$destino}";	
}

function obterCampos($destino) {
	$campos = array(
		"metas" => array(				
					"identificadorUnico" => TIPO_INTEIRO,
					"codigoMomento" => TIPO_INTEIRO,
					"codigoMeta" => TIPO_INTEIRO,
					"exercicio" => TIPO_INTEIRO,
					"codigoObjetivo" => TIPO_NUMERAL,
					"codigoPrograma" => TIPO_NUMERAL,
				  	"descricao" => TIPO_TEXTUAL
				),
		"orgaos" => array(
					"codigoOrgao" => TIPO_INTEIRO,
					"exercicio" => TIPO_INTEIRO,
					"tipoOrgao" => TIPO_TEXTUAL,
					"codigoOrgaoPai" => TIPO_NUMERAL,
					"descricao" => TIPO_TEXTUAL,
					"descricaoAbreviada" => TIPO_TEXTUAL,
					"orgaoId"  => TIPO_INTEIRO,
					"orgaoSiorg" => TIPO_NUMERAL,
					"snAtivo" => TIPO_BOOLEAN
				),
		"objetivos" => array(
					"identificadorUnico" => TIPO_INTEIRO,
					"exercicio" => TIPO_INTEIRO,
					"codigoMomento" => TIPO_INTEIRO,
					"codigoObjetivo" => TIPO_NUMERAL,
					"codigoOrgao" => TIPO_NUMERAL,
					"codigoPrograma" => TIPO_NUMERAL,
					"enunciado" => TIPO_TEXTUAL,
					"snExclusaoLogica" => TIPO_BOOLEAN
				),
		"programas" => array(
					"identificadorUnico" => TIPO_INTEIRO,
					"codigoMomento" => TIPO_INTEIRO,
					"codigoOrgao" => TIPO_NUMERAL,
					"codigoPrograma" => TIPO_NUMERAL,
					"codigoTipoPrograma" => TIPO_NUMERAL,
					"estrategiaImplementacao" => TIPO_TEXTUAL,
					"exercicio" => TIPO_INTEIRO,
					"horizonteTemporalContinuo" => TIPO_INTEIRO,
					"justificativa" => TIPO_TEXTUAL,
					"objetivo" => TIPO_TEXTUAL,
					"problema" => TIPO_TEXTUAL,
					"publicoAlvo" => TIPO_TEXTUAL,
					"snExclusaoLogica" => TIPO_BOOLEAN,
					"titulo" => TIPO_TEXTUAL
				),
		"acoes" => array(
					"identificadorUnico" => TIPO_INTEIRO,
					"exercicio" => TIPO_INTEIRO,
					"codigoMomento" => TIPO_INTEIRO,
					"codigoTipoInclusaoAcao" => TIPO_INTEIRO,
					"titulo" => TIPO_TEXTUAL,
					"baseLegal" => TIPO_TEXTUAL,
					"descricao" => TIPO_TEXTUAL,
					"codigoAcao" => TIPO_NUMERAL,
					"codigoPrograma" => TIPO_NUMERAL,
					"codigoFuncao" => TIPO_NUMERAL,
					"codigoSubFuncao" => TIPO_NUMERAL,
					"codigoOrgao" => TIPO_NUMERAL,
					"codigoEsfera" => TIPO_NUMERAL,
					"codigoTipoAcao" => TIPO_NUMERAL,
					"snDireta" => TIPO_BOOLEAN,
					"snDescentralizada" => TIPO_BOOLEAN,
					"snLinhaCredito" => TIPO_BOOLEAN,
					"snTransferenciaObrigatoria" => TIPO_BOOLEAN,
					"snTransferenciaVoluntaria" => TIPO_BOOLEAN,
					"snExclusaoLogica" => TIPO_BOOLEAN,
					"snRegionalizarNaExecucao" => TIPO_BOOLEAN,
					"snAquisicaoInsumoEstrategico" => TIPO_BOOLEAN,
					"snParticipacaoSocial" => TIPO_BOOLEAN
				),
		"execucao_orcamentaria" => array (
					"codigoAcao" => TIPO_NUMERAL,
					"codigoPrograma" => TIPO_NUMERAL,
					"exercicio" => TIPO_INTEIRO,
					"dotacaoAtual" => TIPO_NUMERAL,
					"empenhado" => TIPO_NUMERAL,
					"liquidado" => TIPO_NUMERAL,
 					"percentualLiquidadoEmpenhado" => TIPO_NUMERAL
				)
	);
	return $campos[$destino];
} 

function salvarDadosSiop($destino, $valores) {
	$tabela = obterTabelaSiop($destino);
	$campos = obterCampos($destino);
	$colunas = implode("\",\"", array_keys($campos));
	$auxValores = implode(",:", array_keys($campos));
	
	$operacao = "insert into {$tabela} (\"{$colunas}\") values (:{$auxValores})";
	$stmt = ConfigWs::factory()->getConnection()->prepare($operacao);
	$gravar = true;
	foreach($campos as $chave => $tipo) {
		if($chave == 'identificadorUnico' && $valores[$chave] == 0)
			$gravar = false;
		switch($tipo) {
			case TIPO_NUMERAL:
				$valor = $valores[$chave];
				$tipo_numeral = substr($valor, -3, 1); // Pega o separador de decimal se houver
				switch($tipo_numeral) {
					case ',':
						$valor = str_replace('.', '', $valor);
						$valor = str_replace(',', '.', $valor);
						break;
					case '.':
						$valor = str_replace(',', '', $valor);
						break;
					default :
						break;
				}
				$pdo = \PDO::PARAM_STR;
				break;
			case TIPO_TEXTUAL :
				$valor = utf8_encode($valores[$chave]);
				$pdo = \PDO::PARAM_STR;
				break;
			case TIPO_BOOLEAN :
				$valor = ($valores[$chave] == 'true') ? true : false;
				$pdo = \PDO::PARAM_BOOL;
				break;
			default :
				$valor = (INT) $valores[$chave];
				$pdo = \PDO::PARAM_INT;
		}
		$bind = ":{$chave}";
		$stmt->bindValue($bind, $valor, $pdo);
	}
	if( $gravar ) {	
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}
	return false;
}


function obterDadosSiop($destino, $condicao = null, $ordem = null, $limite = null ) {
	$tabela = obterTabelaSiop($destino);
	
	$where = '';
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
		if(is_array($condicao)) {
			$ordenadores = implode(' , ', $condicao);
		}
		$order = " order by {$ordenadores} ";
	}
	
	$limit = '';
	if(!is_null($limite)) {
		$limit = " limit $limite ";
	}
	
	$operacao = "select * from {$tabela} {$where} {$order} {$limit}";
	$stmt = ConfigWs::factory()->getConnection()->prepare($operacao);
	$stmt->execute();
	$out = array();
	while($tuple = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$out[] = $tuple;
	}
	return $out;
}

function limparDadosSiop($destino, $condicao) {
	$tabela = obterTabelaSiop($destino);
	$condicoes = $condicao;
	if(is_array($condicao)) {
		$condicoes = implode(' and ', $condicao);
	}
	$operacao = "delete from {$tabela} where {$condicoes}";
	
	$stmt = ConfigWs::factory()->getConnection()->prepare($operacao);
	$stmt->execute();
	return $stmt->fetch(PDO::FETCH_ASSOC);
}


function obterOrgaosSgbio() {
	$operacao = "
		select 
			distinct (stps.co_orgao::integer)::text as codigoSiorg
		from 
			sgdoc.tb_pessoa_siorg stps 
	";
	$stmt = ConfigWs::factory()->getConnection()->prepare($operacao);
	$stmt->execute();
	$out = array();
	while($tuple = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$out[] = $tuple;
	}
	return $out;
}

function obterOrgaosSiopPorExercicio($exercicio) {
	$operacao = "
		select 
			distinct \"codigoOrgao\" as codigoSiop
		from 
			snas.tb_siop_orgaos 
		where 
			exercicio = :exercicio
		order by
			\"codigoOrgao\"
	";
	
	$stmt = ConfigWs::factory()->getConnection()->prepare($operacao);
	$stmt->bindValue(':exercicio',$exercicio,\PDO::PARAM_INT);
	$stmt->execute();
	$out = array();
	while($tuple = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$out[] = $tuple;
	}
	return $out;
}

function obterProgramasSiopPorExercicio($exercicio) {
	$operacao = "
		SELECT DISTINCT 
			\"codigoPrograma\"
		FROM 
			snas.tb_siop_programas
		WHERE 
			exercicio = :exercicio
		 ORDER BY \"codigoPrograma\"
	";

	$stmt = ConfigWs::factory()->getConnection()->prepare($operacao);
	$stmt->bindValue(':exercicio',$exercicio,\PDO::PARAM_INT);
	$stmt->execute();
	$out = array();
	while($tuple = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$out[] = $tuple;
	}
	return $out;
}

function obterTodosOrgaosPorAnoExercicio($anoExercicio) {
	$configuracao = ConfigWs::factory()->getSiopConfig('qualitativo');
	$orgaosParametros = array(
			'credencial'	=> retornaCredenciais($configuracao),
			'exercicio'		=> $anoExercicio,
			'retornarOrgaos'=> true
	);
	$orgaosDTO = acessarWebServiceSOF( 'obterProgramacaoCompleta', $orgaosParametros, $configuracao, 'orgaosDTO' );
	return $orgaosDTO;
}

function obterOrgaosPorCodigoSiorgAnoExercicio($codigoSiorg, $anoExercicio) {
	$configuracao = ConfigWs::factory()->getSiopConfig('qualitativo');
	$orgaosParametros = array(
			'credencial'	=> retornaCredenciais($configuracao),
			'exercicio'		=> $anoExercicio,
			'codigoSiorg'	=> $codigoSiorg
	);
	$orgaosDTO = acessarWebServiceSOF( 'obterOrgaoPorCodigoSiorg', $orgaosParametros, $configuracao );
	return $orgaosDTO;
}

function obterTodosProgramasPorAnoExercicio($anoExercicio) {
	$configuracao = ConfigWs::factory()->getSiopConfig('qualitativo');
	$parametros = array(
			'credencial'		=> retornaCredenciais($configuracao),
			'exercicio'			=> $anoExercicio,
			'retornarProgramas' => true
	);
	$DTO = acessarWebServiceSOF( 'obterProgramacaoCompleta', $parametros, $configuracao, 'programasDTO' );
	return $DTO;
}

function obterProgramasPorOrgaoAnoExercicio($codigoOrgao, $anoExercicio) {
	$configuracao = ConfigWs::factory()->getSiopConfig('qualitativo');
	$parametros = array(
			'credencial'		=> retornaCredenciais($configuracao),
			'exercicio'			=> $anoExercicio,
			'codigoOrgao'		=> $codigoOrgao,
			'retornarProgramas' => true
	);
	$DTO = acessarWebServiceSOF( 'obterProgramasPorOrgao', $parametros, $configuracao );
	return $DTO;
}

function obterTodasAcoesPorAnoExercicio($anoExercicio) {
	$configuracao = ConfigWs::factory()->getSiopConfig('qualitativo');
	$parametros = array(
			'credencial'		=> retornaCredenciais($configuracao),
			'exercicio'			=> $anoExercicio,
			'retornarAcoes' 	=> true
	);
	$DTO = acessarWebServiceSOF( 'obterProgramacaoCompleta', $parametros, $configuracao, 'acoesDTO' );
	return $DTO;
}

function obterTodosObjetivosPorAnoExercicio($anoExercicio) {
	$configuracao = ConfigWs::factory()->getSiopConfig('qualitativo');
	$parametros = array(
			'credencial'		=> retornaCredenciais($configuracao),
			'exercicio'			=> $anoExercicio,
			'retornarObjetivos' 	=> true
	);
	$DTO = acessarWebServiceSOF( 'obterProgramacaoCompleta', $parametros, $configuracao, 'objetivosDTO' );
	return $DTO;
}

function obterTodasMetasPorAnoExercicio($anoExercicio) {
	$configuracao = ConfigWs::factory()->getSiopConfig('qualitativo');
	$parametros = array(
			'credencial'		=> retornaCredenciais($configuracao),
			'exercicio'			=> $anoExercicio,
			'retornarMetas' 	=> true
	);
	$DTO = acessarWebServiceSOF( 'obterProgramacaoCompleta', $parametros, $configuracao, 'metasDTO' );
	return $DTO;
}

function obterAcoesPorPrograma($programa, $anoExercicio) {
	$configuracao = ConfigWs::factory()->getSiopConfig('qualitativo');
	$acoesPorProgramaParametros = array(
			'credencial'		=> retornaCredenciais($configuracao),
			'exercicio'			=> $anoExercicio,
			'codigoPrograma'	=> $programa['codigoPrograma']
	);
	$acoesPorProgramaDTO = acessarWebServiceSOF( 'obterAcoesPorPrograma', $acoesPorProgramaParametros, $configuracao );
	return $acoesPorProgramaDTO;
}

function obterObjetivosPorPrograma($programa, $anoExercicio) {
	$configuracao = ConfigWs::factory()->getSiopConfig('qualitativo');
	$objetivosPorProgramaParametros = array(
			'credencial'		=> retornaCredenciais($configuracao),
			'exercicio'			=> $anoExercicio,
			'codigoPrograma'	=> $programa['codigoPrograma'],
			'codigoMomento'		=> $programa['codigoMomento']
	);
	$objetivosPorProgramaDTO = acessarWebServiceSOF( 'obterObjetivosPorPrograma', $objetivosPorProgramaParametros, $configuracao );
	return $objetivosPorProgramaDTO;
}

function obterMetasPorObjetivo($objetivo, $anoExercicio) {
	$configuracao = ConfigWs::factory()->getSiopConfig('qualitativo');
	$metasPorObjetivoParametros = array(
			'credencial'		=> retornaCredenciais($configuracao),
			'exercicio'			=> $anoExercicio,
			'codigoPrograma'	=> $objetivo['codigoPrograma'],
			'codigoMomento'		=> $objetivo['codigoMomento'],
			'codigoObjetivo'	=> $objetivo['codigoObjetivo']
	);
	$metasPorObjetivo = acessarWebServiceSOF( 'obterMetasPorObjetivo', $metasPorObjetivoParametros, $configuracao );
	return $metasPorObjetivo;
}

function obterExecucaoOrcamentaria($programa, $exercicio) {
	$configuracao = ConfigWs::factory()->getSiopConfig('quantitativo');	
	$programaParametros = array(
			'credencial'		=> retornaCredenciais($configuracao),
			'filtro'			=> array(
					'anoExercicio'		=> $exercicio,
					// 'acoes'			=> array(
					//		'acao'		=> $acao,
					//),
					'programas'	=> array(
							'programa'	=> $programa,
					)
			),
			'selecaoRetorno' => array(
					'acao'				=> true,
					'dotAtual'			=> true,
					'empLiquidado'		=> true,
					'empenhadoALiquidar'=> true,
					'programa'			=> true
			)
	);
	$programasDTO = acessarWebServiceSOF( 'consultarExecucaoOrcamentaria', $programaParametros, $configuracao, 'execucoesOrcamentarias' );
	return $programasDTO;
}

function retornaValorValido($entrada, $chave, $default) {
	return isset($entrada[$chave]) ? $entrada[$chave] : $default;
}
