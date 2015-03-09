;
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
/*Funcoes Javascript*/
function processos_selecionados(total_processos) {
    var cont = 0;
    var lim = total_processos;
    var parametros = Array();
    for (var i = 0; i < lim; i++) {
        if (document.getElementById('PROCESSO[' + i + ']').checked) {
            parametros[cont] = document.getElementById('PROCESSO[' + i + ']').value;//com jquery nao funcionou...
            cont++;
        }
    }
    return parametros;
}


function validar_digito_verificador_dois_digitos(e) {
    var s = FiltraCampo(e.value);
    var tam = s.length;
    if (tam == 15) {
        var num = s.substring(0, tam - 2);
        for (i = 0; i < 2; i++) {
            var soma = 0;
            var mult = num.length + 1;
            for (k = 0; k < num.length; k++)
                soma += num.substring(k, k + 1) * (mult - k);
            var mod11 = 11 - (soma % 11);
            if (mod11 < 10)
                dv_proc = "0" + mod11;
            else
                dv_proc = mod11 + "";
            var dv_proc = dv_proc.substring(1, 2);
            num += dv_proc;
        }

        if (num == s) {
            return true;
        } else {
            return false;
        }
    }
    return false;
}

function formatar_numero_processo(e) {
    var s = FiltraCampo(e.value);
    var tam = s.length;
    var ano_dig;

    if (tam > 15) {
        ano_dig = 4;
    } else {
        ano_dig = 2;
    }

    var r = s.substring(0, 5) + "." + s.substring(5, 11) + "/";
    r += s.substring(11, 11 + ano_dig) + "-" + s.substring(11 + ano_dig, 13 + ano_dig);

    if (tam < 6) {
        s = r.substring(0, tam);
    } else if (tam < 12) {
        s = r.substring(0, tam + 1);
    } else if (tam < 12 + ano_dig) {
        s = r.substring(0, tam + 2);
    } else {
        s = r.substring(0, tam + 3);
    }
    e.value = s;
    return s;
}

function validar_digito_verificador_processo(e) {
    /*Variaveis*/

    try {

        var s = FiltraCampo(e.value);
        var tam = s.length;

        if (tam == 15) {
            var num = s.substring(0, tam - 2);
            for (i = 0; i < 2; i++) {
                var soma = 0;
                var mult = num.length + 1;
                for (k = 0; k < num.length; k++)
                    soma += num.substring(k, k + 1) * (mult - k);
                var mod11 = 11 - (soma % 11);
                if (mod11 < 10)
                    dv_proc = "0" + mod11;
                else
                    dv_proc = mod11 + "";
                var dv_proc = dv_proc.substring(1, 2);
                num += dv_proc;
            }

            if (num == s) {
                return true;
            } else {
                return false;
            }
        }

        if (tam == 17) {
            num = s.substring(0, tam - 2);
            for (i = 0; i < 2; i++) {
                soma = 0;
                mult = num.length + 1;
                for (k = 0; k < num.length; k++) {
                    soma += num.substring(k, k + 1) * (mult - k);
                    mod11 = 11 - (soma % 11);
                    if (mod11 < 10) {
                        dv_proc = "0" + mod11;
                    } else {
                        dv_proc = mod11 + "";
                    }
                }
                dv_proc = dv_proc.substring(1, 2);
                num += dv_proc;
            }

            if (num == s) {
                return true;
            } else {
                return false;
            }
        }

        return false;
    } catch (e) {
        return false;
    }

}

function verificador_valido(e) {
    /*Variaveis*/
    //   var dv = false;
    var s = FiltraCampo(e.value);
    var tam = s.length;

    if (tam == 15) {
        var num = s.substring(0, tam - 2);
        for (i = 0; i < 2; i++) {
            var soma = 0;
            var mult = num.length + 1;
            for (k = 0; k < num.length; k++)
                soma += num.substring(k, k + 1) * (mult - k);
            var mod11 = 11 - (soma % 11);
            if (mod11 < 10)
                dv_proc = "0" + mod11;
            else
                dv_proc = mod11 + "";
            var dv_proc = dv_proc.substring(1, 2);
            num += dv_proc;
        }
        return num.substr(13, 15);
    }

    if (tam == 17) {
        num = s.substring(0, tam - 2);
        for (i = 0; i < 2; i++) {
            soma = 0;
            mult = num.length + 1;
            for (k = 0; k < num.length; k++) {
                soma += num.substring(k, k + 1) * (mult - k);
                mod11 = 11 - (soma % 11);
                if (mod11 < 10) {
                    dv_proc = "0" + mod11;
                } else {
                    dv_proc = mod11 + "";
                }
            }
            dv_proc = dv_proc.substring(1, 2);
            num += dv_proc;
        }
        return num.substr(15, 17);
    }
}

