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

namespace Documento\Imagem;

class DocumentoImagemAggregator {

    protected $_digital = null;
    protected $_arrDocumentoImagem = array();

    public function setDigital( $parDigital = null )
    {
        if($parDigital){
            $this->_digital = $parDigital;
        }else{
            throw new \Exception("DocumentoImagemAggregator::setDigital() - Digital {$parDigital} inválida!");
        }
    }
    
    /**
     * Informa o Array de Documentos Imagens que compõem este Agregador
     * @param array $parDocumentoImagem
     */
    public function setArrDocumentoImagem( array $parDocumentoImagem )
    {
        $this->_arrDocumentoImagem = $parDocumentoImagem;
    }
    
    /**
     * Retorna o array de Documentos Imagem
     * @return array Array de Documentos Imagem
     * @param array $parDocumentoImagem
     */
    public function getArrDocumentoImagem()
    {
        return $this->_arrDocumentoImagem;
    }
    
    /**
     * Retorna as Imagens solicitadas (Repassa a solicitação ao
     * Documento Imagem referente)
     * @param array $parPage
     * @return array(IFormato)
     */
    public function getImagesToRead( $parPage )
    {
        //Varre array de DocumentoImagem e retorna Imagem para leitura solicitada
        foreach ($this->_arrDocumentoImagem as $documentoImagem) {
            $rowset = $documentoImagem->getRowset();
            foreach ($rowset as $row) {
                if( preg_match('/^'. $row['MD5'] .'/', $parPage[0]) ){
                    return $documentoImagem->getImagesToRead( $parPage );
                }
            }
        }
        //Se não retornar imagem solicitada, emite Exception
        throw new \Exception("DocumentoImagemAggregator::getImagesToRead() - Imagem não encontrada na Digital-Página {$this->_digital}-{$parPage}");
    }
    
    /**
     * Retorna lista com endereço das Páginas listadas por todos os 
     * Documentos Imagens que compõem este Agregador
     * @return array
     */
    public function getImageList()
    {
        $images = array();
        foreach ($this->_arrDocumentoImagem as $documentoImagem) {
            $images = array_merge($images, $documentoImagem->getImageList());            
        }
        return $images;
    }
    
    /**
     * Realiza Merge entre os PDFs dos Documentos Imagens contidos no Agregador
     * @return Formato\FormatoPDF PDF da Digital
     */
    public function getPDF()
    {
        //Se agregador contiver somente um DocumentoImagem, retorna o PDF dele
        $nrDocumentosImagem = count($this->_arrDocumentoImagem);        
        if($nrDocumentosImagem == 1){
            $pdf = $this->_arrDocumentoImagem[0]->getPDF();
            return $pdf;
        }else{//Senão, realiza merge e retorna
            $documentoImagemTmp = $this->_arrDocumentoImagem[0];
            $pdfMergedAbsoluteFilename = $documentoImagemTmp->getCachePath() . 'AGREGADOR_MERGE';
            $rowset = $documentoImagemTmp->getRowset();
            $digital = $rowset[0]['DIGITAL'];

            $arrPDFs = array();
            $i=0;
            foreach ( $this->_arrDocumentoImagem as $documentoImagem ){            
                $pdfDocAbsoluteFilename = sprintf("{$pdfMergedAbsoluteFilename}_PART_%04d.pdf", ++$i );
                $arrPDFs[] = $pdfDocAbsoluteFilename;

                //Salva em arquivos PDFs temporários
                file_put_contents( $pdfDocAbsoluteFilename, $documentoImagem->getPDF()->getData() );
            }
            //Define comando inicial
            $cmd = "gs -q -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -sOutputFile={$pdfMergedAbsoluteFilename}.pdf ";
            foreach ($arrPDFs as $pdfFilename) {
                $cmd .= $pdfFilename." ";
            }
            shell_exec($cmd);

            foreach ($arrPDFs as $pdfFilename) {
              //  unlink($pdfFilename);
            }

            $absoluteFileName = $pdfMergedAbsoluteFilename . '.pdf';
            return new Formato\FormatoPDF( $absoluteFileName, $digital );
        }

    }
    
}