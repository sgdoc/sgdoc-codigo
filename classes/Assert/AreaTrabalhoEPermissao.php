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
 * Description of Assert_AreaTrabalhoEPermissao
 *
 * @author jhonatan.flach <jhonatan.flach@icmbio.gov.br>
 */
class Assert_AreaTrabalhoEPermissao implements Core_Acl_Assert_Interface
{
    public function assert(Core_Acl $acl, 
                           Zend_Acl_Role_Interface $role = null, 
                           Zend_Acl_Resource_Interface $resource = null, 
                           $privilege = null)
    {
        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            return false;
        }
        $controller = Controlador::getInstance();
        $usuario = $controller->usuario;
        $somenteUltimo = false;
        $contexto = $resource->getContexto( $somenteUltimo );
        
        // Se houver um contexto, e o usuario tem permissao para o recurso
        if (!is_null($contexto)) {
            // Checa se o usuario tem permissao de acessar o recurso
            if (!$controller->cache->test('privilegio_'.$usuario->ID.'_'.$resource->id)) {
                // não existe o cache, pegar o valor do banco
                $privilegio = DaoRecurso::getPrivilegioByUsuario($usuario, $resource);
                $controller->cache->save($privilegio, 'privilegio_'.$usuario->ID.'_'.$resource->id,
                                        array('acl_usuario_'.$usuario->ID,
                                                'acl_unidade_'.$usuario->ID_UNIDADE));
            } else {
                $privilegio = $controller->cache->load('privilegio_'.$usuario->ID.'_'.$resource->id);
            }

            if ($privilegio) {
                foreach ($contexto as $umContexto) {                    
                    // checa se o objeto do contexto tem a mesma area de trabalho do usuario
                    if( $umContexto['id_unid_area_trabalho'] == $usuario->ID_UNIDADE ){
                        return true;
                    }
                }
            }                
        }
        
        return false;
    }
}
