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

    var oTableLocalizarDocs;

    $(document).ready(function() {

        /*Dialog*/

        $('#box-localizar-digital').dialog({
            autoOpen: false,
            resizable: false,
            modal: true,
            width: '90%',
            height: 620,
            position: ['center', 60],
            open: function(event, ui) {
                jquery_iniciar_datatables2();
            },
            close: function(event, ui) {
                oTableLocalizarDocs.fnDestroy();
                //oTableCaixas.fnDraw(false);
            }
        });

        $('#NUMERO_PESQUISAR_DIGITAL').keyup(function() {
            formatar_numero_processo(document.getElementById('NUMERO_PESQUISAR_DIGITAL'));
        });

        /*Pesquisar*/
        $('#box-pesquisar-digital').dialog({
            autoOpen: false,
            resizable: false,
            modal: true,
            width: 600,
            autoHeight: true,
            open: function() {
                $('#DIGITAL_PESQUISAR_DIGITAL').val('');
                $('#NUMERO_PESQUISAR_DIGITAL').val('');
            },
            buttons: {
                Pesquisar: function() {
                    if (
                            $('#DIGITAL_PESQUISAR_DIGITAL').val() == '' &&
                            $('#NUMERO_PESQUISAR_DIGITAL').val() == ''
                            )
                    {
                        alert('Preencha a digital ou o número do processo.');
                        return false;
                    }
                    $.post("modelos/documentos/documentos.php", {
                        acao: 'pesquisar',
                        DIGITAL: $('#DIGITAL_PESQUISAR_DIGITAL').val(),
                        NUMERO: $('#NUMERO_PESQUISAR_DIGITAL').val()
                    },
                    function(data) {
                        try {
                            if (data.success == 'true') {
                                /*Alert*/
                                $('#box-localizar-digital').dialog('open');
                                $('#box-pesquisar-digital').dialog("close");
                            } else {
                                alert(data.error);
                            }
                        } catch (e) {
                            alert('Ocorreu um erro ao tentar localizar documento!\n[' + e + ']');
                        }
                    },
                            "json"
                            );

                }
            }
        });

        $('#botao-pesquisa-documento-digital').click(function() {
            $('#box-pesquisar-digital').dialog('open');
        });
    });

    function jquery_retirar_documento_caixa2(id) {
        $('#progressbar').show();
        $.post("modelos/caixas/caixas.php",
                {
                    acao: 'retirar-documento',
                    id: parseInt(id)
                },
        function(data) {
            $('#progressbar').hide();
            if (data.success == 'true') {
                oTableLocalizarDocs.fnDraw(false);
            } else {
                alert('Ocorreu um erro ao tentar retirar documento da caixa!');
            }
        }, "json");
    }

    function jquery_iniciar_datatables2() {
        /*DataTable*/
        oTableLocalizarDocs = $('#tabela_localizar_documento').dataTable({
            aLengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            bStateSave: false,
            bPaginate: false,
            bProcessing: false,
            bServerSide: true,
            bJQueryUI: true,
            sScrollY: "380px",
            sPaginationType: "full_numbers",
            sAjaxSource: "modelos/caixas/listar_caixa_documento.php",
            oLanguage: {
                sProcessing: "Carregando...",
                sLengthMenu: "_MENU_ por página",
                sZeroRecords: "Nenhum documento encontrado em caixas com esta digital.",
                sInfo: "_START_ a _END_ de _TOTAL_ documentos.",
                sInfoEmpty: "Nenhum documento encontrado em caixas com esta digital!",
                sInfoFiltered: "(Total _MAX_ documentos)",
                sInfoPostFix: "",
                sSearch: "Pesquisar:",
                oPaginate: {
                    sFirst: "Primeira",
                    sPrevious: "Anterior",
                    sNext: "Proxima",
                    sLast: "Ultima"
                }
            },
            fnServerData: function(sSource, aoData, fnCallback) {
                $.getJSON(sSource, aoData, function(json) {
                    fnCallback(json);
                });
            },
            fnRowCallback: function(nRow, aData, iDisplayIndex) {
                nRow.setAttribute('title', aData[3]);

                var $line = $('td:eq(8)', nRow);
                $line.html('');

                if (aData[3] == '') {
                    $('td:eq(3)', nRow).html('<div title=""></div>');
                }

                if (aData[4] == '') {
                    $('td:eq(4)', nRow).html('<div title=""></div>');
                }

                if (aData[5] == '') {
                    $('td:eq(5)', nRow).html('<div title=""></div>');
                }

                if (aData[6] == '') {
                    $('td:eq(6)', nRow).html('<div title=""></div>');
                }

                $("<img/>", {
                    src: 'imagens/cancelar_upload.png',
                    title: 'Retirar documento da caixa',
                    'class': 'botao32'
                }).bind("click", function() {
                    jquery_retirar_documento_caixa2(aData[0]);
                }).appendTo($line);

                return nRow;
            }
        });
    }
</script>
</head>
<body>
    <div id="box-localizar-digital" class="div-form-dialog" title="Localizar Documento">
        <input type="hidden" id="DIGITAL_LOCALIZAR_CAIXA" />
        <input type="hidden" id="NUMERO_LOCALIZAR_CAIXA" />
        <div class="cabecalho-caixas">
            <div class="logo-manter-unidades"></div>
            <div class="titulo-manter-unidades">Localizar Documento por Digital</div>
            <div class="menu-auxiliar">
            </div>
        </div>
        <table class="display" border="0" id="tabela_localizar_documento">
            <thead>
                <tr>
                    <th class="style13">#</th>
                    <th class="style13">Número da Caixa</th>
                    <th class="style13">Diretoria da Caixa</th>
                    <th class="style13">Digital</th>
                    <th class="style13">Número do Documento</th>
                    <th class="style13">Área de Trabalho</th>
                    <th class="style13">Destino</th>
                    <th class="style13">Classificação</th>
                    <th class="style13">Opções</th>
                </tr>
            </thead>
        </table>
    </div>
    <!--Pesquisar-->
    <div id="box-pesquisar-digital" class="div-form-dialog" title="Pesquisar Documentos">
        <fieldset>
            <label>Informações Principais</label>
            <div class="row">
                <label class="label">DIGITAL:</label>
                <span class="conteudo">
                    <input type="text" class="FUNDOCAIXA1" id="DIGITAL_PESQUISAR_DIGITAL" maxlength="7" onkeyup="DigitaNumero(this)" />
                </span>
            </div>
            <div class="row">
                <label class="label">NUMERO PROCESSO:</label>
                <span class="conteudo">
                    <input type="text" class="FUNDOCAIXA1" id="NUMERO_PESQUISAR_DIGITAL" maxlength="50" onkeyup="DigitaLetraSeguro(this)" />
                </span>
            </div>
        </fieldset>
    </div>