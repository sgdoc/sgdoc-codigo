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
function ValidaEmail(email){
	
    var CaracValid = "_-.@0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM";
    var tam = email.value.length;
    var valido = true;
    var pos1 = email.value.indexOf('@');
    var pos2 = email.value.indexOf('.');
    var pos3 = email.value.indexOf('.',pos1);
		
    for (i = 0;  i < tam;  i++)
    {
        ch = email.value.charAt(i);
        for (j = 0;  j < CaracValid.length;  j++)
            if (ch == CaracValid.charAt(j))
                break;
        if (j == CaracValid.length)
        {
            valido = false;
            break;
        }
    }
			
    if((pos1 == -1) || (pos1 == 0) )
    {
        valido = false;
    }
    else
    {
        if(email.value.indexOf('@',pos1+1)!=-1)
        {
            valido = false;
        }
    }
		
    if(tam<=pos1+1)
    {
        valido = false;
    }
		
    if(pos3 == -1)
    {
        valido = false;
    }
		
    if(tam<=pos3+1)
    {
        valido = false;
    }
		
    if(valido)
    {
        return(true);
    }
    else
    {
        return(false);
    }
}

function ValidarEmailICMBio(id){

    var email = document.getElementById(id);
    var CaracValid = "_-.@0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM";
    var tam = email.value.length;
    var valido = true;
    var pos1 = email.value.indexOf('@');
    var pos2 = email.value.indexOf('.');
    var pos3 = email.value.indexOf('.',pos1);

    for (i = 0;  i < tam;  i++){
        ch = email.value.charAt(i);
        for (j = 0;  j < CaracValid.length;  j++)
            if (ch == CaracValid.charAt(j))
                break;
        if (j == CaracValid.length)
        {
            valido = false;
            break;
        }
    }

    if((pos1 == -1) || (pos1 == 0) ){
        valido = false;
    } else{
        if(email.value.indexOf('@',pos1+1)!=-1){
            valido = false;
        }
    }

    if(tam<=pos1+1){
        valido = false;
    }

    if(pos3 == -1){
        valido = false;
    }

    if(tam<=pos3+1){
        valido = false;
    }

    if(valido){
        if(email.value.indexOf('@icmbio.gov.br')==-1){
            return false;
        }else{
            return true;
        }
    } else{
        return false;
    }
}