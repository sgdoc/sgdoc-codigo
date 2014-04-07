<?php
$DONT_RENDER_BACKGROUND = true;
try {
    if(isset( $_POST )){
        switch ($_POST['action']) {
            case 'setImageSituation':
                $arrSelSituacaoDigitais = $_REQUEST['selSituacaoDigitais'];
                $arrDigitaisSituacao = array();
                
                $conn = \Controlador::getInstance()->getConnection()->connection;
                
                try{
                    $conn->beginTransaction();
                    foreach ($arrSelSituacaoDigitais as $selSituacaoDigital) {
                        $arrName = explode('_', $selSituacaoDigital['name']);
                        $idDocumentoImagem = $arrName[1];
                        $flgPublico = $selSituacaoDigital['value'];

                        $stmt = $conn->prepare("
                            UPDATE TB_DOCUMENTOS_IMAGEM 
                            SET FLG_PUBLICO = ?
                            WHERE ID = ? ");

                        $stmt->bindParam(1, $flgPublico, PDO::PARAM_INT);
                        $stmt->bindParam(2, $idDocumentoImagem, PDO::PARAM_INT);
                        $stmt->execute();
                    }
                    $conn->commit();
                    echo json_encode(array('success'=>true));
                }  catch (Exception $e){
                    $conn->rollBack();
                    echo json_encode(array(
                        'success'   => false,
                        'msg'       => $e->getMessage() . "\n" . $e->getTraceAsString()
                    ));
                }

                exit;
                break;
            
            default:
                break;
        }
    }
    switch ($_GET['action']) {

        case 'downloadFiles':
            $digital = $_REQUEST['digital'];

            $documentoImagemTmp = new \Documento\Imagem\DocumentoImagemPDF();
            $documentoImagemTmp->setDigital( $digital );
            $sourcePath = $documentoImagemTmp->getUploadPath();
            unset($documentoImagemTmp);
            
            //Remove Cache da Digital
            $pattern = $sourcePath . '*';
            $arrArquivosExistentes = glob( $pattern );
            
            $zipAbsFilename = __BASE_PATH__ . '/documento_virtual/cache/arquivos.zip';

            $zip = new ZipArchive();
            $i = 0;
            if( $zip->open( $zipAbsFilename , ZipArchive::CREATE ) === true){
                while($i < count($arrArquivosExistentes)){
                    $zip->addFile( 
                        $arrArquivosExistentes[$i], //caminho absoluto do arquivo
                        substr($arrArquivosExistentes[$i], strpos($arrArquivosExistentes[$i], $digital.'/')+8 )//nome interno
                    );
                    $i++;
                }
                $zip->close();                    
            }
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="arquivos_'.$digital.'.zip"');
            header('Content-Length: '.filesize($zipAbsFilename) );
            readfile( $zipAbsFilename );
            unlink($zipAbsFilename);

            exit;
            break;
        
        case 'delete':
            LogErrorReader::factory()->delete()->getLog('Log deletado com sucesso!');
            exit;
            break;

        case 'garbageCollectionImagesByDigital':
            Imagens::factory()
                    ->garbageCollection($_REQUEST['digital'])
                    ->clearCache($_REQUEST['digital'])
            ;
            exit;
            break;

        case 'tester':
            LogErrorReader::factory()->tester();
            exit;
            break;

        case 'clearAll':
            LogErrorReader::factory()->clearAllCache()->getLog('Cache removido com sucesso!');
            exit;
            break;
        
        case 'logoffAll':
            //Remove Cache das sessões
            $pattern = __BASE_PATH__ . "/cache/sessions/sess_*";
            $arrSessionFiles = glob( $pattern );
            foreach ($arrSessionFiles as $key => $absoluteFileName) {
                @unlink( $absoluteFileName );
            }
            LogErrorReader::factory()->getLog('Logoff efetuado com sucesso!');

            exit;
            break;

        case 'getImagesSituation':
            $digital = $_REQUEST['digital'];

            $documentoImagemTmp = new \Documento\Imagem\DocumentoImagemPDF();
            $documentoImagemTmp->setDigital( $digital );
            $sourcePath = $documentoImagemTmp->getUploadPath();
            $documentoImagemTmp = null;
            
            $sql = "
                SELECT *
                FROM TB_DOCUMENTOS_IMAGEM 
                WHERE 
                    DIGITAL = ? 
                ORDER BY ID
            ";
//            AND DAT_INCLUSAO > TO_DATE('2014-01-11', 'YYYY-MM-DD')
//            AND DAT_INCLUSAO < TO_TIMESTAMP('2014-01-16 14:00:00','YYYY-MM-DD HH24:MI:SS')

            $stmt = \Controlador::getInstance()->getConnection()->connection->prepare($sql);
            $stmt->bindParam(1, $digital, \PDO::PARAM_STR);
            $stmt->execute();

            $arrDocumentoImagem = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            //Remove Cache da Digital
            $pattern = $sourcePath . '*';
            $arrArquivosExistentes = glob( $pattern );
            
            $i = 0;
            while($i < count($arrArquivosExistentes)){
                $arrArquivosExistentes[$i] = substr($arrArquivosExistentes[$i], strpos($arrArquivosExistentes[$i], 'documento_virtual') );
                $i++;
            }
                        
            $arrRegistrosSemArquivo = array();
            $arrArquivosComRegistro = array();
            $arrArquivosSemRegistro = array();
            
            foreach ($arrDocumentoImagem as $documentoImagem) {
                $extensao = '';
                switch ($documentoImagem['IMG_TYPE']) {
                    case '7':
                        $extensao = '.tif';
                        break;
                    case '8':
                        $extensao = '.png';
                        break;
                    case '9':
                        $extensao = '.pdf';
                        break;
                }
                
                $i = 0;
                $encontrouArquivo = false;
                while ($i < count($arrArquivosExistentes)){
                    if( strpos($arrArquivosExistentes[$i], $documentoImagem['MD5'].$extensao) !== false ){
                        $arrArquivosComRegistro[] = array(
                            'file' => $arrArquivosExistentes[$i],
                            'row' => $documentoImagem,
                            'extensao' => $extensao
                        );
                        $encontrouArquivo = true;
                    }
                    $i++;
                }
                if(!$encontrouArquivo){
                    $arrRegistrosSemArquivo[] = $documentoImagem;
                }
            }
            
            foreach ($arrArquivosExistentes as $arquivoExistente) {
                $encontrouRegistro = false;
                foreach ($arrDocumentoImagem as $registroDocumentoImagem) {
                    $extensao = '';
                    switch ($registroDocumentoImagem['IMG_TYPE']) {
                        case '7':
                            $extensao = '.tif';
                            break;
                        case '8':
                            $extensao = '.png';
                            break;
                        case '9':
                            $extensao = '.pdf';
                            break;
                    }

                    if( strpos($arquivoExistente, $registroDocumentoImagem['MD5'].$extensao) !== false ){
                        $encontrouRegistro = true;                        
                    }
                }
                if(!$encontrouRegistro){
                    $arrArquivosSemRegistro[] = $arquivoExistente;
                }
            }
            
            $arrRetorno = array(
                'ArquivosComRegistro'   => $arrArquivosComRegistro,
                'ArquivosSemRegistro'   => $arrArquivosSemRegistro,
                'ArquivosExistentes'    => $arrArquivosExistentes,
                'RegistrosSemArquivo'   => $arrRegistrosSemArquivo,
            );
            echo json_encode($arrRetorno);
            
            exit;
            break;

        case 'clearRecursos':
            LogErrorReader::factory()->clearCacheRecursos()->getLog('Cache removido com sucesso!');
            exit;
            break;

        case 'back':
            header('Location: sistemas_administrador.php');
            exit;
            break;

        case 'reload':
            LogErrorReader::factory()->getLog(
                    LogErrorReader::factory()->isExists() ? LogErrorReader::factory()->time() : 'Nenhum log foi registrado!'
            );
            exit;
            break;
    }
} catch (\Exception $e) {
    LogErrorReader::factory()->getLog($e->getMessage());
}

include("function/auto_load_statics.php");
?>
<html>
    <head>
<style type = "text/css">
            
body *{
    font-family: monospace !important;
}
button{}
textarea{
    width: 1000px;
    height: 600px;
}
a{
    font-size: 15px !important;
}
            
div#reload{
    display: block;
    clear: both;
}

