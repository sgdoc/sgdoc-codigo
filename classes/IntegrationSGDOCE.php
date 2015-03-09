<?php

/**
 * @author Michael F. Rodrigues <cerberosnash@gmail.com>
 */
class IntegrationSGDOCE extends CFModelAbstract {

    /**
     * @var string
     */
    protected $_schema = 'corporativo';

    /**
     * @var string
     */
    protected $_table = 'pessoa_fisica_usuario_sgdoc';

    /**
     * @var string
     */
    protected $_primary = 'id_usuario';

    /**
     * @var string
     */
    protected $_sequence = 'TB_CONTROLE_ACESSO_ID_SEQ';

    /**
     * @var array
     */
    protected $_fields = array(
        'sq_pessoa' => 'integer',
    );

    /**
     * @return IntegrationSGDOCE
     */
    public static function factory() {
        return new self;
    }

    /**
     * @return void
     */
    public function load() {

        $userZendSession = Controlador::getInstance()->usuario;

        /**
         *  @todo A carga abaixo deverá existir para que o recursos do SGDOC-e funcionem...
         *  
         *  insert into sgdoc.tb_recursos (id,nome,descricao,id_recurso_tipo) values(999999999,'(SGDOCE) Integração Minuta Eletrônica','(SGDOCE) Integração Minuta Eletrônica',4);
         *  insert into sgdoc.tb_recursos (id,nome,descricao,id_recurso_tipo) values(999999411,'(SGDOCE) Caixa de Minutas','(SGDOCE) Caixa de Minutas',4);
         *  insert into sgdoc.tb_recursos (id,nome,descricao,id_recurso_tipo) values(999999402,'(SGDOCE) Mensagem','(SGDOCE) Mensagem',4);
         *  insert into sgdoc.tb_recursos (id,nome,descricao,id_recurso_tipo) values(999999408,'(SGDOCE) Minuta Eletrônica','(SGDOCE) Minuta EletrÙnica',4);
         *  insert into sgdoc.tb_recursos (id,nome,descricao,id_recurso_tipo) values(999999403,'(SGDOCE) Modelo Carimbo','(SGDOCE) Modelo Carimbo',4);
         *  insert into sgdoc.tb_recursos (id,nome,descricao,id_recurso_tipo) values(999999409,'(SGDOCE) Modelo de Minuta','(SGDOCE) Modelo de Minuta',4);
         *  insert into sgdoc.tb_recursos (id,nome,descricao,id_recurso_tipo) values(999999405,'(SGDOCE) Sequencial de Unidade','(SGDOCE) Sequencial de Unidade',4);
         *  insert into sgdoc.tb_recursos (id,nome,descricao,id_recurso_tipo) values(999999406,'(SGDOCE) Tipo de Documento','(SGDOCE) Tipo de Documento',4);
         *  insert into sgdoc.tb_recursos (id,nome,descricao,id_recurso_tipo) values(999999404,'(SGDOCE) Tipo de Prioridade','(SGDOCE) Tipo de Prioridade',4);
         *  insert into sgdoc.tb_recursos (id,nome,descricao,id_recurso_tipo) values(999999407,'(SGDOCE) Vinculo de Prazo','(SGDOCE) Vinculo de Prazo',4);
         *  
         *  insert into sgdoc.tb_recursos (id,nome,descricao,id_recurso_tipo) values(999999412,'(SGDOCE) Documento Eletrônico','(SGDOCE) Documento Eletrônico',4);
         *  insert into sgdoc.tb_recursos (id,nome,descricao,id_recurso_tipo) values(999999413,'(SGDOCE) Dossiê Eletrônico','(SGDOCE) Dossiê Eletrônico',4);
         *  insert into sgdoc.tb_recursos (id,nome,descricao,id_recurso_tipo) values(999999414,'(SGDOCE) Processo Eletrônico','(SGDOCE) Processo Eletrônico',4);
         *  insert into sgdoc.tb_recursos (id,nome,descricao,id_recurso_tipo) values(999999415,'(SGDOCE) Artefato','(SGDOCE) Artefato',4);
         *  insert into sgdoc.tb_recursos (id,nome,descricao,id_recurso_tipo) values(999999416,'(SGDOCE) Área de Trabalho - Documentos','(SGDOCE) Área de Trabalho - Documentos',4);
         */
        if (!isset($_SESSION['USER'])) {

            $user = new \stdClass();
            $user->sqUsuario = current($this->find($userZendSession->ID))->SQ_PESSOA;
            $user->noUsuario = $userZendSession->NOME;
            $user->sqTipoPessoa = 1;
            $user->ativo = 1;
            $user->sqPessoa = current($this->find($userZendSession->ID))->SQ_PESSOA;
            $user->nuCpf = $userZendSession->CPF;
            $user->sqSistema = 5;
            $user->sqUnidadeOrg = $userZendSession->ID_UNIDADE; //@todo consultar e recuperar ID-UNIDADE do corporativo
            $user->sqPerfil = 33;
            $user->sistemas = array(0 => array(
                    'noSistema' => 'Sistema de Gerenciamento de documentos eletrônicos',
                    'sgSistema' => 'SGDOC-e',
                    'txEnderecoImagem' => 'http://dev.sgdoc3.sisicmbio.icmbio.gov.br/img/sgdoc_logo.png',
            ));

            $menusFilhos = array();

            if (AclFactory::checaPermissao(
                            Controlador::getInstance()->acl, Controlador::getInstance()->usuario, DaoRecurso::getRecursoById(999999411)
                    )) {
                $menusFilhos[] = array('MenuFilho' => array(
                        'sqMenu' => 411,
                        'sqMenuPai' => 410,
                        'noMenu' => 'Caixa de Minutas',
                        'sqFuncionalidade' => 431,
                        'stRegistroAtivo' => 1
                    ),
                    'Acao' => '/artefato/visualizar-caixa-minuta/index'
                );
            }

            if (AclFactory::checaPermissao(
                            Controlador::getInstance()->acl, Controlador::getInstance()->usuario, DaoRecurso::getRecursoById(999999402)
                    )) {
                $menusFilhos[] = array('MenuFilho' => array(
                        'sqMenu' => 402,
                        'sqMenuPai' => 401,
                        'noMenu' => 'Mensagem',
                        'sqFuncionalidade' => 402,
                        'stRegistroAtivo' => 1
                    ),
                    'Acao' => '/auxiliar/mensagem/index'
                );
            }

            if (AclFactory::checaPermissao(
                            Controlador::getInstance()->acl, Controlador::getInstance()->usuario, DaoRecurso::getRecursoById(999999408)
                    )) {
                $menusFilhos[] = array('MenuFilho' => array(
                        'sqMenu' => 408,
                        'sqMenuPai' => 401,
                        'noMenu' => 'Minuta Eletrônica',
                        'sqFuncionalidade' => 425,
                        'stRegistroAtivo' => 1
                    ),
                    'Acao' => '/artefato/minuta-eletronica/index'
                );
            }

            if (AclFactory::checaPermissao(
                            Controlador::getInstance()->acl, Controlador::getInstance()->usuario, DaoRecurso::getRecursoById(999999403)
                    )) {
                $menusFilhos[] = array('MenuFilho' => array(
                        'sqMenu' => 403,
                        'sqMenuPai' => 401,
                        'noMenu' => 'Modelo de Carimbo',
                        'sqFuncionalidade' => 406,
                        'stRegistroAtivo' => 1
                    ),
                    'Acao' => '/auxiliar/carimbo/index'
                );
            }

            if (AclFactory::checaPermissao(
                            Controlador::getInstance()->acl, Controlador::getInstance()->usuario, DaoRecurso::getRecursoById(999999409)
                    )) {
                $menusFilhos[] = array('MenuFilho' => array(
                        'sqMenu' => 409,
                        'sqMenuPai' => 401,
                        'noMenu' => 'Modelo de Minuta',
                        'sqFuncionalidade' => 428,
                        'stRegistroAtivo' => 1
                    ),
                    'Acao' => '/modelo-minuta/modelo-minuta/index'
                );
            }

            if (AclFactory::checaPermissao(
                            Controlador::getInstance()->acl, Controlador::getInstance()->usuario, DaoRecurso::getRecursoById(999999405)
                    )) {
                $menusFilhos[] = array('MenuFilho' => array(
                        'sqMenu' => 405,
                        'sqMenuPai' => 401,
                        'noMenu' => 'Sequencial de Unidade',
                        'sqFuncionalidade' => 414,
                        'stRegistroAtivo' => 1
                    ),
                    'Acao' => '/auxiliar/sequnidorg/index'
                );
            }

            if (AclFactory::checaPermissao(
                            Controlador::getInstance()->acl, Controlador::getInstance()->usuario, DaoRecurso::getRecursoById(999999406)
                    )) {
                $menusFilhos[] = array('MenuFilho' => array(
                        'sqMenu' => 406,
                        'sqMenuPai' => 401,
                        'noMenu' => 'Tipo de Documento',
                        'sqFuncionalidade' => 417,
                        'stRegistroAtivo' => 1
                    ),
                    'Acao' => '/auxiliar/tipodoc/index'
                );
            }

            if (AclFactory::checaPermissao(
                            Controlador::getInstance()->acl, Controlador::getInstance()->usuario, DaoRecurso::getRecursoById(999999404)
                    )) {
                $menusFilhos[] = array('MenuFilho' => array(
                        'sqMenu' => 404,
                        'sqMenuPai' => 401,
                        'noMenu' => 'Tipo de Prioridade',
                        'sqFuncionalidade' => 409,
                        'stRegistroAtivo' => 1
                    ),
                    'Acao' => '/auxiliar/prioridade/index'
                );
            }

            if (AclFactory::checaPermissao(
                            Controlador::getInstance()->acl, Controlador::getInstance()->usuario, DaoRecurso::getRecursoById(999999407)
                    )) {
                $menusFilhos[] = array('MenuFilho' => array(
                        'sqMenu' => 407,
                        'sqMenuPai' => 401,
                        'noMenu' => 'Vinculo de Prazo',
                        'sqFuncionalidade' => 421,
                        'stRegistroAtivo' => 1
                    ),
                    'Acao' => '/auxiliar/vincularprazo/index'
                );
            }

            if (AclFactory::checaPermissao(
                            Controlador::getInstance()->acl, Controlador::getInstance()->usuario, DaoRecurso::getRecursoById(999999412)
                    )) {
                $menusFilhos[] = array('MenuFilho' => array(
                        'sqMenu' => 412,
                        'sqMenuPai' => 401,
                        'noMenu' => 'Documento Eletrônico',
                        'sqFuncionalidade' => 421,
                        'stRegistroAtivo' => 1
                    ),
                    'Acao' => '/artefato/documento/index'
                );
            }

            if (AclFactory::checaPermissao(
                            Controlador::getInstance()->acl, Controlador::getInstance()->usuario, DaoRecurso::getRecursoById(999999413)
                    )) {
                $menusFilhos[] = array('MenuFilho' => array(
                        'sqMenu' => 413,
                        'sqMenuPai' => 401,
                        'noMenu' => 'Dossiê Eletrônico',
                        'sqFuncionalidade' => 421,
                        'stRegistroAtivo' => 1
                    ),
                    'Acao' => '/artefato/dossie/index'
                );
            }

            if (AclFactory::checaPermissao(
                            Controlador::getInstance()->acl, Controlador::getInstance()->usuario, DaoRecurso::getRecursoById(999999414)
                    )) {
                $menusFilhos[] = array('MenuFilho' => array(
                        'sqMenu' => 414,
                        'sqMenuPai' => 401,
                        'noMenu' => 'Processo Eletrônico',
                        'sqFuncionalidade' => 421,
                        'stRegistroAtivo' => 1
                    ),
                    'Acao' => '/artefato/processo-eletronico/index'
                );
            }

            if (AclFactory::checaPermissao(
                            Controlador::getInstance()->acl, Controlador::getInstance()->usuario, DaoRecurso::getRecursoById(999999415)
                    )) {
                $menusFilhos[] = array('MenuFilho' => array(
                        'sqMenu' => 415,
                        'sqMenuPai' => 401,
                        'noMenu' => 'Artefato',
                        'sqFuncionalidade' => 421,
                        'stRegistroAtivo' => 1
                    ),
                    'Acao' => '/artefato/consultar-artefato/consultar-artefato-padrao'
                );
            }

            if (AclFactory::checaPermissao(
                            Controlador::getInstance()->acl, Controlador::getInstance()->usuario, DaoRecurso::getRecursoById(999999416)
                    )) {
                $menusFilhos[] = array('MenuFilho' => array(
                        'sqMenu' => 416,
                        'sqMenuPai' => 401,
                        'noMenu' => 'Área de Trabalho - Documentos',
                        'sqFuncionalidade' => 421,
                        'stRegistroAtivo' => 1
                    ),
                    'Acao' => '/artefato/area-trabalho/index'
                );
            }

            $user->MenuExterno = array(
                array(
                    'MenuPai' => array(
                        'sqMenu' => 410,
                        'sqMenuPai' => NULL,
                        'noMenu' => 'Minutas Eletrônicas',
                        'sqFuncionalidade' => NULL,
                        'stRegistroAtivo' => 1
                    ),
                    'MenuFilho' => ($menusFilhos),
                )
            );

            $user->Acl = array(
                array('funcionalidade' => 414, 'classe' => '/auxiliar/sequnidorg', 'metodo' => 'index'),
                array('funcionalidade' => 409, 'classe' => '/auxiliar/prioridade', 'metodo' => 'index'),
                array('funcionalidade' => 402, 'classe' => '/auxiliar/mensagem', 'metodo' => 'index'),
                array('funcionalidade' => 425, 'classe' => '/minuta/artefato', 'metodo' => 'index'),
                array('funcionalidade' => 406, 'classe' => '/auxiliar/carimbo', 'metodo' => 'index'),
                array('funcionalidade' => 427, 'classe' => '/minuta/modelo-minuta', 'metodo' => 'index'),
                array('funcionalidade' => 417, 'classe' => '/auxiliar/tipodoc', 'metodo' => 'index'),
                array('funcionalidade' => 421, 'classe' => '/auxiliar/vincularprazo', 'metodo' => 'index'),
                array('funcionalidade' => 432, 'classe' => '/minuta/visualizar-caixa-minuta', 'metodo' => 'index'),
                array('funcionalidade' => 441, 'classe' => '/artefato/documento', 'metodo' => 'index'),
                array('funcionalidade' => 442, 'classe' => '/artefato/dossie', 'metodo' => 'index'),
                array('funcionalidade' => 443, 'classe' => '/artefato/processo-eletronico', 'metodo' => 'index'),
                array('funcionalidade' => 444, 'classe' => '/artefato/consultar-artefato', 'metodo' => 'consultar-artefato-padrao'),
                array('funcionalidade' => 447, 'classe' => '/artefato/area-trabalho', 'metodo' => 'index'),
            );

            $_SESSION['Messaging'] = array('packets');
            $_SESSION['USER'] = $user;
        }
    }

}