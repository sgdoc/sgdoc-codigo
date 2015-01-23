<?php
require_once __BASE_PATH__ . '/extensoes/pr_snas/1.4/classes/DaoPrazoDemanda.php';

require_once __BASE_PATH__ . '/extensoes/pr_snas/1.4/interfaces/dialog_navegar_dados_siop.php';

require_once __BASE_PATH__ . '/extensoes/pr_snas/1.4/interfaces/dialog_detalhe_meta_acao_prazo.php';

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

$optSituacao = '';
foreach (DaoPrazoDemanda::getSituacoesLegislacao() as $val => $desc) {
	$optSituacao .= '<option value="' . $val . '">' . $desc . '</option>';
}

print(Util::autoLoadJavascripts(array('javascripts/jquery.form.js')));
?>
<!-- DIALOG RESPONDER PRAZO -->

<script type="text/javascript">

	var somenteLeitura = false;
	var prazosFilhos = null;

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
	    	        prazosFilhos = data.resposta.prazos_filhos;
	    	        tratarVinculoPPA();
	            	carregarAnexos($('#divArqAnexos'), $('#hdnResPrazoIdPrazo').val(), somenteLeitura);
	            	tratarLegisSituacao();
	            	if (!$('#divResponderPrazo').dialog('isOpen')) {
	            		$('#divResponderPrazo').dialog('open');
	            	}
	            	$('#divResPrazoSemPPASomenteLeitura').hide();
	            	if (somenteLeitura) {
	            		$('#divResponderPrazo').dialog('option', 'title', 'Resposta do Prazo');
	            		$('#btnLimparResp').hide();
	            		$('#btnSalvarResp').hide();
	            		$('#btnEnviarResp').hide();
	            		$('#divUpload').hide();
	            		/* oculta a area de busca do PPA */
            			$('#tblPPA').hide();
	            		if (!$('#chkResPrazoSemPPA').is(":checked")) {
		            		/* se não está marcado, oculta o label */
		            		$('#divBuscaPPA').show();
	            		} else {
	            			$('#divResPrazoSemPPASomenteLeitura').show();
	            		}
	            	} else {
	            		$('#divResponderPrazo').dialog('option', 'title', 'Responder ao Prazo');
	            		$('#btnLimparResp').show();
	            		$('#btnSalvarResp').show();
	            		$('#btnEnviarResp').show();
	            		$('#divUpload').show();
            			$('#tblPPA').show();
            			$('#lblResPrazoSemPPA').show();	            	
	            	}
	            	carregarPrazosFilhos();
                }
			} catch (e) {
				alert('Ocorreu um erro ao tentar abrir detalhes da solicitação!\n[' + e + ']');
			}
		}, "json");
		return false;
	}

	function concatenarResposta(idPrazoFilho) {
		$('#progressbar').show();
		if (confirm('Você tem certeza que deseja concatenar esta resposta?\nAtenção essa ação não poderá ser desfeita.')) {
			$.post("modelos/prazos/prazos.php", {
					acao: 'concatenar-resposta',
					id_prazo_pai: $('#hdnResPrazoIdPrazo').val(),
					id_prazo_filho: idPrazoFilho
				},
				function(data) {
					try {
						if (data.success == 'true') {
	                        alert(data.message);
	                        responderPrazo($('#hdnResPrazoIdPrazo').val(), $('#hdnResPrazoOrigem').val());
	                    } else {
							alert(data.error);
						}
					} catch (e) {
						alert('Ocorreu um erro ao concatenar a resposta:\n[' + e + ']');
					}
				},
				"json"
			);
		}
		$('#progressbar').hide();
	}

	function limparResposta() {
		$('#progressbar').show();
		if (confirm('Você tem certeza que deseja limpar os dados desta resposta?\nAtenção essa ação não poderá ser desfeita.')) {
			$.post("modelos/prazos/prazos.php", {
					acao: 'limpar-resposta',
					id_prazo: $('#hdnResPrazoIdPrazo').val()
				},
				function(data) {
					try {
						if (data.success == 'true') {
	                        alert(data.message);
	                        responderPrazo($('#hdnResPrazoIdPrazo').val(), $('#hdnResPrazoOrigem').val());
	                    } else {
							alert(data.error);
						}
					} catch (e) {
						alert('Ocorreu um erro ao limpar a resposta:\n[' + e + ']');
					}
				},
				"json"
			);
		}
		$('#progressbar').hide();
	}
	
	function tratarVinculoPPA() {
		if ($('#chkResPrazoSemPPA').is(":checked")) {
			$('.buscaPPA').hide();
		} else {
			carregarMetasPPA($('#divBuscaPPA'), false, $('#hdnResPrazoIdPrazo').val(), somenteLeitura);
			$('.buscaPPA').show();
		}
	}
	
	function carregarMetasPPA(objDiv, semPPA, idPrazo, leitura) {
		objDiv.empty();
		if (!semPPA) {
			objDiv.load('tab_detalhes_responder_prazo.php?lista=ppa&id_prazo=' + idPrazo + '&leitura=' + (leitura ? 'true' : 'false')).show();
		}
	}

	function carregarAnexos(objDiv, idPrazo, leitura) {
		objDiv.empty();
		objDiv.load('tab_detalhes_responder_prazo.php?lista=anexos&id_prazo=' + idPrazo + '&leitura=' + (leitura ? 'true' : 'false')).show();
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

	function carregarPrazosFilhos() {
		/* remove todos as abas e divs de prazos filhos */
		$('.prazoFilho').remove();
		$("#tabsPrazos").tabs('destroy');
		if (prazosFilhos != null) {
			var arr = JSON.parse(prazosFilhos);
			for	(i=0; i<arr.length;i++) {
				var aAba = $('<a>');
				aAba.attr('href', '#divTabFilho' + i);
				aAba.html(arr[i].SIGLA + ' (' + arr[i].DT_PRAZO + ')');
				var liAba = $('<li>');
				liAba.addClass('prazoFilho');
				liAba.append(aAba);
				$('#ulTabsPrazos').append(liAba);

				var divAba = $('<div>');
				divAba.addClass('prazoFilho');
				divAba.attr('style', 'padding: 0px;');
				divAba.attr('id', 'divTabFilho' + i);
				$('#tabsPrazos').append(divAba);

				divAba.load('aba_resposta_prazo_filho.php?prazo=' + arr[i].ID + '&ind=' + i + '&leitura=' + (somenteLeitura ? 'true' : 'false')).show();
			}
		}
		$("#tabsPrazos").tabs({ active: '1' });
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
							carregarAnexos($('#divArqAnexos'), $('#hdnResPrazoIdPrazo').val(), false);
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
	function excluirVinculoPPA(tipo, id) {
		if (confirm('Você tem certeza que deseja excluir este vínculo?')) {
			$.post("modelos/prazos/prazos.php", {
					acao: 'excluir-resposta-ppa',
					tipo_vinculo: tipo,
					id_vinculo: id
				},
				function(data) {
					try {
						if (data.success == 'true') {
							carregarMetasPPA($('#divBuscaPPA'), $('#chkResPrazoSemPPA').is(":checked"), $('#hdnResPrazoIdPrazo').val(), somenteLeitura);
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
	
	function antesFechar() {
		limpar();
		if ((!somenteLeitura) && ($('#hdnResPrazoOrigem').val() == 'area_trabalho')) {
			carregarListaPrazos();
		}
	}
	
	$(document).ready(function() {

		$("#clickRemetenteAssunto").click(function() {
			$( "#divRemetenteAssunto" ).toggle('fast', function() {
				if($("#divRemetenteAssunto").is(":visible")) {
					$("#clickRemetenteAssunto").text('-');
					$("#textoRemetenteAssunto").text('Ocultar remetente / assunto');
				} else {
					$("#clickRemetenteAssunto").text('+');				
					$("#textoRemetenteAssunto").text('Exibir remetente / assunto');
				}
			});
		});
		
		function salvarResposta() {
			$('#progressbar').show();
			if (confirm('Você tem certeza que deseja salvar esta resposta agora?\n\nAtenção a resposta não será enviada, para fazê-lo clique em "Enviar".')) {
				$.post("modelos/prazos/prazos.php", {
						acao: 'salvar-resposta',
						sq_prazo: $('#hdnResPrazoIdPrazo').val(),
						tx_resposta: $('#txaResPrazoResposta').val(),
						ha_vinculo: !$('#chkResPrazoSemPPA').is(":checked"),
						legislacao_situacao: $('#selResPrazoLegisSituacao').val(),
						legislacao_descricao: $('#txaResPrazoLegisDescricao').val()
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
						ha_vinculo: !$('#chkResPrazoSemPPA').is(":checked"),
						legislacao_situacao: $('#selResPrazoLegisSituacao').val(),
						legislacao_descricao: $('#txaResPrazoLegisDescricao').val()
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
		                        alert('Resposta enviada com sucesso!');
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
	        minHeight: 600,
	        close: function( event, ui ) {
	        	antesFechar();
	        }
		});

		$('#btnLimparResp').click(function() {
			limparResposta();
		});

		$('#btnSalvarResp').click(function() {
			if (validarResposta()) {
				salvarResposta();
			}
		});

		$('#btnEnviarResp').click(function() {
			if (validarResposta()) {
				enviarResposta();
			}
		});

		$('#btnCancelarResp').click(function() {
			antesFechar();
			$('#divResponderPrazo').dialog("close");
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
					carregarAnexos($('#divArqAnexos'), $('#hdnResPrazoIdPrazo').val(), false);
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
	
	#divTabPai {
		padding: 0px;
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
		height: 60px;
		overflow: auto;
	}
	
	#divResponderPrazo #txaResPrazoResposta {
		height: 60px;
		width: 100%;
	}

	.divBotoesResposta {
		margin-top: 10px;
		margin-bottom: 10px;
		width:100%;
		text-align:right;
	}
	
	#divResponderPrazo .divBotoesResposta span {
		font-weight: bold;
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
	
	.imgBotao24 {
		width: 24px;
		height: 24px;
	}
	
	.imgBotao16 {
		width: 16px;
		height: 16px;
	}

	.imgBotao16, .imgBotao24 {
		margin: 0px;
		cursor: pointer;
	}
	
	.img16 {
		width: 16px;
		height: 16px;
	}
	
	#divBuscaPPA {
		height: 130px;
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
		background-size: 24px 24px;
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
	
	#divRemetenteAssunto {
		display: none;
	}
	
	#clickRemetenteAssunto {
		cursor: pointer;
	}
	
</style>

<div id="divResponderPrazo" title=""> <!-- DIALOGO -->

	<div id="tabsPrazos">
		<ul id="ulTabsPrazos">
			<li><a href="#divTabPai">Prazo</a></li>
		</ul>
	
		<div id="divTabPai">
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
					<td style="width: 55%;">
						<label for="spnResPrazoInteressado">Interessado:</label>
						<br/>
						<span id="spnResPrazoInteressado"></span>
					</td>
				</tr></table>
				<p>
					[<span id="clickRemetenteAssunto">+</span> ] 
					<span id="textoRemetenteAssunto">Exibir remetente / assunto</span>
				</p>
				<div id="divRemetenteAssunto">
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
				</div>
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
				<div id="divResPrazoSemPPASomenteLeitura">
					<label>N&atilde;o h&aacute; v&iacute;nculo com o PPA</label>
					<br/>
				</div>
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
						<img id="imgResPrazoPesquisarPPA" src="imagens/icones/32/pesquisar-assuntos.png" class="buscaPPA imgBotao24" alt="Pesquisar PPA" title="Pesquisar PPA" />
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

			<div id="divBotoesRespondesPrazo" class="divBotoesResposta">
				<button id="btnLimparResp" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"><span class="ui-button-text">Limpar Resposta</span></button>
				<button id="btnSalvarResp" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"><span class="ui-button-text">Salvar</span></button>
				<button id="btnEnviarResp" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"><span class="ui-button-text">Enviar</span></button>
				<button id="btnCancelarResp" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"><span class="ui-button-text">Cancelar</span></button>
			</div>

		</div> <!-- FIM divTabPai -->
		
	</div> <!-- FIM TABS -->
</div>

<div id="divResPrazoFiltroUnidadePPA" class="box-filtro">
    <div class="row">
        <label>Unidade:</label>
        <div class="conteudo">
            <input type="text" id="txtResPrazoFiltroUnidadePPA" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1" />
        </div>
    </div>
</div>