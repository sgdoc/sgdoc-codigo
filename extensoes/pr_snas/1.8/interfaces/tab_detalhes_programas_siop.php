<?php
require_once __BASE_PATH__ . '/extensoes/pr_snas/1.8/classes/DaoDadosSiop.php';

$programa = $_GET['prog'];
$ano = $_GET['ano'];
$orgSiop = $_GET['org'];
$unidade = $_GET['unid'];
$prazo = $_GET['prazo'];
$aba = $_GET['aba'];

$arrDados = array();
$strCabTabela = '';
$strClasseTh = 'style13 ui-state-default';
$strCorpoTabela = '';
$numTam = 0;

try {
	if ($aba == 'obj') { //OBJETIVOS E METAS
		$idHtml = 'tblObjetivos';
		$arrDados = DaoDadosSiop::getObjetivosMetas($programa, $orgSiop, $ano, $prazo);
		
		$strCabTabela = '<tr>
							<th class="'.$strClasseTh.'" style="width: 30%">Objetivos</th>
							<th class="'.$strClasseTh.'">Metas</th>
						<tr>';
		
		$numTam = count($arrDados);
		$idObj = '';
		$strCorpoTabela = '<tr id="trTexto"><td colspan="5">Não existem objetivos e metas para este programa e exercício.</td></tr>';
		$tr = '<tr class="even">';
		//var_dump($arrDados); die;
		if ($arrDados && ($numTam > 0)) {
			$strCorpoTabela = '';
			for ($i=0;$i<$numTam;$i++) {
				if ($idObj != $arrDados[$i]['COD_OBJETIVO']) {
					if ($idObj != '') {
						$strCorpoTabela .= '</td></tr>';
					}
					$tr = ($tr == '<tr class="odd">' ? '<tr class="even">' : '<tr class="odd">');
					$strCorpoTabela .= $tr . '<td class="tdParagrafo">';
					$strCorpoTabela .= $arrDados[$i]['COD_OBJETIVO'] . ' - ' . $arrDados[$i]['DSC_OBJETIVO'];
					$strCorpoTabela .= '</td><td class="tdTexto">';
					$idObj = $arrDados[$i]['COD_OBJETIVO'];
				}
				if ($unidade == Controlador::getInstance()->usuario->ID_UNIDADE) {
					$strCorpoTabela .= '<input type="checkbox" idMeta="'.$arrDados[$i]['ID_META'].'" ';
					if (is_null($arrDados[$i]['VINCULO'])) {
						$strCorpoTabela .= 'class="chkMeta" />';
					} else {
						$strCorpoTabela .= 'checked="checked" disabled="disabled"
											title="Para desmarcar, exclua a meta na tela de respostas do prazo." />';
					}
					$strCorpoTabela .= '&nbsp;&nbsp;&nbsp;';
				}
				$strCorpoTabela .= $arrDados[$i]['COD_META'] . ' - ' . $arrDados[$i]['DSC_META'] . '<br/>';
			}
		}

	} elseif ($aba == 'acoes') { //AÇÕES / LOCALIZADORES / PO
		$idHtml = 'tblAcoes';
		$arrDados = DaoDadosSiop::getAcoes($programa, $orgSiop, $ano, $prazo);
		
		$strCabTabela = '<tr>
							<th class="'.$strClasseTh.'" rowspan="2" width="60%">A&ccedil;&atilde;o / Localizador / PO</th>
							<th class="'.$strClasseTh.'" colspan="4">Valores</th>
						</tr>
						<tr>
							<th class="'.$strClasseTh.'">Dota&ccedil;&atilde;o Atual (R$)</th>
							<th class="'.$strClasseTh.'">Empenhado (R$)</th>
							<th class="'.$strClasseTh.'">Liquidado (R$)</th>
							<th class="'.$strClasseTh.'">&nbsp;% Liq. / Emp.</th>
						</tr>';
		
		$numTam = count($arrDados);
		$strCorpoTabela = '<tr id="trTexto"><td colspan="5">Não existem programas orçamentários para este programa e exercício.</td></tr>';
		$trClass = 'even';
		
		if ($arrDados && ($numTam > 0)) {
			$strCorpoTabela = '';
			for ($i=0;$i<$numTam;$i++) {
				$trClass = ($trClass == 'odd' ? 'even' : 'odd');
				//AÇÕES
				$strCorpoTabela .= '<tr class="' . $trClass . '"><td class="tdTexto">' .
					'Ação: ' . $arrDados[$i]['COD_ACAO'] . ' - ' . $arrDados[$i]['TIT_ACAO'] . '</td>' .
					'<td class="tdNumero">' . number_format($arrDados[$i]['VAL_DOTACAO_ATUAL'],2,',','.') . '</td>' .
					'<td class="tdNumero">' . number_format($arrDados[$i]['VAL_EMPENHADO'],2,',','.') . '</td>' .
					'<td class="tdNumero">' . number_format($arrDados[$i]['VAL_LIQUIDADO'],2,',','.') . '</td>' .
					'<td class="tdNumero">' . number_format($arrDados[$i]['PER_LIQ_EMP'],3,',','.') . '%</td>' .
					'</tr>';
				
				//LOCALIZADORES
				$arrLocal = DaoDadosSiop::getLocalizadoresByAcao($arrDados[$i]['ID_ACAO'], $ano);
				if ($arrLocal !== false) {
					for ($j=0;$j<count($arrLocal);$j++) {
						$strCorpoTabela .= '<tr class="' . $trClass . '"><td class="tdTexto" style="padding-left: 30px;">'.
							'Localizador: ' . $arrLocal[$j]['COD_LOCALIZADOR'] . ' - ' . $arrLocal[$j]['DES_LOCALIZADOR'] . '</td>' .
							'<td class="tdNumero">' . number_format($arrLocal[$j]['VAL_DOTACAO_ATUAL'],2,',','.') . '</td>' .
							'<td class="tdNumero">' . number_format($arrLocal[$j]['VAL_EMPENHADO'],2,',','.') . '</td>' .
							'<td class="tdNumero">' . number_format($arrLocal[$j]['VAL_LIQUIDADO'],2,',','.') . '</td>' .
							'<td class="tdNumero">' . number_format($arrLocal[$j]['PER_LIQ_EMP'],3,',','.') . '%</td>' .
							'</tr>';
						
						//PLANOS ORÇAMENTÁRIOS
						$arrPlOrc = DaoDadosSiop::getPlanosOrcamByAcaoLocalizador($arrDados[$i]['ID_ACAO'], $arrLocal[$j]['COD_LOCALIZADOR'], $ano, $prazo);
						if ($arrPlOrc !== false) {
							for ($k=0;$k<count($arrPlOrc);$k++) {
								$idPO = $arrDados[$i]['ID_ACAO'].'|'.$arrLocal[$j]['ID_LOCALIZADOR'].'|'.$arrPlOrc[$k]['ID_PLANO_ORCAM'];
								
								$strCorpoTabela .= '<tr class="' . $trClass . '"><td class="tdTexto" style="padding-left: 50px;">';
								
								if ($unidade == Controlador::getInstance()->usuario->ID_UNIDADE) {
									$strCorpoTabela .= '<input type="checkbox" idPO="'.$idPO.'" ';
									if (is_null($arrPlOrc[$i]['VINCULO'])) {
										$strCorpoTabela .= 'class="chkPO" />';
									} else {
										$strCorpoTabela .= 'checked="checked" disabled="disabled"
											title="Para desmarcar, exclua a ação na tela de respostas do prazo." />';
									}
									$strCorpoTabela .= '&nbsp;';
								}
								
								$strCorpoTabela .= 'PO: ' . $arrPlOrc[$k]['COD_PLANO_ORCAM'] . ' - ' . $arrPlOrc[$k]['TIT_PLANO_ORCAM'] . '</td>' .
									'<td class="tdNumero">' . number_format($arrPlOrc[$k]['VAL_DOTACAO_ATUAL'],2,',','.') . '</td>' .
									'<td class="tdNumero">' . number_format($arrPlOrc[$k]['VAL_EMPENHADO'],2,',','.') . '</td>' .
									'<td class="tdNumero">' . number_format($arrPlOrc[$k]['VAL_LIQUIDADO'],2,',','.') . '</td>' .
									'<td class="tdNumero">' . number_format($arrPlOrc[$k]['PER_LIQ_EMP'],3,',','.') . '%</td>' .
									'</tr>';
							}
						}
					}
				}
			}
		}
		
	} else {
		throw new Exception('Opção de aba inválida!');
	}
	
	
} catch (Exception $e) {
	echo 'Erro: ' . $e->getMessage();
	die;
}
?>

