<?php

require_once __BASE_PATH__ . '/extensoes/pr_snas/1.2/interfaces/dialog_navegar_dados_siop.php';

//INICIA COM A UNIDADE DO USUÁRIO
$optUnidades = '<option value="' . Controlador::getInstance()->usuario->ID_UNIDADE . '">';
$optUnidades .= Controlador::getInstance()->usuario->DIRETORIA . '</option>';
		
$optAnos = '';
$anoInicial = Config::factory()->getParam('ws.siop.exercicio.inicial');
for ($a=date('Y');$a>=$anoInicial;$a--) {
	$optAnos .= '<option value="' . $a . '"';
	if ($a == date('Y')) {
		$optAnos .= ' selected="selected"';
	}
	$optAnos .= '>' . $a . '</option>';
}

$optSituacao = '<option value="0">N&atilde;o requer ajuste legal/normativa/procedimentos</option>';
$optSituacao .= '<option value="1">Requer cria&ccedil;&atilde;o legal/normativa/procedimentos</option>';
$optSituacao .= '<option value="2">Requer altera&ccedil;&atilde;o legal/normativa/procedimentos</option>';
$optSituacao .= '<option value="3">Requer vota&ccedil;&atilde;o legal/normativa/procedimentos</option>';
$optSituacao .= '<option value="4">Situa&ccedil;&atilde;o legal/normativa/procedimentos atendida</option>';

print(Util::autoLoadJavascripts(array('javascripts/jquery.form.js')));
?>
<!-- DIALOG RESPONDER PRAZO -->

