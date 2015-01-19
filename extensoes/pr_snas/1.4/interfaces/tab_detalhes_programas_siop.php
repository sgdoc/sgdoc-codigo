<?php
require_once __BASE_PATH__ . '/extensoes/pr_snas/1.4/classes/DaoDadosSiop.php';

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
							<th class="'.$strClasseTh.'" style="width: 40%">Objetivos</th>
							<th class="'.$strClasseTh.'">Metas</th>
						<tr>';
		
		$numTam = count($arrDados);
		$idObj = '';
		$strCorpoTabela = '';
		$tr = '<tr class="even">';
		//var_dump($arrDados); die;
		if ($arrDados && ($numTam > 0)) {
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

	} elseif ($aba == 'acoes') { //AÇÕES
		$idHtml = 'tblAcoes';
		$arrDados = DaoDadosSiop::getAcoes($programa, $orgSiop, $ano, $prazo);
		
		$strCabTabela = '<tr>
							<th class="'.$strClasseTh.'" rowspan="2" width="40%">A&ccedil;&atilde;o / Localizador / PO</th>
							<th class="'.$strClasseTh.'" colspan="4">Valores</th>
						</tr>
						<tr>
							<th class="'.$strClasseTh.'">Dota&ccedil;&atilde;o Atual (R$)</th>
							<th class="'.$strClasseTh.'">Empenhado (R$)</th>
							<th class="'.$strClasseTh.'">Liquidado (R$)</th>
							<th class="'.$strClasseTh.'">&nbsp;% Liq. / Emp.</th>
						</tr>';
		
		$numTam = count($arrDados);
		$strCorpoTabela = '';
		$tr = '<tr class="even">';
		
		if ($arrDados && ($numTam > 0)) {
			for ($i=0;$i<$numTam;$i++) {
				$tr = ($tr == '<tr class="odd">' ? '<tr class="even">' : '<tr class="odd">');
				$strCorpoTabela .= $tr . '<td class="tdTexto">';
				if ($unidade == Controlador::getInstance()->usuario->ID_UNIDADE) {
					$strCorpoTabela .= '<input type="checkbox" idAcao="'.$arrDados[$i]['ID_ACAO'].'" ';
					if (is_null($arrDados[$i]['VINCULO'])) {
						$strCorpoTabela .= 'class="chkAcao" />';
					} else {
						$strCorpoTabela .= 'checked="checked" disabled="disabled"
											title="Para desmarcar, exclua a ação na tela de respostas do prazo." />';
					}
					$strCorpoTabela .= '&nbsp;&nbsp;&nbsp;';
				}
				$strCorpoTabela .= $arrDados[$i]['COD_ACAO'] . ' - ' . $arrDados[$i]['TIT_ACAO'] . '</td>';
				$strCorpoTabela .= '<td class="tdNumero">' . number_format($arrDados[$i]['VAL_DOTACAO_ATUAL'],2,',','.') . '</td>';
				$strCorpoTabela .= '<td class="tdNumero">' . number_format($arrDados[$i]['VAL_EMPENHADO'],2,',','.') . '</td>';
				$strCorpoTabela .= '<td class="tdNumero">' . number_format($arrDados[$i]['VAL_LIQUIDADO'],2,',','.') . '</td>';
				$strCorpoTabela .= '<td class="tdNumero">' . number_format($arrDados[$i]['PER_LIQ_EMP'],3,',','.') . ' %</td>';
				$strCorpoTabela .= '</tr>';
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

	function tratarChkAcoes() {
		var arrAcoes = $('#hdnIdAcoesSelecionadas').val().split(',');
		
		$('.chkAcao').each(function(i, chk) {
			$(chk).attr( "checked", (arrAcoes.indexOf($(chk).attr('idAcao')) > -1) );
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
		
		$('.chkAcao').click(function() {
			var arrAcoes = $('#hdnIdAcoesSelecionadas').val().split(',');
			var i = arrAcoes.indexOf($(this).attr('idAcao'));
			
			if ($(this).is(":checked")) {
				if (i == -1) {
					/*para evitar valores duplos*/
					arrAcoes.push($(this).attr('idAcao'));
				}
			} else {
				if (i > -1) {
					arrAcoes.splice(i, 1);
				}
			}
		
			$('#hdnIdAcoesSelecionadas').val(arrAcoes.toString());
		});
		
		tratarChkMetas();
		
		tratarChkAcoes();
	});

</script>

<style type="text/css" title="currentStyle">

	.tdParagrafo {
		text-align: justify;
	}
	
	.tdTexto {
		text-align: left;
	}
	
	.tdNumero {
		text-align: right;
	}
	
</style>

<table class="display" id="<?php echo $idHtml;?>">
	<thead><?php echo $strCabTabela; ?></thead>
	<tbody><?php echo $strCorpoTabela; ?></tbody>
</table>