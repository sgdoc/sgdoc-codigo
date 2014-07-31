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
 * Description of Core_Acl
 *
 * @author jhonatan.flach <jhonatan.flach@icmbio.gov.br>
 */
class Core_Acl extends Zend_Acl {

    private $_trocou;
    private $_idUnidade;
    private $_nomeUnidade;

    /**
     * Adds an "allow" rule to the ACL
     *
     * @param  Zend_Acl_Role_Interface|string|array     $roles
     * @param  Zend_Acl_Resource_Interface|string|array $resources
     * @param  string|array                             $privileges
     * @param  Core_Acl_Assert_Interface                $assert
     * @uses   Zend_Acl::setRule()
     * @return Zend_Acl Provides a fluent interface
     */
    public function allow($roles = null, $resources = null, $privileges = null, Core_Acl_Assert_Interface $assert = null) {
        return $this->setRule(self::OP_ADD, self::TYPE_ALLOW, $roles, $resources, $privileges, $assert);
    }

    /**
     * Adds a "deny" rule to the ACL
     *
     * @param  Zend_Acl_Role_Interface|string|array     $roles
     * @param  Zend_Acl_Resource_Interface|string|array $resources
     * @param  string|array                             $privileges
     * @param  Core_Acl_Assert_Interface                $assert
     * @uses   Zend_Acl::setRule()
     * @return Zend_Acl Provides a fluent interface
     */
    public function deny($roles = null, $resources = null, $privileges = null, Core_Acl_Assert_Interface $assert = null) {
        return $this->setRule(self::OP_ADD, self::TYPE_DENY, $roles, $resources, $privileges, $assert);
    }