<script type="text/javascript">

	var somenteLeitura = false;

	var responderPrazo = function(id, origem) {
		$.post("modelos/prazos/prazos.php", {
			acao: 'carregar-prazo-resposta',
			id: id
		},
		function(data) {
			try {
				if (data) {
					somenteLeitura = (data.resposta.fg_status == 'RP');
	                $('#spnResPrazoDigitalPai').html(data.resposta.digital_pai);
	                $('#spnResPrazoNumRef').html(data.resposta.nu_ref);
	                $('#spnResPrazoInteressado').text(data.resposta.interessado);
	                $('#spnResPrazoRemetente').text(data.resposta.nm_usuario_origem);
	                $('#spnResPrazoSetorRemetente').text(data.resposta.nm_unidade_origem);
	                $('#spnResPrazoDtPrazo').text(data.resposta.dt_prazo);
	                $('#spnResPrazoTipoDoc').text(data.resposta.tipo);
	                $('#divResPrazoSolicitacao').html('<p>' + data.resposta.tx_solicitacao + '</p>');
	    	        $('#spnResPrazoAssunto').text(data.resposta.assunto);
	    	        $('#spnResPrazoAssuntoComp').text(data.resposta.assunto_complementar);
	            	$('#hdnResPrazoIdPrazo').val(id);
	            	$('#hdnResPrazoOrigem').val(origem);
	    	        $('#chkResPrazoSemPPA').attr("checked", (!data.resposta.ha_vinculo));
	    	        $('#txaResPrazoResposta').val(data.resposta.tx_resposta);
	    	        $('#txaResPrazoResposta').attr('readOnly', somenteLeitura);
	    	        $('#selResPrazoLegisSituacao').val(data.resposta.legislacao_situacao);
	    			$('#selResPrazoLegisSituacao').attr('disabled', somenteLeitura);
	    	        $('#txaResPrazoLegisDescricao').val(data.resposta.legislacao_descricao);
	    			$('#txaResPrazoLegisDescricao').attr('readOnly', somenteLeitura);
	    	        $('#hdnResPrazoStatus').val(data.resposta.fg_status);
	    	        $('#hdnUploadPrazo').val(id);
	    	        $('#hdnUploadDigital').val(data.resposta.digital_pai);
	    	        tratarVinculoPPA();
	            	carregarAnexos();
	            	tratarLegisSituacao();
	            	$('#divResponderPrazo').dialog('open');
	            	if (somenteLeitura) {
	            		$('#btnSalvar').hide();
	            		$('#btnEnviar').hide();
	            		$('#imgUpload').hide();
	            		/* oculta o checkbox */
            			$('#chkResPrazoSemPPA').hide();
            			$('#tblPPA').hide();
	            		if (!$('#chkResPrazoSemPPA').is(":checked")) {
		            		/* se não está marcado, oculta o label */
		            		$('#lblResPrazoSemPPA').hide();
		            		$('#divBuscaPPA').show();
	            		}
	            	} else {
	            		$('#btnSalvar').show();
	            		$('#btnEnviar').show();
	            		$('#imgUpload').show();
            			$('#chkResPrazoSemPPA').show();
            			$('#tblPPA').show();
            			$('#lblResPrazoSemPPA').show();	            	
	            	}
                }
			} catch (e) {
				alert('Ocorreu um erro ao tentar abrir detalhes da solicita&ccedil;&atilde;o!\n[' + e + ']');
			}
		}, "json");
		return false;
	}

	function tratarVinculoPPA() {
		if ($('#chkResPrazoSemPPA').is(":checked")) {
			$('.buscaPPA').hide();
		} else {
			carregarMetasPPA();
			$('.buscaPPA').show();
		}
	}
	
	function carregarMetasPPA() {
		$('#divBuscaPPA').empty();
		if ($('#chkResPrazoSemPPA').is(":checked") == false) {
			$('#divBuscaPPA').load('tab_detalhes_responder_prazo.php?lista=ppa&id_prazo=' + $('#hdnResPrazoIdPrazo').val() + '&leitura='+(somenteLeitura ? 'true' : 'false')).show();
		}
	}

	function carregarAnexos() {
		$('#divArqAnexos').empty();
		$('#divArqAnexos').load('tab_detalhes_responder_prazo.php?lista=anexos&id_prazo=' + $('#hdnResPrazoIdPrazo').val() + '&leitura='+(somenteLeitura ? 'true' : 'false')).show();
	}

	function tratarLegisSituacao() {
		if ($('#selResPrazoLegisSituacao').val() > 0) {
			$('#txaResPrazoLegisDescricao').show();
			$('#lblResPrazoLegisDescricao').show();
		} else {
			$('#txaResPrazoLegisDescricao').val('');
			$('#txaResPrazoLegisDescricao').hide();
			$('#lblResPrazoLegisDescricao').hide();
		}
	}

	/* EXCLUIR ANEXO */
	function excluirAnexo(id) {
		if (confirm('Você tem certeza que deseja excluir este anexo?\nOBS: Este procedimento não poderá ser desfeito.')) {
			$.post("modelos/prazos/prazos.php", {
					acao: 'excluir-anexo-resposta',
					id_anexo: id
				},
				function(data) {
					try {
						if (data.success == 'true') {
							carregarAnexos();
	                        alert('Anexo Excluído com Sucesso!');
	                    } else {
							alert(data.error);
						}
					} catch (e) {
						alert('Ocorreu um erro ao excluir o anexo:\n[' + e + ']');
					}
				},
				"json"
			);
		}
	}

	/* EXCLUIR VINCULO COM O OBJETIVO / META */
	function excluirMetaPPA(id) {
		if (confirm('Você tem certeza que deseja excluir este vínculo?')) {
			$.post("modelos/prazos/prazos.php", {
					acao: 'excluir-resposta-ppa',
					id_vinculo: id
				},
				function(data) {
					try {
						if (data.success == 'true') {
							carregarMetasPPA();
	                        alert('Vínculo PPA Excluído com Sucesso!');
	                    } else {
							alert(data.error);
						}
					} catch (e) {
						alert('Ocorreu um erro ao excluir o vínculo PPA:\n[' + e + ']');
					}
				},
				"json"
			);
		}
	}

	$(document).ready(function() {

		function salvarResposta() {
			$('#progressbar').show();
			if (confirm('Você tem certeza que deseja salvar esta resposta agora?\n\nAtenção a resposta não será enviada, para fazê-lo clique em "Enviar".')) {
				$.post("modelos/prazos/prazos.php", {
						acao: 'salvar-resposta',
						sq_prazo: $('#hdnResPrazoIdPrazo').val(),
						tx_resposta: $('#txaResPrazoResposta').val(),
						ha_ppa: !$('#chkResPrazoSemPPA').is(":checked"),
						sit_legis: $('#selResPrazoLegisSituacao').val(),
						desc_legis: $('#txaResPrazoLegisDescricao').val()
					},
					function(data) {
						try {
							if (data.success == 'true') {
		                        alert('Resposta salva com Sucesso!\n\nAtenção a resposta não foi enviada, para fazê-lo clique em "Enviar"');
		                    } else {
								alert(data.error);
							}
						} catch (e) {
							alert('Ocorreu um erro ao salvar o prazo:\n[' + e + ']');
						}
					},
					"json"
				);
			}
			$('#progressbar').hide();
		}
	
		function enviarResposta() {
			$('#progressbar').show();
			if (confirm('Você tem certeza que deseja enviar esta resposta agora?\nOBS: Este procedimento não poderá ser desfeito.')) {
				$.post("modelos/prazos/prazos.php", {
						acao: 'responder-prazo',
						sq_prazo: $('#hdnResPrazoIdPrazo').val(),
						tx_resposta: $('#txaResPrazoResposta').val(),
						ha_ppa: !$('#chkResPrazoSemPPA').is(":checked"),
						sit_legis: $('#selResPrazoLegisSituacao').val(),
						desc_legis: $('#txaResPrazoLegisDescricao').val()
					},
					function(data) {
						try {
							if (data.success == 'true') {
								if ($('#hdnResPrazoOrigem').val() == 'area_trabalho') {
									carregarListaPrazos();
								} else if ($('#hdnResPrazoOrigem').val() == 'prazos') {
                                    oTableRecebidos.fnDraw(false);
                                    oTableRecebidosSetor.fnDraw(false);
								}
		                        limpar();
		                        $('#divResponderPrazo').dialog("close");
		                        alert('Prazo enviado com Sucesso!');
		                    } else {
								alert(data.error);
							}
						} catch (e) {
							alert('Ocorreu um erro ao enviar o prazo:\n[' + e + ']');
						}
					},
					"json"
				);
			}
			$('#progressbar').hide();
		}
	
		function limpar() {
	        $('#spnResPrazoDigitalPai').text('');
	        $('#spnResPrazoNumRef').text('');
	        $('#spnResPrazoInteressado').text('');
	        $('#spnResPrazoRemetente').text('');
	        $('#spnResPrazoSetorRemetente').text('');
	        $('#spnResPrazoDtPrazo').text('');
	        $('#spnResPrazoTipoDoc').text('');
	        $('#divResPrazoSolicitacao').html('');
	        $('#spnResPrazoAssunto').text('');
	        $('#spnResPrazoAssuntoComp').text('');
	        $('#chkResPrazoSemPPA').attr("checked",false);
	        $('.buscaPPA').show();
	        $('#txaResPrazoResposta').val('');
	        $('#selResPrazoLegisSituacao').val(0);
	        $('#txaResPrazoLegisDescricao').val('');
	    	$('#hdnResPrazoIdPrazo').val('');
	    	$('#hdnResPrazoOrigem').val('');
		}
		
		$('#chkResPrazoSemPPA').click(function() {
			tratarVinculoPPA();
		});

		$('#imgResPrazoPesquisarPPA').click(function() {
			pesquisarSiop($('#selResPrazoAnoPPA').val(), $('#selResPrazoUnidadePPA').val());
		});

		function validarResposta() {
			var strErro = '';
			var obj = null;
			
			if ($('#txaResPrazoResposta').val() == '') {
				strErro = '- Informe o conteúdo da resposta.\n';
				obj = $('#txaResPrazoResposta');
			}

			if (($('#selResPrazoLegisSituacao').val() > 0) && ($('#txaResPrazoLegisDescricao').val() == '')) {
				strErro = strErro.concat('- Descreva a situação legal.\n');
				if (obj == null) { obj = $('#txaResPrazoLegisDescricao'); }
			}

			if (!$('#chkResPrazoSemPPA').is(":checked")) {
				if ($('.tdMetaPPA').length == 0) {
					strErro = strErro.concat('- Escolha uma ou mais Metas para vincular à resposta, ou marque "Não há vínculo com o PPA".\n');
					if (obj == null) { obj = $('#chkResPrazoSemPPA'); }
				}
			}
			
			if (strErro != '') {
				obj.focus();
				alert('Existem informações faltando:\n' + strErro);
				return false;
			}
			
			return true;
		}
	    
		$('#divResponderPrazo').dialog({
			autoOpen: false,
	        resizable: false,
	        modal: true,
	        width: 1000,
	        heigth: 'auto',
	        buttons: {
				Salvar: {id: 'btnSalvar',
					text: 'Salvar',
					click: function() {
						if (validarResposta()) {
							salvarResposta();
						}
					}
				},
	        	Enviar: {id: 'btnEnviar',
		        	text: 'Enviar',
		        	click: function() {
						if (validarResposta()) {
							enviarResposta();
						}
					}
				},
				Cancelar: function() {
					limpar();
					carregarListaPrazos();
					$(this).dialog("close");
				}
			}
		});

        /*Filtro Unidade*/
        $('#divResPrazoFiltroUnidadePPA').dialog({
            title: 'Filtro',
            autoOpen: false,
            resizable: false,
            modal: false,
            width: 380,
            height: 90,
            open: function() {
                $("#txtResPrazoFiltroUnidadePPA").val('');
            }
        });

        $('#imgResPrazoFiltroUnidadePPA').click(function() {
            $('#divResPrazoFiltroUnidadePPA').dialog('open');
        });

		$('#selResPrazoLegisSituacao').change(function() {
			tratarLegisSituacao();
		});
		
        /*Combo Unidades*/
        $("#txtResPrazoFiltroUnidadePPA").autocompleteonline({
            url: 'modelos/combos/autocomplete.php',
            idComboBox: 'selResPrazoUnidadePPA',
            extraParams: {
                action: 'unidades-internas',
                type: 'IN'
            }
        });

		/* Upload de arquivos */
		function responderUpload(data, statusText) {
			
			if (statusText == 'success') {
				if (data == null) {
					alert('O arquivo exedeu o tamanho máximo: <?php echo $TAMANHO_MAXIMO_UPLOAD?> Mb.');
				} else if (data.success == 'true') {
					alert(data.message);
					carregarAnexos();
				} else {
					alert('Ocorreu um erro:\n' + data.error);
				}
			} else {
				alert('Ocorreu um erro:\n' + statusText);
			}
			
			$('#inpFileUpload').val(null);
		}
		
		$('#frmUpload').ajaxForm({success: responderUpload, dataType: 'json'});
		
		$('#inpFileUpload').change(function() {
			$('#frmUpload').submit();
		});
		
	}); /* FIM document ready */
	
	
