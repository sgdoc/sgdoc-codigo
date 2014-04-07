
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
<script type="text/javascript">
            
    $(document).ready(function(){

        /*Listeners*/
        /*Bind Dialog Close*/
        $("#box-filtro-origem-pre-cadastrar-documentos").bind("dialogclose", function(event, ui) {
            var exists = false;
            var c = false;
            if($('#TIPO_ORIGEM_PRE_CADASTRAR_DOCUMENTOS').val()!='IN' && $('#TIPO_ORIGEM_PRE_CADASTRAR_DOCUMENTOS').val()!='PR'){
                $("#ORIGEM_PRE_CADASTRAR_DOCUMENTOS option").each(function() {
                    if($('#FILTRO_ORIGEM_PRE_CADASTRAR_DOCUMENTOS').val()==$(this).text()){
                        exists = true;
                    }
                });
                if(!exists){

                    if($('#ORIGEM_PRE_CADASTRAR_DOCUMENTOS option').length>0){
                        c = confirm('Atenção: '+$('#ORIGEM_PRE_CADASTRAR_DOCUMENTOS option').length+' Origem(s) localizada(s). Mas nao sao identicas a origem informada ('+$('#FILTRO_ORIGEM_PRE_CADASTRAR_DOCUMENTOS').val()+') !\nDeseja adiciona-la na base de dados de origens de documentos?');
                    }else{
                        c = confirm('Esta origem nao foi encontrada!\nDeseja adiciona-la na base de dados de origens de documentos?');
                    }


                    if(c){
                        $.post("modelos/documentos/pessoa.php", {
                            acao: 'adicionar',
                            pessoa: $('#FILTRO_ORIGEM_PRE_CADASTRAR_DOCUMENTOS').val(),
                            tipo: $('#TIPO_ORIGEM_PRE_CADASTRAR_DOCUMENTOS').val()
                        },
                        function(data){
                            try{
                                if(data.success == 'true'){
                                    var options = $('#ORIGEM_PRE_CADASTRAR_DOCUMENTOS').attr('options');
                                    $('option', '#ORIGEM_PRE_CADASTRAR_DOCUMENTOS').remove();
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

        $('#DIGITAL_PRE_CADASTRAR_DOCUMENTOS').keyup(function(){
            if($(this).val().length==7){
                try{
                    if(jquery_pre_cadastrar_validar_digital($(this).val())){
                        if($('#TIPO_PRE_CADASTRAR_DOCUMENTOS').find('option').length<1){
                            /*Carregar o combo de tipologias de documentos*/
                            $('#TIPO_PRE_CADASTRAR_DOCUMENTOS').combobox('modelos/combos/tipologias_documentos.php');
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

        $('#PROCEDENCIA_PRE_CADASTRAR_DOCUMENTOS').change(function(){
            switch ($(this).val()) {
                case 'I':
                    $('#RECIBO_PRE_CADASTRAR_DOCUMENTOS').attr('disabled', 'disabled');
                    $('#DATA_ENTRADA_PRE_CADASTRAR_DOCUMENTOS').attr('disabled', 'disabled');
                    $('#RECIBO_PRE_CADASTRAR_DOCUMENTOS').val('');
                    $('#DATA_ENTRADA_PRE_CADASTRAR_DOCUMENTOS').val('');
                    break;
           
                case 'E':
                    $('#RECIBO_PRE_CADASTRAR_DOCUMENTOS').removeAttr('disabled');
                    $('#DATA_ENTRADA_PRE_CADASTRAR_DOCUMENTOS').removeAttr('disabled');
                    break;

                default:
                    break;
            }
        });
    });

    /*Validar Campos Cadastro Documento*/
    function jquery_validar_campos_pre_cadastrar_documentos(){
                
        /*Validar campos*/
        if(
        $('#DIGITAL_PRE_CADASTRAR_DOCUMENTOS').val().length == 7 &&
            $('#TIPO_PRE_CADASTRAR_DOCUMENTOS').val() &&
            $('#NUMERO_PRE_CADASTRAR_DOCUMENTOS').val() &&
            $('#ORIGEM_PRE_CADASTRAR_DOCUMENTOS').val() &&
            $('#PROCEDENCIA_PRE_CADASTRAR_DOCUMENTOS').val()
    ){
            /*Validar duplicidade de documento*/
            var id_doc = 0;
            $.ajaxSetup({async:false});
            var retorno = false;
            $.post("modelos/documentos/documentos.php", {
                acao: 'unique',
                id: id_doc,
                tipo: $('#TIPO_PRE_CADASTRAR_DOCUMENTOS').val(),
                numero: $('#NUMERO_PRE_CADASTRAR_DOCUMENTOS').val(),
                origem: $('#ORIGEM_PRE_CADASTRAR_DOCUMENTOS').val()
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
                if(jquery_pre_cadastrar_validar_digital($('#DIGITAL_PRE_CADASTRAR_DOCUMENTOS').val())){
                    return true;
                }else{
                    return 'Digital Invalido!';
                }
            } else {
                return retorno;
            }
                   
        } else {
            return 'Campo(s) obrigatório(s) em branco ou preenchidos de forma inválida!';
        }
    }

    /*Validar digital*/
    function jquery_pre_cadastrar_validar_digital(digital){
                                        
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
    function jquery_pre_cadastrar_documento(){
        if(confirm('Você tem certeza que deseja pré-cadastrar este documento?')){
            $( "#progressbar" ).show();
            $.post("modelos/documentos/pre-cadastrar.php", {
                digital: $('#DIGITAL_PRE_CADASTRAR_DOCUMENTOS').val(),
                tipo: $('#TIPO_PRE_CADASTRAR_DOCUMENTOS').val(),
                numero: $('#NUMERO_PRE_CADASTRAR_DOCUMENTOS').val(),
                origem: $('#ORIGEM_PRE_CADASTRAR_DOCUMENTOS').val(),
                procedencia:  $('#PROCEDENCIA_PRE_CADASTRAR_DOCUMENTOS').val(),
                comentario:  $('#COMENTARIO_PRE_CADASTRAR_DOCUMENTOS').val()
            },
            function(data){
                try{
                    if(data.success == 'true'){
                        /*Liberar Campos*/
                        $('#TIPO_PRE_CADASTRAR_DOCUMENTOS').removeAttr('disabled');
                        $('#NUMERO_PRE_CADASTRAR_DOCUMENTOS').removeAttr('disabled', 'disabled');
                                                            
                        /*Limpar Campos*/
                        $('#DIGITAL_PRE_CADASTRAR_DOCUMENTOS').val('');
                        $('#NUMERO_PRE_CADASTRAR_DOCUMENTOS').val('');
                        $('#ORIGEM_PRE_CADASTRAR_DOCUMENTOS').empty();
                        $('#TIPO_ORIGEM_PRE_CADASTRAR_DOCUMENTOS').val('IN');
                        $('#FILTRO_ORIGEM_PRE_CADASTRAR_DOCUMENTOS').val('');
                        $('#TIPO_DESTINO_PRE_CADASTRAR_DOCUMENTOS').val('IN');
                        $('#FILTRO_DESTINO_PRE_CADASTRAR_DOCUMENTOS').val('');
                        $('#FILTRO_ASSUNTO_PRE_CADASTRAR_DOCUMENTOS').val('');

                        $('#PROCEDENCIA_PRE_CADASTRAR_DOCUMENTOS').val('I');

                        /*Alert*/
                        $('#div-form-pre-cadastrar-documentos').dialog('close');
                        alert('Documento pré-cadastrado com sucesso!');
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

    $(document).ready(function(){
               
        $("#FILTRO_ORIGEM_PRE_CADASTRAR_DOCUMENTOS").autocompleteonline({
            idTypeField: 'TIPO_ORIGEM_PRE_CADASTRAR_DOCUMENTOS',
            idComboBox:'ORIGEM_PRE_CADASTRAR_DOCUMENTOS',
            url: 'modelos/combos/autocomplete.php',
            paramTypeName: 'type',
            extraParams: {
                action: 'documentos-origens'
            }
        });
    
        $('#box-filtro-origem-pre-cadastrar-documentos').dialog({
            title: 'Filtro',
            autoOpen: false,
            resizable: false,
            modal: false,
            width: 380,
            height: 120
        });

        $('#div-form-pre-cadastrar-documentos').dialog({
            title: 'Novo Pré-Cadastro',
            autoOpen: false,
            resizable: false,
            modal: true,
            width: 650,
            height: 450,
            buttons: {
                Salvar: function() {
                    $( "#progressbar" ).show();
                    var validou = jquery_validar_campos_pre_cadastrar_documentos();
                    if (validou == true) {
                        jquery_pre_cadastrar_documento();
                    } else {
                        alert(validou);
                    }
                    $( "#progressbar" ).hide();
                },
                Cancelar: function() {
                    $('#box-filtro-origem-pre-cadastrar-documentos').dialog('close');
                    $('#box-filtro-destino-pre-cadastrar-documentos').dialog('close');
                    $('#box-filtro-assunto-pre-cadastrar-documentos').dialog('close');
                    /*Liberar campos tipo e numero possivelmente bloqueados*/
                    $('#NUMERO_PRE_CADASTRAR_DOCUMENTOS').removeAttr('disabled').val('');
                    $('#TIPO_PRE_CADASTRAR_DOCUMENTOS').removeAttr('disabled').val('');
                    $(this).dialog('close');
                }
            }
        });
        /*Ativar filtros*/
        $('#botao-filtro-origem-pre-cadastrar-documentos').click(function(){
            $('#box-filtro-origem-pre-cadastrar-documentos').dialog('open');
        });

        /*Abrir form novo documento*/
        $('#botao-novo-documento-pre-cadastro').click(function(){
            $('#div-form-pre-cadastrar-documentos').dialog('open');
        });
        /*Botao gerar numero*/
        $('#botao-gerar-numero-pre-cadastrar-documentos').click(function(){
            alert('Você não pode gerar numeração automática no pré-cadastro!');
        });
        /*Mudar procedencia*/
    });

</script>

<!--Formulario-->
<div class="div-form-dialog" id="div-form-pre-cadastrar-documentos">

    <div class="row">
        <label class="label">*DIGITAL:</label>
        <span class="conteudo">
            <input type="text" id="DIGITAL_PRE_CADASTRAR_DOCUMENTOS" maxlength="7" onKeyPress="DigitaNumero(this);">
        </span>
    </div>

    <div class="row">
        <label class="label">*TIPO:</label>
        <span class="conteudo">
            <select class='FUNDOCAIXA1' id='TIPO_PRE_CADASTRAR_DOCUMENTOS'></select>
        </span>
    </div>

    <div class="row">
        <label class="label">*NUMERO:</label>
        <span class="conteudo">
            <input type="text" id="NUMERO_PRE_CADASTRAR_DOCUMENTOS" maxlength="50" onKeyUp="DigitaLetraSeguro(this)">
        </span>
        <img title="Gerar Numero" class="botao-auxiliar" id="botao-gerar-numero-pre-cadastrar-documentos" src="imagens/fam/add.png">
    </div>

    <div class="row">
        <label>*ORIGEM:</label>
        <span class="conteudo">
            <select id="ORIGEM_PRE_CADASTRAR_DOCUMENTOS"></select>
        </span>
        <img title="Filtrar" class="botao-auxiliar-fix-combobox" id="botao-filtro-origem-pre-cadastrar-documentos" src="imagens/fam/application_edit.png">
    </div>

    <div class="row">
        <label>*PROCEDENCIA:</label>
        <span class="conteudo">
            <select id="PROCEDENCIA_PRE_CADASTRAR_DOCUMENTOS">
                <option value="I">INTERNO</option>
                <option value="E">EXTERNO</option>
            </select>
        </span>
    </div>

    <div class="row">
        <label>*COMENTÁRIO:</label>
        <span class="conteudo">
            <textarea style="width: 400px;" id="COMENTARIO_PRE_CADASTRAR_DOCUMENTOS" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1"></textarea>
        </span>
    </div>

</div>

<!-- Filtros -->
<div id="box-filtro-origem-pre-cadastrar-documentos" class="box-filtro">
    <div class="row">
        <label>Tipo de Origem:</label>
        <div class="conteudo">
            <select id="TIPO_ORIGEM_PRE_CADASTRAR_DOCUMENTOS" class="FUNDOCAIXA1">
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
            <input id="FILTRO_ORIGEM_PRE_CADASTRAR_DOCUMENTOS" type="text" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
        </div>
    </div>
</div>

<div id="box-filtro-assunto-pre-cadastrar-documentos" class="box-filtro">
    <div class="row">
        <label>Assunto:</label>
        <div class="conteudo">
            <input id="FILTRO_ASSUNTO_PRE_CADASTRAR_DOCUMENTOS" type="text" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
        </div>
    </div>
</div>