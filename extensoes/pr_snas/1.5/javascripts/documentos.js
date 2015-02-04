;/*
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
function digital_selecionados(total_documentos) {
    var cont = 0;
    var lim = total_documentos;
    var parametros = Array();
    for (var i = 0; i < lim; i++) {
        if (document.getElementById('DIGITAL[' + i + ']').checked) {
            parametros[cont] = document.getElementById('DIGITAL[' + i + ']').value;//com jquery nao funcionou...
            cont++;
        }
    }
    return parametros;
}

function marcar_todos_documentos(total_documentos) {
    var valor = document.getElementById('marcadorD').checked;
    var lim = total_documentos;
    for (var i = 0; i < lim; i++) {
        document.getElementById('DIGITAL[' + i + ']').checked = valor;
    }
}

/*Funcoes JQuery*/
/*Listar os vinculos dos documentos*/
function jquery_listar_vinculacao_documento(digital, reload) {
    popup('lista_vinculacao_documentos.php?digital=' + digital, function() {
        if (reload) {
            oTableDocumentos.fnDraw(false);
        }
    });
}

/*Verificar a quantidade de imagens de um documento*/
function jquery_quantidade_imagens_documento(digital) {

    var r = $.ajax({
        type: 'POST',
        url: 'modelos/documentos/imagens.php',
        data: 'acao=quantidade&digital=' + digital,
        async: false,
        success: function() {
        },
        failure: function() {
        }
    }).responseText;

    r = eval('(' + r + ')');

    if (r.success == 'true') {
        return r.quantidade;
    } else {
        return false;
    }

}
/* Preencher Campos...*/
function jquery_populate_documento(digital, acao) {
	acao = acao || '';
    try {

        $.post("modelos/documentos/documentos.php", {
            acao: 'carregar',
            digital: digital
        },
        function(data) {
            try {
                if (data.success == 'true') {
                	$('#HDN_ACAO').val(acao);
                    $('#ID_DETALHAR_DOCUMENTO').val(data.documento.id);
                    $('#FAKE_DIGITAL_DETALHAR_DOCUMENTO').val(data.documento.digital);
                    $('#DIGITAL_DETALHAR_DOCUMENTO').val(data.documento.digital);
                    $('#PROCEDENCIA_DETALHAR_DOCUMENTO').val(data.documento.procedencia);
                    $('#NUMERO_DETALHAR_DOCUMENTO').val(data.documento.numero);
                    /*Tipo*/
                    $('#TIPO_DETALHAR_DOCUMENTO').append($('<option></option>').val(data.documento.tipo).html(data.documento.tipo));
                    $('#TIPO_DETALHAR_DOCUMENTO').val(data.documento.tipo);
                    /*Origem*/
                    $('#ORIGEM_DETALHAR_DOCUMENTO').empty().append($('<option></option>').val(data.documento.origem).html(data.documento.origem));
                    /*Assunto*/
                    $('#ASSUNTO_DETALHAR_DOCUMENTO').empty().append($('<option></option>').val(data.documento.id_assunto).html(data.documento.assunto));
                    $('#ASSUNTO_COMPLEMENTAR_DETALHAR_DOCUMENTO').val(data.documento.assunto_complementar);
                    /*Prioridade*/
                    $('#PRIORIDADE_DETALHAR_DOCUMENTO').val(data.documento.prioridade);
                    /*Destino*/
                    $('#DESTINO_DETALHAR_DOCUMENTO').empty().append($('<option></option>').val(data.documento.destino).html(data.documento.destino));

                    $('#TECNICO_RESPONSAVEL_DETALHAR_DOCUMENTO').val(data.documento.tecnico_responsavel);
                    $('#INTERESSADO_DETALHAR_DOCUMENTO').val(data.documento.interessado);
                    $('#ASSINATURA_DETALHAR_DOCUMENTO').val(data.documento.assinatura);
                    $('#CARGO_DETALHAR_DOCUMENTO').val(data.documento.cargo);
                    $('#STATUS_PRAZO_DETALHAR_DOCUMENTO').attr('checked', data.documento.fg_prazo);
                    $('#DATA_DOCUMENTO_DETALHAR_DOCUMENTO').val(data.documento.dt_documento);
                    if (data.documento.recibo != 'null') {
                        $('#RECIBO_DETALHAR_DOCUMENTO').val(data.documento.recibo);
                    }

                    /*Data Prazo*/
                    if (data.documento.dt_prazo != null) {
                        $('#DATA_PRAZO_DETALHAR_DOCUMENTO').val(data.documento.dt_prazo);
                        /*Notificacao do Prazo*/
                        if (data.documento.fg_prazo == true && data.documento.dt_prazo != false) {
                            jquery_get_dias_prazo(data.documento.dt_prazo, 'notificacao-prazo-detalhar-documento');
                        } else {
                            $('#notificacao-prazo-detalhar-documento').html('');
                        }
                    } else {
                        $('#DATA_PRAZO_DETALHAR_DOCUMENTO').val('');
                        $('#STATUS_PRAZO_DETALHAR_DOCUMENTO').attr('checked', false);
                        $('#notificacao-prazo-detalhar-documento').html('');
                    }

                    /*Data Entrada*/
                    if (data.documento.dt_entrada != '00/00/0000') {
                        $('#DATA_ENTRADA_DETALHAR_DOCUMENTO').val(data.documento.dt_entrada);
                    } else {
                        $('#DATA_ENTRADA_DETALHAR_DOCUMENTO').val('');
                    }

                    $('#progressbar').hide();
                    
                    //alert('ACAO = ' + acao);
                    
                    if (acao == 'N') {
                    	//OCULTA AS AÇÕES PARA DOCUMENTOS QUE NÃO SÃO DA UNIDADE ATUAL
                    	$("#botao-folha-despacho-documento").hide();
                    	$("#botao-upload-imagens-multiplas-documentos").hide();
                    	$("#botao-salvar-alteracoes-documentos").hide();
                    	$("#botao-nova-demanda-associada").hide();
                    } else {
                    	$("#botao-folha-despacho-documento").show();
                    	$("#botao-upload-imagens-multiplas-documentos").show();
                    	$("#botao-salvar-alteracoes-documentos").show();
                    	$("#botao-nova-demanda-associada").show();
                    }
                    
                    $('#div-form-detalhar-documentos').dialog('open');
                    
                } else {
                    $('#progressbar').hide();
                    alert(data.error);
                }
            } catch (e) {
                $('#progressbar').hide();
                alert('Ocorreu um erro ao tentar carregar as informacoes do documento!\n[' + e + ']');
            }
        }, "json");


    } catch (e) {
        $('#progressbar').hide();
        alert('Ocorreu um ao tentar carregar as informacoes do documento!\n[' + e + ']');
    }
}
/*Carregar formulario de Detalhes de Documentos*/
function jquery_detalhar_documento(digital, acao) {
    /*Ativar Preloader*/
    $('#progressbar').show();

    /*Previnir que a solicitacao ocorra sem necessidade*/
    if ($('#TIPO_DETALHAR_DOCUMENTO').find('option').length == 0) {
        /*Carregar o combo de tipologias de documentos*/
        $('#TIPO_DETALHAR_DOCUMENTO').comboboxWithCallBack('modelos/combos/tipologias_documentos.php', null, function() {
            jquery_populate_documento(digital, acao);
        });
    } else {
        jquery_populate_documento(digital, acao);
    }
}

/*Listener campo procedencia do documento*/
$(document).ready(function() {

    $('#PROCEDENCIA_DETALHAR_DOCUMENTO').change(function() {
        if ($(this).val() == 'E') {
            $('#RECIBO_DETALHAR_DOCUMENTO').removeAttr('disabled');
            $('#DATA_ENTRADA_DETALHAR_DOCUMENTO').removeAttr('disabled');
        } else {
            $('#RECIBO_DETALHAR_DOCUMENTO').attr('disabled', 'disabled');
            $('#DATA_ENTRADA_DETALHAR_DOCUMENTO').attr('disabled', 'disabled');
            $('#RECIBO_DETALHAR_DOCUMENTO').val('');
            $('#DATA_ENTRADA_DETALHAR_DOCUMENTO').val('');
        }
    });
});