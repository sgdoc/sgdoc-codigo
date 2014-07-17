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

namespace Documento\Imagem\Behavior;

use Documento\Imagem\IDocumentoImagem;

interface IListaArquivosBehavior 
{
    public function __construct( IDocumentoImagem &$parDocumentoImagem );
    
    /**
     * Lista todos os Thumbs
     * @return array(string) Caminho absoluto das imagens Thumbs
     */
    public function getListaThumbs();
    
    /**
     * Lista todos os arquivos com respectivas permissões e outras informações
     * @return array(Rowset\IRowset) Lista de IRowset
     */
    public function getListaLeitura();
    
    /**
     * Retorna a quantidade de páginas do documento
     * @return integer Quantidade de pàginas
     */
    public function getQuantidadePaginas( $parForce = false );
    
//    possivel ordenar?
}