function marcar_todos_processos() {
    var valor = document.getElementById('marcadorP').checked;
    var lim = total_processos;
    for (var i = 0; i < lim; i++) {
        document.getElementById('PROCESSO[' + i + ']').checked = valor;
    }
}



/*Funcoes JQuery*/

/*Listar os vinculos dos processos*/
function jquery_listar_vinculacao_processo(numero_processo, reload) {
    popup('lista_vinculacao_processos.php?numero_processo=' + numero_processo, function() {
        if (reload) {
            oTableProcessos.fnDraw(false);
        }
    });
}
/*Cadastro e Autuacao de Processos*/

/*Validar se o interessado respeita a clausula que obriga o campo cnpj/cpf preenchidos apartir do assunto escolhido*/
function jquery_validar_assunto_interessado_obrigatorio(assunto, interessado) {

    var r = $.ajax({
        type: 'POST',
        url: 'modelos/processos/assunto.php',
        data: 'acao=interessado-obrigatorio&interessado=' + interessado + '&assunto=' + assunto,
        async: false,
        success: function() {
        },
        failure: function() {
        }
    }).responseText;

    r = eval('(' + r + ')');

    /* Tabela-Verdade */
    /* A - I - R */
    /* T - F - F */
    /* T - T - T */
    /* F - F - T */
    /* F - T - T */

    if (r.success == 'true') {
        if (r.obrigatorio == 'false') {
            return true;
        } else {
            return r.valido;
        }

    } else {
        throw 'Ocorreu um erro ao tentar validar a obrigatoriedade do cnpj/cpf do interessado com relacao ao assunto escolhido!\n[' + r.error + ']';
        return false;
    }

}

/*Abrir impressao de etiqueta*/
function jquery_etiqueta_processo(numero_processo) {
    var win = open('../modelo_etiqueta_processo.php?numero_processo=' + numero_processo, '', 'top=0,left=0,width=450,height=300,status=no,scrollbars=no,resizable=no');
    var tam1 = (window.screen.availWidth / 2) - (win.document.body.offsetWidth / 2) - (window.screen.availWidth * 0.01);
    var tam2 = (window.screen.availHeight / 4) - (win.document.body.offsetHeight / 2) - (window.screen.availHeight * 0.062);
    win.moveTo(tam1, tam2);
}

/*Validar os Cpfs e Cnpjs*/
function jquery_validar_cpf_cnpj(cpfcnpj) {
    switch (cpfcnpj.length) {
        case 14:
            return ValidaCPF(cpfcnpj);
            break;
        case 18:
            return ValidaCNPJ(cpfcnpj);
            break;
        default:
            return false;
            break;
    }
}
/*Carregar a descricao apartir no id*/
function jquery_get_assunto_processo(valor, campo) {
    var r = $.ajax({
        type: 'POST',
        url: 'modelos/processos/assunto.php',
        data: 'acao=get&valor=' + valor + '&campo=' + campo,
        async: false,
        success: function() {
        },
        failure: function() {
        }
    }).responseText;

    return eval(r);
}

/*Carregar a descricao apartir no id*/
function jquery_get_unidades(valor, campo) {
    var r = $.ajax({
        type: 'POST',
        url: 'modelos/unidades/unidades.php',
        data: 'acao=get&valor=' + valor + '&campo=' + campo,
        async: false,
        success: function() {
        },
        failure: function() {
        }
    }).responseText;

    return eval("(" + r + ")");
}

/*Carregar a descricao apartir no id*/
function jquery_get_origem_externa(valor, campo) {
    var r = $.ajax({
        type: 'POST',
        url: 'modelos/processos/origem.php',
        data: 'acao=get-origem-externa&origem=' + valor + '&campo=' + campo,
        async: false,
        success: function() {
        },
        failure: function() {
        }
    }).responseText;

    return eval('(' + r + ')');
}


/*Carregar o nome do interessado apartir do id*/
function jquery_get_interessado_processo(valor, campo) {
    var r = $.ajax({
        type: 'POST',
        url: 'modelos/processos/interessado.php',
        data: 'acao=get&valor=' + valor + '&campo=' + campo,
        async: false,
        success: function() {
        },
        failure: function() {
        }
    }).responseText;

    r = eval('(' + r + ')');

    return r;

}


