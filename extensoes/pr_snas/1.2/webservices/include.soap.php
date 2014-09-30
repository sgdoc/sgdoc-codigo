<?php
require_once(dirname(__FILE__) . '/../bibliotecas/nusoap/lib/nusoap.php');

function acessarWebServiceSOF($metodo, $parametros = array(), $configuracao = array(), $dto = 'registros' ) {
	$proxy = ConfigWs::factory()->getSiopProxyConfig();	
	$client = new nusoap_client($configuracao['wsdl_url'], $proxy['server'], $proxy['port'], $proxy['username'], $proxy['password'], 0, 3000 );
	$client->setUseCURL(true);
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
					"identificadorUnico" => \PDO::PARAM_INT,
					"codigoMomento" => \PDO::PARAM_INT,
					"codigoMeta" => \PDO::PARAM_INT,
					"exercicio" => \PDO::PARAM_INT,
					"codigoObjetivo" => \PDO::PARAM_STR,
					"codigoPrograma" => \PDO::PARAM_STR,
				  	"descricao" => \PDO::PARAM_STR
				),
		"orgaos" => array(
					"codigoOrgao" => \PDO::PARAM_INT,
					"exercicio" => \PDO::PARAM_INT,
					"tipoOrgao" => \PDO::PARAM_STR,
					"codigoOrgaoPai" => \PDO::PARAM_STR,
					"descricao" => \PDO::PARAM_STR,
					"descricaoAbreviada" => \PDO::PARAM_STR,
					"orgaoId"  => \PDO::PARAM_INT,
					"orgaoSiorg" => \PDO::PARAM_STR,
					"snAtivo" => \PDO::PARAM_BOOL
				),
		"objetivos" => array(
					"identificadorUnico" => \PDO::PARAM_INT,
					"exercicio" => \PDO::PARAM_INT,
					"codigoMomento" => \PDO::PARAM_INT,
					"codigoObjetivo" => \PDO::PARAM_STR,
					"codigoOrgao" => \PDO::PARAM_STR,
					"codigoPrograma" => \PDO::PARAM_STR,
					"enunciado" => \PDO::PARAM_STR,
					"snExclusaoLogica" => \PDO::PARAM_BOOL
				),
		"programas" => array(
					"identificadorUnico" => \PDO::PARAM_INT,
					"codigoMomento" => \PDO::PARAM_INT,
					"codigoOrgao" => \PDO::PARAM_STR,
					"codigoPrograma" => \PDO::PARAM_STR,
					"codigoTipoPrograma" => \PDO::PARAM_STR,
					"estrategiaImplementacao" => \PDO::PARAM_STR,
					"exercicio" => \PDO::PARAM_INT,
					"horizonteTemporalContinuo" => \PDO::PARAM_INT,
					"justificativa" => \PDO::PARAM_STR,
					"objetivo" => \PDO::PARAM_STR,
					"problema" => \PDO::PARAM_STR,
					"publicoAlvo" => \PDO::PARAM_STR,
					"snExclusaoLogica" => \PDO::PARAM_BOOL,
					"titulo" => \PDO::PARAM_STR
				),
		"acoes" => array(
					"identificadorUnico" => \PDO::PARAM_INT,
					"exercicio" => \PDO::PARAM_INT,
					"codigoMomento" => \PDO::PARAM_INT,
					"codigoTipoInclusaoAcao" => \PDO::PARAM_INT,
					"titulo" => \PDO::PARAM_STR,
					"baseLegal" => \PDO::PARAM_STR,
					"descricao" => \PDO::PARAM_STR,
					"codigoAcao" => \PDO::PARAM_STR,
					"codigoPrograma" => \PDO::PARAM_STR,
					"codigoFuncao" => \PDO::PARAM_STR,
					"codigoSubFuncao" => \PDO::PARAM_STR,
					"codigoOrgao" => \PDO::PARAM_STR,
					"codigoEsfera" => \PDO::PARAM_STR,
					"codigoTipoAcao" => \PDO::PARAM_STR,
					"snDireta" => \PDO::PARAM_BOOL,
					"snDescentralizada" => \PDO::PARAM_BOOL,
					"snLinhaCredito" => \PDO::PARAM_BOOL,
					"snTransferenciaObrigatoria" => \PDO::PARAM_BOOL,
					"snTransferenciaVoluntaria" => \PDO::PARAM_BOOL,
					"snExclusaoLogica" => \PDO::PARAM_BOOL,
					"snRegionalizarNaExecucao" => \PDO::PARAM_BOOL,
					"snAquisicaoInsumoEstrategico" => \PDO::PARAM_BOOL,
					"snParticipacaoSocial" => \PDO::PARAM_BOOL
				),
		"execucao_orcamentaria" => array (
					"codigoAcao" => \PDO::PARAM_STR,
					"codigoPrograma" => \PDO::PARAM_STR,
					"exercicio" => \PDO::PARAM_INT,
					"dotacaoAtual" => \PDO::PARAM_STR,
					"empenhado" => \PDO::PARAM_STR,
					"liquidado" => \PDO::PARAM_STR,
					"percentualLiquidadoEmpenhado" => \PDO::PARAM_STR
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
			case \PDO::PARAM_STR :
				$valor = utf8_encode($valores[$chave]);
				break;
			case \PDO::PARAM_BOOL :
				$valor = ($valores[$chave] == 'true') ? true : false;
				break;
			default :
				$valor = (INT) $valores[$chave];
		}
		$bind = ":{$chave}";
		$stmt->bindValue($bind,$valor, $tipo);
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