<script type="text/javascript">

	function tratarChkMetas() {
		var arrMetas = $('#hdnIdMetasSelecionadas').val().split(',');
		
		$('.chkMeta').each(function(i, chk) {
			$(chk).attr( "checked", (arrMetas.indexOf($(chk).attr('idMeta')) > -1) );
		});
	}

	function tratarChkPO() {
		var arrPO = $('#hdnIdPOSelecionados').val().split(',');
		
		$('.chkPO').each(function(i, chk) {
			$(chk).attr( "checked", (arrPO.indexOf($(chk).attr('idPO')) > -1) );
		});
	}
	
	$(document).ready(function() {
		$('.chkMeta').click(function() {
			var arrMetas = $('#hdnIdMetasSelecionadas').val().split(',');
			var i = arrMetas.indexOf($(this).attr('idMeta'));
			
			if ($(this).is(":checked")) {
				if (i == -1) {
					/*para evitar valores duplos*/
					arrMetas.push($(this).attr('idMeta'));
				}
			} else {
				if (i > -1) {
					arrMetas.splice(i, 1);
				}
			}
		
			$('#hdnIdMetasSelecionadas').val(arrMetas.toString());
		});

		$('.chkPO').click(function() {
			var arrPO = $('#hdnIdPOSelecionados').val().split(',');
			var i = arrPO.indexOf($(this).attr('idPO'));
			
			if ($(this).is(":checked")) {
				if (i == -1) {
					/*para evitar valores duplos*/
					arrPO.push($(this).attr('idPO'));
				}
			} else {
				if (i > -1) {
					arrPO.splice(i, 1);
				}
			}
		
			$('#hdnIdPOSelecionados').val(arrPO.toString());
		});
		
		tratarChkMetas();
		
		tratarChkPO();
	});

</script>

<style type="text/css" title="currentStyle">

	table.display td th {
		border-width: 2px;
	}
	
	.tdParagrafo {
		text-align: justify;
	}
	
	.tdTexto {
		text-align: left;
	}
	
	.tdNumero {
		text-align: right;
	}
	
	#trTexto {
		text-align: center;
		color: #ffffff;
		font-weight: bold;
		font-size: 1em;
	}
</style>

<table class="display" id="<?php echo $idHtml;?>" style="border-collapse: collapse;">
	<thead><?php echo $strCabTabela; ?></thead>
	<tbody class="tbDadosSiop"><?php echo $strCorpoTabela; ?></tbody>
</table>