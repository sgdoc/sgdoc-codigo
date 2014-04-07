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
function DigitaNumero(campo)
{
    var valid    = "0123456789";
    var numerook = "";
    var temp;
  
    
    for (var i=0; i< campo.value.length; i++) {
        temp = campo.value.substr(i, 1);
        if (valid.indexOf(temp) != "-1")
            numerook = numerook + temp;
    }
   
    campo.value = numerook;

}

function DigitaNumeroProcesso(campo)
{
    var valid    = "0123456789.-/";
    var numerook = "";
    var temp;
  
    
    for (var i=0; i< campo.value.length; i++) {
        temp = campo.value.substr(i, 1);
        if (valid.indexOf(temp) != "-1")
            numerook = numerook + temp;
    }
   
    campo.value = numerook;

}

function FormatoMoeda(campo)
{
   
    var Moeda = SoNumero(campo.value);
    var MoedaAux = '';
    var campo1 = campo.value;
    var ponto = 3;

   
    for (var i=Moeda.length; i > 0; i--) {
     
        if ((i == Moeda.length - 2) && (Moeda.length > 2)){
         
            MoedaAux =  "," + MoedaAux;
            ponto = 3;
        }
        if ((ponto == 0) && (Moeda.length > 5)){
            MoedaAux =  '.' + MoedaAux;
            ponto = 3;
        }               
        MoedaAux = Moeda.substr(i-1,1) + MoedaAux;
        ponto --;
    }
    campo.value = MoedaAux;

}


/*Jquery*/

function isNumber(val) {
    return /^-?((\d+\.?\d?)|(\.\d+))$/.test(val);
}

function isInteger(val) {
    return /^\+?[0-9]*\.?[0-9]+$/.test(val);
}