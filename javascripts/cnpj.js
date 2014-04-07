;/*
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
function ValidaCNPJ(campo){
    var CNPJ = null;

    if(typeof(campo)=='string'){
        CNPJ = campo;
    }else{
        CNPJ = campo.value;
    }

    if (CNPJ) {
      
        while (CNPJ.indexOf(".") != -1)
            CNPJ = CNPJ.replace(".","");
        while (CNPJ.indexOf("-") != -1)
            CNPJ = CNPJ.replace("-","");
        while (CNPJ.indexOf(" ") != -1)
            CNPJ = CNPJ.replace(" ","");
        while (CNPJ.indexOf("/") != -1)
            CNPJ = CNPJ.replace("/","");

        var cnpjCalc = CNPJ.substr(0,12);
        var cnpjSoma = 0;
        var cnpjDigit = 0;
        var digit = "";
  
        for (i = 0;  i < 4;  i++) {
            cnpjSoma = cnpjSoma + parseInt(cnpjCalc.charAt(i)) * (5 - i);
        }
  
        for (i = 0;  i < 8;  i++) {
            cnpjSoma = cnpjSoma + parseInt(cnpjCalc.charAt(i+4)) * (9 - i);
        }
 
        cnpjDigit = 11 - cnpjSoma%11;

        if ((cnpjDigit == 10) || (cnpjDigit == 11)){
            cnpjCalc = cnpjCalc + "0";
        }
        else {
            digit = digit + cnpjDigit;
            cnpjCalc = cnpjCalc + (digit.charAt(0));
        }
  
        cnpjSoma = 0;
  
        for (i = 0;  i < 5;  i++){
            cnpjSoma = cnpjSoma + parseInt(cnpjCalc.charAt(i)) * (6 - i);
        }
  
        for (i = 0;  i < 8;  i++) {
            cnpjSoma = cnpjSoma + parseInt(cnpjCalc.charAt(i+5)) * (9 - i);
        }

        cnpjDigit = 11 - cnpjSoma%11;
    
        if ((cnpjDigit == 10) || (cnpjDigit == 11)) {
            cnpjCalc = cnpjCalc + "0";
        }
        else {
            digit = "";
            digit = digit + cnpjDigit;
            cnpjCalc = cnpjCalc + (digit.charAt(0))
        }
  
        if (CNPJ != cnpjCalc) {
            return false;
        }
        return true;
    }else{
        return false;
    }
}


function MascaraCNPJ(campo,w)
{
    var CNPJ = SoNumero(campo.value);
    var CNPJAux = '';
    var campo1 = campo.value;

    if (w.keyCode == 8) {
        if (campo1.length == 2 || campo1.length == 6 || campo1.length == 10 || campo1.length == 15) {
            CNPJ = CNPJ.substr(0,CNPJ.length -1);
        }
    }
    
    if (CNPJ.length < 15) {
        for (var i=0; i<CNPJ.length; i++) {
            CNPJAux = CNPJAux + CNPJ.substr(i,1);
            if (i == 1 || i == 4) {
                CNPJAux = CNPJAux + ".";
            }
            if (i == 7) {
                CNPJAux = CNPJAux + "/";
            }
            if (i == 11) {
                CNPJAux = CNPJAux + "-";
            }
        }
        campo.value = CNPJAux;
    }
    else {
        campo.value = campo.value.substr(0,18);
    }    
}


function CNPJsemMascara(campo)
{
    if (campo.value.length > 14)
        campo.value = campo.value.substring(0,campo.value.length -1);
    else
        campo.value = SoNumero(campo.value);
}


function DesmascaraCNPJ(campo)
{
    campo.value = SoNumero(campo.value);
}	



function FormataCpf(e,cpf) {
    var s = "";
    if(e)
        s = FiltraCampo(e.value);
    else
        s = FiltraCampo(cpf );

    tam =  s.length;
    r = s.substring(0,3) + "." + s.substring(3,6) + "." + s.substring(6,9)
    r += "-" + s.substring(9,11);
    if ( tam < 4 )
        s = r.substring(0,tam);
    else if ( tam < 7 )
        s = r.substring(0,tam+1);
    else if ( tam < 10 )
        s = r.substring(0,tam+2);
    else
        s = r.substring(0,tam+3);
    if( e ) {
        e.value = s;
    }
    return s;
}

function FormataCnpj(e) {
    var s = "";
    var r = "";

    s = FiltraCampo(e.value);
    tam =  s.length;
    r = s.substring(0,2) + "." + s.substring(2,5) + "." + s.substring(5,8)
    r += "/" + s.substring(8,12) + "-" + s.substring(12,14);
    if ( tam < 3 )
        s = r.substring(0,tam);
    else if ( tam < 6 )
        s = r.substring(0,tam+1);
    else if ( tam < 9 )
        s = r.substring(0,tam+2);
    else if ( tam < 13 )
        s = r.substring(0,tam+3);
    else
        s = r.substring(0,tam+4);
    e.value = s;
    return s;
}


function FormataCpfCnpj(e) {
    var s = "";
    s = FiltraCampo(e.value);
    tam =  s.length;
    if (tam < 12 ) {
        FormataCpf(e)
    }
    else
    {
        FormataCnpj(e);
    }
}
	

function valida_cpf(cpf)
{
    var numeros, digitos, soma, i, resultado, digitos_iguais;
    digitos_iguais = 1;
    if (cpf.length < 11)
        return false;
    for (i = 0; i < cpf.length - 1; i++)
        if (cpf.charAt(i) != cpf.charAt(i + 1))
        {
            digitos_iguais = 0;
            break;
        }
    if (!digitos_iguais)
    {
        numeros = cpf.substring(0,9);
        digitos = cpf.substring(9);
        soma = 0;
        for (i = 10; i > 1; i--)
            soma += numeros.charAt(10 - i) * i;
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(0))
            return false;
        numeros = cpf.substring(0,10);
        soma = 0;
        for (i = 11; i > 1; i--)
            soma += numeros.charAt(11 - i) * i;
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(1))
            return false;
        return true;
    }
    else
        return false;
}



function valida_cnpj(cnpj)
{
    var numeros, digitos, soma, i, resultado, pos, tamanho, digitos_iguais;
    digitos_iguais = 1;
    if (cnpj.length < 14 && cnpj.length < 15)
        return false;
    for (i = 0; i < cnpj.length - 1; i++)
        if (cnpj.charAt(i) != cnpj.charAt(i + 1))
        {
            digitos_iguais = 0;
            break;
        }
    if (!digitos_iguais)
    {
        tamanho = cnpj.length - 2
        numeros = cnpj.substring(0,tamanho);
        digitos = cnpj.substring(tamanho);
        soma = 0;
        pos = tamanho - 7;
        for (i = tamanho; i >= 1; i--)
        {
            soma += numeros.charAt(tamanho - i) * pos--;
            if (pos < 2)
                pos = 9;
        }
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(0))
            return false;
        tamanho = tamanho + 1;
        numeros = cnpj.substring(0,tamanho);
        soma = 0;
        pos = tamanho - 7;
        for (i = tamanho; i >= 1; i--)
        {
            soma += numeros.charAt(tamanho - i) * pos--;
            if (pos < 2)
                pos = 9;
        }
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(1))
            return false;
        return true;
    }
    else
        return false;
}