</script>

<style type="text/css" title="currentStyle">

	.tblResponderPrazo {
		font-family: inherit;
		font-size: inherit;
		font-style: inherit;
		color: inherit;
		border-collapse: collapse;
		margin-bottom: 5px;
	}

	.tblResponderPrazo td {
		padding-right: 5px;
		vertical-align: top;
	}
	
	#divResponderPrazo fieldset {
		border: 1px #9ac619 dotted;
		margin: 2px;
		padding: 5px;
	}
	
	#divResponderPrazo label {
		margin: 5px;
		font-weight: bold;
	}
	
	#divResponderPrazo span {
		margin-left: 5px;
		font-weight: normal;
	}
	
	#divResponderPrazo legend {
		font-weight: bold;
		margin-left: 3px;
		margin-right: 3px;
	}
	
	#divResponderPrazo #divResPrazoSolicitacao {
		height: 80px;
		overflow: auto;
	}
	
	#divResponderPrazo #txaResPrazoResposta {
		height: 80px;
		width: 100%;
	}
	
	.tdAnexos {
		text-align: left;
		padding-left: 10px;
	}
	
	.tdLeft {
		text-align: left;
	}
		
	.spnExcluida {
		text-decoration: line-through;
	}
	
	.imgBotao32 {
		width: 32px;
		height: 32px;
	}
	
	.imgBotao16 {
		width: 16px;
		height: 16px;
	}

	.imgBotao16, .imgBotao32 {
		margin: 0px;
		cursor: pointer;
	}
	
	.img16 {
		width: 16px;
		height: 16px;
	}
	
	#divBuscaPPA {
		height: 100px;
		width: 100%;
		overflow: auto;
	}

	#divArqAnexos {
		height: 50px;
		width: 100%;
		overflow: auto;
	}
	
	#txaResPrazoLegisDescricao {
		width: 100%;
	}
	
	#divUpload {
		width: 40px;
		height: 40px;
		overflow: hidden;
		background-image: url('imagens/upload.png');
		background-size: 32px 32px;
    	background-repeat: no-repeat;
    	background-position: center;
	}
	
	#divUpload input {
		position: relative;
		left: 0px;
		font-size: 30px;
		opacity: 0;
		filter: alpha(opacity=0);
		cursor: pointer;
	}
		
