<?php

?>

<!-- DIALOG EXPORTAR DADOS -->
<script	src="<?php echo '/extensoes/pr_snas/1.7/javascripts/exportar_dados_config.js'?>"></script>

<script type="text/javascript">

	function exportarDados(idDocumento, digDocumento) {
		$('#hdnIdDocumento').val(idDocumento);
		$('#hdnDigDocumento').val(digDocumento);
		$('#divExportarDados').dialog('open');
	}

	function exportar() {
		$('#progressbar').show();
		if ($('#hdnOpcoes').val().trim() == '') {
			alert('Escolha as informações para exportar!');
		} else if (confirm('Confirme a exportação deste documento.')) {
			$.post("modelos/documentos/exportar_documentos_configuravel.php", {
					acao: 'exportar',
					documento: $('#hdnIdDocumento').val(),
					digital: $('#hdnDigDocumento').val(),
					opcoes: $('#hdnOpcoes').val()
				},
				function(data) {
					if (data.success == 'true') {
						alert(data.message);
						popUp_anexo('download_arquivo.php?arquivo=' + data.file);
						$('#visualizador_popup').hide();
					} else if (data.success == 'false') {
						alert(data.erro);
					} else {
						alert('Erro ao exportar o documento!');
					}
				},
				"json"
			);
		}
		$('#progressbar').hide();
	}
	
	$(document).ready(function() {
		$('#divExportarDados').dialog({
			autoOpen: false,
	        resizable: false,
	        modal: true,
	        width: 400,
	        height: 300,
	        buttons: {
		        Exportar: function() {
		        	exportar();
		        },
				Fechar: function() {
					$(this).dialog("close");
				}
			}
		});

		$.post("modelos/documentos/exportar_documentos_configuravel.php", 
			{ acao: 'obter_opcoes' },
			function(data) {
				if (data.success == 'true') {
					montarOpcoes($('#divConfigExportacao'), JSON.parse(data.message));
				} else if (data.success == 'false') {
					alert(data.erro);
				} else {
					alert('Erro ao obter as opções!');
				}
			},
			"json"
		);
		
	});	

</script>

<div id="divExportarDados" title="Exportar Dados">

	<input type="hidden" id="hdnIdDocumento" />
	<input type="hidden" id="hdnDigDocumento" />
	
	<div id="divConfigExportacao">
	</div>

</div>