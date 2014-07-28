/* 
 * reescrevendo metodo presente em /javascripts/documentos.js
 * 
 * */
function jquery_populate_documento(digital) {

    try {

        $.post("modelos/documentos/documentos.php", {
            acao: 'carregar',
            digital: digital
        },
        function(data) {
            try {
                if (data.success == 'true') {
                    $('#ID_DETALHAR_DOCUMENTO').val(data.documento.id);
                    $('#FAKE_DIGITAL_DETALHAR_DOCUMENTO').val(data.documento.digital);
                    $('#DIGITAL_DETALHAR_DOCUMENTO').val(data.documento.digital);
                    $('#DIGITAL_DETALHAR_DOCUMENTO__SERPRO').val(data.documento.serpro);
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