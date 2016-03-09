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

include(__BASE_PATH__ . '/extensoes/pr_snas/1.8/interfaces/form_novo_prazo.php');

?>

<script type="text/javascript">

    var oTableDocumentosDemanda;
    var toggleDemanda = [];
	var unidadeCorrente = "<?php echo Controlador::getInstance()->usuario->ID_UNIDADE; ?>";
	var colUnidOrigem = 5;
	var colUnidDestino = 6;
	var colData = 7;
	var colOpcoes = 8;
	var colStatus = 9;

    $(document).ready(function() {
    	/* Colunas da dataTable que não devem aparecer */
    	var arrColInvisiveis = [9, 10, 11];
    	/* trata as colunas dos órgão, ocultando ou exibindo, dependendo da opção de filtro */
    	if ($("#selFiltroOrigemDemanda").val() != 'TD') {
        	if ($("#selFiltroOrigemDemanda").val() == 'PU') {
            	arrColInvisiveis.push(colUnidDestino);
        	} else {
        		arrColInvisiveis.push(colUnidOrigem);
        	}
        }
        
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
            var id_prazo_pai = $(this).attr('id_prazo_pai');
            e.preventDefault();

            $('#nu_proc_dig_ref').val(demanda);
            $('#hdnIdPrazoPai').val(id_prazo_pai);
            $('#box-novo-prazo').dialog('open');
        });

        $('.link_historico').die('click').live('click', function(e) {
            var demanda = $(this).attr('demanda');
            e.preventDefault();

            popup('historico_tramite_documentos.php?digital=' + demanda, function() {

            });
        });

        /*DataTable*/
        oTableDocumentosDemanda = $('#TabelaDocumentosDemanda').dataTable({
            sDom: '<"H"<"divEncaminharPrazos">fr>t<"F"lip>',
            bLengthChange: true,
            bStateSave: false,
            bPaginate: true,
            bProcessing: false,
            bServerSide: true,
            bJQueryUI: true,
            bAutoWidth: false,
            sPaginationType: "full_numbers",
            sAjaxSource: "modelos/documentos/listar_documentos_vinculados.php?digital_doc=" + $('#DIGITAL_DETALHAR_DOCUMENTO').val() + 
            				"&origem=" + $("#selFiltroOrigemDemanda").val() +
            				"&status=" + $("#selFiltroStatusDemanda").val(), 
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

            	var prazoDaUnidade = (aData[10] == unidadeCorrente);
            	
                if ($("#selFiltroOrigemDemanda").val() != 'TD') {
                    /* se não for exibir todos, irá excluir uma coluna, então ajusta-se o índice das colunas abaixo */
                	colData = 6;
                	colOpcoes = 7;
                }
            	
                /* checkbox para seleção */
                $('td:eq(0)', nRow).html('');
                if (prazoDaUnidade) {
                	$('td:eq(0)', nRow).html('<input type="checkbox" idPrazo="' + aData[0] + '" dataPrazo="' + aData[7] + '" class="chkPrazo" />');
                }
                
                $('td:eq(1)', nRow).html('<a href="#" class="link_historico" demanda="' + aData[1] + '">' + aData[1] + '</a>');

                if (aData[2] != '') {
                    $('td:eq(2)', nRow).html(jquery_toggle(aData[2], 100));
                }
                
                if (aData[3] != '') {
                    $('td:eq(3)', nRow).html(jquery_toggle(aData[3], 100));
                }
                
                /* Converter formato Date para String (dd/mm/aaaa) */
                $('td:eq('+colData+')', nRow).html(convertDateToString(aData[7]));
                
                /* coluna de opções */
                var $line = $('td:eq('+colOpcoes+')', nRow);
                $('td:eq('+colOpcoes+')', nRow).html('');

<?php
// verifica a existencia da permissao para detalhar documentos
if (AclFactory::checaPermissao(Controlador::getInstance()->acl, Controlador::getInstance()->usuario, DaoRecurso::getRecursoById(310121))) {
?>
                /* Ver historico */
				$("<img/>", {
					src: 'imagens/fam/page_white_find.png',
                    title: 'Visualizar o histórico de prazos da demanda',
                    'class': 'botao30 botao-historico-prazo-demanda',
                    width: 20,
                    height: 20,
                    demanda: aData[1],
	            }).bind("click", function() {
						popup('historico_prazos_demandas.php?digital=' + aData[1] + '&pai=' + $('#DIGITAL_DETALHAR_DOCUMENTO').val(), function() { });
                }).appendTo($line);
<?php
}
// verifica a existencia da permissao para detalhar documentos
if (AclFactory::checaPermissao(Controlador::getInstance()->acl, Controlador::getInstance()->usuario, DaoRecurso::getRecursoById(310118))) {
?>
				/* Desassociar */
				if (prazoDaUnidade) { 
	            	$("<img/>", {
	                	src: 'imagens/fam/delete.png',
	                    title: 'Desassociar Demanda',
	                    'class': 'botao30 botao-remover-associacao',
	                    width: 20,
	                    height: 20,
	                    demanda: aData[1],
	                }).bind("click", function() {
	                	jquery_desassociar_documento($('#DIGITAL_DETALHAR_DOCUMENTO').val(), aData[1]);
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
                    demanda: aData[1],
                }).appendTo($line);
                
                if (prazoDaUnidade) {
	                $("<img/>", {
	                    src: 'imagens/novo_prazo.png',
	                    title: 'Adicionar Novo Prazo para esta Demanda. Só poderá ser feito uma vez. Após adicionado o prazo, ele deverá ser acompanhado pelo link \'Prazos\'',
	                    'class': 'botao30 botao-novo-prazo',
	                    width: 20,
	                    height: 20,
	                    demanda: aData[1],
	                    id_prazo_pai: aData[0],
	                }).bind("click", function() {

	                }).appendTo($line);

	                if (aData[colStatus] != 'RP') {
		                $("<img/>", {
		                    src: 'imagens/responder_prazo.png',
		                    title: 'Responder',
		                    'class': 'botao30',
		                    width: 20,
		                    height: 20,
		                }).bind("click", function() {
		                    responderPrazo(aData[0], 'area_trabalho');
		                }).appendTo($line);
                	}
                }
                
	            if (aData[colStatus] == 'RP') {
	            	$("<img/>", {
	                    src: 'imagens/alterar.png',
	                    title: 'Exibir Resposta',
	                    'class': 'botao30',
	                    width: 20,
	                    height: 20,
	                }).bind("click", function() {
	                	responderPrazo(aData[0], 'area_trabalho');
	                }).appendTo($line);
				}
				
                $("</div>").appendTo($line);
                return nRow;
            },
            aoColumnDefs: [
                {bSortable: false, aTargets: [0, 8, 9, 10, 11]},
                {bVisible: false, aTargets: arrColInvisiveis }
            ]
        }); /* Fim dataTable */

        var txtHtml = 	'<button title="Enviar demandas agrupadas" id="btnEncaminharPrazos" class="ui-button ui-widget ui-state-default ui-corner-all" style="margin-right:10px;">'+
            				'<img src="imagens/novo_prazo.png" id="imgEncaminhar" /> Encaminhar'+
            			'</button>';
       	$("div.divEncaminharPrazos").html(txtHtml);

    }); /* fim document.ready */

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
                        $('#div_grid_demandas_cadastradas').load('grid_demanda_cadastrada.php').show();
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

	#TabelaDocumentosDemanda_wrapper {
		min-height: 0px;
	}
	
	#TabelaDocumentosDemanda_filter {
		width: 380px;
	}
	
	#TabelaDocumentosDemanda_filter input {
		width: 300px;
	}
	
    .toggleDemanda, toggleUnion{
        visibility: visible;
    }

    .toggleDemandaOff{
        display: none;
    }

    .objOculto {
        display: none;
    }
    
	.linhaUnidade {
		background-color: #91C891;
	}
    
    .divEncaminharPrazos {
    	float: left;
    }
    
    #btnEncaminharPrazos {
    	padding: 5px;
    }
    
    #imgEncaminhar {
    	width: 20px;
    	height: 20px;
    	border: none;
    	vertical-align: middle;
    }
    
</style>

<table class="display" id="TabelaDocumentosDemanda">
    <thead>
        <tr>
        	<th class="style13 column-checkbox">#</th>
            <th class="style13 column-digital">Número</th>
            <th class="style13 column-assunto">Demanda</th>
            <th class="style13 column-assunto">Resposta</th> 
            <th class="style13 column-assunto">Movimento/Interessado</th>
            <th class="style13 column-assunto">Órgão Origem</th>
            <th class="style13 column-assunto">Órgão Destino</th>
            <th class="style13 column-data">Prazo</th>
            <th class="style13 column-tipo">Opções</th>
            <!-- ESTAS COLUNAS ESTÃO OCULTAS -->
            <th>STATUS_PRAZO</th>
            <th>ID_UNIDADE_ORIGEM</th>
            <th>ID_UNIDADE_DESTINO</th>
        </tr>
    </thead>
</table>
