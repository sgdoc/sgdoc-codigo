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

function languageManager() {
    this.lang = "pt-Br";
		
    this.load = function(lang) {
        this.lang = lang
        this.url = location.href.substring(0, location.href.lastIndexOf('interfaces/'));
        document.write("<script language='javascript' src='"+this.url+"/plugins/tree/js/langs/"+this.lang+".js'></script>");
    }
	
    this.addIndexes= function() {
        for (var n in arguments[0]) {
            this[n] = arguments[0][n];
        }
    }
}