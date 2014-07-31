<?php

/*
 * Copyright 2008 ICMBio
 * Este arquivo é parte do programa SISICMBio
 * O SISICMBio é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos
 * da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão
 * 2 da Licença.
 *
 * Este programa é distribuído na esperança que possa ser útil, mas SEM NENHUMA GARANTIA; sem
 * uma garantia implícita de ADEQUAÇÃO a qualquer MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a
 * Licença Pública Geral GNU/GPL em português para maiores detalhes.
 * Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "LICENCA.txt",
 * junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço
 * www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF)
 * Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
 * */

/**
 * Description of AclFactory
 *
 * @author jhonatan.flach <jhonatan.flach@icmbio.gov.br>
 */
class AclFactory {

    /**
     * Creates an ACL for a specific user
     * @param stdClass $usuario - usuario logado, recebido do Zend_Auth
     * @return Core_Acl
     */
    public static function createAcl($usuario) {
        //Lets assume we have a model for the page_privileges with a method like this
        //which would return PagePrivilege objects with the page_id passed as the param.
        $privilegios_unidade = DaoPrivilegio::getPrivilegiosPorUnidade((int) $usuario->ID_UNIDADE);
        $privilegios_usuario = DaoPrivilegioUsuario::getPrivilegiosPorUsuario((int) $usuario->ID);

        $acl = new Core_Acl();
        $acl->addRole(new Zend_Acl_Role($usuario->ID));

        $acl->setIdUnidade($usuario->ID_UNIDADE);
        $acl->setNomeUnidade($usuario->DIRETORIA);
        $acl->setTrocouUnidade($usuario->TROCOU ? true : false);

        foreach ($privilegios_unidade as $privilege) {
            $recurso = DaoRecurso::getRecursoById($privilege['ID_RECURSO']);
            if (!$acl->has($recurso)) {
                $acl->addResource($recurso);
            }
            // Checa pela presenca de assertion
            if ($recurso->hasClasseAssertion()) {
                $acl->allow($usuario->ID, $recurso, null, new $recurso->classe_assertion());
            } else {
                if ($privilege['PERMISSAO'] == 1) {
                    $acl->allow($usuario->ID, $recurso);
                } else {
                    $acl->deny($usuario->ID, $recurso);
                }
            }
        }

        foreach ($privilegios_usuario as $privilege2) {
            $recurso = DaoRecurso::getRecursoById($privilege2['ID_RECURSO']);

            // @todo foi adicionado a condicao !NULL para corrigir possivel bug, analizar!
            if (!is_null($recurso) && !$acl->has($recurso)) {
                $acl->addResource($recurso);
            }

            // @todo foi adicionado a condicao !NULL para corrigir possivel bug, analizar!
            // Checa pela presenca de assertion
            if (!is_null($recurso) && $recurso->hasClasseAssertion()) {
                $acl->allow($usuario->ID, $recurso, null, new $recurso->classe_assertion());
            } else {
                if ($privilege2['PERMISSAO'] == "1") {
                    $acl->allow($usuario->ID, $recurso);
                } else {
                    $acl->deny($usuario->ID, $recurso);
                }
            }
        }

        if ($acl->isTrocouUnidade()) {
            $acl->allow($usuario->ID, 115);
        }

        return $acl;
    }

    /**
     * Checa se um determinado recurso pode ser acessado por um usuário
     * 
     * @param Core_Acl $acl
     * @param StdClass $usuario
     * @param Recurso $recurso
     * @return boolean 
     */
    public static function checaPermissao(Core_Acl $acl, StdClass $usuario, Recurso $recurso) {
        if ($acl->has($recurso->id)) {
            return $acl->isAllowed($usuario->ID, $recurso->id);
        }
    }

}