    /**
     * Performs operations on ACL rules
     *
     * The $operation parameter may be either OP_ADD or OP_REMOVE, depending on whether the
     * user wants to add or remove a rule, respectively:
     *
     * OP_ADD specifics:
     *
     *      A rule is added that would allow one or more Roles access to [certain $privileges
     *      upon] the specified Resource(s).
     *
     * OP_REMOVE specifics:
     *
     *      The rule is removed only in the context of the given Roles, Resources, and privileges.
     *      Existing rules to which the remove operation does not apply would remain in the
     *      ACL.
     *
     * The $type parameter may be either TYPE_ALLOW or TYPE_DENY, depending on whether the
     * rule is intended to allow or deny permission, respectively.
     *
     * The $roles and $resources parameters may be references to, or the string identifiers for,
     * existing Resources/Roles, or they may be passed as arrays of these - mixing string identifiers
     * and objects is ok - to indicate the Resources and Roles to which the rule applies. If either
     * $roles or $resources is null, then the rule applies to all Roles or all Resources, respectively.
     * Both may be null in order to work with the default rule of the ACL.
     *
     * The $privileges parameter may be used to further specify that the rule applies only
     * to certain privileges upon the Resource(s) in question. This may be specified to be a single
     * privilege with a string, and multiple privileges may be specified as an array of strings.
     *
     * If $assert is provided, then its assert() method must return true in order for
     * the rule to apply. If $assert is provided with $roles, $resources, and $privileges all
     * equal to null, then a rule having a type of:
     *
     *      TYPE_ALLOW will imply a type of TYPE_DENY, and
     *
     *      TYPE_DENY will imply a type of TYPE_ALLOW
     *
     * when the rule's assertion fails. This is because the ACL needs to provide expected
     * behavior when an assertion upon the default ACL rule fails.
     *
     * @param  string                                   $operation
     * @param  string                                   $type
     * @param  Zend_Acl_Role_Interface|string|array     $roles
     * @param  Zend_Acl_Resource_Interface|string|array $resources
     * @param  string|array                             $privileges
     * @param  Core_Acl_Assert_Interface                $assert
     * @throws Zend_Acl_Exception
     * @uses   Zend_Acl_Role_Registry::get()
     * @uses   Zend_Acl::get()
     * @return Zend_Acl Provides a fluent interface
     */
    public function setRule($operation, $type, $roles = null, $resources = null, $privileges = null, Core_Acl_Assert_Interface $assert = null) {
        // ensure that the rule type is valid; normalize input to uppercase
        $type = strtoupper($type);
        if (self::TYPE_ALLOW !== $type && self::TYPE_DENY !== $type) {
            require_once 'Zend/Acl/Exception.php';
            throw new Zend_Acl_Exception("Unsupported rule type; must be either '" . self::TYPE_ALLOW . "' or '"
            . self::TYPE_DENY . "'");
        }

        // ensure that all specified Roles exist; normalize input to array of Role objects or null
        if (!is_array($roles)) {
            $roles = array($roles);
        } else if (0 === count($roles)) {
            $roles = array(null);
        }
        $rolesTemp = $roles;
        $roles = array();
        foreach ($rolesTemp as $role) {
            if (null !== $role) {
                $roles[] = $this->_getRoleRegistry()->get($role);
            } else {
                $roles[] = null;
            }
        }
        unset($rolesTemp);

        // ensure that all specified Resources exist; normalize input to array of Resource objects or null
        if ($resources !== null) {
            if (!is_array($resources)) {
                $resources = array($resources);
            } else if (0 === count($resources)) {
                $resources = array(null);
            }
            $resourcesTemp = $resources;
            $resources = array();
            foreach ($resourcesTemp as $resource) {
                if (null !== $resource) {
                    $resources[] = $this->get($resource);
                } else {
                    $resources[] = null;
                }
            }
            unset($resourcesTemp, $resource);
        } else {
            $allResources = array(); // this might be used later if resource iteration is required
            foreach ($this->_resources as $rTarget) {
                $allResources[] = $rTarget['instance'];
            }
            unset($rTarget);
        }

        // normalize privileges to array
        if (null === $privileges) {
            $privileges = array();
        } else if (!is_array($privileges)) {
            $privileges = array($privileges);
        }

        switch ($operation) {

            // add to the rules
            case self::OP_ADD:
                if ($resources !== null) {
                    // this block will iterate the provided resources
                    foreach ($resources as $resource) {
                        foreach ($roles as $role) {
                            $rules = & $this->_getRules($resource, $role, true);
                            if (0 === count($privileges)) {
                                $rules['allPrivileges']['type'] = $type;
                                $rules['allPrivileges']['assert'] = $assert;
                                if (!isset($rules['byPrivilegeId'])) {
                                    $rules['byPrivilegeId'] = array();
                                }
                            } else {
                                foreach ($privileges as $privilege) {
                                    $rules['byPrivilegeId'][$privilege]['type'] = $type;
                                    $rules['byPrivilegeId'][$privilege]['assert'] = $assert;
                                }
                            }
                        }
                    }
                } else {
                    // this block will apply to all resources in a global rule
                    foreach ($roles as $role) {
                        $rules = & $this->_getRules(null, $role, true);
                        if (0 === count($privileges)) {
                            $rules['allPrivileges']['type'] = $type;
                            $rules['allPrivileges']['assert'] = $assert;
                        } else {
                            foreach ($privileges as $privilege) {
                                $rules['byPrivilegeId'][$privilege]['type'] = $type;
                                $rules['byPrivilegeId'][$privilege]['assert'] = $assert;
                            }
                        }
                    }
                }
                break;

            // remove from the rules
            case self::OP_REMOVE:
                if ($resources !== null) {
                    // this block will iterate the provided resources
                    foreach ($resources as $resource) {
                        foreach ($roles as $role) {
                            $rules = & $this->_getRules($resource, $role);
                            if (null === $rules) {
                                continue;
                            }
                            if (0 === count($privileges)) {
                                if (null === $resource && null === $role) {
                                    if ($type === $rules['allPrivileges']['type']) {
                                        $rules = array(
                                            'allPrivileges' => array(
                                                'type' => self::TYPE_DENY,
                                                'assert' => null
                                            ),
                                            'byPrivilegeId' => array()
                                        );
                                    }
                                    continue;
                                }

                                if (isset($rules['allPrivileges']['type']) &&
                                        $type === $rules['allPrivileges']['type']) {
                                    unset($rules['allPrivileges']);
                                }
                            } else {
                                foreach ($privileges as $privilege) {
                                    if (isset($rules['byPrivilegeId'][$privilege]) &&
                                            $type === $rules['byPrivilegeId'][$privilege]['type']) {
                                        unset($rules['byPrivilegeId'][$privilege]);
                                    }
                                }
                            }
                        }
                    }
                } else {
                    // this block will apply to all resources in a global rule
                    foreach ($roles as $role) {
                        /**
                         * since null (all resources) was passed to this setRule() call, we need
                         * clean up all the rules for the global allResources, as well as the indivually
                         * set resources (per privilege as well)
                         */
                        foreach (array_merge(array(null), $allResources) as $resource) {
                            $rules = & $this->_getRules($resource, $role, true);
                            if (null === $rules) {
                                continue;
                            }
                            if (0 === count($privileges)) {
                                if (null === $role) {
                                    if ($type === $rules['allPrivileges']['type']) {
                                        $rules = array(
                                            'allPrivileges' => array(
                                                'type' => self::TYPE_DENY,
                                                'assert' => null
                                            ),
                                            'byPrivilegeId' => array()
                                        );
                                    }
                                    continue;
                                }

                                if (isset($rules['allPrivileges']['type']) && $type === $rules['allPrivileges']['type']) {
                                    unset($rules['allPrivileges']);
                                }
                            } else {
                                foreach ($privileges as $privilege) {
                                    if (isset($rules['byPrivilegeId'][$privilege]) &&
                                            $type === $rules['byPrivilegeId'][$privilege]['type']) {
                                        unset($rules['byPrivilegeId'][$privilege]);
                                    }
                                }
                            }
                        }
                    }
                }
                break;

            default:
                require_once 'Zend/Acl/Exception.php';
                throw new Zend_Acl_Exception("Unsupported operation; must be either '" . self::OP_ADD . "' or '"
                . self::OP_REMOVE . "'");
        }

        return $this;
    }

