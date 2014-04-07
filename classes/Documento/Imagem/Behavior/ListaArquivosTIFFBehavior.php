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

class ListaArquivosTIFFBehavior implements IListaArquivosBehavior 
{
    protected $_documentoImagem;
    
    protected $_qtdPaginas = 0;
    
    protected $_boolFileInfo = false;    
    
    protected $_thumbs = array();
    protected $_imagens = array();
    
    public function __construct( IDocumentoImagem &$parDocumentoImagem )
    {
        $this->_documentoImagem = $parDocumentoImagem;
    }
    
    public function getListaLeitura()
    {
        return $this->_documentoImagem->getRowSet();
    }

    public function getListaThumbs()
    {
        $dirTo = $this->_documentoImagem->getCachePath();
        $rowset = $this->_documentoImagem->getRowSet();

        $this->_thumbs = array();
        
        foreach ($rowset as $row) {
            $this->_thumbs[] = "{$dirTo}{$row['MD5']}_thumb.png";
        }

        return $this->_thumbs;
    }

    /* @todo: Implementar carregar quantidade de páginas do banco de dados */
    public function getQuantidadePaginas( $parForce=false )
    {
        return count( $this->_documentoImagem->getRowSet() );
    }
}
