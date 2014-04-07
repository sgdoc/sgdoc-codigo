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

/**
 * Javascripts
 */
$js = array(
    "cache/main.js"
);

if (Zend_Auth::getInstance()->hasIdentity()) {
    $js[] = "javascripts/variaveis.php";
}

$allowScriptsMinifierJs = array(
    "javascripts/jquery-1.4.2.min.js",
    "plugins/jqueryui/js/jquery-ui-1.8.20.custom.min.js",
    "plugins/jqueryui/ui/i18n/jquery.ui.datepicker-pt-BR.js",
    "plugins/jqueryui/external/jquery.bgiframe-2.1.1.js",
    "javascripts/util.js",
    "javascripts/cpf.js",
    "javascripts/data.js",
    "javascripts/email.js",
    "javascripts/funcoes.js",
    "javascripts/numero.js",
    "javascripts/interface.js",
    "javascripts/visualizador.js",
    "javascripts/cnpj.js",
    "javascripts/processos.js",
    "javascripts/documentos.js",
    "javascripts/jquery.autocomplete.js",
    "javascripts/jquery.timer.plugin.js",
    "javascripts/jquery.autocomplete-online.js",
    "javascripts/jquery.maskedinput-1.3.min.js",
    "javascripts/jquery.combobox.js",
    "javascripts/jquery.validate.js",
    "javascripts/popup.js",
);

/**
 * Css
 */
$css = array(
    "cache/main.css"
);

$allowScriptsMinifierCss = array(
    "css/CSS_001.css",
    "css/CSS_002.css",
    "css/default.css",
    "css/style.css",
    "css/jquery.autocomplete.css",
    "css/lista_modelo_termos.css",
    "css/jquery-ui-1.8.13.custom.css"
);

/**
 * Printar
 */
if (Util::renovateCacheContentStatics($allowScriptsMinifierCss, 'cache/main.css')) {
    Util::generateCacheContentsCssStatics($allowScriptsMinifierCss, 'cache/main.css');
}

if (Util::renovateCacheContentStatics($allowScriptsMinifierJs, 'cache/main.js')) {
    Util::generateCacheContentsJsStatics($allowScriptsMinifierJs, 'cache/main.js');
}

print(Util::autoLoadJavascripts($js));
print(Util::autoLoadCss($css));
?>

<?php if (!isset($DONT_RENDER_BACKGROUND)): ?>
    <html>
        <head>
            <title>SGDoc</title>
            <style type="text/css">
                body {
                    background-color: #0E1800;
                    background-image: url('imagens/<?php print(__BACKGROUND__); ?>');
                    background-position: bottom right;
                    background-repeat: no-repeat;
                }
                .ui-progressbar-value { 
                    background-image: url('imagens/jqueryui_progressbar.gif'); 
                }
                #progressbar-default{
                    width: 300px;
                    height: 22px; 
                }
                #progressbar #progressbar-default-container{
                    position: absolute;
                    margin: 20px;
                    right: 0px;
                    bottom: 0px;
                }
                #progressbar{
                    display: none;
                    position: fixed;
                    top: 0px;
                    bottom: 0px;
                    right: 0px;
                    left: 0px;   
                    z-index: 999999;
                    width: 100%;
                    height: 100%;
                    background-color: rgba(0,0,0,0.9);
                    border: 0px solid white;
                }
            </style>
        </head>
        <body>
            <div id="visualizador_popup" style="display:none">
                <input type="hidden" id="">
                <input class="exitButtonPopup" id="btnClosePopup" type="image" src="imagens/cancelar.png" title="Fechar" onclick="stop_visualizador_processo();">
                <iframe name="frame_visualizador_popup" id="frame_visualizador_popup"></iframe>
            </div>
            <div id="visualizador_imagem_processo" style="display:none">
                <input type="hidden" id="aux_numero_processo">
                <input class="exitButtonProcesso" type="image" src="imagens/cancelar.png" title="Fechar" onclick="stop_visualizador_processo();">
                <iframe name="frame_visualizador_imagem_processo" id="frame_visualizador_imagem_processo"></iframe>
            </div>
            <div id="visualizador_imagem" style="display:none" >
                <input type="hidden" id="aux_digital">
                <input class="exitButton" type="image" src="imagens/cancelar.png" title="Fechar" onclick="stop_visualizador_documento();">
                <iframe name="frame_visualizador_imagem" id="frame_visualizador_imagem"></iframe>
            </div>
            <div id="progressbar">
                <div id="progressbar-default-container"> 
                    <p>Aguarde, processando...</p>
                    <div id="progressbar-default" class="ui-progressbar-value"></div>
                </div>
            </div>
        </body>
    </html>
<?php endif; ?>