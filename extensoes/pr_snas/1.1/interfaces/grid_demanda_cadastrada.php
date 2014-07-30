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

include(__BASE_PATH__ . '/extensoes/pr_snas/1.1/interfaces/form_novo_prazo.php');

?>

<script type="text/javascript">

    var oTableDocumentos;
    var total_documentos;
    var toggleDemanda = [];
	var unidadeCorrente = "<?php echo Controlador::getInstance()->usuario->ID_UNIDADE; ?>";

    $(document).ready(function() {
        $('.botao-visualizar-detalhes-demanda').die('click').live('click', function(e) {
            var demanda = $(this).attr('demanda');
            e.preventDefault();

            toggleDemanda[demanda] = !toggleDemanda[demanda];

            if (toggleDemanda[demanda]) {
                $('.toggleUnion').hide();
                $('.toggleDemandaOff').show();
            } else {
                $('.toggleUnion').show();
                $('.toggleDemandaOff').hide();
            }

        });

        $('.botao-remover-associacao').die('click').live('click', function(e) {
            var demanda = $(this).attr('demanda');
            e.preventDefault();
        });

        $('.botao-novo-prazo').die('click').live('click', function(e) {
            var demanda = $(this).attr('demanda');
            e.preventDefault();

            $('#nu_proc_dig_ref').val(demanda);
            $('#box-novo-prazo').dialog('open');
        });

        $('.link_historico').die('click').live('click', function(e) {
            var demanda = $(this).attr('demanda');
            e.preventDefault();

            popup('historico_tramite_documentos.php?digital=' + demanda, function() {

            });
        });

        /*DataTable*/
        oTableDocumentosDemandas = $('#TabelaDocumentosDemanda').dataTable({
            aLengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            bStateSave: false,
            bPaginate: true,
            bProcessing: false,
            bServerSide: true,
            bJQueryUI: true,
            sPaginationType: "full_numbers",
            sAjaxSource: "modelos/documentos/listar_documentos_vinculados.php?DIGITAL_PAI=" + $('#ID_DETALHAR_DOCUMENTO').val(),
            oLanguage: {
                sProcessing: "Carregando...",
                sLengthMenu: "_MENU_ por página",
                sZeroRecords: "Nenhum documento encontrado.",
                sInfo: "_START_ a _END_ de _TOTAL_ documentos",
                sInfoEmpty: "Não foi possível localizar documentos com o parametros informados!",
                sInfoFiltered: "",
                sInfoPostFix: "",
                sSearch: "Pesquisar:",
                oPaginate: {
                    sFirst: "Primeiro",
                    sPrevious: "Anterior",
                    sNext: "Próximo",
                    sLast: "Ultimo"
                }
            },
            fnServerData: function(sSource, aoData, fnCallback) {
                $.getJSON(sSource, aoData, function(json) {
                    fnCallback(json);
                    if (json.iTotalRecords == 0) {
                        jquery_datatable_complementa_mensagem_vazia('TabelaDocumentosDemanda');
                    }
                });
            },
            fnRowCallback: function(nRow, aData, iDisplayIndex) {
                total_documentos = (iDisplayIndex + 1);

                if (aData[8] == unidadeCorrente) {
	                $(nRow).find('td').addClass('linhaUnidade');
	                console.log(nRow);
                }
                
//                $('td:eq(0)', nRow).html('<input type="checkbox" id="DIGITAL[' + iDisplayIndex + ']" value="' + aData[3] + '">');
                var $line = $('td:eq(6)', nRow);

                $('td:eq(0)', nRow).html('<a href="#" class="link_historico" demanda="' + aData[0] + '">' + aData[0] + '</a>');

                if (aData[6] == '1') {
                    $('td:eq(6)', nRow).html('');

<?php
// verifica a existencia da permissao para detalhar documentos
if (AclFactory::checaPermissao(Controlador::getInstance()->acl, Controlador::getInstance()->usuario, DaoRecurso::getRecursoById(310121))) {
    ?>
                        // Visuzalizar prazos da demanda...

                        $("<img/>", {
                            src: 'imagens/fam/page_white_find.png',
                            title: 'Visualizar o histórico de prazos da demanda',
                            'class': 'botao30 botao-historico-prazo-demanda',
                            width: 20,
                            height: 20,
                            demanda: aData[0],
                        }).bind("click", function() {
                            popup('historico_prazos_demandas.php?digital=' + aData[0] + '&pai=' + $('#DIGITAL_DETALHAR_DOCUMENTO').val(), function() {
                            });
                        }).appendTo($line);
    <?php
}
?>

<?php
// verifica a existencia da permissao para detalhar documentos
if (AclFactory::checaPermissao(Controlador::getInstance()->acl, Controlador::getInstance()->usuario, DaoRecurso::getRecursoById(310118))) {
    ?>
                        // Desassociar
						if (aData[8] == unidadeCorrente) { 
	                        $("<img/>", {
	                            src: 'imagens/fam/delete.png',
	                            title: 'Desassociar Demanda',
	                            'class': 'botao30 botao-remover-associacao',
	                            width: 20,
	                            height: 20,
	                            demanda: aData[0],
	                        }).bind("click", function() {
	                            jquery_desassociar_documento($('#DIGITAL_DETALHAR_DOCUMENTO').val(), aData[0]);
	                        }).appendTo($line);
						}
    <?php
}
?>

                    $("<img/>", {
                        src: 'imagens/visualizar.png',
                        title: 'Visualizar detalhes da demanda.',
                        'class': 'botao30 botao-visualizar-detalhes-demanda',
                        width: 20,
                        height: 20,
                        demanda: aData[0],
                    }).appendTo($line);
                }
                $('td:eq(7)', nRow).html('');
                $('td:eq(7)', nRow).attr('style', 'display: none;');

                if (aData[8] == unidadeCorrente) {
	                $("<img/>", {
	                    src: 'imagens/novo_prazo.png',
	                    title: 'Adicionar Novo Prazo para esta Demanda. Só poderá ser feito uma vez. Após adicionado o prazo, ele deverá ser acompanhado pelo link \'Prazos\'',
	                    'class': 'botao30 botao-novo-prazo',
	                    width: 20,
	                    height: 20,
	                    demanda: aData[0],
	                }).bind("click", function() {
	                    //jquery_desassociar_documento($('#DIGITAL_DETALHAR_DOCUMENTO').val(), aData[0]);
	                }).appendTo($line);
                }
                
                if (aData[2] != '') {
                    $('td:eq(2)', nRow).html(jquery_toggle(aData[2], 100));
                }
                if (aData[1] != '') {
                    $('td:eq(1)', nRow).html(jquery_toggle(aData[1], 100));
                }

                /* Converter formato Date para String (dd/mm/aaaa) */
                $('td:eq(5)', nRow).html(convertDateToString(aData[5]));


                $("</div>").appendTo($line);
                return nRow;
            },
            fnDrawCallback: function(oSettings, nRow) {
            },
            aoColumnDefs: [
                {bSortable: false, aTargets: [0]},
                {bVisible: false, aTargets: [8] }
            ]
        });

    });

    /**
     * @@description Função de controle do toggle de um texto.
     * @au@author Bruno Pedreira
     * @param string texto
     * @param int cortaTexto
     * @returns Toggle da string passada
     *
     **/
    function jquery_toggle(texto, cortaTexto) {
        var spanDemandaAbre = '<span class="toggleDemanda">';
        var spanTextoOff = '<span class="toggleDemandaOff">';
        var spanUnion = '<span class="toggleUnion">...</span>';
        var spanFecha = '</span>';

        if (texto.length > cortaTexto)
        {
            return (spanDemandaAbre + texto.slice(0, cortaTexto) + spanFecha + spanUnion + spanTextoOff + texto.slice(cortaTexto, texto.length) + spanFecha);
        } else {
            return spanDemandaAbre + texto + spanFecha;
        }
    }

    function jquery_desassociar_documento(documentoPai, documentoFilho) {

        if (confirm('Confirma desvinculação da demanda ' + documentoFilho + '?')) {
            $.post("modelos/documentos/vinculacao.php", {
                acao: 'desvincular',
                pai: documentoPai,
                filho: documentoFilho,
                vinculacao: 3,
            }, function(data) {
                try {
                    if (data.success == 'true') {
                        $('#div_grid_demandas_cadastradas').load('grid_demanda_cadastrada.php?DIGITAL_PAI=' + $('#DIGITAL_DETALHAR_DOCUMENTO').val()).show();
                    } else {
                        alert(data.error);
                    }
                } catch (e) {
                    alert('Ocorreu um erro ao tentar desassociar o documento!\n[' + e + ']');
                }
            }, "json");
        }
    }


</script>
<style type="text/css">

    .toggleDemanda, toggleUnion{
        visibility: visible;
    }

    .toggleDemandaOff{
        display: none;

    }

	.linhaUnidade {
		background-color: #91C891;
	}
    
</style>

<table class="display" border="0" id="TabelaDocumentosDemanda">
    <thead>
        <tr>
            <th class="style13 column-digital" style="width: 70px;">Numero</th>
            <th class="style13 column-digital" style="width: 70px;">Demanda</th>
            <th class="style13 column-encaminhamento" style="width: 300px;">Resposta</th> 
            <th class="style13 column-numero">Movimento/Interessado</th>
            <th class="style13 column-assunto">Órgão</th>
            <th class="style13 column-numero">Prazo</th>
            <th class="style13 column-tipo">Opções</th>
            <th style="display: none;"></th>
            <th style="display: none;"></th>
        </tr>
    </thead>
</table>
