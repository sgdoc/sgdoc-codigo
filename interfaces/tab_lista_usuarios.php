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

    var oTableUsuarios = null;
    $(document).ready(function() {
        /*DataTable*/
        oTableUsuarios = $('#tabela_usuarios').dataTable({
            aLengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            bStateSave: false,
            bPaginate: true,
            bProcessing: true,
            bServerSide: true,
            bJQueryUI: true,
            sPaginationType: "full_numbers",
            sAjaxSource: "modelos/administrador/listar_usuarios.php",
            aoColumnDefs: [{bSortable: false, aTargets: [8]}],
            oLanguage: {
                sProcessing: "Carregando...",
                sLengthMenu: "_MENU_ por página",
                sZeroRecords: "Nenhum usuario encontrado.",
                sInfo: "_START_ a _END_ de _TOTAL_ usuarios.",
                sInfoEmpty: "Nao foi possivel localizar usuarios com os parametros informados!",
                sInfoFiltered: "(Total _MAX_ usuarios)",
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
                    listernLoadGrid();
                });
            },
            fnRowCallback: function(nRow, aData, iDisplayIndex) {
                var $line = $('td:eq(8)', nRow);
                $line.html('<div title="">');
                var telefone = soNumero(aData[4]) + '';
                telefone = (telefone.length < 10 ? '61' + telefone : telefone);
                $('td:eq(1)', nRow).html((aData[1] == '' ? '<div></div>' : formatarCPF(aData[1])));
                $('td:eq(4)', nRow).html((aData[4] == '' ? '<div></div>' : formatarTelefoneComDDD(telefone)));
                $('td:eq(5)', nRow).html((aData[5] == '' ? '<div></div>' : aData[5]));
                $('td:eq(6)', nRow).html((aData[6] == '' ? '<div></div>' : aData[6]));
                $('td:eq(7)', nRow).html('Ativo <input type="checkbox" class="chk-status" id="' + aData[0] + '" ' + (aData[7] == 1 ? 'checked="checked"' : '') + '>');
<?php
// verifica a existencia da permissao para adicionar um usuário
if (AclFactory::checaPermissao(Controlador::getInstance()->acl, Controlador::getInstance()->usuario, DaoRecurso::getRecursoById(1140202))) {
    ?>
                    // Alterar
                    $("<img/>", {
                        src: 'imagens/alterar.png',
                        title: 'Detalhar usuário',
                        'class': 'detalhar-usuario botao32',
                        id: aData[0]
                    }).appendTo($line);
    <?php
}
// verifica a existencia da permissao para alterar privilégios de usuário
if (AclFactory::checaPermissao(Controlador::getInstance()->acl, Controlador::getInstance()->usuario, DaoRecurso::getRecursoById(11402101))) {
    ?>
                    // Alterar
                    $("<img/>", {
                        src: 'imagens/login.png',
                        title: 'Alterar privilégios de usuário',
                        'class': 'botao32'
                    }).bind("click", function() {
                        window.location.href = 'acl_privilegio_usuario.php?usuario=' + aData[0];
                    }).appendTo($line);
    <?php
}
?>
                $("</div>").appendTo($line);
                return nRow;
            }
        });
    });
    function listernLoadGrid() {
        /*Detalhar Usuario*/
        $('.detalhar-usuario').click(function() {

            limparFormulario('#FORM_DETALHAR_USUARIO');

            $.post("modelos/usuarios/usuarios.php", {
                acao: 'detalhar',
                id: this.id
            }, function(data) {
                if (data.success === 'true') {

                    $('#container-unidades-usuario').empty();
                    $.each(data.usuario.UNIDADES, function(index) {
                        $('#container-unidades-usuario').append(tmpl.replace('%d', data.usuario.UNIDADES[index].ID).replace('%s', data.usuario.UNIDADES[index].NOME));
                    });

                    var TELEFONE = data.usuario.TELEFONE + '';

                    $('#CPF_DETALHAR_USUARIO').blur(function() {
                        if (!jquery_validar_cpf_cnpj(this.value)) {
                            this.value = '';
                        }
                    });

                    $('#ID_DETALHAR_USUARIO').val((data.usuario.ID !== '' ? data.usuario.ID : 0));
                    $('#USUARIO_DETALHAR_USUARIO').val(data.usuario.USUARIO);
                    $('#NOME_DETALHAR_USUARIO').val(data.usuario.NOME);
                    $('#TELEFONE_DETALHAR_USUARIO').val((TELEFONE.length < 10 ? '61' + data.usuario.TELEFONE : data.usuario.TELEFONE));
                    $('#EMAIL_DETALHAR_USUARIO').val(data.usuario.EMAIL);
                    $('#SKYPE_DETALHAR_USUARIO').val(data.usuario.SKYPE);
                    $('#CPF_DETALHAR_USUARIO').val(data.usuario.CPF);
                    maskFieldsUser();
                    cmdNewUser = false;
                    $('#detalhar_usuario').dialog('open');
                } else {
                    alert('Ocorreu um erro ao tentar detalhar as informacoes do usuário!');
                }
            }, "json");

        });

        $('.chk-status').die('change').live('change', function() {
            $('#progressbar').show();
            $.post("modelos/usuarios/usuarios.php", {
                acao: 'alterar-status',
                id: this.id,
                status: parseInt((this.checked ? 1 : 0))
            }, function(data) {
                $('#progressbar').hide();
                if (data.success !== 'true') {
                    alert('Ocorreu um erro ao tentar alterar o status do usuário!');
                }
            }, "json");
        });
    }

    function maskFieldsUser() {
        $('#TELEFONE_DETALHAR_USUARIO').unmask();
        $('#TELEFONE_DETALHAR_USUARIO').focusout(function() {
            var phone, element;
            element = $(this);
            element.unmask();
            phone = element.val().replace(/\D/g, '');
            if (phone.length > 10) {
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



</script>      

<table class="display" border="0" id="tabela_usuarios">
    <thead>
        <tr>
            <th class="style13">#</th>
            <th class="style13">CPF</th>
            <th class="style13">Usuário</th>
            <th class="style13">Nome</th>
            <th class="style13">Telefone</th>
            <th class="style13">Email</th>
            <th class="style13">Skype</th>
            <th class="style13">Status</th>
            <th class="style13">Opções</th>
        </tr>
    </thead>
</table>