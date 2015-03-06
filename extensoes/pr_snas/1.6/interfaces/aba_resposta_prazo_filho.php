<?php

require_once __BASE_PATH__ . '/extensoes/pr_snas/1.6/classes/DaoPrazoDemanda.php';

$idPrazo = intval($_GET['prazo']);
$indAba = intval($_GET['ind']);

$prazo = DaoPrazoDemanda::getPrazoResposta($idPrazo);

?>

<!-- ABA RESPOSTA PRAZO FILHO -->

<script type="text/javascript">
	var ind = '<?php echo $indAba; ?>';
	var id = '<?php echo $idPrazo; ?>';
	var leituraAba = <?php echo $_GET['leitura']; ?>;
	
	$(document).ready(function() {

		$("#clickRemetenteAssunto"+ind).click(function() {
			$( "#divRemetenteAssunto"+ind ).toggle('fast', function() {
				if($("#divRemetenteAssunto"+ind).is(":visible")) {
					$("#clickRemetenteAssunto"+ind).text('-');
					$("#textoRemetenteAssunto"+ind).text('Ocultar remetente / assunto');
				} else {
					$("#clickRemetenteAssunto"+ind).text('+');				
					$("#textoRemetenteAssunto"+ind).text('Exibir remetente / assunto');
				}
			});
		});

		carregarAnexos($('#divArqAnexos'+ind), id, true);

		carregarMetasPPA($('#divVinculosPPA'+ind), <?php echo ($prazo['ha_vinculo'] ? 'false' : 'true'); ?>, id, true);

		if (leituraAba) {
			$("#btnUtilizarResp"+ind).hide();
		} else {
			$("#btnUtilizarResp"+ind).show();
			$("#btnUtilizarResp"+ind).click(function() {
				concatenarResposta(id);
			});
		}

		$('#btnCancelarResp'+ind).click(function() {
			antesFechar();
			$('#divResponderPrazo').dialog("close");
		});
		
	});
</script>

<style type="text/css">
	#divRemetenteAssunto<?php echo $indAba; ?> {
		display: none;
	}
	
	#clickRemetenteAssunto<?php echo $indAba; ?> {
		cursor: pointer;
	}
	
    .imgBtn {
    	width: 20px;
    	height: 20px;
    	border: none;
    	vertical-align: middle;
    }
	
</style>

<input type="hidden" id="hdnIdAba<?php echo $indAba; ?>" value="<?php echo $indAba; ?>" />
<input type="hidden" id="hdnIdPrazo<?php echo $indAba; ?>" value="<?php echo $idPrazo; ?>" />

<fieldset>
	<legend>Dados do Prazo</legend>
	<table class="tblResponderPrazo" style="width: 100%;"><tr>
		<td>
			<label>Digital Principal:</label>
			<br/>
			<span><?php echo $prazo['digital_pai']; ?></span>
		</td>
		<td>
			<label>N&ordm; Referência:</label>
			<br/>
			<span><?php echo $prazo['nu_ref']; ?></span>
		</td>
		<td>
			<label>Prazo:</label>
			<br/>
			<span><?php echo $prazo['dt_prazo']; ?></span>
		</td>
		<td>
			<label>Tipo:</label>
			<br/>
			<span><?php echo $prazo['tipo']; ?></span>
		</td>
		<td style="width: 60%;">
			<label>Interessado:</label>
			<br/>
			<span><?php echo $prazo['interessado']; ?></span>
		</td>
	</tr></table>
	<p>
		[<span id="clickRemetenteAssunto<?php echo $indAba; ?>">+</span> ] 
		<span id="textoRemetenteAssunto<?php echo $indAba; ?>">Exibir remetente / assunto</span>
	</p>
	<div id="divRemetenteAssunto<?php echo $indAba; ?>">
		<table class="tblResponderPrazo" style="width: 100%;"><tr>
			<td style="width: 40%;">
				<label>Remetente:</label>
				<br/>
				<span><?php echo $prazo['nm_usuario_resposta']; ?></span>
			</td>
			<td>
				<label>Setor Remetente:</label>
				<br/>
				<span><?php echo $prazo['nm_unidade_resposta']; ?></span>
			</td>
		</tr><tr>
			<td>
				<label>Assunto:</label>
				<br/>
				<span><?php echo $prazo['assunto']; ?></span>
			</td>
			<td>
				<label>Assunto Complementar:</label>
				<br/>
				<span><?php echo $prazo['assunto_complementar']; ?></span>
			</td>
		</tr></table>
	</div>
	<label>Solicitação:</label>
	<br/>
	<span><?php echo $prazo['tx_solicitacao']; ?></span>
</fieldset>
	
<fieldset>
	<legend>Arquivos Anexos</legend>
	
	<div id="divArqAnexos<?php echo $indAba; ?>"></div>
</fieldset>

<fieldset>
	<legend>Resposta</legend>
<?php
	if (!$prazo['ha_vinculo']) {
		echo '<label>N&atilde;o h&aacute; v&iacute;nculo com o PPA</label>';
	} else {
		echo '<div id="divVinculosPPA'.$indAba.'"></div>';
	}
?>
	<br/><br/>
	
	<label>Conte&uacute;do da Resposta / Encaminhamentos Necess&aacute;rios:</label>
	<br/>
	<span><?php echo $prazo['tx_resposta']; ?></span>
</fieldset>

<fieldset>
	<legend>Legisla&ccedil;&atilde;o</legend>
	
	<label>Situa&ccedil;&atilde;o:</label>
	<br/>
	<span><?php echo $prazo['legislacao_situacao_descricao']; ?></span>
	
	<br/><br/>
	
	<label>Descri&ccedil;&atilde;o:</label>
	<br/>
	<span><?php echo $prazo['legislacao_descricao']; ?></span>
</fieldset>

<div class="divBotoesResposta">
	<button id="btnUtilizarResp<?php echo $indAba; ?>" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
		<span class="ui-button-text">
			Utilizar Resposta
		</span>
	</button>
	<button id="btnCancelarResp<?php echo $indAba; ?>" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
		<span class="ui-button-text">Cancelar</span>
	</button>
</div>
