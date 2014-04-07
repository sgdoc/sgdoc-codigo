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

interface IGeraCacheBehavior 
{
    public function __construct( IDocumentoImagem &$parDocumentoImagem );
    
    /**
     * Realiza exportação para Cache Thumbs e rotaciona quando necessário
     * Formato PNG, Tamanho A10, Resolução 50ppp, Mapa de cores GrayScale
     */
    public function geraThumbs();
    
    /**
     * Converte Páginas solicitadas em tamanho leitura, rotaciona quando
     * necessário
     * Formato PNG, Tamanho A4, Resolução 200ppp, Mapa de Cores 16milhões de cores
     * 
     * @param array(int) $parPage
     * @return array(IFormato) Lista de IFormato, contendo todas as imagens geradas
     */
    public function geraLeitura( array $parPages );
    
}
