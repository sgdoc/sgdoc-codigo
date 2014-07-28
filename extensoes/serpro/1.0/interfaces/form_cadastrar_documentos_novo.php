
<!--/*
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
 * */-->
<?php
$auth = Zend_Auth::getInstance()->getStorage()->read();
?>
<html>
    <head>
        
<script type="text/javascript">
var elements = {};

$(document).ready(function(){
    elements.digital = $('#DIGITAL_CADASTRAR_DOCUMENTOS');
    elements.serpro = $('#DIGITAL_CADASTRAR_DOCUMENTOS__SERPRO');
    elements.tipo = $('#TIPO_CADASTRAR_DOCUMENTOS');
    elements.numero = $('#NUMERO_CADASTRAR_DOCUMENTOS');
    elements.origem = $('#ORIGEM_CADASTRAR_DOCUMENTOS');
    elements.data_documento = $('#DATA_DOCUMENTO_CADASTRAR_DOCUMENTOS');
    elements.destino = $('#DESTINO_CADASTRAR_DOCUMENTOS');
    elements.tecnico_responsavel = $('#TECNICO_RESPONSAVEL_CADASTRAR_DOCUMENTOS');
    elements.assunto = $('#ASSUNTO_CADASTRAR_DOCUMENTOS');
    elements.assunto_complementar = $('#ASSUNTO_COMPLEMENTAR_CADASTRAR_DOCUMENTOS');
    elements.assinatura = $('#ASSINATURA_CADASTRAR_DOCUMENTOS');
    elements.interessado = $('#INTERESSADO_CADASTRAR_DOCUMENTOS');
    elements.procedencia =  $('#PROCEDENCIA_CADASTRAR_DOCUMENTOS');
    elements.data_entrada = $('#DATA_ENTRADA_CADASTRAR_DOCUMENTOS');
    elements.recibo = $('#RECEBIDO_POR_CADASTRAR_DOCUMENTOS');
    elements.cargo = $('#CARGO_CADASTRAR_DOCUMENTOS');
    elements.prazo = $('#PRAZO_CADASTRAR_DOCUMENTOS');
    elements.tipo_origem = $('#TIPO_ORIGEM_CADASTRAR_DOCUMENTOS');
    elements.tipo_destino = $('#TIPO_DESTINO_CADASTRAR_DOCUMENTOS');
    elements.filtro_origem = $('#FILTRO_ORIGEM_CADASTRAR_DOCUMENTOS');
    elements.filtro_destino = $('#FILTRO_DESTINO_CADASTRAR_DOCUMENTOS');
    elements.filtro_assunto = $('#FILTRO_ASSUNTO_CADASTRAR_DOCUMENTOS');
    elements.filtro_recebido_por = $('#FILTRO_RECEBIDO_POR_CADASTRAR_DOCUMENTOS');

    /*Listeners*/
    /*Bind Dialog Close*/
    $("#box-filtro-origem-cadastrar-documentos").bind("dialogclose", function(event, ui) {
        var exists = false;
        var c = false;
        if(elements.tipo_origem.val()!='IN' && elements.tipo_origem.val()!='PR'){
            elements.origem.find('option').each(function() {
                if(elements.filtro_origem.val()==$(this).text()){
                    exists = true;
                }
            });
            if(!exists){

                if(elements.origem.find('option').length>0){
                    c = confirm('Atenção: '+elements.origem.find('option').length+' Origem(s) localizada(s). Mas nao sao identicas a origem informada ('+elements.filtro_origem.val()+') !\nDeseja adiciona-la na base de dados de origens de documentos?');
                }else{
                    c = confirm('Esta origem nao foi encontrada!\nDeseja adiciona-la na base de dados de origens de documentos?');
                }


                if(c){
                    $.post("modelos/documentos/pessoa.php", {
                        acao: 'adicionar',
                        pessoa: elements.filtro_origem.val(),
                        tipo: elements.tipo_origem.val()
                    },
                    function(data){
                        try{
                            if(data.success == 'true'){
                                var options = elements.origem.attr('options');
                                $('option', '#ORIGEM_CADASTRAR_DOCUMENTOS').remove();
                                options[0] = new Option(data.pessoa, data.pessoa);
                            }else{
                                alert(data.error);
                            }
                        }catch(e){
                            alert('Ocorreu um erro ao tentar adicionar esta origem!\n['+e+']');
                        }
                    }, "json");
                }
            }
        }
    });

    $("#box-filtro-destino-cadastrar-documentos").bind("dialogclose", function(event, ui) {
        var exists = false;
        var c = false;
        if(elements.tipo_destino.val()!='IN' && elements.tipo_destino.val()!='PR'){
            elements.destino.find('option').each(function() {
                if(elements.filtro_destino.val()==$(this).text()){
                    exists = true;
                }
            });
            if(!exists){

                if(elements.destino.find('option').length>0){
                    c = confirm('Atenção: '+elements.destino.find('option').length+' Destino(s) localizado(s). Mas nao sao identicos ao destino informado ('+elements.filtro_destino.val()+') !\nDeseja adiciona-lo na base de dados de destinos de documentos?');
                }else{
                    c = confirm('Este destino nao foi encontrado!\nDeseja adiciona-lo na base de dados de destinos de documentos?');
                }


                if(c){
                    $.post("modelos/documentos/pessoa.php", {
                        acao: 'adicionar',
                        pessoa: elements.filtro_destino.val(),
                        tipo: elements.tipo_destino.val()
                    },
                    function(data){
                        try{
                            if(data.success == 'true'){
                                var options = elements.destino.attr('options');
                                $('option', '#DESTINO_CADASTRAR_DOCUMENTOS').remove();
                                options[0] = new Option(data.pessoa, data.pessoa);
                            }else{
                                alert(data.error);
                            }
                        }catch(e){
                            alert('Ocorreu um erro ao tentar adicionar esta destino!\n['+e+']');
                        }
                    }, "json");
                }
            }
        }
    });

    elements.digital.keyup(function(){
        if($(this).val().length==7){
            try{
                if(jquery_validar_digital($(this).val())){
                    if(elements.tipo.find('option').length<1){
                        /*Carregar o combo de tipologias de documentos*/
                        elements.tipo.combobox('modelos/combos/tipologias_documentos.php');
                    }
                }else{
                    alert('Digital inválida!');
                    $(this).val('');
                };
            }catch(e){
                alert(e);
            }
        }
    });

    elements.procedencia.change(function(){
        switch ($(this).val()) {
            case 'I':
                elements.recibo.attr('disabled', 'disabled');
                elements.data_entrada.attr('disabled', 'disabled');
                elements.recibo.html('');
                elements.data_entrada.val('');
                break;

            case 'E':
                elements.recibo.removeAttr('disabled');
                elements.recibo.append("<option selected='selected'><?php print_r($auth->NOME);?></option>");
                elements.data_entrada.removeAttr('disabled');
                break;

            default:
                break;
        }
    });
});

