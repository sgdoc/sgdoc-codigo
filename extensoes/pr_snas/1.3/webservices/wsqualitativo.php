<?php

require_once(dirname(__FILE__) . '/include.soap.php');

$credencial = array(
	'perfil'	=> $configuracao['Qualitativo']['user']['perfil'],
	'senha'		=> $configuracao['Qualitativo']['user']['senha'],
	'usuario'	=> $configuracao['Qualitativo']['user']['usuario']
);

$parametros = false;
$sucesso = false;
$erro = '';

$input = array();
$input['orgao'] = (string) $_REQUEST['orgao'];
$input['unidade'] = (string) $_REQUEST['unidade'];
$input['ano'] = (string) $_REQUEST['ano'];
if($input['orgao'] == '' && $input['unidade'] == '' && $input['ano'] == '') {
	$erro = "Faltam par&acirc;metros para realizar a consulta";
} else {
	$parametros = true;
}


$dados = array();

if($parametros) {
	$programaParametros = array(				
		'credencial'		=> $credencial,
		'exercicio'			=> $input['ano'],
		'retornarProgramas' => true
	);
	$programasDTO = acessarWebServiceSOF( 'obterProgramacaoCompleta', $programaParametros, $configuracao['Qualitativo'], 'programasDTO' );
	//echo "<pre>"; print_r($programasDTO); echo "</pre>";
	if($programasDTO['sucesso'] == 'true') {

		$programas_orgao = array();
		
		foreach($programasDTO['registros'] as $programa) {
	
			if($programa['codigoOrgao'] == $input['orgao']) {
	
				$programas_orgao[$programa['codigoPrograma']] = $programa['titulo'];
				
			} // if($programa['codigoOrgao'] == $input['orgao'])
				
		} // foreach($programasDTO['programasDTO'] as $programa)
			
		foreach($programas_orgao as $chave_programa => $programa_orgao) {
			
			$acoesPorProgramaParametros = array(
				'credencial'		=> $credencial,
				'exercicio'			=> $input['ano'],
				'codigoPrograma'	=> $chave_programa
			);			
			$acoesPorProgramaDTO = acessarWebServiceSOF( 'obterAcoesPorPrograma', $acoesPorProgramaParametros, $configuracao['Qualitativo'] );
			
			if( $acoesPorProgramaDTO['sucesso'] == 'true' ) {
				
				$acao = array();
				foreach( $acoesPorProgramaDTO['registros'] as $acao ) {

					if( $acao['codigoOrgao'] == $input['unidade'] ){
						//echo "<pre>Linha 83:"; print_r($acao); echo "</pre>";
							
						$planosOrcamentariosPorAcaoParametros = array(
								'credencial'				=> $credencial,
								'exercicio'					=> $input['ano'],
								'identificadorUnicoAcao'	=> $acao['identificadorUnico']
						);
						$planosOrcamentariosPorAcaoDTO = acessarWebServiceSOF('obterPlanosOrcamentariosPorAcao', $planosOrcamentariosPorAcaoParametros, $configuracao['Qualitativo'] );
						
						if( $planosOrcamentariosPorAcaoDTO['sucesso'] == 'true' ) {
							$planoOrcamentario = array();
							foreach( $planosOrcamentariosPorAcaoDTO['registros'] as $planoOrcamentario ) {	
								//echo "<pre>Linha 97:"; print_r($planoOrcamentario); echo "</pre>";
								
								if( is_array($planoOrcamentario) ) {
									$dados[] = array(
											'exercicio'	=> $input['ano'],
											'unidade'	=> $input['unidade'],
											'programa'	=> $programa_orgao,
											'acao'		=> "{$acao['codigoAcao']} - {$acao['titulo']}",
											'plano'		=> "{$planoOrcamentario['planoOrcamentario']} - {$planoOrcamentario['titulo']}",
											'chaves'	=> array(
																'programa'		 	=> $chave_programa,
																'acao'				=> $planoOrcamentario['identificadorUnicoAcao'],
																'planoOrcamentario'	=> $planoOrcamentario['identificadorUnico']
															)									
									);
								} // if( is_array($planoOrcamentario) ) 
								
							} // foreach( $planosOrcamentariosPorAcaoDTO as $planoOrcamentario )
								
							$sucesso = true;
							
						} else {
							
							$erro = $planosOrcamentariosPorAcaoDTO['mensagensErro'];
							
						} // if( $planosOrcamentariosPorAcaoDTO['sucesso'] == 'true' ) 
							
					}  // if( $acao['codigoOrgao'] == $input['unidade'] )	
							
				} // foreach( $acoesPorProgramaDTO['registros'] as $acao )
					
			} else {
						
				$erro = $acoesPorProgramaDTO['mensagensErro'];
						
			}// if( $acoesPorProgramaDTO['sucesso'] == 'true' )
				
		} // foreach($programas_orgao as $chave_programa => $programa_orgao)
			
	} else { // if($programasDTO['sucesso'] == 'true' )
		
		$erro = $programasDTO['mensagensErro'];
		
	} 
	
} // if($parametros)
	
if($sucesso) {

	$output = "<table>";
	$output.= "<tr><th>Exerc&iacute;cio</th><th>A&ccedil;&atilde;o</th><th>Plano Or&ccedil;ament&aacute;rio</th></tr>";
	foreach($dados as $dado) {
		$output .= "<tr><td>{$dado['exercicio']}</td><td>{$dado['acao']}</td><td>{$dado['plano']}</td></tr>";
	}
	$output.= "</table>";
	
} else {
	
	$output = "<table><tr><th>Erro na obten&ccedil;&atilde;o de dados</th></tr><tr><td>Por favor entre em contato com o respons&aacute;vel pelo sistema repassando as seguintes informa&ccedil;&otilde;es:<br/><br />{$erro}</td></tr></table>";
	
}

echo mb_convert_encoding($output, "UTF-8");