</style>


<div id="divResponderPrazo" title="Responder ao Prazo">
	<input type="hidden" id="hdnResPrazoIdPrazo"/>
	<input type="hidden" id="hdnResPrazoOrigem"/>
	<input type="hidden" id="hdnResPrazoStatus"/>

	<fieldset>
		<legend>Dados do Prazo</legend>
		<table class="tblResponderPrazo" style="width: 100%;"><tr>
			<td>
				<label for="spnResPrazoDigitalPai">Digital Principal:</label>
				<br/>
				<span id="spnResPrazoDigitalPai"></span>
			</td>
			<td>
				<label for="spnResPrazoNumRef">N&ordm; Referência:</label>
				<br/>
				<span id="spnResPrazoNumRef"></span>
			</td>
			<td>
				<label for="spnResPrazoDtPrazo">Prazo:</label>
				<br/>
				<span id="spnResPrazoDtPrazo"></span>
			</td>
			<td>
				<label for="spnResPrazoTipoDoc">Tipo:</label>
				<br/>
				<span id="spnResPrazoTipoDoc"></span>
			</td>
			<td style="width: 60%;">
				<label for="spnResPrazoInteressado">Interessado:</label>
				<br/>
				<span id="spnResPrazoInteressado"></span>
			</td>
		</tr></table>
		<table class="tblResponderPrazo" style="width: 100%;"><tr>
			<td style="width: 40%;">
				<label for="spnResPrazoRemetente">Remetente:</label>
				<br/>
				<span id="spnResPrazoRemetente"></span>
			</td>
			<td>
				<label for="spnResPrazoSetorRemetente">Setor Remetente:</label>
				<br/>
				<span id="spnResPrazoSetorRemetente"></span>
			</td>
		</tr><tr>
			<td>
				<label for="spnResPrazoAssunto">Assunto:</label>
				<br/>
				<span id="spnResPrazoAssunto"></span>
			</td>
			<td>
				<label for="spnResPrazoAssuntoComp">Assunto Complementar:</label>
				<br/>
				<span id="spnResPrazoAssuntoComp"></span>
			</td>
		</tr></table>
		<label for="divResPrazoSolicitacao">Solicitação:</label>
		<div id="divResPrazoSolicitacao"></div>
	</fieldset>
	
	<fieldset>
		<legend>Arquivos Anexos</legend>
		<table class="tblResponderPrazo" style="width: 100%;"><tr>
			<td style="width: 100%;">
				<div id="divArqAnexos"></div>
			</td>
			<td style="width: 50px; padding-left: 20px; vertical-align: middle;">
				<div id="divUpload">
					<form id="frmUpload" action="modelos/prazos/prazos.php" method="post" enctype="multipart/form-data">
						<input type="hidden" name="acao" value="incluir-anexo-resposta" />
						<input type="hidden" id="hdnUploadDigital" name="hdnUploadDigital" />
						<input type="hidden" id="hdnUploadPrazo" name="hdnUploadPrazo" />
						<input type="file" id="inpFileUpload" name="inpFileUpload" />
					</form>
				</div>
			</td>
		</tr></table>
	</fieldset>

	<fieldset>
		<legend>Resposta</legend>
		<table id="tblPPA" class="tblResponderPrazo"><tr style="height: 34px;">
			<td style="vertical-align: middle;">
				<label id="lblResPrazoSemPPA" for="chkResPrazoSemPPA">
					<input type="checkbox" id="chkResPrazoSemPPA" />
					N&atilde;o h&aacute; v&iacute;nculo com o PPA
				</label>
			</td>
			<td style="vertical-align: middle;">
				<label class="buscaPPA" for="selResPrazoAnoPPA">Ano:</label> 
				<select id="selResPrazoAnoPPA" class="ui-corner-all FUNDOCAIXA1 buscaPPA">
					<?php echo $optAnos; ?>
				</select>
			</td>
			<td style="width: 590px; vertical-align: middle;">
				<label class="buscaPPA" for="selResPrazoUnidadePPA">Unidade:</label>
	            <span class="conteudo buscaPPA">
	                <select id="selResPrazoUnidadePPA" class="FUNDOCAIXA1 buscaPPA" style="width: 85%">
	                	<?php echo $optUnidades; ?>
	                </select>
	            </span>
	            <img title="Filtrar" style="margin-left:-20px;" class="botao-auxiliar-fix-combobox buscaPPA" id="imgResPrazoFiltroUnidadePPA" src="imagens/fam/application_edit.png" />
			</td>
			<td style="vertical-align: middle;">
				<img id="imgResPrazoPesquisarPPA" src="imagens/icones/32/pesquisar-assuntos.png" class="buscaPPA imgBotao32" alt="Pesquisar PPA" title="Pesquisar PPA" />
			</td>
		</tr></table>
		<div id="divBuscaPPA" class="buscaPPA"></div>
		<br/>
		<label for="txaResPrazoResposta">* Conte&uacute;do da Resposta / Encaminhamentos Necess&aacute;rios:</label>
		<textarea id="txaResPrazoResposta" onkeyup="DigitaLetraSeguro(this)" cols="1" rows="1" class="ui-corner-all FUNDOCAIXA1"></textarea>
	</fieldset>

	<fieldset>
		<legend>Legisla&ccedil;&atilde;o</legend>
		<table class="tblResponderPrazo"><tr>
			<td>
				<label for="selResPrazoLegisSituacao">* Situa&ccedil;&atilde;o:</label>
				<br/>
				<select id="selResPrazoLegisSituacao" class="ui-corner-all FUNDOCAIXA1">
					<?php echo $optSituacao; ?>
				</select>
			</td>
			<td style="width: 100%;">
				<label id="lblResPrazoLegisDescricao" for="txaResPrazoLegisDescricao">Descri&ccedil;&atilde;o:</label>
				<br/>
				<textarea id="txaResPrazoLegisDescricao" onkeyup="DigitaLetraSeguro(this)" cols="1" rows="2" class="ui-corner-all FUNDOCAIXA1"></textarea>
			</td>
		</tr></table>
	</fieldset>
	
</div>

<div id="divResPrazoFiltroUnidadePPA" class="box-filtro">
    <div class="row">
        <label>Unidade:</label>
        <div class="conteudo">
            <input type="text" id="txtResPrazoFiltroUnidadePPA" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1" />
        </div>
    </div>
</div>