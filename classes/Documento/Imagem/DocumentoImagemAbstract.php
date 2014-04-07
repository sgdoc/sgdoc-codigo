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

abstract class DocumentoImagemAbstract implements IDocumentoImagem
{
    const IMG_WIDTH = 595; // As imagens são convertidas com esta largura.
    const IMG_HEIGHT = 842; // As imagens são convertidas com esta altura.
    
    const CONFIDENCIAL_IMAGE_RELATIVE_FILENAME = 'imagens/documento_confidencial_view.jpg';
    const INEXISTENTE_IMAGE_RELATIVE_FILENAME = 'imagens/imagem_quebrada_a4.jpg';
    
    /**
     * @var IGeraCacheBehavior 
     */
    protected $_geraCacheBehavior;

    /**
     * @var IListaArquivosBehavior 
     */
    protected $_listaArquivosBehavior;

    protected $_usuario;
    protected $_unidade;
    
    protected $_digital;
    protected $_tmpName;
    protected $_originalName;
    protected $_img_name;
    protected $_uploadPath;
    protected $_cachePath;
    
    protected $_imagens = array();
    protected $_thumbs = array();

    protected $_rowset = null;

    protected $_conn = null;

    public function __construct() 
    {
        $controller = \Controlador::getInstance();
        $this->_usuario = $controller->usuario->ID;
        $this->_unidade = $controller->usuario->ID_UNIDADE;

        $this->_conn = $controller->getConnection()->connection;
    }
    
    public function setDigital( $parDigital )
    {
        $this->_digital = $parDigital;
    }
    
    public function getUploadPath()
    {
        if( !$this->_uploadPath ){
            $this->_setUploadPath();
        }
        return $this->_uploadPath;
    }
    public function getCachePath()
    {
        if( !$this->_cachePath ){
            $this->_setCachePath();
        }
        return $this->_cachePath;
    }
    public function getImageName()
    {
        return $this->_img_name;
    }    
    public function getRowSet()
    {
        return $this->_rowset;
    }
    
    /**
     * Verifica se o usuário atualmente logado é autorizado a ver/manipular
     * documentos confidenciais. Caso o documento em questão não seja Confidencial
     * o valor de retorno é verdadeiro.
     * Para o usuário possuir permissão, ele pode ser expressamente autorizado 
     * ou ser um administrador do sistema.
     * @return boolean 
     */
    protected function _isConfidentialAuthorized()
    {
        //Se nao for confidencial, qualquer um é autorizado a ver
        if(!$this->_isConfidentialDocument()){
            return true;
        }        
        
        $isAdmin = \AclFactory::checaPermissao( 
                \Controlador::getInstance()->acl, 
                \Controlador::getInstance()->usuario, 
                \DaoRecurso::getRecursoById(998)
        );
        
        $isAllowed = \AclFactory::checaPermissao(
                \Controlador::getInstance()->acl, 
                \Controlador::getInstance()->usuario, 
                \DaoRecurso::getRecursoById(999)
        );
        
        if( $isAdmin || $isAllowed ){            
            return true;
        }
        
        //Se o documento é confidencial e o usuário não é Admin, não possui permissão
        return false;
    }
    
    /**
     * Verifica se o documento atual é Confidencial
     * @return boolean
     */
    protected function _isConfidentialDocument()
    {
        if(isset($this->_rowset)){
            $boolConfidencial = ( $this->_rowset[0]['FLG_PUBLICO'] == '0' )? true : false;
            return $boolConfidencial;
        }
        return false;
    }
    
    /**
     * Movimenta o arquivo da pasta temporária para pasta de destino
     * @param type $paramFile
     * @return \Documento\AbstractDocumentoImagem
     * @throws Exception
     */
    public function upload( $paramFile = array() )
    {
        $this->_isRightExtension( $paramFile['type'] );

        $session = \Session::get('_upload');
        $this->_digital = $session['digital'];

        $this->_originalName = $paramFile['name'];
        $this->_tmpName      = $paramFile['tmp_name'];
        $this->_img_name     = preg_replace( '/[., ]/', '',$this->_digital . '_' . microtime() );
        
//        $this->_uploadPath = '/var/www/html/sgdoc-branches/documento_virtual/LOTE0/0000596/';//temporario

        $this->_setUploadPath(); 
        $this->_setCachePath();
        
        //Se não houver dados do arquivo
        if( !count($paramFile) ){
            throw new \Exception('Dados insuficientes para Upload');
        }
        //move arquivo para diretório da aplicação
        if (!move_uploaded_file($this->_tmpName, $this->_uploadPath . $this->_img_name . '.pdf')) {
            throw new \Exception('Erro na tentativa de gravar o arquivo.');
        }

        $this->_getFileInfo(true);
        
        $this->_saveImageData();
        
        /* Removido do Upload. Passa a ser executado somente na visualização do slideshow */
//        $this->_exportToThumbs();        
        
        return $this;
    }
    
