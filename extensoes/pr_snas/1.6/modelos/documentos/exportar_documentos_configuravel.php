<?php
include_once(__BASE_PATH__ . '/extensoes/pr_snas/1.6/classes/DaoDocumentoDemanda.php');
include_once(__BASE_PATH__ . '/extensoes/pr_snas/1.6/classes/DaoPrazoDemanda.php');
include_once(__BASE_PATH__ . '/extensoes/pr_snas/1.6/classes/VinculacaoDemanda.php');

function getOpcao($array, $key, $value) {
	$results = array();
	if (is_array($array)) {
		if (isset($array[$key]) && $array[$key] == $value) {
			$results[] = $array;
		}
		foreach ($array as $subarray) {
			$results = array_merge($results, getOpcao($subarray, $key, $value));
		}
	}
	return $results;
}

if ($_POST) {
	
	$out = null;
	
	/* Os id's abaixo representam um sigla da tabela do banco (doc / prz) e o nome das colunas nas tabelas.
	 * Exceto os id's "prz-ppa" e "prz-exec_orc", que representam a inclusão de todos os dados do vínculo
	 * com o PPA/LOA e das execuções orçamentárias, respectivamente.
	 */
	$strOpcoes = '[{"id":"todas",
		"label":"Todas",
		"campo":"",
		"filhos": [
			{"id":"documento",
			"label":"Documento",
			"campo":"",
			"filhos": [
				{"id":"doc-digital", "label":"Digital", "campo":"digital"},
				{"id":"doc-tipo", "label":"Tipo", "campo":"tipo"},
				{"id":"doc-numero", "label":"Número", "campo":"numero"},
				{"id":"doc-dt_documento", "label":"Data do Documento", "campo":"dt_documento"},
				{"id":"doc-id_assunto", "label":"Assunto", "campo":"id_assunto"},
				{"id":"doc-assunto_complementar", "label":"Assunto Complementar", "campo":"assunto_complementar"},
				{"id":"doc-origem", "label":"Origem", "campo":"origem"},
				{"id":"doc-interessado", "label":"Interessado", "campo":"interessado"},
				{"id":"doc-assinatura", "label":"Assinatura", "campo":"assinatura"},
				{"id":"doc-cargo", "label":"Cargo", "campo":"cargo"},
				{"id":"doc-procedencia", "label":"Procedência", "campo":"procedencia"},
				{"id":"doc-dt_entrada", "label":"Data Entrada", "campo":"dt_entrada"},
				{"id":"doc-recibo", "label":"Recebido Por", "campo":"recibo"},
				{"id":"doc-tecnico_responsavel", "label":"Encaminhado Para", "campo":"tecnico_responsavel"},
				{"id":"doc-dt_prazo", "label":"Data do Prazo", "campo":"dt_prazo"}
			]},
			{"id":"prazo",
			"label":"Demandas",
			"campo":"",
			"filhos": [
				{"id":"prz-nu_proc_dig_ref", "label":"Digital", "campo":"nu_proc_dig_ref"},
				{"id":"prz-dt_prazo", "label":"Data do Prazo", "campo":"dt_prazo"},
				{"id":"prz-id_unid_destino", "label":"Destino", "campo":"id_unid_destino"},
				{"id":"prz-tx_solicitacao", "label":"Solicitação", "campo":"tx_solicitacao"},
				{"id":"prz-tx_resposta", "label":"Resposta", "campo":"tx_resposta"},
				{"id":"prz-legislacao_situacao", "label":"Legislação Situação", "campo":"legislacao_situacao"},
				{"id":"prz-legislacao_descricao", "label":"Legislação Descrição", "campo":"legislacao_descricao"},
				{"id":"prz-ppa", "label":"Dados do PPA", "campo":""},
				{"id":"prz-exec_orc", "label":"Execução Orçamentária", "campo":""}
			]}
		]}]';
	
	//var_dump(json_decode($strOpcoes, true)[0]); die;
	
	if ($_POST['acao'] == 'obter_opcoes') {
		
		$out = array('success' => 'true', 'message' => $strOpcoes);
		
	} elseif ($_POST['acao'] == 'exportar') {
	
		try {
			
			$arrDocumentos = array();
			if (!is_null($_POST['documento'])) {
				//Para exportar um documento
				$arrDocumentos[] = $_POST['documento'];
			} elseif (!is_null($_POST['documentos'])) {
				//Para exportar uma lista de documentos, separados por "|"
				$arrDocumentos = explode('|', trim($_POST['documentos']));
			} else {
				throw new Exception('Informe pelo menos um documento para exportação.');
			}
			
			//Só terá digital se for exportar um documento
			$digDocumento = is_null($_POST['digital']) ? '' : " '{$_POST['digital']}'"; 
			
			if (is_null($_POST['opcoes']) || ($_POST['opcoes'] == '')) {
				throw new Exception('Escolha as informações para exportação.');
			}
			$arrOpcSel = explode('|', trim($_POST['opcoes'], '|'));
			
			$arrOpcoes = json_decode($strOpcoes, true);
			
			$pastaTmp = __CAM_UPLOAD__ . '/TMP/expdoccfg/';
			
			if (!is_dir($pastaTmp)) {
				mkdir($pastaTmp);
			} else {
				//limpa os arquivos de 2 dias atrás, se houver
				$dataAlvo = strtotime('-2 day', mktime());
				foreach(glob($pastaTmp.'*.txt') as $arq) {
					$dataArq = filemtime($arq);
					if( $dataAlvo > $dataArq )	{
						unlink($arq);
					}
				}				
			}
			
			$pastaTmp .= session_id().'_';
			$arrArquivos = array();
			//$separador = ';';
			
			foreach ($arrDocumentos as $idDocumento) {
				//independente das opções de campos, deve buscar o documento,
				//para comprovar sua existência, e então buscar demais dados
				$doc = DaoDocumentoDemanda::getDocumento((integer) $idDocumento);
				
				if ($doc === false) {
					throw new Exception("Documento$digDocumento não localizado para exportação.");
				}
				
				/* Será um arquivo para cada documento
				 * nome do arquivo = TipoDocumento#######.txt, onde ####### = digital
				*/
				$nomeArq = StringUtil::getCamelCase($doc['tipo']) . $doc['digital'] . '.txt';
				$fp = fopen(($pastaTmp . $nomeArq), 'w');
				
				//Impressão da capa
				fwrite($fp, "Caderno de Respostas\n\n" . $doc['interessado'] . "\n\n" . date("Y") . "\n\n");
				
				//Converter datas
				$doc['dt_entrada'] = Util::formatDate($doc['dt_entrada']);
				$doc['dt_documento'] = Util::formatDate($doc['dt_documento']);
				$doc['dt_prazo'] = Util::formatDate($doc['dt_prazo']);
				$doc['id_assunto'] = DaoAssuntoDocumento::getAssunto($doc['id_assunto'], 'assunto');
				$doc['procedencia'] = $doc['procedencia'] == 'I' ? 'Interna' : 'Externa';
				
				$buscarPrazos = false;
				$buscarPPA = false;
				$buscarExecOrc = false;
				
				foreach ($arrOpcSel as $opt) {
					if (substr($opt, 0, 3) == 'doc') {
						$arrTmp = getOpcao($arrOpcoes, 'id', $opt);
						fwrite($fp, $arrTmp[0]['label'] . ': ' . $doc[$arrTmp[0]['campo']] . "\n");
					} elseif (substr($opt, 0, 3) == 'prz') {
						if ($opt == 'prz-ppa') {
							$buscarPPA = true;
						} elseif ($opt == 'prz-exec_orc') {
							$buscarExecOrc = true;
						} else {
							$buscarPrazos = true;
						}
					}
				}
				
				//PRAZOS (DEMANDAS)
				if ($buscarPrazos || $buscarPPA || $buscarExecOrc) {
					$arrDem = VinculacaoDemanda::listarVinculados($idDocumento, 'p', 3);
					if ($arrDem !== false) {
						for ($i=0; $i<count($arrDem); $i++) {
							$prazo = DaoPrazoDemanda::getPrimeiroPrazo($arrDem[$i]['DIGITAL']);
							$prazo['legislacao_situacao'] = DaoPrazoDemanda::getSituacaoLegislacao($prazo['legislacao_situacao']);
							fwrite($fp, "\n----- DEMANDA " . ($i+1) . " -----\n");
							foreach ($arrOpcSel as $opt) {
								if (($opt != 'prz-ppa') && ($opt != 'prz-exec_orc')) {
									if (substr($opt, 0, 3) == 'prz') {
										/*
										if (!isset($arrSaidaPrz[$prazo['sq_prazo']])) {
											$arrSaidaPrz[$prazo['sq_prazo']] = array();
										}*/
										$arrTmp = getOpcao($arrOpcoes, 'id', $opt);
										//$arrCabecalho[] = $arrTmp[0]['label'];
										//$arrSaidaPrz[$prazo['sq_prazo']][] = $prazo[$arrTmp[0]['campo']];
										fwrite($fp, $arrTmp[0]['label'] . ': ' . $prazo[$arrTmp[0]['campo']] . "\n");
									}
								}
							}
							
							if ($buscarPPA || $buscarExecOrc) {
								$arrPpa = DaoPrazoDemanda::listarObjetivosMetasPpa($prazo['sq_prazo']);
								if ($arrPpa !== false) {
									fwrite($fp, "\n----- OBJETIVOS E METAS PPA/LOA -----\n");
									for ($j=0; $j<count($arrPpa); $j++) {
										fwrite($fp, 'Programa: ' . $arrPpa[$j]['PROGRAMA'] . "\n");
										fwrite($fp, 'Exercício: ' . $arrPpa[$j]['EXERCICIO'] . "\n");
										fwrite($fp, 'Objetivo: ' . $arrPpa[$j]['OBJETIVO'] . "\n");
										fwrite($fp, 'Meta: ' . $arrPpa[$j]['META'] . "\n\n");
									}
								} else {
									fwrite($fp, "SEM OBJETIVOS E METAS PPA/LOA INFORMADOS\n\n");
								}
								
								$arrPpa = DaoPrazoDemanda::listarAcoesPpa($prazo['sq_prazo']);
								if ($arrPpa !== false) {
									fwrite($fp, "\n----- AÇÕES" .($buscarExecOrc ? ' E EXECUÇÕES ORÇAMENTÁRIAS' : ''). " PPA/LOA -----\n");
									for ($j=0; $j<count($arrPpa); $j++) {
										fwrite($fp, 'Programa: ' . $arrPpa[$j]['PROGRAMA'] . "\n");
										fwrite($fp, 'Exercício: ' . $arrPpa[$j]['EXERCICIO'] . "\n");
										fwrite($fp, 'Ação: ' . $arrPpa[$j]['ACAO'] . "\n");
										if ($buscarExecOrc) {
											fwrite($fp, "Valoes Execução Orçamentária:\n");
											fwrite($fp, '  Dotação Atual: R$ ' . $arrPpa[$j]['VAL_DOTACAO_ATUAL'] . "\n");
											fwrite($fp, '  Empenhado: R$ ' . $arrPpa[$j]['VAL_EMPENHADO'] . "\n");
											fwrite($fp, '  Liquidado: R$ ' . $arrPpa[$j]['VAL_LIQUIDADO'] . "\n");
											fwrite($fp, '  Liq. / Emp.: ' . $arrPpa[$j]['PER_LIQ_EMP'] . "%\n");
										}
										fwrite($fp, "\n");
									}
								} else {
									fwrite($fp, "SEM AÇÕES" .($buscarExecOrc ? ' E EXECUÇÕES ORÇAMENTÁRIAS' : ''). " PPA/LOA INFORMADAS\n\n");
								}
							}
						}
					}
				}
				
				fclose($fp);
				$arrArquivos[] = $nomeArq;
			} //fim loop documentos
			
			/*
			 * TODO: para exportação de multiplos documentos, gerar UM arquivo compactado 
			 */
			$out = array('success' => 'true', 
						 'message' => 'Foi(ram) gerado(s) ' . count($arrArquivos) . ' arquivo(s) com sucesso!',
						 'file' => $nomeArq
			);
			
		} catch (Exception $e) {
			$out = array('success' => 'false', 'erro' => $e->getMessage());
		}
	}
	
	print(json_encode($out));

}