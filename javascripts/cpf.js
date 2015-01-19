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
function ValidaCPF(campo){
    var CPF = null;

    if(typeof(campo)=='string'){
        CPF = campo;
    }else{
        CPF = campo.value;
    }  

    if(CPF){

        while (CPF.indexOf(".") != -1)
            CPF = CPF.replace(".","");
        while (CPF.indexOf("-") != -1)
            CPF = CPF.replace("-","");
        while (CPF.indexOf(" ") != -1)
            CPF = CPF.replace(" ","");

        var cpfCalc = CPF.substr(0,9);
        var cpfSoma = 0;
        var cpfDigit = 0;
        var digit = "";
    
        for (i = 0; i < 9; i++) {
            cpfSoma = cpfSoma + parseInt(cpfCalc.charAt(i)) * (10 - i)
        }
  
        cpfDigit = 11 - cpfSoma%11;
    
        if (cpfDigit > 9) {
            cpfCalc = cpfCalc + "0";
        }
        else {
            digit = digit + cpfDigit;
            cpfCalc = cpfCalc + digit.charAt(0);
        }
  
        cpfSoma = 0;
  
        for (i = 0; i < 10; i++) {
            cpfSoma = cpfSoma + parseInt(cpfCalc.charAt(i)) * (11 - i)
        }
  
        cpfDigit = 11 - cpfSoma%11;
  
        if (cpfDigit > 9) {
            cpfCalc = cpfCalc + "0";
        }
        else {
            digit = "";
            digit = digit + cpfDigit;
            cpfCalc = cpfCalc + digit.charAt(0);
        }
   
        if (CPF != cpfCalc){
            return false;
        }

        return true;
    }else{
        return false;
    }
      
}

jQuery.fn.clearCPF=function(a){
    var b=this;
    var c=b.val().replace('.','').replace('.','').replace('-','');
    return c;
}

jQuery.fn.validateCPF=function(a){
    var b=this;
    var c=b.val().replace('.','').replace('.','').replace('-','');
    if(c.length!=11){
        return false
    }else{
        var d=/1{11}|2{11}|3{11}|4{11}|5{11}|6{11}|7{11}|8{11}|9{11}|0{11}/;
        if(d.test(c)){
            return false
        }else{
            var x=0;
            var y=0;
            var e=0;
            var f=0;
            var g=0;
            var h="";
            var k="";
            var l=c.length;
            x=l-1;
            for(var i=0;i<=l-3;i++){
                y=c.substring(i,i+1);
                e=e+(y*x);
                x=x-1;
                h=h+y
            }
            f=11-(e%11);
            if(f==10){
                f=0
            }
            if(f==11){
                f=0
            }
            k=c.substring(0,l-2)+f;
            x=11;
            e=0;
            for(var j=0;j<=(l-2);j++){
                e=e+(k.substring(j,j+1)*x);
                x=x-1
            }
            g=11-(e%11);
            if(g==10){
                g=0
            }
            if(g==11){
                g=0
            }
            if((f+""+g)==c.substring(l,l-2)){
                return true
            }else{
                return false
            }
        }
    }
};

function MascaraCPF(campo,w)
{
    var CPF = SoNumero(campo.value);
    var CPFAux = '';
    var campo1 = campo.value;

    if (w.keyCode == 8) {
        if (campo1.length == 3 || campo1.length == 7 || campo1.length == 11) {
            CPF = CPF.substr(0,CPF.length-1);
        }
    }
     
    if (CPF.length < 12) {
     
        for (var i=0; i<CPF.length; i++) {
            CPFAux = CPFAux + CPF.substr(i,1);
            if (i == 2 || i == 5) {
                CPFAux = CPFAux + ".";
            }
            if (i == 8) {
                CPFAux = CPFAux + "-";
            }
        }
        campo.value = CPFAux;
    }
    else {
        campo.value = campo.value.substr(0,14);
    }
}


function CPFsemMascara(campo)
{
    if (campo.value.length > 11)
        campo.value = campo.value.substring(0,campo.value.length -1);
    else
        campo.value = SoNumero(campo.value);
}


function DesmascaraCPF(campo)
{
    campo.value = SoNumero(campo.value);
}