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

    var oTableUnidades;

    $(document).ready(function() {
        /*DataTable*/
        oTableUnidades = $('#tabela_unidades').dataTable({
            aLengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            bStateSave: false,
            bPaginate: true,
            bProcessing: true,
            bServerSide: true,
            bJQueryUI: true,
            sPaginationType: "full_numbers",
            sAjaxSource: "modelos/unidades/listar_unidades.php",
            "aoColumns": [
                { "sWidth": "7px" },
                { "sWidth": "275px" },
                { "sWidth": "70px" },
                { "sWidth": "100px" },
                { "sWidth": "35px" },
                { "sWidth": "100px" },
                { "sWidth": "40px" },
                { "sWidth": "80px" },
                { "sWidth": "275px" },
                { "sWidth": "60px" }
            ],
            aoColumnDefs: [{ bSortable: false, aTargets: [8] }],
            oLanguage: {
                sProcessing: "Carregando...",
                sLengthMenu: "_MENU_ por página",
                sZeroRecords: "Nenhum unidade encontrado.",
                sInfo: "_START_ a _END_ de _TOTAL_ unidades.",
                sInfoEmpty: "Nao foi possivel localizar unidades com os parametros informados!",
                sInfoFiltered: "(Total _MAX_ unidades)",
                sInfoPostFix: "",
                sSearch: "Pesquisar:",
                oPaginate: {
                    sFirst:    "Primeiro",
                    sPrevious: "Anterior",
                    sNext:     "Próximo",
                    sLast:     "Ultimo"
                }
            },
            fnServerData: function ( sSource, aoData, fnCallback ) {
                $.getJSON( sSource, aoData, function (json) {
                    fnCallback(json);
                } );
            },
            fnRowCallback: function(nRow, aData, iDisplayIndex) {
                nRow.setAttribute( 'title', aData[1] );

                var $line = $('td:eq(9)', nRow);
                $line.html('<div title="">');

                var $situacao = $('td:eq(7)', nRow);
                $situacao.html('');

                /*Protocolizadora*/
                if(aData[4]==1){
                    $('td:eq(4)', nRow).html('Sim');
                }else{
                    $('td:eq(4)', nRow).html('Nao');
                }
                /*Codigo*/
                if(aData[5]<5){
                    $('td:eq(5)', nRow).html('Em Branco');
                }

<?php
// verifica a existencia da permissao para alterar regras de trâmite
if (AclFactory::checaPermissao(Controlador::getInstance()->acl, Controlador::getInstance()->usuario, DaoRecurso::getRecursoById(11403101))) {
?>
                // Alterar regras de trâmite
                $("<img/>", {
                    src: 'imagens/regras-tramite.png',
                    title: 'Manter tramite',
                    'class': 'botao32'
                }).bind( "click", function(){
                    jquery_detalhar_regras_tramite(aData[0]);
                }).appendTo($line);

<?php
}
// verifica a existencia da permissao para adicionar uma unidade
if (AclFactory::checaPermissao(Controlador::getInstance()->acl, Controlador::getInstance()->usuario, DaoRecurso::getRecursoById(1140301))) {
?>
                // Alterar
                $("<img/>", {
                    src: 'imagens/alterar.png',
                    title: 'Editar',
                    'class': 'botao32'
                }).bind( "click", function(){
                    jquery_detalhar_unidades(aData[0]);
                }).appendTo($line);

<?php
}
// verifica a existencia da permissao para alterar privilégios de unidade
if (AclFactory::checaPermissao(Controlador::getInstance()->acl, Controlador::getInstance()->usuario, DaoRecurso::getRecursoById(11403102))) {
?>
                // Alterar
                $("<img/>", {
                    src: 'imagens/login.png',
                    title: 'Privilégio',
                    'class': 'botao32'
                }).bind( "click", function() {
                    aclPrivilegio.jquery_acl_privilegio(aData[0], aData[1]);
                }).appendTo($line);

<?php
}
?>

                $("</div>").appendTo($line);
                
                $situacao.append('<strong>Ativo</strong>');

                var ativo = $("<input/>", {
                    type: 'checkbox',
                    title: 'Ativar/Desativar'
                }).bind( "change", function(){
                    jquery_alterar_status_unidade(aData[0], (this.checked ? 1 : 0));
                }).appendTo($situacao);

                if(aData[7] == 1){
                    ativo.attr('checked','checked');
                }

                return nRow;
            }
        });
    });

    function jquery_alterar_status_unidade(id, status){
        $('#progressbar').show();
        $.post("modelos/unidades/unidades.php",
        {
            acao: 'alterar-status',
            id: id,
            status: parseInt(status)
        },
        function(data){
            $('#progressbar').hide();
            if(data.success != 'true'){
                alert('Ocorreu um erro ao tentar alterar o status da unidade!');
            }
        },"json");
    }

    function mascaras(){
        $('#TELEFONE_DETALHAR_USUARIO').unmask();
        $('#TELEFONE_DETALHAR_USUARIO').focusout(function(){
            var phone, element;
            element = $(this);
            element.unmask();
            phone = element.val().replace(/\D/g, '');
            if(phone.length > 10) {
                element.mask("(99) 99999-999?9");
            } else {
                element.mask("(99) 9999-9999?9");
            }
        }).trigger('focusout');
        $('#CPF_DETALHAR_USUARIO').unmask();
        $('#CPF_DETALHAR_USUARIO').mask('999.999.999-99');
        $('#TELEFONE_PESQUISAR').unmask();
        $('#TELEFONE_PESQUISAR').mask('(99) 9999-9999');
        $('#CPF_PESQUISAR').unmask();
        $('#CPF_PESQUISAR').mask('999.999.999-99');
    }

    function jquery_acl_privilegio(id) {
        window.location.href = 'acl_privilegio_usuario.php?usuario='+id;
    }

</script>      

    <table class="display" border="0" id="tabela_unidades">
        <thead>
            <tr>
                <th class="style13">#</th>
                <th class="style13">Nome</th>
                <th class="style13">Sigla</th>
                <th class="style13">Tipo</th>
                <th class="style13">Protocolizadora</th>
                <th class="style13">Codigo</th>
                <th class="style13">UF</th>
                <th class="style13">Situação</th>
                <th class="style13">UOP</th>
                <th class="style13">Opções</th>
            </tr>
        </thead>
    </table>
