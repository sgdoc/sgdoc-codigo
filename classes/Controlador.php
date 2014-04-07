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

class Controlador {

    /**
     *
     * @var Controlador
     */
    private static $_instance = null;

    /**
     *
     * @var Connection
     */
    private $_db;

    /**
     * @var string
     */
    private $_view = '';

    /**
     *
     * @var array 
     */
    private $_contexto = array();

    /**
     *
     * @var Zend_Cache_Core 
     */
    public $cache;

    /**
     * Armazena o acl do usuario logado
     * @var Core_Acl
     */
    public $acl = null;

    /*
     * Armazena o usuário logado na sessao
     */
    public $usuario = null;

    /**
     *
     * @var type $unidades
     */
    public $unidades = array();

    /**
     * Armazena o objeto do recurso de página atualmente acessado
     * @var Recurso
     */
    public $recurso = null;

    /**
     * Armazena as excessões de recursos cujas permissoes não são checadas
     * @var type 
     */
    public $_excessoes = array(
        'captcha.php',
        'modelos/login/login.php',
        'modelos/usuarios/recuperar_senha_usuario.php',
        'logoff.php',
        'alerta_prazos.php',
        'arvore_anexos_documentos.php',
        'arvore_apensos_documentos.php',
        'arvore_anexos_processos.php',
        'arvore_apensos_processos.php',
        'arvore_pecas_processos.php',
        'novo_jquery_file_uploader.php',
        'novo_upload_imagens_multiplas.php',
        'restaurar_etiquetas.php',
        'guia_documentos.php',
        'guia_processos.php',
        'gerador_etiquetas_processos.php',
        'termo_abertura_volume.php',
        'termo_desentranhamento.php',
        'termo_desmembramento.php',
        'termo_encerramento_volume.php',
        'termo_juntada_apensacao_anexacao.php',
        'termo_juntada_desapensacao.php',
        'fundo_visualizador.php',
        'atualizar_cpf_usuario.php',
        'usuario_selecionar_unidade.php',
        'usuario_sem_unidade_vinculada.php',
        'arvore_documentos_associados.php', // @todo descomentar quando for utilizar a extensao pr-snas!!!
    );

    /**
     *
     * @var array
     */
    public $botoes = array();

    /**
     * Array de Extensões do SGDOC
     * @var Extensoes 
     */
    protected $_extensions = null;

    /**
     * @return void
     */
    private function __construct() {
        $this->_updateView();

        $bOpts = array('cache_dir' => __BASE_PATH__ . '/cache/zend/');
        $fOpts = array('lifetime' => null, 'automatic_serialization' => true);

        $this->cache = Zend_Cache::factory('Core', 'File', $fOpts, $bOpts);

        $this->_db = $this->getConnection();

        // Verifica se os dados de sessão do usuário devem expirar
        $authNamespace = new Zend_Session_Namespace('auth');

        // Limpa a identidade de um usuario que não acessou uma página por um tempo maior que o timeout
        if (isset($authNamespace->timeout) && time() > $authNamespace->timeout && Zend_Auth::getInstance()->hasIdentity()) {
            // O tempo foi esgotado, verificar se o usuário em questão havia trocado de setor
            $tmp_auth = Zend_Auth::getInstance()->getStorage()->read();
            if ($tmp_auth->TROCOU == true) {
                // usuário passou por troca de unidade, apagar cache de Acl
                $this->cache->clean('matchingAnyTag', array('acl_usuario_' . $tmp_auth->ID));
            }
            Zend_Auth::getInstance()->clearIdentity();
            unset($tmp_auth);
        } else if (Zend_Auth::getInstance()->hasIdentity()) {
            // Usuário ainda ativo - atualizar o tempo de timeout 
            $authNamespace->timeout = $this->_updateTimeout($authNamespace->timeout);
        } else {
            Zend_Session::namespaceUnset('auth');
        }

        // Verifica se o usuário está logado, se estiver, armazena os dados do mesmo
        // no controller, se não estiver armazena null
        $this->usuario = Zend_Auth::getInstance()->hasIdentity() ?
                Zend_Auth::getInstance()->getStorage()->read() : null;

        $this->_configureExtensions();
    }

    /**
     * 
     */
    protected function _configureExtensions() {
        include(__BASE_PATH__ . "/extensoes/Extensoes.php");

        $config = Config::factory();
        $arrActiveExtensions = $config->getParam("extensions.active");

        //Se existirem algum módulo Ativo for informado
        if (isset($arrActiveExtensions) && count($arrActiveExtensions)) {
            $this->_extensions = new \Extensoes($arrActiveExtensions);
        }
    }

