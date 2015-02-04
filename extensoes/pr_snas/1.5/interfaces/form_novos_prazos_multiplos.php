<?php
/*
 * Copyright 2008 ICMBio
 * Este arquivo é parte do programa SISICMBio
 * O SISICMBio é um software livre; você pode redistribuíção e/ou modifição dentro dos termos
 * da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão
 * 2 da Licença.
 *
 * Este programa é distribuíção na esperança que possa ser útil, mas SEM NENHUMA GARANTIA; sem
 * uma garantia implícita de ADEQUAÇÃO a qualquer MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a
 * Licença Pública Geral GNU/GPL em português para maiores detalhes.
 * Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "LICENCA.txt",
 * junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço
 * www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF)
 * Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
 * */
?>
<script type="text/javascript">

var listarUsuariosEncaminhar = function(unidade) {
    $.getJSON("modelos/combos/mostrar_usuarios_unidades.php", {
        diretoria: unidade,
        request: 'usuarios'
    }, function(data) {
        if (data) {
            var newOption = $('<option>');
            newOption.html('Nenhum');
            newOption.attr('value', '');
            $('#selEncaminharUsuarioDestino').append(newOption);
            $.each(data.usuarios, function(i) {
                var newOption = $('<option>');
                newOption.html(data.usuarios[i].nome);
                newOption.attr('value', data.usuarios[i].id);
                $('#selEncaminharUsuarioDestino').append(newOption);
            });
        } else {
            alert('Esta unidade não possui usuários cadastrados!');
        }
    });
}

function limparChkPrazo() {
	$('#hdnEncaminharPrazosSelecionados').val('');
	$('#hdnEncaminharPrazosData').val('');
	
	$('.chkPrazo').each(function(i, chk) {
		$(chk).attr("checked", false);
	});
}

function validarEncaminhar() {
	if ($('#selEncaminharUnidadeDestino').val() == '') {
		alert('Informe a unidade de destino');
	} else if ($("#txtEncaminharDataPrazo").val() == '') {
		alert('Informe a data dos prazos.');
	} else {
		return true;
	}
	return false;
}

function selecionarMenorPrazo() {
	var arrDatas = new Array();
	var strData = "";
	
	var date_sort_asc = function (date1, date2) {
	  if (date1 > date2) return 1;
	  if (date1 < date2) return -1;
	  return 0;
	};

	
	$('.chkPrazo').each(function(i, chk) {
		if ($(chk).is(":checked")) {
			strData = $(chk).attr('dataPrazo');
			if (jQuery.browser.mozilla) {
				strData = strData.concat('T00:00:00');
			} else {
				strData = strData.concat(' 00:00:00');
			}
			arrDatas.push(new Date(strData));
		}
	});
	
	arrDatas.sort(date_sort_asc);
	$('#hdnEncaminharPrazosData').val(convertDateToString(arrDatas[0].toISOString().substr(0, 10)));
}

