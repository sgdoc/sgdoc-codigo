<?php
/**
 *
 * Copyright 2011 ICMBio
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
 *
 */
$controller = Controlador::getInstance();
$auth = Zend_Auth::getInstance()->getStorage()->read();
$unidades = CFModelUsuarioUnidade::factory()->retrieveUnitsAvailableByIdUser($controller->usuario->ID);

if (count($unidades) == 0) {
    header('Location: usuario_sem_unidade_vinculada.php');
}

if ($_POST) :

    $unidadesUsuario = CFModelUsuarioUnidade::factory()->findByParam(array('ID_USUARIO' => $auth->ID, 'ID_UNIDADE' => $_POST['UNIDADE']));

    //verificar se a unidade informada esta associada ao usuario logado...
    if (count($unidadesUsuario) == 0) {
        header('Location: denied.php');
    }

    $auth->DIRETORIA = current(CFModelUnidade::factory()->find($_POST['UNIDADE']))->NOME; //chave diretoria nao pode ser alterada...
    $auth->ID_UNIDADE = $_POST['UNIDADE'];
    $auth->ID_UNIDADE_ORIGINAL = $_POST['UNIDADE'];

    Zend_Auth::getInstance()->getStorage()->write($auth);

    $controller->cache->remove('acl_' . $auth->ID);
    $controller->cache->clean('matchingAnyTag', array('acl_usuario_' . $auth->ID));

    header('Location: sistemas.php');
else :
    include("function/auto_load_statics.php");
    ?>

    <!--Dialogo com a listagem de unidades do ICMBio-->
    <html>
        <head>
            <script type="text/javascript">
                $(document).ready(function() {
                    /*Listeners*/
                    /*Bind Dialog Close*/
                    $("#box-listagem-unidades").bind("dialogclose", function(event, ui) {
                        $('#progressbar').show();
                        if ($('#combo-listagem-unidades').val()) {
                            $('#form-listagem-unidades').submit();
                        } else {
                            window.location = 'sistemas.php';
                        }
                        $("#box-listagem-unidades").dialog('open');
                    });

                    $("#filtro-listagem-unidades").autocompleteonline({
                        url: 'modelos/administrador/unidades.php',
                        idComboBox: 'combo-listagem-unidades',
                        idTypeField: 'tipo-listagem-unidades',
                        paramName: "query",
                        paramTypeName: 'tipo',
                        inputClass: "",
                        resultsClass: "acResults",
                        loadingClass: "acLoading",
                        lineSeparator: "\n\n",
                        cellSeparator: "|",
                        minChars: 3,
                        delay: 2000,
                        mustMatch: false,
                        matchCase: false,
                        matchInside: false,
                        matchSubset: false,
                        useCache: false,
                        maxCacheLength: 0,
                        autoFill: false,
                        sortResults: false,
                        onNoMatch: false
                    });
                    $('#box-listagem-unidades').dialog({
                        title: 'Selecione a unidade',
                        autoOpen: true,
                        resizable: false,
                        modal: false,
                        width: 480,
                        height: 100
                    });
                });
            </script>
        </head>
        <body>
            <!--Formulario-->
            <p class="style13" valign="top" align="left">
                <?php print "Olá, {$auth->NOME}."; ?>
            </p>
            <div class="div-form-dialog" id="box-listagem-unidades">
                <div class="row">
                    <label class="label">*UNIDADES DISPONÍVEIS:</label>
                    <span class="conteudo">
                        <form id="form-listagem-unidades" action="usuario_selecionar_unidade.php" method="post">
                            <select class='FUNDOCAIXA1' id='combo-listagem-unidades' name='UNIDADE'>
                                <?php
                                foreach ($unidades as $unidade) {
                                    print("<option value='{$unidade->ID}'>{$unidade->NOME}</option>");
                                }
                                ?>
                            </select>
                        </form>
                    </span>
                </div>
            </div>
            <span class="style13 rodape"><?php print(__RODAPE__); ?></span>
        </body>
    </html>
<?php endif; ?>