/*Validar Campos Cadastro Documento*/
function jquery_validar_campos_cadastrar_documentos(){
    /*Validar tipo de origem procuradorias*/
    if(elements.tipo_origem.val() == 'PR' && elements.prazo.val().length!=10){
        return 'Ao escolher o tipo de origem "Procuradorias Federais" a campo prazo torna-se obrigatorio!';
    }
    /*Validar campos*/
    if(
        (
            elements.digital.val().length == 7 &&
            elements.tipo.val() &&
            jQuery.trim( elements.numero.val() ) &&
            elements.origem.val() &&
            jQuery.trim( elements.data_documento.val() ) &&
            elements.destino.val() &&
            jQuery.trim( elements.tecnico_responsavel.val() ) &&
            elements.assunto.val() &&
            elements.procedencia.val()=='I'
        ) || (
            elements.digital.val().length == 7 &&
            elements.tipo.val() &&
            jQuery.trim( elements.numero.val() ) &&
            elements.origem.val() &&
            jQuery.trim( elements.data_documento.val() ) &&
            elements.destino.val() &&
            jQuery.trim( elements.tecnico_responsavel.val() ) &&
            elements.assunto.val() &&
            elements.procedencia.val()=='E' &&
            elements.data_entrada.val() &&
            elements.recibo.val()
        )
    ){
        /*Validar duplicidade de documento*/
        var id_doc = 0;
        $.ajaxSetup({async:false});
        var retorno = false;
        $.post("modelos/documentos/documentos.php", {
            acao: 'unique',
            id: id_doc,
            tipo: elements.tipo.val(),
            numero: elements.numero.val(),
            origem: elements.origem.val()
        },
        function(data){
            if(data.success == 'true'){
                retorno = true;
            }else{
                retorno = data.error;
            }
        }, "json");
        if (retorno == true) {
            /*Validar o digital*/
            if(jquery_validar_digital(elements.digital.val())){
                return true;
            }else{
                return 'Digital Invalido!';
            }
        } else {
            return retorno;
        }

    } else {
        alert('Campo(s) obrigatório(s) em branco ou preenchidos de forma inválidas!');

        if( elements.digital.val().length !== 7 ){ elements.digital.focus(); return null; }
        if( elements.tipo.val() == '' ){ elements.tipo.focus(); return null; }
        if( jQuery.trim( elements.numero.val() ) == '' ){ elements.numero.focus(); return null; }
        if( elements.origem.val() == null ){ elements.origem.focus(); return null; }
        if( jQuery.trim( elements.data_documento.val() ) == '' ){ elements.data_documento.focus(); return null; }
        if( elements.destino.val() == null ){ elements.destino.focus(); return null; }
        if( jQuery.trim( elements.tecnico_responsavel.val() ) == '' ){ elements.tecnico_responsavel.focus(); return null; }
        if( elements.assunto.val() == null ){ elements.assunto.focus(); return null; }
        if( elements.procedencia.val() == '' ){ elements.procedencia.focus(); return null; }
        if( elements.procedencia.val()=='E' ){
            if( elements.data_entrada.val() == '' ){ elements.data_entrada.focus(); return null; }
            if( jQuery.trim( elements.recibo.val() ) == null ){ elements.recibo.focus(); return null; }
        }

        return null;
    }
}// \function jquery_validar_campos_cadastrar_documentos()

