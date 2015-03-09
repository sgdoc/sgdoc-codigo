<?php

?>
<!-- DIALOG DETALHAR METAS OU AÇÕES DE UMA RESPOSTA DE PRAZO -->

<script type="text/javascript">

	function limparMetasAcoes() {
		$('#divMeta').hide();
		$('#divAcao').hide();
		
		$('#pPrograma').empty();
		$('#pExercicio').empty();
		
		$('#pObjetivo').empty();
		$('#pMeta').empty();
		
		$('#pAcao').empty();
		$('#tdValDotacao').empty();
		$('#tdValEmpenhado').empty();
		$('#tdValLiquidado').empty();
		$('#tdPerLiqEmp').empty();
		
	}

	function detalharMeta(idVinculo) {
		$.post("modelos/prazos/dados_siop.php", {
			acao: 'detalhar-meta',
			vinculo: idVinculo
		},
		function(data) {
			try {
				limparMetasAcoes();
				if (data) {
					$('#pPrograma').html(data.COD_PROGRAMA + ' - ' + data.TIT_PROGRAMA);
					$('#pExercicio').html(data.EXERCICIO);
					$('#pObjetivo').html(data.COD_OBJETIVO + ' - ' + data.DSC_OBJETIVO);
					$('#pMeta').html(data.COD_META + ' - ' + data.DSC_META);
					$('#divMeta').show();
	            	$('#divMetasAcoes').dialog('open');
	            	$('#divMetasAcoes').dialog('option', 'title', 'Detalhes da Meta');
                } else {
                	alert('Não foi encontrado a meta vinculada!');
                }
			} catch (e) {
				alert('Ocorreu um erro ao tentar carregar a meta vinculada!\n[' + e + ']');
			}
		}, "json");
		return false;
	}

	function detalharAcao(idVinculo) {
		$.post("modelos/prazos/dados_siop.php", {
			acao: 'detalhar-acao',
			vinculo: idVinculo
		},
		function(data) {
			try {
				limparMetasAcoes();
				if (data) {
					$('#pPrograma').html(data.COD_PROGRAMA + ' - ' + data.TIT_PROGRAMA);
					$('#pExercicio').html(data.EXERCICIO);
					$('#pAcao').html(data.COD_ACAO + ' - ' + data.TIT_ACAO);
					$('#tdValDotacao').html('R$ ' + data.VAL_DOTACAO_ATUAL);
					$('#tdValEmpenhado').html('R$ ' + data.VAL_EMPENHADO);
					$('#tdValLiquidado').html('R$ ' + data.VAL_LIQUIDADO);
					$('#tdPerLiqEmp').html(data.PER_LIQ_EMP + '%');
					$('#divAcao').show();
	            	$('#divMetasAcoes').dialog('open');
	            	$('#divMetasAcoes').dialog('option', 'title', 'Detalhes da Ação');
                } else {
                	alert('Não foi encontrado a meta vinculada!');
                }
			} catch (e) {
				alert('Ocorreu um erro ao tentar carregar a meta vinculada!\n[' + e + ']');
			}
		}, "json");
		return false;
	}
	
	$(document).ready(function() {
		$('#divMetasAcoes').dialog({
			autoOpen: false,
	        resizable: false,
	        modal: true,
	        width: 600,
	        buttons: {
				Fechar: function() {
					$(this).dialog("close");
				}
			}
		});
	});
	
</script>

<style type="text/css" title="currentStyle">
	#divTexto p {
		text-align: justify;
	}

	#tabExecOrc {
		width: 100%;
	}
	
	.tdNumero {
		text-align: right;
	}
</style>

<div id="divMetasAcoes" title="Dados SIOP">
	<div id="divTexto">
		<label>Programa:</label>
		<p id="pPrograma"></p>
		<label>Exerc&iacute;cio:</label>
		<p id="pExercicio"></p>
		<div id="divMeta">
			<label>Objetivo:</label>
			<p id="pObjetivo"></p>
			<label>Meta:</label>
			<p id="pMeta"></p>
		</div>
		<div id="divAcao">
			<label>A&ccedil;&atilde;o / Localizador / PO:</label>
			<p id="pAcao"></p>
			<table class="display" id="tabExecOrc">
				<thead>
					<tr>
						<th class="style13 ui-state-default" colspan="4">
							Valoes Execu&ccedil;&atilde;o Or&ccedil;ament&aacute;ria
						</th>
					</tr>
					<tr>
						<th class="style13 ui-state-default">Dota&ccedil;&atilde;o Atual</th>
						<th class="style13 ui-state-default">Empenhado</th>
						<th class="style13 ui-state-default">Liquidado</th>
						<th class="style13 ui-state-default">&nbsp;% Liq. / Emp.</th>
					</tr>				
				</thead>
				<tbody>
					<tr class="odd">
						<td id="tdValDotacao" class="tdNumero"></td>
						<td id="tdValEmpenhado" class="tdNumero"></td>
						<td id="tdValLiquidado" class="tdNumero"></td>
						<td id="tdPerLiqEmp" class="tdNumero"></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>