    /**
     * Verifica a existência do diretório para persistir os arquivos fonte
     */
    protected function _setUploadPath()
    {
        $dir = sprintf('%s/%s/%s/', __CAM_UPLOAD__, (string) \Util::gerarRaiz($this->_digital, __CAM_UPLOAD__) , $this->_digital);
        if(!is_dir($dir)){
            @mkdir($dir, 0777, true);
        }
        $this->_uploadPath = $dir;
    }
    
    /**
     * Gera Diretório para Cache
     */
    protected function _setCachePath()
    {
        $dir = sprintf('%s/cache/%s/%s/', __CAM_UPLOAD__, (string) \Util::gerarRaiz($this->_digital, __CAM_UPLOAD__) , $this->_digital);
        if(!is_dir($dir)){
            @mkdir($dir, 0777, true);
        }
        $this->_cachePath = $dir;        
    }
    
    /**
     * Verifica se a extensão do arquivo está correta
     */
    protected function _isRightExtension( $parExtension='' )
    {
        if( !strpos(strtolower($parExtension), strtolower($this->_getRightExtension()) ) ){
            throw new \Exception("Tipo de arquivo inválido! {$parExtension} é diferente de {$this->_getRightExtension()}");
        }
    }
    
    /**
     * Cada arquivo de upload deve informar a extensão de arquivo correta.
     * Exemplo: pdf
     */
    abstract protected function _getRightExtension();
    
    /**
     * Realiza a captura de informações do arquivo
     * @param boolean $parForce Forçar a obtenção da estrutura do arquivo
     */
    abstract protected function _getFileInfo( $parForce = false );
    
    /**
     * Realiza exportação para Cache Thumbs
     * Formato PNG, Tamanho A10, Resolução 50ppp, Mapa de cores GrayScale
     */
    abstract protected function _exportToThumbs();
    
    /**
     * Converte Páginas solicitadas em tamanho leitura, rotaciona
     * quando necessário e retorna endereço absoluto dos arquivos convertidos
     * Formato PNG, Tamanho A4, Resolução 200ppp, Mapa de Cores 16milhões de cores
     * 
     * @param array(int) $paramPages
     * @return array(IFormato)
     */
    abstract public function getImagesToRead( $parPages=array() );
    
    /**
     * Verifica se já foi efetuado o Upload do arquivo
     * @param string $parFile
     * @return boolean
     */
    protected function _hasUploaded($parFile='')
    {
        if(!count($parFile)){
            throw new \Exception("DocumentoImagem::_hasUploaded() - Arquivo não informado");
        }
        $hash = $this->_generateHashSha2FromFile($parFile);

        if ($hash) {
            $sttm = $this->_conn->prepare("
                SELECT count(ID) AS HAS 
                FROM TB_DOCUMENTOS_IMAGEM 
                WHERE DES_HASH_FILE = ? AND DIGITAL = ? AND FLG_PUBLICO != 2
            ");
            $sttm->bindParam(1, $hash, \PDO::PARAM_STR);
            $sttm->bindParam(2, $this->_digital, \PDO::PARAM_STR);
            $sttm->execute();

            if ($sttm->fetch(\PDO::FETCH_OBJ)->HAS) {
                return true;
            }
        }

        return false;
    }
    
    /**
     * Gera um hash do conteúdo do arquivo
     * 
     * @param type $pieceFilename parte do nome do arquivo sem extensão
     * @return boolean
     */
    protected function _generateHashSha2FromFile( $parAbsoluteFileName ) 
    {
        if (file_exists($parAbsoluteFileName)) {
            return hash('sha256', file_get_contents($parAbsoluteFileName));
        }else{
            throw new \Exception("DocumentoImagem::_generateHashSha2FromFile() - Arquivo não existe - ".$parAbsoluteFileName);
        }
        return false;
    }

    /**
     * Retorna lista de imagens em formato Thumbs para visualização em Slideshow
     * @return array(string) Lista de Imagens formato Thumbs
     */
    abstract public function getThumbList();
    
    /**
     * Retorna lista de imagens em formato Leitura para visualização em Slideshow
     * @return array(Rowset) Lista de Imagens formato Leitura
     */
    abstract public function getImageList();
    
    /**
     * Atualiza o campo "des_hash_file" de tb_documentos_imagem
     * Este campo é um hash do conteúdo do arquivo, e serve para identificar
     * o arquivo no sistema.
     */
    abstract protected function _saveImageData();
    
    /**
     * Efetua a exclusão lógica das Imagens do banco de dados e da pasta de cache
     */
    abstract protected function _deleteImagem();
}