span.message{
    display: block; 
}

div.log{
    display: block;
}

#garbageCollectionImagesByDigitalInput{
    font-size: 13px;
    color: black;
    width: 70px;
    font-weight: bold;
}
</style>
<script type="text/javascript">
    $(document).ready(function() {
        String.prototype.lpad = function(padString, length) {
            var str = this;
            while (str.length < length){   
                str = padString + str;
            }
            return str;
        }        
    
        $("#test_errors").click(function() {
            $.get("exibir_erros.php", {
                action: "tester"
            }, function() {
                $("#reload").load("exibir_erros.php?action=reload");
            }, "json");
        });
        $("#reload_log").click(function() {
            $("#reload").load("exibir_erros.php?action=reload");
        });
        $("#delete").click(function() {
            $("#reload").load("exibir_erros.php?action=delete");
        });
        $("#clearRecursos").click(function() {
            $("#reload").load("exibir_erros.php?action=clearRecursos");
        });
        $("#clearAll").click(function() {
            $("#reload").load("exibir_erros.php?action=clearAll");
        });
        $("#logoffAll").click(function() {
            $("#reload").load("exibir_erros.php?action=logoffAll");
            alert('Logoff efetuado com sucesso.')
        });
        
        $("#getImagesSituation").click(function() {
            $.getJSON(
                "exibir_erros.php?action=getImagesSituation&digital="+$('#digitalImageSituation').val(),
                function(result){
                    var optionsFlgPublico = "";
                    optionsFlgPublico += "<option value='2'>Excluido</option>";
                    optionsFlgPublico += "<option value='0'>Confidencial</option>";
                    optionsFlgPublico += "<option value='1'>Público</option>";

                    $("#div_arquivos_com_registro").html('');
                    $.each(result.ArquivosComRegistro, function(i, field){
                        var selFlgPublico = "<select name='id_"+field.row["ID"]+"'>"+optionsFlgPublico+"</select>"
                        var linkDownload = "<a href='" 
                                + field.file 
                                + "' title='"
                                + "FLG_PUBLICO: "    + field.row["FLG_PUBLICO"] + "\n"
                                + "IMG_TYPE: "       + field.row["IMG_TYPE"] + "\n"
                                + "IMG_BYTES: "      + field.row["IMG_BYTES"] + "\n"
                                + "TOTAL_PAGINAS: "  + field.row["TOTAL_PAGINAS"] + "\n"
                                + "DAT_INCLUSAO: "   + field.row["DAT_INCLUSAO"] + "\n"
                                + "'>"
                                + field.row["MD5"] + field.extensao 
                                + "</a><br />";
                        $("#div_arquivos_com_registro").append(selFlgPublico + linkDownload);//..row
                        $("#div_arquivos_com_registro > select:last").val(field.row["FLG_PUBLICO"]);
                    });

                    $("#div_arquivos_sem_registro").html('');
                    $.each(result.ArquivosSemRegistro, function(i, field){
                        var enderecoArquivo = field.substring(field.indexOf("documento_virtual"),field.length);
                        var linkDownload = "<a href='" + enderecoArquivo + "'>"+enderecoArquivo+"</a><br />";
                        $("#div_arquivos_sem_registro").append(linkDownload);
                    });

                    $("#div_arquivos_existentes").html('');
                    $.each(result.ArquivosExistentes, function(i, field){
                        var enderecoArquivo = field.substring(field.indexOf("documento_virtual"),field.length);
                        var linkDownload = "<a href='" + enderecoArquivo + "'>"+enderecoArquivo+"</a><br />";
                        $("#div_arquivos_existentes").append(linkDownload);
                    });
                    var linkDownTodos = "<a href='exibir_erros.php?action=downloadFiles&digital=" 
                            + $('#digitalImageSituation').val()
                            + "'>[Download de Todos]</a><br />";
                    $("#div_arquivos_existentes").append( linkDownTodos );

                    $("#div_registros_sem_arquivos").html('');
                    $.each(result.RegistrosSemArquivo, function(i, field){
                        var selFlgPublico = "<select name='id_"+field["ID"]+"'>"+optionsFlgPublico+"</select>"
                        var img_bytes_pad = field["IMG_BYTES"]+"";
                        img_bytes_pad = (img_bytes_pad == 'null')? "0" : img_bytes_pad;
                        var total_paginas_pad = field["TOTAL_PAGINAS"]+"";
                        total_paginas_pad = (total_paginas_pad == 'null')? "0" : total_paginas_pad;
                        var arquivoFaltante = "MD5:"    + field["MD5"] 
                                + " | FLG_PUBLICO:"     + field["FLG_PUBLICO"] 
                                + " | IMG_TYPE:"        + field["IMG_TYPE"] 
                                + " | IMG_BYTES:"       + img_bytes_pad.lpad("0", 10)
                                + " | TOTAL_PAGINAS:"   + total_paginas_pad.lpad("0", 3)
                                + " | DAT_INCLUSAO:"    + field["DAT_INCLUSAO"]
                                + "</br>"
                        $("#div_registros_sem_arquivos").append(selFlgPublico + arquivoFaltante);
                        $("#div_registros_sem_arquivos > select:last").val(field["FLG_PUBLICO"]);
                    });
                }
            );
        });
        
        $("#btSalvarSituacaoImagens").click(function(){
            var activeTabIdx = $( '#abas_imagens_arquivos_registros' ).tabs( "option", "selected" );
            var selector = '#abas_imagens_arquivos_registros > ul > li > a';
            var activeTabID = $(selector).eq(activeTabIdx).attr('href');
            
            switch(activeTabID){
                case "#div_arquivos_com_registro":                    
                break;
                case "#div_arquivos_sem_registro":
                    return false;
                break;
                case "#div_arquivos_existentes":
                    return false
                break;
                case "#div_registros_sem_arquivos":                    
                break;
            }
            
            $.post("./exibir_erros.php", {
                action: 'setImageSituation',
                selSituacaoDigitais: $(activeTabID + ' > select').serializeArray(),
            }, function(data) {
                if (data.success != true) {
                    alert('Não foi possível atualizar o status das imagens! ' + data.msg);
                }else{
                    alert('Alteração realizada com sucesso!');
                }
            }, 'json');

        });
        
        $("#administracao_abas").tabs();
        $("#abas_imagens_arquivos_registros").tabs();

        $("#garbageCollectionImagesByDigital").click(function() {
            if ($('#garbageCollectionImagesByDigitalInput').val().length != 7) {
                return false;
            }
            $.get('exibir_erros.php?action=garbageCollectionImagesByDigital', {
                digital: $('#garbageCollectionImagesByDigitalInput').val()
            }, function() {
                alert('Comando executado com sucesso!');
                $('#garbageCollectionImagesByDigitalInput').val('');
            });
        });
    });