/*Validar digital*/
function jquery_validar_digital(digital){

    var r = $.ajax({
        type: 'POST',
        url: 'modelos/documentos/validar_digital.php',
        data: 'digital='+digital,
        async: false,
        success: function(){},
        failure: function(){}
    }).responseText;

    r = eval('('+r+')');

    if(r.success == 'true'){
        return r.valid;    
    }else{
        throw 'Ocorreu um erro ao tentar validar digital!\n['+r.error+']';
        return false;    
    }
}

/*Inserir documento*/
function jquery_cadastrar_documento(){
    if(confirm('Você tem certeza que deseja cadastrar este documento?')){
        $( "#progressbar" ).show();
        $.post("modelos/documentos/cadastrar.php", {
            digital: elements.digital.val(),
            serpro: elements.serpro.val(),
            tipo: elements.tipo.val(),
            numero: elements.numero.val(),
            origem: elements.origem.val(),
            data_documento: elements.data_documento.val(),
            destino: elements.destino.val(),
            tecnico_responsavel: elements.tecnico_responsavel.val(),
            assunto: elements.assunto.val(),
            assunto_complementar: elements.assunto_complementar.val(),
            assinatura: elements.assinatura.val(),
            interessado: elements.interessado.val(),
            procedencia:  elements.procedencia.val(),
            data_entrada: elements.data_entrada.val(),
            recibo: elements.recibo.val(),
            cargo: elements.cargo.val(),
            prazo: elements.prazo.val()
        },
        function(data){
            try{
                if(data.success == 'true'){
                    /*Liberar Campos*/
                    elements.tipo.removeAttr('disabled');
                    elements.numero.removeAttr('disabled', 'disabled');

                    /*Limpar Campos*/
                    elements.digital.val('');
                    //elements.tipo.empty();
                    elements.numero.val('');
                    elements.origem.empty();
                    elements.data_documento.val('');
                    elements.destino.empty();
                    elements.tecnico_responsavel.val('');
                    elements.assunto.empty()
                    elements.assunto_complementar.val('');
                    elements.interessado.val('');
                    elements.assinatura.val('');
                    elements.cargo.val('');
                    elements.prazo.val('');
                    elements.tipo_origem.val('IN');
                    elements.filtro_origem.val('');
                    elements.tipo_destino.val('IN');
                    elements.filtro_destino.val('');
                    elements.filtro_assunto.val('');
                    elements.filtro_recebido_por.val('');

                    elements.procedencia.val('I');
                    elements.data_entrada.val('');
                    elements.recibo.val('');
                    /*Alert*/
                    $('#div-form-cadastrar-documentos').dialog('close');
                    oTableDocumentos.fnDraw(false);
                    alert('Documento cadastrado com sucesso!');
                }else{
                    alert(data.error);
                }
            }catch(e){
                alert('Ocorreu um erro ao tentar validar o digital!\n['+e+']');
            }
            $( "#progressbar" ).hide();
        }, "json");
    }
}

