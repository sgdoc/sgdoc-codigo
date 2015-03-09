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

if (isset($_POST['DIRETORIA'])):

    if ($auth->ID_UNIDADE != $_POST['DIRETORIA']) {
        // Diretoria selecionada é diferente da original do usuário, houve troca
        $auth->ID_UNIDADE_ORIGINAL = $auth->ID_UNIDADE;
        $auth->TROCOU = true;
    } else {
        $auth->ID_UNIDADE_ORIGINAL = $auth->ID_UNIDADE;
        unset($auth->TROCOU);
    }

    $auth->ID_UNIDADE = $_POST['DIRETORIA'];
    $auth->DIRETORIA = current(CFModelUnidade::factory()->find($_POST['DIRETORIA']))->NOME;

    Zend_Auth::getInstance()->getStorage()->write($auth);
    $controller->cache->remove('acl_' . $auth->ID);
    $controller->cache->clean('matchingAnyTag', array('acl_usuario_' . $auth->ID));

    header('Location: sistemas.php');
else:
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
                        height: 150
                    });
                });
            </script>
        </head>
        <body>
            <!--Formulario-->
            <p class="style13" valign="top" align="left">
                <?php print "Olá, {$auth->NOME} - {$auth->DIRETORIA}"; ?>
            </p>
            <div class="div-form-dialog" id="box-listagem-unidades">
                <div class="row">
                    <label class="label">*FILTRO (Resultado limitado a 100 registros):</label>
                    <span class="conteudo">
                        <input class="FUNDOCAIXA1" type="text" id="filtro-listagem-unidades">
                        <input type="hidden" id="tipo-listagem-unidades" value="administrador-mudar-unidade">
                    </span>
                </div>
                <div class="row">
                    <label class="label">*UNIDADES:</label>
                    <span class="conteudo">
                        <form id="form-listagem-unidades" action="administrador_selecionar_unidade.php" method="post">
                            <select class='FUNDOCAIXA1' id='combo-listagem-unidades' name='DIRETORIA'></select>
                        </form>
                    </span>
                </div>

            </div>
            <span class="style13 rodape"><?php print(__RODAPE__); ?></span>
        </body>
    </html>
<?php endif; ?>