</script>

    </head>
    <body>
        <button name="action" value="back" type="submit">VOLTAR</button>
        
        <!-- Abas com todos os resultados da pesquisa -->
        <div id="administracao_abas">
            
            <ul>
                <li><a id="" title="" href="#div_log_erros">Log/Erros</a></li>
                <li><a id="" title="" href="#div_cache">Cache</a></li>
                
                <?php 
                    if($_REQUEST['admin']=='root'):
                ?>
                    <li><a id="" title="" href="#div_imagens">Imagens</a></li>
                    <li><a id="" title="" href="#div_info">PHPInfo</a></li>
                <?php 
                    endif;
                ?>
            </ul>

            <div id="div_log_erros">
                
                <div class="menu">
                    <!--<form>-->
                    <button id="delete" type="button">LIMPAR LOG</button>
                    <button id="test_errors" type="button">TESTAR</button>
                    <button id="reload_log" type="button">RECARREGAR</button>
                    <hr>
                    <!--</form>-->
                </div>
                <div id="reload">
                    <?php
                    LogErrorReader::factory()->getLog(
                            LogErrorReader::factory()->isExists() ? LogErrorReader::factory()->time() : 'Nenhum log foi registrado!'
                    );
                    ?>
                </div>
                
            </div>
            
            <div id="div_cache">
                <!--<form>-->
                <button id="clearRecursos" type="button">LIMPAR CACHE RECURSOS</button>
                <button id="clearAll" type="button">LIMPAR CACHE GERAL</button>
                <button id="logoffAll" type="button">REMOVER SESSÕES</button>
                <!--</form>-->
                <hr>
                <button id="garbageCollectionImagesByDigital" type="button">SINCRONIZAR IMAGENS</button>
                <input type="text" id="garbageCollectionImagesByDigitalInput" maxlength="7">
            </div>

            <?php 
                if($_REQUEST['admin']=='root'):
            ?>
                <div id="div_imagens">               
                    <input id="getImagesSituation" type="button" value="VERIFICAR SITUAÇÃO" />
                    <input type="text" id="digitalImageSituation" maxlength="7" value="" />

                    <!-- Abas com todos os resultados da pesquisa -->
                    <div id="abas_imagens_arquivos_registros">
                        <ul>
                            <li><a id="" title="" href="#div_arquivos_com_registro">Arquivos Com Registro</a></li>
                            <li><a id="" title="" href="#div_arquivos_sem_registro">Arquivos Sem Registro</a></li>
                            <li><a id="" title="" href="#div_arquivos_existentes">Arquivos Existentes</a></li>
                            <li><a id="" title="" href="#div_registros_sem_arquivos">Registros sem Arquivos</a></li>
                        </ul>
                        <div id="div_arquivos_com_registro"></div>
                        <div id="div_arquivos_sem_registro"></div>
                        <div id="div_arquivos_existentes"></div>
                        <div id="div_registros_sem_arquivos"></div>
                    </div>
                    <button id="btSalvarSituacaoImagens" type="button">SALVAR NO BANCO</button>

                </div>
                <div id="div_info">

                    <div>                        
                        <?php
                            //Remove os Estilos CSS do PHPINFO
                            ob_start();
                            phpinfo();
                            preg_match ('%<style type="text/css">(.*?)</style>.*?(<body>.*</body>)%s', ob_get_clean(), $matches);

                            # $matches [1]; # Style information
                            # $matches [2]; # Body information

                            echo "<div class='phpinfodisplay'><style type='text/css'>\n",
                                join( "\n",
                                    array_map(
                                        create_function(
                                            '$i',
                                            'return ".phpinfodisplay " . preg_replace( "/,/", ",.phpinfodisplay ", $i );'
                                            ),
                                        preg_split( '/\n/', $matches[1] )
                                        )
                                    ),
                                "</style>\n",
                                $matches[2],
                                "\n</div>\n";
                        ?>
                    </div>
                </div>
            <?php 
                endif;
            ?>

            
        </div>
        
    </body>
</html>