/*Carregar formulario de Detalhes de Processos*/
function jquery_detalhar_processo(numero_processo, area_trabalho) {
    /*Ativar Preloader*/
    $('#progressbar').show();
    try {

        $.post("modelos/processos/processos.php", {
            acao: 'carregar',
            numero_processo: numero_processo
        },
        function(data) {
            try {
                $('#lista_modelo_termos').hide();
                if (data.success == 'true') {
                    if (data.processo.id_unid_area_trabalho != '' &&
                            data.processo.id_unid_area_trabalho != 0) {
                        // Processo está em alguma área de trabalho
                        if (data.processo.id_unid_area_trabalho == area_trabalho) {
                            // processo está na área de trabalho do usuário logado
                            $('#lista_modelo_termos').show();
                        }
                    }

                    $('#TIPO_ORIGEM_DETALHAR_PROCESSO').val(data.processo.procedencia);//verificar
                    $('#FAKE_NUMERO_DETALHAR_PROCESSO').val(data.processo.numero_processo);
                    $('#NUMERO_DETALHAR_PROCESSO').val(data.processo.numero_processo);

                    /*Assunto*/
                    $('#ASSUNTO_DETALHAR_PROCESSO').empty();
                    $('#ASSUNTO_DETALHAR_PROCESSO').append($('<option></option>').val(data.processo.assunto).html(jquery_get_assunto_processo(data.processo.assunto, 'assunto')));

                    $('#ASSUNTO_COMPLEMENTAR_DETALHAR_PROCESSO').val(data.processo.assunto_complementar);
                    $('#INTERESSADO_DETALHAR_PROCESSO').empty();
                    $('#INTERESSADO_DETALHAR_PROCESSO').append($('<option></option>').val(data.processo.interessado).html(jquery_get_interessado_processo(data.processo.interessado, 'interessado')));
                    $('#STATUS_PRAZO_DETALHAR_PROCESSO').attr('checked', data.processo.fg_prazo);

                    /*Data Autuacao*/
                    if (data.processo.dt_autuacao != '00/00/0000') {
                        $('#DATA_AUTUACAO_DETALHAR_PROCESSO').val(data.processo.dt_autuacao);
                    } else {
                        $('#DATA_AUTUACAO_DETALHAR_PROCESSO').val('');
                    }

                    /*Data Prazo*/
                    if (data.processo.dt_prazo != null) {
                        $('#DATA_PRAZO_DETALHAR_PROCESSO').val(data.processo.dt_prazo);
                        /*Notificacao do Prazo*/
                        if (data.processo.fg_prazo == true) {
                            jquery_get_dias_prazo(data.processo.dt_prazo, 'notificacao-prazo-detalhar-processo');
                        } else {
                            $('#notificacao-prazo-detalhar-processo').html('');
                        }
                    } else {
                        $('#DATA_PRAZO_DETALHAR_PROCESSO').val('');
                        $('#STATUS_PRAZO_DETALHAR_PROCESSO').attr('checked', false);
                        $('#notificacao-prazo-detalhar-processo').html('');
                    }

                    /*Alternar entre origem interna e externa*/
                    if (data.processo.procedencia == 1) {//interno = 1 , externo = 0
                        $('#ORIGEM_DETALHAR_PROCESSO').empty();
                        $('#ORIGEM_DETALHAR_PROCESSO').append($('<option></option>').val(data.processo.origem).html(jquery_get_unidades(data.processo.origem, 'nome')));
                    } else {
                        $('#ORIGEM_DETALHAR_PROCESSO').empty();
                        $('#ORIGEM_DETALHAR_PROCESSO').append($('<option></option>').val(data.processo.origem).html(jquery_get_origem_externa(data.processo.origem, 'origem')));
                    }


                    $('#progressbar').hide();
                    $('#div-form-detalhar-processos').dialog('open');
                } else {
                    $('#progressbar').hide();
                    alert(data.error);
                }
            } catch (e) {
                $('#progressbar').hide();
                alert('Ocorreu um erro ao tentar carregar as informacoes do processo!\n[' + e + ']');
            }
        }, "json");


    } catch (e) {
        $('#progressbar').hide();
        alert('Ocorreu um ao tentar carregar as informacoes do processo!\n[' + e + ']');
    }
}