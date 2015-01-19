<?php
require_once __BASE_PATH__ . '/extensoes/pr_snas/1.3/classes/DaoPrazoDemanda.php';

$leitura = ($_GET['leitura'] == 'true');
$idPrazo = $_GET['id_prazo'];
$lista = $_GET['lista'];

function quebrarTexto($texto, $tamanho) {
	$retorno = trim($texto);
	if (strlen($retorno) > $tamanho) {
		return substr($retorno, 0, ($tamanho-3)) . '...';
	}
	return $retorno;
}

$arrDados = null;
$strClasseTh = 'style13 ui-state-default';
$numTam = 0;

try {
	if ($lista == 'ppa') {
		//OBJETIVOS E METAS
		$arrDados = DaoPrazoDemanda::listarObjetivosMetasPpa($idPrazo);
		
		$strTabMetas = '<table class="display" id="tblObjetivosMetas"><thead><tr>
							<th class="'.$strClasseTh.'">Programa</th>
							<th class="'.$strClasseTh.'" style="width: 22%;">Objetivo</th>
							<th class="'.$strClasseTh.'" style="width: 22%;">Meta</th>
							<th class="'.$strClasseTh.'" style="width: 10%;">Exerc&iacute;cio</th>
							<th class="'.$strClasseTh.'" style="width: 10%;">Op&ccedil;&otilde;es</th>
						</tr></thead>';

		$numTam = count($arrDados);
		$tr = '<tr class="even">';

		if ($arrDados && ($numTam > 0)) {
			for ($i=0;$i<$numTam;$i++) {
				$tr = ($tr == '<tr class="odd">' ? '<tr class="even">' : '<tr class="odd">');
				$strTabMetas .= $tr . '<td class="tdLeft tdMetaPPA">' . quebrarTexto($arrDados[$i]['PROGRAMA'], 60) . '</td>';
				$strTabMetas .= '<td class="tdLeft">' . quebrarTexto($arrDados[$i]['OBJETIVO'], 30) . '</td>';
				$strTabMetas .= '<td class="tdLeft">' . quebrarTexto($arrDados[$i]['META'], 30) . '</td>';
				$strTabMetas .= '<td>' . $arrDados[$i]['EXERCICIO'] . '</td>';
				$strTabMetas .= '<td>';
				if (!$leitura) {
					$strTabMetas .= '<img onclick="excluirVinculoPPA(\'meta\', '.$arrDados[$i]['ID'].');" src="imagens/fam/delete.png" class="imgBotao16" title="Excluir Vínculo PPA" />';
				}
				$strTabMetas .= '<img onclick="detalharMeta('.$arrDados[$i]['ID'].');" src="imagens/fam/magnifier.png" class="imgBotao16" title="Detalhar Objetivo/Meta" /></td>';
				$strTabMetas .= '</tr>';
			}
		}
		$strTabMetas .= '</table>';
		
		//AÇÕES
		$arrDados = DaoPrazoDemanda::listarAcoesPpa($idPrazo);
		
		$strTabAcoes = '<table class="display" id="tblAcoes"><thead><tr>
							<th class="'.$strClasseTh.'">Programa</th>
							<th class="'.$strClasseTh.'" style="width: 40%;">A&ccedil;&atilde;o</th>
							<th class="'.$strClasseTh.'" style="width: 10%;">Exerc&iacute;cio</th>
							<th class="'.$strClasseTh.'" style="width: 10%;">Op&ccedil;&otilde;es</th>
						</tr></thead>';
		
		$numTam = count($arrDados);
		$tr = '<tr class="even">';
		
		if ($arrDados && ($numTam > 0)) {
			for ($i=0;$i<$numTam;$i++) {
				$tr = ($tr == '<tr class="odd">' ? '<tr class="even">' : '<tr class="odd">');
				$strTabAcoes .= $tr . '<td class="tdLeft tdMetaPPA">' . quebrarTexto($arrDados[$i]['PROGRAMA'], 60) . '</td>';
				$strTabAcoes .= '<td class="tdLeft">' . quebrarTexto($arrDados[$i]['ACAO'], 60) . '</td>';
				$strTabAcoes .= '<td>' . $arrDados[$i]['EXERCICIO'] . '</td>';
				$strTabAcoes .= '<td>';
				if (!$leitura) {
					$strTabAcoes .= '<img onclick="excluirVinculoPPA(\'acao\', '.$arrDados[$i]['ID'].');" src="imagens/fam/delete.png" class="imgBotao16" title="Excluir Vínculo PPA" />';
				}
				$strTabAcoes .= '<img onclick="detalharAcao('.$arrDados[$i]['ID'].');" src="imagens/fam/magnifier.png" class="imgBotao16" title="Detalhar Vínculo PPA" /></td>';
				$strTabAcoes .= '</tr>';
			}
		}
		$strTabAcoes .= '</table>';
		
		echo '<div id="tabsPPA"><ul>';
		echo '<li><a href="#divTabMetas">Objetivos / Metas</a></li>';
		echo '<li><a href="#divTabAcoes">A&ccedil;&otilde;es</a></li>';
		echo '</ul>';
		echo '<div id="divTabMetas">' . $strTabMetas . '</div>';
		echo '<div id="divTabAcoes">' . $strTabAcoes . '</div>';
		echo '</div>';
		
		echo '<script type="text/javascript">';
		echo '$("#tabsPPA").tabs({ active: 1 });';
		echo '</script>';
		
	} elseif ($lista == 'anexos') {
		$strCabTabela = '';
		$strCorpoTabela = '';
		
		$arrDados = DaoPrazoDemanda::listarArquivosAnexos($idPrazo);
		
		$strCabTabela = "<table class='display' id='tblAnexos'>
						<tr>
		                	<th class='{$strClasseTh}' width=60%>Arquivo</th>
							<th class='{$strClasseTh}' width=30%>Usuario</th>
							<th class='{$strClasseTh}' width=30%>Data</th>";
		if (!$leitura) {
			$strCabTabela .= "<th class='{$strClasseTh}' width=10%>Excluir</th>";
		}
		$strCabTabela .= "</tr>";

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
					<td class='tdAnexos {$classTr} {$classExcluida}'>{$arrDados[$i]['DT_UPLOAD']}</td>";
				if (!$leitura) {
					$strCorpoTabela .= "<td class='tdAnexos {$classTr} '>{$linkExclusao}</td>";
				}
				$strCorpoTabela .= "</tr>";
				
				$linha++;
			}
		}
		
		echo $strCabTabela;
		echo $strCorpoTabela;
		echo '</table>';
		
	} else {
		throw new Exception('Opção de lista inválida!');
	}
	
} catch (Exception $e) {
	echo 'Erro: ' . $e->getMessage();
	die;
}