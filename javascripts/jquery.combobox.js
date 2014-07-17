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
 * */;
$.fn.combobox = function(url, params) {
    var target = '#' + $(this).attr('id');
    return this.each(function() {
        $(this).ready(function() {
            var ajaxCallback = function(data) {
                $(target).html("");
                try {
                    if (data) {
                        data = eval(data);
                        for (i = 0; i < data.length; i++) {
                            for (key in data[i]) {
                                $(target).get(0).add(new Option(data[i][key], [key]), document.all ? i : null);
                            }
                        }
                    }
                } catch (e) {
                    /*@supress*/
                }
            };
            $.post(url, (!params) ? {} : params, ajaxCallback);
        });
    });
};

/**
 * 
 **/
;
$.fn.comboboxWithCallBack = function(url, params, callback) {
    var target = '#' + $(this).attr('id');
    return this.each(function() {
        $(this).ready(function() {
            var ajaxCallback = function(data) {
                $(target).html("");
                try {
                    if (data) {
                        data = eval(data);
                        for (i = 0; i < data.length; i++) {
                            for (key in data[i]) {
                                $(target).get(0).add(new Option(data[i][key], [key]), document.all ? i : null);
                            }
                        }
                        callback.call(this);
                    }
                } catch (e) {
                    /*@supress*/
                }
            };
            $.post(url, (!params) ? {} : params, ajaxCallback);
        });
    });
};


/*Debugger*/
/*Plugin prazo*/

$.fn.prazo = function(options) {

    $('#' + options.targetStatus).change(function() {
        alert('atuo');
        if (!$(this).attr('checked')) {
            $('#' + target_message).html("");
        }
    });

    $(this).change(function() {
        /*Validar o Status do prazo*/
        if ($('#' + options.targetStatus).attr('checked')) {
            jquery_get_dias_prazo($(this).val(), options.targetMessage);


        } else {
            $('#' + target_message).html("");
        }

    });

};

function jquery_get_dias_prazo(data_prazo/*00/00/0000*/, target_message/*idDiv*/) {
    try {
        /*Preparar data de hoje*/
        hoje = new Date()
        dia = hoje.getDate()
        mes = hoje.getMonth()
        ano = hoje.getFullYear()
        if (dia < 10)
            dia = "0" + dia
        if (ano < 2000)
            ano = "19" + ano

        /*Variaveis da data*/
        var data = data_prazo.split('/');
        var hoje = new Date(ano, mes + 1, dia);
        var prazo = new Date(data[2], data[1], data[0]);
        var dias = (prazo - hoje) / (1000 * 60 * 60 * 24);

        /*Logica de formatacao do prazo*/
        if (dias <= 3) {
            /*Prazos entre +3 á (-)infinito*/
            $('#' + target_message).addClass('notificacao-prazo-vermelho');
            $('#' + target_message).removeClass('notificacao-prazo-amarelo');
            $('#' + target_message).removeClass('notificacao-prazo-verde');
            if (dias > 0) {
                $('#' + target_message).html("Atenção! este prazo vence em " + dias + " dia(s).");
            } else if (dias < 0) {
                $('#' + target_message).html("Atenção! este prazo venceu a " + (dias * -1) + " dia(s).");
            } else {
                $('#' + target_message).html("Atenção! este prazo vence hoje.");
            }

        } else if (dias >= 4 && dias <= 7) {
            /*Prazos entre +4 á +7*/
            $('#' + target_message).addClass('notificacao-prazo-amarelo');
            $('#' + target_message).removeClass('notificacao-prazo-vermelho');
            $('#' + target_message).removeClass('notificacao-prazo-verde');
            $('#' + target_message).html("Atenção! este prazo vence em " + dias + " dia(s).");
        } else {
            /*Prazos entre +8 á +(infinito)*/
            $('#' + target_message).addClass('notificacao-prazo-verde');
            $('#' + target_message).removeClass('notificacao-prazo-vermelho');
            $('#' + target_message).removeClass('notificacao-prazo-amarelo');
            $('#' + target_message).html("Atenção! este prazo vence em " + dias + " dia(s).");
        }
    } catch (e) {

    }
}