    /**
     * Captura View
     */
    private function _updateView() {
        $this->_view = current(explode('?', $_SERVER['REQUEST_URI']));
        // Se o arquivo requisitado for uma chamada de interface, entao remove a barra (/)
        if (substr_count($this->_view, '/') == 1) {
            $this->_view = str_replace('/', '', $this->_view);
        }
    }

    /**
     * Só atualiza o Timeout caso não seja a Verificação de Prazos
     * Caso o timeout já tenha vendi
     * @return integer
     */
    private function _updateTimeout($parTimeout) {
        if (strstr($this->_view, '/modelos/prazos/verificar_prazos_pendentes.php') ||
                strstr($this->_view, 'logoff.php')) {
            return $parTimeout;
        }
        return time() + (__MAXIMO_MINUTOS_SESSAO__ * 60);
    }

    public function __destruct() {
        $this->_db->connection = null;
        $this->_db = null;
    }

    /**
     * @return Controlador
     */
    public static function getInstance() {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function getConnection() {
        if (null === $this->_db) {
            $this->_db = new Connection();
        }

        return $this->_db;
    }

    /**
     * @return Controlador
     */
    public function dispatch() {

        $this->_prepareUnidades()
                ->_prepareAcl()
                ->_prepare()
                ->_check()
                ->_require();

        return $this;
    }

    /**
     * Retorna contexto. Método pode retornar somente o ultimo contexto setado
     * ou retornar todos os contextos setados, de acordo com a necessidade.
     * @param boolean $justLast
     * @return mixed
     */
    public function getContexto($justLast = true) {
        if ($justLast && is_array($this->_contexto) && count($this->_contexto)) {
            return $this->_contexto[count($this->_contexto) - 1];
        }
        return $this->_contexto;
    }

    /**
     * Empilha contexto para verificação
     * @param type $contexto
     */
    public function setContexto($contexto) {
        if ($contexto) {
            $this->_contexto[] = $contexto;
        }
    }

    public function includeResource($parResource = '') {
        if (!$parResource) {
            throw new \Exception("Recurso não definido");
        }

        //Se não possuir / se trata de uma interface
        $file = "";
        if (is_object($this->_extensions)) {
            if (!$this->_extensions->overWrittenResource($parResource)) {
                $file = (substr_count($parResource, '/') == 0) ? 'interfaces/' . $parResource : $parResource;
            }
        } else {
            $file = (substr_count($parResource, '/') == 0) ? 'interfaces/' . $parResource : $parResource;
        }

        if (is_file(__BASE_PATH__ . "/{$file}")) {
            include_once(__BASE_PATH__ . "/{$file}");
        }
    }

    /**
     * Somente interfaces e modelos podem ser requeridos...
     * @return Controlador
     */
    private function _require() {
        $this->includeResource($this->_view);

        if (is_a($this->recurso, 'Recurso')) {
            if (is_array($this->recurso->dependencias)) {
                foreach ($this->recurso->dependencias as $arquivo) {
                    $this->includeResource($arquivo);
                }
            }
        }

        return $this;
    }

    /**
     * @return Controlador
     */
    private function _prepareUnidades() {

        if (!is_null($this->usuario)) {
            // criar cache de unidades, e checar se ele existe
            $this->unidades = CFModelUsuarioUnidade::factory()->retrieveUnitsAvailableByIdUser($this->usuario->ID);
        }

        return $this;
    }

    /**
     * @return Controlador
     */
    private function _prepare() {

        $this->_view = current(explode('?', $_SERVER['REQUEST_URI']));

        // Se o arquivo requisitado for uma chamada de interface, entao remove a barra (/)
        if (substr_count($this->_view, '/') == 1) {
            $this->_view = str_replace('/', '', $this->_view);
        }

        return $this;
    }

    /**
     * @return controlar
     */
    private function _prepareAcl() {
        if (!is_null($this->usuario) && !is_null($this->usuario->ID_UNIDADE)) {

            // atualizar informações da unidade do usuário na sessão
            // com as informações do banco, a menos que o usuário tenha trocado
            // Testar a existência de um cache de acl para o usuário
            if (!($this->cache->test('acl_' . $this->usuario->ID))) {
                $this->acl = AclFactory::createAcl($this->usuario);

                $this->cache->save($this->acl, 'acl_' . $this->usuario->ID, array('acl_usuario_' . $this->usuario->ID,
                    'acl_unidade_' . $this->usuario->ID_UNIDADE));
            } else {
                $this->acl = $this->cache->load('acl_' . $this->usuario->ID);
                if ($this->usuario->ID_UNIDADE != $this->acl->getIdUnidade()) {
                    if (!$this->acl->isTrocouUnidade() && !$this->usuario->TROCOU) {
                        // id do cache e da session não batem, e usuário não trocou de unidade
                        // limpar o cache e recriá-lo
                        $this->cache->remove('acl_' . $this->usuario->ID);

                        $this->cache->clean('matchingAnyTag', array('acl_usuario_' . $this->usuario->ID));

                        $this->acl = AclFactory::createAcl($this->usuario);

                        $this->cache->save($this->acl, 'acl_' . $this->usuario->ID, array('acl_usuario_' . $this->usuario->ID,
                            'acl_unidade_' . $this->usuario->ID_UNIDADE));
                        // limpar
                    }
                }
                $this->usuario = $this->acl->updateSession();
            }

            // Forçar permissão de troca de unidades pro usuário que já trocou de unidade
            if ($this->acl->isTrocouUnidade()) {
                $this->acl->allow($this->usuario->ID, 115);
            }
        }

        return $this;
    }

    /**
     * @return Controlador
     */
    private function _check() {

        if (is_null($this->usuario) &&
                !strstr($this->_view, 'captcha.php') &&
                !strstr($this->_view, 'modelos/login/login.php') &&
                !strstr($this->_view, 'recuperar_senha_usuario.php') &&
                !strstr($this->_view, 'webservices/') &&
                !strstr($this->_view, 'logoff.php') &&
                !strstr($this->_view, 'modelos/prazos/verificar_prazos_pendentes.php') &&
                !strstr($this->_view, 'modelos/usuarios/recuperar_senha_usuario.php')) {
            $this->_view = 'identificacao.php';
        }

        if (!is_null($this->usuario)) {

            if (is_null($this->usuario->ID_UNIDADE) && $this->_view != 'logoff.php') {
                if (count($this->unidades) > 1) {
                    $this->_view = 'usuario_selecionar_unidade.php';
                } else {
                    if (count($this->unidades) == 1) {
                        $this->usuario->DIRETORIA = current($this->unidades)->NOME;
                        $this->usuario->ID_UNIDADE = current($this->unidades)->ID;
                        $this->usuario->ID_UNIDADE_ORIGINAL = current($this->unidades)->ID;
                        Zend_Auth::getInstance()->getStorage()->write($this->usuario);
                        $this->_prepareAcl();
                    } else {
                        $this->_view = 'usuario_sem_unidade_vinculada.php';
                    }
                }
            }

            if ($this->_view == '' || $this->_view == '/' || $this->_view == 'identificacao.php') {
                $this->_view = 'sistemas.php';
            }

            /*
             * Pegar o id do recurso que o usuário está tentando acessar
             * isso não deve ser feito no caso do recurso ser login ou logoff
             */

            if (substr_count($this->_view, '/') == 0 &&
                    array_search($this->_view, $this->_excessoes) === false) {

                $name_recurso = str_replace('.', '_', $this->_view);

                if (!($this->cache->test('recurso_' . $name_recurso))) {
                    $this->recurso = DaoRecurso::getRecursoByUrl($this->_view);
                    if (isset($this->recurso->id)) {
                        $this->cache->save($this->recurso, 'recurso_' . $name_recurso, array('recurso_' . $this->recurso->id, 'paginas'));
                    } else {
                        $this->recurso = null;
                    }
                } else {
                    $this->recurso = $this->cache->load('recurso_' . $name_recurso);
                }

                /*
                 * Verificacao de ACL ocorre abaixo
                 * mas não deve ocorrer a menos que o usuário esteja requisitando
                 * um recurso contido na pasta interfaces
                 * Não existe verificacao de Acl para a pasta modelos/
                 */

                if (isset($this->recurso->id)) {
                    if ($this->acl->has($this->recurso->id)) {
                        if (!$this->acl->isAllowed($this->usuario->ID, $this->recurso)) {
                            $this->_view = 'denied.php';
                        } else {
                            // nao recusou o acl, montar os submenus do recurso
                            $this->botoes = Util::getMenus($this->usuario, $this->recurso, $this->acl);
                        }
                    } else {
                        // TODO: Aqui negará novamente, pois se o recurso não existe no acl
                        // deve ser negado por segurança
                        $this->_view = 'denied.php';
                    }
                } else {
                    $this->_view = 'denied.php';
                }
            }
        }

        return $this;
    }

}