/*Gerar numero documento*/
function jquery_gerar_numero_documento(tipo){
    if(confirm('Você tem certeza que deseja gerar um novo numero para a tipologia "'+tipo+'"?\nAtenção! Este procedimento nao pode ser desfeito!')){
        $.post("modelos/documentos/gerar_numeracao.php", {
            tipologia: tipo
        },
        function(data){
            try{
                if(data.success == 'true'){
                    if(data.numero){
                        elements.numero.val(data.numero);
                        elements.numero.attr('disabled','disabled');
                        elements.tipo.attr('disabled','disabled');
                        // $('#botao-gerar-numero-cadastrar-documentos').attr('disabled','disabled');
                    }
                }else{
                    alert(data.error);
                }
            }catch(e){
                alert('Ocorreu um erro ao tentar inclementar o numero da tipologia escolhidal!\n['+e+']');
            }
        }, "json");
    }
}

$(document).ready(function(){
    /*Auto Completes*/
    elements.filtro_destino.autocompleteonline({
        idTypeField: 'TIPO_DESTINO_CADASTRAR_DOCUMENTOS',
        idComboBox:'DESTINO_CADASTRAR_DOCUMENTOS',
        url: 'modelos/combos/autocomplete.php',
        paramTypeName: 'type',
        extraParams: {
            action: 'documentos-origens'
        }
    });

    elements.filtro_origem.autocompleteonline({
        idTypeField: 'TIPO_ORIGEM_CADASTRAR_DOCUMENTOS',
        idComboBox:'ORIGEM_CADASTRAR_DOCUMENTOS',
        url: 'modelos/combos/autocomplete.php',
        paramTypeName: 'type',
        extraParams: {
            action: 'documentos-origens'
        }
    });

    elements.filtro_assunto.autocompleteonline({
        url: 'modelos/combos/autocomplete.php',
        idComboBox: 'ASSUNTO_CADASTRAR_DOCUMENTOS',
        extraParams: {
            action: 'documentos-assuntos'
        }
    });

    elements.filtro_recebido_por.autocompleteonline({
        url: 'modelos/combos/autocomplete.php',
        idComboBox: 'RECEBIDO_POR_CADASTRAR_DOCUMENTOS',
        extraParams: {
            action: 'documentos-recebido-por'
        }
    });
    
    /*Calendarios*/
    elements.prazo.datepicker({
        changeMonth: true,
        changeYear: true
    });

    elements.data_documento.datepicker({
        defaultDate: new Date(),
        changeMonth: true,
        changeYear: true,
        onClose: function(selectedDate) {
            elements.data_entrada.datepicker("option", "minDate", selectedDate);
        }
    });

    elements.data_entrada.datepicker({
        defaultDate: new Date(),
        changeMonth: true,
        changeYear: true,
    });

    $('#box-filtro-origem-cadastrar-documentos').dialog({
        title: 'Filtro',
        autoOpen: false,
        resizable: false,
        modal: false,
        width: 380,
        height: 120
    });

    $('#box-filtro-destino-cadastrar-documentos').dialog({
        title: 'Filtro',
        autoOpen: false,
        resizable: false,
        modal: false,
        width: 380,
        height: 120
    });

    $('#box-filtro-assunto-cadastrar-documentos').dialog({
        title: 'Filtro',
        autoOpen: false,
        resizable: false,
        modal: false,
        width: 380,
        height: 90
    });

    $('#box-filtro-recebido-por-cadastrar-documentos').dialog({
        title: 'Filtro',
        autoOpen: false,
        resizable: false,
        modal: false,
        width: 380,
        height: 90
    });

    $('#div-form-cadastrar-documentos').dialog({
        title: 'Novo Documento',
        autoOpen: false,
        resizable: false,
        modal: true,
        width: 650,
        height: 600,
        buttons: {
            Salvar: function() {
                var validou = jquery_validar_campos_cadastrar_documentos();
                if (validou == true) {
                    jquery_cadastrar_documento();
                } else {
                    if(validou){                                    
                        alert(validou);
                    }                                 
                }
            },
            Cancelar: function() {
                $('#box-filtro-origem-cadastrar-documentos').dialog('close');
                $('#box-filtro-destino-cadastrar-documentos').dialog('close');
                $('#box-filtro-assunto-cadastrar-documentos').dialog('close');
                /*Liberar campos tipo e numero possivelmente bloqueados*/
                elements.numero.removeAttr('disabled').val('');
                elements.tipo.removeAttr('disabled').val('');
                $(this).dialog('close');
            }
        }
    });
    
    /*Ativar filtros*/
    $('#botao-filtro-origem-cadastrar-documentos').click(function(){
        $('#box-filtro-origem-cadastrar-documentos').dialog('open');
    });

    $('#botao-filtro-assunto-cadastrar-documentos').click(function(){
        $('#box-filtro-assunto-cadastrar-documentos').dialog('open');
    });

    $('#botao-filtro-recebido-por-cadastrar-documentos').click(function(){
        $('#box-filtro-recebido-por-cadastrar-documentos').dialog('open');
    });

    $('#botao-filtro-destino-cadastrar-documentos').click(function(){
        $('#box-filtro-destino-cadastrar-documentos').dialog('open');
    });

    /*Abrir form novo documento*/
    $('#botao-documento-novo-cadastrar-documentos').click(function(){
        /*Previnir que a solicitacao ocorra sem necessidade*/
        //                    if(elements.tipo.find('option').length<1){
        //                        /*Carregar o combo de tipologias de documentos*/
        //                        elements.tipo.combobox('modelos/combos/tipologias_documentos.php');
        //                    }
        $('#div-form-cadastrar-documentos').dialog('open');
    });
 
    /*Botao limpar*/
    $('#botao-limpar-data-entrada-cadastrar-documentos').click(function(){
        elements.data_entrada.val('');
    });
    $('#botao-limpar-prazo-cadastrar-documentos').click(function(){
        elements.prazo.val('');
    });
 
    /*Botao gerar numero*/
    $('#botao-gerar-numero-cadastrar-documentos').click(function(){
        if(elements.tipo.val()){
            jquery_gerar_numero_documento(elements.tipo.val());
        }else{
            alert('Primeiro selecione uma tipologia valida!');
        }
    });
    /*Mudar procedencia*/
});

