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

include("function/auto_load_statics.php");
?>
<html>
    <head>
        <script type='text/javascript' src='javascripts/jquery-1.4.2.min.js?1341438996'></script>
        <script type='text/javascript' src='plugins/jqueryui/js/jquery-ui-1.8.7.custom.min.js?1341439049'></script>
        <link   type='text/css'        href='plugins/jqueryui/css/ui-lightness/jquery-ui-1.8.5.custom.css?1341439028' rel='stylesheet' />
        <script type="text/javascript" src="javascripts/jquery.charts.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $.ajax({
                    url: 'modelos/graficos/graficos.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        grafico_por_periodo: 1
                    },
                    success: function(data) {
                        if(data != undefined && data.length > 0) {
                            var dados  = "<table id='tabelaImagensPorPeriodo'><caption>Gráfico dos totais de imagens inseridas nos últimos 7 dias</caption><thead><tr><th></th><th></th></tr></thead><tbody>";
                            for(var i in data) {
                                var dados = dados + eval("<tr><td>"+data[i].data_inclusao+"</td><td>"+data[i].total_imagens+"</td></tr>");
                            }
                            dados = dados + "</tbody></table>";
                            $('#qtdeImagensPorPeriodo').html(dados);
                            if(data.length % 2 == 1) {
                                data.length = data.length - 1;
                            }
                            $("#tabelaImagensPorPeriodo").charts({
                                direction: 'vertical',
                                showgrid: true,
                                labelcolumn: 0,
                                duration: 1000,
                                gridlines: data.length
                            });
                            $('.chartsbar').click(function() {
                                var regexSpan = /.*\<span.*\>(.*)\<\/span\>/g;
                                var string = $.trim($(this).html());
                                var datInclusao = string.replace(regexSpan, "$1");
                                //var dataBrToUS = datInclusao.split('/');
                                //datInclusao = dataBrToUS[2]+'-'+dataBrToUS[1]+'-'+dataBrToUS[0];
                                $('#imagensDiaria').dialog('destroy');
                                $.ajax({
                                    url: 'modelos/graficos.php',
                                    type: 'POST',
                                    dataType: 'json',
                                    data: {
                                        datInclusao: datInclusao,
                                        grafico_diario: 1
                                    },
                                    success: function(data) {
                                        if(data != undefined && data.length > 0) {
                                            var dados = "<table id='tabelaImagensDiaria'><caption></caption><thead><tr><th></th><th></th></tr></thead><tbody>";
                                            for(var i in data) {
                                                var dados = dados + eval("<tr><td>"+data[i].nome_unidade+"</td><td>"+data[i].total_imagens+"</td></tr>");
                                            }
                                            dados = dados + "</tbody></table>";
                                            $('#imagensDiaria').html(dados);
                                            $("#tabelaImagensDiaria").charts({
                                                direction: 'vertical',
                                                showgrid: false,
                                                labelcolumn: 0,
                                                duration: 1000,
                                                gridlines: data.length
                                            });
                                            var comprimentoDialog = 500;
                                            if(data.length > 1) {
                                                comprimentoDialog = data.length * 150;
                                            } else {
                                                comprimentoDialog = 300;
                                            }
                                            $('#imagensDiaria').dialog({
                                                title: "Gráfico dos totais de imagens inseridas no dia "+datInclusao+" por Unidade Organizacional",
                                                autoOpen: true,
                                                resizable: true,
                                                modal: true,
                                                height: 380,
                                                width: comprimentoDialog,
                                                close: function() {
                                                    //$(this).dialog('close');
                                                }
                                            });
                                            $('#imagensDiaria .chartslabel').remove();
                                            $('#imagensDiaria .chartsbar').click(function() {
                                                alert($(this).attr('title'));
                                            });
                                        }
                                    }
                                });
                            });
                        }
                    }
                });
                $('#voltar').click(function() {
                    location.href='sistemas.php';
                });
            });
        </script>
        <style type="text/css">
            .ui-dialog-titlebar, .ui-widget-header {
                background-color: #87A44C !important;
                background-image: url('css/images/ui-bg_gloss-wave_60_799936_500x100.png') !important;
                border: 1px solid #93AF56 !important;
            }
            * {
                font-weight: bold;
                font-size: 12px;
            }
            #imagensDiaria {
                /*padding-left:40px;*/
            }
            #qtdeImagensPorPeriodo h3 {
                color: #ccc;
            }
            #qtdeImagensPorPeriodo {
                color: #ccc;
                padding: 2em;
                margin: 0 auto;
                width: 560px;
            }
            #voltar {
                background-image: url('imagens/menu_principal.png');
                background-size: 32px;
                margin: 10px;
                cursor:pointer;
                width: 32px;
                height: 32px;
            }
            #titulo {
                float: right;
            }
        </style>
    </head>
    <body>
        <div id="titulo">
            <div id="voltar"></div>
        </div>
        <!-- BEGIN gráficos -->
        <div id="graficoImagensPorDocumento">
            <div id="qtdeImagensPorPeriodo"></div>
            <div id="imagensDiaria"></div>
        </div>
        <!-- END gráficos -->
    </body>
</html>