$(document).ready(function() {
	
    $('#btnEncaminharPrazos').live('click', function(e) {
        if ($('#hdnEncaminharPrazosSelecionados').val() != '') {
	        
        	$('#boxEncaminharPrazos').dialog('open');
        } else {
        	alert('Escolha um ou mais demadas para encaminhar.');
        }
    });

   	$('.chkPrazo').live('click', function(e) {
		var arrPrazos = $('#hdnEncaminharPrazosSelecionados').val().split(',');
		var i = arrPrazos.indexOf($(this).attr('idPrazo'));
			
		if ($(this).is(":checked")) {
			if (i == -1) {
				/*para evitar valores duplos*/
				arrPrazos.push($(this).attr('idPrazo'));
			}
		} else {
			if (i > -1) {
				arrPrazos.splice(i, 1);
			}
		}
		$('#hdnEncaminharPrazosSelecionados').val(arrPrazos.toString());
		
		selecionarMenorPrazo();
	});
    
    $('#boxEncaminharPrazos').dialog({
        autoOpen: false,
        resizable: false,
        modal: true,
        width: 600,
        autoHeight: true,
        open: function(event, ui) {
        	$('#selEncaminharUnidadeDestino').empty();
        	$('#selEncaminharUsuarioDestino').empty();
        	$("#txtEncaminharDataPrazo").val($('#hdnEncaminharPrazosData').val());
        	$('#txtEncaminharSolicitacao').val('');
        },
        buttons: {
            Salvar: function() {
                if (validarEncaminhar()) {
                    if (confirm('Você tem certeza que deseja encaminhar estes prazos?\nOBS: Esta operação não poderá ser desfeita!')) {
                        $.post("extensoes/pr_snas/1.5/modelos/prazos/prazos.php", {
                            acao: 'encaminhar',
                            prazos: $('#hdnEncaminharPrazosSelecionados').val(),
                            id_unid_destino: $('#selEncaminharUnidadeDestino').val(),
                            id_usuario_destino: $('#selEncaminharUsuarioDestino').val(),
                            dt_prazo: $('#txtEncaminharDataPrazo').val(),
                            tx_solicitacao: $('#txtEncaminharSolicitacao').val()
                        },
                        function(data) {
                            try {
                            	carregarListaPrazos();
                            	$('#progressbar').hide();
                               	limparChkPrazo();
								$("#boxEncaminharPrazos").dialog("close");

                                if (data.success == 'true') {
                                    alert('Prazos encaminhados com sucesso!');
                                } else {
                                    alert(data.error);
                                }
                            } catch (e) {
                                alert('Ocorreu um erro ao encaminhar os prazos:\n[' + e + ']');
                            }
                        }, "json");
                    } else {
                        $('#progressbar').hide();
                    }
                }
            }
        }
    });

    $("#txtEncaminharDataPrazo").datepicker({
        minDate: 0
    });
    
    /*Filtro Unidade*/
    $('#divEncaminharPrazoFiltroUnidade').dialog({
        title: 'Filtro',
        autoOpen: false,
        resizable: false,
        modal: false,
        width: 380,
        height: 90,
        open: function() {
            $("#txtEncaminharPrazoFiltroUnidade").val('');
        }
    });

    $('#imgEncaminharPrazoFiltroUnidade').click(function() {
        $('#divEncaminharPrazoFiltroUnidade').dialog('open');
    });

    /*Combo Unidades*/
    $("#txtEncaminharPrazoFiltroUnidade").autocompleteonline({
        url: 'modelos/combos/autocomplete.php',
        idComboBox: 'selEncaminharUnidadeDestino',
        extraParams: {
            action: 'unidades-internas',
            type: 'IN'
        },
        onFinish: function() {
            $('#selEncaminharUsuarioDestino').empty();
            listarUsuariosEncaminhar($('#selEncaminharUnidadeDestino').val());
        }
    });

    $('#selEncaminharUnidadeDestino').change(function(e) {
        $('#selEncaminharUsuarioDestino').empty();
        listarUsuariosEncaminhar($('#selEncaminharUnidadeDestino').val());
    });
    
});

</script>

<div id="boxEncaminharPrazos" class="div-form-dialog" title="Encaminhar Prazos">
	<input type="hidden" id="hdnEncaminharPrazosSelecionados" />
	<input type="hidden" id="hdnEncaminharPrazosData" />

	<div class="row">
		<label for="selEncaminharUnidadeDestino" class="label">*Setor Destino:</label>
		<span class="conteudo">
			<select id="selEncaminharUnidadeDestino" class="FUNDOCAIXA1"></select>
		</span>
		<img title="Filtrar" class="botao-auxiliar-fix-combobox" id="imgEncaminharPrazoFiltroUnidade" src="imagens/fam/application_edit.png">
	</div>

	<div class="row">
		<label for="selEncaminharUsuarioDestino" class="label">Destinatário:</label>
		<span class="conteudo">
			<select id="selEncaminharUsuarioDestino" class="FUNDOCAIXA1">
				<option value="">Nenhum</option>
			</select>
		</span>
	</div>
	
	<div class="row">
		<label for="txtEncaminharDataPrazo" class="label">*Data do Prazo:</label>
		<span class="conteudo">
			<input type="text" id="txtEncaminharDataPrazo" class="FUNDOCAIXA1">
		</span>
	</div>
	
	<div style="text-align: left;">
		<label for="txtEncaminharSolicitacao" class="label">Solicitação:<br/>Este texto será anexado ao texto original dos prazos.</label>
		<textarea id="txtEncaminharSolicitacao" class="FUNDOCAIXA1" onkeyup="DigitaLetraSeguro(this)" style="width: 100%; height: 100;"></textarea>
	</div>
</div>


<div id="divEncaminharPrazoFiltroUnidade" class="box-filtro">
    <div class="row">
        <label>Unidade:</label>
        <div class="conteudo">
            <input type="text" id="txtEncaminharPrazoFiltroUnidade" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1" />
        </div>
    </div>
</div>