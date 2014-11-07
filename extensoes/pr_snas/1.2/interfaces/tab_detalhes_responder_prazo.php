<?php
require_once __BASE_PATH__ . '/extensoes/pr_snas/1.2/classes/DaoPrazoDemanda.php';

$leitura = ($_GET['leitura'] == 'true');
$idPrazo = $_GET['id_prazo'];
$lista = $_GET['lista'];

function quebrarTexto($texto, $tamanho) {
	if (strlen($texto) > $tamanho) {
		return substr($texto, 0, $tamanho) . '...';
	}
	return $texto;
}

$arrDados = null;
$strCabTabela = '';
$strClasseTh = 'style13 ui-state-default';
$strCorpoTabela = '';
$numTam = 0;

try {
	if ($lista == 'ppa') {
		$arrDados = DaoPrazoDemanda::listarObjetivosMetasPpa($idPrazo);
		
		$strCabTabela = '<table class="display" id="tblObjetivosMetas"><thead><tr>
							<th class="'.$strClasseTh.'">Programa</th>
							<th class="'.$strClasseTh.'" style="width: 22%;">Objetivo</th>
							<th class="'.$strClasseTh.'" style="width: 22%;">Meta</th>
							<th class="'.$strClasseTh.'" style="width: 10%;">Exerc&iacute;cio</th>
							<th class="'.$strClasseTh.'" style="width: 10%;">Op&ccedil;&otilde;es</th>
						</tr></thead>';

		$numTam = count($arrDados);
		$strCorpoTabela = '';
		$tr = '<tr class="even">';

		if ($arrDados && ($numTam > 0)) {
			for ($i=0;$i<$numTam;$i++) {
				$tr = ($tr == '<tr class="odd">' ? '<tr class="even">' : '<tr class="odd">');
				$strCorpoTabela .= $tr . '<td class="tdLeft tdMetaPPA">' . quebrarTexto($arrDados[$i]['PROGRAMA'], 60) . '</td>';
				$strCorpoTabela .= '<td class="tdLeft">' . quebrarTexto($arrDados[$i]['OBJETIVO'], 30) . '</td>';
				$strCorpoTabela .= '<td class="tdLeft">' . quebrarTexto($arrDados[$i]['META'], 30) . '</td>';
				$strCorpoTabela .= '<td>' . $arrDados[$i]['EXERCICIO'] . '</td>';
				$strCorpoTabela .= '<td>';
				if (!$leitura) {
					$strCorpoTabela .= '<img onclick="excluirMetaPPA('.$arrDados[$i]['ID'].');" src="imagens/fam/delete.png" class="imgBotao16" title="Excluir Vínculo PPA" />';
				}
				$strCorpoTabela .= '<img onclick="detalharDadosSiop('.$arrDados[$i]['ID'].');" src="imagens/fam/magnifier.png" class="imgBotao16" title="Detalhar Vínculo PPA" /></td>';
				$strCorpoTabela .= '</tr>';
			}
		}

	} elseif ($lista == 'anexos') {
		$arrDados = DaoPrazoDemanda::listarArquivosAnexos($idPrazo);
		
		$strCabTabela = "
				<table class='display' id='tblAnexos'>
				<tr>
                	<th class='{$strClasseTh}' width=60%>Arquivo</th>
					<th class='{$strClasseTh}' width=30%>Usuario</th>
					<th class='{$strClasseTh}' width=30%>Data</th>
					<th class='{$strClasseTh}' width=10%>Excluir</th>
				</tr>
		";

		$numTam = count($arrDados);

		$classTr = 'even';
		$strCorpoTabela = $tr;
		$linha = 0;

		if ($arrDados && ($numTam > 0)) {
			for ($i=0;$i<$numTam;$i++) {
				$classTr = ($classTr == 'odd' ? 'even' : 'odd');
					
				if ($arrDados[$i]['ST_ATIVO'] == 1) {
					$iconeArquivo = '<img src="imagens/pdf_alta.png" class="img16"/>';
					$classExcluida = '';
					$linkExclusao = "<img onclick='excluirAnexo({$arrDados[$i]['ID']});' src='imagens/excluir_registros.png' class='imgBotao16' title='Excluir Anexo' />";
				} else {
					$iconeArquivo = '<img src="imagens/pdf_baixa.png" class="img16"/>';
					$classExcluida = 'spnExcluida';
					$linkExclusao = '';
				}
								
				$linkPdf = str_replace( __BASE_PATH__, '', __CAM_UPLOAD__ ) . $arrDados[$i]['NOME_ARQUIVO_SISTEMA'];
				$strCorpoTabela .= "
				<tr class='{$classTr}'>
					<td class='tdAnexos {$classTr} {$classExcluida}'>
						<a href='{$linkPdf}' target='_blank'>{$iconeArquivo}&nbsp;{$arrDados[$i]['NOME_ORIGINAL']}&nbsp;</a>
					</td>
					<td class='tdAnexos {$classTr} {$classExcluida}'>{$arrDados[$i]['NOME_PESSOA']}</td>
					<td class='tdAnexos {$classTr} {$classExcluida}'>{$arrDados[$i]['DT_UPLOAD']}</td>
					<td class='tdAnexos {$classTr} '>{$linkExclusao}</td>
				</tr>
				";
				
				$linha++;
			}
		}

	} else {
		throw new Exception('Opção de lista inválida!');
	}
	
	echo $strCabTabela;
	echo $strCorpoTabela;
	echo '</table>';
	
} catch (Exception $e) {
	echo 'Erro: ' . $e->getMessage();
	die;
}