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

$controller = Controlador::getInstance();
$auth = $controller->usuario;

$file = 'detalhar_processos.php';
$name_recurso = str_replace('.', '_', $file);
if (!($controller->cache->test('recurso_' . $name_recurso))) {
    $recurso = DaoRecurso::getRecursoByUrl($file);
    if (isset($recurso->id)) {
        $controller->cache->save($recurso, 'recurso_' . $name_recurso, array('recurso_' . $recurso->id, 'paginas'));
    } else {
        $recurso = null;
    }
} else {
    $recurso = $controller->cache->load('recurso_' . $name_recurso);
}

$controller->setContexto(null);
$botoes = Util::getMenus($auth, $recurso, $controller->acl);
foreach ($recurso->dependencias as $arquivo) {
    include('interfaces/' . $arquivo);
}
?>
<html>
    <head>
        <style type="text/css">
            #notificacao-prazo-detalhar-processo{
                vertical-align: middle;
                margin-left: 100px;
                position: absolute;
                margin-top: 7px;    
                width: 270px;
                height: 14px;
                font-size: 10px;
                text-align: right;
                border: 0px solid #000;
            }

            #DATA_PRAZO_DETALHAR_PROCESSO{
                padding-left: 25px;
            }

            #STATUS_PRAZO_DETALHAR_PROCESSO{
                vertical-align: middle;
                margin-left: -395px;
                position: absolute;
                margin-top: 5px;    
                width: 20px;
                height: 20px;
            }

            .aux-combo-cpf{
                position: absolute;
                right: 18px;
                bottom: 8px;
            }
            .menu-detalhar-processos{
                position: absolute;
                bottom: 15px;
                left: 25%;
            }
        </style>

        <script type="text/javascript" src="javascripts/lista_modelo_termos.js"></script>
        <script type="text/javascript" src="javascripts/form_detalhar_processos.js"></script>

    </head>
    <body>
        <!--Formulario-->
        <div class="div-form-dialog" id="div-form-detalhar-processos">

            <div class="row">
                <label class="label">*NUMERO PROCESSO:</label>
                <span class="conteudo">
                    <input type="hidden" id="NUMERO_DETALHAR_PROCESSO" disabled maxlength="20">
                    <input type="text" id="FAKE_NUMERO_DETALHAR_PROCESSO" disabled maxlength="20">
                </span>
            </div>

            <div class="row">
                <label class="label">*ASSUNTO:</label>
                <span class="conteudo">
                    <select class='FUNDOCAIXA1' id='ASSUNTO_DETALHAR_PROCESSO'></select>
                </span>
                <img title="Filtrar" class="botao-auxiliar-fix-combobox" id="botao-filtro-assunto-detalhar-processo" src="imagens/fam/application_edit.png">
            </div>

            <div class="row">
                <label class="label">ASSUNTO COMPLEMENTAR:</label>
                <span class="conteudo">
                    <input type="text" id="ASSUNTO_COMPLEMENTAR_DETALHAR_PROCESSO" maxlength="250" onKeyUp="DigitaLetraSeguro(this)">
                </span>
            </div>

            <div class="row">
                <label class="label">*INTERESSADO:</label>
                <span class="conteudo">
                    <select id="INTERESSADO_DETALHAR_PROCESSO"></select>
                </span>
                <img title="Filtrar" class="botao-auxiliar-fix-combobox" id="botao-filtro-interessado-detalhar-processo" src="imagens/fam/application_edit.png">
            </div>

            <div class="row">
                <label class="label">*ORIGEM:</label>
                <span class="conteudo">
                    <select id="ORIGEM_DETALHAR_PROCESSO"></select>
                </span>
                <img title="Filtrar" class="botao-auxiliar-fix-combobox" id="botao-filtro-origem-detalhar-processo" src="imagens/fam/application_edit.png">
            </div>

            <div class="row">
                <label class="label">*DATA DA AUTUACAO:</label>
                <span class="conteudo">
                    <input type="text" id="DATA_AUTUACAO_DETALHAR_PROCESSO" maxlength="10" readonly="true">
                </span>
            </div>

            <div class="row">
                <label class="label">DATA DO PRAZO:</label>
                <span class="conteudo">
                    <span id="notificacao-prazo-detalhar-processo" class="notificacao-prazo-verde"></span>
                    <input type="text" id="DATA_PRAZO_DETALHAR_PROCESSO" maxlength="10" readonly="true">
                </span>  
                <img title="Limpar" class="botao-auxiliar" src="imagens/fam/delete.png" onClick="limparCampoData('DATA_PRAZO_DETALHAR_PROCESSO');">
                <input type="checkbox" id="STATUS_PRAZO_DETALHAR_PROCESSO" title="Ativar ou Desativar prazo geral processo.">
            </div>

            <div class="menu-detalhar-processos">
                <?php Util::montaMenus($botoes, array('class' => 'botao32')); ?>
            </div>

        </div>

        <!-- Filtros -->
        <div id="box-filtro-origem-detalhar-processo" class="box-filtro">
            <div class="row">
                <label>*Tipo de Origem:</label>
                <div class="conteudo">
                    <select id="TIPO_ORIGEM_DETALHAR_PROCESSO" class="FUNDOCAIXA1">
                        <option value="IN">Processo Interno</option>
                        <option value="EX">Processo Externo</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <label>*Origem:</label>
                <div class="conteudo">
                    <input type="text" id="FILTRO_ORIGEM_DETALHAR_PROCESSO" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
                </div>
            </div>
        </div>

        <div id="box-filtro-assunto-detalhar-processo" class="box-filtro">
            <div class="row">
                <label>*Assunto:</label>
                <div class="conteudo">
                    <input type="text" id="FILTRO_ASSUNTO_DETALHAR_PROCESSO" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
                </div>
            </div>
        </div>

        <div id="box-filtro-interessado-detalhar-processo" class="box-filtro">
            <div class="row">
                <label>*Interessado:</label>
                <div class="conteudo">
                    <input type="text" id="FILTRO_INTERESSADO_DETALHAR_PROCESSO" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
                </div>
            </div>
            <div class="row">
                <label>*CPF ou CNPJ:</label>
                <div class="conteudo">
                    <select class="FUNDOCAIXA1" id="combo_cpf_interessado_detalhar_processo">
                        <option selected value="cpf">CPF</option>
                        <option value="cnpj">CNPJ</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <label id="cpf_ou_cnpj_label_detalhar">CPF:</label>
                <div class="conteudo">
                    <input type="text" id="FILTRO_CNPJ_INTERESSADO_DETALHAR_PROCESSO" class="FUNDOCAIXA1" maxlength="18">
                    <input type="text" id="FILTRO_CPF_INTERESSADO_DETALHAR_PROCESSO" class="FUNDOCAIXA1" maxlength="14">
                </div>
            </div>
        </div>
    </body>
</html>
