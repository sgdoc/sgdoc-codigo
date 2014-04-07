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

    $(document).ready(function() {
        /* Alterar */
        $('#div-alterar-assunto-doc').dialog({
            title: 'Alterar Assunto de Documento',
            autoOpen: false,
            resizable: false,
            modal: true,
            width: 600,
            autoHeight: true,
            buttons: {
                Salvar:function(){
                    var validou = jquery_validar_assunto_doc('ALTERAR');
                    if(validou == true){
                        var c = confirm('Você tem certeza que deseja salvar este assunto?');
                        if(c){
                            $.post("modelos/administrador/assuntos_documentos.php", {
                                acao: 'alterar',
                                id: $('#ID_DOC_ALTERAR').val(),
                                assunto: $('#ASSUNTO_DOC_ALTERAR').val()
                            },
                            function(data){
                                if(data.success == 'true'){
                                    $('#div-alterar-assunto-doc').dialog("close");
                                    alert(data.message);
                                    $("#tabs").tabs('select', 0);
                                    oTabelaAssuntosDoc.fnDraw(false);
                                }else{
                                    alert('Ocorreu um erro ao tentar salvar as informacoes do assunto!\n['+data.error+']');
                                }
                            }, "json");
                        }
                    }else{
                        alert(validou);
                    }
                }
            }
        });

        /* Cadastrar */
        $('#div-cadastrar-assunto-doc').dialog({
            autoOpen: false,
            resizable: false,
            modal: true,
            width: 600,
            autoHeight: true,
            open: function(event, ui) {
                $('#ASSUNTO_DOC_CADASTRAR').val('');
            },
            buttons: {
                Salvar: function() {
                    var validou = jquery_validar_assunto_doc('CADASTRAR');
                    if (validou == true){
                        if(confirm('Você tem certeza que deseja salvar este assunto agora?')){
                            $.post("modelos/administrador/assuntos_documentos.php", {
                                acao: 'cadastrar',
                                assunto: $('#ASSUNTO_DOC_CADASTRAR').val()
                            },
                            function(data){
                                if(data.success == 'true'){
                                    $('#div-cadastrar-assunto-doc').dialog("close");
                                    alert('Assunto cadastrado com sucesso!');
                                    $("#tabs").tabs('select', 0);
                                    oTabelaAssuntosDoc.fnDraw(false);
                                }else{
                                    alert('Ocorreu um erro ao tentar cadastrar um novo assunto!\n['+data.error+']');
                                }
                            }, "json");
                        }
                    }else{
                        alert(validou);
                    }
                }
            }
        });

        /*Listeners*/
        $('#botao-novo-assunto-doc').click(function(){
            $('#div-cadastrar-assunto-doc').dialog('open');
        });

        /*Carregar Combos*/
    });
    
    /*Functions*/
    function jquery_validar_assunto_doc(formulario){
        if($('#ASSUNTO_DOC_'+formulario).val())
        {
            // Foi tudo preenchido, fazer verificação ajax para validar se pode cadastrar ou não
            $.ajaxSetup({async:false});
            var retorno = 0;
            $.post("modelos/administrador/assuntos_documentos.php", {
                acao: 'unique',
                id: $('#ID_DOC_'+formulario).val(),
                assunto: $('#ASSUNTO_DOC_'+formulario).val()
            },
            function(data){
                if(data.success == 'true'){
                    retorno = true;
                }else{
                    retorno = data.error;
                }
            },
            "json");
            return retorno;
        }else{
            return 'Campo(s) obrigatorio(s) em branco ou preenchido(s) de forma invalida!';
        }
    }

    function jquery_detalhar_docs(id){
        $.post("modelos/administrador/assuntos_documentos.php", {
            acao: 'get',
            valor: id,
            campo: '*'
        },
        function(data){
            if(data.success == 'true'){
                $('#ID_DOC_ALTERAR').val(data.id);
                $('#identificador_doc').val(data.id);
                $('#ASSUNTO_DOC_ALTERAR').val(data.assunto);
                $('#div-alterar-assunto-doc').dialog('open');
            }else{
                alert('Ocorreu um erro ao tentar detalhar as informacoes do assunto!');
            }
        }, "json");

    }
    
    function jquery_alterar_homologacao_doc(id, status){
        $('#progressbar').show();
        $.post("modelos/administrador/assuntos_documentos.php", 
        {
            acao: 'alterar-homologacao',
            id: id,
            status: parseInt(status)
        },
        function(data){
            $('#progressbar').hide();
            if(data.success == 'true'){
                oTabelaAssuntosDoc.fnDraw(false);
            } else {
                alert('Ocorreu um erro ao tentar alterar a homologação do assunto!');
            }
        },"json");        
    }
</script>

<!--Detalhar-->
<div id="div-alterar-assunto-doc" class="div-form-dialog" title="Alterar Assunto de Documento">
    <fieldset>
        <label>Informações Principais</label>
        <input id="ID_DOC_ALTERAR" type="hidden" value="" />
        <div class="row">
            <label class="label">ID:</label>
            <span class="conteudo">
                <input type="text" readonly="readonly" disabled="disabled" class="FUNDOCAIXA1" id="identificador_doc" value="">
            </span>
        </div>
        <div class="row">
            <label class="label">*ASSUNTO:</label>
            <span class="conteudo">
                <input type="text" class="FUNDOCAIXA1" id="ASSUNTO_DOC_ALTERAR" onkeyup="DigitaLetraSeguro(this)">
            </span>
        </div>
    </fieldset>
</div>

<!--Cadastrar-->
<div id="div-cadastrar-assunto-doc" class="div-form-dialog" title="Novo Assunto de Documento">
    <fieldset>
        <label>Informações Principais</label>
        <div class="row">
            <label class="label">*ASSUNTO:</label>
            <span class="conteudo">
                <input type="text" class="FUNDOCAIXA1" id="ASSUNTO_DOC_CADASTRAR" onkeyup="DigitaLetraSeguro(this)">
            </span>
        </div>
    </fieldset>
</div>

