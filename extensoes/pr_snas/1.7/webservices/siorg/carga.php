<?php

function limparNumero($strNum) {
	$strNum = trim($strNum);
	$strRet = '';
	for($i=0;$i<strlen($strNum);$i++) {
		if (is_numeric($strNum{$i})) {
			$strRet .= $strNum{$i};
		}
	}
	return $strRet;
}

function display_xml_error($error, $xml) {
	$return  = $xml. "\n";
	$return .= str_repeat('-', $error->column) . "^\n";

	switch ($error->level) {
		case LIBXML_ERR_WARNING:
			$return .= "Warning $error->code: ";
			break;
		case LIBXML_ERR_ERROR:
			$return .= "Error $error->code: ";
			break;
		case LIBXML_ERR_FATAL:
			$return .= "Fatal Error $error->code: ";
			break;
	}

	$return .= trim($error->message) .
				"\n  Line: $error->line" .
				"\n  Column: $error->column";

	if ($error->file) {
		$return .= "\n  File: $error->file";
	}

	return $return;
}

define('CF_APP_BASE_PATH', realpath(__DIR__) . '/../../../../..');
define('CF_APP_ENVIRONMENT', 'dsv');

require_once('../ConfigWs.php');
require_once('../lib/Log.php');
require_once('arraysDominios.php');

$sqlInsert = 'INSERT INTO sgdoc.tb_pessoa_siorg_carga (co_orgao, co_orgao_pai, link_tipo_orgao, co_tipo_orgao, no_tipo_orgao, link_tipo_unidade, no_orgao, sg_orgao,
	link_endereco, link_contato, in_organizacao, tx_versao_consulta) 
VALUES (:co_orgao, :co_orgao_pai, :link_tipo_orgao, :co_tipo_orgao, :no_tipo_orgao, :link_tipo_unidade, :no_orgao, :sg_orgao,
	:link_endereco, :link_contato, :in_organizacao, :tx_versao_consulta);';

/*
 * Em virtude do tamanho do arquivo, ele será lido como um arquivo txt,
 * linha a linha, e cada linha será convertida, como um string xml, para um objeto
 */
$arqDadosXml = 'dados.txt';

$log = new Log('.', 'cargaSiorg');
$log->setPrintScreen(true);

$cnnBanco = ConfigWs::factory()->getConnection();

try {
	$log->addLog("Abrindo arquivo de dados [$arqDadosXml]");
	$hdlDados = fopen($arqDadosXml, 'r');
	
	libxml_use_internal_errors(true);
	
	if ($hdlDados) {
		$l = 0;
		$tmp = '';
		$strXml = '';
		$cnnBanco->beginTransaction();
		
		$log->addLog('Limpando tabela destino');
		$cnnBanco->prepare('truncate sgdoc.tb_pessoa_siorg_carga;')->execute();

		$stmt = $cnnBanco->prepare($sqlInsert);
		
		$log->addLog('Início da leitura dos dados');
		while (($linha = fgets($hdlDados)) !== false) {
			$l++;
			/*
			if ($l > 100) { break; }
			*/
			if (($l % 10000) == 0) {
				$log->addLog("Lendo linha $l");
			}
			
			if (stripos($linha, '</unidades>') == false) {
				$strXml .= trim(str_replace("\n", '', $linha));
				continue;
			} else {
				$strXml .= ' ' . trim(str_replace("\n", '', $linha));
			}
			
			$objXml = simplexml_load_string($strXml);
			if ($objXml !== false) {
				
				$tmp = sprintf("%06d", (int) limparNumero($objXml->codigoUnidade));
				$stmt->bindValue(':co_orgao', $tmp, \PDO::PARAM_STR);
				
				$tmp = sprintf("%06d", (int) limparNumero($objXml->codigoUnidadePai));
				$stmt->bindValue(':co_orgao_pai', $tmp, \PDO::PARAM_STR);
				
				$stmt->bindValue(':no_orgao', trim($objXml->nome), \PDO::PARAM_STR);
				$stmt->bindValue(':sg_orgao', trim($objXml->sigla), \PDO::PARAM_STR);
				
				if ((!property_exists($objXml, 'codigoCategoriaUnidade')) || (trim($objXml->codigoCategoriaUnidade) == '')) {
					$stmt->bindValue(':link_tipo_orgao', null, \PDO::PARAM_NULL);
					$stmt->bindValue(':co_tipo_orgao', null, \PDO::PARAM_NULL);
					$stmt->bindValue(':no_tipo_orgao', null, \PDO::PARAM_NULL);
				} else {
					$stmt->bindValue(':link_tipo_orgao', trim($objXml->codigoCategoriaUnidade), \PDO::PARAM_STR);
					$stmt->bindValue(':co_tipo_orgao', $categoria_unidade[trim($objXml->codigoCategoriaUnidade)][0], \PDO::PARAM_STR);
					$stmt->bindValue(':no_tipo_orgao', $categoria_unidade[trim($objXml->codigoCategoriaUnidade)][1], \PDO::PARAM_STR);
				}
				
				if ((!property_exists($objXml, 'codigoTipoUnidade')) || (trim($objXml->codigoTipoUnidade) == '')) {
					$stmt->bindValue(':link_tipo_unidade', null, \PDO::PARAM_NULL);
					$stmt->bindValue(':in_organizacao', null, \PDO::PARAM_NULL);
				} else {
					$stmt->bindValue(':link_tipo_unidade', trim($objXml->codigoTipoUnidade), \PDO::PARAM_STR);
					if ((trim($objXml->codigoTipoUnidade) == 'http://estruturaorganizacional.dados.gov.br/id/tipo-unidade/orgao')
						|| (trim($objXml->codigoTipoUnidade) == 'http://estruturaorganizacional.dados.gov.br/id/tipo-unidade/entidade')) {
						$tmp = 'O';
					} else {
						$tmp = 'N';
					}
					$stmt->bindValue(':in_organizacao', $tmp, \PDO::PARAM_STR);
				}
				
				if (property_exists($objXml, 'endereco')) {
					$stmt->bindValue(':link_endereco', trim($objXml->endereco), \PDO::PARAM_STR);
				} else {
					$stmt->bindValue(':link_endereco', null, \PDO::PARAM_NULL);
				}
				
				if (property_exists($objXml, 'contato')) {
					$stmt->bindValue(':link_contato', trim($objXml->contato), \PDO::PARAM_STR);
				} else {
					$stmt->bindValue(':link_contato', null, \PDO::PARAM_NULL);
				}
				
				$stmt->bindValue(':tx_versao_consulta', trim($objXml->versaoConsulta), \PDO::PARAM_STR);
				
				$stmt->execute();

			} else {
				$tmp = "ERRO: Não foi possível converter a linha $l em um objeto:\n";
				$errors = libxml_get_errors();
				foreach ($errors as $error) {
					$log->addLog($tmp . display_xml_error($error, $linha));
				}
				libxml_clear_errors();				
			}
			$strXml = '';
		} //FIM WHILE
		$log->addLog('Fim da leitura dos dados');
		
		$cnnBanco->commit();

	}

	fclose($hdlDados);
	$log->generateLog();
	
} catch (Exception $e) {
	try { $cnnBanco->rollBack(); } catch (Exception $e1) {}
	$log->errorLog($e);
}
?>