</script>

    </head>

    <body>
        <!--Formulario-->
        <div class="div-form-dialog" id="div-form-cadastrar-documentos">

            <div class="row">
                <label class="label">*DIGITAL_____:</label>
                <span class="conteudo">
                    <input type="text" id="DIGITAL_CADASTRAR_DOCUMENTOS__SERPRO" maxlength="7" onKeyPress="DigitaNumero(this);">
                </span>
            </div>
            
            <div class="row">
                <label class="label">*DIGITAL:</label>
                <span class="conteudo">
                    <input type="text" id="DIGITAL_CADASTRAR_DOCUMENTOS" maxlength="7" onKeyPress="DigitaNumero(this);">
                </span>
            </div>

            <div class="row">
                <label class="label">*TIPO:</label>
                <span class="conteudo">
                    <select class='FUNDOCAIXA1' id='TIPO_CADASTRAR_DOCUMENTOS'></select>
                </span>
            </div>

            <div class="row">
                <label class="label">*NUMERO:</label>
                <span class="conteudo">
                    <input type="text" id="NUMERO_CADASTRAR_DOCUMENTOS" maxlength="60" <?php isset($GERADO) ? print $GERADO  : ''; ?> onKeyUp="DigitaLetraSeguro(this)">
                </span>
                <img title="Gerar Numero" class="botao-auxiliar" id="botao-gerar-numero-cadastrar-documentos" src="imagens/fam/add.png">
            </div>

            <div class="row">
                <label>*ORIGEM:</label>
                <span class="conteudo">
                    <select id="ORIGEM_CADASTRAR_DOCUMENTOS"></select>
                </span>
                <img title="Filtrar" class="botao-auxiliar-fix-combobox" id="botao-filtro-origem-cadastrar-documentos" src="imagens/fam/application_edit.png">
            </div>

            <div class="row">
                <label>*DATA DO DOCUMENTO:</label>
                <span class="conteudo">
                    <input type="text" id="DATA_DOCUMENTO_CADASTRAR_DOCUMENTOS" maxlength="10" readonly="true" />
                    <input type="hidden" id="DIRETORIA" value="<?php print($auth->ID_UNIDADE); ?>" maxlength="120" <?php isset($GERADO) ? print $GERADO  : ''; ?> />
                </span>
            </div>

            <div class="row">
                <label>*DESTINO:</label>
                <span class="conteudo">
                    <select id="DESTINO_CADASTRAR_DOCUMENTOS"></select>
                </span>
                <img title="Filtrar" class="botao-auxiliar-fix-combobox" id="botao-filtro-destino-cadastrar-documentos" src="imagens/fam/application_edit.png">
            </div>

            <div class="row">
                <label>*ENCAMINHADO PARA:</label>
                <span class="conteudo">
                    <input type="text" onKeyUp="DigitaLetraSeguro(this)" maxlength="60" id="TECNICO_RESPONSAVEL_CADASTRAR_DOCUMENTOS">
                </span>
            </div>

            <div class="row">
                <label>*ASSUNTO:</label>
                <span class="conteudo">
                    <select id="ASSUNTO_CADASTRAR_DOCUMENTOS"></select>
                </span>
                <img title="Filtrar" class="botao-auxiliar-fix-combobox" id="botao-filtro-assunto-cadastrar-documentos" src="imagens/fam/application_edit.png">
            </div>

            <div class="row">
                <label>ASSUNTO COMPLEMENTAR:</label>
                <span class="conteudo">
                    <input type="text" onKeyUp="DigitaLetraSeguro(this)" id="ASSUNTO_COMPLEMENTAR_CADASTRAR_DOCUMENTOS">
                </span>
            </div>

            <div class="row">
                <label>INTERESSADO:</label>
                <span class="conteudo">
                    <input type="text" onKeyUp="DigitaLetraSeguro(this)" maxlength="100" id="INTERESSADO_CADASTRAR_DOCUMENTOS">
                </span>
            </div>

            <div class="row">
                <label>ASSINATURA:</label>
                <span class="conteudo">
                    <input type="text" onKeyUp="DigitaLetraSeguro(this)" id="ASSINATURA_CADASTRAR_DOCUMENTOS" maxlength="60">
                </span>
            </div>

            <div class="row">
                <label>CARGO:</label>
                <span class="conteudo">
                    <input type="text" onKeyUp="DigitaLetraSeguro(this)" id="CARGO_CADASTRAR_DOCUMENTOS" maxlength="60">
                </span>
            </div>

            <div class="row">
                <label>DATA DO PRAZO:</label>
                <span class="conteudo">
                    <input type="text" id="PRAZO_CADASTRAR_DOCUMENTOS" maxlength="10" readonly="true">
                </span>
                <img title="Limpar" class="botao-auxiliar" src="imagens/fam/delete.png" id="botao-limpar-prazo-cadastrar-documentos">
            </div>

            <div class="row">
                <label>*PROCEDENCIA:</label>
                <span class="conteudo">
                    <select id="PROCEDENCIA_CADASTRAR_DOCUMENTOS">
                        <option value="I">Interno</option>
                        <option value="E">Externo</option>
                    </select>
                </span>
            </div>

            <div class="row">
                <label>**DATA ENTRADA:</label>
                <span class="conteudo">
                    <input type="text" disabled id="DATA_ENTRADA_CADASTRAR_DOCUMENTOS" maxlength="10" readonly="true">
                </span>
                <img title="Limpar" class="botao-auxiliar" src="imagens/fam/delete.png" id="botao-limpar-data-entrada-cadastrar-documentos">
            </div>

            <div class="row">
                <label>**RECEBIDO POR:</label>
                <span class="conteudo">
                    <select id="RECEBIDO_POR_CADASTRAR_DOCUMENTOS"></select>
                </span>
                <img title="Filtrar" class="botao-auxiliar-fix-combobox" id="botao-filtro-recebido-por-cadastrar-documentos" src="imagens/fam/application_edit.png">
            </div>
        </div>

        <!-- Filtros -->
        <div id="box-filtro-origem-cadastrar-documentos" class="box-filtro">
            <div class="row">
                <label>Tipo de Origem:</label>
                <div class="conteudo">
                    <select id="TIPO_ORIGEM_CADASTRAR_DOCUMENTOS" class="FUNDOCAIXA1">
                        <option value="IN">Unidades Organizacionais</option>
                        <option value="PR">Procuradorias Federais</option>
                        <option value="PF">Pessoa Fisica</option>
                        <option value="PJ">Pessoa Juridica</option>
                        <option value="OF">Outros Orgaos</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <label>Origem:</label>
                <div class="conteudo">
                    <input id="FILTRO_ORIGEM_CADASTRAR_DOCUMENTOS" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
                </div>
            </div>
        </div>

        <div id="box-filtro-destino-cadastrar-documentos" class="box-filtro">
            <div class="row">
                <label>Tipo de Destino:</label>
                <div class="conteudo">
                    <select id="TIPO_DESTINO_CADASTRAR_DOCUMENTOS" class="FUNDOCAIXA1">
                        <option value="IN">Unidades Organizacionais</option>
                        <option value="PR">Procuradorias Federais</option>
                        <option value="PF">Pessoa Fisica</option>
                        <option value="PJ">Pessoa Juridica</option>
                        <option value="OF">Outros Orgaos</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <label>Destino:</label>
                <div class="conteudo">
                    <input id="FILTRO_DESTINO_CADASTRAR_DOCUMENTOS" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
                </div>
            </div>
        </div>

        <div id="box-filtro-assunto-cadastrar-documentos" class="box-filtro">
            <div class="row">
                <label>Assunto:</label>
                <div class="conteudo">
                    <input id="FILTRO_ASSUNTO_CADASTRAR_DOCUMENTOS" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
                </div>
            </div>
        </div>
        
        <div id="box-filtro-recebido-por-cadastrar-documentos" class="box-filtro">
            <div class="row">
                <label>RECEBIDO POR:</label>
                <div class="conteudo">
                    <input id="FILTRO_RECEBIDO_POR_CADASTRAR_DOCUMENTOS" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
                </div>
            </div>
        </div>
        
    </body>
</html>