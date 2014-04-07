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

/**
 * @author Rogerio Alves <ralves.moura@gmail.com>
 */

namespace Documento\Imagem\Formato;

interface IFormato
{
    const FORMATO_RETRATO=1;
    const FORMATO_PAISAGEM=2;

    /**
     * Realiza a mostragem no navegador do Formato que implementá-la
     */
    public function show();

    /**
     * Escreve somente endereço absoluto do arquivo
     */
    public function __toString();
            
    /**
     * Realiza rotação da imagem original para formato Retrato com mesmas dimensões
     * @param IFormato::FORMAT_RETRATO | IFormato::FORMAT_RETRATO $parFormato
     * @throws \Exception
     */
    public function rotaciona( $parFormato=0 );
    
    /**
     * Retorna conteúdo do arquivo
     */
    public function getData();
    
}
