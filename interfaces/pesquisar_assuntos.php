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

<html>
    <head>
        <script type="text/javascript">

            var oTabelaAssuntosDoc;
            var oTabelaAssuntosProc;

            $(document).ready(function() {

                $("#tabs").tabs();
                $("#tabs-pesquisar").tabs();
                $(".cabecalho-caixas").tabs();

                /*Dialog*/
                /*Filtrar Despachos e Comentarios*/
                $('#box-filtrar-assuntos-documento-processo').dialog({
                    title: 'Filtrar pesquisa',
                    autoOpen: false,
                    resizable: false,
                    modal: true,
                    width: 700,
                    close: function(){
                    },
                    buttons: {
                        Pesquisar: function() {
                            jquery_pesquisar_assuntos();
                        }
                    }
                });
                /*Listeners*/
                /*Filtrar*/
                $('#botao-pesquisa-avancada-assuntos').click(function(){
                    $('#box-filtrar-assuntos-documento-processo').dialog('open');
                });

                $('#tipo-operacao').val('DOCUMENTO');

                $('#link-documentos').click(function(){
                    $('#tipo-operacao').val('DOCUMENTO');
                });

                $('#link-processos').click(function(){
                    $('#tipo-operacao').val('PROCESSO');
                });
            });

            /*Funcoes*/
            function jquery_pesquisar_assuntos(){
                if($('#tipo-operacao').val()=='DOCUMENTO') {
                    $.post('modelos/administrador/pesquisar_assuntos.php', {
                        operacao: $('#tipo-operacao').val(),
                        tipo: $('#TIPO_ASSUNTO_PESQUISAR_DOC').val(),
                        ASSUNTO: $('#TEXTO_ASSUNTO_PESQUISAR_DOC').val(),
                        HOMOLOGADO: $('#HOMOLOGADO_ASSUNTO_PESQUISAR_DOC').val(),
                        CORRIGIDO: $('#CORRIGIDO_ASSUNTO_PESQUISAR_DOC').val()
                    },
                    function(data) {
                        if(data.success == 'true') {
                            $("#tabs").tabs('select', 0);
                            oTabelaAssuntosDoc.fnDraw(false);
                            $('#box-filtrar-assuntos-documento-processo').dialog('close');
                        } else {
                            alert('Ocorreu um erro ao tentar efetuar a busca!\['+data.error+']');
                        }
                    }, 'json');
                } else {
                    $.post('modelos/administrador/pesquisar_assuntos.php',{
                        operacao: $('#tipo-operacao').val(),
                        tipo: $('#TIPO_ASSUNTO_PESQUISAR_PROC').val(),
                        ASSUNTO: $('#TEXTO_ASSUNTO_PESQUISAR_PROC').val(),
                        HOMOLOGADO: $('#HOMOLOGADO_ASSUNTO_PESQUISAR_PROC').val(),
                        CORRIGIDO: $('#CORRIGIDO_ASSUNTO_PESQUISAR_PROC').val(),
                        INTERESSADO_OBRIGATORIO: $('#INTERESSADO_ASSUNTO_PESQUISAR_PROC').val()
                    },
                    function(data){
                        if(data.success == 'true') {
                            $("#tabs").tabs('select', 1);
                            oTabelaAssuntosProc.fnDraw(false);
                            $('#box-filtrar-assuntos-documento-processo').dialog('close');
                        } else {
                            alert('Ocorreu um erro ao tentar efetuar a buscar!\['+data.error+']');
                        }
                    }, 'json');
                }
            }
        </script>
    </head>
    <body>
        <div id="box-filtrar-assuntos-documento-processo">
            <div id="tabs-pesquisar">
                <ul>
                    <li><a id="link-documentos" title="Filtrar Assuntos de Documentos" href="#tab-pesquisar-documentos">Filtrar Assuntos de Documentos</a></li>
                    <li><a id="link-processos" title="Filtrar Assuntos de Processos" href="#tab-pesquisar-processos">Filtrar Assuntos de Processos</a></li>
                </ul>
                <div id="tab-pesquisar-documentos" class="div-form-dialog">
                    <div class="row">
                        <input type="hidden" id="tipo-operacao">
                        <label class="label">TIPO DE PESQUISA:</label>
                        <span class="conteudo">
                            <select class="FUNDOCAIXA1" id='TIPO_ASSUNTO_PESQUISAR_DOC'>
                                <option value="EXATA" selected="selected">ASSUNTO EXATO</option>
                                <option value="FRAGMENTO">FRAGMENTO DE ASSUNTO</option>
                            </select>
                        </span>
                    </div>
                    <div class="row">
                        <label class="label">ASSUNTO:</label>
                        <span class="conteudo">
                            <input type="text" class="FUNDOCAIXA1" onkeyup="DigitaLetraSeguro(this)" id='TEXTO_ASSUNTO_PESQUISAR_DOC'>
                        </span>
                    </div>
                    <div class="row">
                        <label class="label">SITUACAO:</label>
                        <span class="conteudo">
                            <select class="FUNDOCAIXA1" id="HOMOLOGADO_ASSUNTO_PESQUISAR_DOC">
                                <option value="" selected="selected"></option>
                                <option value="1">Homologado</option>
                                <option value="0">Não-homologado</option>
                            </select>
                        </span>
                    </div>
                    <div class="row">
                        <label class="label">ASSUNTO REAL:</label>
                        <span class="conteudo">
                            <select class="FUNDOCAIXA1" id="CORRIGIDO_ASSUNTO_PESQUISAR_DOC">
                                <option value="" selected="selected"></option>
                                <option value="1">Corrigido</option>
                                <option value="0">Não-corrigido</option>
                            </select>
                        </span>
                    </div>
                </div>
                <div id="tab-pesquisar-processos" class="div-form-dialog">
                    <div class="row">
                        <label class="label">TIPO DE PESQUISA:</label>
                        <span class="conteudo">
                            <select class="FUNDOCAIXA1" id='TIPO_ASSUNTO_PESQUISAR_PROC'>
                                <option value="EXATA" selected="selected">ASSUNTO EXATO</option>
                                <option value="FRAGMENTO">FRAGMENTO DE ASSUNTO</option>
                            </select>
                        </span>
                    </div>
                    <div class="row">
                        <label class="label">ASSUNTO:</label>
                        <span class="conteudo">
                            <input type="text" class="FUNDOCAIXA1" onkeyup="DigitaLetraSeguro(this)" id='TEXTO_ASSUNTO_PESQUISAR_PROC'>
                        </span>
                    </div>
                    <div class="row">
                        <label class="label">SITUACAO:</label>
                        <span class="conteudo">
                            <select class="FUNDOCAIXA1" id="HOMOLOGADO_ASSUNTO_PESQUISAR_PROC">
                                <option value="" selected="selected"></option>
                                <option value="1">Homologado</option>
                                <option value="0">Não-homologado</option>
                            </select>
                        </span>
                    </div>
                    <div class="row">
                        <label class="label">ASSUNTO REAL:</label>
                        <span class="conteudo">
                            <select class="FUNDOCAIXA1" id="CORRIGIDO_ASSUNTO_PESQUISAR_PROC">
                                <option value="" selected="selected"></option>
                                <option value="1">Corrigido</option>
                                <option value="0">Não-corrigido</option>
                            </select>
                        </span>
                    </div>
                    <div class="row">
                        <label class="label">INTERESSADO:</label>
                        <span class="conteudo">
                            <select class="FUNDOCAIXA1" id="INTERESSADO_ASSUNTO_PESQUISAR_PROC">
                                <option value="" selected="selected"></option>
                                <option value="1">Obrigatório</option>
                                <option value="0">Opcional</option>
                            </select>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