    /**
     * 
     * @return boolean 
     */
    public function isTrocouUnidade() {
        return $this->_trocou;
    }

    /**
     * 
     * @param boolean $changed 
     */
    public function setTrocouUnidade($changed) {
        $this->_trocou = $changed;
    }

    /**
     * 
     * @return type 
     */
    public function getIdUnidade() {
        return $this->_idUnidade;
    }

    /**
     * 
     * @return type 
     */
    public function getNomeUnidade() {
        return $this->_nomeUnidade;
    }

    /**
     *
     * @param int $idUnidade 
     */
    public function setIdUnidade($idUnidade) {
        $this->_idUnidade = (int) $idUnidade;
    }

    /**
     *
     * @param string $nomeUnidade 
     */
    public function setNomeUnidade($nomeUnidade) {
        $this->_nomeUnidade = $nomeUnidade;
    }

    /**
     * 
     */
    public function updateSession() {
        $sessao = Zend_Auth::getInstance()->getStorage()->read();
        if ($this->isTrocouUnidade()) {
            $sessao->TROCOU = true;
            $sessao->ID_UNIDADE_ORIGINAL = $sessao->ID_UNIDADE;
        } else {
            $sessao->ID_UNIDADE_ORIGINAL = $sessao->ID_UNIDADE;
            unset($sessao->TROCOU);
        }
        $sessao->ID_UNIDADE = $this->getIdUnidade();
        $sessao->DIRETORIA = $this->getNomeUnidade();
        Zend_Auth::getInstance()->getStorage()->write($sessao);
        return $sessao;
    }

    /**
     * @return string
     */
    public function getLinkChangeUnidade() {

        try {
            if ($this->isAllowed(Zend_Auth::getInstance()->getStorage()->read()->ID, 115)) {
                return 'administrador_selecionar_unidade.php';
            }
        } catch (Exception $e) {
            return 'usuario_selecionar_unidade.php';
        }

        return 'usuario_selecionar_unidade.php';
    }

}